@extends('dashboard')

@section('content')

<style>
    /* VARIABLES & TOKENS DE DISEÑO */
    :root {
        --usr-primary: #4f46e5;
        --usr-primary-dark: #3730a3;
        --usr-primary-light: #eef2ff;
        --usr-success: #10b981;
        --usr-info: #0284c7;
        --usr-warning: #f59e0b;
        --usr-danger: #ef4444;
        --usr-purple: #8b5cf6;
        --usr-card-bg: #ffffff;
        --usr-border: #f1f5f9;
    }

    .users-container {
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

    /* FILTER PANEL */
    .filter-card {
        background: #ffffff;
        border-radius: 16px;
        border: 1px solid #f1f5f9;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
        margin-bottom: 1.5rem;
        overflow: hidden;
    }

    .filter-header {
        padding: 1rem 1.25rem;
        background: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    /* MAIN DATA TABLE CARD */
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

    .table-custom tbody tr {
        transition: background-color 0.15s ease;
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

    /* AVATAR BUBBLE */
    .user-avatar-bubble {
        width: 42px;
        height: 42px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #ffffff;
        font-weight: 700;
        font-size: 0.95rem;
        letter-spacing: 0.02em;
        flex-shrink: 0;
        box-shadow: 0 3px 8px rgba(0,0,0,0.12);
    }

    /* ROLE BADGES */
    .badge-role {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 12px;
        border-radius: 30px;
        font-size: 0.75rem;
        font-weight: 700;
        letter-spacing: 0.02em;
    }

    .badge-role-admin {
        background: rgba(79, 70, 229, 0.12);
        color: #4f46e5;
        border: 1px solid rgba(79, 70, 229, 0.25);
    }

    .badge-role-vendedor {
        background: rgba(16, 185, 129, 0.12);
        color: #059669;
        border: 1px solid rgba(16, 185, 129, 0.25);
    }

    .badge-role-supervisor {
        background: rgba(245, 158, 11, 0.12);
        color: #d97706;
        border: 1px solid rgba(245, 158, 11, 0.25);
    }

    .badge-role-almacen {
        background: rgba(2, 132, 199, 0.12);
        color: #0284c7;
        border: 1px solid rgba(2, 132, 199, 0.25);
    }

    .badge-role-none {
        background: #f1f5f9;
        color: #64748b;
        border: 1px solid #e2e8f0;
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

    .btn-action-view:hover {
        background: #e0f2fe;
        color: #0284c7;
        border-color: #bae6fd;
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

    /* COPY BUTTON */
    .btn-copy-email {
        cursor: pointer;
        padding: 2px 6px;
        border-radius: 6px;
        transition: background 0.2s;
        border: none;
        background: transparent;
        color: #94a3b8;
    }

    .btn-copy-email:hover {
        background: #f1f5f9;
        color: #4f46e5;
    }
</style>

<div class="users-container">

    <!-- 1. ENCABEZADO Y ACCIONES PRINCIPALES -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <div class="kpi-icon-box kpi-icon-indigo" style="width: 36px; height: 36px; font-size: 1rem; border-radius: 10px;">
                    <i class="fas fa-users-cog"></i>
                </div>
                <h2 class="h4 fw-bold mb-0 text-dark">Gestión de Usuarios</h2>
            </div>
            <p class="text-muted small mb-0">
                Administración de cuentas de acceso, perfiles de seguridad y asignación de roles del personal.
            </p>
        </div>

        <div class="d-flex gap-2">
            @if(auth()->user()->tienePermiso('roles'))
            <a href="{{ route('roles.index') }}" class="btn btn-outline-secondary rounded-3 px-3 py-2 fw-semibold d-inline-flex align-items-center gap-2" style="font-size: 0.88rem;">
                <i class="fas fa-shield-alt text-primary"></i>
                <span>Roles & Permisos</span>
            </a>
            @endif

            <a href="{{ route('users.create') }}" class="btn text-white rounded-3 px-3 py-2 fw-semibold d-inline-flex align-items-center gap-2" style="background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%); font-size: 0.88rem; box-shadow: 0 4px 14px rgba(79, 70, 229, 0.35);">
                <i class="fas fa-user-plus"></i>
                <span>Nuevo Usuario</span>
            </a>
        </div>
    </div>

    <!-- 2. KPI STATS CARDS -->
    <div class="row g-3 mb-4">
        <!-- Total Usuarios -->
        <div class="col-xl-3 col-sm-6">
            <div class="kpi-card">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="kpi-label">Total Usuarios</div>
                        <div class="kpi-value text-dark">{{ $totalUsuarios ?? 0 }}</div>
                        <div class="kpi-subtext mt-1">
                            <i class="fas fa-user-check text-success me-1"></i> {{ $nuevosMes ?? 0 }} registrados este mes
                        </div>
                    </div>
                    <div class="kpi-icon-box kpi-icon-indigo">
                        <i class="fas fa-users"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Administradores -->
        <div class="col-xl-3 col-sm-6">
            <div class="kpi-card">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="kpi-label">Administradores</div>
                        <div class="kpi-value text-primary">{{ $totalAdmins ?? 0 }}</div>
                        <div class="kpi-subtext mt-1">
                            <i class="fas fa-shield-alt text-primary me-1"></i> Control y acceso total
                        </div>
                    </div>
                    <div class="kpi-icon-box kpi-icon-purple">
                        <i class="fas fa-user-shield"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Con Rol Asignado -->
        <div class="col-xl-3 col-sm-6">
            <div class="kpi-card">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="kpi-label">Con Rol Asignado</div>
                        <div class="kpi-value text-success">{{ $totalConRol ?? 0 }}</div>
                        <div class="kpi-subtext mt-1">
                            <i class="fas fa-check-circle text-success me-1"></i>
                            {{ $totalUsuarios > 0 ? round(($totalConRol / $totalUsuarios) * 100) : 0 }}% cobertura de perfiles
                        </div>
                    </div>
                    <div class="kpi-icon-box kpi-icon-emerald">
                        <i class="fas fa-id-badge"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sin Rol Asignado -->
        <div class="col-xl-3 col-sm-6">
            <div class="kpi-card">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="kpi-label">Sin Rol Asignado</div>
                        <div class="kpi-value {{ ($totalSinRol ?? 0) > 0 ? 'text-warning' : 'text-muted' }}">
                            {{ $totalSinRol ?? 0 }}
                        </div>
                        <div class="kpi-subtext mt-1">
                            @if(($totalSinRol ?? 0) > 0)
                                <span class="text-warning fw-semibold"><i class="fas fa-exclamation-triangle me-1"></i> Requieren asignación</span>
                            @else
                                <span class="text-success"><i class="fas fa-shield-virus me-1"></i> Todos tienen rol</span>
                            @endif
                        </div>
                    </div>
                    <div class="kpi-icon-box kpi-icon-amber">
                        <i class="fas fa-user-clock"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 3. PANEL DE BÚSQUEDA Y FILTROS -->
    <div class="filter-card mb-4">
        <div class="filter-header">
            <div class="d-flex align-items-center gap-2">
                <i class="fas fa-filter text-primary"></i>
                <span class="fw-bold text-dark" style="font-size: 0.9rem;">Filtros de Búsqueda Rápida</span>
            </div>
            <span class="badge bg-light text-secondary border px-2 py-1" id="userCounterText" style="font-size: 0.75rem;">
                Mostrando {{ count($users) }} usuarios
            </span>
        </div>
        <div class="p-3">
            <div class="row g-3">
                <div class="col-md-7 col-lg-8">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0 text-muted">
                            <i class="fas fa-search"></i>
                        </span>
                        <input type="text" id="filterInput" class="form-control border-start-0 ps-0" placeholder="Buscar por nombre de usuario o correo electrónico..." onkeyup="filterUserTable()">
                    </div>
                </div>
                <div class="col-md-5 col-lg-4">
                    <div class="d-flex gap-2">
                        <select id="roleFilterSelect" class="form-select" onchange="filterUserTable()">
                            <option value="">Todos los Roles</option>
                            @foreach($roles as $r)
                                <option value="{{ mb_strtolower($r->nombre) }}">{{ $r->nombre }} ({{ $r->users_count }})</option>
                            @endforeach
                            <option value="__sin_rol__">Sin Rol Asignado</option>
                        </select>

                        <button type="button" class="btn btn-outline-secondary px-3" onclick="resetUserFilters()" title="Limpiar filtros">
                            <i class="fas fa-undo"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 4. TABLA DE USUARIOS -->
    <div class="table-container-card">
        <div class="table-responsive">
            <table class="table table-custom align-middle" id="usersTable">
                <thead>
                    <tr>
                        <th width="60" class="text-center">#</th>
                        <th>Usuario</th>
                        <th>Correo Electrónico</th>
                        <th>Rol Asignado</th>
                        <th>Fecha Registro</th>
                        <th width="140" class="text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody id="usersTableBody">
                    @forelse($users as $index => $u)
                    @php
                        $esAdmin = $u->esAdmin();
                        $rolNombre = $u->rol->nombre ?? 'Sin Rol';
                        $rolSlug = mb_strtolower($rolNombre);
                        $badgeClass = 'badge-role-none';
                        $roleIcon = 'fas fa-user-tag';
                        
                        if (str_contains($rolSlug, 'admin')) {
                            $badgeClass = 'badge-role-admin';
                            $roleIcon = 'fas fa-user-shield';
                        } elseif (str_contains($rolSlug, 'vendedor') || str_contains($rolSlug, 'ventas')) {
                            $badgeClass = 'badge-role-vendedor';
                            $roleIcon = 'fas fa-cash-register';
                        } elseif (str_contains($rolSlug, 'supervisor') || str_contains($rolSlug, 'gerente')) {
                            $badgeClass = 'badge-role-supervisor';
                            $roleIcon = 'fas fa-user-tie';
                        } elseif (str_contains($rolSlug, 'almacen') || str_contains($rolSlug, 'inventario')) {
                            $badgeClass = 'badge-role-almacen';
                            $roleIcon = 'fas fa-boxes';
                        }
                    @endphp
                    <tr class="user-row" data-name="{{ mb_strtolower($u->name) }}" data-email="{{ mb_strtolower($u->email) }}" data-role="{{ $u->rol_id ? $rolSlug : '__sin_rol__' }}">
                        <td class="text-center text-muted fw-bold">{{ $index + 1 }}</td>
                        
                        <!-- Columna Usuario -->
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                <div class="user-avatar-bubble" style="background: {{ $u->avatar_gradient }};">
                                    {{ $u->iniciales }}
                                </div>
                                <div>
                                    <div class="fw-bold text-dark d-flex align-items-center gap-2">
                                        <span>{{ $u->name }}</span>
                                        @if($u->id === auth()->id())
                                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle" style="font-size: 0.68rem; padding: 2px 6px;">Tú</span>
                                        @endif
                                    </div>
                                    <small class="text-muted d-block" style="font-size: 0.76rem;">ID: #USR-{{ str_pad($u->id, 4, '0', STR_PAD_LEFT) }}</small>
                                </div>
                            </div>
                        </td>

                        <!-- Columna Email -->
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <span class="text-secondary">{{ $u->email }}</span>
                                <button type="button" class="btn-copy-email" onclick="copyToClipboard('{{ $u->email }}', this)" title="Copiar correo">
                                    <i class="far fa-copy"></i>
                                </button>
                            </div>
                        </td>

                        <!-- Columna Rol -->
                        <td>
                            @if($u->rol)
                                <span class="badge-role {{ $badgeClass }}">
                                    <i class="{{ $roleIcon }}"></i>
                                    {{ $u->rol->nombre }}
                                </span>
                            @else
                                <span class="badge-role badge-role-none">
                                    <i class="fas fa-question-circle"></i>
                                    Sin Rol Asignado
                                </span>
                            @endif
                        </td>

                        <!-- Columna Fecha -->
                        <td>
                            <div class="text-dark small fw-medium">
                                <i class="far fa-calendar-alt text-muted me-1"></i>
                                {{ $u->created_at ? $u->created_at->format('d/m/Y') : 'N/A' }}
                            </div>
                            <small class="text-muted" style="font-size: 0.72rem;">
                                {{ $u->created_at ? $u->created_at->diffForHumans() : '' }}
                            </small>
                        </td>

                        <!-- Columna Acciones -->
                        <td class="text-end">
                            <div class="d-inline-flex gap-1">
                                <!-- Ver Detalle Modal -->
                                <button type="button" class="btn-action-icon btn-action-view" title="Ver Detalles"
                                    onclick="openUserDetailModal({{ json_encode([
                                        'id' => $u->id,
                                        'name' => $u->name,
                                        'email' => $u->email,
                                        'rol' => $u->rol->nombre ?? 'Sin Rol',
                                        'rol_id' => $u->rol_id,
                                        'iniciales' => $u->iniciales,
                                        'gradient' => $u->avatar_gradient,
                                        'badgeClass' => $badgeClass,
                                        'roleIcon' => $roleIcon,
                                        'created_at' => $u->created_at ? $u->created_at->format('d/m/Y H:i') : 'N/A',
                                        'updated_at' => $u->updated_at ? $u->updated_at->format('d/m/Y H:i') : 'N/A',
                                        'permisos' => $u->rol ? $u->rol->permisos->pluck('nombre')->toArray() : []
                                    ]) }})">
                                    <i class="fas fa-eye"></i>
                                </button>

                                <!-- Editar -->
                                <a href="{{ route('users.edit', $u) }}" class="btn-action-icon btn-action-edit" title="Editar Usuario">
                                    <i class="fas fa-pen"></i>
                                </a>

                                <!-- Eliminar -->
                                @if($u->id === auth()->id())
                                    <button class="btn-action-icon text-muted opacity-50" style="cursor: not-allowed;" title="No puedes eliminarte a ti mismo" disabled>
                                        <i class="fas fa-trash"></i>
                                    </button>
                                @else
                                    <button type="button" class="btn-action-icon btn-action-delete" title="Eliminar Usuario"
                                        onclick="confirmDeleteUser('{{ $u->id }}', '{{ addslashes($u->name) }}', '{{ addslashes($rolNombre) }}')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                    <form id="delete-form-{{ $u->id }}" action="{{ route('users.destroy', $u) }}" method="POST" style="display: none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr id="emptyRowPlaceholder">
                        <td colspan="6" class="text-center py-5 text-muted">
                            <div class="py-4">
                                <i class="fas fa-users fa-3x text-muted mb-3 d-block opacity-50"></i>
                                <h6 class="fw-bold text-dark">No hay usuarios registrados</h6>
                                <p class="small text-muted mb-3">Comienza creando el primer usuario del sistema.</p>
                                <a href="{{ route('users.create') }}" class="btn btn-primary btn-sm rounded-pill px-3">
                                    <i class="fas fa-plus me-1"></i> Crear Usuario
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse

                    <tr id="noResultsRow" style="display: none;">
                        <td colspan="6" class="text-center py-5 text-muted">
                            <i class="fas fa-search fa-2x text-muted mb-2 d-block opacity-50"></i>
                            <p class="mb-0 fw-semibold text-secondary">No se encontraron usuarios que coincidan con la búsqueda o filtro aplicado.</p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

