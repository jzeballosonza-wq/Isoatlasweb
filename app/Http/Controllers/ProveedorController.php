<?php

namespace App\Http\Controllers;

use App\Models\Proveedor;
use App\Models\Compra;
use App\Models\Bitacora;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProveedorController extends Controller
{
    /**
     * 📋 Listado General de Proveedores con Métricas y Filtros
     */
    public function index(Request $request)
    {
        $query = Proveedor::withCount('compras')
            ->withSum('compras', 'total');

        // Búsqueda por texto (empresa, contacto, teléfono, email, dirección)
        if ($request->filled('buscar')) {
            $buscar = trim($request->buscar);
            $query->where(function ($q) use ($buscar) {
                $q->where('nombre', 'like', "%{$buscar}%")
                  ->orWhere('contacto', 'like', "%{$buscar}%")
                  ->orWhere('telefono', 'like', "%{$buscar}%")
                  ->orWhere('celular', 'like', "%{$buscar}%")
                  ->orWhere('email', 'like', "%{$buscar}%")
                  ->orWhere('direccion', 'like', "%{$buscar}%");
            });
        }

        // Filtro por Actividad comercial
        if ($request->filled('estado')) {
            if ($request->estado === 'con_compras') {
                $query->has('compras');
            } elseif ($request->estado === 'sin_compras') {
                $query->doesntHave('compras');
            }
        }

        $proveedores = $query->latest('id')->get();

        // 📊 Métricas Globales del Directorio
        $totalProveedores = Proveedor::count();
        $totalCompras = Compra::count();
        $montoTotalComprado = Compra::sum('total') ?? 0;
        $proveedoresActivos = Proveedor::has('compras')->count();

        return view('proveedores.index', compact(
            'proveedores',
            'totalProveedores',
            'totalCompras',
            'montoTotalComprado',
            'proveedoresActivos'
        ));
    }

    /**
     * ➕ Formulario Crear Proveedor
     */
    public function create()
    {
        return view('proveedores.create');
    }

    /**
     * 💾 Guardar Proveedor
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre'    => 'required|string|max:255',
            'direccion' => 'required|string|max:255',
            'telefono'  => 'required|string|max:50',
            'email'     => 'nullable|email|max:150',
            'contacto'  => 'nullable|string|max:150',
            'celular'   => 'nullable|string|max:50',
        ], [
            'nombre.required'    => 'El nombre o razón social de la empresa proveedora es obligatorio.',
            'direccion.required' => 'La dirección física o fiscal es obligatoria.',
            'telefono.required'  => 'El número de teléfono fijo o de contacto es obligatorio.',
            'email.email'        => 'Ingrese una dirección de correo electrónico válida.',
        ]);

        $proveedor = Proveedor::create([
            'nombre'    => trim($request->nombre),
            'direccion' => trim($request->direccion),
            'telefono'  => trim($request->telefono),
            'email'     => $request->email ? trim($request->email) : null,
            'contacto'  => $request->contacto ? trim($request->contacto) : null,
            'celular'   => $request->celular ? trim($request->celular) : null,
        ]);

        if (Auth::check()) {
            Bitacora::create([
                'user_id'     => Auth::id(),
                'modulo'      => 'Proveedores',
                'accion'      => 'Crear',
                'descripcion' => "Registró al proveedor: {$proveedor->nombre} | Contacto: " . ($proveedor->contacto ?: 'N/A') . " (Tel: {$proveedor->telefono})",
                'ip'          => $request->ip(),
            ]);
        }

        return redirect()->route('proveedores.index')
            ->with('success', "¡Proveedor '{$proveedor->nombre}' registrado correctamente!");
    }

    /**
     * 👁️ Ficha y Perfil Detallado del Proveedor
     */
    public function show(Proveedor $proveedore)
    {
        $proveedore->load(['compras' => function ($q) {
            $q->with('detalles.producto')->latest('fecha')->latest('id');
        }]);

        $totalCompras = $proveedore->compras->count();
        $totalInvertido = $proveedore->compras->sum('total') ?? 0;
        $promedioPorOrden = $totalCompras > 0 ? ($totalInvertido / $totalCompras) : 0;
        $ultimaCompra = $proveedore->compras->first();

        return view('proveedores.show', compact(
            'proveedore',
            'totalCompras',
            'totalInvertido',
            'promedioPorOrden',
            'ultimaCompra'
        ));
    }

    /**
     * ✏️ Formulario Editar Proveedor
     */
    public function edit(Proveedor $proveedore)
    {
        return view('proveedores.edit', compact('proveedore'));
    }

    /**
     * 🔄 Actualizar Proveedor
     */
    public function update(Request $request, Proveedor $proveedore)
    {
        $request->validate([
            'nombre'    => 'required|string|max:255',
            'direccion' => 'required|string|max:255',
            'telefono'  => 'required|string|max:50',
            'email'     => 'nullable|email|max:150',
            'contacto'  => 'nullable|string|max:150',
            'celular'   => 'nullable|string|max:50',
        ], [
            'nombre.required'    => 'El nombre o razón social de la empresa es obligatorio.',
            'direccion.required' => 'La dirección es obligatoria.',
            'telefono.required'  => 'El teléfono es obligatorio.',
            'email.email'        => 'Ingrese un correo electrónico válido.',
        ]);

        $nombreAnterior = $proveedore->nombre;
        $telefonoAnterior = $proveedore->telefono;

        $proveedore->update([
            'nombre'    => trim($request->nombre),
            'direccion' => trim($request->direccion),
            'telefono'  => trim($request->telefono),
            'email'     => $request->email ? trim($request->email) : null,
            'contacto'  => $request->contacto ? trim($request->contacto) : null,
            'celular'   => $request->celular ? trim($request->celular) : null,
        ]);

        if (Auth::check()) {
            $cambios = [];
            if ($nombreAnterior !== $proveedore->nombre) $cambios[] = "nombre: '{$nombreAnterior}' -> '{$proveedore->nombre}'";
            if ($telefonoAnterior !== $proveedore->telefono) $cambios[] = "tel: '{$telefonoAnterior}' -> '{$proveedore->telefono}'";

            $desc = count($cambios) > 0
                ? "Actualizó los datos del proveedor {$proveedore->nombre} (" . implode(', ', $cambios) . ")"
                : "Actualizó los datos del proveedor {$proveedore->nombre}";

            Bitacora::create([
                'user_id'     => Auth::id(),
                'modulo'      => 'Proveedores',
                'accion'      => 'Editar',
                'descripcion' => $desc,
                'ip'          => $request->ip(),
            ]);
        }

        return redirect()->route('proveedores.index')
            ->with('success', "¡Proveedor '{$proveedore->nombre}' actualizado exitosamente!");
    }

    /**
     * 🗑️ Eliminar Proveedor con Validación de Integridad
     */
    public function destroy(Request $request, Proveedor $proveedore)
    {
        $comprasCount = $proveedore->compras()->count();

        if ($comprasCount > 0) {
            return redirect()->route('proveedores.index')
                ->with('error', "No es posible eliminar a '{$proveedore->nombre}' porque tiene {$comprasCount} orden(es) de compra registradas en el historial comercial.");
        }

        $nombre = $proveedore->nombre;
        $proveedore->delete();

        if (Auth::check()) {
            Bitacora::create([
                'user_id'     => Auth::id(),
                'modulo'      => 'Proveedores',
                'accion'      => 'Eliminar',
                'descripcion' => "Eliminó del directorio al proveedor: {$nombre}",
                'ip'          => $request->ip(),
            ]);
        }

        return redirect()->route('proveedores.index')
            ->with('success', "Proveedor '{$nombre}' eliminado satisfactoriamente del directorio.");
    }
}