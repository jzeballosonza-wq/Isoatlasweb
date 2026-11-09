<?php

namespace App\Http\Controllers;

use App\Models\Bitacora;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Symfony\Component\HttpFoundation\StreamedResponse;

class BitacoraController extends Controller
{
    /**
     * 📋 Listado de Auditoría y Bitácora con KPIs y Filtros Avanzados
     */
    public function index(Request $request)
    {
        $query = Bitacora::with('usuario.rol');

        // 1. Búsqueda de texto libre (descripción, módulo, acción, IP, nombre de usuario o email)
        if ($request->filled('buscar')) {
            $buscar = trim($request->buscar);
            $query->where(function ($q) use ($buscar) {
                $q->where('descripcion', 'like', "%{$buscar}%")
                  ->orWhere('modulo', 'like', "%{$buscar}%")
                  ->orWhere('accion', 'like', "%{$buscar}%")
                  ->orWhere('ip', 'like', "%{$buscar}%")
                  ->orWhere('id', 'like', "%{$buscar}%")
                  ->orWhereHas('usuario', function ($qu) use ($buscar) {
                      $qu->where('name', 'like', "%{$buscar}%")
                         ->orWhere('email', 'like', "%{$buscar}%");
                  });
            });
        }

        // 2. Filtro por Módulo
        if ($request->filled('modulo')) {
            $query->where('modulo', $request->modulo);
        }

        // 3. Filtro por Acción
        if ($request->filled('accion')) {
            $query->where('accion', $request->accion);
        }

        // 4. Filtro por Usuario
        if ($request->filled('user_id')) {
            if ($request->user_id === 'sistema') {
                $query->whereNull('user_id');
            } else {
                $query->where('user_id', $request->user_id);
            }
        }

        // 5. Filtro rápido por rango de tiempo preestablecido
        if ($request->filled('rango_rapido')) {
            switch ($request->rango_rapido) {
                case 'hoy':
                    $query->whereDate('created_at', Carbon::today());
                    break;
                case 'esta_semana':
                    $query->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
                    break;
                case 'este_mes':
                    $query->whereMonth('created_at', Carbon::now()->month)
                          ->whereYear('created_at', Carbon::now()->year);
                    break;
                case 'ultimos_30_dias':
                    $query->where('created_at', '>=', Carbon::now()->subDays(30));
                    break;
            }
        }

        // 6. Filtro por rango de fechas manual
        if ($request->filled('fecha_desde')) {
            $query->whereDate('created_at', '>=', $request->fecha_desde);
        }

        if ($request->filled('fecha_hasta')) {
            $query->whereDate('created_at', '<=', $request->fecha_hasta);
        }

        // 7. Ordenamiento
        $orden = $request->get('orden', 'recientes');
        switch ($orden) {
            case 'antiguos':
                $query->oldest('created_at')->oldest('id');
                break;
            case 'modulo_asc':
                $query->orderBy('modulo', 'asc')->latest('created_at');
                break;
            case 'modulo_desc':
                $query->orderBy('modulo', 'desc')->latest('created_at');
                break;
            case 'accion_asc':
                $query->orderBy('accion', 'asc')->latest('created_at');
                break;
            case 'recientes':
            default:
                $query->latest('created_at')->latest('id');
                break;
        }

        // 8. Paginación con persistencia de parámetros
        $perPage = in_array((int)$request->per_page, [10, 15, 25, 50, 100]) ? (int)$request->per_page : 15;
        $bitacoras = $query->paginate($perPage)->withQueryString();

        // 📊 Métricas KPI para el encabezado
        $totalRegistros = Bitacora::count();
        $registrosHoy = Bitacora::whereDate('created_at', Carbon::today())->count();
        
        $accionesCriticas = Bitacora::where(function ($q) {
            $q->where('accion', 'like', '%elimin%')
              ->orWhere('accion', 'like', '%borr%')
              ->orWhere('accion', 'like', '%restaur%');
        })->count();

        $moduloMasActivoData = Bitacora::select('modulo', DB::raw('count(*) as total'))
            ->groupBy('modulo')
            ->orderByDesc('total')
            ->first();

        $moduloMasActivo = $moduloMasActivoData ? $moduloMasActivoData->modulo : 'N/A';
        $moduloMasActivoCount = $moduloMasActivoData ? $moduloMasActivoData->total : 0;

        $usuariosActivosCount = Bitacora::whereNotNull('user_id')->distinct('user_id')->count('user_id');

        // 🏷️ Listas para los dropdowns de filtrado
        $modulosBase = [
            'Ventas', 'Compras', 'Productos', 'Categorías',
            'Clientes', 'Proveedores', 'Usuarios', 'Roles',
            'Copias de Seguridad', 'Autenticación'
        ];
        $modulosDb = Bitacora::distinct()->pluck('modulo')->filter()->toArray();
        $modulosList = collect(array_unique(array_merge($modulosBase, $modulosDb)))->sort()->values();

        $accionesList = Bitacora::distinct()->pluck('accion')->filter()->sort()->values();
        $usuariosList = User::orderBy('name', 'asc')->get(['id', 'name', 'email']);

        return view('bitacoras.index', compact(
            'bitacoras',
            'totalRegistros',
            'registrosHoy',
            'accionesCriticas',
            'moduloMasActivo',
            'moduloMasActivoCount',
            'usuariosActivosCount',
            'modulosList',
            'accionesList',
            'usuariosList'
        ));
    }

