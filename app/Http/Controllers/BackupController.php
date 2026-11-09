<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Bitacora;
use Carbon\Carbon;

class BackupController extends Controller
{
    /**
     * 🛡️ Verificar autorización para el módulo de copias de seguridad
     */
    protected function autorizarAcceso(): bool
    {
        $user = Auth::user();
        if (!$user || (!$user->esAdmin() && !$user->tienePermiso('backups'))) {
            return false;
        }
        return true;
    }

    /**
     * 📋 Listado de Copias de Seguridad con Métricas KPI
     */
    public function index()
    {
        if (!$this->autorizarAcceso()) {
            return redirect()->route('dashboard')
                ->with('error', 'Acceso denegado: No tiene permisos para acceder al módulo de Copias de Seguridad.');
        }

        $ruta = storage_path('app/backups');

        if (!File::exists($ruta)) {
            File::makeDirectory($ruta, 0755, true);
        }

        // Obtener archivos .sql y mapearlos con metadatos
        $archivosRaw = File::files($ruta);
        $totalBytes = 0;

        $archivos = collect($archivosRaw)
            ->filter(function ($archivo) {
                return in_array(strtolower($archivo->getExtension()), ['sql', 'gz']);
            })
            ->map(function ($archivo) use (&$totalBytes) {
                $size = $archivo->getSize();
                $totalBytes += $size;
                $mtime = $archivo->getMTime();

                return [
                    'nombre' => $archivo->getFilename(),
                    'fecha' => date('d/m/Y H:i:s', $mtime),
                    'hace_tiempo' => Carbon::createFromTimestamp($mtime)->diffForHumans(),
                    'tamano' => $this->formatBytes($size),
                    'tamano_raw' => $size,
                    'timestamp' => $mtime
                ];
            })
            ->sortByDesc('timestamp')
            ->values();

        // 📊 Métricas KPI del Sistema de Respaldo
        $totalBackups = $archivos->count();
        $totalEspacio = $this->formatBytes($totalBytes);
        $ultimoBackup = $archivos->first();

        // 🗄️ Estadísticas de la Base de Datos Activa
        $dbName = config('database.connections.mysql.database', env('DB_DATABASE', 'sistema_laptops'));
        $tablasCount = 0;
        $dbSizeMB = '0.00 MB';

        try {
            $tablas = DB::select("SELECT COUNT(*) as total FROM information_schema.tables WHERE table_schema = ?", [$dbName]);
            $tablasCount = $tablas[0]->total ?? 0;

            $sizeQuery = DB::select("
                SELECT SUM(data_length + index_length) / 1024 / 1024 AS size_mb 
                FROM information_schema.TABLES 
                WHERE table_schema = ?
            ", [$dbName]);
            
            if (!empty($sizeQuery[0]->size_mb)) {
                $dbSizeMB = number_format($sizeQuery[0]->size_mb, 2) . ' MB';
            }
        } catch (\Exception $e) {
            // Fallback silencioso si no hay permisos sobre information_schema
        }

        return view('backups.index', compact(
            'archivos',
            'totalBackups',
            'totalEspacio',
            'ultimoBackup',
            'dbName',
            'tablasCount',
            'dbSizeMB'
        ));
    }

    /**
     * 🚀 Crear Nueva Copia de Seguridad (Estrategia Híbrida: mysqldump + Pure PHP Fallback)
     */
    public function create(Request $request)
    {
        if (!$this->autorizarAcceso()) {
            return redirect()->route('dashboard')
                ->with('error', 'Acceso denegado: No tiene permisos para generar copias de seguridad.');
        }

        @set_time_limit(300);
        @ini_set('memory_limit', '512M');

        $ruta = storage_path('app/backups');

        if (!File::exists($ruta)) {
            File::makeDirectory($ruta, 0755, true);
        }

        $nombre = 'backup_' . date('Y_m_d_H_i_s') . '.sql';
        $archivo = $ruta . DIRECTORY_SEPARATOR . $nombre;

        $host = config('database.connections.mysql.host', env('DB_HOST', '127.0.0.1'));
        $puerto = config('database.connections.mysql.port', env('DB_PORT', '3306'));
        $base = config('database.connections.mysql.database', env('DB_DATABASE', 'sistema_laptops'));
        $usuario = config('database.connections.mysql.username', env('DB_USERNAME', 'root'));
        $password = config('database.connections.mysql.password', env('DB_PASSWORD', ''));

        $mysqldump = $this->findMysqldumpBinary();
        $generado = false;

        // Intentar primero con mysqldump.exe si está disponible
        if ($mysqldump && file_exists($mysqldump)) {
            $passParam = empty($password) ? '' : "--password=\"{$password}\"";
            $comando = "\"{$mysqldump}\" --host={$host} --port={$puerto} --user={$usuario} {$passParam} --routines --triggers {$base} > \"{$archivo}\"";

            @exec($comando, $salida, $resultado);
            if ($resultado === 0 && file_exists($archivo) && filesize($archivo) > 0) {
                $generado = true;
            }
        }

        // Si mysqldump no está o falló, utilizar el generador nativo PDO en PHP
        if (!$generado) {
            try {
                $this->generarDumpConPhp($archivo, $base);
                $generado = true;
            } catch (\Exception $e) {
                return redirect()->route('backups.index')
                    ->with('error', 'Error al generar respaldo: ' . $e->getMessage());
            }
        }

        if ($generado && file_exists($archivo)) {
            $tamanoFormateado = $this->formatBytes(filesize($archivo));

            Bitacora::create([
                'user_id' => Auth::id(),
                'modulo' => 'Copias de Seguridad',
                'accion' => 'Crear',
                'descripcion' => "Generó la copia de seguridad '{$nombre}' ({$tamanoFormateado})",
                'ip' => $request->ip(),
            ]);

            return redirect()->route('backups.index')
                ->with('success', "¡Copia de seguridad '{$nombre}' creada exitosamente ({$tamanoFormateado})!");
        }

        return redirect()->route('backups.index')
            ->with('error', 'No fue posible generar el archivo de respaldo.');
    }

    /**
     * 📥 Descargar Archivo SQL
     */
    public function download(Request $request, $archivo)
    {
        if (!$this->autorizarAcceso()) {
            return redirect()->route('dashboard')
                ->with('error', 'Acceso denegado: No tiene permisos para descargar copias de seguridad.');
        }

        $archivoLimpio = basename($archivo);
        $ruta = storage_path('app/backups/' . $archivoLimpio);

        if (!File::exists($ruta)) {
            return redirect()->route('backups.index')
                ->with('error', 'El archivo solicitado no existe en el almacenamiento.');
        }

        Bitacora::create([
            'user_id' => Auth::id(),
            'modulo' => 'Copias de Seguridad',
            'accion' => 'Descargar',
            'descripcion' => "Descargó el respaldo '{$archivoLimpio}'",
            'ip' => $request->ip(),
        ]);

        return Response::download($ruta, $archivoLimpio, [
            'Content-Type' => 'application/sql'
        ]);
    }

    /**
     * 📤 Subir Archivo de Respaldo Externo (.sql)
     */
    public function upload(Request $request)
    {
        if (!$this->autorizarAcceso()) {
            return redirect()->route('dashboard')
                ->with('error', 'Acceso denegado: No tiene permisos para subir copias de seguridad.');
        }

        @set_time_limit(300);
        @ini_set('memory_limit', '512M');

        $request->validate([
            'archivo_sql' => 'required|file|max:102400' // Hasta 100MB
        ], [
            'archivo_sql.required' => 'Debe seleccionar un archivo .sql.',
            'archivo_sql.max' => 'El archivo supera el límite permitido de 100MB.'
        ]);

        $file = $request->file('archivo_sql');
        $extension = strtolower($file->getClientOriginalExtension());

        if (!in_array($extension, ['sql', 'txt'])) {
            return redirect()->route('backups.index')
                ->with('error', 'Formato inválido. Solo se admiten archivos con extensión .sql');
        }

        $nombreOriginal = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $nombreLimpio = preg_replace('/[^A-Za-z0-9_\-]/', '_', $nombreOriginal);
        $nombreFinal = 'upload_' . $nombreLimpio . '_' . date('Ymd_His') . '.sql';

        $ruta = storage_path('app/backups');
        if (!File::exists($ruta)) {
            File::makeDirectory($ruta, 0755, true);
        }

        $file->move($ruta, $nombreFinal);

        Bitacora::create([
            'user_id' => Auth::id(),
            'modulo' => 'Copias de Seguridad',
            'accion' => 'Subir',
            'descripcion' => "Subió un archivo de respaldo externo: '{$nombreFinal}'",
            'ip' => $request->ip(),
        ]);

        return redirect()->route('backups.index')
            ->with('success', "¡Archivo '{$nombreFinal}' subido y disponible para restaurar!");
    }

    /**
     * 🔄 Restaurar Base de Datos desde Respaldo
     */
    public function restore(Request $request, $archivo)
    {
        if (!$this->autorizarAcceso()) {
            return redirect()->route('dashboard')
                ->with('error', 'Acceso denegado: No tiene permisos para restaurar la base de datos.');
        }

        @set_time_limit(300);
        @ini_set('memory_limit', '512M');

        $archivoLimpio = basename($archivo);
        $ruta = storage_path('app/backups/' . $archivoLimpio);

        if (!File::exists($ruta)) {
            return redirect()->route('backups.index')
                ->with('error', 'El archivo de respaldo no existe.');
        }

        $host = config('database.connections.mysql.host', env('DB_HOST', '127.0.0.1'));
        $puerto = config('database.connections.mysql.port', env('DB_PORT', '3306'));
        $base = config('database.connections.mysql.database', env('DB_DATABASE', 'sistema_laptops'));
        $usuario = config('database.connections.mysql.username', env('DB_USERNAME', 'root'));
        $password = config('database.connections.mysql.password', env('DB_PASSWORD', ''));

        $mysql = $this->findMysqlBinary();
        $restaurado = false;

        // Intentar con binario mysql.exe
        if ($mysql && file_exists($mysql)) {
            $passParam = empty($password) ? '' : "--password=\"{$password}\"";
            $comando = "\"{$mysql}\" --host={$host} --port={$puerto} --user={$usuario} {$passParam} {$base} < \"{$ruta}\"";

            @exec($comando, $salida, $resultado);
            if ($resultado === 0) {
                $restaurado = true;
            }
        }

        // Si falló mysql binario, restaurar vía PDO nativo
        if (!$restaurado) {
            try {
                $sqlContent = File::get($ruta);
                DB::statement('SET FOREIGN_KEY_CHECKS=0;');
                DB::unprepared($sqlContent);
                DB::statement('SET FOREIGN_KEY_CHECKS=1;');
                $restaurado = true;
            } catch (\Exception $e) {
                DB::statement('SET FOREIGN_KEY_CHECKS=1;');
                return redirect()->route('backups.index')
                    ->with('error', 'Error durante la restauración PDO: ' . $e->getMessage());
            }
        }

        if ($restaurado) {
            Bitacora::create([
                'user_id' => Auth::id(),
                'modulo' => 'Copias de Seguridad',
                'accion' => 'Restaurar',
                'descripcion' => "Restauró la base de datos a partir de: '{$archivoLimpio}'",
                'ip' => $request->ip(),
            ]);

            return redirect()->route('backups.index')
                ->with('success', "¡Base de datos restaurada con éxito desde '{$archivoLimpio}'!");
        }

        return redirect()->route('backups.index')
            ->with('error', 'No fue posible completar la restauración de la base de datos.');
    }

    /**
     * 🗑️ Eliminar Archivo de Respaldo
     */
    public function delete(Request $request, $archivo)
    {
        if (!$this->autorizarAcceso()) {
            return redirect()->route('dashboard')
                ->with('error', 'Acceso denegado: No tiene permisos para eliminar copias de seguridad.');
        }

        $archivoLimpio = basename($archivo);
        $ruta = storage_path('app/backups/' . $archivoLimpio);

        if (File::exists($ruta)) {
            File::delete($ruta);

            Bitacora::create([
                'user_id' => Auth::id(),
                'modulo' => 'Copias de Seguridad',
                'accion' => 'Eliminar',
                'descripcion' => "Eliminó el archivo de respaldo: '{$archivoLimpio}'",
                'ip' => $request->ip(),
            ]);

            return redirect()->route('backups.index')
                ->with('success', "Copia de seguridad '{$archivoLimpio}' eliminada.");
        }

        return redirect()->route('backups.index')
            ->with('error', 'El archivo no fue encontrado para eliminar.');
    }

    // ==========================================
    // 🛠️ MÉTODOS AUXILIARES Y DUMPERS EN PHP
    // ==========================================

    /**
     * Busca la ruta de mysqldump en Windows y Linux
     */
    private function findMysqldumpBinary()
    {
        $posibles = [
            'C:\xampp\mysql\bin\mysqldump.exe',
            'D:\xampp\mysql\bin\mysqldump.exe',
            'C:\wamp64\bin\mysql\mysql*\bin\mysqldump.exe',
            'C:\Program Files\MySQL\MySQL Server *\bin\mysqldump.exe',
            '/usr/bin/mysqldump',
            '/usr/local/bin/mysqldump'
        ];

        foreach ($posibles as $ruta) {
            $coincidencias = glob($ruta);
            if (!empty($coincidencias) && file_exists($coincidencias[0])) {
                return $coincidencias[0];
            }
        }

        return null;
    }

    /**
     * Busca la ruta de mysql CLI en Windows y Linux
     */
    private function findMysqlBinary()
    {
        $posibles = [
            'C:\xampp\mysql\bin\mysql.exe',
            'D:\xampp\mysql\bin\mysql.exe',
            'C:\wamp64\bin\mysql\mysql*\bin\mysql.exe',
            'C:\Program Files\MySQL\MySQL Server *\bin\mysql.exe',
            '/usr/bin/mysql',
            '/usr/local/bin/mysql'
        ];

        foreach ($posibles as $ruta) {
            $coincidencias = glob($ruta);
            if (!empty($coincidencias) && file_exists($coincidencias[0])) {
                return $coincidencias[0];
            }
        }

        return null;
    }

    /**
     * Generador nativo de Dump SQL usando PDO (Fallback 100% confiable)
     */
    private function generarDumpConPhp($archivoDestino, $nombreDb)
    {
        $handle = fopen($archivoDestino, 'w+');
        if (!$handle) {
            throw new \Exception('No se pudo crear el archivo de respaldo en disco.');
        }

        $header = "-- ========================================================\n";
        $header .= "-- SISTEMA DE VENTAS E INVENTARIO - TIENDA ELECTRÓNICA\n";
        $header .= "-- Respaldo Generado Automáticamente (PHP PDO Dump)\n";
        $header .= "-- Fecha: " . date('Y-m-d H:i:s') . "\n";
        $header .= "-- Base de Datos: {$nombreDb}\n";
        $header .= "-- ========================================================\n\n";
        $header .= "SET FOREIGN_KEY_CHECKS=0;\n";
        $header .= "SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';\n";
        $header .= "SET NAMES utf8mb4;\n\n";

        fwrite($handle, $header);

        $tables = DB::select('SHOW FULL TABLES WHERE Table_type = "BASE TABLE"');
        $dbKey = "Tables_in_" . $nombreDb;

        foreach ($tables as $t) {
            $tableName = isset($t->$dbKey) ? $t->$dbKey : array_values((array)$t)[0];

            fwrite($handle, "-- --------------------------------------------------------\n");
            fwrite($handle, "-- Estructura de la tabla `{$tableName}`\n");
            fwrite($handle, "-- --------------------------------------------------------\n");
            fwrite($handle, "DROP TABLE IF EXISTS `{$tableName}`;\n");

            $createTable = DB::select("SHOW CREATE TABLE `{$tableName}`");
            $ddl = $createTable[0]->{'Create Table'} ?? array_values((array)$createTable[0])[1];
            fwrite($handle, $ddl . ";\n\n");

            // Volcado de datos
            $rows = DB::table($tableName)->get();
            if ($rows->count() > 0) {
                fwrite($handle, "-- Datos de la tabla `{$tableName}`\n");
                
                foreach ($rows->chunk(100) as $chunk) {
                    $insertSql = "INSERT INTO `{$tableName}` VALUES ";
                    $valueRows = [];

                    foreach ($chunk as $row) {
                        $values = array_map(function ($val) {
                            if (is_null($val)) return "NULL";
                            return "'" . addslashes($val) . "'";
                        }, (array)$row);

                        $valueRows[] = "(" . implode(", ", $values) . ")";
                    }

                    $insertSql .= implode(",\n", $valueRows) . ";\n";
                    fwrite($handle, $insertSql);
                }
                fwrite($handle, "\n");
            }
        }

        fwrite($handle, "SET FOREIGN_KEY_CHECKS=1;\n");
        fclose($handle);
    }

    /**
     * Formatea bytes en unidades legibles
     */
    private function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);

        return round($bytes, $precision) . ' ' . $units[$pow];
    }
}