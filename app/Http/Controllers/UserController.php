<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Rol;
use App\Models\Bitacora;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * 📋 Listado de Usuarios con KPIs y Filtros
     */
    public function index(Request $request)
    {
        $query = User::with('rol');

        // Búsqueda por término (nombre o email)
        if ($request->filled('buscar')) {
            $buscar = trim($request->buscar);
            $query->where(function ($q) use ($buscar) {
                $q->where('name', 'like', "%{$buscar}%")
                  ->orWhere('email', 'like', "%{$buscar}%");
            });
        }

        // Filtro por Rol
        if ($request->filled('rol_id')) {
            if ($request->rol_id === 'sin_rol') {
                $query->whereNull('rol_id');
            } else {
                $query->where('rol_id', $request->rol_id);
            }
        }

        // Obtener listado ordenado
        $users = $query->latest()->get();

        // Lista de roles para el filtro y formularios
        $roles = Rol::withCount('users')->get();

        // 📊 Métricas KPI para el encabezado
        $totalUsuarios = User::count();
        $totalAdmins = User::whereHas('rol', function ($q) {
            $q->where('nombre', 'like', '%admin%');
        })->count();
        $totalConRol = User::whereNotNull('rol_id')->count();
        $totalSinRol = User::whereNull('rol_id')->count();
        $nuevosMes = User::where('created_at', '>=', now()->startOfMonth())->count();

        return view('users.index', compact(
            'users',
            'roles',
            'totalUsuarios',
            'totalAdmins',
            'totalConRol',
            'totalSinRol',
            'nuevosMes'
        ));
    }

    /**
     * ➕ Formulario para Crear Usuario
     */
    public function create()
    {
        $roles = Rol::withCount('permisos')->get();
        return view('users.create', compact('roles'));
    }

    /**
     * 💾 Guardar Nuevo Usuario
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'rol_id' => 'nullable|exists:rols,id'
        ], [
            'name.required' => 'El nombre completo es obligatorio.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.unique' => 'Este correo electrónico ya se encuentra registrado.',
            'email.email' => 'Ingrese una dirección de correo válida.',
            'password.required' => 'La contraseña es obligatoria.',
            'password.min' => 'La contraseña debe contener al menos 6 caracteres.',
            'password.confirmed' => 'La confirmación de la contraseña no coincide.',
            'rol_id.exists' => 'El rol seleccionado no es válido.'
        ]);

        $user = User::create([
            'name' => trim($request->name),
            'email' => trim($request->email),
            'password' => Hash::make($request->password),
            'rol_id' => $request->rol_id ?: null
        ]);

        $rolNombre = $user->rol ? $user->rol->nombre : 'Sin Rol Asignado';

        Bitacora::create([
            'user_id' => Auth::id(),
            'modulo' => 'Usuarios',
            'accion' => 'Crear',
            'descripcion' => "Creó el usuario: {$user->name} ({$user->email}) con rol [{$rolNombre}]",
            'ip' => $request->ip(),
        ]);

        return redirect()->route('users.index')
            ->with('success', "¡Usuario {$user->name} creado exitosamente!");
    }

    /**
     * ✏️ Formulario para Editar Usuario
     */
    public function edit(User $user)
    {
        $roles = Rol::withCount('permisos')->get();
        
        // Cargar relación del rol y bitácoras recientes del usuario
        $user->load(['rol.permisos', 'bitacoras' => function($q) {
            $q->latest()->take(5);
        }]);

        return view('users.edit', compact('user', 'roles'));
    }

    /**
     * 🔄 Actualizar Datos del Usuario
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6|confirmed',
            'rol_id' => 'nullable|exists:rols,id'
        ], [
            'name.required' => 'El nombre completo es obligatorio.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.unique' => 'Este correo ya pertenece a otro usuario registrado.',
            'password.min' => 'La nueva contraseña debe tener al menos 6 caracteres.',
            'password.confirmed' => 'La confirmación de la contraseña no coincide.',
            'rol_id.exists' => 'El rol seleccionado no es válido.'
        ]);

        $nombreAnterior = $user->name;
        $emailAnterior = $user->email;
        $rolAnterior = $user->rol ? $user->rol->nombre : 'Sin Rol';

        // Salvaguarda: si el usuario activo es el único admin, no permitir quitarse el rol de admin
        if ($user->id === Auth::id() && $user->esAdmin()) {
            $adminsRestantes = User::whereHas('rol', fn($q) => $q->where('nombre', 'like', '%admin%'))
                ->where('id', '!=', $user->id)
                ->count();
                
            $nuevoRol = $request->rol_id ? Rol::find($request->rol_id) : null;
            $nuevoEsAdmin = $nuevoRol && $nuevoRol->esAdmin();

            if ($adminsRestantes === 0 && !$nuevoEsAdmin) {
                return back()->withInput()->with('error', 'No puedes retirar tus privilegios de Administrador ya que eres el único administrador activo del sistema.');
            }
        }

        $data = [
            'name' => trim($request->name),
            'email' => trim($request->email),
            'rol_id' => $request->rol_id ?: null
        ];

        $cambioPassword = false;
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
            $cambioPassword = true;
        }

        $user->update($data);
        $user->load('rol');
        $nuevoRolNombre = $user->rol ? $user->rol->nombre : 'Sin Rol';

        // Descripción detallada para bitácora
        $detalles = [];
        if ($nombreAnterior !== $user->name) $detalles[] = "nombre: '{$nombreAnterior}' -> '{$user->name}'";
        if ($emailAnterior !== $user->email) $detalles[] = "email: '{$emailAnterior}' -> '{$user->email}'";
        if ($rolAnterior !== $nuevoRolNombre) $detalles[] = "rol: '{$rolAnterior}' -> '{$nuevoRolNombre}'";
        if ($cambioPassword) $detalles[] = "contraseña actualizada";

        $desc = count($detalles) > 0 
            ? "Actualizó al usuario {$user->name} (" . implode(', ', $detalles) . ")"
            : "Actualizó los datos del usuario {$user->name}";

        Bitacora::create([
            'user_id' => Auth::id(),
            'modulo' => 'Usuarios',
            'accion' => 'Editar',
            'descripcion' => $desc,
            'ip' => $request->ip(),
        ]);

        return redirect()->route('users.index')
            ->with('success', "¡Usuario {$user->name} actualizado correctamente!");
    }

    /**
     * 🗑️ Eliminar Usuario con Protecciones
     */
    public function destroy(Request $request, User $user)
    {
        // 1. Protección: No permitir que el usuario actual se elimine a sí mismo
        if ($user->id === Auth::id()) {
            return redirect()->route('users.index')
                ->with('error', 'Acción denegada: No puedes eliminar tu propia cuenta de usuario en sesión activa.');
        }

        // 2. Protección: No permitir eliminar el último administrador
        if ($user->esAdmin()) {
            $otrosAdmins = User::whereHas('rol', fn($q) => $q->where('nombre', 'like', '%admin%'))
                ->where('id', '!=', $user->id)
                ->count();

            if ($otrosAdmins === 0) {
                return redirect()->route('users.index')
                    ->with('error', 'Acción denegada: No se puede eliminar al único Administrador del sistema.');
            }
        }

        $nombre = $user->name;
        $email = $user->email;
        $rolNombre = $user->rol ? $user->rol->nombre : 'Sin Rol';

        $user->delete();

        Bitacora::create([
            'user_id' => Auth::id(),
            'modulo' => 'Usuarios',
            'accion' => 'Eliminar',
            'descripcion' => "Eliminó al usuario: {$nombre} ({$email}) que tenía rol [{$rolNombre}]",
            'ip' => $request->ip(),
        ]);

        return redirect()->route('users.index')
            ->with('success', "Usuario {$nombre} eliminado correctamente del sistema.");
    }
}