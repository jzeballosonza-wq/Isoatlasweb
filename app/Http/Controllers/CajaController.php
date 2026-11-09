<?php

namespace App\Http\Controllers;

use App\Models\CajaSesion;
use App\Models\CajaMovimiento;
use App\Models\Venta;
use App\Models\Bitacora;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CajaController extends Controller
{
    /**
     * 🛡️ Verificar si el usuario autenticado tiene permisos para el módulo de caja
     */
    protected function autorizarAccesoCaja(): bool
    {
        $user = Auth::user();
        if (!$user || (!$user->esAdmin() && !$user->tienePermiso('caja'))) {
            return false;
        }
        return true;
    }

    /**
     * 💵 Panel Principal de Control y Arqueo de Caja
     */
    public function index(Request $request)
    {
        if (!$this->autorizarAccesoCaja()) {
            return redirect()->route('dashboard')
                ->with('error', 'Acceso denegado: No tiene permisos para acceder al módulo de Arqueo y Control de Caja. Este módulo debe ser habilitado por el Administrador.');
        }

        // 1. Obtener sesión de caja actualmente abierta (si existe)
        $cajaAbierta = CajaSesion::where('estado', 'abierta')
            ->with(['usuarioApertura', 'movimientos.usuario'])
            ->latest('id')
            ->first();

        $ventasTurno = collect();
        $ventasTurnoTotal = 0;
        $ingresosExtraTurno = 0;
        $egresosGastosTurno = 0;
        $saldoEsperadoEnVivo = 0;

        if ($cajaAbierta) {
            // Asociar cualquier venta realizada durante este turno que haya quedado sin id
            Venta::where('created_at', '>=', $cajaAbierta->fecha_apertura)
                ->whereNull('caja_sesion_id')
                ->update(['caja_sesion_id' => $cajaAbierta->id]);

            $ventasTurno = Venta::where('caja_sesion_id', $cajaAbierta->id)->latest('id')->get();
            $ventasTurnoTotal = (float) $ventasTurno->sum('total');

            $ingresosExtraTurno = (float) $cajaAbierta->movimientos()->where('tipo', 'ingreso')->sum('monto');
            $egresosGastosTurno = (float) $cajaAbierta->movimientos()->where('tipo', 'egreso')->sum('monto');

            $saldoEsperadoEnVivo = (float) (
                $cajaAbierta->monto_inicial +
                $ventasTurnoTotal +
                $ingresosExtraTurno -
                $egresosGastosTurno
            );
        }

        // 2. Historial de sesiones de caja anteriores (arqueos cerrados)
        $query = CajaSesion::where('estado', 'cerrada')
            ->with(['usuarioApertura', 'usuarioCierre']);

        // Filtro por rango de fechas
        if ($request->filled('fecha_desde')) {
            $query->whereDate('fecha_apertura', '>=', $request->fecha_desde);
        }
        if ($request->filled('fecha_hasta')) {
            $query->whereDate('fecha_apertura', '<=', $request->fecha_hasta);
        }

        // Filtro por resultado de cuadre
        if ($request->filled('resultado')) {
            if ($request->resultado === 'cuadrada') {
                $query->whereRaw('ABS(diferencia) < 0.01');
            } elseif ($request->resultado === 'faltante') {
                $query->where('diferencia', '<', -0.01);
            } elseif ($request->resultado === 'sobrante') {
                $query->where('diferencia', '>', 0.01);
            }
        }

        // Búsqueda por cajero o caja
        if ($request->filled('buscar')) {
            $buscar = trim($request->buscar);
            $query->where(function($q) use ($buscar) {
                $q->where('caja_nombre', 'like', "%{$buscar}%")
                  ->orWhereHas('usuarioApertura', fn($qu) => $qu->where('name', 'like', "%{$buscar}%"))
                  ->orWhereHas('usuarioCierre', fn($qu) => $qu->where('name', 'like', "%{$buscar}%"));
            });
        }

        $perPage = in_array($request->per_page, [10, 20, 50]) ? (int)$request->per_page : 10;
        $historialCajas = $query->latest('id')->paginate($perPage)->withQueryString();

        // 3. KPIs Históricos
        $totalCierres = CajaSesion::where('estado', 'cerrada')->count();
        $totalVentasHistorico = CajaSesion::where('estado', 'cerrada')->sum('total_ventas_efectivo');
        $cierresCuadrados = CajaSesion::where('estado', 'cerrada')->whereRaw('ABS(diferencia) < 0.01')->count();
        $cierresConDescuadre = CajaSesion::where('estado', 'cerrada')->whereRaw('ABS(diferencia) >= 0.01')->count();

        return view('caja.index', compact(
            'cajaAbierta',
            'ventasTurno',
            'ventasTurnoTotal',
            'ingresosExtraTurno',
            'egresosGastosTurno',
            'saldoEsperadoEnVivo',
            'historialCajas',
            'totalCierres',
            'totalVentasHistorico',
            'cierresCuadrados',
            'cierresConDescuadre'
        ));
    }

    /**
     * 🔓 Aperturar un nuevo turno de caja
     */
    public function aperturar(Request $request)
    {
        if (!$this->autorizarAccesoCaja()) {
            return redirect()->route('dashboard')
                ->with('error', 'Acceso denegado: No tiene permisos para aperturar turnos de caja.');
        }

        $request->validate([
            'caja_nombre'            => 'required|string|max:100',
            'monto_inicial'          => 'required|numeric|min:0',
            'observaciones_apertura' => 'nullable|string|max:500'
        ], [
            'caja_nombre.required'   => 'Debe especificar el nombre o punto de la caja.',
            'monto_inicial.required' => 'El monto inicial o saldo base en efectivo es obligatorio.',
            'monto_inicial.min'      => 'El monto inicial no puede ser negativo.'
        ]);

        // Verificar si ya existe una caja abierta en el sistema
        $cajaExistente = CajaSesion::where('estado', 'abierta')->first();
        if ($cajaExistente) {
            return back()->with('error', "Ya existe una caja abierta actualmente ('{$cajaExistente->caja_nombre}' por {$cajaExistente->usuarioApertura->name}). Debe realizar el arqueo y cierre de la caja activa antes de aperturar una nueva.");
        }

        $caja = CajaSesion::create([
            'caja_nombre'            => trim($request->caja_nombre),
            'user_id'                => Auth::id(),
            'monto_inicial'          => (float) $request->monto_inicial,
            'fecha_apertura'         => now(),
            'estado'                 => 'abierta',
            'observaciones_apertura' => $request->observaciones_apertura
        ]);

        Bitacora::create([
            'user_id'     => Auth::id(),
            'modulo'      => 'Caja',
            'accion'      => 'Apertura de Caja',
            'descripcion' => "Aperturó la caja '{$caja->caja_nombre}' con saldo inicial de Bs. " . number_format($caja->monto_inicial, 2),
            'ip'          => $request->ip(),
        ]);

        return redirect()->route('caja.index')
            ->with('success', "¡Caja '{$caja->caja_nombre}' aperturada exitosamente con Bs. " . number_format($caja->monto_inicial, 2) . " en efectivo!");
    }

    /**
     * 💸 Registrar movimiento manual en caja (Ingreso extra o Retiro/Gasto menor)
     */
    public function storeMovimiento(Request $request)
    {
        if (!$this->autorizarAccesoCaja()) {
            return redirect()->route('dashboard')
                ->with('error', 'Acceso denegado: No tiene permisos para registrar movimientos en caja.');
        }

        $request->validate([
            'caja_sesion_id' => 'required|exists:caja_sesiones,id',
            'tipo'           => 'required|in:ingreso,egreso',
            'concepto'       => 'required|string|max:255',
            'monto'          => 'required|numeric|min:0.5',
            'comprobante'    => 'nullable|string|max:100',
            'observacion'    => 'nullable|string|max:500'
        ], [
            'tipo.required'     => 'Seleccione si es un ingreso de efectivo o un egreso/retiro.',
            'concepto.required' => 'Especifique el motivo o concepto del movimiento.',
            'monto.required'    => 'El monto es obligatorio.',
            'monto.min'         => 'El monto mínimo permitido es de Bs. 0.50.'
        ]);

        $caja = CajaSesion::where('id', $request->caja_sesion_id)->where('estado', 'abierta')->firstOrFail();

        $movimiento = CajaMovimiento::create([
            'caja_sesion_id' => $caja->id,
            'user_id'        => Auth::id(),
            'tipo'           => $request->tipo,
            'concepto'       => trim($request->concepto),
            'monto'          => (float) $request->monto,
            'comprobante'    => $request->comprobante,
            'observacion'    => $request->observacion
        ]);

        $tipoTexto = $request->tipo === 'ingreso' ? 'Ingreso extra de efectivo' : 'Retiro / Gasto de caja menor';
        $signo = $request->tipo === 'ingreso' ? '+' : '-';

        Bitacora::create([
            'user_id'     => Auth::id(),
            'modulo'      => 'Caja',
            'accion'      => "Movimiento de Caja ({$request->tipo})",
            'descripcion' => "Registró {$tipoTexto} de {$signo}Bs. " . number_format($movimiento->monto, 2) . " por concepto: '{$movimiento->concepto}' en {$caja->caja_nombre}",
            'ip'          => $request->ip(),
        ]);

        return redirect()->route('caja.index')
            ->with('success', "¡{$tipoTexto} registrado correctamente! {$signo}Bs. " . number_format($movimiento->monto, 2));
    }

    /**
     * 🔒 Realizar Arqueo y Cierre Definitivo de Caja
     */
    public function cerrar(Request $request, $id)
    {
        if (!$this->autorizarAccesoCaja()) {
            return redirect()->route('dashboard')
                ->with('error', 'Acceso denegado: No tiene permisos para realizar el arqueo y cierre de caja.');
        }

        $caja = CajaSesion::where('id', $id)->where('estado', 'abierta')->firstOrFail();

        $request->validate([
            'monto_real'           => 'required|numeric|min:0',
            'observaciones_cierre' => 'nullable|string|max:1000',
            // Desglose de billetes y monedas (opcional)
            'b200'                 => 'nullable|integer|min:0',
            'b100'                 => 'nullable|integer|min:0',
            'b50'                  => 'nullable|integer|min:0',
            'b20'                  => 'nullable|integer|min:0',
            'b10'                  => 'nullable|integer|min:0',
            'm5'                   => 'nullable|integer|min:0',
            'm2'                   => 'nullable|integer|min:0',
            'm1'                   => 'nullable|integer|min:0',
            'm050'                 => 'nullable|integer|min:0',
        ], [
            'monto_real.required'  => 'El monto físico real contado en caja es obligatorio para el arqueo.',
            'monto_real.min'       => 'El monto real contado no puede ser negativo.'
        ]);

        return DB::transaction(function() use ($request, $caja) {
            // Asegurar que todas las ventas del turno estén vinculadas
            Venta::where('created_at', '>=', $caja->fecha_apertura)
                ->whereNull('caja_sesion_id')
                ->update(['caja_sesion_id' => $caja->id]);

            // Cálculos del turno
            $ventasTotal = (float) Venta::where('caja_sesion_id', $caja->id)->sum('total');
            $ingresosExtra = (float) $caja->movimientos()->where('tipo', 'ingreso')->sum('monto');
            $egresosGastos = (float) $caja->movimientos()->where('tipo', 'egreso')->sum('monto');

            $montoEsperado = (float) ($caja->monto_inicial + $ventasTotal + $ingresosExtra - $egresosGastos);
            $montoReal = (float) $request->monto_real;
            $diferencia = (float) ($montoReal - $montoEsperado);

            // Armar desglose de billetes y monedas
            $desglose = [
                'b200'  => (int) $request->input('b200', 0),
                'b100'  => (int) $request->input('b100', 0),
                'b50'   => (int) $request->input('b50', 0),
                'b20'   => (int) $request->input('b20', 0),
                'b10'   => (int) $request->input('b10', 0),
                'm5'    => (int) $request->input('m5', 0),
                'm2'    => (int) $request->input('m2', 0),
                'm1'    => (int) $request->input('m1', 0),
                'm050'  => (int) $request->input('m050', 0),
            ];

            // Actualizar registro de la caja
            $caja->update([
                'fecha_cierre'           => now(),
                'user_cierre_id'         => Auth::id(),
                'total_ventas_efectivo'  => $ventasTotal,
                'total_ingresos_extra'   => $ingresosExtra,
                'total_egresos_gastos'   => $egresosGastos,
                'monto_esperado'         => $montoEsperado,
                'monto_real'             => $montoReal,
                'diferencia'             => $diferencia,
                'estado'                 => 'cerrada',
                'desglose_billetes'      => $desglose,
                'observaciones_cierre'   => $request->observaciones_cierre
            ]);

            // Mensaje según resultado
            if (abs($diferencia) < 0.01) {
                $resultadoTexto = "¡Caja Cuadrada Perfecta! (Diferencia: Bs. 0.00)";
            } elseif ($diferencia > 0) {
                $resultadoTexto = "Arqueo cerrado con SOBRANTE de +Bs. " . number_format($diferencia, 2);
            } else {
                $resultadoTexto = "Arqueo cerrado con FALTANTE de -Bs. " . number_format(abs($diferencia), 2);
            }

            Bitacora::create([
                'user_id'     => Auth::id(),
                'modulo'      => 'Caja',
                'accion'      => 'Cierre y Arqueo de Caja',
                'descripcion' => "Cerró la caja '{$caja->caja_nombre}'. Esperado: Bs. " . number_format($montoEsperado, 2) . " | Real contado: Bs. " . number_format($montoReal, 2) . " | Diferencia: Bs. " . number_format($diferencia, 2) . ". {$resultadoTexto}",
                'ip'          => $request->ip(),
            ]);

            return redirect()->route('caja.show', $caja->id)
                ->with('success', "¡Arqueo y Cierre de Caja completado con éxito! {$resultadoTexto}");
        });
    }

    /**
     * 📄 Ver Comprobante y Detalle del Arqueo de Caja
     */
    public function show($id)
    {
        if (!$this->autorizarAccesoCaja()) {
            return redirect()->route('dashboard')
                ->with('error', 'Acceso denegado: No tiene permisos para consultar comprobantes de arqueo.');
        }

        $caja = CajaSesion::with(['usuarioApertura', 'usuarioCierre', 'movimientos.usuario', 'ventas.detalles.producto'])
            ->findOrFail($id);

        return view('caja.show', compact('caja'));
    }

    /**
     * 🖨️ Vista Imprimible / Ticket del Arqueo
     */
    public function imprimir($id)
    {
        if (!$this->autorizarAccesoCaja()) {
            return redirect()->route('dashboard')
                ->with('error', 'Acceso denegado: No tiene permisos para imprimir comprobantes de arqueo.');
        }

        $caja = CajaSesion::with(['usuarioApertura', 'usuarioCierre', 'movimientos.usuario', 'ventas.detalles.producto'])
            ->findOrFail($id);

        return view('caja.imprimir', compact('caja'));
    }
}
