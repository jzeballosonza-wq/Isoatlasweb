<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Compra;
use App\Models\DetalleCompra;
use App\Models\Producto;
use App\Models\Proveedor;
use App\Models\Categoria;
use App\Models\Bitacora;
use App\Models\MovimientoInventario;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CompraController extends Controller
{
    public function index(Request $request)
    {
        $query = Compra::with(['proveedor', 'detalles.producto']);

        // 1. Filtro por texto general (ID, comprobante, proveedor o producto)
        if ($request->filled('buscar')) {
            $buscar = trim($request->buscar);
            $query->where(function ($q) use ($buscar) {
                $q->where('id', 'like', "%{$buscar}%")
                  ->orWhere('comprobante', 'like', "%{$buscar}%")
                  ->orWhereHas('proveedor', function ($qp) use ($buscar) {
                      $qp->where('nombre', 'like', "%{$buscar}%")
                         ->orWhere('telefono', 'like', "%{$buscar}%");
                  })
                  ->orWhereHas('detalles.producto', function ($qprod) use ($buscar) {
                      $qprod->where('nombre', 'like', "%{$buscar}%")
                            ->orWhere('codigo', 'like', "%{$buscar}%");
                  });
            });
        }

        // 2. Filtro por proveedor específico
        if ($request->filled('proveedor_id')) {
            $query->where('proveedor_id', $request->proveedor_id);
        }

        // 3. Filtro por rango de fechas
        if ($request->filled('fecha_desde')) {
            $query->whereDate('fecha', '>=', $request->fecha_desde);
        }

        if ($request->filled('fecha_hasta')) {
            $query->whereDate('fecha', '<=', $request->fecha_hasta);
        }

        // 4. Filtro por rango de montos
        if ($request->filled('monto_min')) {
            $query->where('total', '>=', $request->monto_min);
        }

        if ($request->filled('monto_max')) {
            $query->where('total', '<=', $request->monto_max);
        }

        // 5. Ordenamiento
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

        // 6. Paginación con persistencia de query string
        $perPage = in_array($request->per_page, [10, 25, 50, 100]) ? (int)$request->per_page : 10;
        $compras = $query->paginate($perPage)->withQueryString();

        // 7. Estadísticas para tarjetas KPI
        $totalCompras = Compra::count();
        $totalInvertido = Compra::sum('total');
        $comprasHoy = Compra::whereDate('fecha', today())->count();
        $inversionHoy = Compra::whereDate('fecha', today())->sum('total');
        $comprasMes = Compra::whereMonth('fecha', now()->month)->whereYear('fecha', now()->year)->count();
        $inversionMes = Compra::whereMonth('fecha', now()->month)->whereYear('fecha', now()->year)->sum('total');
        $promedioCompra = $totalCompras > 0 ? ($totalInvertido / $totalCompras) : 0;
        $proveedores = Proveedor::orderBy('nombre', 'asc')->get();

        return view('compras.index', compact(
            'compras',
            'proveedores',
            'totalCompras',
            'totalInvertido',
            'comprasHoy',
            'inversionHoy',
            'comprasMes',
            'inversionMes',
            'promedioCompra'
        ));
    }

    public function create()
    {
        $proveedores = Proveedor::orderBy('nombre', 'asc')->get();
        $productos = Producto::with('categoria')->orderBy('nombre', 'asc')->get();
        $categorias = Categoria::orderBy('nombre', 'asc')->get();

        return view('compras.create', compact('proveedores', 'productos', 'categorias'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'proveedor_id' => 'required|exists:proveedors,id',
            'fecha' => 'required|date',
            'total' => 'required|numeric|min:0.01',
            'detalles' => 'required|array|min:1'
        ]);

        try {
            DB::beginTransaction();

            $compra = Compra::create([
                'proveedor_id' => $request->proveedor_id,
                'fecha' => $request->fecha,
                'comprobante' => $request->comprobante,
                'total' => $request->total
            ]);

            foreach ($request->detalles as $item) {
                DetalleCompra::create([
                    'compra_id' => $compra->id,
                    'producto_id' => $item['id'],
                    'cantidad' => $item['cantidad'],
                    'precio' => $item['precio']
                ]);

                $producto = Producto::find($item['id']);
                if ($producto) {
                    $stockAnterior = (int) $producto->stock;
                    $producto->stock += $item['cantidad'];
                    if (isset($item['precio']) && $item['precio'] > 0) {
                        $producto->precio_compra = $item['precio'];
                    }
                    $producto->save();

                    // 📦 Registrar movimiento en Kardex
                    MovimientoInventario::create([
                        'producto_id' => $producto->id,
                        'user_id' => Auth::id(),
                        'tipo' => 'compra',
                        'cantidad' => $item['cantidad'],
                        'stock_anterior' => $stockAnterior,
                        'stock_nuevo' => $producto->stock,
                        'costo_unitario' => $item['precio'] ?? $producto->precio_compra,
                        'motivo' => "Ingreso por Compra N° {$compra->id}" . ($compra->comprobante ? " ({$compra->comprobante})" : ""),
                        'referencia_tipo' => Compra::class,
                        'referencia_id' => $compra->id,
                    ]);
                }
            }

            Bitacora::create([
                'user_id' => Auth::id(),
                'modulo' => 'Compras',
                'accion' => 'Registrar',
                'descripcion' => 'Registró la compra N° ' . $compra->id . ' por un monto de Bs. ' . number_format($compra->total, 2),
                'ip' => $request->ip(),
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'mensaje' => 'Compra registrada con éxito y stock actualizado.',
                'compra_id' => $compra->id
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'error' => 'Error al registrar la compra: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        $compra = Compra::with(['proveedor', 'detalles.producto'])->findOrFail($id);
        return view('compras.show', compact('compra'));
    }

    public function edit($id)
    {
        $compra = Compra::with(['proveedor', 'detalles.producto.categoria'])->findOrFail($id);

        $proveedores = Proveedor::orderBy('nombre', 'asc')->get();
        $productos = Producto::with('categoria')->orderBy('nombre', 'asc')->get();
        $categorias = Categoria::orderBy('nombre', 'asc')->get();

        $detalles = $compra->detalles->map(function ($d) {
            $prod = $d->producto;
            return [
                'id' => $d->producto_id,
                'codigo' => $prod ? $prod->codigo : 'N/A',
                'nombre' => $prod ? $prod->nombre : 'Producto no disponible',
                'categoria' => $prod && $prod->categoria ? $prod->categoria->nombre : 'General',
                'precio' => (float)$d->precio,
                'cantidad' => (int)$d->cantidad,
                'subtotal' => (float)($d->cantidad * $d->precio),
                'imagen' => $prod ? $prod->imagen : null,
                'stock_actual' => $prod ? (int)$prod->stock : 0,
            ];
        });

        return view('compras.edit', compact('compra', 'proveedores', 'productos', 'categorias', 'detalles'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'proveedor_id' => 'required|exists:proveedors,id',
            'fecha' => 'required|date',
            'total' => 'required|numeric|min:0.01',
            'detalles' => 'required|array|min:1'
        ], [
            'proveedor_id.required' => 'Debe seleccionar un proveedor.',
            'fecha.required' => 'La fecha de compra es obligatoria.',
            'total.required' => 'El total de la compra debe ser mayor a 0.',
            'detalles.required' => 'Debe ingresar al menos un producto a la compra.',
            'detalles.min' => 'Debe ingresar al menos un producto a la compra.'
        ]);

        try {
            DB::beginTransaction();

            $compra = Compra::with('detalles')->findOrFail($id);

            // 1. Revertir el stock de los detalles previos de la compra
            foreach ($compra->detalles as $detalle) {
                $producto = Producto::find($detalle->producto_id);
                if ($producto) {
                    $producto->stock -= $detalle->cantidad;
                    $producto->save();
                }
            }

            // 2. Eliminar detalles anteriores
            DetalleCompra::where('compra_id', $compra->id)->delete();

            // 3. Actualizar la cabecera de la compra
            $compra->update([
                'proveedor_id' => $request->proveedor_id,
                'fecha' => $request->fecha,
                'comprobante' => $request->comprobante,
                'total' => $request->total
            ]);

            // 4. Crear los nuevos detalles y actualizar stock
            $totalItems = 0;
            foreach ($request->detalles as $item) {
                DetalleCompra::create([
                    'compra_id' => $compra->id,
                    'producto_id' => $item['id'],
                    'cantidad' => $item['cantidad'],
                    'precio' => $item['precio']
                ]);

                $producto = Producto::find($item['id']);
                if ($producto) {
                    $producto->stock += $item['cantidad'];
                    if (isset($item['precio']) && $item['precio'] > 0) {
                        $producto->precio_compra = $item['precio'];
                    }
                    $producto->save();
                }

                $totalItems += (int)$item['cantidad'];
            }

            // 5. Registrar en bitácora
            $compra->load('proveedor');
            $provNombre = $compra->proveedor ? $compra->proveedor->nombre : 'Sin proveedor';

            Bitacora::create([
                'user_id' => Auth::id(),
                'modulo' => 'Compras',
                'accion' => 'Editar',
                'descripcion' => "Actualizó la compra N° {$compra->id} | Total: Bs. " . number_format($compra->total, 2) . " ({$totalItems} unidades) | Prov: {$provNombre}",
                'ip' => $request->ip(),
            ]);

            DB::commit();

            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'mensaje' => "¡Compra N° {$compra->id} actualizada exitosamente y stock sincronizado!",
                    'compra_id' => $compra->id
                ]);
            }

            return redirect()->route('compras.index')
                ->with('success', "¡Compra N° {$compra->id} actualizada exitosamente!");

        } catch (\Exception $e) {
            DB::rollBack();
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'error' => 'Error al actualizar la compra: ' . $e->getMessage()
                ], 500);
            }
            return back()->withInput()->with('error', 'Error al actualizar la compra: ' . $e->getMessage());
        }
    }

    public function destroy(Request $request, $id)
    {
        $compra = Compra::with('detalles')->findOrFail($id);

        foreach ($compra->detalles as $detalle) {
            $producto = Producto::find($detalle->producto_id);

            if ($producto) {
                $stockAnterior = (int) $producto->stock;
                $producto->stock -= $detalle->cantidad;
                $producto->save();

                // 📦 Registrar anulación en Kardex
                MovimientoInventario::create([
                    'producto_id' => $producto->id,
                    'user_id' => Auth::id(),
                    'tipo' => 'anulacion_compra',
                    'cantidad' => -$detalle->cantidad,
                    'stock_anterior' => $stockAnterior,
                    'stock_nuevo' => $producto->stock,
                    'costo_unitario' => $detalle->precio,
                    'motivo' => "Eliminación / Anulación de Compra N° {$id}",
                    'referencia_tipo' => Compra::class,
                    'referencia_id' => $id
                ]);
            }
        }

        $idCompra = $compra->id;

        $compra->delete();

        Bitacora::create([
            'user_id' => Auth::id(),
            'modulo' => 'Compras',
            'accion' => 'Eliminar',
            'descripcion' => 'Eliminó la compra N° ' . $idCompra,
            'ip' => $request->ip(),
        ]);

        return redirect()->route('compras.index')
            ->with('success', 'Compra eliminada');
    }
}