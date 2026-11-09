<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Categoria;
use App\Models\Producto;
use App\Models\Bitacora;
use Illuminate\Support\Facades\Auth;

class CategoriaController extends Controller
{
    /**
     * 📋 Listado de Categorías con KPIs, Filtros y Conteo de Productos
     */
    public function index(Request $request)
    {
        $query = Categoria::withCount('productos')
            ->with(['productos' => function ($q) {
                $q->select('id', 'categoria_id', 'stock', 'precio_compra', 'precio_venta');
            }]);

        // 🔍 Filtro de búsqueda por nombre o descripción
        if ($request->filled('buscar')) {
            $buscar = trim($request->buscar);
            $query->where(function ($q) use ($buscar) {
                $q->where('nombre', 'like', "%{$buscar}%")
                  ->orWhere('descripcion', 'like', "%{$buscar}%");
            });
        }

        // 🔀 Ordenamiento
        $orden = $request->get('orden', 'nombre_asc');
        switch ($orden) {
            case 'mas_productos':
                $query->orderByDesc('productos_count');
                break;
            case 'menos_productos':
                $query->orderBy('productos_count');
                break;
            case 'recientes':
                $query->latest();
                break;
            case 'nombre_desc':
                $query->orderByDesc('nombre');
                break;
            case 'nombre_asc':
            default:
                $query->orderBy('nombre', 'asc');
                break;
        }

        $categorias = $query->get();

        // 📊 Métricas Globales del Módulo de Categorías
        $totalCategorias = Categoria::count();
        $totalModelos = Producto::count();
        $totalStockGlobal = Producto::sum('stock');
        
        // Categoría Líder con más variedad de productos
        $categoriaLider = Categoria::withCount('productos')
            ->orderByDesc('productos_count')
            ->first();

        // Categoría con mayor inventario en unidades
        $categoriasConStock = $categorias->map(function ($cat) {
            $cat->stock_total_acumulado = $cat->productos->sum('stock');
            $cat->valor_inventario_acumulado = $cat->productos->sum(function ($p) {
                return $p->stock * $p->precio_compra;
            });
            return $cat;
        });

        $categoriaMayorStock = $categoriasConStock->sortByDesc('stock_total_acumulado')->first();

        return view('categorias.index', compact(
            'categorias',
            'totalCategorias',
            'totalModelos',
            'totalStockGlobal',
            'categoriaLider',
            'categoriaMayorStock'
        ));
    }

    /**
     * ➕ Formulario Crear Categoría
     */
    public function create()
    {
        return view('categorias.create');
    }

    /**
     * 💾 Guardar Nueva Categoría
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:100|unique:categorias,nombre',
            'descripcion' => 'nullable|string|max:500',
        ], [
            'nombre.required' => 'El nombre de la categoría es obligatorio.',
            'nombre.max' => 'El nombre no puede exceder los 100 caracteres.',
            'nombre.unique' => 'Ya existe una categoría con este nombre en el sistema.',
            'descripcion.max' => 'La descripción no puede exceder los 500 caracteres.',
        ]);

        $categoria = Categoria::create([
            'nombre' => trim($request->nombre),
            'descripcion' => $request->filled('descripcion') ? trim($request->descripcion) : null,
        ]);

        Bitacora::create([
            'user_id' => Auth::id(),
            'modulo' => 'Categorías',
            'accion' => 'Crear',
            'descripcion' => "Creó la categoría: {$categoria->nombre}",
            'ip' => $request->ip(),
        ]);

        return redirect()->route('categorias.index')
            ->with('success', "¡Categoría '{$categoria->nombre}' creada exitosamente!");
    }

    /**
     * 👁️ Perfil y Catálogo Detallado de la Categoría
     */
    public function show(Categoria $categoria)
    {
        // Cargar productos pertenecientes a esta categoría
        $productos = $categoria->productos()
            ->latest()
            ->get();

        $totalModelos = $productos->count();
        $totalUnidades = $productos->sum('stock');
        $valorInventario = $productos->sum(function ($p) {
            return $p->stock * $p->precio_compra;
        });
        $stockBajoCount = $productos->filter(function ($p) {
            return $p->stock <= $p->stock_minimo && $p->stock > 0;
        })->count();
        $agotadosCount = $productos->filter(function ($p) {
            return $p->stock <= 0;
        })->count();

        return view('categorias.show', compact(
            'categoria',
            'productos',
            'totalModelos',
            'totalUnidades',
            'valorInventario',
            'stockBajoCount',
            'agotadosCount'
        ));
    }

    /**
     * ✏️ Formulario Editar Categoría
     */
    public function edit(Categoria $categoria)
    {
        $categoria->loadCount('productos');
        return view('categorias.edit', compact('categoria'));
    }

    /**
     * 🔄 Actualizar Datos de la Categoría
     */
    public function update(Request $request, Categoria $categoria)
    {
        $request->validate([
            'nombre' => 'required|string|max:100|unique:categorias,nombre,' . $categoria->id,
            'descripcion' => 'nullable|string|max:500',
        ], [
            'nombre.required' => 'El nombre de la categoría es obligatorio.',
            'nombre.max' => 'El nombre no puede exceder los 100 caracteres.',
            'nombre.unique' => 'Ya existe otra categoría registrada con este nombre.',
            'descripcion.max' => 'La descripción no puede exceder los 500 caracteres.',
        ]);

        $nombreAnterior = $categoria->nombre;
        $descAnterior = $categoria->descripcion;

        $categoria->update([
            'nombre' => trim($request->nombre),
            'descripcion' => $request->filled('descripcion') ? trim($request->descripcion) : null,
        ]);

        $cambios = [];
        if ($nombreAnterior !== $categoria->nombre) {
            $cambios[] = "nombre: '{$nombreAnterior}' -> '{$categoria->nombre}'";
        }
        if ($descAnterior !== $categoria->descripcion) {
            $cambios[] = "descripción actualizada";
        }

        $descBitacora = count($cambios) > 0
            ? "Actualizó la categoría {$categoria->nombre} (" . implode(', ', $cambios) . ")"
            : "Actualizó datos de la categoría {$categoria->nombre}";

        Bitacora::create([
            'user_id' => Auth::id(),
            'modulo' => 'Categorías',
            'accion' => 'Editar',
            'descripcion' => $descBitacora,
            'ip' => $request->ip(),
        ]);

        return redirect()->route('categorias.index')
            ->with('success', "¡Categoría '{$categoria->nombre}' actualizada correctamente!");
    }

    /**
     * 🗑️ Eliminar Categoría con Protección de Integridad Referencial
     */
    public function destroy(Request $request, Categoria $categoria)
    {
        // 🔒 Protección: Si existen productos asociados, bloquear la eliminación
        $productosCount = $categoria->productos()->count();

        if ($productosCount > 0) {
            return redirect()->route('categorias.index')
                ->with('error', "No es posible eliminar la categoría '{$categoria->nombre}' porque tiene {$productosCount} producto(s) asignado(s). Primero reasigne o elimine los productos de esta categoría.");
        }

        $nombre = $categoria->nombre;
        $categoria->delete();

        Bitacora::create([
            'user_id' => Auth::id(),
            'modulo' => 'Categorías',
            'accion' => 'Eliminar',
            'descripcion' => "Eliminó la categoría: {$nombre}",
            'ip' => $request->ip(),
        ]);

        return redirect()->route('categorias.index')
            ->with('success', "Categoría '{$nombre}' eliminada correctamente del sistema.");
    }
}