    /**
     * 👁️ Detalle de una entrada de bitácora (Vista o JSON para Modal AJAX)
     */
    public function show(Request $request, $id)
    {
        $bitacora = Bitacora::with('usuario.rol')->findOrFail($id);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'bitacora' => [
                    'id' => $bitacora->id,
                    'modulo' => $bitacora->modulo,
                    'accion' => $bitacora->accion,
                    'descripcion' => $bitacora->descripcion ?: 'Sin descripción detallada.',
                    'ip' => $bitacora->ip ?: '127.0.0.1',
                    'created_at' => $bitacora->created_at ? $bitacora->created_at->format('d/m/Y H:i:s') : '-',
                    'tiempo_relativo' => $bitacora->tiempo_relativo,
                    'badge' => $bitacora->badge_info,
                    'modulo_info' => $bitacora->modulo_info,
                    'usuario' => $bitacora->usuario ? [
                        'id' => $bitacora->usuario->id,
                        'name' => $bitacora->usuario->name,
                        'email' => $bitacora->usuario->email,
                        'rol' => $bitacora->usuario->rol->nombre ?? 'Usuario Registrado',
                        'iniciales' => $bitacora->usuario->iniciales,
                        'avatar_gradient' => $bitacora->usuario->avatar_gradient
                    ] : [
                        'id' => null,
                        'name' => 'Sistema / Automático',
                        'email' => 'sistema@isoatlas.local',
                        'rol' => 'Proceso del Sistema',
                        'iniciales' => 'SI',
                        'avatar_gradient' => 'linear-gradient(135deg, #475569 0%, #1e293b 100%)'
                    ]
                ]
            ]);
        }

        return view('bitacoras.show', compact('bitacora'));
    }

    /**
     * 📥 Exportar registros filtrados a formato CSV (Compatible con Excel UTF-8)
     */
    public function exportar(Request $request)
    {
        $query = Bitacora::with('usuario.rol');

        // Aplicar los mismos filtros que en index
        if ($request->filled('buscar')) {
            $buscar = trim($request->buscar);
            $query->where(function ($q) use ($buscar) {
                $q->where('descripcion', 'like', "%{$buscar}%")
                  ->orWhere('modulo', 'like', "%{$buscar}%")
                  ->orWhere('accion', 'like', "%{$buscar}%")
                  ->orWhere('ip', 'like', "%{$buscar}%")
                  ->orWhere('id', 'like', "%{$buscar}%")
                  ->orWhereHas('usuario', function ($qu) use ($buscar) {
                      $qu->where('name', 'like', "%{$buscar}%")
                         ->orWhere('email', 'like', "%{$buscar}%");
                  });
            });
        }

        if ($request->filled('modulo')) {
            $query->where('modulo', $request->modulo);
        }

        if ($request->filled('accion')) {
            $query->where('accion', $request->accion);
        }

        if ($request->filled('user_id')) {
            if ($request->user_id === 'sistema') {
                $query->whereNull('user_id');
            } else {
                $query->where('user_id', $request->user_id);
            }
        }

        if ($request->filled('rango_rapido')) {
            switch ($request->rango_rapido) {
                case 'hoy':
                    $query->whereDate('created_at', Carbon::today());
                    break;
                case 'esta_semana':
                    $query->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
                    break;
                case 'este_mes':
                    $query->whereMonth('created_at', Carbon::now()->month)
                          ->whereYear('created_at', Carbon::now()->year);
                    break;
                case 'ultimos_30_dias':
                    $query->where('created_at', '>=', Carbon::now()->subDays(30));
                    break;
            }
        }

        if ($request->filled('fecha_desde')) {
            $query->whereDate('created_at', '>=', $request->fecha_desde);
        }

        if ($request->filled('fecha_hasta')) {
            $query->whereDate('created_at', '<=', $request->fecha_hasta);
        }

        $query->latest('created_at')->latest('id');

        $filename = 'bitacora_auditoria_' . date('Y_m_d_His') . '.csv';

        // Registrar acción de exportación en bitácora
        if (Auth::check()) {
            Bitacora::create([
                'user_id'     => Auth::id(),
                'modulo'      => 'Bitácora',
                'accion'      => 'Exportar',
                'descripcion' => "Exportó el reporte de auditoría a archivo CSV ({$filename})",
                'ip'          => $request->ip(),
            ]);
        }

        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Pragma'              => 'no-cache',
            'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
            'Expires'             => '0',
        ];

        $callback = function () use ($query) {
            $file = fopen('php://output', 'w');

            // UTF-8 BOM para que Excel abra caracteres latinos y tildes correctamente
            fputs($file, "\xEF\xBB\xBF");

            // Encabezados del CSV
            fputcsv($file, [
                'ID',
                'Fecha y Hora',
                'Usuario Responsable',
                'Email',
                'Rol',
                'Módulo',
                'Acción',
                'Descripción del Evento',
                'Dirección IP'
            ], ';');

            // Procesar por lotes para no saturar memoria
            $query->chunk(500, function ($registros) use ($file) {
                foreach ($registros as $b) {
                    fputcsv($file, [
                        $b->id,
                        $b->created_at ? $b->created_at->format('d/m/Y H:i:s') : '-',
                        $b->usuario ? $b->usuario->name : 'Sistema',
                        $b->usuario ? $b->usuario->email : 'N/A',
                        $b->usuario && $b->usuario->rol ? $b->usuario->rol->nombre : 'Sin Rol',
                        $b->modulo,
                        $b->accion,
                        $b->descripcion,
                        $b->ip ?: '127.0.0.1'
                    ], ';');
                }
            });

            fclose($file);
        };

        return new StreamedResponse($callback, 200, $headers);
    }

    /**
     * 🧹 Depurar o limpiar registros antiguos de la bitácora (Solo Administradores)
     */
    public function limpiar(Request $request)
    {
        $request->validate([
            'periodo' => 'required|in:30,60,90,180,365,todos_excepto_100'
        ], [
            'periodo.required' => 'Debe seleccionar un período de antigüedad válido.',
            'periodo.in' => 'La opción de período seleccionada no es válida.'
        ]);

        if (!Auth::check() || !Auth::user()->esAdmin()) {
            return redirect()->route('bitacoras.index')
                ->with('error', 'Acción denegada: Solo los Administradores del sistema tienen privilegios para depurar la bitácora.');
        }

        $eliminados = 0;
        $criterioTexto = '';

        if ($request->periodo === 'todos_excepto_100') {
            $idsRecientes = Bitacora::latest('id')->take(100)->pluck('id')->toArray();
            $eliminados = Bitacora::whereNotIn('id', $idsRecientes)->delete();
            $criterioTexto = 'manteniendo solo los 100 registros más recientes';
        } else {
            $dias = (int)$request->periodo;
            $fechaLimite = Carbon::now()->subDays($dias);
            $eliminados = Bitacora::where('created_at', '<', $fechaLimite)->delete();
            $criterioTexto = "con más de {$dias} días de antigüedad (anteriores al {$fechaLimite->format('d/m/Y')})";
        }

        // Registrar la depuración en la propia bitácora
        Bitacora::create([
            'user_id'     => Auth::id(),
            'modulo'      => 'Bitácora',
            'accion'      => 'Eliminar',
            'descripcion' => "Depuró {$eliminados} registros antiguos de auditoría ({$criterioTexto})",
            'ip'          => $request->ip(),
        ]);

        return redirect()->route('bitacoras.index')
            ->with('success', "¡Depuración completada! Se eliminaron {$eliminados} registros antiguos de auditoría.");
    }
}