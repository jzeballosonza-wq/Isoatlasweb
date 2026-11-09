@extends('dashboard')

@section('content')

<style>
    /* VARIABLES & DESIGN TOKENS */
    :root {
        --cmp-primary: #4f46e5;
        --cmp-primary-dark: #3730a3;
        --cmp-success: #10b981;
        --cmp-info: #0284c7;
        --cmp-warning: #f59e0b;
        --cmp-danger: #ef4444;
        --cmp-dark: #0f172a;
        --cmp-card-bg: #ffffff;
        --cmp-border: #e2e8f0;
    }

    .compras-container {
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

    .kpi-icon-emerald {
        background: linear-gradient(135deg, #059669 0%, #10b981 100%);
        box-shadow: 0 6px 15px rgba(16, 185, 129, 0.25);
    }

    .kpi-icon-blue {
        background: linear-gradient(135deg, #0284c7 0%, #38bdf8 100%);
        box-shadow: 0 6px 15px rgba(2, 132, 199, 0.25);
    }

    .kpi-icon-purple {
        background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
        box-shadow: 0 6px 15px rgba(99, 102, 241, 0.25);
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

<div class="container-fluid compras-container">

    <!-- TOP HEADER & ACTIONS -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1" style="font-size: 0.82rem;">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none text-muted"><i class="fas fa-home me-1"></i>Inicio</a></li>
                    <li class="breadcrumb-item active text-primary fw-semibold" aria-current="page">Compras</li>
                </ol>
            </nav>
            <h2 class="fw-bold text-dark m-0 d-flex align-items-center gap-2" style="font-size: 1.55rem; letter-spacing: -0.02em;">
                <i class="fas fa-truck-loading text-primary"></i> Gestión y Listado de Compras
            </h2>
            <p class="text-muted small m-0 mt-1">
                Consulta, filtra y gestiona el historial de abastecimiento de mercadería a proveedores.
            </p>
        </div>

        <div class="d-flex flex-wrap gap-2 btn-action-top">
            <button type="button" class="btn btn-outline-success btn-sm rounded-3 px-3 shadow-sm d-flex align-items-center gap-2" onclick="exportarExcelCompras()">
                <i class="fas fa-file-excel"></i>
                <span>Exportar CSV</span>
            </button>

            <button type="button" class="btn btn-outline-secondary btn-sm rounded-3 px-3 shadow-sm d-flex align-items-center gap-2" onclick="window.print()">
                <i class="fas fa-print"></i>
                <span>Imprimir Listado</span>
            </button>

            <a href="{{ route('compras.create') }}" class="btn btn-gradient-primary btn-sm rounded-3 px-3 shadow-sm d-flex align-items-center gap-2">
                <i class="fas fa-plus-circle"></i>
                <span>Nueva Compra</span>
            </a>
        </div>
    </div>

    <!-- STATS / KPI CARDS -->
    <div class="row g-3 mb-4 kpi-section">
        <!-- 1. TOTAL INVERTIDO -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="kpi-card">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="kpi-label">Inversión Total</div>
                        <div class="kpi-value text-emerald">
                            Bs. {{ number_format($totalInvertido ?? 0, 2) }}
                        </div>
                        <div class="kpi-subtext mt-1">
                            <i class="fas fa-check-circle text-success me-1"></i>{{ $totalCompras ?? 0 }} compras registradas
                        </div>
                    </div>
                    <div class="kpi-icon-box kpi-icon-emerald">
                        <i class="fas fa-wallet"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- 2. COMPRAS DE HOY -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="kpi-card">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="kpi-label">Compras de Hoy</div>
                        <div class="kpi-value text-primary">
                            Bs. {{ number_format($inversionHoy ?? 0, 2) }}
                        </div>
                        <div class="kpi-subtext mt-1">
                            <i class="fas fa-calendar-day text-primary me-1"></i>{{ $comprasHoy ?? 0 }} órdenes hoy
                        </div>
                    </div>
                    <div class="kpi-icon-box kpi-icon-blue">
                        <i class="fas fa-bolt"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- 3. COMPRAS DEL MES -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="kpi-card">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="kpi-label">Compras del Mes</div>
                        <div class="kpi-value text-purple">
                            Bs. {{ number_format($inversionMes ?? 0, 2) }}
                        </div>
                        <div class="kpi-subtext mt-1">
                            <i class="fas fa-calendar-alt text-purple me-1"></i>{{ $comprasMes ?? 0 }} en {{ now()->translatedFormat('F') }}
                        </div>
                    </div>
                    <div class="kpi-icon-box kpi-icon-purple">
                        <i class="fas fa-chart-line"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- 4. PROMEDIO POR COMPRA -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="kpi-card">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="kpi-label">Promedio por Compra</div>
                        <div class="kpi-value text-amber">
                            Bs. {{ number_format($promedioCompra ?? 0, 2) }}
                        </div>
                        <div class="kpi-subtext mt-1">
                            <i class="fas fa-tags text-warning me-1"></i>Inversión media por orden
                        </div>
                    </div>
                    <div class="kpi-icon-box kpi-icon-amber">
                        <i class="fas fa-calculator"></i>
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
                @if(request()->anyFilled(['buscar', 'proveedor_id', 'fecha_desde', 'fecha_hasta', 'monto_min', 'monto_max', 'orden']))
                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill ms-1 px-2 py-1" style="font-size: 0.72rem;">
                        <i class="fas fa-check me-1"></i>Filtros Activos
                    </span>
                @endif
            </div>

            <!-- SHORTCUT BUTTONS FOR DATES -->
            <div class="d-none d-md-flex align-items-center gap-1">
                <span class="text-muted small me-1">Rápido:</span>
                <button type="button" class="filter-btn-shortcut" onclick="setDateRangeCompras('hoy')">Hoy</button>
                <button type="button" class="filter-btn-shortcut" onclick="setDateRangeCompras('semana')">Últimos 7 días</button>
                <button type="button" class="filter-btn-shortcut" onclick="setDateRangeCompras('mes')">Este Mes</button>
                <button type="button" class="filter-btn-shortcut" onclick="setDateRangeCompras('limpiar')">Todo</button>
            </div>
        </div>

        <div class="p-3 bg-white">
            <form action="{{ route('compras.index') }}" method="GET" id="filterFormCompras">
                <div class="row g-3 align-items-end">
                    <!-- BUSCADOR -->
                    <div class="col-12 col-md-3">
                        <label class="form-label small fw-bold text-secondary mb-1">
                            <i class="fas fa-search me-1"></i>Buscador
                        </label>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-light border-end-0 text-muted">
                                <i class="fas fa-search"></i>
                            </span>
                            <input type="text"
                                   name="buscar"
                                   id="buscarGeneralCompras"
                                   class="form-control border-start-0 ps-0"
                                   placeholder="N° Compra, factura, producto..."
                                   value="{{ request('buscar') }}">
                        </div>
                    </div>

                    <!-- PROVEEDOR -->
                    <div class="col-12 col-md-3 col-lg-2">
                        <label class="form-label small fw-bold text-secondary mb-1">
                            <i class="fas fa-truck me-1"></i>Proveedor
                        </label>
                        <select name="proveedor_id" class="form-select form-select-sm">
                            <option value="">Todos los proveedores</option>
                            @foreach($proveedores as $p)
                                <option value="{{ $p->id }}" {{ request('proveedor_id') == $p->id ? 'selected' : '' }}>
                                    {{ $p->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- FECHA DESDE -->
                    <div class="col-6 col-md-2 col-lg-2">
                        <label class="form-label small fw-bold text-secondary mb-1">
                            <i class="fas fa-calendar me-1"></i>Desde
                        </label>
                        <input type="date"
                               name="fecha_desde"
                               id="fechaDesdeCompras"
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
                               id="fechaHastaCompras"
                               class="form-control form-control-sm"
                               value="{{ request('fecha_hasta') }}">
                    </div>

                    <!-- ORDEN -->
                    <div class="col-6 col-md-2 col-lg-1">
                        <label class="form-label small fw-bold text-secondary mb-1">
                            <i class="fas fa-sort me-1"></i>Orden
                        </label>
                        <select name="orden" class="form-select form-select-sm">
                            <option value="recientes" {{ request('orden', 'recientes') == 'recientes' ? 'selected' : '' }}>Recientes</option>
                            <option value="antiguas" {{ request('orden') == 'antiguas' ? 'selected' : '' }}>Antiguas</option>
                            <option value="monto_mayor" {{ request('orden') == 'monto_mayor' ? 'selected' : '' }}>+ Monto</option>
                            <option value="monto_menor" {{ request('orden') == 'monto_menor' ? 'selected' : '' }}>- Monto</option>
                        </select>
                    </div>

                    <!-- REGISTROS POR PÁGINA -->
                    <div class="col-6 col-md-2 col-lg-1">
                        <label class="form-label small fw-bold text-secondary mb-1">
                            Mostrar
                        </label>
                        <select name="per_page" class="form-select form-select-sm" onchange="document.getElementById('filterFormCompras').submit()">
                            <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10</option>
                            <option value="25" {{ request('per_page', 25) == 25 ? 'selected' : '' }}>25</option>
                            <option value="50" {{ request('per_page', 50) == 50 ? 'selected' : '' }}>50</option>
                            <option value="100" {{ request('per_page', 100) == 100 ? 'selected' : '' }}>100</option>
                        </select>
                    </div>

                    <!-- BOTONES DE ACCIÓN DEL FILTRO -->
                    <div class="col-12 col-lg-1 d-flex gap-2">
                        <button type="submit" class="btn btn-primary btn-sm rounded-3 flex-fill shadow-sm" title="Filtrar">
                            <i class="fas fa-filter"></i>
                        </button>

                        <a href="{{ route('compras.index') }}" class="btn btn-outline-secondary btn-sm rounded-3 px-2 shadow-sm" title="Limpiar filtros">
                            <i class="fas fa-undo"></i>
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- PURCHASES LIST TABLE CARD -->
    <div class="table-card">
        <div class="table-header-custom">
            <div class="d-flex align-items-center gap-2">
                <div style="width: 10px; height: 10px; border-radius: 50%; background: #4f46e5;"></div>
                <span class="fw-bold text-dark" style="font-size: 1rem;">Historial de Compras Registradas</span>
                <span class="badge bg-light text-muted border px-2 py-1 rounded-pill" style="font-size: 0.75rem;">
                    {{ $compras->total() }} registros encontrados
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
                           id="liveTableSearchCompras"
                           class="form-control border-start-0 ps-0"
                           placeholder="Búsqueda rápida en vivo..."
                           onkeyup="filtrarTablaComprasEnVivo()">
                </div>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-custom align-middle" id="tablaCompras">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 110px;">ID Compra</th>
                        <th style="width: 140px;">Fecha y Hora</th>
                        <th style="width: 180px;">Proveedor</th>
                        <th style="width: 130px;">Comprobante</th>
                        <th>Artículos Ingresados</th>
                        <th class="text-end" style="width: 140px;">Monto Total</th>
                        <th class="text-center" style="width: 130px;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($compras as $compra)
                    @php
                        // Total items count
                        $totalItems = $compra->detalles->sum('cantidad');
                        // Preparamos los datos JSON para el modal
                        $compraData = [
                            'id' => $compra->id,
                            'ticket' => str_pad($compra->id, 6, '0', STR_PAD_LEFT),
                            'fecha' => \Carbon\Carbon::parse($compra->fecha)->format('d/m/Y'),
                            'hora' => $compra->created_at->format('H:i'),
                            'proveedor' => $compra->proveedor->nombre ?? 'Proveedor no especificado',
                            'proveedor_telefono' => $compra->proveedor->telefono ?? 'Sin teléfono',
                            'proveedor_email' => $compra->proveedor->email ?? 'Sin email',
                            'proveedor_direccion' => $compra->proveedor->direccion ?? 'Sin dirección',
                            'comprobante' => $compra->comprobante ?: 'Sin comprobante',
                            'total' => number_format($compra->total, 2),
                            'detalles' => $compra->detalles->map(function($d) {
                                return [
                                    'producto' => $d->producto->nombre ?? 'Producto eliminado',
                                    'codigo' => $d->producto->codigo ?? 'N/A',
                                    'cantidad' => $d->cantidad,
                                    'precio' => number_format($d->precio, 2),
                                    'subtotal' => number_format($d->cantidad * $d->precio, 2)
                                ];
                            })
                        ];
                    @endphp
                    <tr class="compra-row">
                        <!-- ID / TICKET -->
                        <td class="text-center">
                            <div class="ticket-badge" onclick="copyTicketCompra('#CMP-{{ str_pad($compra->id, 5, '0', STR_PAD_LEFT) }}')" title="Clic para copiar código">
                                <i class="fas fa-tag text-purple" style="font-size: 0.72rem;"></i>
                                #CMP-{{ str_pad($compra->id, 5, '0', STR_PAD_LEFT) }}
                            </div>
                        </td>

                        <!-- FECHA Y HORA -->
                        <td>
                            <div class="d-flex flex-column">
                                <span class="fw-bold text-dark d-flex align-items-center gap-1">
                                    <i class="far fa-calendar-alt text-muted" style="font-size: 0.8rem;"></i>
                                    {{ \Carbon\Carbon::parse($compra->fecha)->translatedFormat('d M, Y') }}
                                </span>
                                <span class="text-muted small" style="font-size: 0.75rem;">
                                    <i class="far fa-clock me-1"></i>{{ $compra->created_at->format('H:i A') }}
                                </span>
                            </div>
                        </td>

                        <!-- PROVEEDOR -->
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="rounded-circle bg-primary-subtle text-primary d-flex align-items-center justify-content-center flex-shrink-0" style="width: 30px; height: 30px; font-weight: 700; font-size: 0.75rem;">
                                    {{ strtoupper(substr($compra->proveedor->nombre ?? 'P', 0, 2)) }}
                                </div>
                                <div class="text-truncate" style="max-width: 140px;">
                                    <div class="fw-bold text-dark small text-truncate" title="{{ $compra->proveedor->nombre ?? 'Sin proveedor' }}">
                                        {{ $compra->proveedor->nombre ?? 'Sin proveedor' }}
                                    </div>
                                    <div class="text-muted" style="font-size: 0.72rem;">
                                        {{ $compra->proveedor->telefono ?? 'Teléfono N/D' }}
                                    </div>
                                </div>
                            </div>
                        </td>

                        <!-- COMPROBANTE -->
                        <td>
                            @if($compra->comprobante)
                                <span class="badge bg-light text-dark border px-2 py-1 font-monospace" style="font-size: 0.75rem;">
                                    <i class="fas fa-receipt text-muted me-1"></i>{{ $compra->comprobante }}
                                </span>
                            @else
                                <span class="text-muted small" style="font-size: 0.75rem;">Sin N°</span>
                            @endif
                        </td>

                        <!-- ARTÍCULOS INGRESADOS -->
                        <td>
                            <div class="d-flex flex-wrap align-items-center gap-1">
                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1 rounded-pill mb-1" style="font-size: 0.75rem; font-weight: 700;">
                                    <i class="fas fa-boxes me-1"></i>{{ $totalItems }} {{ $totalItems == 1 ? 'unidad' : 'unidades' }}
                                </span>

                                @foreach($compra->detalles->take(2) as $det)
                                    <div class="product-pill" title="{{ $det->producto->nombre ?? 'Producto eliminado' }} (Costo: Bs. {{ number_format($det->precio, 2) }})">
                                        <span class="badge-qty">x{{ $det->cantidad }}</span>
                                        <span class="text-truncate" style="max-width: 130px;">
                                            {{ $det->producto->nombre ?? 'Producto eliminado' }}
                                        </span>
                                    </div>
                                @endforeach

                                @if($compra->detalles->count() > 2)
                                    <span class="product-pill-more" onclick="abrirDetalleCompra({{ json_encode($compraData) }})" title="Ver todos los productos">
                                        +{{ $compra->detalles->count() - 2 }} más...
                                    </span>
                                @endif
                            </div>
                        </td>

                        <!-- TOTAL -->
                        <td class="text-end">
                            <span class="total-badge">
                                Bs. {{ number_format($compra->total, 2) }}
                            </span>
                        </td>

                        <!-- ACCIONES -->
                        <td class="text-center">
                            <div class="action-btn-group d-flex justify-content-center gap-1">
                                <!-- VER DETALLE MODAL -->
                                <button type="button"
                                        class="btn btn-outline-primary btn-sm"
                                        title="Ver desglose de compra"
                                        onclick="abrirDetalleCompra({{ json_encode($compraData) }})">
                                    <i class="fas fa-eye"></i>
                                </button>

                                <!-- EDITAR -->
                                <a href="{{ route('compras.edit', $compra->id) }}"
                                   class="btn btn-outline-warning btn-sm"
                                   title="Editar compra">
                                    <i class="fas fa-pen"></i>
                                </a>

                                <!-- ELIMINAR CON SWEETALERT2 -->
                                <button type="button"
                                        class="btn btn-outline-danger btn-sm"
                                        title="Eliminar compra y descontar stock"
                                        onclick="confirmarEliminarCompra({{ $compra->id }}, '{{ str_pad($compra->id, 5, '0', STR_PAD_LEFT) }}')">
                                    <i class="fas fa-trash-alt"></i>
                                </button>

                                <form id="formEliminarCompra-{{ $compra->id }}"
                                      action="{{ route('compras.destroy', $compra->id) }}"
                                      method="POST"
                                      class="d-none">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7">
                            <div class="empty-state-box">
                                <div class="empty-state-icon">
                                    <i class="fas fa-truck-loading"></i>
                                </div>
                                <h5 class="fw-bold text-dark mb-1">No se encontraron compras</h5>
                                <p class="text-muted small mb-3">
                                    No hay registros de compras que coincidan con los filtros aplicados.
                                </p>
                                @if(request()->anyFilled(['buscar', 'proveedor_id', 'fecha_desde', 'fecha_hasta', 'monto_min', 'monto_max']))
                                    <a href="{{ route('compras.index') }}" class="btn btn-outline-primary btn-sm rounded-pill px-3">
                                        <i class="fas fa-undo me-1"></i>Restablecer Filtros
                                    </a>
                                @else
                                    <a href="{{ route('compras.create') }}" class="btn btn-gradient-primary btn-sm rounded-pill px-4">
                                        <i class="fas fa-plus me-1"></i>Registrar Primera Compra
                                    </a>
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
                Mostrando <span class="fw-bold text-dark">{{ $compras->firstItem() ?? 0 }}</span> a <span class="fw-bold text-dark">{{ $compras->lastItem() ?? 0 }}</span> de <span class="fw-bold text-dark">{{ $compras->total() }}</span> compras registradas
            </div>

            <div>
                {{ $compras->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>

</div>

<!-- MODAL VISTA PREVIA RÁPIDA DE DETALLE DE COMPRA -->
<div class="modal fade" id="modalDetalleCompra" tabindex="-1" aria-labelledby="modalDetalleCompraLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content modal-ticket shadow-lg">
            
            <div class="ticket-header-box">
                <button type="button" class="btn-close btn-close-white position-absolute top-0 end-0 m-3" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="d-inline-flex p-2 rounded-circle bg-white-subtle mb-2 text-warning" style="background: rgba(255, 255, 255, 0.1);">
                    <i class="fas fa-file-invoice fs-3"></i>
                </div>
                <h4 class="ticket-header-title">Detalle de Orden de Compra</h4>
                <div class="ticket-header-sub">Tienda Isoatlas - Registro de Ingreso de Mercadería</div>
            </div>

            <div class="modal-body p-4">
                <!-- GRID DE INFORMACIÓN GENERAL -->
                <div class="ticket-info-grid">
                    <div class="row g-2">
                        <div class="col-6 col-md-3">
                            <span class="text-muted small d-block">N° Compra</span>
                            <span class="fw-bold text-primary font-monospace" id="modalCompraNumero">#000000</span>
                        </div>
                        <div class="col-6 col-md-3">
                            <span class="text-muted small d-block">Fecha de Compra</span>
                            <span class="fw-bold text-dark" id="modalCompraFecha">--/--/----</span>
                        </div>
                        <div class="col-6 col-md-3">
                            <span class="text-muted small d-block">Comprobante</span>
                            <span class="fw-bold text-dark" id="modalCompraComprobante">Sin comprobante</span>
                        </div>
                        <div class="col-6 col-md-3">
                            <span class="text-muted small d-block">Hora</span>
                            <span class="fw-bold text-dark" id="modalCompraHora">--:--</span>
                        </div>

                        <div class="col-12 mt-2 pt-2 border-top">
                            <span class="text-muted small d-block">Proveedor</span>
                            <span class="fw-bold text-dark fs-6" id="modalCompraProveedor">--</span>
                            <div class="text-muted small" id="modalCompraProveedorDetalles"></div>
                        </div>
                    </div>
                </div>

                <!-- TABLA DE ARTÍCULOS COMPRADOS -->
                <div class="table-responsive mb-3">
                    <table class="table table-bordered table-sm align-middle" style="font-size: 0.85rem;">
                        <thead class="table-light text-center">
                            <tr>
                                <th style="width: 40px;">#</th>
                                <th class="text-start">Producto / Artículo</th>
                                <th style="width: 80px;">Cant.</th>
                                <th style="width: 120px;" class="text-end">Costo Unit.</th>
                                <th style="width: 120px;" class="text-end">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody id="modalCompraItemsBody">
                            <!-- Inyectado dinámicamente con JS -->
                        </tbody>
                    </table>
                </div>

                <!-- TOTAL BANNER -->
                <div class="ticket-total-display">
                    <div class="text-muted small fw-bold text-uppercase" style="letter-spacing: 0.05em;">Total Liquidado</div>
                    <div class="fw-extrabold text-success" id="modalCompraTotal" style="font-size: 1.8rem; font-weight: 800;">
                        Bs. 0.00
                    </div>
                </div>
            </div>

            <div class="modal-footer bg-light border-0 p-3 justify-content-between">
                <button type="button" class="btn btn-secondary btn-sm rounded-3 px-3" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i>Cerrar
                </button>

                <div class="d-flex gap-2">
                    <a href="{{ route('compras.create') }}" class="btn btn-primary btn-sm rounded-3 px-3 shadow-sm">
                        <i class="fas fa-plus me-1"></i>Registrar Otra Compra
                    </a>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- JAVASCRIPT LOGIC -->
<script>
// 1. ABRIR MODAL DE DETALLE DE COMPRA
function abrirDetalleCompra(data) {
    document.getElementById('modalCompraNumero').innerText = '#' + data.ticket;
    document.getElementById('modalCompraFecha').innerText = data.fecha;
    document.getElementById('modalCompraHora').innerText = data.hora;
    document.getElementById('modalCompraComprobante').innerText = data.comprobante;
    document.getElementById('modalCompraProveedor').innerText = data.proveedor;
    document.getElementById('modalCompraProveedorDetalles').innerText = `Teléfono: ${data.proveedor_telefono} | Email: ${data.proveedor_email} | Dirección: ${data.proveedor_direccion}`;
    document.getElementById('modalCompraTotal').innerText = 'Bs. ' + data.total;

    let tbody = document.getElementById('modalCompraItemsBody');
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

    let modal = new bootstrap.Modal(document.getElementById('modalDetalleCompra'));
    modal.show();
}

// 2. LIVE SEARCH RÁPIDO CLIENT-SIDE EN LA TABLA
function filtrarTablaComprasEnVivo() {
    let input = document.getElementById('liveTableSearchCompras');
    let filter = input.value.toLowerCase().trim();
    let rows = document.querySelectorAll('#tablaCompras tbody tr.compra-row');

    rows.forEach(row => {
        let text = row.innerText.toLowerCase();
        row.style.display = text.includes(filter) ? '' : 'none';
    });
}

// 3. SHORTCUTS DE RANGO DE FECHAS
function setDateRangeCompras(type) {
    const today = new Date();
    const formatDate = (date) => date.toISOString().split('T')[0];

    const inputDesde = document.getElementById('fechaDesdeCompras');
    const inputHasta = document.getElementById('fechaHastaCompras');

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

    document.getElementById('filterFormCompras').submit();
}

// 4. CONFIRMAR ELIMINAR CON SWEETALERT2
function confirmarEliminarCompra(id, ticket) {
    Swal.fire({
        title: '¿Eliminar Compra #' + ticket + '?',
        html: `Esta acción anulará la orden de compra y <strong>descontará el stock de los productos</strong> del inventario del sistema.`,
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
            document.getElementById('formEliminarCompra-' + id).submit();
        }
    });
}

// 5. COPIAR NÚMERO DE COMPRA AL PORTAPAPELES
function copyTicketCompra(ticket) {
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
function exportarExcelCompras() {
    let rows = document.querySelectorAll('#tablaCompras tbody tr.compra-row');
    if (rows.length === 0) {
        Swal.fire({
            icon: 'info',
            title: 'Sin datos',
            text: 'No hay compras para exportar con los filtros actuales.',
            confirmButtonColor: '#4f46e5'
        });
        return;
    }

    let csvContent = "\uFEFF"; // UTF-8 BOM
    csvContent += "ID Compra,Fecha,Proveedor,Comprobante,Total (Bs.)\r\n";

    rows.forEach(row => {
        if (row.style.display !== 'none') {
            let cells = row.querySelectorAll('td');
            if (cells.length >= 6) {
                let id = cells[0].innerText.replace(/\r?\n|\r/g, " ").trim();
                let fecha = cells[1].innerText.replace(/\r?\n|\r/g, " ").trim();
                let prov = cells[2].innerText.replace(/\r?\n|\r/g, " ").trim();
                let comp = cells[3].innerText.replace(/\r?\n|\r/g, " ").trim();
                let total = cells[5].innerText.replace(/\r?\n|\r/g, " ").replace("Bs.", "").trim();

                csvContent += `"${id}","${fecha}","${prov}","${comp}","${total}"\r\n`;
            }
        }
    });

    let blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
    let link = document.createElement("a");
    let url = URL.createObjectURL(blob);
    link.setAttribute("href", url);
    link.setAttribute("download", "reporte_compras_" + new Date().toISOString().slice(0,10) + ".csv");
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}
</script>

@endsection