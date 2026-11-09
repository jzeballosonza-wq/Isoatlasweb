@extends('dashboard')

@section('content')

<style>
    .edit-prov-container {
        padding: 1.5rem 1rem;
        max-width: 1000px;
        margin: 0 auto;
    }

    .form-card {
        background: #ffffff;
        border-radius: 20px;
        border: 1px solid #f1f5f9;
        box-shadow: 0 4px 25px rgba(0, 0, 0, 0.04);
        overflow: hidden;
    }

    .form-section-header {
        border-bottom: 1px solid #f1f5f9;
        padding: 1.25rem 1.75rem;
        background: #fafbfc;
    }

    .form-section-title {
        font-size: 0.95rem;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .prov-avatar-header {
        width: 52px;
        height: 52px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        font-weight: 700;
        color: #ffffff;
        flex-shrink: 0;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }
</style>

<div class="edit-prov-container">

    <!-- BREADCRUMB / VOLVER -->
    <div class="mb-3">
        <a href="{{ route('proveedores.index') }}" class="text-decoration-none text-muted small fw-semibold d-inline-flex align-items-center gap-1">
            <i class="fas fa-arrow-left"></i> Volver al directorio de proveedores
        </a>
    </div>

    <!-- ENCABEZADO CON AVATAR -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="d-flex align-items-center gap-3">
            <div class="prov-avatar-header" style="background: {{ $proveedore->avatar_gradient }};">
                {{ $proveedore->iniciales }}
            </div>
            <div>
                <h2 class="h4 fw-bold text-dark mb-0">Editar Proveedor: {{ $proveedore->nombre }}</h2>
                <p class="text-muted small mb-0">Actualice la información legal, dirección y datos de contacto.</p>
            </div>
        </div>

        <a href="{{ route('proveedores.show', $proveedore->id) }}" class="btn btn-sm btn-outline-primary rounded-3 px-3 py-2 fw-semibold">
            <i class="fas fa-eye me-1"></i> Ver Ficha
        </a>
    </div>

    <!-- FLASH ERRORS -->
    @if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm rounded-4 mb-4" role="alert">
        <div class="d-flex align-items-start gap-2">
            <i class="fas fa-exclamation-triangle fs-5 text-danger mt-1"></i>
            <div>
                <div class="fw-bold mb-1">Por favor corrija los siguientes errores:</div>
                <ul class="mb-0 small ps-3">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="form-card">
        <form action="{{ route('proveedores.update', $proveedore->id) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- SECCIÓN 1: DATOS DE LA EMPRESA -->
            <div class="form-section-header">
                <h5 class="form-section-title">
                    <i class="fas fa-building text-primary"></i>
                    Información de la Empresa Proveedora
                </h5>
            </div>
            <div class="p-4">
                <div class="row g-3">
                    <!-- Razón Social / Nombre Comercial -->
                    <div class="col-md-6">
                        <label class="form-label fw-semibold text-secondary small mb-1">
                            Razón Social / Nombre Comercial <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 rounded-start-3 text-muted">
                                <i class="fas fa-store"></i>
                            </span>
                            <input type="text" name="nombre" class="form-control border-start-0 rounded-end-3" placeholder="Ej: Lenovo Mayorista Bolivia S.R.L." value="{{ old('nombre', $proveedore->nombre) }}" required>
                        </div>
                    </div>

                    <!-- Dirección -->
                    <div class="col-md-6">
                        <label class="form-label fw-semibold text-secondary small mb-1">
                            Dirección Física / Almacén Central <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 rounded-start-3 text-muted">
                                <i class="fas fa-map-marker-alt"></i>
                            </span>
                            <input type="text" name="direccion" class="form-control border-start-0 rounded-end-3" placeholder="Ej: Av. Melchor Urquidi #1240, Cochabamba" value="{{ old('direccion', $proveedore->direccion) }}" required>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SECCIÓN 2: CONTACTO Y COMUNICACIÓN -->
            <div class="form-section-header">
                <h5 class="form-section-title">
                    <i class="fas fa-user-tie text-primary"></i>
                    Datos de Contacto Directo & Ventas
                </h5>
            </div>
            <div class="p-4">
                <div class="row g-3">
                    <!-- Persona de Contacto -->
                    <div class="col-md-6">
                        <label class="form-label fw-semibold text-secondary small mb-1">
                            Persona de Contacto / Asesor Comercial
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 rounded-start-3 text-muted">
                                <i class="fas fa-user"></i>
                            </span>
                            <input type="text" name="contacto" class="form-control border-start-0 rounded-end-3" placeholder="Ej: Lic. Carlos Mendoza (Ejecutivo de Cuentas)" value="{{ old('contacto', $proveedore->contacto) }}">
                        </div>
                    </div>

                    <!-- Email -->
                    <div class="col-md-6">
                        <label class="form-label fw-semibold text-secondary small mb-1">
                            Correo Electrónico Corporativo
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 rounded-start-3 text-muted">
                                <i class="fas fa-envelope"></i>
                            </span>
                            <input type="email" name="email" class="form-control border-start-0 rounded-end-3" placeholder="ventas@empresa.com" value="{{ old('email', $proveedore->email) }}">
                        </div>
                    </div>

                    <!-- Teléfono Fijo -->
                    <div class="col-md-6">
                        <label class="form-label fw-semibold text-secondary small mb-1">
                            Teléfono Fijo / Centralita <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 rounded-start-3 text-muted">
                                <i class="fas fa-phone-alt"></i>
                            </span>
                            <input type="text" name="telefono" class="form-control border-start-0 rounded-end-3" placeholder="Ej: 4-4289000 o 2-2451234" value="{{ old('telefono', $proveedore->telefono) }}" required>
                        </div>
                    </div>

                    <!-- Celular / WhatsApp -->
                    <div class="col-md-6">
                        <label class="form-label fw-semibold text-secondary small mb-1">
                            Celular Directo / WhatsApp
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 rounded-start-3 text-success">
                                <i class="fab fa-whatsapp"></i>
                            </span>
                            <input type="text" name="celular" class="form-control border-start-0 rounded-end-3" placeholder="Ej: 71234567" value="{{ old('celular', $proveedore->celular) }}">
                        </div>
                    </div>
                </div>
            </div>

            <!-- FOOTER DE ACCIONES -->
            <div class="p-4 bg-light border-top d-flex justify-content-between align-items-center flex-wrap gap-2">
                <a href="{{ route('proveedores.index') }}" class="btn btn-outline-secondary rounded-3 px-4 py-2 fw-semibold">
                    <i class="fas fa-times me-1"></i> Cancelar
                </a>

                <button type="submit" class="btn text-white rounded-3 px-4 py-2 fw-semibold d-inline-flex align-items-center gap-2" style="background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%); box-shadow: 0 4px 14px rgba(2, 132, 199, 0.35);">
                    <i class="fas fa-save"></i>
                    <span>Actualizar Proveedor</span>
                </button>
            </div>
        </form>
    </div>

</div>

@endsection