<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use App\Models\DetalleVenta;
use App\Models\Producto;
use App\Models\Bitacora;
use App\Models\MovimientoInventario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class VentaController extends Controller
{
    public function index(Request $request)
    {
        $query = Venta::with('detalles.producto');

        // 1. Filtro por texto (buscar ID, comprobante o nombre/código de producto)
        if ($request->filled('buscar')) {
            $buscar = trim($request->buscar);
            $query->where(function ($q) use ($buscar) {
                $q->where('id', 'like', "%{$buscar}%")
                  ->orWhere('comprobante', 'like', "%{$buscar}%")
                  ->orWhereHas('detalles.producto', function ($qp) use ($buscar) {
                      $qp->where('nombre', 'like', "%{$buscar}%")
                         ->orWhere('codigo', 'like', "%{$buscar}%");
                  });
            });
        }

        // 2. Filtro por rango de fechas
        if ($request->filled('fecha_desde')) {
            $query->whereDate('fecha', '>=', $request->fecha_desde);
        }

        if ($request->filled('fecha_hasta')) {
            $query->whereDate('fecha', '<=', $request->fecha_hasta);
        }

        // 3. Filtro por monto
        if ($request->filled('monto_min')) {
            $query->where('total', '>=', $request->monto_min);
        }

        if ($request->filled('monto_max')) {
            $query->where('total', '<=', $request->monto_max);
        }

        // 4. Ordenamiento
        $orden = $request->get('orden', 'recientes');
        switch ($orden) {
            case 'antiguas':
                $query->orderBy('fecha', 'asc')->orderBy('id', 'asc');
                break;
            case 'monto_mayor':
                $query->orderBy('total', 'desc');
                break;
            case 'monto_menor':
                $query->orderBy('total', 'asc');
                break;
            case 'recientes':
            default:
                $query->latest('id');
                break;
        }

        // 5. Paginación con persistencia de query string
        $perPage = in_array($request->per_page, [10, 25, 50, 100]) ? (int)$request->per_page : 10;
        $ventas = $query->paginate($perPage)->withQueryString();

        // 6. Estadísticas para tarjetas KPI
        $totalVentas = Venta::count();
        $totalIngresos = Venta::sum('total');
        $ventasHoy = Venta::whereDate('fecha', today())->count();
        $ingresosHoy = Venta::whereDate('fecha', today())->sum('total');
        $ventasMes = Venta::whereMonth('fecha', now()->month)->whereYear('fecha', now()->year)->count();
        $ingresosMes = Venta::whereMonth('fecha', now()->month)->whereYear('fecha', now()->year)->sum('total');
        $ticketPromedio = $totalVentas > 0 ? ($totalIngresos / $totalVentas) : 0;

        $cajaAbierta = \App\Models\CajaSesion::where('estado', 'abierta')
            ->with('usuarioApertura')
            ->latest('id')
            ->first();

        return view('ventas.index', compact(
            'ventas',
            'cajaAbierta',
            'totalVentas',
            'totalIngresos',
            'ventasHoy',
            'ingresosHoy',
            'ventasMes',
            'ingresosMes',
            'ticketPromedio'
        ));
    }

    public function create()
    {
        $cajaActiva = \App\Models\CajaSesion::where('estado', 'abierta')
            ->with('usuarioApertura')
            ->latest('id')
            ->first();

        $clientes = \App\Models\Cliente::orderBy('nombre', 'asc')->get();
        $productos = \App\Models\Producto::with('categoria')->orderBy('nombre', 'asc')->get();
        $categorias = \App\Models\Categoria::orderBy('nombre', 'asc')->get();

        return view('ventas.create', compact('clientes', 'productos', 'categorias', 'cajaActiva'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'fecha' => 'required|date',
            'total' => 'required|numeric|min:0.01',
            'detalles' => 'required|array|min:1'
        ]);

        try {
            DB::beginTransaction();

            // 1. Validar disponibilidad de stock para cada producto
            foreach ($request->detalles as $item) {
                $producto = Producto::find($item['id']);
                if (!$producto) {
                    return response()->json([
                        'success' => false,
                        'error' => 'El producto seleccionado ya no existe en el catálogo.'
                    ], 422);
                }

                if ($producto->stock < $item['cantidad']) {
                    return response()->json([
                        'success' => false,
                        'error' => "Stock insuficiente para '{$producto->nombre}'. Stock disponible: {$producto->stock} unidades."
                    ], 422);
                }
            }

            // Buscar sesión de caja abierta activa (si existe)
            $cajaActiva = \App\Models\CajaSesion::where('estado', 'abierta')->latest('id')->first();

            // 2. Crear la venta
            $venta = Venta::create([
                'fecha' => $request->fecha,
                'comprobante' => $request->comprobante,
                'total' => $request->total,
                'caja_sesion_id' => $cajaActiva ? $cajaActiva->id : null,
            ]);

            // 3. Crear detalles y descontar stock
            foreach ($request->detalles as $item) {
                DetalleVenta::create([
                    'venta_id' => $venta->id,
                    'producto_id' => $item['id'],
                    'cantidad' => $item['cantidad'],
                    'precio' => $item['precio']
                ]);

                $producto = Producto::find($item['id']);
                if ($producto) {
                    $stockAnterior = (int) $producto->stock;
                    $producto->stock -= $item['cantidad'];
                    $producto->save();

                    // 📦 Registrar salida en Kardex
                    MovimientoInventario::create([
                        'producto_id' => $producto->id,
                        'user_id' => Auth::id(),
                        'tipo' => 'venta',
                        'cantidad' => -$item['cantidad'],
                        'stock_anterior' => $stockAnterior,
                        'stock_nuevo' => $producto->stock,
                        'precio_unitario' => $item['precio'],
                        'costo_unitario' => $producto->precio_compra,
                        'motivo' => "Salida por Venta N° {$venta->id}" . ($venta->comprobante ? " ({$venta->comprobante})" : ""),
                        'referencia_tipo' => Venta::class,
                        'referencia_id' => $venta->id,
                    ]);
                }
            }

            // 4. Registrar en bitácora
            Bitacora::create([
                'user_id' => Auth::id(),
                'modulo' => 'Ventas',
                'accion' => 'Registrar',
                'descripcion' => 'Registró la venta N° ' . $venta->id . ' por un monto de Bs. ' . number_format($venta->total, 2),
                'ip' => $request->ip(),
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'mensaje' => 'Venta registrada con éxito',
                'venta_id' => $venta->id,
                'comprobante_url' => route('ventas.comprobante', $venta->id)
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'error' => 'Error al procesar la venta: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show(Venta $venta)
    {
        $venta->load('detalles.producto');

        return view('ventas.show', compact('venta'));
    }

    public function edit($id)
    {
        $user = Auth::user();
        if (!$user || !$user->esAdmin()) {
            return redirect()->route('ventas.index')
                ->with('error', 'Acceso denegado: Solo el Administrador tiene autorización para modificar ventas registradas.');
        }

        $venta = ($id instanceof Venta) ? $id : Venta::with(['detalles.producto.categoria'])->findOrFail($id);

        $clientes = \App\Models\Cliente::orderBy('nombre', 'asc')->get();
        $productos = Producto::with('categoria')->orderBy('nombre', 'asc')->get();
        $categorias = \App\Models\Categoria::orderBy('nombre', 'asc')->get();

        $detalles = $venta->detalles->map(function ($d) {
            $prod = $d->producto;
            $stockActual = $prod ? (int)$prod->stock : 0;
            $cantidadEnEstaVenta = (int)$d->cantidad;

            return [
                'id' => $d->producto_id,
                'codigo' => $prod ? $prod->codigo : 'N/A',
                'nombre' => $prod ? $prod->nombre : 'Producto no disponible',
                'categoria' => $prod && $prod->categoria ? $prod->categoria->nombre : 'General',
                'precio' => (float)$d->precio,
                'cantidad' => $cantidadEnEstaVenta,
                'subtotal' => (float)($cantidadEnEstaVenta * $d->precio),
                'imagen' => $prod ? $prod->imagen : null,
                // El stock disponible para edición incluye las unidades que ya tiene esta venta
                'stock_disponible' => $stockActual + $cantidadEnEstaVenta,
                'stock_actual' => $stockActual,
            ];
        });

        return view('ventas.edit', compact('venta', 'clientes', 'productos', 'categorias', 'detalles'));
    }

    public function update(Request $request, $id)
    {
        $user = Auth::user();
        if (!$user || !$user->esAdmin()) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'error' => 'Acceso denegado: Solo el Administrador tiene autorización para modificar ventas registradas.'
                ], 403);
            }
            return redirect()->route('ventas.index')
                ->with('error', 'Acceso denegado: Solo el Administrador tiene autorización para modificar ventas registradas.');
        }

        $ventaId = ($id instanceof Venta) ? $id->id : $id;
        $venta = Venta::with('detalles')->findOrFail($ventaId);

        $request->validate([
            'fecha' => 'required|date',
            'total' => 'required|numeric|min:0.01',
            'detalles' => 'required|array|min:1'
        ], [
            'fecha.required' => 'La fecha de la venta es obligatoria.',
            'total.required' => 'El total de la venta debe ser mayor a 0.',
            'detalles.required' => 'Debe ingresar al menos un producto a la venta.',
            'detalles.min' => 'Debe ingresar al menos un producto a la venta.'
        ]);

        try {
            DB::beginTransaction();

            // 1. Revertir temporalmente el stock de los productos que estaban en la venta original
            foreach ($venta->detalles as $detalle) {
                $producto = Producto::find($detalle->producto_id);
                if ($producto) {
                    $producto->stock += $detalle->cantidad;
                    $producto->save();
                }
            }

            // 2. Validar que haya suficiente stock disponible para la nueva lista de productos
            foreach ($request->detalles as $item) {
                $producto = Producto::find($item['id']);
                if (!$producto) {
                    DB::rollBack();
                    if ($request->wantsJson() || $request->ajax()) {
                        return response()->json([
                            'success' => false,
                            'error' => 'Uno de los productos seleccionados ya no existe en el catálogo.'
                        ], 422);
                    }
                    return back()->with('error', 'Uno de los productos seleccionados ya no existe.');
                }

                if ($producto->stock < $item['cantidad']) {
                    DB::rollBack();
                    if ($request->wantsJson() || $request->ajax()) {
                        return response()->json([
                            'success' => false,
                            'error' => "Stock insuficiente para '{$producto->nombre}'. Stock disponible: {$producto->stock} unidades."
                        ], 422);
                    }
                    return back()->with('error', "Stock insuficiente para '{$producto->nombre}'. Stock disponible: {$producto->stock} unidades.");
                }
            }

            // 3. Eliminar los detalles anteriores
            DetalleVenta::where('venta_id', $venta->id)->delete();

            // 4. Actualizar la cabecera de la venta
            $venta->update([
                'fecha' => $request->fecha,
                'comprobante' => $request->comprobante,
                'total' => $request->total
            ]);

            // 5. Crear los nuevos detalles y descontar el stock
            $totalItems = 0;
            foreach ($request->detalles as $item) {
                DetalleVenta::create([
                    'venta_id' => $venta->id,
                    'producto_id' => $item['id'],
                    'cantidad' => $item['cantidad'],
                    'precio' => $item['precio']
                ]);

                $producto = Producto::find($item['id']);
                if ($producto) {
                    $producto->stock -= $item['cantidad'];
                    $producto->save();
                }

                $totalItems += (int)$item['cantidad'];
            }

            // 6. Registrar en bitácora
            Bitacora::create([
                'user_id' => Auth::id(),
                'modulo' => 'Ventas',
                'accion' => 'Editar',
                'descripcion' => "Actualizó la venta N° {$venta->id} | Total: Bs. " . number_format($venta->total, 2) . " ({$totalItems} unidades)",
                'ip' => $request->ip(),
            ]);

            DB::commit();

            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'mensaje' => "¡Venta N° {$venta->id} actualizada exitosamente y stock recalculado!",
                    'venta_id' => $venta->id,
                    'comprobante_url' => route('ventas.comprobante', $venta->id)
                ]);
            }

            return redirect()->route('ventas.index')
                ->with('success', "¡Venta N° {$venta->id} actualizada exitosamente!");

        } catch (\Exception $e) {
            DB::rollBack();
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'error' => 'Error al actualizar la venta: ' . $e->getMessage()
                ], 500);
            }
            return back()->withInput()->with('error', 'Error al procesar la actualización: ' . $e->getMessage());
        }
    }

    public function destroy(Request $request, Venta $venta)
    {
        $user = Auth::user();
        if (!$user || !$user->esAdmin()) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'error' => 'Acceso denegado: Solo el Administrador tiene autorización para anular o eliminar transacciones de venta.'
                ], 403);
            }
            return redirect()->route('ventas.index')
                ->with('error', 'Acceso denegado: Solo el Administrador tiene autorización para anular o eliminar transacciones de venta.');
        }

        foreach ($venta->detalles as $detalle) {
            $producto = Producto::find($detalle->producto_id);

            if ($producto) {
                $stockAnterior = (int) $producto->stock;
                $producto->stock += $detalle->cantidad;
                $producto->save();

                // 📦 Registrar reingreso/anulación en Kardex
                MovimientoInventario::create([
                    'producto_id' => $producto->id,
                    'user_id' => Auth::id(),
                    'tipo' => 'anulacion_venta',
                    'cantidad' => $detalle->cantidad,
                    'stock_anterior' => $stockAnterior,
                    'stock_nuevo' => $producto->stock,
                    'precio_unitario' => $detalle->precio,
                    'costo_unitario' => $producto->precio_compra,
                    'motivo' => "Anulación / Devolución de Venta N° {$venta->id}",
                    'referencia_tipo' => Venta::class,
                    'referencia_id' => $venta->id
                ]);
            }
        }

        $id = $venta->id;

        $venta->delete();

        Bitacora::create([
            'user_id' => Auth::id(),
            'modulo' => 'Ventas',
            'accion' => 'Eliminar',
            'descripcion' => 'Eliminó la venta N° ' . $id,
            'ip' => $request->ip(),
        ]);

        return redirect()->route('ventas.index')
            ->with('success', 'Venta eliminada correctamente');
    }
    public function comprobante(Venta $venta)
    {
        $venta->load('detalles.producto.categoria', 'cajaSesion.usuarioApertura');

        $cliente = null;
        if (!empty($venta->comprobante) && $venta->comprobante !== 'Sin NIT' && $venta->comprobante !== '0') {
            $cliente = \App\Models\Cliente::where('nit', $venta->comprobante)->first();
        }

        return view('ventas.comprobante', compact('venta', 'cliente'));
    }
}