</div>

<!-- MODAL DETALLE DE USUARIO -->
<div class="modal fade" id="userDetailModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow-lg overflow-hidden">
            <div class="modal-header text-white p-4" id="modalHeaderGradient" style="background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);">
                <div class="d-flex align-items-center gap-3">
                    <div id="modalAvatarBubble" class="user-avatar-bubble" style="width: 52px; height: 52px; font-size: 1.25rem; border: 2px solid rgba(255,255,255,0.4);">
                        US
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold mb-0" id="modalUserName">Nombre del Usuario</h5>
                        <small class="text-white-50" id="modalUserEmail">correo@ejemplo.com</small>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <div class="modal-body p-4">
                <div class="row g-3 mb-4">
                    <div class="col-6">
                        <div class="p-3 bg-light rounded-3 border">
                            <small class="text-muted d-block" style="font-size: 0.72rem; text-transform: uppercase; font-weight: 700;">Rol Asignado</small>
                            <div class="mt-1" id="modalRoleBadgeContainer">
                                <span class="badge-role badge-role-admin" id="modalRoleBadge">
                                    <i class="fas fa-shield-alt"></i> Administrador
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-3 bg-light rounded-3 border">
                            <small class="text-muted d-block" style="font-size: 0.72rem; text-transform: uppercase; font-weight: 700;">Fecha de Alta</small>
                            <span class="fw-bold text-dark mt-1 d-block" id="modalCreatedAt" style="font-size: 0.85rem;">-</span>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <h6 class="fw-bold text-dark d-flex align-items-center gap-2 mb-2" style="font-size: 0.85rem;">
                        <i class="fas fa-key text-primary"></i>
                        Permisos Concedidos por su Rol
                    </h6>
                    <div id="modalPermissionsList" class="d-flex flex-wrap gap-1 p-2 bg-light rounded-3 border" style="min-height: 50px;">
                        <!-- Permisos cargados dinámicamente -->
                    </div>
                </div>
            </div>

            <div class="modal-footer bg-light p-3 border-top d-flex justify-content-between">
                <small class="text-muted" id="modalLastUpdated" style="font-size: 0.75rem;">-</small>
                <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<!-- SCRIPTS DE INTERACTIVIDAD -->
