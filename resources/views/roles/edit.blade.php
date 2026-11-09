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
        background: linear-gradient(135deg, #059669 0%, #10b981 100%);
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
        border-color: #10b981;
        box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.15);
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

    .role-meta-box {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        padding: 16px 20px;
    }
</style>

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-xl-7">

            <!-- BREADCRUMB & BACK LINK -->
            <div class="mb-3 d-flex justify-content-between align-items-center">
                <a href="{{ route('roles.index') }}" class="text-decoration-none text-muted small fw-semibold d-inline-flex align-items-center gap-1">
                    <i class="fas fa-arrow-left"></i> Volver al listado de roles
                </a>

                <a href="{{ route('roles.permisos', $role->id) }}" class="btn btn-sm btn-outline-warning text-dark fw-semibold rounded-pill px-3 d-inline-flex align-items-center gap-1">
                    <i class="fas fa-key text-warning"></i> Configurar Permisos ({{ $role->permisos->count() }})
                </a>
            </div>

            <div class="form-container-card">
                <!-- ENCABEZADO CON GRADIENTE -->
                <div class="form-header-gradient" style="background: {{ $role->color_badge['bg'] }};">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <span class="badge bg-white bg-opacity-20 text-white rounded-pill px-3 py-1 small fw-semibold mb-2">
                                <i class="fas fa-pen me-1"></i> Modificación de Rol
                            </span>
                            <h3 class="h4 fw-bold mb-1">Editar Rol: {{ $role->nombre }}</h3>
                            <p class="mb-0 text-white-50 small">Actualiza el nombre identificador y la descripción de este rol.</p>
                        </div>
                        <div class="d-none d-sm-flex align-items-center justify-content-center" 
                            style="width: 58px; height: 58px; border-radius: 16px; background: rgba(255,255,255,0.2); border: 2px solid rgba(255,255,255,0.4); font-size: 1.5rem;">
                            <i class="{{ $role->color_badge['icon'] }}"></i>
                        </div>
                    </div>
                </div>

                <!-- RESUMEN METADATOS DEL ROL -->
                <div class="bg-light p-3 border-bottom px-4">
                    <div class="row g-2 text-center text-sm-start">
                        <div class="col-sm-6">
                            <span class="text-muted small">Usuarios con este rol:</span>
                            <strong class="text-dark d-block">
                                <i class="fas fa-users text-primary me-1"></i> {{ $role->users->count() }} usuario(s) asignados
                            </strong>
                        </div>
                        <div class="col-sm-6">
                            <span class="text-muted small">Permisos habilitados:</span>
                            <strong class="text-dark d-block">
                                <i class="fas fa-shield-alt text-success me-1"></i> {{ $role->permisos->count() }} de 10 permisos activos
                            </strong>
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

                    <form method="POST" action="{{ route('roles.update', $role) }}" id="editRoleForm">
                        @csrf
                        @method('PUT')

                        <!-- NOMBRE DEL ROL -->
                        <div class="mb-4">
                            <label class="form-label-custom" for="rolNombreInput">
                                <i class="fas fa-tag text-primary me-1"></i> Nombre del Rol <span class="text-danger">*</span>
                            </label>
                            <div class="input-group form-input-group">
                                <span class="input-group-text"><i class="fas fa-shield-alt"></i></span>
                                <input type="text" name="nombre" id="rolNombreInput" 
                                    class="form-control @error('nombre') is-invalid @enderror" 
                                    value="{{ old('nombre', $role->nombre) }}" 
                                    required autofocus>
                            </div>
                            @error('nombre')
                                <div class="invalid-feedback d-block small mt-1">{{ $message }}</div>
                            @enderror
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
                                    rows="3" 
                                    placeholder="Describe las responsabilidades de este rol...">{{ old('descripcion', $role->descripcion) }}</textarea>
                            </div>
                            @error('descripcion')
                                <div class="invalid-feedback d-block small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- ACCESO DIRECTO A GESTIÓN DE PERMISOS -->
                        <div class="role-meta-box mb-4">
                            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                                <div>
                                    <h6 class="fw-bold text-dark mb-1 small">
                                        <i class="fas fa-key text-warning me-1"></i> Matriz de Permisos
                                    </h6>
                                    <p class="text-muted small mb-0">
                                        Configura individualmente qué acciones y vistas puede consultar este rol.
                                    </p>
                                </div>
                                <a href="{{ route('roles.permisos', $role->id) }}" class="btn btn-warning btn-sm text-dark fw-bold rounded-pill px-3 shadow-sm">
                                    <i class="fas fa-external-link-alt me-1"></i> Modificar Permisos
                                </a>
                            </div>
                        </div>

                        <!-- BOTONES DE ACCIÓN -->
                        <div class="d-flex justify-content-end gap-2 pt-3 border-top mt-4">
                            <a href="{{ route('roles.index') }}" class="btn btn-light border px-4 py-2 fw-semibold text-secondary rounded-3">
                                Cancelar
                            </a>
                            <button type="submit" class="btn text-white px-4 py-2 fw-semibold rounded-3 d-inline-flex align-items-center gap-2" 
                                style="background: linear-gradient(135deg, #059669 0%, #10b981 100%); box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);">
                                <i class="fas fa-sync-alt"></i>
                                <span>Actualizar Rol</span>
                            </button>
                        </div>

                    </form>

                </div>
            </div>

        </div>
    </div>
</div>

@endsection