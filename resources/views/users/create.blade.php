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
        background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
        padding: 1.75rem 2rem;
        color: #ffffff;
        position: relative;
    }

    .form-input-group .input-group-text {
        background-color: #f8fafc;
        border-color: #e2e8f0;
        color: #64748b;
    }

    .form-input-group .form-control,
    .form-input-group .form-select {
        border-color: #e2e8f0;
        padding: 0.65rem 0.9rem;
        font-size: 0.92rem;
    }

    .form-input-group .form-control:focus,
    .form-input-group .form-select:focus {
        border-color: #6366f1;
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.15);
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

    .avatar-preview-box {
        width: 68px;
        height: 68px;
        border-radius: 18px;
        background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
        color: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        font-size: 1.5rem;
        box-shadow: 0 8px 18px rgba(79, 70, 229, 0.3);
        transition: all 0.3s ease;
    }

    .btn-toggle-password {
        border-color: #e2e8f0;
        background: #f8fafc;
        color: #64748b;
    }

    .btn-toggle-password:hover {
        background: #e2e8f0;
        color: #334155;
    }
</style>

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-xl-7">

            <!-- BREADCRUMB & BACK LINK -->
            <div class="mb-3">
                <a href="{{ route('users.index') }}" class="text-decoration-none text-muted small fw-semibold d-inline-flex align-items-center gap-1">
                    <i class="fas fa-arrow-left"></i> Volver al listado de usuarios
                </a>
            </div>

            <div class="form-container-card">
                <!-- ENCABEZADO CON GRADIENTE -->
                <div class="form-header-gradient">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <span class="badge bg-white bg-opacity-20 text-white rounded-pill px-3 py-1 small fw-semibold mb-2">
                                <i class="fas fa-user-plus me-1"></i> Nuevo Registro
                            </span>
                            <h3 class="h4 fw-bold mb-1">Registrar Nuevo Usuario</h3>
                            <p class="mb-0 text-white-50 small">Crea una cuenta de acceso con credenciales y asigna un rol de seguridad.</p>
                        </div>
                        <div class="avatar-preview-box d-none d-sm-flex" id="avatarLivePreview">
                            NU
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

                    <form method="POST" action="{{ route('users.store') }}" id="createUserForm">
                        @csrf

                        <!-- NOMBRE COMPLETO -->
                        <div class="mb-4">
                            <label class="form-label-custom" for="userNameInput">
                                <i class="fas fa-user text-primary me-1"></i> Nombre Completo <span class="text-danger">*</span>
                            </label>
                            <div class="input-group form-input-group">
                                <span class="input-group-text"><i class="fas fa-user-tie"></i></span>
                                <input type="text" name="name" id="userNameInput" 
                                    class="form-control @error('name') is-invalid @enderror" 
                                    placeholder="Ej: Lic. Carlos Mendoza" 
                                    value="{{ old('name') }}" 
                                    required autofocus
                                    onkeyup="updateAvatarPreview(this.value)">
                            </div>
                            @error('name')
                                <div class="invalid-feedback d-block small mt-1">{{ $message }}</div>
                            @enderror
                            <div class="form-text small text-muted">Nombre y apellidos del usuario para comprobantes y bitácora.</div>
                        </div>

                        <!-- CORREO ELECTRÓNICO -->
                        <div class="mb-4">
                            <label class="form-label-custom" for="userEmailInput">
                                <i class="fas fa-envelope text-primary me-1"></i> Correo Electrónico (Login) <span class="text-danger">*</span>
                            </label>
                            <div class="input-group form-input-group">
                                <span class="input-group-text"><i class="fas fa-at"></i></span>
                                <input type="email" name="email" id="userEmailInput" 
                                    class="form-control @error('email') is-invalid @enderror" 
                                    placeholder="usuario@isoatlas.com" 
                                    value="{{ old('email') }}" 
                                    required>
                            </div>
                            @error('email')
                                <div class="invalid-feedback d-block small mt-1">{{ $message }}</div>
                            @enderror
                            <div class="form-text small text-muted">Se utilizará para iniciar sesión en el sistema.</div>
                        </div>

                        <!-- CONTRASEÑA & CONFIRMACIÓN -->
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label-custom" for="userPasswordInput">
                                    <i class="fas fa-lock text-primary me-1"></i> Contraseña <span class="text-danger">*</span>
                                </label>
                                <div class="input-group form-input-group">
                                    <span class="input-group-text"><i class="fas fa-key"></i></span>
                                    <input type="password" name="password" id="userPasswordInput" 
                                        class="form-control @error('password') is-invalid @enderror" 
                                        placeholder="Mínimo 6 caracteres" 
                                        required>
                                    <button class="btn btn-toggle-password" type="button" onclick="togglePassword('userPasswordInput', this)">
                                        <i class="far fa-eye"></i>
                                    </button>
                                </div>
                                @error('password')
                                    <div class="invalid-feedback d-block small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label-custom" for="userPasswordConfirmInput">
                                    <i class="fas fa-shield-alt text-primary me-1"></i> Confirmar Contraseña <span class="text-danger">*</span>
                                </label>
                                <div class="input-group form-input-group">
                                    <span class="input-group-text"><i class="fas fa-check-double"></i></span>
                                    <input type="password" name="password_confirmation" id="userPasswordConfirmInput" 
                                        class="form-control" 
                                        placeholder="Repita la contraseña" 
                                        required>
                                    <button class="btn btn-toggle-password" type="button" onclick="togglePassword('userPasswordConfirmInput', this)">
                                        <i class="far fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- SELECCIÓN DE ROL -->
                        <div class="mb-4">
                            <label class="form-label-custom" for="userRoleSelect">
                                <i class="fas fa-id-badge text-primary me-1"></i> Rol y Perfil de Acceso
                            </label>
                            <div class="input-group form-input-group">
                                <span class="input-group-text"><i class="fas fa-user-shield"></i></span>
                                <select name="rol_id" id="userRoleSelect" class="form-select @error('rol_id') is-invalid @enderror">
                                    <option value="">-- Sin Rol Asignado (Acceso Restringido) --</option>
                                    @foreach($roles as $r)
                                        <option value="{{ $r->id }}" {{ old('rol_id') == $r->id ? 'selected' : '' }}>
                                            {{ $r->nombre }} ({{ $r->permisos_count ?? $r->permisos()->count() }} permisos asociados)
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            @error('rol_id')
                                <div class="invalid-feedback d-block small mt-1">{{ $message }}</div>
                            @enderror
                            <div class="form-text small text-muted">Define los módulos y permisos a los que tendrá acceso en el sistema.</div>
                        </div>

                        <!-- BOTONES DE ACCIÓN -->
                        <div class="d-flex justify-content-end gap-2 pt-3 border-top mt-4">
                            <a href="{{ route('users.index') }}" class="btn btn-light border px-4 py-2 fw-semibold text-secondary rounded-3">
                                Cancelar
                            </a>
                            <button type="submit" class="btn text-white px-4 py-2 fw-semibold rounded-3 d-inline-flex align-items-center gap-2" 
                                style="background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%); box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3);">
                                <i class="fas fa-save"></i>
                                <span>Guardar Usuario</span>
                            </button>
                        </div>

                    </form>

                </div>
            </div>

        </div>
    </div>
</div>

<script>
function togglePassword(inputId, btn) {
    const input = document.getElementById(inputId);
    const icon = btn.querySelector('i');
    if (input.type === 'password') {
        input.type = 'text';
        icon.className = 'far fa-eye-slash';
    } else {
        input.type = 'password';
        icon.className = 'far fa-eye';
    }
}

function updateAvatarPreview(name) {
    const preview = document.getElementById('avatarLivePreview');
    if (!name || name.trim() === '') {
        preview.textContent = 'NU';
        return;
    }
    const words = name.trim().split(/\s+/);
    let initials = '';
    for (let i = 0; i < Math.min(2, words.length); i++) {
        if (words[i].length > 0) initials += words[i][0].toUpperCase();
    }
    preview.textContent = initials || 'US';
}
</script>

@endsection