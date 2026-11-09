@extends('dashboard')

@section('content')

<style>
    :root {
        --perm-primary: #4f46e5;
        --perm-primary-dark: #3730a3;
        --perm-success: #10b981;
        --perm-warning: #f59e0b;
        --perm-purple: #7c3aed;
    }

    .permisos-container {
        padding: 1.5rem 1rem;
    }

    /* HERO HEADER CARD */
    .role-hero-card {
        background: #ffffff;
        border-radius: 20px;
        border: 1px solid #f1f5f9;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
        padding: 1.75rem;
        margin-bottom: 1.5rem;
        position: relative;
        overflow: hidden;
    }

    .role-avatar-badge {
        width: 54px;
        height: 54px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #ffffff;
        font-size: 1.4rem;
        box-shadow: 0 6px 15px rgba(0, 0, 0, 0.15);
        flex-shrink: 0;
    }

    /* PROGRESS BAR */
    .perm-progress-bar-container {
        height: 8px;
        border-radius: 10px;
        background-color: #e2e8f0;
        overflow: hidden;
        margin-top: 8px;
    }

    .perm-progress-fill {
        height: 100%;
        border-radius: 10px;
        transition: width 0.3s ease, background-color 0.3s ease;
    }

    /* MODULE ACCORDION / GROUP CARD */
    .module-card {
        background: #ffffff;
        border-radius: 16px;
        border: 1px solid #f1f5f9;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.02);
        margin-bottom: 1.5rem;
        overflow: hidden;
    }

    .module-card-header {
        padding: 1.1rem 1.4rem;
        background: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .module-card-body {
        padding: 1.25rem 1.4rem;
    }

    /* PERMISSION INTERACTIVE ITEM CARD */
    .perm-interactive-card {
        border: 1.5px solid #e2e8f0;
        border-radius: 14px;
        padding: 14px 16px;
        background: #ffffff;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        cursor: pointer;
        display: flex;
        gap: 12px;
        align-items: flex-start;
        height: 100%;
        user-select: none;
    }

    .perm-interactive-card:hover {
        border-color: #cbd5e1;
        background: #f8fafc;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.04);
    }

    .perm-interactive-card.is-active {
        border-color: #6366f1;
        background: #f8faff;
        box-shadow: 0 6px 16px rgba(99, 102, 241, 0.12);
    }

    .perm-icon-wrapper {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        background: #f1f5f9;
        color: #475569;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
        flex-shrink: 0;
        transition: all 0.2s ease;
    }

    .perm-interactive-card.is-active .perm-icon-wrapper {
        background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
        color: #ffffff;
        box-shadow: 0 3px 8px rgba(79, 70, 229, 0.3);
    }

    /* FORM CHECK SWITCH */
    .form-switch .form-check-input {
        width: 2.2em;
        height: 1.2em;
        cursor: pointer;
    }

    .form-switch .form-check-input:checked {
        background-color: #4f46e5;
        border-color: #4f46e5;
    }

    /* STICKY BOTTOM SAVE BAR */
    .sticky-actions-bar {
        position: sticky;
        bottom: 1rem;
        background: rgba(15, 23, 42, 0.92);
        backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 16px;
        padding: 12px 20px;
        color: #ffffff;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.25);
        z-index: 1000;
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-top: 2rem;
    }
</style>

<div class="permisos-container">

    <!-- BREADCRUMB & VOLVER -->
    <div class="mb-3">
        <a href="{{ route('roles.index') }}" class="text-decoration-none text-muted small fw-semibold d-inline-flex align-items-center gap-1">
            <i class="fas fa-arrow-left"></i> Volver al listado de roles
        </a>
    </div>

    <!-- 1. HERO ROLE CARD WITH LIVE COUNTER -->
    <div class="role-hero-card">
        <div class="row align-items-center g-3">
            <div class="col-lg-7">
                <div class="d-flex align-items-center gap-3">
                    <div class="role-avatar-badge" style="background: {{ $rol->color_badge['bg'] }};">
                        <i class="{{ $rol->color_badge['icon'] }}"></i>
                    </div>
                    <div>
                        <div class="d-flex align-items-center gap-2">
                            <h3 class="h4 fw-bold mb-0 text-dark">{{ $rol->nombre }}</h3>
                            @if($rol->esAdmin())
                                <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle small px-2 py-1">
                                    <i class="fas fa-crown"></i> SuperAdmin
                                </span>
                            @endif
                        </div>
                        <p class="text-muted small mb-0 mt-1">
                            {{ $rol->descripcion ?: 'Perfil de usuario sin descripción detallada.' }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="p-3 bg-light rounded-4 border">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="small fw-bold text-dark">
                            <i class="fas fa-key text-warning me-1"></i> Cobertura de Permisos
                        </span>
                        <span class="badge bg-primary text-white" id="livePermissionBadge" style="font-size: 0.8rem;">
                            {{ $totalPermisosRol }} de {{ $totalPermisosSistema }} activos
                        </span>
                    </div>
                    @php
                        $initPct = $totalPermisosSistema > 0 ? round(($totalPermisosRol / $totalPermisosSistema) * 100) : 0;
                    @endphp
                    <div class="perm-progress-bar-container">
                        <div class="perm-progress-fill" id="liveProgressBar" style="width: {{ $initPct }}%; background-color: {{ $initPct >= 80 ? '#10b981' : ($initPct >= 40 ? '#6366f1' : '#f59e0b') }};"></div>
                    </div>
                    <div class="d-flex justify-content-between small text-muted mt-1" style="font-size: 0.72rem;">
                        <span>0% (Sin acceso)</span>
                        <span id="livePctText" class="fw-bold text-dark">{{ $initPct }}% otorgado</span>
                        <span>100% (Acceso Total)</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 2. BARRA DE ACCIONES MASIVAS -->
    <div class="bg-white p-3 rounded-4 border shadow-sm mb-4 d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div class="d-flex gap-2">
            <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-3 fw-semibold" onclick="selectAllPerms(true)">
                <i class="fas fa-check-double me-1"></i> Seleccionar Todos
            </button>
            <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3 fw-semibold" onclick="selectAllPerms(false)">
                <i class="fas fa-ban me-1"></i> Desmarcar Todos
            </button>
            <button type="button" class="btn btn-sm btn-outline-dark rounded-pill px-3 fw-semibold" onclick="invertAllPerms()">
                <i class="fas fa-random me-1"></i> Invertir Selección
            </button>
        </div>

        <div class="d-flex align-items-center gap-2">
            <span class="small text-muted d-none d-sm-inline">Filtro rápido:</span>
            <input type="text" id="permSearchInput" class="form-control form-control-sm rounded-pill px-3" placeholder="Buscar permiso..." style="width: 180px;" onkeyup="filterPermCards()">
        </div>
    </div>

    <!-- 3. FORMULARIO DE PERMISOS AGRUPADO POR MÓDULOS -->
    <form action="{{ route('roles.guardarPermisos', $rol->id) }}" method="POST" id="permisosForm">
        @csrf

        @foreach($modulosPermisos as $modKey => $modulo)
        <div class="module-card">
            <div class="module-card-header">
                <div class="d-flex align-items-center gap-2">
                    <div class="d-flex align-items-center justify-content-center" 
                        style="width: 32px; height: 32px; border-radius: 8px; background: rgba(99, 102, 241, 0.1); color: {{ $modulo['color'] }};">
                        <i class="{{ $modulo['icono'] }}"></i>
                    </div>
                    <div>
                        <h4 class="h6 fw-bold mb-0 text-dark">{{ $modulo['titulo'] }}</h4>
                        <small class="text-muted" style="font-size: 0.74rem;">{{ count($modulo['permisos']) }} módulos disponibles</small>
                    </div>
                </div>

                <div class="d-flex gap-1">
                    <button type="button" class="btn btn-sm btn-light border rounded-pill px-3 py-1 small fw-semibold text-secondary" 
                        onclick="toggleModuleGroup('group-{{ $modKey }}')">
                        <i class="fas fa-tasks me-1"></i> Marcar / Desmarcar Grupo
                    </button>
                </div>
            </div>

            <div class="module-card-body">
                <div class="row g-3">
                    @foreach($modulo['permisos'] as $p)
                    @php
                        $permisoModel = $todosPermisos->get($p['clave']);
                        $estaMarcado = $permisoModel && $rol->permisos->contains($permisoModel->id);
                    @endphp
                    @if($permisoModel)
                    <div class="col-md-6 perm-col-wrapper" data-perm-name="{{ mb_strtolower($p['nombre']) }}" data-perm-desc="{{ mb_strtolower($p['descripcion']) }}">
                        <div class="perm-interactive-card {{ $estaMarcado ? 'is-active' : '' }}" 
                            id="perm-card-{{ $permisoModel->id }}"
                            onclick="handleCardClick('check-perm-{{ $permisoModel->id }}', event)">
                            
                            <div class="perm-icon-wrapper">
                                <i class="{{ $p['icono'] }}"></i>
                            </div>

                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <span class="fw-bold text-dark small">{{ $p['nombre'] }}</span>
                                    <div class="form-check form-switch m-0">
                                        <input class="form-check-input group-{{ $modKey }} perm-checkbox" 
                                            type="checkbox" 
                                            id="check-perm-{{ $permisoModel->id }}"
                                            name="permisos[]" 
                                            value="{{ $permisoModel->id }}"
                                            {{ $estaMarcado ? 'checked' : '' }}
                                            onchange="handleCheckboxChange(this, 'perm-card-{{ $permisoModel->id }}')">
                                    </div>
                                </div>
                                <p class="text-muted mb-0" style="font-size: 0.75rem; line-height: 1.35;">
                                    {{ $p['descripcion'] }}
                                </p>
                            </div>

                        </div>
                    </div>
                    @endif
                    @endforeach
                </div>
            </div>
        </div>
        @endforeach

        <!-- 4. BARRA FLOTANTE DE GUARDADO -->
        <div class="sticky-actions-bar">
            <div class="d-flex align-items-center gap-3">
                <div class="d-flex align-items-center justify-content-center bg-primary rounded-circle" style="width: 38px; height: 38px;">
                    <i class="fas fa-shield-alt text-white"></i>
                </div>
                <div>
                    <div class="fw-bold small" id="stickyCountText">
                        {{ $totalPermisosRol }} de {{ $totalPermisosSistema }} permisos seleccionados
                    </div>
                    <small class="text-white-50" style="font-size: 0.72rem;">Los cambios se aplicarán de inmediato a todos los usuarios del rol.</small>
                </div>
            </div>

            <div class="d-flex gap-2">
                <a href="{{ route('roles.index') }}" class="btn btn-outline-light btn-sm rounded-pill px-3">
                    Cancelar
                </a>
                <button type="submit" class="btn btn-success btn-sm rounded-pill px-4 fw-bold shadow d-inline-flex align-items-center gap-2">
                    <i class="fas fa-save"></i>
                    <span>Guardar Permisos</span>
                </button>
            </div>
        </div>

    </form>

</div>

<!-- SCRIPTS DE INTERACTIVIDAD -->
<script>
const TOTAL_PERMS = {{ $totalPermisosSistema > 0 ? $totalPermisosSistema : 10 }};

function handleCardClick(checkboxId, event) {
    // Si se hizo click directo en el input o switch, dejamos que el evento nativo corra
    if (event.target.tagName === 'INPUT' || event.target.tagName === 'LABEL') return;
    
    const cb = document.getElementById(checkboxId);
    if (cb) {
        cb.checked = !cb.checked;
        cb.dispatchEvent(new Event('change'));
    }
}

function handleCheckboxChange(cb, cardId) {
    const card = document.getElementById(cardId);
    if (cb.checked) {
        card.classList.add('is-active');
    } else {
        card.classList.remove('is-active');
    }
    updateStatsCounter();
}

function updateStatsCounter() {
    const checkedCount = document.querySelectorAll('.perm-checkbox:checked').length;
    const pct = TOTAL_PERMS > 0 ? Math.round((checkedCount / TOTAL_PERMS) * 100) : 0;

    const badge = document.getElementById('livePermissionBadge');
    if (badge) badge.textContent = `${checkedCount} de ${TOTAL_PERMS} activos`;

    const stickyText = document.getElementById('stickyCountText');
    if (stickyText) stickyText.textContent = `${checkedCount} de ${TOTAL_PERMS} permisos seleccionados`;

    const pctText = document.getElementById('livePctText');
    if (pctText) pctText.textContent = `${pct}% otorgado`;

    const bar = document.getElementById('liveProgressBar');
    if (bar) {
        bar.style.width = `${pct}%`;
        let color = '#ef4444';
        if (pct >= 80) color = '#10b981';
        else if (pct >= 40) color = '#6366f1';
        else if (pct > 0) color = '#f59e0b';
        bar.style.backgroundColor = color;
    }
}

function toggleModuleGroup(groupClass) {
    const cbs = document.querySelectorAll('.' + groupClass);
    const anyUnchecked = Array.from(cbs).some(cb => !cb.checked);
    cbs.forEach(cb => {
        cb.checked = anyUnchecked;
        const card = document.getElementById('perm-card-' + cb.value);
        if (card) {
            if (anyUnchecked) card.classList.add('is-active');
            else card.classList.remove('is-active');
        }
    });
    updateStatsCounter();
}

function selectAllPerms(shouldCheck) {
    const cbs = document.querySelectorAll('.perm-checkbox');
    cbs.forEach(cb => {
        cb.checked = shouldCheck;
        const card = document.getElementById('perm-card-' + cb.value);
        if (card) {
            if (shouldCheck) card.classList.add('is-active');
            else card.classList.remove('is-active');
        }
    });
    updateStatsCounter();
}

function invertAllPerms() {
    const cbs = document.querySelectorAll('.perm-checkbox');
    cbs.forEach(cb => {
        cb.checked = !cb.checked;
        const card = document.getElementById('perm-card-' + cb.value);
        if (card) {
            if (cb.checked) card.classList.add('is-active');
            else card.classList.remove('is-active');
        }
    });
    updateStatsCounter();
}

function filterPermCards() {
    const query = document.getElementById('permSearchInput').value.toLowerCase().trim();
    const items = document.querySelectorAll('.perm-col-wrapper');

    items.forEach(item => {
        const name = item.getAttribute('data-perm-name') || '';
        const desc = item.getAttribute('data-perm-desc') || '';
        if (query === '' || name.includes(query) || desc.includes(query)) {
            item.style.display = '';
        } else {
            item.style.display = 'none';
        }
    });
}
</script>

@endsection