<script>
function filterUserTable() {
    const textQuery = document.getElementById('filterInput').value.toLowerCase().trim();
    const roleFilter = document.getElementById('roleFilterSelect').value.toLowerCase().trim();
    const rows = document.querySelectorAll('.user-row');
    let visibleCount = 0;

    rows.forEach(row => {
        const name = row.getAttribute('data-name') || '';
        const email = row.getAttribute('data-email') || '';
        const role = row.getAttribute('data-role') || '';

        const matchesText = textQuery === '' || name.includes(textQuery) || email.includes(textQuery);
        const matchesRole = roleFilter === '' || role === roleFilter;

        if (matchesText && matchesRole) {
            row.style.display = '';
            visibleCount++;
        } else {
            row.style.display = 'none';
        }
    });

    const noResultsRow = document.getElementById('noResultsRow');
    if (noResultsRow) {
        noResultsRow.style.display = (visibleCount === 0 && rows.length > 0) ? '' : 'none';
    }

    const counterText = document.getElementById('userCounterText');
    if (counterText) {
        counterText.textContent = `Mostrando ${visibleCount} de ${rows.length} usuarios`;
    }
}

function resetUserFilters() {
    document.getElementById('filterInput').value = '';
    document.getElementById('roleFilterSelect').value = '';
    filterUserTable();
}

