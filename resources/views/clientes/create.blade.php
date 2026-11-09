@extends('dashboard')

@section('content')

<style>
    /* VARIABLES & TOKENS DE DISEÑO */
    :root {
        --cli-primary: #4f46e5;
        --cli-primary-dark: #3730a3;
        --cli-primary-light: #eef2ff;
        --cli-success: #10b981;
        --cli-info: #0284c7;
        --cli-warning: #f59e0b;
        --cli-card-bg: #ffffff;
        --cli-border: #f1f5f9;
    }

    .cliente-create-container {
        padding: 1.5rem 1rem;
    }

    /* CARD STYLING */
    .form-card {
        background: #ffffff;
        border-radius: 16px;
        border: 1px solid #f1f5f9;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
        margin-bottom: 20px;
        overflow: hidden;
    }

    .form-card-header {
        padding: 18px 24px;
        background: #ffffff;
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .form-card-body {
        padding: 24px;
    }

    /* SECTION HEADERS */
    .section-header {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 1.25rem;
        padding-bottom: 0.5rem;
        border-bottom: 1px dashed #e2e8f0;
    }

    .section-icon {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        background: var(--cli-primary-light);
        color: var(--cli-primary);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.95rem;
    }

    /* LIVE PREVIEW CARD */
    .preview-card {
        background: #ffffff;
        border-radius: 16px;
        border: 1px solid #f1f5f9;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
        position: sticky;
        top: 20px;
        overflow: hidden;
    }

    .preview-card-header {
        padding: 16px 20px;
        background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
        color: #ffffff;
    }

    .preview-avatar {
        width: 64px;
        height: 64px;
        border-radius: 18px;
        background: #ffffff;
        color: var(--cli-primary);
        font-weight: 800;
        font-size: 1.45rem;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        margin: 0 auto;
    }

    .btn-gradient-primary {
        background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
        color: #ffffff;
        border: none;
        transition: all 0.2s ease;
    }

    .btn-gradient-primary:hover {
        background: linear-gradient(135deg, #4338ca 0%, #6d28d9 100%);
        color: #ffffff;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(79, 70, 229, 0.35);
    }
</style>

<div class="container-fluid cliente-create-container">

    <!-- TOP HEADER -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1" style="font-size: 0.82rem;">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none text-muted"><i class="fas fa-home me-1"></i>Inicio</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('clientes.index') }}" class="text-decoration-none text-muted">Clientes</a></li>
                    <li class="breadcrumb-item active text-primary fw-semibold" aria-current="page">Nuevo Cliente</li>
                </ol>
            </nav>
            <h2 class="fw-bold text-dark m-0 d-flex align-items-center gap-2" style="font-size: 1.55rem; letter-spacing: -0.02em;">
                <i class="fas fa-user-plus text-primary"></i> Registrar Nuevo Cliente
            </h2>
            <p class="text-muted small m-0 mt-1">
                Completa los datos para registrar un cliente en la base de datos del sistema.
            </p>
        </div>

        <div>
            <a href="{{ route('clientes.index') }}" class="btn btn-outline-secondary btn-sm rounded-3 px-3 shadow-sm d-flex align-items-center gap-2">
                <i class="fas fa-arrow-left"></i>
                <span>Volver al Directorio</span>
            </a>
        </div>
    </div>

    <!-- ERROR ALERT -->
    @if ($errors->any())
        <div class="alert alert-danger rounded-4 shadow-sm border-0 mb-4 p-3">
            <div class="d-flex align-items-center gap-2 mb-2">
                <i class="fas fa-exclamation-triangle fs-5"></i>
                <span class="fw-bold">Por favor corrige los siguientes errores:</span>
            </div>
            <ul class="mb-0 ps-3 small">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row g-4">

        <!-- FORMULARIO PRINCIPAL (COLUMNA IZQUIERDA) -->
        <div class="col-12 col-lg-8">
            <div class="form-card">
                <div class="form-card-header">
                    <div class="d-flex align-items-center gap-2">
                        <i class="fas fa-edit text-primary"></i>
                        <span class="fw-bold text-dark">Información del Cliente</span>
                    </div>
                    <span class="badge bg-light text-muted border px-2 py-1 small">
                        Los campos marcados con (<span class="text-danger">*</span>) son obligatorios
                    </span>
                </div>

                <form action="{{ route('clientes.store') }}" method="POST" id="formCrearCliente">
                    @csrf
                    <input type="hidden" name="guardar_otro" id="inputGuardarOtro" value="0">

                    <div class="form-card-body">

                        <!-- SECCIÓN 1: IDENTIDAD Y DATOS PRINCIPALES -->
                        <div class="section-header">
                            <div class="section-icon">
                                <i class="fas fa-id-card"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold text-dark m-0">Datos Principales</h6>
                                <p class="text-muted small m-0">Información básica y tributaria del cliente</p>
                            </div>
                        </div>

                        <div class="row g-3 mb-4">
                            <!-- NOMBRE COMPLETO -->
                            <div class="col-12 col-md-7">
                                <label for="nombre" class="form-label fw-bold text-dark small">
                                    Nombre Completo o Razón Social <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="fas fa-user text-muted"></i></span>
                                    <input type="text"
                                           name="nombre"
                                           id="inputNombre"
                                           class="form-control @error('nombre') is-invalid @enderror"
                                           placeholder="Ej: Carlos Mendoza Quispe / Importadora San Juan S.R.L."
                                           value="{{ old('nombre') }}"
                                           required
                                           autofocus
                                           onkeyup="actualizarLivePreview()">
                                </div>
                                @error('nombre')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                                <div class="form-text small text-muted">
                                    Nombre completo de la persona natural o nombre oficial de la empresa.
                                </div>
                            </div>

                            <!-- NIT / CI / DOCUMENTO -->
                            <div class="col-12 col-md-5">
                                <label for="nit" class="form-label fw-bold text-dark small">
                                    NIT / CI / Cédula de Identidad
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="fas fa-id-badge text-muted"></i></span>
                                    <input type="text"
                                           name="nit"
                                           id="inputNit"
                                           class="form-control @error('nit') is-invalid @enderror"
                                           placeholder="Ej: 10293847012 / 6789012 LP"
                                           value="{{ old('nit') }}"
                                           onkeyup="actualizarLivePreview()">
                                </div>
                                @error('nit')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                                <div class="form-text small text-muted">
                                    Número de documento para facturación o comprobantes.
                                </div>
                            </div>
                        </div>

                        <!-- SECCIÓN 2: CANALES DE CONTACTO -->
                        <div class="section-header">
                            <div class="section-icon">
                                <i class="fas fa-address-book"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold text-dark m-0">Información de Contacto</h6>
                                <p class="text-muted small m-0">Canales para comunicación, avisos de venta y envío de recibos</p>
                            </div>
                        </div>

                        <div class="row g-3 mb-4">
                            <!-- TELÉFONO / WHATSAPP -->
                            <div class="col-12 col-md-6">
                                <label for="telefono" class="form-label fw-bold text-dark small">
                                    Teléfono / Celular / WhatsApp
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="fas fa-phone text-muted"></i></span>
                                    <input type="text"
                                           name="telefono"
                                           id="inputTelefono"
                                           class="form-control @error('telefono') is-invalid @enderror"
                                           placeholder="Ej: 77889900 ó +591 77889900"
                                           value="{{ old('telefono') }}"
                                           onkeyup="actualizarLivePreview()">
                                </div>
                                @error('telefono')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                                <div class="form-text small text-muted">
                                    Permite enviar comprobantes por WhatsApp con un solo clic.
                                </div>
                            </div>

                            <!-- CORREO ELECTRÓNICO -->
                            <div class="col-12 col-md-6">
                                <label for="email" class="form-label fw-bold text-dark small">
                                    Correo Electrónico
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="fas fa-envelope text-muted"></i></span>
                                    <input type="email"
                                           name="email"
                                           id="inputEmail"
                                           class="form-control @error('email') is-invalid @enderror"
                                           placeholder="Ej: cliente@correo.com"
                                           value="{{ old('email') }}"
                                           onkeyup="actualizarLivePreview()">
                                </div>
                                @error('email')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                                <div class="form-text small text-muted">
                                    Para envío de comprobantes electrónicos y cotizaciones.
                                </div>
                            </div>
                        </div>

                        <hr class="my-4" style="border-color: #f1f5f9;">

                        <!-- BOTONES DE ACCIÓN -->
                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                            <a href="{{ route('clientes.index') }}" class="btn btn-light rounded-3 px-4 border text-muted">
                                <i class="fas fa-times me-1"></i> Cancelar
                            </a>

                            <div class="d-flex flex-wrap gap-2">
                                <button type="button" class="btn btn-outline-primary rounded-3 px-3" onclick="guardarYCrearOtro()">
                                    <i class="fas fa-plus me-1"></i> Guardar y Crear Otro
                                </button>

                                <button type="submit" class="btn btn-gradient-primary rounded-3 px-4 shadow-sm">
                                    <i class="fas fa-save me-1"></i> Guardar Cliente
                                </button>
                            </div>
                        </div>

                    </div>
                </form>
            </div>
        </div>

        <!-- TARJETA DE PREVISUALIZACIÓN EN VIVO (COLUMNA DERECHA) -->
        <div class="col-12 col-lg-4">
            <div class="preview-card">
                <div class="preview-card-header text-center">
                    <div class="small fw-semibold opacity-75 mb-2 text-uppercase tracking-wider">
                        <i class="fas fa-eye me-1"></i> Vista Previa en Tiempo Real
                    </div>
                    <div class="preview-avatar mb-2" id="liveAvatar">
                        --
                    </div>
                    <h5 class="fw-bold m-0 text-white text-truncate" id="liveNombre">
                        Nombre del Cliente
                    </h5>
                    <span class="badge bg-white bg-opacity-25 text-white rounded-pill px-3 py-1 mt-1 small">
                        Cliente Nuevo
                    </span>
                </div>

                <div class="p-3">
                    <div class="p-2 bg-light rounded-3 border mb-2">
                        <div class="text-muted small fw-semibold">Documento / NIT</div>
                        <div class="fw-bold text-dark mt-1" id="liveNit">
                            <span class="text-muted fst-italic">Sin especificar</span>
                        </div>
                    </div>

                    <div class="p-2 bg-light rounded-3 border mb-2">
                        <div class="text-muted small fw-semibold">Teléfono / WhatsApp</div>
                        <div class="fw-bold text-dark mt-1" id="liveTelefono">
                            <span class="text-muted fst-italic">Sin especificar</span>
                        </div>
                    </div>

                    <div class="p-2 bg-light rounded-3 border mb-3">
                        <div class="text-muted small fw-semibold">Correo Electrónico</div>
                        <div class="fw-bold text-dark text-truncate mt-1" id="liveEmail">
                            <span class="text-muted fst-italic">Sin especificar</span>
                        </div>
                    </div>

                    <!-- TIPS DE AYUDA -->
                    <div class="p-3 rounded-3" style="background: #f8fafc; border: 1px dashed #cbd5e1;">
                        <div class="d-flex align-items-center gap-2 text-primary fw-bold small mb-1">
                            <i class="fas fa-lightbulb"></i> ¿Sabías que?
                        </div>
                        <p class="text-muted small m-0" style="font-size: 0.78rem;">
                            Si ingresas el número de WhatsApp, podrás enviar el comprobante de venta digital directamente con un solo clic desde el módulo de ventas.
                        </p>
                    </div>
                </div>
            </div>
        </div>

    </div>

