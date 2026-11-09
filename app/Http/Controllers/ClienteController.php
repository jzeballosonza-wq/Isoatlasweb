<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cliente;
use App\Models\Bitacora;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ClienteController extends Controller
{
    /**
     * Display a listing of the resource with advanced filters and KPIs.
     */
    public function index(Request $request)
    {
        $query = Cliente::query();

        // 1. Filtro de búsqueda general por texto
        if ($request->filled('buscar')) {
            $buscar = trim($request->buscar);
            $query->where(function ($q) use ($buscar) {
                $q->where('nombre', 'like', "%{$buscar}%")
                  ->orWhere('nit', 'like', "%{$buscar}%")
                  ->orWhere('telefono', 'like', "%{$buscar}%")
                  ->orWhere('email', 'like', "%{$buscar}%")
                  ->orWhere('id', 'like', "%{$buscar}%");
            });
        }

        // 2. Filtro rápido por tipo de información
        if ($request->filled('tipo_filtro')) {
            switch ($request->tipo_filtro) {
                case 'con_telefono':
                    $query->whereNotNull('telefono')->where('telefono', '!=', '');
                    break;
                case 'con_email':
                    $query->whereNotNull('email')->where('email', '!=', '');
                    break;
                case 'con_nit':
                    $query->whereNotNull('nit')->where('nit', '!=', '');
                    break;
                case 'este_mes':
                    $query->whereMonth('created_at', Carbon::now()->month)
                          ->whereYear('created_at', Carbon::now()->year);
                    break;
            }
        }

        // 3. Filtro por rango de fechas de registro
        if ($request->filled('fecha_desde')) {
            $query->whereDate('created_at', '>=', $request->fecha_desde);
        }

        if ($request->filled('fecha_hasta')) {
            $query->whereDate('created_at', '<=', $request->fecha_hasta);
        }

        // 4. Ordenamiento
        $orden = $request->get('orden', 'recientes');
        switch ($orden) {
            case 'antiguos':
                $query->orderBy('id', 'asc');
                break;
            case 'nombre_asc':
                $query->orderBy('nombre', 'asc');
                break;
            case 'nombre_desc':
                $query->orderBy('nombre', 'desc');
                break;
            case 'recientes':
            default:
                $query->orderBy('id', 'desc');
                break;
        }

        // Paginación
        $perPage = $request->get('per_page', 10);
        $clientes = $query->paginate($perPage)->withQueryString();

        // Métricas KPI
        $totalClientes = Cliente::count();
        $conTelefono = Cliente::whereNotNull('telefono')->where('telefono', '!=', '')->count();
        $conEmail = Cliente::whereNotNull('email')->where('email', '!=', '')->count();
        $conNit = Cliente::whereNotNull('nit')->where('nit', '!=', '')->count();
        $nuevosEsteMes = Cliente::whereMonth('created_at', Carbon::now()->month)
                                ->whereYear('created_at', Carbon::now()->year)
                                ->count();

        // Si es petición AJAX/JSON retornamos listado
        if ($request->wantsJson() && !$request->has('page')) {
            return response()->json([
                'success' => true,
                'clientes' => $clientes,
                'kpis' => [
                    'total' => $totalClientes,
                    'con_telefono' => $conTelefono,
                    'con_email' => $conEmail,
                    'con_nit' => $conNit,
                    'nuevos_mes' => $nuevosEsteMes
                ]
            ]);
        }

        return view('clientes.index', compact(
            'clientes',
            'totalClientes',
            'conTelefono',
            'conEmail',
            'conNit',
            'nuevosEsteMes'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('clientes.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre'   => 'required|string|max:255',
            'nit'      => 'nullable|string|max:50',
            'telefono' => 'nullable|string|max:30',
            'email'    => 'nullable|email|max:255',
        ], [
            'nombre.required' => 'El nombre o razón social del cliente es obligatorio.',
            'nombre.max'      => 'El nombre no debe superar los 255 caracteres.',
            'email.email'     => 'Por favor, ingrese una dirección de correo electrónico válida.',
            'telefono.max'    => 'El número de teléfono no debe exceder los 30 caracteres.',
        ]);

        $cliente = Cliente::create($validated);

        // Registro en Bitácora
        if (Auth::check()) {
            Bitacora::create([
                'user_id'     => Auth::id(),
                'modulo'      => 'Clientes',
                'accion'      => 'Crear',
                'descripcion' => 'Registró al cliente: ' . $cliente->nombre . ($cliente->nit ? ' (NIT: ' . $cliente->nit . ')' : ''),
                'ip'          => $request->ip(),
            ]);
        }

        // Respuesta AJAX / JSON
        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'mensaje' => '¡Cliente registrado con éxito!',
                'cliente' => $cliente
            ]);
        }

        // Si el usuario presionó "Guardar y Crear Otro"
        if ($request->has('guardar_otro') && $request->guardar_otro == '1') {
            return redirect()
                ->route('clientes.create')
                ->with('success', 'Cliente "' . $cliente->nombre . '" registrado con éxito. Puede registrar el siguiente.');
        }

        return redirect()
            ->route('clientes.index')
            ->with('success', 'Cliente "' . $cliente->nombre . '" registrado con éxito.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, $id)
    {
        $cliente = Cliente::findOrFail($id);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'cliente' => $cliente,
                'created_at_formatted' => $cliente->created_at ? $cliente->created_at->format('d/m/Y H:i') : '-',
                'updated_at_formatted' => $cliente->updated_at ? $cliente->updated_at->format('d/m/Y H:i') : '-',
            ]);
        }

        return view('clientes.show', compact('cliente'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $cliente = Cliente::findOrFail($id);

        return view('clientes.edit', compact('cliente'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $cliente = Cliente::findOrFail($id);

        $validated = $request->validate([
            'nombre'   => 'required|string|max:255',
            'nit'      => 'nullable|string|max:50',
            'telefono' => 'nullable|string|max:30',
            'email'    => 'nullable|email|max:255',
        ], [
            'nombre.required' => 'El nombre del cliente es obligatorio.',
            'nombre.max'      => 'El nombre no debe superar los 255 caracteres.',
            'email.email'     => 'Ingrese un correo electrónico válido.',
            'telefono.max'    => 'El teléfono no debe superar los 30 caracteres.',
        ]);

        $nombreAnterior = $cliente->nombre;
        $cliente->update($validated);

        // Registro en Bitácora
        if (Auth::check()) {
            Bitacora::create([
                'user_id'     => Auth::id(),
                'modulo'      => 'Clientes',
                'accion'      => 'Editar',
                'descripcion' => 'Actualizó los datos del cliente: ' . $nombreAnterior . ($nombreAnterior !== $cliente->nombre ? ' a ' . $cliente->nombre : ''),
                'ip'          => $request->ip(),
            ]);
        }

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'mensaje' => '¡Cliente actualizado con éxito!',
                'cliente' => $cliente
            ]);
        }

        return redirect()
            ->route('clientes.index')
            ->with('success', 'Cliente "' . $cliente->nombre . '" actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, $id)
    {
        $cliente = Cliente::findOrFail($id);
        $nombre = $cliente->nombre;

        $cliente->delete();

        // Registro en Bitácora
        if (Auth::check()) {
            Bitacora::create([
                'user_id'     => Auth::id(),
                'modulo'      => 'Clientes',
                'accion'      => 'Eliminar',
                'descripcion' => 'Eliminó al cliente: ' . $nombre,
                'ip'          => $request->ip(),
            ]);
        }

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'mensaje' => 'Cliente eliminado con éxito.'
            ]);
        }

        return redirect()
            ->route('clientes.index')
            ->with('success', 'Cliente "' . $nombre . '" eliminado del sistema.');
    }
}