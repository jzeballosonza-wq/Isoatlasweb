@extends('dashboard')

@section('content')

<style>
    :root {
        --inv-primary: #6366f1;
        --inv-primary-dark: #4f46e5;
        --inv-success: #10b981;
        --inv-danger: #ef4444;
        --inv-warning: #f59e0b;
        --inv-info: #0284c7;
        --inv-purple: #7c3aed;
    }

    .inventario-container {
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

    .kpi-icon-indigo {
        background: linear-gradient(135deg, #4f46e5 0%, #6366f1 100%);
        box-shadow: 0 6px 15px rgba(79, 70, 229, 0.25);
    }

    .kpi-icon-emerald {
        background: linear-gradient(135deg, #059669 0%, #10b981 100%);
        box-shadow: 0 6px 15px rgba(16, 185, 129, 0.25);
    }

    .kpi-icon-rose {
        background: linear-gradient(135deg, #e11d48 0%, #f43f5e 100%);
        box-shadow: 0 6px 15px rgba(225, 29, 72, 0.25);
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

    /* FILTER CARD */
    .filter-card {
        background: #ffffff;
        border-radius: 16px;
        border: 1px solid #f1f5f9;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
        margin-bottom: 1.5rem;
        overflow: hidden;
    }

    /* TABLE MODERN */
    .table-container-card {
        background: #ffffff;
        border-radius: 18px;
        border: 1px solid #f1f5f9;
        box-shadow: 0 4px 25px rgba(0, 0, 0, 0.03);
        overflow: hidden;
    }

    .table-custom thead th {
        background: #f8fafc;
        color: #475569;
        font-weight: 700;
        font-size: 0.78rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        padding: 1rem 1.2rem;
        border-bottom: 1px solid #e2e8f0;
        white-space: nowrap;
    }

    .table-custom tbody td {
        padding: 1rem 1.2rem;
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9;
        font-size: 0.88rem;
    }

    .table-custom tbody tr:hover td {
        background-color: #f8fafc;
    }

    /* NAV PILLS MODERN */
    .nav-tabs-modern {
        border-bottom: 2px solid #e2e8f0;
        gap: 0.5rem;
    }

    .nav-tabs-modern .nav-link {
        border: none;
        color: #64748b;
        font-weight: 600;
        font-size: 0.9rem;
        padding: 0.75rem 1.25rem;
        border-radius: 10px 10px 0 0;
        transition: all 0.2s ease;
        position: relative;
    }

    .nav-tabs-modern .nav-link:hover {
        color: #0f172a;
        background: #f1f5f9;
    }

    .nav-tabs-modern .nav-link.active {
        color: #4f46e5;
        background: transparent;
    }

    .nav-tabs-modern .nav-link.active::after {
        content: '';
        position: absolute;
        bottom: -2px;
        left: 0;
        right: 0;
        height: 3px;
        background: #4f46e5;
        border-radius: 3px 3px 0 0;
    }

    .badge-qty-in {
        background: rgba(16, 185, 129, 0.12);
        color: #059669;
        border: 1px solid rgba(16, 185, 129, 0.25);
        font-weight: 700;
        font-size: 0.85rem;
        padding: 0.35rem 0.65rem;
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }

    .badge-qty-out {
        background: rgba(239, 68, 68, 0.12);
        color: #dc2626;
        border: 1px solid rgba(239, 68, 68, 0.25);
        font-weight: 700;
        font-size: 0.85rem;
        padding: 0.35rem 0.65rem;
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }
</style>

<div class="inventario-container">

    <!-- 1. ENCABEZADO Y BOTONES DE ACCIÓN -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <div class="kpi-icon-box kpi-icon-indigo" style="width: 36px; height: 36px; font-size: 1rem; border-radius: 10px;">
                    <i class="fas fa-boxes-stacked"></i>
                </div>
                <h2 class="h4 fw-bold mb-0 text-dark">Control de Inventario, Ingresos y Egresos</h2>
            </div>
            <p class="text-muted small mb-0">
                Kardex valorizado en tiempo real, registro de entradas por compras, salidas por ventas y ajustes manuales.
            </p>
        </div>
        <div class="d-flex flex-wrap align-items-center gap-2">
            <!-- Botón Registrar Ingreso (+) -->
            <button type="button" class="btn text-white rounded-3 px-3 py-2 fw-semibold d-inline-flex align-items-center gap-2 shadow-sm" style="background: linear-gradient(135deg, #059669 0%, #10b981 100%);" onclick="abrirModalMovimiento('ajuste_positivo')">
                <i class="fas fa-plus-circle"></i>
                <span>Registrar Ingreso (+)</span>
            </button>

            <!-- Botón Registrar Egreso (-) -->
            <button type="button" class="btn text-white rounded-3 px-3 py-2 fw-semibold d-inline-flex align-items-center gap-2 shadow-sm" style="background: linear-gradient(135deg, #dc2626 0%, #ef4444 100%);" onclick="abrirModalMovimiento('ajuste_negativo')">
                <i class="fas fa-minus-circle"></i>
                <span>Registrar Egreso (-)</span>
            </button>

            <!-- Análisis de Costos y Margen -->
            <a href="{{ route('inventario.costos') }}" class="btn btn-outline-warning rounded-3 px-3 py-2 fw-semibold d-inline-flex align-items-center gap-2">
                <i class="fas fa-chart-line text-warning"></i>
                <span>Costos & Margen</span>
            </a>

            <!-- Inventario Valorizado -->
            <a href="{{ route('inventario.valorizado') }}" class="btn btn-outline-secondary rounded-3 px-3 py-2 fw-semibold d-inline-flex align-items-center gap-2">
                <i class="fas fa-file-invoice-dollar text-primary"></i>
                <span>Valorizado</span>
            </a>

            <!-- Kardex por Producto -->
            <a href="{{ route('inventario.kardex') }}" class="btn btn-outline-secondary rounded-3 px-3 py-2 fw-semibold d-inline-flex align-items-center gap-2">
                <i class="fas fa-list-check text-purple"></i>
                <span>Kardex Producto</span>
            </a>
        </div>
    </div>

    <!-- FLASH MESSAGES -->
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-4 mb-4" role="alert">
        <div class="d-flex align-items-center gap-2">
            <i class="fas fa-check-circle fs-5 text-success"></i>
            <div class="fw-semibold">{{ session('success') }}</div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm rounded-4 mb-4" role="alert">
        <div class="d-flex align-items-center gap-2">
            <i class="fas fa-exclamation-triangle fs-5 text-danger"></i>
            <div class="fw-semibold">{{ session('error') }}</div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <!-- 2. KPI STATS CARDS -->
    <div class="row g-3 mb-4">
        <!-- Stock Total Físico -->
        <div class="col-xl-3 col-sm-6">
            <div class="kpi-card">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="kpi-label">Stock Físico Total</div>
                        <div class="kpi-value text-dark">{{ number_format($totalUnidades) }} <span style="font-size: 0.9rem; font-weight: 600; color: #64748b;">unids</span></div>
                        <div class="kpi-subtext">{{ $totalProductos }} modelos de productos</div>
                    </div>
                    <div class="kpi-icon-box kpi-icon-indigo">
                        <i class="fas fa-boxes-stacked"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Inversión Total Valorizada -->
        <div class="col-xl-3 col-sm-6">
            <div class="kpi-card">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="kpi-label">Capital en Stock (Costo)</div>
                        <div class="kpi-value text-success" style="font-size: 1.3rem;">
                            Bs. {{ number_format($inversionTotal, 2) }}
                        </div>
                        <div class="kpi-subtext">Valor venta: Bs. {{ number_format($valorVentaTotal, 2) }}</div>
                    </div>
                    <div class="kpi-icon-box kpi-icon-emerald">
                        <i class="fas fa-coins"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Ingresos del Mes (+) -->
        <div class="col-xl-3 col-sm-6">
            <div class="kpi-card">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="kpi-label">Ingresos del Mes (+)</div>
                        <div class="kpi-value text-success">+{{ number_format($ingresosMes) }} <span style="font-size: 0.85rem; font-weight: 600;">unids</span></div>
                        <div class="kpi-subtext">Compras y ajustes positivos</div>
                    </div>
                    <div class="kpi-icon-box kpi-icon-emerald">
                        <i class="fas fa-arrow-down-long"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Egresos del Mes (-) -->
        <div class="col-xl-3 col-sm-6">
            <div class="kpi-card">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="kpi-label">Egresos del Mes (-)</div>
                        <div class="kpi-value text-danger">-{{ number_format($egresosMes) }} <span style="font-size: 0.85rem; font-weight: 600;">unids</span></div>
                        <div class="kpi-subtext">Ventas y mermas del mes</div>
                    </div>
                    <div class="kpi-icon-box kpi-icon-rose">
                        <i class="fas fa-arrow-up-long"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 3. PESTAÑAS DE NAVEGACIÓN ENTRE MÓDULOS -->
    <ul class="nav nav-tabs nav-tabs-modern mb-3">
        <li class="nav-item">
            <a class="nav-link active" href="{{ route('inventario.index') }}">
                <i class="fas fa-boxes-stacked me-1 text-primary"></i> Control de Stock & Existencias
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('inventario.movimientos') }}">
                <i class="fas fa-arrows-up-down me-1 text-info"></i> Ingresos y Egresos
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('inventario.costos') }}">
                <i class="fas fa-chart-line text-warning me-1"></i> Análisis de Costos & Margen
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('inventario.valorizado') }}">
                <i class="fas fa-file-invoice-dollar text-success me-1"></i> Inventario Valorizado
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('inventario.kardex') }}">
                <i class="fas fa-list-check text-purple me-1"></i> Kardex por Producto
            </a>
        </li>
    </ul>

    <!-- 4. PANEL DE FILTROS AVANZADOS -->
    <div class="filter-card">
        <div class="p-3 border-bottom bg-light d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div class="d-flex align-items-center gap-2">
                <i class="fas fa-filter text-primary"></i>
                <span class="fw-bold text-dark" style="font-size: 0.9rem;">Filtros de Trazabilidad y Kardex</span>
            </div>
            <span class="badge bg-light text-secondary border px-2 py-1" style="font-size: 0.75rem;">
                {{ $movimientos->total() }} movimientos registrados
            </span>
        </div>
        <div class="p-3">
            <form method="GET" action="{{ route('inventario.index') }}" class="row g-3 align-items-end">
                @if(request('tipo_filtro'))
                    <input type="hidden" name="tipo_filtro" value="{{ request('tipo_filtro') }}">
                @endif

                <!-- Buscador -->
                <div class="col-md-4 col-lg-3">
                    <label class="form-label small text-muted mb-1 fw-semibold">Búsqueda rápida</label>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-white border-end-0 text-muted">
                            <i class="fas fa-search"></i>
                        </span>
                        <input type="text" name="buscar" class="form-control border-start-0" placeholder="SKU, producto, motivo o usuario..." value="{{ request('buscar') }}">
                    </div>
                </div>

                <!-- Filtro por Producto -->
                <div class="col-md-3 col-lg-3">
                    <label class="form-label small text-muted mb-1 fw-semibold">Filtrar por Producto</label>
                    <select name="producto_id" class="form-select form-select-sm">
                        <option value="">Todos los productos</option>
                        @foreach($productos as $p)
                            <option value="{{ $p->id }}" {{ request('producto_id') == $p->id ? 'selected' : '' }}>
                                {{ $p->nombre }} [{{ $p->codigo }}]
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Fecha Desde -->
                <div class="col-md-2 col-lg-2">
                    <label class="form-label small text-muted mb-1 fw-semibold">Desde</label>
                    <input type="date" name="fecha_desde" class="form-control form-control-sm" value="{{ request('fecha_desde') }}">
                </div>

                <!-- Fecha Hasta -->
                <div class="col-md-2 col-lg-2">
                    <label class="form-label small text-muted mb-1 fw-semibold">Hasta</label>
                    <input type="date" name="fecha_hasta" class="form-control form-control-sm" value="{{ request('fecha_hasta') }}">
                </div>

                <!-- Botones Aplicar / Limpiar -->
                <div class="col-md-1 col-lg-2">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-sm btn-primary rounded-3 px-3 w-100 fw-semibold">
                            <i class="fas fa-search me-1"></i> Filtrar
                        </button>
                        <a href="{{ route('inventario.index') }}" class="btn btn-sm btn-outline-secondary rounded-3" title="Limpiar Filtros">
                            <i class="fas fa-undo"></i>
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- 5. TABLA KARDEX DE MOVIMIENTOS -->
    <div class="table-container-card">
        <div class="table-responsive">
            <table class="table table-custom align-middle">
                <thead>
                    <tr>
                        <th width="60" class="text-center">#</th>
                        <th>Fecha y Hora</th>
                        <th>Producto / Modelo</th>
                        <th>Tipo de Movimiento</th>
                        <th class="text-center">Cantidad</th>
                        <th class="text-center">Evolución Stock</th>
                        <th>Motivo / Referencia</th>
                        <th>Responsable</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($movimientos as $index => $mov)
                    @php
                        $tipoInfo = $mov->tipo_info;
                        $esIngreso = in_array($mov->tipo, ['compra', 'ajuste_positivo', 'anulacion_venta', 'inicial']);
                    @endphp
                    <tr>
                        <td class="text-center text-muted fw-bold">{{ $movimientos->firstItem() + $index }}</td>

                        <!-- Fecha y Hora -->
                        <td>
                            <div class="fw-semibold text-dark">{{ $mov->created_at->format('d/m/Y') }}</div>
                            <small class="text-muted">{{ $mov->created_at->format('H:i A') }}</small>
                        </td>

                        <!-- Producto -->
                        <td>
                            @if($mov->producto)
                            <div class="d-flex align-items-center gap-2">
                                <div style="width: 38px; height: 38px; border-radius: 8px; background: #f8fafc; border: 1px solid #e2e8f0; display: flex; align-items: center; justify-content: center; overflow: hidden; flex-shrink: 0;">
                                    @if($mov->producto->imagen_url)
                                        <img src="{{ $mov->producto->imagen_url }}" alt="" style="width: 100%; height: 100%; object-fit: cover;">
                                    @else
                                        <i class="fas fa-box-open text-muted small"></i>
                                    @endif
                                </div>
                                <div>
                                    <a href="{{ route('inventario.kardex', $mov->producto->id) }}" class="fw-bold text-dark text-decoration-none hover-primary" style="font-size: 0.88rem;">
                                        {{ $mov->producto->nombre }}
                                    </a>
                                    <div class="text-muted small font-monospace" style="font-size: 0.75rem;">
                                        SKU: {{ $mov->producto->codigo }}
                                    </div>
                                </div>
                            </div>
                            @else
                            <span class="text-muted small fst-italic">Producto eliminado</span>
                            @endif
                        </td>

                        <!-- Tipo de Movimiento -->
                        <td>
                            <span class="badge {{ $tipoInfo['badge_bs'] }} rounded-pill px-3 py-1" style="font-size: 0.75rem;">
                                <i class="{{ $tipoInfo['icon'] }} me-1"></i> {{ $tipoInfo['label'] }}
                            </span>
                        </td>

                        <!-- Cantidad -->
                        <td class="text-center">
                            @if($esIngreso)
                                <span class="badge-qty-in">
                                    <i class="fas fa-arrow-down text-success" style="font-size: 0.7rem;"></i> +{{ $mov->cantidad }}
                                </span>
                            @else
                                <span class="badge-qty-out">
                                    <i class="fas fa-arrow-up text-danger" style="font-size: 0.7rem;"></i> -{{ $mov->cantidad }}
                                </span>
                            @endif
                        </td>

                        <!-- Stock Anterior -> Stock Nuevo -->
                        <td class="text-center">
                            <div class="d-inline-flex align-items-center gap-2 px-2 py-1 rounded-3 bg-light border small font-monospace">
                                <span class="text-muted">{{ $mov->stock_anterior }}</span>
                                <i class="fas fa-arrow-right text-secondary" style="font-size: 0.65rem;"></i>
                                <span class="fw-bold {{ $mov->stock_nuevo > $mov->stock_anterior ? 'text-success' : 'text-danger' }}">{{ $mov->stock_nuevo }}</span>
                            </div>
                        </td>

                        <!-- Motivo / Referencia -->
                        <td>
                            <div class="text-dark small fw-medium" style="max-width: 250px;">
                                {{ $mov->motivo ?: 'Sin observación' }}
                            </div>
                        </td>

                        <!-- Responsable -->
                        <td>
                            <div class="d-flex align-items-center gap-1 small text-secondary">
                                <i class="fas fa-user-circle text-muted"></i>
                                <span>{{ $mov->usuario->name ?? 'Sistema' }}</span>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-5 text-muted">
                            <div class="py-4">
                                <div class="kpi-icon-box kpi-icon-indigo mx-auto mb-3" style="width: 56px; height: 56px;">
                                    <i class="fas fa-exchange-alt fs-4"></i>
                                </div>
                                <h6 class="fw-bold text-dark">No hay movimientos registrados</h6>
                                <p class="small text-muted mb-3">Los movimientos de stock se registran automáticamente con compras, ventas y ajustes manuales.</p>
                                <button type="button" class="btn btn-primary rounded-3 px-4 shadow-sm" onclick="abrirModalMovimiento('ajuste_positivo')">
                                    <i class="fas fa-plus me-1"></i> Registrar Primer Ingreso de Stock
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($movimientos->hasPages())
        <div class="p-3 border-top bg-light d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div class="small text-muted">
                Mostrando {{ $movimientos->firstItem() }} a {{ $movimientos->lastItem() }} de {{ $movimientos->total() }} registros
            </div>
            <div>
                {{ $movimientos->links('pagination::bootstrap-5') }}
            </div>
        </div>
        @endif
    </div>

