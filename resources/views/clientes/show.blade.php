@extends('dashboard')

@section('content')

<style>
    :root {
        --cli-primary: #4f46e5;
        --cli-primary-dark: #3730a3;
        --cli-primary-light: #eef2ff;
        --cli-success: #10b981;
        --cli-info: #0284c7;
        --cli-warning: #f59e0b;
        --cli-danger: #ef4444;
        --cli-card-bg: #ffffff;
        --cli-border: #f1f5f9;
    }

    .cliente-show-container {
        padding: 1.5rem 1rem;
    }

    .profile-card {
        background: #ffffff;
        border-radius: 16px;
        border: 1px solid #f1f5f9;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
        overflow: hidden;
        margin-bottom: 20px;
    }

    .profile-card-header {
        background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
        padding: 2.5rem 1.5rem 1.5rem;
        text-align: center;
        color: #ffffff;
        position: relative;
    }

    .profile-avatar {
        width: 80px;
        height: 80px;
        border-radius: 22px;
        background: #ffffff;
        color: var(--cli-primary);
        font-weight: 800;
        font-size: 1.8rem;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 12px;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    }

    .info-card {
        background: #ffffff;
        border-radius: 16px;
        border: 1px solid #f1f5f9;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
        padding: 1.5rem;
        height: 100%;
    }

    .info-item {
        padding: 12px 14px;
        background: #f8fafc;
        border-radius: 10px;
        border: 1px solid #e2e8f0;
        margin-bottom: 12px;
    }

    .btn-gradient-primary {
        background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
        color: #ffffff;
        border: none;
    }

    .btn-gradient-primary:hover {
        background: linear-gradient(135deg, #4338ca 0%, #6d28d9 100%);
        color: #ffffff;
    }
</style>

@php
    $words = explode(' ', trim($cliente->nombre));
    $initials = '';
    if (count($words) >= 2) {
        $initials = mb_substr($words[0], 0, 1) . mb_substr($words[1], 0, 1);
    } else {
        $initials = mb_substr($cliente->nombre, 0, 2);
    }
    $initials = mb_strtoupper($initials);
    $cleanPhone = preg_replace('/[^0-9]/', '', $cliente->telefono);
@endphp

<div class="container-fluid cliente-show-container">

    <!-- TOP HEADER -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1" style="font-size: 0.82rem;">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none text-muted"><i class="fas fa-home me-1"></i>Inicio</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('clientes.index') }}" class="text-decoration-none text-muted">Clientes</a></li>
                    <li class="breadcrumb-item active text-primary fw-semibold" aria-current="page">{{ $cliente->nombre }}</li>
                </ol>
            </nav>
            <h2 class="fw-bold text-dark m-0 d-flex align-items-center gap-2" style="font-size: 1.55rem; letter-spacing: -0.02em;">
                <i class="fas fa-id-card text-primary"></i> Ficha y Perfil de Cliente
            </h2>
            <p class="text-muted small m-0 mt-1">
                Consulta completa de datos personales, fiscales y canales de comunicación.
            </p>
        </div>

        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('clientes.edit', $cliente->id) }}" class="btn btn-warning btn-sm rounded-3 px-3 shadow-sm d-flex align-items-center gap-2">
                <i class="fas fa-pen"></i>
                <span>Editar Cliente</span>
            </a>

            <a href="{{ route('clientes.index') }}" class="btn btn-outline-secondary btn-sm rounded-3 px-3 shadow-sm d-flex align-items-center gap-2">
                <i class="fas fa-arrow-left"></i>
                <span>Volver al Directorio</span>
            </a>
        </div>
    </div>

    <div class="row g-4">

        <!-- TARJETA DE PERFIL (COLUMNA IZQUIERDA) -->
        <div class="col-12 col-md-5 col-xl-4">
            <div class="profile-card">
                <div class="profile-card-header">
                    <div class="profile-avatar">
                        {{ $initials ?: 'CL' }}
                    </div>
                    <h4 class="fw-bold text-white mb-1">{{ $cliente->nombre }}</h4>
                    <span class="badge bg-white bg-opacity-25 text-white px-3 py-1 rounded-pill small">
                        Cliente Registrado #{{ $cliente->id }}
                    </span>
                </div>

                <div class="p-3">
                    <div class="d-grid gap-2 mb-3">
                        @if($cleanPhone)
                            <a href="https://wa.me/{{ $cleanPhone }}" target="_blank" class="btn btn-success btn-sm rounded-3 shadow-sm d-flex align-items-center justify-content-center gap-2">
                                <i class="fab fa-whatsapp fs-6"></i>
                                <span>Enviar WhatsApp</span>
                            </a>
                            <a href="tel:{{ $cleanPhone }}" class="btn btn-outline-primary btn-sm rounded-3 d-flex align-items-center justify-content-center gap-2">
                                <i class="fas fa-phone-alt"></i>
                                <span>Llamar por Teléfono</span>
                            </a>
                        @endif

                        @if($cliente->email)
                            <a href="mailto:{{ $cliente->email }}" class="btn btn-outline-info btn-sm rounded-3 d-flex align-items-center justify-content-center gap-2">
                                <i class="fas fa-envelope"></i>
                                <span>Enviar Correo Electrónico</span>
                            </a>
                        @endif

                        <button type="button" class="btn btn-light btn-sm border rounded-3 d-flex align-items-center justify-content-center gap-2 text-muted" onclick="copiarTexto('{{ $cliente->nombre }}\nNIT: {{ $cliente->nit ?? 'N/A' }}\nTel: {{ $cliente->telefono ?? 'N/A' }}\nEmail: {{ $cliente->email ?? 'N/A' }}', 'Datos copiados')">
                            <i class="far fa-copy"></i>
                            <span>Copiar Datos del Cliente</span>
                        </button>
                    </div>

                    <div class="p-3 rounded-3 bg-light border small text-muted">
                        <div class="d-flex justify-content-between mb-1">
                            <span>Registrado el:</span>
                            <span class="fw-semibold text-dark">{{ $cliente->created_at ? $cliente->created_at->format('d/m/Y H:i') : '-' }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>Última modificación:</span>
                            <span class="fw-semibold text-dark">{{ $cliente->updated_at ? $cliente->updated_at->format('d/m/Y H:i') : '-' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- INFORMACIÓN DETALLADA (COLUMNA DERECHA) -->
        <div class="col-12 col-md-7 col-xl-8">
            <div class="info-card">
                <h5 class="fw-bold text-dark mb-3 d-flex align-items-center gap-2">
                    <i class="fas fa-clipboard-list text-primary"></i> Información General
                </h5>

                <div class="row g-3 mb-4">
                    <div class="col-12 col-sm-6">
                        <div class="info-item">
                            <div class="text-muted small fw-semibold">Nombre Completo / Razón Social</div>
                            <div class="fw-bold text-dark fs-6 mt-1">{{ $cliente->nombre }}</div>
                        </div>
                    </div>

                    <div class="col-12 col-sm-6">
                        <div class="info-item">
                            <div class="text-muted small fw-semibold">NIT / Documento / CI</div>
                            <div class="fw-bold text-dark fs-6 mt-1">
                                @if($cliente->nit)
                                    <span class="badge bg-light text-dark border"><i class="fas fa-id-card text-muted me-1"></i>{{ $cliente->nit }}</span>
                                @else
                                    <span class="text-muted small fst-italic">No registrado</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-sm-6">
                        <div class="info-item">
                            <div class="text-muted small fw-semibold">Teléfono / Celular</div>
                            <div class="fw-bold text-dark fs-6 mt-1">
                                @if($cliente->telefono)
                                    <span class="badge bg-success bg-opacity-10 text-success border border-success-subtle"><i class="fas fa-phone-alt me-1"></i>{{ $cliente->telefono }}</span>
                                @else
                                    <span class="text-muted small fst-italic">No registrado</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-sm-6">
                        <div class="info-item">
                            <div class="text-muted small fw-semibold">Correo Electrónico</div>
                            <div class="fw-bold text-dark fs-6 mt-1">
                                @if($cliente->email)
                                    <a href="mailto:{{ $cliente->email }}" class="text-decoration-none text-primary"><i class="fas fa-envelope me-1 text-muted"></i>{{ $cliente->email }}</a>
                                @else
                                    <span class="text-muted small fst-italic">No registrado</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                    <form action="{{ route('clientes.destroy', $cliente->id) }}" method="POST" id="formDeleteShow">
                        @csrf
                        @method('DELETE')
                        <button type="button" class="btn btn-outline-danger btn-sm rounded-3" onclick="confirmarEliminarShow()">
                            <i class="fas fa-trash me-1"></i> Eliminar Cliente
                        </button>
                    </form>

                    <div class="d-flex gap-2">
                        <a href="{{ route('clientes.index') }}" class="btn btn-light btn-sm rounded-3 border">
                            Volver al listado
                        </a>
                        <a href="{{ route('clientes.edit', $cliente->id) }}" class="btn btn-primary btn-sm rounded-3">
                            <i class="fas fa-pen me-1"></i> Editar
                        </a>
                    </div>
                </div>
            </div>
        </div>

    </div>

</div>

<script>
function copiarTexto(texto, mensaje) {
    navigator.clipboard.writeText(texto).then(() => {
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 2000,
            timerProgressBar: true,
            iconColor: '#4f46e5'
        });
        Toast.fire({
            icon: 'success',
            title: mensaje || 'Copiado al portapapeles'
        });
    });
}

function confirmarEliminarShow() {
    Swal.fire({
        title: '¿Eliminar cliente?',
        html: '¿Estás seguro de que deseas eliminar permanentemente a este cliente del sistema?<br><span class="text-muted small">Esta acción no se puede deshacer.</span>',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#64748b',
        confirmButtonText: '<i class="fas fa-trash me-1"></i> Sí, eliminar',
        cancelButtonText: 'Cancelar',
        reverseButtons: true,
        customClass: {
            popup: 'rounded-4 shadow'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('formDeleteShow').submit();
        }
    });
}
</script>

@endsection