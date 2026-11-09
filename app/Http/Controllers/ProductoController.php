<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;
use App\Models\Categoria;
use App\Models\Bitacora;
use App\Models\MovimientoInventario;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProductoController extends Controller
{
    /**
     * 📋 Listado de Productos con KPIs y Filtros
     */
    public function index(Request $request)
    {
        $query = Producto::with(['categoria']);

        // Búsqueda por término (nombre, código o descripción)
        if ($request->filled('buscar')) {
            $buscar = trim($request->buscar);
            $query->where(function ($q) use ($buscar) {
                $q->where('nombre', 'like', "%{$buscar}%")
                  ->orWhere('codigo', 'like', "%{$buscar}%")
                  ->orWhere('descripcion', 'like', "%{$buscar}%");
            });
        }

        // Filtro por Categoría
        if ($request->filled('categoria_id')) {
            $query->where('categoria_id', $request->categoria_id);
        }

        // Filtro por Estado de Stock
        if ($request->filled('estado_stock')) {
            if ($request->estado_stock === 'agotado') {
                $query->where('stock', '<=', 0);
            } elseif ($request->estado_stock === 'bajo') {
                $query->whereColumn('stock', '<=', 'stock_minimo')->where('stock', '>', 0);
            } elseif ($request->estado_stock === 'optimo') {
                $query->whereColumn('stock', '>', 'stock_minimo');
            }
        }

        $productos = $query->latest()->get();
        $categorias = Categoria::withCount('productos')->get();

        // 📊 Métricas KPI del Inventario
        $totalModelos = Producto::count();
        $totalUnidades = Producto::sum('stock');
        $valorInventario = Producto::selectRaw('SUM(stock * precio_compra) as total_valor')->value('total_valor') ?? 0;
        $stockBajoCount = Producto::whereColumn('stock', '<=', 'stock_minimo')->where('stock', '>', 0)->count();
        $agotadosCount = Producto::where('stock', '<=', 0)->count();

        return view('productos.index', compact(
            'productos',
            'categorias',
            'totalModelos',
            'totalUnidades',
            'valorInventario',
            'stockBajoCount',
            'agotadosCount'
        ));
    }

    /**
     * ➕ Formulario Crear Producto
     */
    public function create()
    {
        $categorias = Categoria::all();
        return view('productos.create', compact('categorias'));
    }

    /**
     * 💾 Guardar Producto
     */
    public function store(Request $request)
    {
        $request->validate([
            'codigo' => 'required|string|max:50|unique:productos,codigo',
            'nombre' => 'required|string|max:255',
            'categoria_id' => 'required|exists:categorias,id',
            'precio_compra' => 'required|numeric|min:0',
            'precio_venta' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'stock_minimo' => 'nullable|integer|min:0',
            'stock_maximo' => 'nullable|integer|min:0',
            'fecha_ingreso' => 'nullable|date',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:2048'
        ], [
            'codigo.required' => 'El código o SKU del producto es obligatorio.',
            'codigo.unique' => 'Este código ya está registrado en el inventario.',
            'nombre.required' => 'El nombre del producto es obligatorio.',
            'categoria_id.required' => 'Debe seleccionar una categoría.',
            'categoria_id.exists' => 'La categoría seleccionada no es válida.',
            'precio_compra.required' => 'El precio de compra es obligatorio.',
            'precio_venta.required' => 'El precio de venta es obligatorio.',
            'stock.required' => 'El stock inicial es obligatorio.',
            'imagen.image' => 'El archivo debe ser una imagen válida (JPEG, PNG, WEBP).'
        ]);

        $imagen = null;
        if ($request->hasFile('imagen')) {
            $imagen = $request->file('imagen')->store('productos', 'public');
        }

        $producto = Producto::create([
            'codigo' => trim($request->codigo),
            'categoria_id' => $request->categoria_id,
            'nombre' => trim($request->nombre),
            'descripcion' => $request->descripcion,
            'imagen' => $imagen,
            'stock' => (int) $request->stock,
            'stock_minimo' => $request->stock_minimo ? (int) $request->stock_minimo : 5,
            'stock_maximo' => $request->stock_maximo ? (int) $request->stock_maximo : 100,
            'precio_compra' => (float) $request->precio_compra,
            'precio_venta' => (float) $request->precio_venta,
            'fecha_ingreso' => $request->fecha_ingreso ?: now()->toDateString(),
        ]);

        // Registrar movimiento inicial en Kardex si tiene stock
        if ($producto->stock > 0) {
            MovimientoInventario::create([
                'producto_id' => $producto->id,
                'user_id' => Auth::id(),
                'tipo' => 'inicial',
                'cantidad' => $producto->stock,
                'stock_anterior' => 0,
                'stock_nuevo' => $producto->stock,
                'costo_unitario' => $producto->precio_compra,
                'precio_unitario' => $producto->precio_venta,
                'motivo' => 'Inventario inicial al dar de alta el producto',
                'referencia_tipo' => 'App\Models\Producto',
                'referencia_id' => $producto->id,
            ]);
        }

        $producto->load(['categoria']);
        $catNombre = $producto->categoria ? $producto->categoria->nombre : 'Sin categoría';

        Bitacora::create([
            'user_id' => Auth::id(),
            'modulo' => 'Productos',
            'accion' => 'Crear',
            'descripcion' => "Registró el producto: {$producto->nombre} [{$producto->codigo}] | Stock: {$producto->stock} | Cat: {$catNombre}",
            'ip' => $request->ip(),
        ]);

        return redirect()->route('productos.index')
            ->with('success', "¡Producto '{$producto->nombre}' registrado correctamente!");
    }

    /**
     * 👁️ Ficha Detalle del Producto
     */
    public function show(Producto $producto)
    {
        $producto->load(['categoria', 'detalleVentas' => function($q) {
            $q->latest()->take(5);
        }, 'detalleCompras' => function($q) {
            $q->latest()->take(5);
        }]);

        return view('productos.show', compact('producto'));
    }

    /**
     * ✏️ Formulario Editar Producto
     */
    public function edit(Producto $producto)
    {
        $categorias = Categoria::all();
        return view('productos.edit', compact('producto', 'categorias'));
    }

    /**
     * 🔄 Actualizar Producto
     */
    public function update(Request $request, Producto $producto)
    {
        $request->validate([
            'codigo' => 'required|string|max:50|unique:productos,codigo,' . $producto->id,
            'nombre' => 'required|string|max:255',
            'categoria_id' => 'required|exists:categorias,id',
            'precio_compra' => 'required|numeric|min:0',
            'precio_venta' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'stock_minimo' => 'nullable|integer|min:0',
            'stock_maximo' => 'nullable|integer|min:0',
            'fecha_ingreso' => 'nullable|date',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:2048'
        ], [
            'codigo.required' => 'El código o SKU es obligatorio.',
            'codigo.unique' => 'Este código ya pertenece a otro producto.',
            'nombre.required' => 'El nombre del producto es obligatorio.',
            'categoria_id.required' => 'Debe seleccionar una categoría.',
            'precio_compra.required' => 'El precio de compra es obligatorio.',
            'precio_venta.required' => 'El precio de venta es obligatorio.',
            'stock.required' => 'El stock es obligatorio.'
        ]);

        $nombreAnterior = $producto->nombre;
        $stockAnterior = $producto->stock;
        $precioVentaAnterior = $producto->precio_venta;

        $imagen = $producto->imagen;
        if ($request->hasFile('imagen')) {
            if ($producto->imagen && Storage::disk('public')->exists($producto->imagen)) {
                Storage::disk('public')->delete($producto->imagen);
            }
            $imagen = $request->file('imagen')->store('productos', 'public');
        }

        $producto->update([
            'codigo' => trim($request->codigo),
            'categoria_id' => $request->categoria_id,
            'nombre' => trim($request->nombre),
            'descripcion' => $request->descripcion,
            'imagen' => $imagen,
            'stock' => (int) $request->stock,
            'stock_minimo' => $request->stock_minimo ? (int) $request->stock_minimo : 5,
            'stock_maximo' => $request->stock_maximo ? (int) $request->stock_maximo : 100,
            'precio_compra' => (float) $request->precio_compra,
            'precio_venta' => (float) $request->precio_venta,
            'fecha_ingreso' => $request->fecha_ingreso ?: $producto->fecha_ingreso,
        ]);
        // Si el stock cambió manualmente en la edición, registrar en Kardex
        if ($stockAnterior != $producto->stock) {
            $diff = $producto->stock - $stockAnterior;
            MovimientoInventario::create([
                'producto_id' => $producto->id,
                'user_id' => Auth::id(),
                'tipo' => $diff > 0 ? 'ajuste_positivo' : 'ajuste_negativo',
                'cantidad' => abs($diff),
                'stock_anterior' => $stockAnterior,
                'stock_nuevo' => $producto->stock,
                'costo_unitario' => $producto->precio_compra,
                'precio_unitario' => $producto->precio_venta,
                'motivo' => 'Ajuste manual directo desde la edición de producto',
                'referencia_tipo' => 'App\Models\Producto',
                'referencia_id' => $producto->id,
            ]);
        }

        // Detalles para bitácora
        $cambios = [];
        if ($nombreAnterior !== $producto->nombre) $cambios[] = "nombre: '{$nombreAnterior}' -> '{$producto->nombre}'";
        if ($stockAnterior != $producto->stock) $cambios[] = "stock: {$stockAnterior} -> {$producto->stock}";
        if ($precioVentaAnterior != $producto->precio_venta) $cambios[] = "precio venta: Bs. {$precioVentaAnterior} -> Bs. {$producto->precio_venta}";

        $desc = count($cambios) > 0 
            ? "Actualizó el producto {$producto->nombre} (" . implode(', ', $cambios) . ")"
            : "Actualizó los datos del producto {$producto->nombre}";

        Bitacora::create([
            'user_id' => Auth::id(),
            'modulo' => 'Productos',
            'accion' => 'Editar',
            'descripcion' => $desc,
            'ip' => $request->ip(),
        ]);

        return redirect()->route('productos.index')
            ->with('success', "¡Producto '{$producto->nombre}' actualizado correctamente!");
    }

    /**
     * 🗑️ Eliminar Producto con Validación de Integridad
     */
    public function destroy(Request $request, Producto $producto)
    {
        // 1. Verificar si el producto tiene historial en ventas o compras
        $tieneVentas = $producto->detalleVentas()->count() > 0;
        $tieneCompras = $producto->detalleCompras()->count() > 0;

        if ($tieneVentas || $tieneCompras) {
            $motivo = $tieneVentas ? 'comprobantes de venta' : 'registros de compra';
            return redirect()->route('productos.index')
                ->with('error', "No es posible eliminar el producto '{$producto->nombre}' porque está vinculado a {$motivo} históricos.");
        }

        $nombre = $producto->nombre;
        $codigo = $producto->codigo;

        if ($producto->imagen && Storage::disk('public')->exists($producto->imagen)) {
            Storage::disk('public')->delete($producto->imagen);
        }

        $producto->delete();

        Bitacora::create([
            'user_id' => Auth::id(),
            'modulo' => 'Productos',
            'accion' => 'Eliminar',
            'descripcion' => "Eliminó el producto: {$nombre} [{$codigo}] del catálogo",
            'ip' => $request->ip(),
        ]);

        return redirect()->route('productos.index')
            ->with('success', "Producto '{$nombre}' eliminado correctamente del inventario.");
    }
}