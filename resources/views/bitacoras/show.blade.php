@extends('dashboard')

@section('content')
<div class="container py-4">

    <!-- BREADCRUMB Y RETORNO -->
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1" style="font-size: 0.8rem;">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none text-muted">Panel</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('bitacoras.index') }}" class="text-decoration-none text-muted">Bitácora</a></li>
                    <li class="breadcrumb-item active text-primary fw-bold" aria-current="page">Registro #{{ $bitacora->id }}</li>
                </ol>
            </nav>
            <h1 class="h3 fw-bold text-dark mb-0">
                Ficha de Auditoría #{{ $bitacora->id }}
            </h1>
        </div>

        <div class="d-flex align-items-center gap-2">
            <button type="button" onclick="window.print()" class="btn btn-outline-secondary rounded-pill px-3 py-2 fw-semibold d-inline-flex align-items-center gap-2" style="font-size: 0.85rem;">
                <i class="fas fa-print"></i>
                <span>Imprimir Ficha</span>
            </button>
            <a href="{{ route('bitacoras.index') }}" class="btn btn-secondary rounded-pill px-4 py-2 fw-semibold d-inline-flex align-items-center gap-2" style="font-size: 0.85rem;">
                <i class="fas fa-arrow-left"></i>
                <span>Volver al Listado</span>
            </a>
        </div>
    </div>

    <!-- TARJETA PRINCIPAL DE AUDITORÍA -->
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4" style="background: #ffffff; border: 1px solid #e2e8f0 !important;">
        
        @php
            $badge = $bitacora->badge_info;
            $modInfo = $bitacora->modulo_info;
        @endphp

        <!-- HEADER CON ESTILO ELEGANTE -->
        <div class="card-header text-white py-4 px-4 px-md-5" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%) !important;">
            <div class="row align-items-center g-3">
                <div class="col-md-8">
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <span class="badge {{ $badge['badge_class'] }} px-3 py-1.5 rounded-pill fw-bold text-uppercase d-inline-flex align-items-center gap-1.5" style="font-size: 0.78rem;">
                            <i class="{{ $badge['icon'] }}"></i>
                            <span>{{ $bitacora->accion }}</span>
                        </span>
                        <span class="badge bg-light text-dark px-3 py-1.5 rounded-pill fw-bold d-inline-flex align-items-center gap-1.5" style="font-size: 0.78rem;">
                            <i class="{{ $modInfo['icon'] }}" style="color: {{ $modInfo['color'] }};"></i>
                            <span>Módulo {{ $bitacora->modulo }}</span>
                        </span>
                    </div>
                    <h2 class="h4 fw-bold text-white mb-1">
                        Evento de Auditoría: {{ $bitacora->accion }} en {{ $bitacora->modulo }}
                    </h2>
                    <small class="text-white-50">
                        Registrado el {{ $bitacora->created_at ? $bitacora->created_at->isoFormat('LLLL') : '-' }}
                    </small>
                </div>

                <div class="col-md-4 text-md-end">
                    <div class="d-inline-block p-3 rounded-4" style="background: rgba(255, 255, 255, 0.08); border: 1px solid rgba(255, 255, 255, 0.15);">
                        <span class="text-white-50 text-uppercase fw-bold d-block" style="font-size: 0.7rem;">Tiempo Transcurrido</span>
                        <div class="text-warning fw-bold fs-5 mt-0.5">
                            {{ $bitacora->tiempo_relativo }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- CUERPO DE LA FICHA -->
        <div class="card-body p-4 p-md-5">

            <!-- FILA 1: OPERADOR Y METADATOS TÉCNICOS -->
            <div class="row g-4 mb-4">
                
                <!-- Responsable -->
                <div class="col-md-6">
                    <div class="p-4 rounded-4 h-100" style="background: #f8fafc; border: 1px solid #e2e8f0;">
                        <span class="text-muted text-uppercase fw-bold d-block mb-3" style="font-size: 0.72rem; letter-spacing: 0.05em;">
                            <i class="fas fa-user-shield me-1 text-primary"></i> Operador Responsable
                        </span>

                        @if($bitacora->usuario)
                            <div class="d-flex align-items-center gap-3">
                                <div style="width: 54px; height: 54px; border-radius: 14px; background: {{ $bitacora->usuario->avatar_gradient }}; color: #ffffff; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 1.25rem; flex-shrink: 0; box-shadow: 0 4px 12px rgba(0,0,0,0.12);">
                                    {{ $bitacora->usuario->iniciales }}
                                </div>
                                <div>
                                    <h5 class="fw-bold text-dark mb-0">{{ $bitacora->usuario->name }}</h5>
                                    <p class="text-muted mb-1" style="font-size: 0.85rem;">{{ $bitacora->usuario->email }}</p>
                                    <span class="badge bg-primary text-white rounded-pill px-2.5 py-0.5" style="font-size: 0.72rem;">
                                        {{ $bitacora->usuario->rol->nombre ?? 'Usuario Activo' }}
                                    </span>
                                </div>
                            </div>
                        @else
                            <div class="d-flex align-items-center gap-3">
                                <div style="width: 54px; height: 54px; border-radius: 14px; background: #64748b; color: #ffffff; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 1.25rem; flex-shrink: 0;">
                                    <i class="fas fa-robot"></i>
                                </div>
                                <div>
                                    <h5 class="fw-bold text-secondary mb-0">Sistema Automático</h5>
                                    <p class="text-muted mb-1" style="font-size: 0.85rem;">Proceso o tarea programada interna</p>
                                    <span class="badge bg-secondary rounded-pill px-2.5 py-0.5" style="font-size: 0.72rem;">Sistema</span>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Detalles Técnicos y Red -->
                <div class="col-md-6">
                    <div class="p-4 rounded-4 h-100" style="background: #f8fafc; border: 1px solid #e2e8f0;">
                        <span class="text-muted text-uppercase fw-bold d-block mb-3" style="font-size: 0.72rem; letter-spacing: 0.05em;">
                            <i class="fas fa-network-wired me-1 text-info"></i> Información de Red y Tiempo
                        </span>

                        <div class="d-flex flex-column gap-2.5" style="font-size: 0.88rem;">
                            <div class="d-flex justify-content-between align-items-center py-1 border-bottom border-light-subtle">
                                <span class="text-muted">Dirección IP de Origen:</span>
                                <span class="font-monospace fw-bold text-dark px-2 py-0.5 bg-white rounded border">
                                    {{ $bitacora->ip ?: '127.0.0.1 (Localhost)' }}
                                </span>
                            </div>

                            <div class="d-flex justify-content-between align-items-center py-1 border-bottom border-light-subtle">
                                <span class="text-muted">Fecha y Hora Exacta:</span>
                                <span class="fw-bold text-dark">
                                    {{ $bitacora->fecha_formateada }}
                                </span>
                            </div>

                            <div class="d-flex justify-content-between align-items-center py-1">
                                <span class="text-muted">Identificador Único:</span>
                                <span class="badge bg-secondary-subtle text-secondary rounded-pill px-2.5">
                                    ID Registro #{{ $bitacora->id }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- FILA 2: DESCRIPCIÓN COMPLETA DEL EVENTO -->
            <div class="mb-4">
                <div class="p-4 rounded-4" style="background: #ffffff; border: 1px solid #e2e8f0; box-shadow: 0 2px 8px rgba(0,0,0,0.02);">
                    <span class="text-muted text-uppercase fw-bold d-block mb-2" style="font-size: 0.75rem; letter-spacing: 0.05em;">
                        <i class="fas fa-file-alt me-1 text-primary"></i> Descripción Detallada de la Operación
                    </span>

                    <div class="p-3.5 rounded-3 bg-light border text-dark fw-medium" style="font-size: 0.95rem; line-height: 1.6; white-space: pre-wrap;">
                        {{ $bitacora->descripcion ?: 'No se registraron comentarios específicos para esta transacción.' }}
                    </div>
                </div>
            </div>

            <!-- BOTONES DE ACCIÓN RÁPIDA -->
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 pt-3 border-top">
                <div class="d-flex gap-2">
                    <a href="{{ route('bitacoras.index', ['modulo' => $bitacora->modulo]) }}" class="btn btn-outline-primary btn-sm rounded-pill px-3">
                        <i class="fas fa-search me-1"></i> Ver más eventos de {{ $bitacora->modulo }}
                    </a>
                    @if($bitacora->user_id)
                    <a href="{{ route('bitacoras.index', ['user_id' => $bitacora->user_id]) }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
                        <i class="fas fa-user me-1"></i> Ver más eventos de este usuario
                    </a>
                    @endif
                </div>

                <a href="{{ route('bitacoras.index') }}" class="btn btn-primary rounded-pill px-4 py-2 fw-bold" style="background: var(--primary-gradient); border: none;">
                    <i class="fas fa-list me-1"></i> Volver a la Bitácora
                </a>
            </div>

        </div>

    </div>

</div>

<!-- ESTILOS DE IMPRESIÓN -->
<style>
@media print {
    .main-sidebar, .main-header, .navbar, .btn, .breadcrumb, nav {
        display: none !important;
    }
    .content-wrapper, body {
        background: #ffffff !important;
        margin: 0 !important;
        padding: 0 !important;
    }
    .card {
        box-shadow: none !important;
        border: 1px solid #ccc !important;
    }
}
</style>
@endsection