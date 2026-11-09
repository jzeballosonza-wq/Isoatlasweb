@extends('dashboard')

@section('content')

<style>
    .form-container-card {
        background: #ffffff;
        border-radius: 20px;
        border: 1px solid #f1f5f9;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04);
        overflow: hidden;
    }

    .form-header-gradient {
        background: linear-gradient(135deg, #7c3aed 0%, #4f46e5 100%);
        padding: 1.75rem 2rem;
        color: #ffffff;
        position: relative;
    }

    .form-input-group .input-group-text {
        background-color: #f8fafc;
        border-color: #e2e8f0;
        color: #64748b;
    }

    .form-input-group .form-control {
        border-color: #e2e8f0;
        padding: 0.65rem 0.9rem;
        font-size: 0.92rem;
    }

    .form-input-group .form-control:focus {
        border-color: #7c3aed;
        box-shadow: 0 0 0 3px rgba(124, 58, 237, 0.15);
    }

    .form-label-custom {
        font-size: 0.82rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        color: #475569;
        margin-bottom: 0.4rem;
        display: block;
    }

    .perm-card-check {
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 12px 14px;
        background: #ffffff;
        transition: all 0.2s ease;
        cursor: pointer;
        height: 100%;
    }

    .perm-card-check:hover {
        border-color: #cbd5e1;
        background: #f8fafc;
    }

    .perm-card-check.active {
        border-color: #7c3aed;
        background: #fbf7ff;
        box-shadow: 0 4px 12px rgba(124, 58, 237, 0.1);
    }

    .module-group-box {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        padding: 16px;
        margin-bottom: 16px;
    }
</style>

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-9 col-xl-8">

            <!-- BREADCRUMB & BACK LINK -->
            <div class="mb-3">
                <a href="{{ route('roles.index') }}" class="text-decoration-none text-muted small fw-semibold d-inline-flex align-items-center gap-1">
                    <i class="fas fa-arrow-left"></i> Volver al listado de roles
                </a>
            </div>

            <div class="form-container-card">
                <!-- ENCABEZADO CON GRADIENTE -->
                <div class="form-header-gradient">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <span class="badge bg-white bg-opacity-20 text-white rounded-pill px-3 py-1 small fw-semibold mb-2">
                                <i class="fas fa-shield-alt me-1"></i> Perfil de Seguridad
                            </span>
                            <h3 class="h4 fw-bold mb-1">Crear Nuevo Rol de Acceso</h3>
                            <p class="mb-0 text-white-50 small">Define un rol y selecciona los permisos que tendrán los usuarios asignados.</p>
                        </div>
                        <div class="d-none d-sm-flex align-items-center justify-content-center" 
                            style="width: 58px; height: 58px; border-radius: 16px; background: rgba(255,255,255,0.2); border: 2px solid rgba(255,255,255,0.4); font-size: 1.5rem;">
                            <i class="fas fa-user-shield"></i>
                        </div>
                    </div>
                </div>

                <!-- CUERPO DEL FORMULARIO -->
                <div class="p-4 p-md-5">

                    @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show rounded-3 p-3 mb-4" role="alert">
                        <div class="d-flex align-items-center gap-2 mb-1">
                            <i class="fas fa-exclamation-circle fs-5"></i>
                            <strong class="small">Por favor corrige los siguientes errores:</strong>
                        </div>
                        <ul class="mb-0 small ps-3">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close small" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif

                    <form method="POST" action="{{ route('roles.store') }}" id="createRoleForm">
                        @csrf

                        <!-- NOMBRE DEL ROL -->
                        <div class="mb-4">
                            <label class="form-label-custom" for="rolNombreInput">
                                <i class="fas fa-tag text-primary me-1"></i> Nombre del Rol <span class="text-danger">*</span>
                            </label>
                            <div class="input-group form-input-group">
                                <span class="input-group-text"><i class="fas fa-shield-alt"></i></span>
                                <input type="text" name="nombre" id="rolNombreInput" 
                                    class="form-control @error('nombre') is-invalid @enderror" 
                                    placeholder="Ej: Supervisor de Almacén, Vendedor Senior..." 
                                    value="{{ old('nombre') }}" 
                                    required autofocus>
                            </div>
                            @error('nombre')
                                <div class="invalid-feedback d-block small mt-1">{{ $message }}</div>
                            @enderror
                            <div class="form-text small text-muted">Nombre distintivo del perfil de seguridad en el sistema.</div>
                        </div>

                        <!-- DESCRIPCIÓN -->
                        <div class="mb-4">
                            <label class="form-label-custom" for="rolDescInput">
                                <i class="fas fa-align-left text-primary me-1"></i> Descripción de Funciones
                            </label>
                            <div class="input-group form-input-group">
                                <span class="input-group-text"><i class="fas fa-info-circle"></i></span>
                                <textarea name="descripcion" id="rolDescInput" 
                                    class="form-control @error('descripcion') is-invalid @enderror" 
                                    rows="2" 
                                    placeholder="Describe las responsabilidades o alcance de este rol...">{{ old('descripcion') }}</textarea>
                            </div>
                            @error('descripcion')
                                <div class="invalid-feedback d-block small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- MATRIZ DE PERMISOS INICIALES -->
                        <div class="mt-4 pt-3 border-top">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    <h5 class="fw-bold text-dark mb-0" style="font-size: 0.95rem;">
                                        <i class="fas fa-key text-warning me-1"></i> Permisos Iniciales del Rol
                                    </h5>
                                    <small class="text-muted">Marca los módulos a los que tendrá acceso este rol.</small>
                                </div>
                                <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-3" onclick="toggleAllPerms()">
                                    <i class="fas fa-check-double me-1"></i> Marcar Todos
                                </button>
                            </div>

                            @foreach($modulosPermisos as $modKey => $modulo)
                            <div class="module-group-box">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="{{ $modulo['icono'] }}" style="color: {{ $modulo['color'] }}; font-size: 1rem;"></i>
                                        <span class="fw-bold text-dark small">{{ $modulo['titulo'] }}</span>
                                    </div>
                                    <button type="button" class="btn btn-link btn-sm text-decoration-none p-0 small fw-semibold" 
                                        onclick="toggleGroupPerms('group-{{ $modKey }}')">
                                        Marcar grupo
                                    </button>
                                </div>

                                <div class="row g-2">
                                    @foreach($modulo['permisos'] as $p)
                                    @php
                                        $permisoModel = $todosPermisos->get($p['clave']);
                                    @endphp
                                    @if($permisoModel)
                                    <div class="col-md-6">
                                        <label class="perm-card-check d-flex align-items-start gap-2" id="label-perm-{{ $permisoModel->id }}">
                                            <input type="checkbox" name="permisos[]" value="{{ $permisoModel->id }}" 
                                                class="form-check-input mt-1 group-{{ $modKey }} perm-checkbox" 
                                                id="check-perm-{{ $permisoModel->id }}"
                                                onchange="toggleCardActive(this, 'label-perm-{{ $permisoModel->id }}')">
                                            <div>
                                                <div class="fw-bold text-dark small mb-0">{{ $p['nombre'] }}</div>
                                                <div class="text-muted" style="font-size: 0.72rem; line-height: 1.3;">{{ $p['descripcion'] }}</div>
                                            </div>
                                        </label>
                                    </div>
                                    @endif
                                    @endforeach
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <!-- BOTONES DE ACCIÓN -->
                        <div class="d-flex justify-content-end gap-2 pt-3 border-top mt-4">
                            <a href="{{ route('roles.index') }}" class="btn btn-light border px-4 py-2 fw-semibold text-secondary rounded-3">
                                Cancelar
                            </a>
                            <button type="submit" class="btn text-white px-4 py-2 fw-semibold rounded-3 d-inline-flex align-items-center gap-2" 
                                style="background: linear-gradient(135deg, #7c3aed 0%, #4f46e5 100%); box-shadow: 0 4px 12px rgba(124, 58, 237, 0.3);">
                                <i class="fas fa-save"></i>
                                <span>Guardar Rol</span>
                            </button>
                        </div>

                    </form>

                </div>
            </div>

        </div>
    </div>
</div>

<script>
function toggleCardActive(checkbox, labelId) {
    const label = document.getElementById(labelId);
    if (checkbox.checked) {
        label.classList.add('active');
    } else {
        label.classList.remove('active');
    }
}

function toggleGroupPerms(groupClass) {
    const checkboxes = document.querySelectorAll('.' + groupClass);
    const anyUnchecked = Array.from(checkboxes).some(cb => !cb.checked);
    checkboxes.forEach(cb => {
        cb.checked = anyUnchecked;
        const label = document.getElementById('label-perm-' + cb.value);
        if (label) {
            if (anyUnchecked) label.classList.add('active');
            else label.classList.remove('active');
        }
    });
}

function toggleAllPerms() {
    const checkboxes = document.querySelectorAll('.perm-checkbox');
    const anyUnchecked = Array.from(checkboxes).some(cb => !cb.checked);
    checkboxes.forEach(cb => {
        cb.checked = anyUnchecked;
        const label = document.getElementById('label-perm-' + cb.value);
        if (label) {
            if (anyUnchecked) label.classList.add('active');
            else label.classList.remove('active');
        }
    });
}
</script>

@endsection