</div>

<!-- SCRIPT LIVE PREVIEW -->
<script>
function actualizarLivePreview() {
    const nombre = document.getElementById('inputNombre').value.trim();
    const nit = document.getElementById('inputNit').value.trim();
    const telefono = document.getElementById('inputTelefono').value.trim();
    const email = document.getElementById('inputEmail').value.trim();

    const avatarEl = document.getElementById('liveAvatar');
    const nombreEl = document.getElementById('liveNombre');
    const nitEl = document.getElementById('liveNit');
    const telEl = document.getElementById('liveTelefono');
    const emailEl = document.getElementById('liveEmail');

    // Nombre e iniciales
    if (nombre) {
        nombreEl.innerText = nombre;
        const words = nombre.split(' ').filter(w => w.length > 0);
        let inits = '';
        if (words.length >= 2) {
            inits = words[0][0] + words[1][0];
        } else {
            inits = nombre.substring(0, 2);
        }
        avatarEl.innerText = inits.toUpperCase();
    } else {
        nombreEl.innerText = 'Nombre del Cliente';
        avatarEl.innerText = '--';
    }

    // NIT
    if (nit) {
        nitEl.innerHTML = `<span class="badge bg-light text-dark border"><i class="fas fa-id-card text-muted me-1"></i>${nit}</span>`;
    } else {
        nitEl.innerHTML = '<span class="text-muted fst-italic">Sin especificar</span>';
    }

    // Teléfono
    if (telefono) {
        telEl.innerHTML = `<span class="badge bg-success bg-opacity-10 text-success border border-success-subtle"><i class="fab fa-whatsapp me-1"></i>${telefono}</span>`;
    } else {
        telEl.innerHTML = '<span class="text-muted fst-italic">Sin especificar</span>';
    }

    // Email
    if (email) {
        emailEl.innerHTML = `<span class="badge bg-info bg-opacity-10 text-info border border-info-subtle"><i class="fas fa-envelope me-1"></i>${email}</span>`;
    } else {
        emailEl.innerHTML = '<span class="text-muted fst-italic">Sin especificar</span>';
    }
}

function guardarYCrearOtro() {
    document.getElementById('inputGuardarOtro').value = '1';
    document.getElementById('formCrearCliente').submit();
}

// Ejecutar al cargar por si hay valores de sesión / old()
document.addEventListener('DOMContentLoaded', function() {
    actualizarLivePreview();
});
</script>

@endsection