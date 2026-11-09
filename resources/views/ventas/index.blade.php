@extends('dashboard')

@section('content')

<style>
    /* VARIABLES & DESIGN TOKENS */
    :root {
        --vnt-primary: #4f46e5;
        --vnt-primary-dark: #3730a3;
        --vnt-success: #10b981;
        --vnt-info: #0284c7;
        --vnt-warning: #f59e0b;
        --vnt-danger: #ef4444;
        --vnt-dark: #0f172a;
        --vnt-card-bg: #ffffff;
        --vnt-border: #e2e8f0;
    }

    .ventas-container {
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
        font-size: 0.75rem;
        padding: 3px 10px;
        border-radius: 20px;
        font-weight: 600;
        background: #e2e8f0;
        color: #475569;
        border: none;
        transition: all 0.15s ease;
    }

    .filter-btn-shortcut:hover,
    .filter-btn-shortcut.active {
        background: #4f46e5;
        color: #ffffff;
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
        padding: 1rem 0.85rem;
        border-bottom: 1px solid #f1f5f9;
        font-size: 0.88rem;
        color: #334155;
    }

    /* TICKET ID BADGE */
    .ticket-badge {
        font-family: monospace;
        font-weight: 700;
        font-size: 0.82rem;
        padding: 5px 9px;
        background: #ede9fe;
        color: #5b21b6;
        border: 1px solid #ddd6fe;
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        cursor: pointer;
        transition: all 0.15s ease;
    }

    .ticket-badge:hover {
        background: #ddd6fe;
        color: #4c1d95;
    }

    /* PRODUCT PILL */
    .product-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: #f1f5f9;
        border: 1px solid #e2e8f0;
        border-radius: 6px;
        padding: 3px 8px;
        font-size: 0.78rem;
        color: #334155;
        font-weight: 500;
        margin-bottom: 4px;
        max-width: 100%;
    }

    .product-pill .badge-qty {
        background: #4f46e5;
        color: #ffffff;
        border-radius: 4px;
        padding: 1px 5px;
        font-size: 0.7rem;
        font-weight: 700;
    }

    .product-pill-more {
        cursor: pointer;
        background: #e0e7ff;
        color: #4338ca;
        border: 1px solid #c7d2fe;
        border-radius: 6px;
        padding: 3px 8px;
        font-size: 0.75rem;
        font-weight: 700;
        display: inline-block;
        margin-bottom: 4px;
    }

    .product-pill-more:hover {
        background: #c7d2fe;
    }

    /* TOTAL BADGE */
    .total-badge {
        font-weight: 800;
        font-size: 0.95rem;
        color: #059669;
        background: #ecfdf5;
        border: 1px solid #a7f3d0;
        border-radius: 8px;
        padding: 5px 10px;
        display: inline-block;
        letter-spacing: -0.01em;
    }

    /* ACTION BUTTONS */
    .action-btn-group .btn {
        width: 32px;
        height: 32px;
        padding: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        font-size: 0.85rem;
        transition: all 0.15s ease;
    }

    .action-btn-group .btn:hover {
        transform: translateY(-2px);
    }

    /* BUTTONS PRIMARY STYLES */
    .btn-gradient-primary {
        background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
        border: none;
        color: #ffffff;
        font-weight: 600;
        box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3);
        transition: all 0.2s ease;
    }

    .btn-gradient-primary:hover {
        background: linear-gradient(135deg, #4338ca 0%, #6d28d9 100%);
        color: #ffffff;
        box-shadow: 0 6px 18px rgba(79, 70, 229, 0.4);
        transform: translateY(-1px);
    }

    /* MODAL TICKET STYLES */
    .modal-ticket {
        background: #ffffff;
        border-radius: 16px;
        border: none;
        overflow: hidden;
    }

    .ticket-header-box {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
        color: #ffffff;
        padding: 20px;
        text-align: center;
        position: relative;
    }

    .ticket-header-title {
        font-size: 1.25rem;
        font-weight: 800;
        letter-spacing: -0.02em;
        margin: 0;
    }

    .ticket-header-sub {
        font-size: 0.82rem;
        color: #94a3b8;
        margin-top: 3px;
    }

    .ticket-info-grid {
        background: #f8fafc;
        border: 1px dashed #cbd5e1;
        border-radius: 12px;
        padding: 12px 16px;
        margin: 16px 0;
    }

    .ticket-total-display {
        background: #ecfdf5;
        border: 1px solid #a7f3d0;
        border-radius: 12px;
        padding: 14px;
        text-align: center;
        margin-top: 15px;
    }

    /* EMPTY STATE */
    .empty-state-box {
        text-align: center;
        padding: 60px 20px;
    }

    .empty-state-icon {
        width: 70px;
        height: 70px;
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
        .main-sidebar, .main-header, .filter-card, .kpi-section, .btn-action-top, .pagination-box, .action-btn-group, .product-pill-more {
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

<div class="container-fluid ventas-container">

    <!-- TOP HEADER & ACTIONS -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1" style="font-size: 0.82rem;">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none text-muted"><i class="fas fa-home me-1"></i>Inicio</a></li>
                    <li class="breadcrumb-item active text-primary fw-semibold" aria-current="page">Ventas</li>
                </ol>
            </nav>
            <div class="d-flex align-items-center flex-wrap gap-2">
                <h2 class="fw-bold text-dark m-0 d-flex align-items-center gap-2" style="font-size: 1.55rem; letter-spacing: -0.02em;">
                    <i class="fas fa-receipt text-primary"></i> Gestión de Ventas
                </h2>
                @if(isset($cajaAbierta) && $cajaAbierta)
                    <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-1.5 rounded-pill fw-semibold d-inline-flex align-items-center gap-1.5">
                        <i class="fas fa-circle text-success animate-pulse" style="font-size: 0.5rem;"></i> Caja Activa: {{ $cajaAbierta->caja_nombre }}
                    </span>
                @else
                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-3 py-1.5 rounded-pill fw-semibold d-inline-flex align-items-center gap-1.5">
                        <i class="fas fa-lock text-danger"></i> Caja Cerrada
                    </span>
                @endif
            </div>
            <p class="text-muted small m-0 mt-1">
                Consulta, filtra y gestiona los comprobantes y transacciones de venta registradas.
            </p>
        </div>

        <div class="d-flex flex-wrap gap-2 btn-action-top">
            <button type="button" class="btn btn-outline-success btn-sm rounded-3 px-3 shadow-sm d-flex align-items-center gap-2" onclick="exportarExcel()">
                <i class="fas fa-file-excel"></i>
                <span>Exportar CSV</span>
            </button>

            <button type="button" class="btn btn-outline-secondary btn-sm rounded-3 px-3 shadow-sm d-flex align-items-center gap-2" onclick="window.print()">
                <i class="fas fa-print"></i>
                <span>Imprimir Listado</span>
            </button>

            <a href="{{ route('ventas.create') }}" class="btn btn-gradient-primary btn-sm rounded-3 px-3 shadow-sm d-flex align-items-center gap-2">
                <i class="fas fa-plus-circle"></i>
                <span>Nueva Venta (POS)</span>
            </a>

            @if(isset($cajaAbierta) && $cajaAbierta)
                <a href="{{ route('caja.index') }}" class="btn btn-outline-danger btn-sm rounded-3 px-3 shadow-sm d-flex align-items-center gap-2 fw-semibold" title="Ir al módulo de Arqueo y Cierre de Caja">
                    <i class="fas fa-vault"></i>
                    <span>Cerrar / Arqueo Caja</span>
                </a>
            @else
                <a href="{{ route('caja.index') }}" class="btn btn-outline-secondary btn-sm rounded-3 px-3 shadow-sm d-flex align-items-center gap-2" title="Ir al módulo de Caja">
                    <i class="fas fa-door-open text-success"></i>
                    <span>Aperturar Caja</span>
                </a>
            @endif
        </div>
    </div>

    <!-- STATS / KPI CARDS -->
    <div class="row g-3 mb-4 kpi-section">
        <!-- 1. TOTAL RECAUDADO -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="kpi-card">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="kpi-label">Ingresos Totales</div>
                        <div class="kpi-value text-emerald">
                            Bs. {{ number_format($totalIngresos ?? 0, 2) }}
                        </div>
                        <div class="kpi-subtext mt-1">
                            <i class="fas fa-check-circle text-success me-1"></i>{{ $totalVentas ?? 0 }} ventas registradas
                        </div>
                    </div>
                    <div class="kpi-icon-box kpi-icon-emerald">
                        <i class="fas fa-wallet"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- 2. VENTAS DE HOY -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="kpi-card">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="kpi-label">Ventas de Hoy</div>
                        <div class="kpi-value text-primary">
                            Bs. {{ number_format($ingresosHoy ?? 0, 2) }}
                        </div>
                        <div class="kpi-subtext mt-1">
                            <i class="fas fa-calendar-day text-primary me-1"></i>{{ $ventasHoy ?? 0 }} transacciones hoy
                        </div>
                    </div>
                    <div class="kpi-icon-box kpi-icon-blue">
                        <i class="fas fa-bolt"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- 3. VENTAS DEL MES -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="kpi-card">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="kpi-label">Ventas del Mes</div>
                        <div class="kpi-value text-purple">
                            Bs. {{ number_format($ingresosMes ?? 0, 2) }}
                        </div>
                        <div class="kpi-subtext mt-1">
                            <i class="fas fa-chart-line text-purple me-1"></i>{{ $ventasMes ?? 0 }} en {{ now()->translatedFormat('F') }}
                        </div>
                    </div>
                    <div class="kpi-icon-box kpi-icon-purple">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- 4. TICKET PROMEDIO -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="kpi-card">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="kpi-label">Ticket Promedio</div>
                        <div class="kpi-value text-amber">
                            Bs. {{ number_format($ticketPromedio ?? 0, 2) }}
                        </div>
                        <div class="kpi-subtext mt-1">
                            <i class="fas fa-receipt text-warning me-1"></i>Monto medio por venta
                        </div>
                    </div>
                    <div class="kpi-icon-box kpi-icon-amber">
                        <i class="fas fa-tags"></i>
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
                <span class="fw-bold text-dark" style="font-size: 0.92rem;">Filtros y Búsqueda Avanzada</span>
                @if(request()->anyFilled(['buscar', 'fecha_desde', 'fecha_hasta', 'monto_min', 'monto_max', 'orden']))
                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill ms-1 px-2 py-1" style="font-size: 0.72rem;">
                        <i class="fas fa-check me-1"></i>Filtros Activos
                    </span>
                @endif
            </div>

            <!-- SHORTCUT BUTTONS FOR DATES -->
            <div class="d-none d-md-flex align-items-center gap-1">
                <span class="text-muted small me-1">Rápido:</span>
                <button type="button" class="filter-btn-shortcut" onclick="setDateRange('hoy')">Hoy</button>
                <button type="button" class="filter-btn-shortcut" onclick="setDateRange('semana')">Últimos 7 días</button>
                <button type="button" class="filter-btn-shortcut" onclick="setDateRange('mes')">Este Mes</button>
                <button type="button" class="filter-btn-shortcut" onclick="setDateRange('limpiar')">Todo</button>
            </div>
        </div>

        <div class="p-3 bg-white">
            <form action="{{ route('ventas.index') }}" method="GET" id="filterForm">
                <div class="row g-3 align-items-end">
                    <!-- BUSCADOR -->
                    <div class="col-12 col-md-4 col-lg-3">
                        <label class="form-label small fw-bold text-secondary mb-1">
                            <i class="fas fa-search me-1"></i>Buscador
                        </label>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-light border-end-0 text-muted">
                                <i class="fas fa-search"></i>
                            </span>
                            <input type="text"
                                   name="buscar"
                                   id="buscarGeneral"
                                   class="form-control border-start-0 ps-0"
                                   placeholder="N° Venta, NIT, producto..."
                                   value="{{ request('buscar') }}">
                        </div>
                    </div>

                    <!-- FECHA DESDE -->
                    <div class="col-6 col-md-2 col-lg-2">
                        <label class="form-label small fw-bold text-secondary mb-1">
                            <i class="fas fa-calendar me-1"></i>Desde
                        </label>
                        <input type="date"
                               name="fecha_desde"
                               id="fechaDesde"
                               class="form-control form-control-sm"
                               value="{{ request('fecha_desde') }}">
                    </div>

                    <!-- FECHA HASTA -->
                    <div class="col-6 col-md-2 col-lg-2">
                        <label class="form-label small fw-bold text-secondary mb-1">
                            <i class="fas fa-calendar-check me-1"></i>Hasta
                        </label>
                        <input type="date"
                               name="fecha_hasta"
                               id="fechaHasta"
                               class="form-control form-control-sm"
                               value="{{ request('fecha_hasta') }}">
                    </div>

                    <!-- ORDEN -->
                    <div class="col-6 col-md-2 col-lg-2">
                        <label class="form-label small fw-bold text-secondary mb-1">
                            <i class="fas fa-sort me-1"></i>Ordenar por
                        </label>
                        <select name="orden" class="form-select form-select-sm">
                            <option value="recientes" {{ request('orden', 'recientes') == 'recientes' ? 'selected' : '' }}>Más recientes</option>
                            <option value="antiguas" {{ request('orden') == 'antiguas' ? 'selected' : '' }}>Más antiguas</option>
                            <option value="monto_mayor" {{ request('orden') == 'monto_mayor' ? 'selected' : '' }}>Mayor monto</option>
                            <option value="monto_menor" {{ request('orden') == 'monto_menor' ? 'selected' : '' }}>Menor monto</option>
                        </select>
                    </div>

                    <!-- REGISTROS POR PÁGINA -->
                    <div class="col-6 col-md-2 col-lg-1">
                        <label class="form-label small fw-bold text-secondary mb-1">
                            Mostrar
                        </label>
                        <select name="per_page" class="form-select form-select-sm" onchange="document.getElementById('filterForm').submit()">
                            <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10</option>
                            <option value="25" {{ request('per_page', 25) == 25 ? 'selected' : '' }}>25</option>
                            <option value="50" {{ request('per_page', 50) == 50 ? 'selected' : '' }}>50</option>
                            <option value="100" {{ request('per_page', 100) == 100 ? 'selected' : '' }}>100</option>
                        </select>
                    </div>

                    <!-- BOTONES DE ACCIÓN DEL FILTRO -->
                    <div class="col-12 col-lg-2 d-flex gap-2">
                        <button type="submit" class="btn btn-primary btn-sm rounded-3 flex-fill shadow-sm">
                            <i class="fas fa-filter me-1"></i>Filtrar
                        </button>

                        <a href="{{ route('ventas.index') }}" class="btn btn-outline-secondary btn-sm rounded-3 px-3 shadow-sm" title="Limpiar todos los filtros">
                            <i class="fas fa-undo"></i>
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- SALES LIST TABLE CARD -->
    <div class="table-card">
        <div class="table-header-custom">
            <div class="d-flex align-items-center gap-2">
                <div style="width: 10px; height: 10px; border-radius: 50%; background: #10b981;"></div>
                <span class="fw-bold text-dark" style="font-size: 1rem;">Historial de Ventas Registradas</span>
                <span class="badge bg-light text-muted border px-2 py-1 rounded-pill" style="font-size: 0.75rem;">
                    {{ $ventas->total() }} registros encontrados
                </span>
            </div>

            <!-- LIVE SEARCH BAR (INSTANT CLIENT FILTER) -->
            <div class="d-flex align-items-center gap-2">
                <label class="small text-muted fw-semibold d-none d-sm-inline">Filtrar tabla:</label>
                <div class="input-group input-group-sm" style="max-width: 250px;">
                    <span class="input-group-text bg-white border-end-0 text-muted">
                        <i class="fas fa-bolt text-warning"></i>
                    </span>
                    <input type="text"
                           id="liveTableSearch"
                           class="form-control border-start-0 ps-0"
                           placeholder="Búsqueda rápida en vivo..."
                           onkeyup="filtrarTablaEnVivo()">
                </div>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-custom align-middle" id="tablaVentas">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 110px;">ID Venta</th>
                        <th style="width: 140px;">Fecha y Hora</th>
                        <th style="width: 150px;">Cliente / NIT</th>
                        <th>Artículos / Resumen</th>
                        <th class="text-end" style="width: 140px;">Monto Total</th>
                        <th class="text-center" style="width: 160px;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($ventas as $venta)
                    @php
                        // Total items count
                        $totalItems = $venta->detalles->sum('cantidad');
                        // Preparamos los datos JSON para el modal
                        $ventaData = [
                            'id' => $venta->id,
                            'ticket' => str_pad($venta->id, 6, '0', STR_PAD_LEFT),
                            'fecha' => \Carbon\Carbon::parse($venta->fecha)->format('d/m/Y'),
                            'hora' => $venta->created_at->format('H:i'),
                            'comprobante' => $venta->comprobante ?: 'Sin NIT / Venta Rápida',
                            'total' => number_format($venta->total, 2),
                            'comprobante_url' => route('ventas.comprobante', $venta->id),
                            'detalles' => $venta->detalles->map(function($d) {
                                return [
                                    'producto' => $d->producto->nombre ?? 'Producto no disponible',
                                    'codigo' => $d->producto->codigo ?? 'N/A',
                                    'cantidad' => $d->cantidad,
                                    'precio' => number_format($d->precio, 2),
                                    'subtotal' => number_format($d->cantidad * $d->precio, 2)
                                ];
                            })
                        ];
                    @endphp
                    <tr class="venta-row">
                        <!-- ID / TICKET -->
                        <td class="text-center">
                            <div class="ticket-badge" onclick="copyTicket('#VNT-{{ str_pad($venta->id, 5, '0', STR_PAD_LEFT) }}')" title="Clic para copiar código">
                                <i class="fas fa-tag text-purple" style="font-size: 0.72rem;"></i>
                                #VNT-{{ str_pad($venta->id, 5, '0', STR_PAD_LEFT) }}
                            </div>
                        </td>

                        <!-- FECHA Y HORA -->
                        <td>
                            <div class="d-flex flex-column">
                                <span class="fw-bold text-dark d-flex align-items-center gap-1">
                                    <i class="far fa-calendar-alt text-muted" style="font-size: 0.8rem;"></i>
                                    {{ \Carbon\Carbon::parse($venta->fecha)->translatedFormat('d M, Y') }}
                                </span>
                                <span class="text-muted small" style="font-size: 0.75rem;">
                                    <i class="far fa-clock me-1"></i>{{ $venta->created_at->format('H:i A') }}
                                </span>
                            </div>
                        </td>

                        <!-- CLIENTE / NIT -->
                        <td>
                            @if($venta->comprobante && $venta->comprobante !== 'Sin NIT')
                                <div class="d-flex align-items-center gap-2">
                                    <div class="rounded-circle bg-primary-subtle text-primary d-flex align-items-center justify-content-center" style="width: 28px; height: 28px; font-size: 0.75rem; font-weight: 700;">
                                        <i class="fas fa-id-card"></i>
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark small">NIT: {{ $venta->comprobante }}</div>
                                        <div class="text-muted" style="font-size: 0.72rem;">Factura / Comprobante</div>
                                    </div>
                                </div>
                            @else
                                <span class="badge bg-light text-secondary border px-2 py-1 rounded-pill" style="font-size: 0.75rem;">
                                    <i class="fas fa-user-tag me-1"></i>Venta Directa
                                </span>
                            @endif
                        </td>

                        <!-- RESUMEN DE ARTÍCULOS -->
                        <td>
                            <div class="d-flex flex-wrap align-items-center gap-1">
                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1 rounded-pill mb-1" style="font-size: 0.75rem; font-weight: 700;">
                                    <i class="fas fa-shopping-bag me-1"></i>{{ $totalItems }} {{ $totalItems == 1 ? 'item' : 'items' }}
                                </span>

                                @foreach($venta->detalles->take(2) as $det)
                                    <div class="product-pill" title="{{ $det->producto->nombre ?? 'Producto eliminado' }}">
                                        <span class="badge-qty">x{{ $det->cantidad }}</span>
                                        <span class="text-truncate" style="max-width: 140px;">
                                            {{ $det->producto->nombre ?? 'Producto eliminado' }}
                                        </span>
                                    </div>
                                @endforeach

                                @if($venta->detalles->count() > 2)
                                    <span class="product-pill-more" onclick="abrirDetalleVenta({{ json_encode($ventaData) }})" title="Ver todos los productos">
                                        +{{ $venta->detalles->count() - 2 }} más...
                                    </span>
                                @endif
                            </div>
                        </td>

                        <!-- TOTAL -->
                        <td class="text-end">
                            <span class="total-badge">
                                Bs. {{ number_format($venta->total, 2) }}
                            </span>
                        </td>

                        <!-- ACCIONES -->
                        <td class="text-center">
                            <div class="action-btn-group d-flex justify-content-center gap-1">
                                <!-- VER DETALLE MODAL -->
                                <button type="button"
                                        class="btn btn-outline-primary btn-sm"
                                        title="Vista rápida del comprobante"
                                        onclick="abrirDetalleVenta({{ json_encode($ventaData) }})">
                                    <i class="fas fa-eye"></i>
                                </button>

                                <!-- IMPRIMIR COMPROBANTE -->
                                <a href="{{ route('ventas.comprobante', $venta->id) }}"
                                   target="_blank"
                                   class="btn btn-outline-secondary btn-sm"
                                   title="Imprimir comprobante térmico">
                                    <i class="fas fa-print"></i>
                                </a>

                                @if(auth()->user()->esAdmin())
                                <!-- EDITAR (SOLO ADMINISTRADOR) -->
                                <a href="{{ route('ventas.edit', $venta->id) }}"
                                   class="btn btn-outline-warning btn-sm"
                                   title="Editar venta (Solo Administrador)">
                                    <i class="fas fa-pen"></i>
                                </a>

                                <!-- ELIMINAR CON SWEETALERT2 (SOLO ADMINISTRADOR) -->
                                <button type="button"
                                        class="btn btn-outline-danger btn-sm"
                                        title="Eliminar venta y devolver stock (Solo Administrador)"
                                        onclick="confirmarEliminarVenta({{ $venta->id }}, '{{ str_pad($venta->id, 5, '0', STR_PAD_LEFT) }}')">
                                    <i class="fas fa-trash-alt"></i>
                                </button>

                                <form id="formEliminar-{{ $venta->id }}"
                                      action="{{ route('ventas.destroy', $venta->id) }}"
                                      method="POST"
                                      class="d-none">
                                    @csrf
                                    @method('DELETE')
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6">
                            <div class="empty-state-box">
                                <div class="empty-state-icon">
                                    <i class="fas fa-receipt"></i>
                                </div>
                                <h5 class="fw-bold text-dark mb-1">No se encontraron ventas</h5>
                                <p class="text-muted small mb-3">
                                    No hay registros de ventas que coincidan con los filtros aplicados.
                                </p>
                                @if(request()->anyFilled(['buscar', 'fecha_desde', 'fecha_hasta', 'monto_min', 'monto_max']))
                                    <a href="{{ route('ventas.index') }}" class="btn btn-outline-primary btn-sm rounded-pill px-3">
                                        <i class="fas fa-undo me-1"></i>Restablecer Filtros
                                    </a>
                                @else
                                    @if(isset($cajaAbierta) && $cajaAbierta)
                                        <a href="{{ route('ventas.create') }}" class="btn btn-gradient-primary btn-sm rounded-pill px-4">
                                            <i class="fas fa-plus me-1"></i>Registrar Primera Venta
                                        </a>
                                    @else
                                        @if(auth()->user()->esAdmin())
                                            <a href="{{ route('caja.index') }}" class="btn text-white btn-sm rounded-pill px-4" style="background: linear-gradient(135deg, #059669 0%, #10b981 100%);">
                                                <i class="fas fa-door-open me-1"></i>Aperturar Caja para Vender
                                            </a>
                                        @else
                                            <button type="button" class="btn btn-secondary btn-sm rounded-pill px-4 opacity-75" onclick="alertaCajaCerrada()">
                                                <i class="fas fa-lock me-1"></i>Caja Cerrada (Transacciones Bloqueadas)
                                            </button>
                                        @endif
                                    @endif
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- PAGINATION & SUMMARY FOOTER -->
        <div class="p-3 bg-white border-top d-flex flex-wrap justify-content-between align-items-center gap-3 pagination-box">
            <div class="text-muted small">
                Mostrando <span class="fw-bold text-dark">{{ $ventas->firstItem() ?? 0 }}</span> a <span class="fw-bold text-dark">{{ $ventas->lastItem() ?? 0 }}</span> de <span class="fw-bold text-dark">{{ $ventas->total() }}</span> ventas registradas
            </div>

            <div>
                {{ $ventas->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>

</div>

<!-- MODAL VISTA PREVIA RÁPIDA DE COMPROBANTE -->
<div class="modal fade" id="modalDetalleVenta" tabindex="-1" aria-labelledby="modalDetalleVentaLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content modal-ticket shadow-lg">
            
            <div class="ticket-header-box">
                <button type="button" class="btn-close btn-close-white position-absolute top-0 end-0 m-3" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="d-inline-flex p-2 rounded-circle bg-white-subtle mb-2 text-warning" style="background: rgba(255, 255, 255, 0.1);">
                    <i class="fas fa-file-invoice-dollar fs-3"></i>
                </div>
                <h4 class="ticket-header-title">Detalle de Comprobante de Venta</h4>
                <div class="ticket-header-sub">Tienda Isoatlas - Sistema de Compra y Venta</div>
            </div>

            <div class="modal-body p-4">
                <!-- GRID DE INFORMACIÓN GENERAL -->
                <div class="ticket-info-grid">
                    <div class="row g-2">
                        <div class="col-6 col-md-3">
                            <span class="text-muted small d-block">N° Ticket</span>
                            <span class="fw-bold text-primary font-monospace" id="modalTicketNumero">#000000</span>
                        </div>
                        <div class="col-6 col-md-3">
                            <span class="text-muted small d-block">Fecha de Emisión</span>
                            <span class="fw-bold text-dark" id="modalTicketFecha">--/--/----</span>
                        </div>
                        <div class="col-6 col-md-3">
                            <span class="text-muted small d-block">Hora</span>
                            <span class="fw-bold text-dark" id="modalTicketHora">--:--</span>
                        </div>
                        <div class="col-6 col-md-3">
                            <span class="text-muted small d-block">NIT / Cliente</span>
                            <span class="fw-bold text-dark" id="modalTicketNit">Sin NIT</span>
                        </div>
                    </div>
                </div>

                <!-- TABLA DE ARTÍCULOS VENDIDOS -->
                <div class="table-responsive mb-3">
                    <table class="table table-bordered table-sm align-middle" style="font-size: 0.85rem;">
                        <thead class="table-light text-center">
                            <tr>
                                <th style="width: 40px;">#</th>
                                <th class="text-start">Producto / Artículo</th>
                                <th style="width: 80px;">Cant.</th>
                                <th style="width: 110px;" class="text-end">P. Unitario</th>
                                <th style="width: 120px;" class="text-end">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody id="modalTicketItemsBody">
                            <!-- Inyectado dinámicamente con JS -->
                        </tbody>
                    </table>
                </div>

                <!-- TOTAL BANNER -->
                <div class="ticket-total-display">
                    <div class="text-muted small fw-bold text-uppercase" style="letter-spacing: 0.05em;">Total Pagado</div>
                    <div class="fw-extrabold text-success" id="modalTicketTotal" style="font-size: 1.8rem; font-weight: 800;">
                        Bs. 0.00
                    </div>
                </div>
            </div>

            <div class="modal-footer bg-light border-0 p-3 justify-content-between">
                <button type="button" class="btn btn-secondary btn-sm rounded-3 px-3" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i>Cerrar
                </button>

                <div class="d-flex gap-2">
                    <a href="#" id="modalBtnImprimir" target="_blank" class="btn btn-primary btn-sm rounded-3 px-3 shadow-sm">
                        <i class="fas fa-print me-1"></i>Imprimir Ticket
                    </a>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- JAVASCRIPT LOGIC -->
<script>
// 1. ABRIR MODAL DE DETALLE DE VENTA
function abrirDetalleVenta(data) {
    document.getElementById('modalTicketNumero').innerText = '#' + data.ticket;
    document.getElementById('modalTicketFecha').innerText = data.fecha;
    document.getElementById('modalTicketHora').innerText = data.hora;
    document.getElementById('modalTicketNit').innerText = data.comprobante;
    document.getElementById('modalTicketTotal').innerText = 'Bs. ' + data.total;
    document.getElementById('modalBtnImprimir').href = data.comprobante_url;

    let tbody = document.getElementById('modalTicketItemsBody');
    tbody.innerHTML = '';

    data.detalles.forEach((item, index) => {
        let tr = document.createElement('tr');
        tr.innerHTML = `
            <td class="text-center text-muted">${index + 1}</td>
            <td>
                <div class="fw-bold text-dark">${item.producto}</div>
                <div class="text-muted" style="font-size: 0.72rem;">Cód: ${item.codigo}</div>
            </td>
            <td class="text-center">
                <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1">${item.cantidad}</span>
            </td>
            <td class="text-end">Bs. ${item.precio}</td>
            <td class="text-end fw-bold text-dark">Bs. ${item.subtotal}</td>
        `;
        tbody.appendChild(tr);
    });

    let modal = new bootstrap.Modal(document.getElementById('modalDetalleVenta'));
    modal.show();
}

// 2. LIVE SEARCH RÁPIDO CLIENT-SIDE EN LA TABLA
function filtrarTablaEnVivo() {
    let input = document.getElementById('liveTableSearch');
    let filter = input.value.toLowerCase().trim();
    let rows = document.querySelectorAll('#tablaVentas tbody tr.venta-row');

    rows.forEach(row => {
        let text = row.innerText.toLowerCase();
        row.style.display = text.includes(filter) ? '' : 'none';
    });
}

// 3. SET DATE RANGE SHORTCUTS
function setDateRange(type) {
    const today = new Date();
    const formatDate = (date) => date.toISOString().split('T')[0];

    const inputDesde = document.getElementById('fechaDesde');
    const inputHasta = document.getElementById('fechaHasta');

    if (type === 'hoy') {
        inputDesde.value = formatDate(today);
        inputHasta.value = formatDate(today);
    } else if (type === 'semana') {
        const past7Days = new Date();
        past7Days.setDate(today.getDate() - 7);
        inputDesde.value = formatDate(past7Days);
        inputHasta.value = formatDate(today);
    } else if (type === 'mes') {
        const firstDayOfMonth = new Date(today.getFullYear(), today.getMonth(), 1);
        inputDesde.value = formatDate(firstDayOfMonth);
        inputHasta.value = formatDate(today);
    } else if (type === 'limpiar') {
        inputDesde.value = '';
        inputHasta.value = '';
    }

    document.getElementById('filterForm').submit();
}

// 4. CONFIRMAR ELIMINAR CON SWEETALERT2
function confirmarEliminarVenta(id, ticket) {
    Swal.fire({
        title: '¿Eliminar Venta #' + ticket + '?',
        html: `Esta acción anulará la transacción de venta y <strong>restaurará el stock de los productos</strong> vendidos al inventario del sistema.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#64748b',
        confirmButtonText: '<i class="fas fa-trash-alt me-1"></i> Sí, eliminar',
        cancelButtonText: 'Cancelar',
        customClass: {
            popup: 'rounded-4'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('formEliminar-' + id).submit();
        }
    });
}

// 5. COPIAR NÚMERO DE TICKET AL PORTAPAPELES
function copyTicket(ticket) {
    navigator.clipboard.writeText(ticket).then(() => {
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'success',
            title: 'Código ' + ticket + ' copiado al portapapeles',
            showConfirmButton: false,
            timer: 2000
        });
    });
}

// 6. EXPORTAR TABLA VISIBLE A CSV / EXCEL
function exportarExcel() {
    let rows = document.querySelectorAll('#tablaVentas tbody tr.venta-row');
    if (rows.length === 0) {
        Swal.fire({
            icon: 'info',
            title: 'Sin datos',
            text: 'No hay ventas para exportar con los filtros actuales.',
            confirmButtonColor: '#4f46e5'
        });
        return;
    }

    let csvContent = "\uFEFF"; // UTF-8 BOM
    csvContent += "ID Venta,Fecha,Hora,NIT/Cliente,Total (Bs.)\r\n";

    rows.forEach(row => {
        if (row.style.display !== 'none') {
            let cells = row.querySelectorAll('td');
            if (cells.length >= 5) {
                let id = cells[0].innerText.replace(/\r?\n|\r/g, " ").trim();
                let fechaHora = cells[1].innerText.replace(/\r?\n|\r/g, " ").trim();
                let nit = cells[2].innerText.replace(/\r?\n|\r/g, " ").trim();
                let total = cells[4].innerText.replace(/\r?\n|\r/g, " ").replace("Bs.", "").trim();

                csvContent += `"${id}","${fechaHora}","${nit}","${total}"\r\n`;
            }
        }
    });

    let blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
    let link = document.createElement("a");
    let url = URL.createObjectURL(blob);
    link.setAttribute("href", url);
    link.setAttribute("download", "reporte_ventas_" + new Date().toISOString().slice(0,10) + ".csv");
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}

// 7. ALERTA DE CAJA CERRADA PARA VENDEDORES
function alertaCajaCerrada() {
    Swal.fire({
        icon: 'warning',
        title: 'Caja Registradora Cerrada',
        html: '<p class="mb-2">No puede realizar transacciones en este momento porque <strong>la caja se encuentra cerrada</strong>.</p><p class="text-muted small mb-0">El <strong>Administrador</strong> tiene el control exclusivo de aperturar la caja para habilitar las ventas en el sistema.</p>',
        confirmButtonText: '<i class="fas fa-check me-1"></i> Entendido',
        confirmButtonColor: '#4f46e5'
    });
}
</script>

@endsection