function copyToClipboard(text, btnElement) {
    navigator.clipboard.writeText(text).then(() => {
        const icon = btnElement.querySelector('i');
        const oldClass = icon.className;
        icon.className = 'fas fa-check text-success';
        setTimeout(() => {
            icon.className = oldClass;
        }, 1500);
    });
}

function confirmDeleteUser(userId, userName, roleName) {
    Swal.fire({
        title: '¿Eliminar usuario?',
        html: `Estás a punto de eliminar a <strong>${userName}</strong> (${roleName}).<br><span class="text-danger small">Esta acción no se puede deshacer.</span>`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#64748b',
        confirmButtonText: '<i class="fas fa-trash me-1"></i> Sí, eliminar',
        cancelButtonText: 'Cancelar',
        customClass: {
            popup: 'rounded-4'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('delete-form-' + userId).submit();
        }
    });
}

function openUserDetailModal(user) {
    document.getElementById('modalUserName').textContent = user.name;
    document.getElementById('modalUserEmail').textContent = user.email;
    document.getElementById('modalCreatedAt').textContent = user.created_at;
    document.getElementById('modalLastUpdated').textContent = `Última mod: ${user.updated_at}`;

    const avatar = document.getElementById('modalAvatarBubble');
    avatar.textContent = user.iniciales;
    avatar.style.background = user.gradient;

    const roleBadge = document.getElementById('modalRoleBadge');
    roleBadge.className = `badge-role ${user.badgeClass}`;
    roleBadge.innerHTML = `<i class="${user.roleIcon}"></i> ${user.rol}`;

    const permissionsContainer = document.getElementById('modalPermissionsList');
    permissionsContainer.innerHTML = '';

    if (user.permisos && user.permisos.length > 0) {
        user.permisos.forEach(p => {
            const badge = document.createElement('span');
            badge.className = 'badge bg-white text-dark border px-2 py-1 m-1';
            badge.style.fontSize = '0.75rem';
            badge.innerHTML = `<i class="fas fa-check-circle text-success me-1"></i> ${p.charAt(0).toUpperCase() + p.slice(1)}`;
            permissionsContainer.appendChild(badge);
        });
    } else {
        permissionsContainer.innerHTML = '<span class="text-muted small p-2"><i class="fas fa-ban me-1"></i> Este usuario no cuenta con permisos activos otorgados por su rol.</span>';
    }

    const modal = new bootstrap.Modal(document.getElementById('userDetailModal'));
    modal.show();
}
</script>

@endsection