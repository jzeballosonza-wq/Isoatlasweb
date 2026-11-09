@extends('dashboard')

@section('content')

<style>
    :root {
        --rol-primary: #4f46e5;
        --rol-primary-dark: #3730a3;
        --rol-success: #10b981;
        --rol-warning: #f59e0b;
        --rol-danger: #ef4444;
        --rol-purple: #7c3aed;
    }

    .roles-container {
        padding: 1.5rem 1rem;
    }

    /* KPI STAT CARDS */
    .kpi-card {
        background: #ffffff;
        border-radius: 16px;
        border: 1px solid #f1f5f9;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
        padding: 1.25rem;
        transition: all 0.25s ease;
        position: relative;
        overflow: hidden;
    }

    .kpi-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 25px -5px rgba(0, 0, 0, 0.08);
    }

    .kpi-icon-box {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.35rem;
        color: #ffffff;
        flex-shrink: 0;
    }

    .kpi-icon-indigo {
        background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
        box-shadow: 0 6px 15px rgba(79, 70, 229, 0.25);
    }

    .kpi-icon-purple {
        background: linear-gradient(135deg, #7c3aed 0%, #a855f7 100%);
        box-shadow: 0 6px 15px rgba(124, 58, 237, 0.25);
    }

    .kpi-icon-emerald {
        background: linear-gradient(135deg, #059669 0%, #10b981 100%);
        box-shadow: 0 6px 15px rgba(16, 185, 129, 0.25);
    }

    .kpi-icon-amber {
        background: linear-gradient(135deg, #d97706 0%, #f59e0b 100%);
        box-shadow: 0 6px 15px rgba(245, 158, 11, 0.25);
    }

    .kpi-label {
        font-size: 0.8rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: #64748b;
        margin-bottom: 2px;
    }

    .kpi-value {
        font-size: 1.45rem;
        font-weight: 800;
        color: #0f172a;
        line-height: 1.2;
    }

    .kpi-subtext {
        font-size: 0.78rem;
        color: #94a3b8;
        font-weight: 500;
    }

    /* TABLE CARD */
    .table-container-card {
        background: #ffffff;
        border-radius: 16px;
        border: 1px solid #f1f5f9;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
        overflow: hidden;
    }

    .table-custom {
        margin-bottom: 0;
    }

    .table-custom thead th {
        background: #f8fafc;
        color: #475569;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        padding: 14px 16px;
        border-bottom: 1px solid #e2e8f0;
        vertical-align: middle;
    }

    .table-custom tbody tr:hover {
        background-color: #f8fafc;
    }

    .table-custom tbody td {
        padding: 14px 16px;
        vertical-align: middle;
        font-size: 0.88rem;
        border-bottom: 1px solid #f1f5f9;
    }

    /* ROLE BADGE IN TABLE */
    .role-icon-box {
        width: 38px;
        height: 38px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
        color: #ffffff;
        flex-shrink: 0;
    }

    /* PROGRESS BAR FOR PERMISSIONS */
    .permission-progress {
        height: 6px;
        border-radius: 10px;
        background-color: #e2e8f0;
        overflow: hidden;
        margin-top: 4px;
    }

    .permission-progress-bar {
        height: 100%;
        border-radius: 10px;
        transition: width 0.4s ease;
    }

    /* ACTION BUTTONS */
    .btn-action-icon {
        width: 34px;
        height: 34px;
        border-radius: 9px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 0.85rem;
        border: 1px solid #e2e8f0;
        background: #ffffff;
        color: #475569;
        transition: all 0.2s ease;
        text-decoration: none;
    }

    .btn-action-icon:hover {
        background: #f8fafc;
        transform: translateY(-2px);
    }

    .btn-action-perm:hover {
        background: #fef3c7;
        color: #b45309;
        border-color: #fde68a;
    }

    .btn-action-edit:hover {
        background: #eef2ff;
        color: #4f46e5;
        border-color: #c7d2fe;
    }

    .btn-action-delete:hover {
        background: #fef2f2;
        color: #ef4444;
        border-color: #fecaca;
    }

    .btn-permisos-highlight {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        color: #ffffff !important;
        font-weight: 600;
        font-size: 0.78rem;
        padding: 5px 12px;
        border-radius: 8px;
        border: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        text-decoration: none !important;
        transition: all 0.2s ease;
        box-shadow: 0 2px 8px rgba(245, 158, 11, 0.3);
    }

    .btn-permisos-highlight:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(245, 158, 11, 0.45);
        color: #ffffff;
    }
</style>

<div class="roles-container">

    <!-- 1. ENCABEZADO Y ACCIONES PRINCIPALES -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <div class="kpi-icon-box kpi-icon-purple" style="width: 36px; height: 36px; font-size: 1rem; border-radius: 10px;">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <h2 class="h4 fw-bold mb-0 text-dark">Roles y Permisos de Acceso</h2>
            </div>
            <p class="text-muted small mb-0">
                Control de acceso basado en roles (RBAC) para administrar privilegios y restricciones en cada módulo del sistema.
            </p>
        </div>

        <div class="d-flex gap-2">
            @if(auth()->user()->tienePermiso('usuarios'))
            <a href="{{ route('users.index') }}" class="btn btn-outline-secondary rounded-3 px-3 py-2 fw-semibold d-inline-flex align-items-center gap-2" style="font-size: 0.88rem;">
                <i class="fas fa-users text-primary"></i>
                <span>Gestión de Usuarios</span>
            </a>
            @endif

            <a href="{{ route('roles.create') }}" class="btn text-white rounded-3 px-3 py-2 fw-semibold d-inline-flex align-items-center gap-2" style="background: linear-gradient(135deg, #7c3aed 0%, #4f46e5 100%); font-size: 0.88rem; box-shadow: 0 4px 14px rgba(124, 58, 237, 0.35);">
                <i class="fas fa-plus"></i>
                <span>Crear Nuevo Rol</span>
            </a>
        </div>
    </div>

    <!-- 2. KPI STATS CARDS -->
    <div class="row g-3 mb-4">
        <!-- Total Roles -->
        <div class="col-xl-3 col-sm-6">
            <div class="kpi-card">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="kpi-label">Perfiles de Rol</div>
                        <div class="kpi-value text-dark">{{ $totalRoles ?? 0 }}</div>
                        <div class="kpi-subtext mt-1">
                            <i class="fas fa-layer-group text-primary me-1"></i> Perfiles configurados
                        </div>
                    </div>
                    <div class="kpi-icon-box kpi-icon-purple">
                        <i class="fas fa-user-shield"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Permisos del Sistema -->
        <div class="col-xl-3 col-sm-6">
            <div class="kpi-card">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="kpi-label">Permisos del Sistema</div>
                        <div class="kpi-value text-primary">{{ $totalPermisos ?? 10 }}</div>
                        <div class="kpi-subtext mt-1">
                            <i class="fas fa-key text-warning me-1"></i> Módulos auditables
                        </div>
                    </div>
                    <div class="kpi-icon-box kpi-icon-amber">
                        <i class="fas fa-key"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Cobertura de Usuarios -->
        <div class="col-xl-3 col-sm-6">
            <div class="kpi-card">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="kpi-label">Usuarios con Rol</div>
                        <div class="kpi-value text-success">{{ $totalUsuariosConRol ?? 0 }}</div>
                        <div class="kpi-subtext mt-1">
                            <i class="fas fa-id-card text-success me-1"></i> Cuentas asignadas
                        </div>
                    </div>
                    <div class="kpi-icon-box kpi-icon-emerald">
                        <i class="fas fa-users-cog"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Rol Principal -->
        <div class="col-xl-3 col-sm-6">
            <div class="kpi-card">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="kpi-label">Rol Principal</div>
                        <div class="kpi-value text-dark" style="font-size: 1.25rem;">Admin</div>
                        <div class="kpi-subtext mt-1">
                            <span class="text-success"><i class="fas fa-shield-alt me-1"></i> Protegido por sistema</span>
                        </div>
                    </div>
                    <div class="kpi-icon-box kpi-icon-indigo">
                        <i class="fas fa-crown"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 3. BUSCADOR RÁPIDO DE ROLES -->
    <div class="bg-white p-3 rounded-4 border shadow-sm mb-4">
        <div class="row align-items-center g-3">
            <div class="col-md-8">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0 text-muted">
                        <i class="fas fa-search"></i>
                    </span>
                    <input type="text" id="roleSearchInput" class="form-control border-start-0 ps-0" placeholder="Buscar rol por nombre o descripción..." onkeyup="filterRolesTable()">
                </div>
            </div>
            <div class="col-md-4 text-md-end">
                <span class="badge bg-light text-secondary border px-3 py-2 fw-semibold" id="roleCounterBadge">
                    {{ count($roles) }} roles registrados
                </span>
            </div>
        </div>
    </div>

    <!-- 4. TABLA DE ROLES -->
    <div class="table-container-card">
        <div class="table-responsive">
            <table class="table table-custom align-middle" id="rolesTable">
                <thead>
                    <tr>
                        <th width="60" class="text-center">#</th>
                        <th width="260">Nombre del Rol</th>
                        <th>Descripción</th>
                        <th width="160" class="text-center">Usuarios Asignados</th>
                        <th width="220">Cobertura de Permisos</th>
                        <th width="200" class="text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody id="rolesTableBody">
                    @forelse($roles as $key => $r)
                    @php
                        $badgeInfo = $r->color_badge;
                        $totalPerms = $totalPermisos > 0 ? $totalPermisos : 10;
                        $assignedPerms = $r->permisos_count ?? $r->permisos->count();
                        $pct = $totalPerms > 0 ? round(($assignedPerms / $totalPerms) * 100) : 0;
                        
                        $progressColor = '#ef4444';
                        if ($pct >= 80) $progressColor = '#10b981';
                        elseif ($pct >= 40) $progressColor = '#6366f1';
                        elseif ($pct > 0) $progressColor = '#f59e0b';
                    @endphp
                    <tr class="role-row" data-name="{{ mb_strtolower($r->nombre) }}" data-desc="{{ mb_strtolower($r->descripcion ?? '') }}">
                        <td class="text-center text-muted fw-bold">{{ $key + 1 }}</td>

                        <!-- Nombre del Rol con Ícono y Badge -->
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                <div class="role-icon-box" style="background: {{ $badgeInfo['bg'] }};">
                                    <i class="{{ $badgeInfo['icon'] }}"></i>
                                </div>
                                <div>
                                    <div class="fw-bold text-dark d-flex align-items-center gap-2">
                                        <span>{{ $r->nombre }}</span>
                                        @if($r->esAdmin())
                                            <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle" style="font-size: 0.68rem; padding: 2px 6px;">
                                                <i class="fas fa-crown"></i> SuperAdmin
                                            </span>
                                        @endif
                                    </div>
                                    <small class="text-muted" style="font-size: 0.74rem;">#ROL-{{ str_pad($r->id, 3, '0', STR_PAD_LEFT) }}</small>
                                </div>
                            </div>
                        </td>

                        <!-- Descripción -->
                        <td>
                            @if($r->descripcion)
                                <span class="text-secondary small">{{ $r->descripcion }}</span>
                            @else
                                <span class="text-muted fst-italic small">Sin descripción proporcionada</span>
                            @endif
                        </td>

                        <!-- Usuarios Asignados -->
                        <td class="text-center">
                            @if($r->users_count > 0)
                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-2 rounded-pill fw-bold" style="font-size: 0.78rem;">
                                    <i class="fas fa-users me-1"></i> {{ $r->users_count }} {{ $r->users_count === 1 ? 'usuario' : 'usuarios' }}
                                </span>
                            @else
                                <span class="badge bg-light text-muted border px-3 py-2 rounded-pill" style="font-size: 0.78rem;">
                                    <i class="fas fa-user-slash me-1"></i> 0 usuarios
                                </span>
                            @endif
                        </td>

                        <!-- Cobertura de Permisos -->
                        <td>
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="fw-bold small text-dark" style="font-size: 0.78rem;">
                                    {{ $assignedPerms }} de {{ $totalPerms }}
                                </span>
                                <span class="fw-semibold small" style="color: {{ $progressColor }}; font-size: 0.75rem;">
                                    {{ $pct }}%
                                </span>
                            </div>
                            <div class="permission-progress">
                                <div class="permission-progress-bar" style="width: {{ $pct }}%; background-color: {{ $progressColor }};"></div>
                            </div>
                        </td>

                        <!-- Acciones -->
                        <td class="text-end">
                            <div class="d-inline-flex align-items-center gap-1">
                                <!-- Botón Matriz de Permisos -->
                                <a href="{{ route('roles.permisos', $r->id) }}" class="btn-permisos-highlight me-1" title="Asignar y configurar permisos">
                                    <i class="fas fa-key"></i>
                                    <span>Permisos</span>
                                </a>

                                <!-- Editar -->
                                <a href="{{ route('roles.edit', $r->id) }}" class="btn-action-icon btn-action-edit" title="Editar Nombre / Descripción">
                                    <i class="fas fa-pen"></i>
                                </a>

                                <!-- Eliminar -->
                                @if($r->esAdmin())
                                    <button type="button" class="btn-action-icon text-muted opacity-50" style="cursor: not-allowed;" title="El rol Administrador está protegido por el sistema" disabled>
                                        <i class="fas fa-trash"></i>
                                    </button>
                                @else
                                    <button type="button" class="btn-action-icon btn-action-delete" title="Eliminar Rol"
                                        onclick="confirmDeleteRole('{{ $r->id }}', '{{ addslashes($r->nombre) }}', {{ $r->users_count }})">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                    <form id="delete-role-form-{{ $r->id }}" action="{{ route('roles.destroy', $r->id) }}" method="POST" style="display: none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            <div class="py-4">
                                <i class="fas fa-shield-alt fa-3x text-muted mb-3 d-block opacity-50"></i>
                                <h6 class="fw-bold text-dark">No hay roles registrados</h6>
                                <p class="small text-muted mb-3">Crea roles para definir qué permisos tendrá el personal.</p>
                                <a href="{{ route('roles.create') }}" class="btn btn-primary btn-sm rounded-pill px-3">
                                    <i class="fas fa-plus me-1"></i> Crear Rol
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse

                    <tr id="noRoleResultsRow" style="display: none;">
                        <td colspan="6" class="text-center py-5 text-muted">
                            <i class="fas fa-search fa-2x text-muted mb-2 d-block opacity-50"></i>
                            <p class="mb-0 fw-semibold text-secondary">No se encontraron roles con el término de búsqueda ingresado.</p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

</div>

<!-- SCRIPTS DE INTERACTIVIDAD -->
<script>
function filterRolesTable() {
    const query = document.getElementById('roleSearchInput').value.toLowerCase().trim();
    const rows = document.querySelectorAll('.role-row');
    let count = 0;

    rows.forEach(row => {
        const name = row.getAttribute('data-name') || '';
        const desc = row.getAttribute('data-desc') || '';

        if (query === '' || name.includes(query) || desc.includes(query)) {
            row.style.display = '';
            count++;
        } else {
            row.style.display = 'none';
        }
    });

    const noResultsRow = document.getElementById('noRoleResultsRow');
    if (noResultsRow) {
        noResultsRow.style.display = (count === 0 && rows.length > 0) ? '' : 'none';
    }

    const counterBadge = document.getElementById('roleCounterBadge');
    if (counterBadge) {
        counterBadge.textContent = `${count} de ${rows.length} roles`;
    }
}

function confirmDeleteRole(roleId, roleName, usersCount) {
    let warningUsers = '';
    if (usersCount > 0) {
        warningUsers = `<div class="alert alert-warning p-2 small mt-2 mb-0"><i class="fas fa-exclamation-triangle me-1"></i> <strong>Atención:</strong> Este rol tiene <strong>${usersCount} usuario(s)</strong> asignados. Si lo eliminas, los usuarios quedarán sin rol hasta que se les asigne otro.</div>`;
    }

    Swal.fire({
        title: '¿Eliminar rol?',
        html: `¿Estás seguro de que deseas eliminar el rol <strong>${roleName}</strong>?${warningUsers}`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#64748b',
        confirmButtonText: '<i class="fas fa-trash me-1"></i> Sí, eliminar rol',
        cancelButtonText: 'Cancelar',
        customClass: {
            popup: 'rounded-4'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('delete-role-form-' + roleId).submit();
        }
    });
}
</script>

@endsection