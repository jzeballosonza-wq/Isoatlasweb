<?php

namespace App\Http\Controllers;

use App\Models\Rol;
use App\Models\Permiso;
use App\Models\User;
use App\Models\Bitacora;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RolController extends Controller
{
    /**
     * Definición de los permisos estándar del sistema agrupados por módulo
     */
    public static function getPermisosEstandar(): array
    {
        return [
            'comercial' => [
                'titulo' => 'Módulos Comerciales y Transacciones',
                'icono' => 'fas fa-cash-register',
                'color' => '#10b981',
                'permisos' => [
                    [
                        'clave' => 'ventas',
                        'nombre' => 'Ventas y Comprobantes',
                        'descripcion' => 'Permite emitir comprobantes de venta, registrar transacciones y consultar historial de facturación.',
                        'icono' => 'fas fa-receipt'
                    ],
                    [
                        'clave' => 'compras',
                        'nombre' => 'Compras a Proveedores',
                        'descripcion' => 'Permite registrar compras de productos, periféricos y suministros, gestionar órdenes de abastecimiento y costos.',
                        'icono' => 'fas fa-shopping-cart'
                    ],
                    [
                        'clave' => 'clientes',
                        'nombre' => 'Directorio de Clientes',
                        'descripcion' => 'Permite registrar, actualizar y consultar información de clientes particulares y corporativos.',
                        'icono' => 'fas fa-user-friends'
                    ],
                    [
                        'clave' => 'proveedores',
                        'nombre' => 'Directorio de Proveedores',
                        'descripcion' => 'Permite gestionar proveedores de productos electrónicos, marcas aliadas y datos de contacto de distribuidores.',
                        'icono' => 'fas fa-truck-loading'
                    ],
                    [
                        'clave' => 'caja',
                        'nombre' => 'Arqueo de Caja y Flujo de Efectivo',
                        'descripcion' => 'Permite aperturar turnos, registrar ingresos/gastos de efectivo, y realizar el arqueo y cierre de caja.',
                        'icono' => 'fas fa-cash-register'
                    ],
                ]
            ],
            'catalogo' => [
                'titulo' => 'Catálogo e Inventario',
                'icono' => 'fas fa-boxes-stacked',
                'color' => '#6366f1',
                'permisos' => [
                    [
                        'clave' => 'productos',
                        'nombre' => 'Catálogo de Productos & Stock',
                        'descripcion' => 'Permite gestionar productos, periféricos, especificaciones técnicas, precios de compra y venta.',
                        'icono' => 'fas fa-boxes-stacked'
                    ],
                    [
                        'clave' => 'inventario',
                        'nombre' => 'Control de Inventario & Kardex',
                        'descripcion' => 'Permite auditar el Kardex físico, registrar ingresos/egresos manuales y balances valorizados.',
                        'icono' => 'fas fa-exchange-alt'
                    ],
                    [
                        'clave' => 'categorias',
                        'nombre' => 'Categorías y Familias',
                        'descripcion' => 'Permite organizar productos en categorías (Teclados, Mouse, Auriculares, Cámaras, Periféricos, etc.).',
                        'icono' => 'fas fa-tags'
                    ],
                ]
            ],
            'seguridad' => [
                'titulo' => 'Administración, Sistema y Seguridad',
                'icono' => 'fas fa-shield-alt',
                'color' => '#f59e0b',
                'permisos' => [
                    [
                        'clave' => 'usuarios',
                        'nombre' => 'Gestión de Usuarios',
                        'descripcion' => 'Permite crear, editar y administrar cuentas de acceso del personal del sistema.',
                        'icono' => 'fas fa-users-cog'
                    ],
                    [
                        'clave' => 'roles',
                        'nombre' => 'Roles y Permisos (RBAC)',
                        'descripcion' => 'Permite configurar perfiles de acceso y matriz de permisos de seguridad.',
                        'icono' => 'fas fa-user-shield'
                    ],
                    [
                        'clave' => 'bitacoras',
                        'nombre' => 'Bitácora y Auditoría',
                        'descripcion' => 'Permite consultar el registro cronológico de acciones y accesos del personal en el sistema.',
                        'icono' => 'fas fa-history'
                    ],
                    [
                        'clave' => 'backups',
                        'nombre' => 'Copias de Seguridad (Backups)',
                        'descripcion' => 'Permite generar, descargar y restaurar copias de respaldo de la base de datos.',
                        'icono' => 'fas fa-database'
                    ],
                ]
            ]
        ];
    }

    /**
     * Asegura que todos los permisos estándar existan en la base de datos y depura obsoletos
     */
    protected function asegurarPermisosEstandar()
    {
        $modulos = self::getPermisosEstandar();
        $clavesValidas = [];
        foreach ($modulos as $modulo) {
            foreach ($modulo['permisos'] as $p) {
                Permiso::firstOrCreate(['nombre' => $p['clave']]);
                $clavesValidas[] = $p['clave'];
            }
        }

        // Eliminar permisos obsoletos que ya no forman parte de los módulos activos
        $obsoletos = Permiso::whereNotIn('nombre', $clavesValidas)->get();
        foreach ($obsoletos as $obs) {
            $obs->roles()->detach();
            $obs->delete();
        }
    }

    /**
     * 📋 Listado de roles con KPIs y Conteo
     */
    public function index(Request $request)
    {
        $this->asegurarPermisosEstandar();

        $query = Rol::withCount(['users', 'permisos']);

        if ($request->filled('buscar')) {
            $buscar = trim($request->buscar);
            $query->where(function ($q) use ($buscar) {
                $q->where('nombre', 'like', "%{$buscar}%")
                  ->orWhere('descripcion', 'like', "%{$buscar}%");
            });
        }

        $roles = $query->orderBy('id', 'asc')->get();

        // 📊 Métricas KPI
        $totalRoles = Rol::count();
        $totalPermisos = Permiso::count();
        $totalUsuariosConRol = User::whereNotNull('rol_id')->count();
        $totalUsuariosSinRol = User::whereNull('rol_id')->count();

        return view('roles.index', compact(
            'roles',
            'totalRoles',
            'totalPermisos',
            'totalUsuariosConRol',
            'totalUsuariosSinRol'
        ));
    }

    /**
     * ➕ Formulario crear rol
     */
    public function create()
    {
        $this->asegurarPermisosEstandar();
        $modulosPermisos = self::getPermisosEstandar();
        $todosPermisos = Permiso::all()->keyBy('nombre');

        return view('roles.create', compact('modulosPermisos', 'todosPermisos'));
    }

    /**
     * 💾 Guardar nuevo rol
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:100|unique:rols,nombre',
            'descripcion' => 'nullable|string|max:500',
            'permisos' => 'nullable|array',
            'permisos.*' => 'exists:permisos,id'
        ], [
            'nombre.required' => 'El nombre del rol es obligatorio.',
            'nombre.unique' => 'Ya existe un rol registrado con este nombre.',
            'nombre.max' => 'El nombre no debe superar los 100 caracteres.',
            'descripcion.max' => 'La descripción no debe superar los 500 caracteres.'
        ]);

        $rol = Rol::create([
            'nombre' => trim($request->nombre),
            'descripcion' => trim($request->descripcion)
        ]);

        // Sincronizar permisos si fueron seleccionados en el formulario
        if ($request->has('permisos') && is_array($request->permisos)) {
            $rol->permisos()->sync($request->permisos);
        }

        $permisosCount = $rol->permisos()->count();

        Bitacora::create([
            'user_id' => Auth::id(),
            'modulo' => 'Roles',
            'accion' => 'Crear',
            'descripcion' => "Creó el rol: {$rol->nombre} con {$permisosCount} permiso(s) asignado(s)",
            'ip' => $request->ip(),
        ]);

        return redirect()
            ->route('roles.index')
            ->with('success', "¡Rol '{$rol->nombre}' creado exitosamente!");
    }

    /**
     * ✏️ Formulario editar rol
     */
    public function edit(Rol $role)
    {
        $this->asegurarPermisosEstandar();
        $role->load(['permisos', 'users']);

        return view('roles.edit', compact('role'));
    }

    /**
     * 🔄 Actualizar rol
     */
    public function update(Request $request, Rol $role)
    {
        $request->validate([
            'nombre' => 'required|string|max:100|unique:rols,nombre,' . $role->id,
            'descripcion' => 'nullable|string|max:500'
        ], [
            'nombre.required' => 'El nombre del rol es obligatorio.',
            'nombre.unique' => 'Ya existe otro rol registrado con este nombre.',
            'nombre.max' => 'El nombre no debe superar los 100 caracteres.'
        ]);

        $nombreAnterior = $role->nombre;

        $role->update([
            'nombre' => trim($request->nombre),
            'descripcion' => trim($request->descripcion)
        ]);

        Bitacora::create([
            'user_id' => Auth::id(),
            'modulo' => 'Roles',
            'accion' => 'Editar',
            'descripcion' => "Editó el rol: '{$nombreAnterior}' a '{$role->nombre}'",
            'ip' => $request->ip(),
        ]);

        return redirect()
            ->route('roles.index')
            ->with('success', "¡Rol '{$role->nombre}' actualizado correctamente!");
    }

    /**
     * 🗑️ Eliminar rol con protecciones
     */
    public function destroy(Request $request, Rol $role)
    {
        // 1. Protección del rol Administrador
        if ($role->esAdmin()) {
            return redirect()
                ->route('roles.index')
                ->with('error', 'Acción denegada: El rol principal de Administrador es del sistema y no puede ser eliminado.');
        }

        $nombre = $role->nombre;
        $usuariosAfectados = $role->users()->count();

        // Desvincular usuarios
        $role->users()->update([
            'rol_id' => null
        ]);

        // Desvincular permisos
        $role->permisos()->detach();

        // Eliminar rol
        $role->delete();

        $infoUsuarios = $usuariosAfectados > 0 
            ? " ({$usuariosAfectados} usuario(s) quedaron ahora sin rol asignado)" 
            : "";

        Bitacora::create([
            'user_id' => Auth::id(),
            'modulo' => 'Roles',
            'accion' => 'Eliminar',
            'descripcion' => "Eliminó el rol: {$nombre}{$infoUsuarios}",
            'ip' => $request->ip(),
        ]);

        return redirect()
            ->route('roles.index')
            ->with('success', "Rol '{$nombre}' eliminado correctamente{$infoUsuarios}.");
    }

    /**
     * 🔑 Vista de asignación de matriz de permisos
     */
    public function permisos($id)
    {
        $this->asegurarPermisosEstandar();

        $rol = Rol::with('permisos')->findOrFail($id);
        $modulosPermisos = self::getPermisosEstandar();
        $todosPermisos = Permiso::all()->keyBy('nombre');

        $totalPermisosSistema = Permiso::count();
        $totalPermisosRol = $rol->permisos->count();

        return view(
            'roles.permisos',
            compact('rol', 'modulosPermisos', 'todosPermisos', 'totalPermisosSistema', 'totalPermisosRol')
        );
    }

    /**
     * 💾 Guardar permisos del rol
     */
    public function guardarPermisos(Request $request, $id)
    {
        $rol = Rol::findOrFail($id);

        $permisosSeleccionados = $request->permisos ?? [];

        // Sincronizar permisos en base de datos
        $rol->permisos()->sync($permisosSeleccionados);

        $totalAsignados = count($permisosSeleccionados);

        // Obtener nombres de los permisos asignados para auditoría
        $nombresPermisos = Permiso::whereIn('id', $permisosSeleccionados)->pluck('nombre')->toArray();
        $lista = !empty($nombresPermisos) ? implode(', ', $nombresPermisos) : 'Ninguno';

        Bitacora::create([
            'user_id' => Auth::id(),
            'modulo' => 'Roles',
            'accion' => 'Asignar permisos',
            'descripcion' => "Actualizó permisos del rol '{$rol->nombre}': {$totalAsignados} permiso(s) asignado(s) [{$lista}]",
            'ip' => $request->ip(),
        ]);

        return redirect()
            ->route('roles.index')
            ->with('success', "¡Permisos actualizados correctamente para el rol '{$rol->nombre}'! ({$totalAsignados} permisos activos)");
    }
}