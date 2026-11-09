<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Categoria;
use App\Models\MovimientoInventario;
use App\Models\Bitacora;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class InventarioController extends Controller
{
    /**
     * 📦 Vista Principal del Centro de Control de Inventario & Kardex
     */
    public function index(Request $request)
    {
        $query = MovimientoInventario::with(['producto.categoria', 'usuario']);

        // 1. Filtro por Tipo de Movimiento (General o Específico)
        if ($request->filled('tipo_filtro')) {
            $tipoFiltro = $request->tipo_filtro;
            if ($tipoFiltro === 'ingresos') {
                $query->whereIn('tipo', ['compra', 'ajuste_positivo', 'anulacion_venta', 'inicial']);
            } elseif ($tipoFiltro === 'egresos') {
                $query->whereIn('tipo', ['venta', 'ajuste_negativo', 'anulacion_compra']);
            } elseif ($tipoFiltro === 'ajustes') {
                $query->whereIn('tipo', ['ajuste_positivo', 'ajuste_negativo']);
            } elseif ($tipoFiltro === 'compras') {
                $query->where('tipo', 'compra');
            } elseif ($tipoFiltro === 'ventas') {
                $query->where('tipo', 'venta');
            }
        }

        // 2. Filtro por Producto Específico
        if ($request->filled('producto_id')) {
            $query->where('producto_id', $request->producto_id);
        }

        // 3. Filtro por Categoría del Producto
        if ($request->filled('categoria_id')) {
            $query->whereHas('producto', function ($qp) use ($request) {
                $qp->where('categoria_id', $request->categoria_id);
            });
        }

        // 4. Filtro por Rango de Fechas
        if ($request->filled('fecha_desde')) {
            $query->whereDate('created_at', '>=', $request->fecha_desde);
        }
        if ($request->filled('fecha_hasta')) {
            $query->whereDate('created_at', '<=', $request->fecha_hasta);
        }

        // 5. Búsqueda por texto (SKU, Producto, Motivo o Usuario)
        if ($request->filled('buscar')) {
            $buscar = trim($request->buscar);
            $query->where(function ($q) use ($buscar) {
                $q->where('motivo', 'like', "%{$buscar}%")
                  ->orWhereHas('producto', function ($qp) use ($buscar) {
                      $qp->where('nombre', 'like', "%{$buscar}%")
                         ->orWhere('codigo', 'like', "%{$buscar}%");
                  })
                  ->orWhereHas('usuario', function ($qu) use ($buscar) {
                      $qu->where('name', 'like', "%{$buscar}%");
                  });
            });
        }

        $perPage = in_array($request->per_page, [15, 30, 50, 100]) ? (int)$request->per_page : 15;
        $movimientos = $query->latest('id')->paginate($perPage)->withQueryString();

        // 📊 Métricas Globales del Inventario
        $totalProductos = Producto::count();
        $totalUnidades = Producto::sum('stock') ?? 0;
        $inversionTotal = Producto::selectRaw('SUM(stock * precio_compra) as total')->value('total') ?? 0;
        $valorVentaTotal = Producto::selectRaw('SUM(stock * precio_venta) as total')->value('total') ?? 0;
        
        $stockBajoCount = Producto::whereColumn('stock', '<=', 'stock_minimo')->where('stock', '>', 0)->count();
        $agotadosCount = Producto::where('stock', '<=', 0)->count();

        // Ingresos y Egresos del mes actual (en unidades)
        $mesActual = now()->month;
        $añoActual = now()->year;

        $ingresosMes = MovimientoInventario::whereIn('tipo', ['compra', 'ajuste_positivo', 'anulacion_venta', 'inicial'])
            ->whereMonth('created_at', $mesActual)
            ->whereYear('created_at', $añoActual)
            ->sum('cantidad') ?? 0;

        $egresosMes = MovimientoInventario::whereIn('tipo', ['venta', 'ajuste_negativo', 'anulacion_compra'])
            ->whereMonth('created_at', $mesActual)
            ->whereYear('created_at', $añoActual)
            ->sum('cantidad') ?? 0;

        // Listas para modales y filtros
        $productos = Producto::orderBy('nombre', 'asc')->get(['id', 'codigo', 'nombre', 'stock', 'precio_compra', 'precio_venta']);
        $categorias = Categoria::orderBy('nombre', 'asc')->get();

        return view('inventario.index', compact(
            'movimientos',
            'totalProductos',
            'totalUnidades',
            'inversionTotal',
            'valorVentaTotal',
            'stockBajoCount',
            'agotadosCount',
            'ingresosMes',
            'egresosMes',
            'productos',
            'categorias'
        ));
    }

    /**
     * 📦 MÓDULO UNIFICADO: Ingresos y Egresos de Stock (Movimientos de Inventario)
     */
    public function movimientos(Request $request)
    {
        $tab = $request->get('tab', 'todos');

        $query = MovimientoInventario::with(['producto.categoria', 'usuario']);

        // 1. Filtrado por Pestaña
        if ($tab === 'ingresos') {
            $query->whereIn('tipo', ['compra', 'ajuste_positivo', 'anulacion_venta', 'inicial']);
        } elseif ($tab === 'egresos') {
            $query->whereIn('tipo', ['venta', 'ajuste_negativo', 'anulacion_compra']);
        } elseif ($tab === 'mermas') {
            $query->where('tipo', 'ajuste_negativo');
        } elseif ($tab === 'compras') {
            $query->where('tipo', 'compra');
        } elseif ($tab === 'ventas') {
            $query->where('tipo', 'venta');
        }

        // 2. Filtro específico por Tipo de Movimiento
        if ($request->filled('tipo_especifico')) {
            $query->where('tipo', $request->tipo_especifico);
        }

        // 3. Filtro por Producto
        if ($request->filled('producto_id')) {
            $query->where('producto_id', $request->producto_id);
        }

        // 4. Filtro por Categoría
        if ($request->filled('categoria_id')) {
            $query->whereHas('producto', function ($qp) use ($request) {
                $qp->where('categoria_id', $request->categoria_id);
            });
        }

        // 5. Rango de Fechas
        if ($request->filled('fecha_desde')) {
            $query->whereDate('created_at', '>=', $request->fecha_desde);
        }
        if ($request->filled('fecha_hasta')) {
            $query->whereDate('created_at', '<=', $request->fecha_hasta);
        }

        // 6. Búsqueda por texto (SKU, Producto, Motivo o Usuario)
        if ($request->filled('buscar')) {
            $buscar = trim($request->buscar);
            $query->where(function ($q) use ($buscar) {
                $q->where('motivo', 'like', "%{$buscar}%")
                  ->orWhereHas('producto', function ($qp) use ($buscar) {
                      $qp->where('nombre', 'like', "%{$buscar}%")
                         ->orWhere('codigo', 'like', "%{$buscar}%");
                  })
                  ->orWhereHas('usuario', function ($qu) use ($buscar) {
                      $qu->where('name', 'like', "%{$buscar}%");
                  });
            });
        }

        $perPage = in_array($request->per_page, [15, 30, 50, 100]) ? (int)$request->per_page : 15;
        $movimientos = $query->latest('id')->paginate($perPage)->withQueryString();

        // 📊 KPIs Unificados del Mes Actual
        $mesActual = now()->month;
        $añoActual = now()->year;

        $ingresosMes = MovimientoInventario::whereIn('tipo', ['compra', 'ajuste_positivo', 'anulacion_venta', 'inicial'])
            ->whereMonth('created_at', $mesActual)
            ->whereYear('created_at', $añoActual)
            ->sum('cantidad');

        $egresosMes = MovimientoInventario::whereIn('tipo', ['venta', 'ajuste_negativo', 'anulacion_compra'])
            ->whereMonth('created_at', $mesActual)
            ->whereYear('created_at', $añoActual)
            ->selectRaw('SUM(ABS(cantidad)) as total')->value('total') ?? 0;

        $inversionIngresosMes = MovimientoInventario::whereIn('tipo', ['compra', 'ajuste_positivo', 'anulacion_venta', 'inicial'])
            ->whereMonth('created_at', $mesActual)
            ->whereYear('created_at', $añoActual)
            ->selectRaw('SUM(cantidad * costo_unitario) as total')->value('total') ?? 0;

        $unidadesVentasMes = MovimientoInventario::where('tipo', 'venta')
            ->whereMonth('created_at', $mesActual)
            ->whereYear('created_at', $añoActual)
            ->selectRaw('SUM(ABS(cantidad)) as total')->value('total') ?? 0;

        $unidadesMermasMes = MovimientoInventario::where('tipo', 'ajuste_negativo')
            ->whereMonth('created_at', $mesActual)
            ->whereYear('created_at', $añoActual)
            ->selectRaw('SUM(ABS(cantidad)) as total')->value('total') ?? 0;

        $balanceNetoStock = $ingresosMes - $egresosMes;

        $productos = Producto::orderBy('nombre', 'asc')->get(['id', 'codigo', 'nombre', 'stock', 'precio_compra', 'precio_venta']);
        $categorias = Categoria::orderBy('nombre', 'asc')->get();

        return view('inventario.movimientos', compact(
            'movimientos',
            'tab',
            'ingresosMes',
            'egresosMes',
            'inversionIngresosMes',
            'unidadesVentasMes',
            'unidadesMermasMes',
            'balanceNetoStock',
            'productos',
            'categorias'
        ));
    }

    /**
     * 📥 Enlace directo filtrado a Ingresos
     */
    public function ingresos(Request $request)
    {
        $request->merge(['tab' => 'ingresos']);
        return $this->movimientos($request);
    }

    /**
     * 📤 Enlace directo filtrado a Egresos
     */
    public function egresos(Request $request)
    {
        $request->merge(['tab' => 'egresos']);
        return $this->movimientos($request);
    }

    /**
     * 🛡️ ANÁLISIS DE COSTOS Y MÁRGENES: Detección de Compras Caras y Protección de Ganancias
     */
    public function analisisCostos(Request $request)
    {
        $query = Producto::with('categoria');

        if ($request->filled('categoria_id')) {
            $query->where('categoria_id', $request->categoria_id);
        }

        if ($request->filled('buscar')) {
            $buscar = trim($request->buscar);
            $query->where(function ($q) use ($buscar) {
                $q->where('nombre', 'like', "%{$buscar}%")
                  ->orWhere('codigo', 'like', "%{$buscar}%");
            });
        }

        $productos = $query->orderBy('nombre', 'asc')->get();
        $categorias = Categoria::orderBy('nombre', 'asc')->get();

        // Mapear cada producto con su margen, estado de rentabilidad y precio sugerido (Markup 30%)
        $analisis = $productos->map(function ($p) {
            $costo = (float) $p->precio_compra;
            $venta = (float) $p->precio_venta;
            $ganancia = (float) max(0, $venta - $costo);

            $margenPorcentaje = $costo > 0 ? round((($venta - $costo) / $costo) * 100, 1) : 0;
            // Precios de venta sugeridos para proteger ganancias mínimas
            $sugerido25 = round($costo * 1.25, 2);
            $sugerido35 = round($costo * 1.35, 2);

            // Clasificación del margen
            if ($costo >= $venta || $margenPorcentaje <= 5) {
                $alerta = 'critico';
                $alertaLabel = 'Sobrecosto Crítico / Venta a Pérdida';
                $alertaBadge = 'bg-danger-subtle text-danger border border-danger-subtle';
            } elseif ($margenPorcentaje < 20) {
                $alerta = 'bajo';
                $alertaLabel = 'Margen Comprimido (<20%)';
                $alertaBadge = 'bg-warning-subtle text-warning-emphasis border border-warning-subtle';
            } else {
                $alerta = 'optimo';
                $alertaLabel = 'Margen Saludable (≥20%)';
                $alertaBadge = 'bg-success-subtle text-success border border-success-subtle';
            }

            return (object) [
                'producto'          => $p,
                'costo'             => $costo,
                'venta'             => $venta,
                'ganancia'          => $ganancia,
                'margen_porcentaje' => $margenPorcentaje,
                'sugerido25'        => $sugerido25,
                'sugerido35'        => $sugerido35,
                'alerta'            => $alerta,
                'alertaLabel'       => $alertaLabel,
                'alertaBadge'       => $alertaBadge,
            ];
        });

        // Filtrar por nivel de riesgo si el usuario lo solicita
        if ($request->filled('nivel_riesgo')) {
            $filtroRiesgo = $request->nivel_riesgo;
            $analisis = $analisis->filter(fn($item) => $item->alerta === $filtroRiesgo);
        }

        // KPIs de rentabilidad
        $totalProductosAnalizados = $productos->count();
        $criticosCount = $analisis->where('alerta', 'critico')->count();
        $bajosCount = $analisis->where('alerta', 'bajo')->count();
        $optimosCount = $analisis->where('alerta', 'optimo')->count();

        return view('inventario.costos', compact(
            'analisis',
            'categorias',
            'totalProductosAnalizados',
            'criticosCount',
            'bajosCount',
            'optimosCount'
        ));
    }

    /**
     * ⚡ Actualizar Precio de Venta Sugerido para Proteger la Ganancia
     */
    public function actualizarPrecioVenta(Request $request, $id)
    {
        $request->validate([
            'precio_venta' => 'required|numeric|min:0.01'
        ], [
            'precio_venta.required' => 'Debe ingresar un precio de venta válido.',
            'precio_venta.min'      => 'El precio de venta debe ser mayor a 0.'
        ]);

        $producto = Producto::findOrFail($id);
        $precioAnterior = $producto->precio_venta;
        $producto->precio_venta = (float) $request->precio_venta;
        $producto->save();

        Bitacora::create([
            'user_id'     => Auth::id(),
            'modulo'      => 'Inventario',
            'accion'      => 'Actualizar Precio Venta',
            'descripcion' => "Ajustó el precio de venta de '{$producto->nombre}' de Bs. " . number_format($precioAnterior, 2) . " a Bs. " . number_format($producto->precio_venta, 2) . " para proteger su margen de ganancia.",
            'ip'          => $request->ip(),
        ]);

        return back()->with('success', "¡Precio de venta actualizado exitosamente para '{$producto->nombre}' a Bs. " . number_format($producto->precio_venta, 2) . "!");
    }

    /**
     * 📊 Kardex Detallado por Producto
     */
    public function kardex(Request $request, $productoId = null)
    {
        $productoSeleccionado = null;
        $movimientos = collect();
        $totalEntradas = 0;
        $totalSalidas = 0;

        $id = $productoId ?: $request->producto_id;

        if ($id) {
            $productoSeleccionado = Producto::with('categoria')->find($id);

            if ($productoSeleccionado) {
                $query = MovimientoInventario::with('usuario')
                    ->where('producto_id', $productoSeleccionado->id);

                if ($request->filled('fecha_desde')) {
                    $query->whereDate('created_at', '>=', $request->fecha_desde);
                }
                if ($request->filled('fecha_hasta')) {
                    $query->whereDate('created_at', '<=', $request->fecha_hasta);
                }

                $movimientos = $query->latest('id')->get();

                $totalEntradas = MovimientoInventario::where('producto_id', $productoSeleccionado->id)
                    ->whereIn('tipo', ['compra', 'ajuste_positivo', 'anulacion_venta', 'inicial'])
                    ->sum('cantidad') ?? 0;

                $totalSalidas = MovimientoInventario::where('producto_id', $productoSeleccionado->id)
                    ->whereIn('tipo', ['venta', 'ajuste_negativo', 'anulacion_compra'])
                    ->sum('cantidad') ?? 0;
            }
        }

        $todosProductos = Producto::orderBy('nombre', 'asc')->get(['id', 'codigo', 'nombre', 'stock']);

        return view('inventario.kardex', compact(
            'productoSeleccionado',
            'movimientos',
            'todosProductos',
            'totalEntradas',
            'totalSalidas'
        ));
    }

    /**
     * 💰 Reporte de Inventario Valorizado (Para auditoría y contabilidad)
     */
    public function valorizado(Request $request)
    {
        $query = Producto::with('categoria');

        if ($request->filled('categoria_id')) {
            $query->where('categoria_id', $request->categoria_id);
        }

        if ($request->filled('estado_stock')) {
            if ($request->estado_stock === 'agotado') {
                $query->where('stock', '<=', 0);
            } elseif ($request->estado_stock === 'bajo') {
                $query->whereColumn('stock', '<=', 'stock_minimo')->where('stock', '>', 0);
            } elseif ($request->estado_stock === 'optimo') {
                $query->whereColumn('stock', '>', 'stock_minimo');
            }
        }

        if ($request->filled('buscar')) {
            $buscar = trim($request->buscar);
            $query->where(function($q) use ($buscar) {
                $q->where('nombre', 'like', "%{$buscar}%")
                  ->orWhere('codigo', 'like', "%{$buscar}%");
            });
        }

        $productos = $query->orderBy('nombre', 'asc')->get();
        $categorias = Categoria::orderBy('nombre', 'asc')->get();

        $totalItems = $productos->count();
        $totalUnidades = $productos->sum('stock');
        $totalInversion = $productos->sum(fn($p) => $p->stock * $p->precio_compra);
        $totalValorVenta = $productos->sum(fn($p) => $p->stock * $p->precio_venta);
        $totalGananciaProyectada = max(0, $totalValorVenta - $totalInversion);

        return view('inventario.valorizado', compact(
            'productos',
            'categorias',
            'totalItems',
            'totalUnidades',
            'totalInversion',
            'totalValorVenta',
            'totalGananciaProyectada'
        ));
    }

    /**
     * ⚡ Guardar Ingreso / Egreso Manual (Ajuste Positivo / Negativo)
     */
    public function storeMovimiento(Request $request)
    {
        $request->validate([
            'producto_id'    => 'required|exists:productos,id',
            'tipo'           => 'required|in:ajuste_positivo,ajuste_negativo',
            'cantidad'       => 'required|integer|min:1',
            'motivo'         => 'required|string|max:255',
            'costo_unitario' => 'nullable|numeric|min:0',
            'observacion'    => 'nullable|string|max:1000'
        ], [
            'producto_id.required' => 'Debe seleccionar un producto del catálogo.',
            'producto_id.exists'   => 'El producto seleccionado no es válido.',
            'tipo.required'        => 'Seleccione si es un Ingreso o un Egreso.',
            'tipo.in'              => 'Tipo de movimiento inválido.',
            'cantidad.required'    => 'La cantidad es obligatoria.',
            'cantidad.min'         => 'La cantidad debe ser al menos 1 unidad.',
            'motivo.required'      => 'Debe especificar el motivo o justificación del ajuste.',
        ]);

        return DB::transaction(function () use ($request) {
            $producto = Producto::lockForUpdate()->findOrFail($request->producto_id);
            $cantidad = (int) $request->cantidad;
            $tipo = $request->tipo;
            $stockAnterior = $producto->stock;

            // Validación de stock para egresos
            if ($tipo === 'ajuste_negativo' && $cantidad > $stockAnterior) {
                return back()->with('error', "No se puede retirar {$cantidad} unidad(es). El stock disponible de '{$producto->nombre}' es de solo {$stockAnterior} unidad(es).");
            }

            if ($tipo === 'ajuste_positivo') {
                $stockNuevo = $stockAnterior + $cantidad;
                $labelTipo = 'Ingreso de Stock (+)';
                $accionBitacora = 'Ingreso Stock';
            } else {
                $stockNuevo = $stockAnterior - $cantidad;
                $labelTipo = 'Egreso de Stock (-)';
                $accionBitacora = 'Egreso Stock';
            }

            // Actualizar stock del producto
            $producto->stock = $stockNuevo;
            $producto->save();

            $motivoTexto = trim($request->motivo);
            if ($request->filled('observacion')) {
                $motivoTexto .= ' | Obs: ' . trim($request->observacion);
            }

            // Registrar movimiento en el Kardex
            MovimientoInventario::create([
                'producto_id'     => $producto->id,
                'user_id'         => Auth::id(),
                'tipo'            => $tipo,
                'cantidad'        => $cantidad,
                'stock_anterior'  => $stockAnterior,
                'stock_nuevo'     => $stockNuevo,
                'costo_unitario'  => $request->filled('costo_unitario') ? (float)$request->costo_unitario : $producto->precio_compra,
                'precio_unitario' => $producto->precio_venta,
                'motivo'          => $motivoTexto,
                'referencia_tipo' => 'AjusteManual',
                'referencia_id'   => null
            ]);

            // Auditoría en Bitácora
            Bitacora::create([
                'user_id'     => Auth::id(),
                'modulo'      => 'Inventario',
                'accion'      => $accionBitacora,
                'descripcion' => "{$labelTipo}: {$cantidad} unid(s) de '{$producto->nombre}' [{$producto->codigo}]. Stock: {$stockAnterior} -> {$stockNuevo}. Motivo: {$motivoTexto}",
                'ip'          => $request->ip(),
            ]);

            $signo = $tipo === 'ajuste_positivo' ? '+' : '-';
            return back()->with('success', "¡{$labelTipo} registrado con éxito! {$signo}{$cantidad} unid(s) para '{$producto->nombre}'. Nuevo stock: {$stockNuevo} unid(s).");
        });
    }
}
