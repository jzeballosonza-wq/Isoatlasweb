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
        --cli-danger: #ef4444;
        --cli-purple: #8b5cf6;
        --cli-card-bg: #ffffff;
        --cli-border: #f1f5f9;
    }

    .clientes-container {
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

    .kpi-icon-purple {
        background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
        box-shadow: 0 6px 15px rgba(99, 102, 241, 0.25);
    }

    .kpi-icon-emerald {
        background: linear-gradient(135deg, #059669 0%, #10b981 100%);
        box-shadow: 0 6px 15px rgba(16, 185, 129, 0.25);
    }

    .kpi-icon-blue {
        background: linear-gradient(135deg, #0284c7 0%, #38bdf8 100%);
        box-shadow: 0 6px 15px rgba(2, 132, 199, 0.25);
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

    .filter-btn-shortcut {
        font-size: 0.78rem;
        padding: 4px 12px;
        border-radius: 20px;
        font-weight: 600;
        background: #e2e8f0;
        color: #475569;
        border: none;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        transition: all 0.15s ease;
    }

    .filter-btn-shortcut:hover,
    .filter-btn-shortcut.active {
        background: var(--cli-primary);
        color: #ffffff !important;
        box-shadow: 0 4px 10px rgba(79, 70, 229, 0.25);
    }

    /* MAIN TABLE CARD */
    .table-card {
        background: #ffffff;
        border-radius: 16px;
        border: 1px solid #f1f5f9;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
        overflow: hidden;
    }

    .table-header-custom {
        padding: 1.25rem;
        background: #ffffff;
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
    }

    .table-custom {
        margin-bottom: 0;
        vertical-align: middle;
    }

    .table-custom thead th {
        background: #f8fafc;
        color: #475569;
        font-weight: 700;
        font-size: 0.78rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        padding: 1rem 0.85rem;
        border-bottom: 1px solid #e2e8f0;
        border-top: none;
    }

    .table-custom tbody tr {
        transition: all 0.15s ease;
    }

    .table-custom tbody tr:hover {
        background-color: #f8fafc;
    }

    .table-custom tbody td {
        padding: 0.95rem 0.85rem;
        border-bottom: 1px solid #f1f5f9;
        font-size: 0.88rem;
        color: #334155;
    }

    /* CLIENT AVATAR */
    .client-avatar {
        width: 40px;
        height: 40px;
        border-radius: 12px;
        background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
        color: #ffffff;
        font-weight: 700;
        font-size: 0.95rem;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        box-shadow: 0 4px 10px rgba(79, 70, 229, 0.2);
    }

    .avatar-emerald { background: linear-gradient(135deg, #059669 0%, #10b981 100%); box-shadow: 0 4px 10px rgba(16, 185, 129, 0.2); }
    .avatar-blue { background: linear-gradient(135deg, #0284c7 0%, #38bdf8 100%); box-shadow: 0 4px 10px rgba(2, 132, 199, 0.2); }
    .avatar-amber { background: linear-gradient(135deg, #d97706 0%, #f59e0b 100%); box-shadow: 0 4px 10px rgba(245, 158, 11, 0.2); }
    .avatar-rose { background: linear-gradient(135deg, #e11d48 0%, #fb7185 100%); box-shadow: 0 4px 10px rgba(225, 29, 72, 0.2); }

    /* ACTION PILLS & BADGES */
    .nit-badge {
        font-family: monospace;
        font-weight: 700;
        font-size: 0.82rem;
        padding: 4px 8px;
        background: #f1f5f9;
        color: #334155;
        border: 1px solid #e2e8f0;
        border-radius: 6px;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }

    .btn-contact {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        font-size: 0.78rem;
        padding: 3px 8px;
        border-radius: 6px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.15s ease;
    }

    .btn-contact-whatsapp {
        background: #dcfce7;
        color: #15803d;
        border: 1px solid #bbf7d0;
    }

    .btn-contact-whatsapp:hover {
        background: #22c55e;
        color: #ffffff;
    }

    .btn-contact-email {
        background: #e0f2fe;
        color: #0369a1;
        border: 1px solid #bae6fd;
    }

    .btn-contact-email:hover {
        background: #0284c7;
        color: #ffffff;
    }

    .btn-action-icon {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: none;
        transition: all 0.15s ease;
        font-size: 0.85rem;
    }

    .btn-action-view {
        background: #eff6ff;
        color: #2563eb;
    }
    .btn-action-view:hover {
        background: #2563eb;
        color: #ffffff;
    }

    .btn-action-edit {
        background: #ecfdf5;
        color: #059669;
    }
    .btn-action-edit:hover {
        background: #059669;
        color: #ffffff;
    }

    .btn-action-delete {
        background: #fef2f2;
        color: #dc2626;
    }
    .btn-action-delete:hover {
        background: #dc2626;
        color: #ffffff;
    }

    /* GRADIENT BUTTONS */
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

    /* EMPTY STATE */
    .empty-state-box {
        padding: 3.5rem 1.5rem;
        text-align: center;
    }
    .empty-state-icon {
        width: 72px;
        height: 72px;
        border-radius: 50%;
        background: #f1f5f9;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.8rem;
        color: #94a3b8;
        margin: 0 auto 16px;
    }

    /* PRINT STYLES */
    @media print {
        .main-sidebar, .main-header, .filter-card, .kpi-section, .btn-action-top, .pagination-box, .action-btn-group, .btn-contact {
            display: none !important;
        }
        .content-wrapper {
            margin: 0 !important;
            padding: 0 !important;
            background: #fff !important;
        }
        .table-card {
            box-shadow: none !important;
            border: 1px solid #000 !important;
        }
    }
</style>

<div class="container-fluid clientes-container">

    <!-- TOP HEADER & ACTIONS -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1" style="font-size: 0.82rem;">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none text-muted"><i class="fas fa-home me-1"></i>Inicio</a></li>
                    <li class="breadcrumb-item active text-primary fw-semibold" aria-current="page">Clientes</li>
                </ol>
            </nav>
            <h2 class="fw-bold text-dark m-0 d-flex align-items-center gap-2" style="font-size: 1.55rem; letter-spacing: -0.02em;">
                <i class="fas fa-users text-primary"></i> Directorio y Gestión de Clientes
            </h2>
            <p class="text-muted small m-0 mt-1">
                Administra la cartera de clientes, datos fiscales, historial de contacto y canales de comunicación.
            </p>
        </div>

        <div class="d-flex flex-wrap gap-2 btn-action-top">
            <button type="button" class="btn btn-outline-success btn-sm rounded-3 px-3 shadow-sm d-flex align-items-center gap-2" onclick="exportarExcelClientes()">
                <i class="fas fa-file-excel"></i>
                <span>Exportar CSV</span>
            </button>

            <button type="button" class="btn btn-outline-secondary btn-sm rounded-3 px-3 shadow-sm d-flex align-items-center gap-2" onclick="window.print()">
                <i class="fas fa-print"></i>
                <span>Imprimir Listado</span>
            </button>

            <button type="button" class="btn btn-outline-primary btn-sm rounded-3 px-3 shadow-sm d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#modalCrearCliente">
                <i class="fas fa-bolt"></i>
                <span>Registro Rápido</span>
            </button>

            <a href="{{ route('clientes.create') }}" class="btn btn-gradient-primary btn-sm rounded-3 px-3 shadow-sm d-flex align-items-center gap-2">
                <i class="fas fa-user-plus"></i>
                <span>Nuevo Cliente</span>
            </a>
        </div>
    </div>

    <!-- STATS / KPI CARDS -->
    <div class="row g-3 mb-4 kpi-section">
        <!-- 1. TOTAL CLIENTES -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="kpi-card">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="kpi-label">Total Clientes</div>
                        <div class="kpi-value text-primary">
                            {{ number_format($totalClientes ?? 0) }}
                        </div>
                        <div class="kpi-subtext mt-1">
                            <i class="fas fa-check-circle text-success me-1"></i>En cartera activa
                        </div>
                    </div>
                    <div class="kpi-icon-box kpi-icon-purple">
                        <i class="fas fa-users"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- 2. CON TELÉFONO / WHATSAPP -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="kpi-card">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="kpi-label">Con Teléfono / WhatsApp</div>
                        <div class="kpi-value text-emerald">
                            {{ number_format($conTelefono ?? 0) }}
                        </div>
                        <div class="kpi-subtext mt-1">
                            <i class="fab fa-whatsapp text-success me-1"></i>Canal directo disponible
                        </div>
                    </div>
                    <div class="kpi-icon-box kpi-icon-emerald">
                        <i class="fas fa-phone-alt"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- 3. CON CORREO ELECTRÓNICO -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="kpi-card">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="kpi-label">Con Correo Electrónico</div>
                        <div class="kpi-value text-info">
                            {{ number_format($conEmail ?? 0) }}
                        </div>
                        <div class="kpi-subtext mt-1">
                            <i class="fas fa-envelope text-info me-1"></i>Para facturación digital
                        </div>
                    </div>
                    <div class="kpi-icon-box kpi-icon-blue">
                        <i class="fas fa-at"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- 4. NUEVOS ESTE MES -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="kpi-card">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="kpi-label">Nuevos Este Mes</div>
                        <div class="kpi-value text-warning">
                            +{{ number_format($nuevosEsteMes ?? 0) }}
                        </div>
                        <div class="kpi-subtext mt-1">
                            <i class="fas fa-calendar-check text-warning me-1"></i>Adquisición reciente
                        </div>
                    </div>
                    <div class="kpi-icon-box kpi-icon-amber">
                        <i class="fas fa-user-clock"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- FILTER & SEARCH PANEL -->
    <div class="filter-card">
        <div class="filter-header">
            <div class="d-flex align-items-center gap-2">
                <i class="fas fa-filter text-primary"></i>
                <span class="fw-bold text-dark" style="font-size: 0.9rem;">Filtros de Búsqueda</span>
            </div>
            <div class="d-flex flex-wrap gap-1">
                <a href="{{ route('clientes.index') }}" class="filter-btn-shortcut {{ !request('tipo_filtro') ? 'active' : '' }}">
                    Todos
                </a>
                <a href="{{ route('clientes.index', array_merge(request()->except('tipo_filtro'), ['tipo_filtro' => 'con_telefono'])) }}" class="filter-btn-shortcut {{ request('tipo_filtro') == 'con_telefono' ? 'active' : '' }}">
                    <i class="fas fa-phone-alt"></i> Con Teléfono
                </a>
                <a href="{{ route('clientes.index', array_merge(request()->except('tipo_filtro'), ['tipo_filtro' => 'con_email'])) }}" class="filter-btn-shortcut {{ request('tipo_filtro') == 'con_email' ? 'active' : '' }}">
                    <i class="fas fa-envelope"></i> Con Email
                </a>
                <a href="{{ route('clientes.index', array_merge(request()->except('tipo_filtro'), ['tipo_filtro' => 'con_nit'])) }}" class="filter-btn-shortcut {{ request('tipo_filtro') == 'con_nit' ? 'active' : '' }}">
                    <i class="fas fa-id-card"></i> Con NIT
                </a>
                <a href="{{ route('clientes.index', array_merge(request()->except('tipo_filtro'), ['tipo_filtro' => 'este_mes'])) }}" class="filter-btn-shortcut {{ request('tipo_filtro') == 'este_mes' ? 'active' : '' }}">
                    <i class="fas fa-calendar-alt"></i> Este Mes
                </a>
            </div>
        </div>

        <div class="p-3">
            <form action="{{ route('clientes.index') }}" method="GET" id="formFiltroClientes">
                @if(request('tipo_filtro'))
                    <input type="hidden" name="tipo_filtro" value="{{ request('tipo_filtro') }}">
                @endif

                <div class="row g-2 align-items-end">
                    <!-- BÚSQUEDA GENERAL -->
                    <div class="col-12 col-md-4">
                        <label class="form-label text-muted small fw-semibold mb-1">
                            <i class="fas fa-search me-1"></i>Buscar por Nombre / NIT / Teléfono / Email
                        </label>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-light border-end-0"><i class="fas fa-search text-muted"></i></span>
                            <input type="text"
                                   name="buscar"
                                   id="inputBuscarClientes"
                                   class="form-control border-start-0 ps-0"
                                   placeholder="Escribe para buscar..."
                                   value="{{ request('buscar') }}">
                            @if(request('buscar'))
                                <button class="btn btn-outline-secondary border-start-0" type="button" onclick="document.getElementById('inputBuscarClientes').value=''; document.getElementById('formFiltroClientes').submit();">
                                    <i class="fas fa-times"></i>
                                </button>
                            @endif
                        </div>
                    </div>

                    <!-- FECHA DESDE -->
                    <div class="col-6 col-md-2">
                        <label class="form-label text-muted small fw-semibold mb-1">
                            <i class="fas fa-calendar me-1"></i>Registrado Desde
                        </label>
                        <input type="date"
                               name="fecha_desde"
                               class="form-control form-control-sm"
                               value="{{ request('fecha_desde') }}">
                    </div>

                    <!-- FECHA HASTA -->
                    <div class="col-6 col-md-2">
                        <label class="form-label text-muted small fw-semibold mb-1">
                            <i class="fas fa-calendar me-1"></i>Registrado Hasta
                        </label>
                        <input type="date"
                               name="fecha_hasta"
                               class="form-control form-control-sm"
                               value="{{ request('fecha_hasta') }}">
                    </div>

                    <!-- ORDENAR POR -->
                    <div class="col-6 col-md-2">
                        <label class="form-label text-muted small fw-semibold mb-1">
                            <i class="fas fa-sort me-1"></i>Ordenar por
                        </label>
                        <select name="orden" class="form-select form-select-sm" onchange="document.getElementById('formFiltroClientes').submit();">
                            <option value="recientes" {{ request('orden') == 'recientes' ? 'selected' : '' }}>Más Recientes</option>
                            <option value="antiguos" {{ request('orden') == 'antiguos' ? 'selected' : '' }}>Más Antiguos</option>
                            <option value="nombre_asc" {{ request('orden') == 'nombre_asc' ? 'selected' : '' }}>Nombre (A - Z)</option>
                            <option value="nombre_desc" {{ request('orden') == 'nombre_desc' ? 'selected' : '' }}>Nombre (Z - A)</option>
                        </select>
                    </div>

                    <!-- BOTONES ACCIÓN FILTRO -->
                    <div class="col-6 col-md-2 d-flex gap-2">
                        <button type="submit" class="btn btn-primary btn-sm flex-grow-1 rounded-3 d-flex align-items-center justify-content-center gap-1">
                            <i class="fas fa-search"></i>
                            <span>Filtrar</span>
                        </button>
                        <a href="{{ route('clientes.index') }}" class="btn btn-light btn-sm rounded-3 border d-flex align-items-center justify-content-center" title="Limpiar Filtros">
                            <i class="fas fa-undo text-muted"></i>
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- MAIN CLIENTS TABLE CARD -->
    <div class="table-card">
        <div class="table-header-custom">
            <div>
                <h5 class="fw-bold text-dark m-0 d-flex align-items-center gap-2" style="font-size: 1.05rem;">
                    <i class="fas fa-address-book text-primary"></i> Clientes Registrados
                </h5>
                <p class="text-muted small m-0 mt-1">
                    Mostrando {{ $clientes->firstItem() ?? 0 }} a {{ $clientes->lastItem() ?? 0 }} de {{ $clientes->total() }} clientes encontrados
                </p>
            </div>

            <div class="d-flex align-items-center gap-2">
                <span class="badge bg-light text-dark border px-3 py-2 rounded-pill fw-semibold">
                    <i class="fas fa-database text-primary me-1"></i> {{ $clientes->total() }} Clientes en Total
                </span>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-custom align-middle" id="tablaClientes">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 60px;">N°</th>
                        <th>Cliente / Razón Social</th>
                        <th>NIT / Documento</th>
                        <th>Teléfono / WhatsApp</th>
                        <th>Correo Electrónico</th>
                        <th>Fecha Registro</th>
                        <th class="text-center" style="width: 140px;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $avatarClasses = ['avatar-purple', 'avatar-emerald', 'avatar-blue', 'avatar-amber', 'avatar-rose'];
                    @endphp

                    @forelse($clientes as $index => $cliente)
                        @php
                            // Generar iniciales del cliente
                            $words = explode(' ', trim($cliente->nombre));
                            $initials = '';
                            if (count($words) >= 2) {
                                $initials = mb_substr($words[0], 0, 1) . mb_substr($words[1], 0, 1);
                            } else {
                                $initials = mb_substr($cliente->nombre, 0, 2);
                            }
                            $initials = mb_strtoupper($initials);
                            $colorClass = $avatarClasses[$loop->index % count($avatarClasses)];
                            // Formatear teléfono para link de WhatsApp (limpiar espacios y caracteres no numéricos)
                            $cleanPhone = preg_replace('/[^0-9]/', '', $cliente->telefono);
                        @endphp

                        <tr id="cliente-row-{{ $cliente->id }}">
                            <td class="text-center">
                                <span class="badge bg-light text-secondary border fw-bold">
                                    {{ $clientes->firstItem() + $index }}
                                </span>
                            </td>

                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="client-avatar {{ $colorClass }}">
                                        {{ $initials ?: 'CL' }}
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark" style="font-size: 0.92rem;">
                                            {{ $cliente->nombre }}
                                        </div>
                                        <div class="text-muted small">
                                            <i class="fas fa-fingerprint me-1" style="font-size: 0.75rem;"></i>ID: #{{ $cliente->id }}
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <td>
                                @if($cliente->nit)
                                    <div class="d-inline-flex align-items-center gap-1">
                                        <span class="nit-badge">
                                            <i class="fas fa-id-card text-muted"></i>
                                            {{ $cliente->nit }}
                                        </span>
                                        <button type="button" class="btn btn-link btn-sm p-0 text-muted" title="Copiar NIT" onclick="copiarTexto('{{ $cliente->nit }}', 'NIT copiado al portapapeles')">
                                            <i class="far fa-copy" style="font-size: 0.8rem;"></i>
                                        </button>
                                    </div>
                                @else
                                    <span class="text-muted small fst-italic">
                                        <i class="fas fa-minus me-1"></i>Sin NIT
                                    </span>
                                @endif
                            </td>

                            <td>
                                @if($cliente->telefono)
                                    <div class="d-flex flex-column align-items-start gap-1">
                                        <div class="fw-semibold text-dark small">
                                            <i class="fas fa-phone text-muted me-1"></i>{{ $cliente->telefono }}
                                        </div>
                                        <div class="d-flex gap-1">
                                            @if($cleanPhone)
                                                <a href="https://wa.me/{{ $cleanPhone }}" target="_blank" class="btn-contact btn-contact-whatsapp" title="Enviar mensaje de WhatsApp">
                                                    <i class="fab fa-whatsapp"></i> WhatsApp
                                                </a>
                                            @endif
                                            <a href="tel:{{ $cleanPhone ?: $cliente->telefono }}" class="btn-contact btn-contact-email" title="Llamar">
                                                <i class="fas fa-phone-alt"></i> Llamar
                                            </a>
                                        </div>
                                    </div>
                                @else
                                    <span class="text-muted small fst-italic">
                                        <i class="fas fa-minus me-1"></i>Sin teléfono
                                    </span>
                                @endif
                            </td>

                            <td>
                                @if($cliente->email)
                                    <div class="d-flex align-items-center gap-2">
                                        <a href="mailto:{{ $cliente->email }}" class="text-decoration-none text-primary fw-medium small d-inline-flex align-items-center gap-1">
                                            <i class="fas fa-envelope text-muted"></i>
                                            {{ $cliente->email }}
                                        </a>
                                        <button type="button" class="btn btn-link btn-sm p-0 text-muted" title="Copiar Email" onclick="copiarTexto('{{ $cliente->email }}', 'Correo copiado al portapapeles')">
                                            <i class="far fa-copy" style="font-size: 0.8rem;"></i>
                                        </button>
                                    </div>
                                @else
                                    <span class="text-muted small fst-italic">
                                        <i class="fas fa-minus me-1"></i>Sin correo
                                    </span>
                                @endif
                            </td>

                            <td>
                                <div class="text-dark small fw-medium">
                                    <i class="far fa-calendar-alt text-muted me-1"></i>
                                    {{ $cliente->created_at ? $cliente->created_at->format('d/m/Y') : '-' }}
                                </div>
                                <div class="text-muted" style="font-size: 0.75rem;">
                                    <i class="far fa-clock me-1"></i>{{ $cliente->created_at ? $cliente->created_at->format('H:i') : '' }}
                                </div>
                            </td>

                            <td class="text-center">
                                <div class="d-inline-flex gap-1 action-btn-group">
                                    <!-- VER DETALLES MODAL -->
                                    <button type="button"
                                            class="btn-action-icon btn-action-view"
                                            title="Ver Detalle Rápido"
                                            onclick="abrirModalVerCliente({{ json_encode($cliente) }})">
                                        <i class="fas fa-eye"></i>
                                    </button>

                                    <!-- EDITAR MODAL -->
                                    <button type="button"
                                            class="btn-action-icon btn-action-edit"
                                            title="Edición Rápida"
                                            onclick="abrirModalEditarCliente({{ json_encode($cliente) }})">
                                        <i class="fas fa-pen"></i>
                                    </button>

                                    <!-- ELIMINAR -->
                                    <form action="{{ route('clientes.destroy', $cliente->id) }}"
                                          method="POST"
                                          class="d-inline form-eliminar-cliente"
                                          data-nombre="{{ $cliente->nombre }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button"
                                                class="btn-action-icon btn-action-delete btn-confirm-delete"
                                                title="Eliminar Cliente">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">
                                <div class="empty-state-box">
                                    <div class="empty-state-icon">
                                        <i class="fas fa-users-slash"></i>
                                    </div>
                                    <h5 class="fw-bold text-dark mb-1">No se encontraron clientes</h5>
                                    <p class="text-muted small mb-3">
                                        @if(request('buscar') || request('tipo_filtro') || request('fecha_desde') || request('fecha_hasta'))
                                            No hay resultados que coincidan con los criterios de búsqueda seleccionados.
                                        @else
                                            Aún no has registrado ningún cliente en el sistema. ¡Empieza creando el primero!
                                        @endif
                                    </p>
                                    <div class="d-flex justify-content-center gap-2">
                                        @if(request('buscar') || request('tipo_filtro') || request('fecha_desde') || request('fecha_hasta'))
                                            <a href="{{ route('clientes.index') }}" class="btn btn-outline-secondary btn-sm rounded-3">
                                                <i class="fas fa-undo me-1"></i> Limpiar Filtros
                                            </a>
                                        @endif
                                        <button type="button" class="btn btn-primary btn-sm rounded-3 px-3" data-bs-toggle="modal" data-bs-target="#modalCrearCliente">
                                            <i class="fas fa-plus-circle me-1"></i> Crear Primer Cliente
                                        </button>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- PAGINACIÓN -->
        @if($clientes->hasPages())
            <div class="p-3 border-top bg-light pagination-box d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div class="text-muted small">
                    Página {{ $clientes->currentPage() }} de {{ $clientes->lastPage() }}
                </div>
                <div>
                    {{ $clientes->links('pagination::bootstrap-5') }}
                </div>
            </div>
        @endif
    </div>

</div>

<!-- ============================================================== -->
<!-- MODAL 1: REGISTRO RÁPIDO DE CLIENTE                           -->
<!-- ============================================================== -->
<div class="modal fade" id="modalCrearCliente" tabindex="-1" aria-labelledby="modalCrearClienteLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header border-0 pb-0 pt-4 px-4">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-3 d-flex align-items-center justify-content-center text-white" style="width: 44px; height: 44px; background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);">
                        <i class="fas fa-user-plus fs-5"></i>
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold text-dark m-0" id="modalCrearClienteLabel">Registrar Nuevo Cliente</h5>
                        <p class="text-muted small m-0">Ingresa los datos para dar de alta al cliente</p>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-target="#modalCrearCliente" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form action="{{ route('clientes.store') }}" method="POST" id="formModalCrearCliente">
                @csrf
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <!-- NOMBRE -->
                        <div class="col-12">
                            <label class="form-label fw-bold small text-dark">
                                Nombre Completo o Razón Social <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fas fa-user text-muted"></i></span>
                                <input type="text"
                                       name="nombre"
                                       id="modalCrearNombre"
                                       class="form-control"
                                       placeholder="Ej: Juan Pérez / Empresa S.A."
                                       required
                                       onkeyup="actualizarPreviewModalCrear()">
                            </div>
                        </div>

                        <!-- NIT / CÓDIGO -->
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-bold small text-dark">
                                NIT / Documento / CI
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fas fa-id-card text-muted"></i></span>
                                <input type="text"
                                       name="nit"
                                       id="modalCrearNit"
                                       class="form-control"
                                       placeholder="Ej: 8492019012"
                                       onkeyup="actualizarPreviewModalCrear()">
                            </div>
                        </div>

                        <!-- TELÉFONO -->
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-bold small text-dark">
                                Teléfono / WhatsApp
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fas fa-phone text-muted"></i></span>
                                <input type="text"
                                       name="telefono"
                                       id="modalCrearTelefono"
                                       class="form-control"
                                       placeholder="Ej: 77889900"
                                       onkeyup="actualizarPreviewModalCrear()">
                            </div>
                        </div>

                        <!-- EMAIL -->
                        <div class="col-12">
                            <label class="form-label fw-bold small text-dark">
                                Correo Electrónico
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fas fa-envelope text-muted"></i></span>
                                <input type="email"
                                       name="email"
                                       id="modalCrearEmail"
                                       class="form-control"
                                       placeholder="Ej: cliente@correo.com"
                                       onkeyup="actualizarPreviewModalCrear()">
                            </div>
                        </div>

                        <!-- PREVIEW MINI CARD -->
                        <div class="col-12">
                            <div class="p-3 bg-light rounded-3 border d-flex align-items-center gap-3">
                                <div class="client-avatar avatar-purple" id="modalCrearAvatarPreview">
                                    --
                                </div>
                                <div class="overflow-hidden flex-grow-1">
                                    <div class="fw-bold text-dark text-truncate" id="modalCrearNombrePreview" style="font-size: 0.9rem;">
                                        Nombre del Cliente
                                    </div>
                                    <div class="text-muted small text-truncate" id="modalCrearSubPreview">
                                        NIT: Sin especificar • Tel: Sin especificar
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer border-0 pt-0 px-4 pb-4">
                    <button type="button" class="btn btn-light rounded-3 px-3" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-gradient-primary rounded-3 px-4 shadow-sm">
                        <i class="fas fa-save me-1"></i> Guardar Cliente
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ============================================================== -->
<!-- MODAL 2: VER DETALLE RÁPIDO DE CLIENTE                         -->
<!-- ============================================================== -->
<div class="modal fade" id="modalVerCliente" tabindex="-1" aria-labelledby="modalVerClienteLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header border-0 pb-0 pt-4 px-4">
                <h5 class="modal-title fw-bold text-dark m-0 d-flex align-items-center gap-2">
                    <i class="fas fa-id-badge text-primary"></i> Ficha del Cliente
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body p-4">
                <div class="text-center pb-3 border-bottom mb-3">
                    <div class="client-avatar mx-auto mb-2" id="verClienteAvatar" style="width: 60px; height: 60px; font-size: 1.35rem; border-radius: 18px;">
                        CL
                    </div>
                    <h5 class="fw-bold text-dark mb-1" id="verClienteNombre">-</h5>
                    <div class="badge bg-light text-secondary border px-3 py-1 rounded-pill" id="verClienteId">
                        ID: #0
                    </div>
                </div>

                <div class="row g-3">
                    <div class="col-6">
                        <div class="p-2 bg-light rounded-3 border">
                            <div class="text-muted small fw-semibold">NIT / Documento</div>
                            <div class="fw-bold text-dark mt-1 d-flex align-items-center justify-content-between" id="verClienteNit">
                                <span>-</span>
                            </div>
                        </div>
                    </div>

                    <div class="col-6">
                        <div class="p-2 bg-light rounded-3 border">
                            <div class="text-muted small fw-semibold">Fecha Registro</div>
                            <div class="fw-bold text-dark mt-1" id="verClienteFecha">
                                -
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="p-2 bg-light rounded-3 border">
                            <div class="text-muted small fw-semibold">Teléfono / WhatsApp</div>
                            <div class="d-flex align-items-center justify-content-between mt-1">
                                <span class="fw-bold text-dark" id="verClienteTelefono">-</span>
                                <div id="verClienteAccionesTelefono" class="d-flex gap-1"></div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="p-2 bg-light rounded-3 border">
                            <div class="text-muted small fw-semibold">Correo Electrónico</div>
                            <div class="d-flex align-items-center justify-content-between mt-1">
                                <span class="fw-bold text-dark text-truncate" id="verClienteEmail">-</span>
                                <div id="verClienteAccionesEmail"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer border-0 pt-0 px-4 pb-4 d-flex justify-content-between">
                <button type="button" class="btn btn-outline-secondary btn-sm rounded-3" onclick="copiarFichaClienteCompleta()">
                    <i class="far fa-copy me-1"></i> Copiar Datos
                </button>

                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-light btn-sm rounded-3" data-bs-dismiss="modal">Cerrar</button>
                    <a href="#" id="verClienteEnlaceEditar" class="btn btn-primary btn-sm rounded-3">
                        <i class="fas fa-pen me-1"></i> Editar Completo
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ============================================================== -->
<!-- MODAL 3: EDICIÓN RÁPIDA DE CLIENTE                             -->
<!-- ============================================================== -->
<div class="modal fade" id="modalEditarCliente" tabindex="-1" aria-labelledby="modalEditarClienteLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header border-0 pb-0 pt-4 px-4">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-3 d-flex align-items-center justify-content-center text-white" style="width: 44px; height: 44px; background: linear-gradient(135deg, #059669 0%, #10b981 100%);">
                        <i class="fas fa-user-edit fs-5"></i>
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold text-dark m-0" id="modalEditarClienteLabel">Editar Cliente</h5>
                        <p class="text-muted small m-0">Actualiza la información del cliente</p>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form action="" method="POST" id="formModalEditarCliente">
                @csrf
                @method('PUT')
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <!-- NOMBRE -->
                        <div class="col-12">
                            <label class="form-label fw-bold small text-dark">
                                Nombre Completo o Razón Social <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fas fa-user text-muted"></i></span>
                                <input type="text"
                                       name="nombre"
                                       id="modalEditarNombre"
                                       class="form-control"
                                       required>
                            </div>
                        </div>

                        <!-- NIT / CÓDIGO -->
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-bold small text-dark">
                                NIT / Documento / CI
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fas fa-id-card text-muted"></i></span>
                                <input type="text"
                                       name="nit"
                                       id="modalEditarNit"
                                       class="form-control">
                            </div>
                        </div>

                        <!-- TELÉFONO -->
                        <div class="col-12 col-md-6">
                            <label class="form-label fw-bold small text-dark">
                                Teléfono / WhatsApp
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fas fa-phone text-muted"></i></span>
                                <input type="text"
                                       name="telefono"
                                       id="modalEditarTelefono"
                                       class="form-control">
                            </div>
                        </div>

                        <!-- EMAIL -->
                        <div class="col-12">
                            <label class="form-label fw-bold small text-dark">
                                Correo Electrónico
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fas fa-envelope text-muted"></i></span>
                                <input type="email"
                                       name="email"
                                       id="modalEditarEmail"
                                       class="form-control">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer border-0 pt-0 px-4 pb-4">
                    <button type="button" class="btn btn-light rounded-3 px-3" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success rounded-3 px-4 shadow-sm" style="background: linear-gradient(135deg, #059669 0%, #10b981 100%); border: none;">
                        <i class="fas fa-save me-1"></i> Actualizar Cambios
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ============================================================== -->
<!-- JAVASCRIPT & INTERACTIVIDAD                                    -->
<!-- ============================================================== -->
<script>
let clienteSeleccionadoActual = null;

// 1. Live Preview en Modal Crear
function actualizarPreviewModalCrear() {
    const nombre = document.getElementById('modalCrearNombre').value.trim();
    const nit = document.getElementById('modalCrearNit').value.trim();
    const telefono = document.getElementById('modalCrearTelefono').value.trim();

    const avatarEl = document.getElementById('modalCrearAvatarPreview');
    const nombreEl = document.getElementById('modalCrearNombrePreview');
    const subEl = document.getElementById('modalCrearSubPreview');

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

    subEl.innerText = `NIT: ${nit || 'Sin especificar'} • Tel: ${telefono || 'Sin especificar'}`;
}

// 2. Abrir Modal Ver Cliente
function abrirModalVerCliente(cliente) {
    clienteSeleccionadoActual = cliente;

    // Iniciales
    const words = cliente.nombre.trim().split(' ').filter(w => w.length > 0);
    let inits = '';
    if (words.length >= 2) {
        inits = words[0][0] + words[1][0];
    } else {
        inits = cliente.nombre.substring(0, 2);
    }

    document.getElementById('verClienteAvatar').innerText = inits.toUpperCase();
    document.getElementById('verClienteNombre').innerText = cliente.nombre;
    document.getElementById('verClienteId').innerText = `ID: #${cliente.id}`;

    // NIT
    if (cliente.nit) {
        document.getElementById('verClienteNit').innerHTML = `
            <span>${cliente.nit}</span>
            <button type="button" class="btn btn-link btn-sm p-0 text-muted" onclick="copiarTexto('${cliente.nit}', 'NIT copiado')">
                <i class="far fa-copy"></i>
            </button>
        `;
    } else {
        document.getElementById('verClienteNit').innerHTML = '<span class="text-muted fst-italic">Sin NIT registrado</span>';
    }

    // Fecha
    const fecha = cliente.created_at ? new Date(cliente.created_at).toLocaleDateString('es-ES', { day: '2-digit', month: '2-digit', year: 'numeric' }) : 'No disponible';
    document.getElementById('verClienteFecha').innerText = fecha;

    // Teléfono
    const cleanPhone = cliente.telefono ? cliente.telefono.replace(/[^0-9]/g, '') : '';
    if (cliente.telefono) {
        document.getElementById('verClienteTelefono').innerText = cliente.telefono;
        let btns = '';
        if (cleanPhone) {
            btns += `<a href="https://wa.me/${cleanPhone}" target="_blank" class="btn btn-sm btn-success py-0 px-2 rounded-2" title="WhatsApp"><i class="fab fa-whatsapp"></i></a>`;
        }
        btns += `<a href="tel:${cleanPhone || cliente.telefono}" class="btn btn-sm btn-outline-primary py-0 px-2 rounded-2" title="Llamar"><i class="fas fa-phone-alt"></i></a>`;
        document.getElementById('verClienteAccionesTelefono').innerHTML = btns;
    } else {
        document.getElementById('verClienteTelefono').innerText = 'Sin teléfono';
        document.getElementById('verClienteAccionesTelefono').innerHTML = '';
    }

    // Email
    if (cliente.email) {
        document.getElementById('verClienteEmail').innerText = cliente.email;
        document.getElementById('verClienteAccionesEmail').innerHTML = `
            <a href="mailto:${cliente.email}" class="btn btn-sm btn-outline-info py-0 px-2 rounded-2" title="Enviar Correo">
                <i class="fas fa-envelope"></i>
            </a>
        `;
    } else {
        document.getElementById('verClienteEmail').innerText = 'Sin correo registrado';
        document.getElementById('verClienteAccionesEmail').innerHTML = '';
    }

    // Enlace editar
    document.getElementById('verClienteEnlaceEditar').href = `/clientes/${cliente.id}/edit`;

    const modal = new bootstrap.Modal(document.getElementById('modalVerCliente'));
    modal.show();
}

// 3. Abrir Modal Editar Cliente
function abrirModalEditarCliente(cliente) {
    document.getElementById('formModalEditarCliente').action = `/clientes/${cliente.id}`;
    document.getElementById('modalEditarNombre').value = cliente.nombre || '';
    document.getElementById('modalEditarNit').value = cliente.nit || '';
    document.getElementById('modalEditarTelefono').value = cliente.telefono || '';
    document.getElementById('modalEditarEmail').value = cliente.email || '';

    const modal = new bootstrap.Modal(document.getElementById('modalEditarCliente'));
    modal.show();
}

// 4. Copiar texto genérico con SweetAlert Toast
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

// 5. Copiar toda la ficha del cliente
function copiarFichaClienteCompleta() {
    if (!clienteSeleccionadoActual) return;
    const c = clienteSeleccionadoActual;
    const info = `FICHA DE CLIENTE:
Nombre: ${c.nombre}
NIT/Doc: ${c.nit || 'N/A'}
Teléfono: ${c.telefono || 'N/A'}
Email: ${c.email || 'N/A'}`;

    copiarTexto(info, '¡Ficha del cliente copiada!');
}

// 6. Confirmación de eliminación con SweetAlert2
document.addEventListener('DOMContentLoaded', function() {
    const deleteButtons = document.querySelectorAll('.btn-confirm-delete');
    deleteButtons.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const form = this.closest('form');
            const nombre = form.getAttribute('data-nombre') || 'este cliente';

            Swal.fire({
                title: '¿Eliminar cliente?',
                html: `¿Estás seguro de que deseas eliminar a <b>"${nombre}"</b>?<br><span class="text-muted small">Esta acción no se puede deshacer.</span>`,
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
                    form.submit();
                }
            });
        });
    });
});

// 7. Exportar tabla a CSV compatible con Excel (UTF-8 con BOM)
function exportarExcelClientes() {
    const table = document.getElementById('tablaClientes');
    if (!table) return;

    let csv = '\uFEFF'; // BOM para soportar tildes y caracteres especiales en Excel
    csv += 'N°,Cliente,NIT / Documento,Teléfono,Correo Electrónico,Fecha Registro\n';

    const rows = table.querySelectorAll('tbody tr');
    rows.forEach((row, index) => {
        // Ignorar fila vacía
        if (row.querySelector('.empty-state-box')) return;

        const cols = row.querySelectorAll('td');
        if (cols.length >= 6) {
            const num = (index + 1);
            const nombre = `"${cols[1].querySelector('.fw-bold')?.innerText.replace(/"/g, '""') || ''}"`;
            const nit = `"${cols[2].innerText.replace('Sin NIT', '').trim().replace(/"/g, '""')}"`;
            const tel = `"${cols[3].querySelector('.fw-semibold')?.innerText.trim().replace(/"/g, '""') || ''}"`;
            const email = `"${cols[4].querySelector('a')?.innerText.trim().replace(/"/g, '""') || ''}"`;
            const fecha = `"${cols[5].querySelector('.text-dark')?.innerText.trim().replace(/"/g, '""') || ''}"`;

            csv += `${num},${nombre},${nit},${tel},${email},${fecha}\n`;
        }
    });

    const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
    const url = URL.createObjectURL(blob);
    const link = document.createElement('a');
    link.setAttribute('href', url);
    link.setAttribute('download', `Directorio_Clientes_${new Date().toISOString().slice(0, 10)}.csv`);
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}
</script>

@endsection