</div>

<!-- MODAL: REGISTRAR INGRESO / EGRESO MANUAL -->
<div class="modal fade" id="modalAjusteStock" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow-lg overflow-hidden">
            <form action="{{ route('inventario.movimiento.store') }}" method="POST" id="formMovimiento">
                @csrf
                <div class="modal-header border-0 pb-0" id="modalHeaderBackground" style="background: linear-gradient(135deg, #059669 0%, #10b981 100%); color: #ffffff;">
                    <div class="d-flex align-items-center gap-2 py-2">
                        <div class="kpi-icon-box bg-white bg-opacity-20 text-white" style="width: 40px; height: 40px; font-size: 1.1rem; border-radius: 10px;">
                            <i id="modalHeaderIcon" class="fas fa-plus-circle"></i>
                        </div>
                        <div>
                            <h5 class="modal-title fw-bold text-white mb-0" id="modalHeaderTitle">Registrar Ingreso de Stock</h5>
                            <small class="text-white-50" id="modalHeaderSubtitle">Entrada directa de mercadería al inventario</small>
                        </div>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body p-4">
                    <!-- Selector de Tipo (Ingreso / Egreso) -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-secondary small mb-1">Tipo de Movimiento <span class="text-danger">*</span></label>
                        <div class="row g-2">
                            <div class="col-6">
                                <input type="radio" class="btn-check" name="tipo" id="tipoIngreso" value="ajuste_positivo" checked onchange="cambiarTipoMovimiento('ajuste_positivo')">
                                <label class="btn btn-outline-success w-100 rounded-3 py-2 fw-bold" for="tipoIngreso">
                                    <i class="fas fa-plus-circle me-1"></i> Ingreso (+)
                                </label>
                            </div>
                            <div class="col-6">
                                <input type="radio" class="btn-check" name="tipo" id="tipoEgreso" value="ajuste_negativo" onchange="cambiarTipoMovimiento('ajuste_negativo')">
                                <label class="btn btn-outline-danger w-100 rounded-3 py-2 fw-bold" for="tipoEgreso">
                                    <i class="fas fa-minus-circle me-1"></i> Egreso (-)
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Selector de Producto -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-secondary small mb-1">Seleccionar Producto <span class="text-danger">*</span></label>
                        <select name="producto_id" id="movProductoSelect" class="form-select rounded-3" required onchange="actualizarInfoProducto()">
                            <option value="">Seleccione un accesorio / producto...</option>
                            @foreach($productos as $prod)
                                <option value="{{ $prod->id }}" data-stock="{{ $prod->stock }}" data-costo="{{ $prod->precio_compra }}" data-codigo="{{ $prod->codigo }}">
                                    {{ $prod->nombre }} (SKU: {{ $prod->codigo }}) — Stock Actual: {{ $prod->stock }} u.
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Indicador de Stock Actual -->
                    <div id="stockInfoBox" class="p-3 rounded-3 bg-light border mb-3 d-none">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="small text-muted">Stock actual disponible:</span>
                            <span class="badge bg-dark font-monospace" id="stockActualBadge" style="font-size: 0.9rem;">0 u.</span>
                        </div>
                    </div>

                    <!-- Cantidad a Mover -->
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-secondary small mb-1">Cantidad de Unidades <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 rounded-start-3 text-muted">
                                    <i class="fas fa-cubes"></i>
                                </span>
                                <input type="number" min="1" name="cantidad" id="movCantidadInput" class="form-control border-start-0 rounded-end-3 fw-bold" placeholder="0" required>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-secondary small mb-1">Costo Unitario (Bs.)</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 rounded-start-3 text-muted">Bs.</span>
                                <input type="number" step="0.01" min="0" name="costo_unitario" id="movCostoInput" class="form-control border-start-0 rounded-end-3" placeholder="Opcional">
                            </div>
                        </div>
                    </div>

                    <!-- Motivo / Justificación -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-secondary small mb-1">Motivo / Justificación <span class="text-danger">*</span></label>
                        <select name="motivo" id="movMotivoSelect" class="form-select rounded-3 mb-2" required>
                            <option value="">Seleccione el motivo principal...</option>
                            <option value="Conteo Físico / Inventario Cíclico">Conteo Físico / Regularización de inventario</option>
                            <option value="Devolución de Cliente / Garantía">Devolución de Cliente / Retorno de Garantía</option>
                            <option value="Muestra Comercial / Exhibición">Muestra Comercial / Vitrina</option>
                            <option value="Merma / Equipo Dañado">Merma / Equipo dañado o defectuoso</option>
                            <option value="Uso Interno / Oficina">Uso Interno / Equipo para la empresa</option>
                            <option value="Corrección de Registro">Corrección de registro erróneo previo</option>
                            <option value="Otro Motivo">Otro motivo justificado</option>
                        </select>
                    </div>

                    <!-- Observaciones Adicionales -->
                    <div class="mb-0">
                        <label class="form-label fw-semibold text-secondary small mb-1">Detalle / Observaciones Adicionales</label>
                        <textarea name="observacion" class="form-control rounded-3" rows="2" placeholder="Agregue notas aclaratorias, número de serie, persona responsable..."></textarea>
                    </div>
                </div>

                <div class="modal-footer bg-light border-top p-3">
                    <button type="button" class="btn btn-outline-secondary rounded-3 px-4" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn text-white rounded-3 px-4 fw-semibold" id="btnGuardarMovimiento" style="background: linear-gradient(135deg, #059669 0%, #10b981 100%);">
                        <i class="fas fa-check me-1"></i> Guardar Movimiento
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- SCRIPTS DEL MÓDULO DE INVENTARIO -->
<script>
let modalAjuste = null;

document.addEventListener('DOMContentLoaded', function() {
    modalAjuste = new bootstrap.Modal(document.getElementById('modalAjusteStock'));
});

function abrirModalMovimiento(tipo = 'ajuste_positivo', productoId = null) {
    if (tipo === 'ajuste_positivo') {
        document.getElementById('tipoIngreso').checked = true;
    } else {
        document.getElementById('tipoEgreso').checked = true;
    }
    cambiarTipoMovimiento(tipo);

    if (productoId) {
        document.getElementById('movProductoSelect').value = productoId;
        actualizarInfoProducto();
    }

    modalAjuste.show();
}

function cambiarTipoMovimiento(tipo) {
    const header = document.getElementById('modalHeaderBackground');
    const icon = document.getElementById('modalHeaderIcon');
    const title = document.getElementById('modalHeaderTitle');
    const subtitle = document.getElementById('modalHeaderSubtitle');
    const btnSubmit = document.getElementById('btnGuardarMovimiento');

    if (tipo === 'ajuste_positivo') {
        header.style.background = 'linear-gradient(135deg, #059669 0%, #10b981 100%)';
        icon.className = 'fas fa-plus-circle';
        title.textContent = 'Registrar Ingreso de Stock (+)';
        subtitle.textContent = 'Entrada directa o ajuste positivo de mercadería';
        btnSubmit.style.background = 'linear-gradient(135deg, #059669 0%, #10b981 100%)';
    } else {
        header.style.background = 'linear-gradient(135deg, #dc2626 0%, #ef4444 100%)';
        icon.className = 'fas fa-minus-circle';
        title.textContent = 'Registrar Egreso de Stock (-)';
        subtitle.textContent = 'Salida por merma, daño o ajuste negativo';
        btnSubmit.style.background = 'linear-gradient(135deg, #dc2626 0%, #ef4444 100%)';
    }
}

function actualizarInfoProducto() {
    const select = document.getElementById('movProductoSelect');
    const selectedOption = select.options[select.selectedIndex];
    const infoBox = document.getElementById('stockInfoBox');
    const badge = document.getElementById('stockActualBadge');
    const costoInput = document.getElementById('movCostoInput');

    if (select.value) {
        const stock = selectedOption.getAttribute('data-stock') || '0';
        const costo = selectedOption.getAttribute('data-costo') || '';
        badge.textContent = `${stock} unidad(es)`;
        costoInput.value = costo;
        infoBox.classList.remove('d-none');
    } else {
        infoBox.classList.add('d-none');
        costoInput.value = '';
    }
}
</script>

@endsection
