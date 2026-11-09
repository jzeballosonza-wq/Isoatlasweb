@extends('dashboard')

@section('content')

<style>
    .movimientos-container {
        padding: 1.5rem 1rem;
    }

    .kpi-card-mov {
        background: #ffffff;
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
        padding: 1.25rem;
        transition: all 0.25s ease;
        position: relative;
        overflow: hidden;
    }

    .kpi-card-mov:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 25px -5px rgba(0, 0, 0, 0.08);
    }

    .kpi-icon-box-emerald {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.35rem;
        color: #ffffff;
        background: linear-gradient(135deg, #059669 0%, #10b981 100%);
        box-shadow: 0 6px 15px rgba(16, 185, 129, 0.25);
    }

    .kpi-icon-box-rose {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.35rem;
        color: #ffffff;
        background: linear-gradient(135deg, #dc2626 0%, #ef4444 100%);
        box-shadow: 0 6px 15px rgba(239, 68, 68, 0.25);
    }

    .kpi-icon-box-sky {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.35rem;
        color: #ffffff;
        background: linear-gradient(135deg, #0284c7 0%, #38bdf8 100%);
        box-shadow: 0 6px 15px rgba(2, 132, 199, 0.25);
    }

    .kpi-icon-box-amber {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.35rem;
        color: #ffffff;
        background: linear-gradient(135deg, #d97706 0%, #f59e0b 100%);
        box-shadow: 0 6px 15px rgba(245, 158, 11, 0.25);
    }

    .kpi-label {
        font-size: 0.78rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: #64748b;
        margin-bottom: 3px;
    }

    .kpi-value {
        font-size: 1.45rem;
        font-weight: 800;
        color: #0f172a;
        line-height: 1.2;
    }

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

    .nav-tabs-mov {
        border-bottom: 2px solid #e2e8f0;
        gap: 0.5rem;
    }

    .nav-tabs-mov .nav-link {
        border: none;
        color: #64748b;
        font-weight: 600;
        font-size: 0.9rem;
        padding: 0.75rem 1.25rem;
        border-radius: 10px 10px 0 0;
        transition: all 0.2s ease;
        position: relative;
    }

    .nav-tabs-mov .nav-link:hover {
        color: #0f172a;
        background: #f1f5f9;
    }

    .nav-tabs-mov .nav-link.active {
        color: #0284c7;
        background: transparent;
    }

    .nav-tabs-mov .nav-link.active::after {
        content: '';
        position: absolute;
        bottom: -2px;
        left: 0;
        right: 0;
        height: 3px;
        background: #0284c7;
        border-radius: 3px 3px 0 0;
    }
</style>

<div class="movimientos-container">

    <!-- 1. ENCABEZADO Y ACCIONES PRINCIPALES -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <div style="width: 38px; height: 38px; border-radius: 10px; background: linear-gradient(135deg, #0284c7 0%, #10b981 100%); color: #fff; display: flex; align-items: center; justify-content: center; font-size: 1.15rem; box-shadow: 0 6px 15px rgba(2, 132, 199, 0.25);">
                    <i class="fas fa-arrows-up-down"></i>
                </div>
                <h2 class="h4 fw-bold mb-0 text-dark">Módulo de Ingresos y Egresos</h2>
                <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1 ms-2" style="font-size: 0.75rem;">
                    Kardex & Movimientos de Stock
                </span>
            </div>
            <p class="text-muted small mb-0">
                Control unificado de entradas y salidas de productos y accesorios: compras a proveedores, salidas por ventas, mermas técnicas y ajustes manuales.
            </p>
        </div>

        <div class="d-flex flex-wrap align-items-center gap-2">
            <!-- Botón Registrar Ingreso (+) -->
            <button type="button" class="btn text-white rounded-3 px-3 py-2 fw-semibold d-inline-flex align-items-center gap-2 shadow-sm" style="background: linear-gradient(135deg, #059669 0%, #10b981 100%);" data-bs-toggle="modal" data-bs-target="#modalIngresoManual">
                <i class="fas fa-plus-circle"></i>
                <span>Registrar Ingreso (+)</span>
            </button>

            <!-- Botón Registrar Egreso (-) -->
            <button type="button" class="btn text-white rounded-3 px-3 py-2 fw-semibold d-inline-flex align-items-center gap-2 shadow-sm" style="background: linear-gradient(135deg, #dc2626 0%, #ef4444 100%);" data-bs-toggle="modal" data-bs-target="#modalEgresoManual">
                <i class="fas fa-minus-circle"></i>
                <span>Registrar Egreso (-)</span>
            </button>

            <!-- Enlace a Control de Inventario -->
            <a href="{{ route('inventario.index') }}" class="btn btn-outline-secondary rounded-3 px-3 py-2 fw-semibold d-inline-flex align-items-center gap-2">
                <i class="fas fa-boxes-stacked"></i>
                <span>Control de Inventario</span>
            </a>

            <!-- Enlace a Análisis de Costos -->
            <a href="{{ route('inventario.costos') }}" class="btn btn-outline-warning rounded-3 px-3 py-2 fw-semibold d-inline-flex align-items-center gap-2">
                <i class="fas fa-chart-line text-warning"></i>
                <span>Costos & Margen</span>
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

    <!-- 2. KPI STATS CARDS UNIFICADOS -->
    <div class="row g-3 mb-4">
        <!-- Ingresos del Mes (+) -->
        <div class="col-xl-3 col-sm-6">
            <div class="kpi-card-mov">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="kpi-label text-success">Ingresos del Mes (+)</div>
                        <div class="kpi-value text-success">+{{ number_format($ingresosMes) }} <span style="font-size: 0.85rem; font-weight: 600;">unids</span></div>
                        <div class="text-muted small mt-1">Compras y ajustes positivos</div>
                    </div>
                    <div class="kpi-icon-box-emerald">
                        <i class="fas fa-arrow-down-long"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Egresos del Mes (-) -->
        <div class="col-xl-3 col-sm-6">
            <div class="kpi-card-mov">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="kpi-label text-danger">Egresos del Mes (-)</div>
                        <div class="kpi-value text-danger">-{{ number_format($egresosMes) }} <span style="font-size: 0.85rem; font-weight: 600;">unids</span></div>
                        <div class="text-muted small mt-1">{{ number_format($unidadesVentasMes) }} ventas / {{ number_format($unidadesMermasMes) }} mermas</div>
                    </div>
                    <div class="kpi-icon-box-rose">
                        <i class="fas fa-arrow-up-long"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Balance Neto de Stock (Mes) -->
        <div class="col-xl-3 col-sm-6">
            <div class="kpi-card-mov">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="kpi-label">Balance Neto Stock (Mes)</div>
                        <div class="kpi-value {{ $balanceNetoStock >= 0 ? 'text-primary' : 'text-danger' }}">
                            {{ $balanceNetoStock >= 0 ? '+' : '' }}{{ number_format($balanceNetoStock) }} <span style="font-size: 0.85rem; font-weight: 600;">unids</span>
                        </div>
                        <div class="text-muted small mt-1">Diferencial entradas vs salidas</div>
                    </div>
                    <div class="kpi-icon-box-sky">
                        <i class="fas fa-scale-balanced"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Inversión en Entradas -->
        <div class="col-xl-3 col-sm-6">
            <div class="kpi-card-mov">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="kpi-label">Inversión Ingresos (Mes)</div>
                        <div class="kpi-value text-dark" style="font-size: 1.3rem;">
                            Bs. {{ number_format($inversionIngresosMes, 2) }}
                        </div>
                        <div class="text-muted small mt-1">Costo valorizado de abastecimiento</div>
                    </div>
                    <div class="kpi-icon-box-amber">
                        <i class="fas fa-coins"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 3. PESTAÑAS DE VISTA RÁPIDA -->
    <ul class="nav nav-tabs nav-tabs-mov mb-3">
        <li class="nav-item">
            <a class="nav-link {{ $tab === 'todos' ? 'active' : '' }}" href="{{ route('inventario.movimientos', ['tab' => 'todos']) }}">
                <i class="fas fa-list me-1"></i> Todos los Movimientos
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $tab === 'ingresos' ? 'active' : '' }}" href="{{ route('inventario.movimientos', ['tab' => 'ingresos']) }}">
                <i class="fas fa-arrow-circle-down text-success me-1"></i> Solo Ingresos (+)
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $tab === 'egresos' ? 'active' : '' }}" href="{{ route('inventario.movimientos', ['tab' => 'egresos']) }}">
                <i class="fas fa-arrow-circle-up text-danger me-1"></i> Solo Egresos (-)
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $tab === 'mermas' ? 'active' : '' }}" href="{{ route('inventario.movimientos', ['tab' => 'mermas']) }}">
                <i class="fas fa-triangle-exclamation text-warning me-1"></i> Mermas / Bajas (-)
            </a>
        </li>
    </ul>

    <!-- 4. FILTROS AVANZADOS -->
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-3">
            <form method="GET" action="{{ route('inventario.movimientos') }}" class="row g-3 align-items-end">
                <input type="hidden" name="tab" value="{{ $tab }}">

                <!-- Buscador rápido -->
                <div class="col-md-3">
                    <label class="form-label small text-muted mb-1 fw-semibold">Búsqueda rápida</label>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-white border-end-0 text-muted">
                            <i class="fas fa-search"></i>
                        </span>
                        <input type="text" name="buscar" class="form-control border-start-0" placeholder="SKU, producto, motivo..." value="{{ request('buscar') }}">
                    </div>
                </div>

                <!-- Tipo específico -->
                <div class="col-md-2">
                    <label class="form-label small text-muted mb-1 fw-semibold">Tipo Específico</label>
                    <select name="tipo_especifico" class="form-select form-select-sm">
                        <option value="">Todos los tipos</option>
                        <option value="compra" {{ request('tipo_especifico') === 'compra' ? 'selected' : '' }}>🟢 Compra a proveedor</option>
                        <option value="venta" {{ request('tipo_especifico') === 'venta' ? 'selected' : '' }}>🔴 Salida por venta</option>
                        <option value="ajuste_positivo" {{ request('tipo_especifico') === 'ajuste_positivo' ? 'selected' : '' }}>🟢 Ajuste positivo</option>
                        <option value="ajuste_negativo" {{ request('tipo_especifico') === 'ajuste_negativo' ? 'selected' : '' }}>🔴 Merma / Baja técnica</option>
                        <option value="anulacion_venta" {{ request('tipo_especifico') === 'anulacion_venta' ? 'selected' : '' }}>🟡 Devolución venta</option>
                    </select>
                </div>

                <!-- Filtrar por producto -->
                <div class="col-md-3">
                    <label class="form-label small text-muted mb-1 fw-semibold">Producto</label>
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
                <div class="col-md-2">
                    <label class="form-label small text-muted mb-1 fw-semibold">Desde</label>
                    <input type="date" name="fecha_desde" class="form-control form-control-sm" value="{{ request('fecha_desde') }}">
                </div>

                <!-- Fecha Hasta -->
                <div class="col-md-2">
                    <label class="form-label small text-muted mb-1 fw-semibold">Hasta</label>
                    <div class="d-flex gap-2">
                        <input type="date" name="fecha_hasta" class="form-control form-control-sm" value="{{ request('fecha_hasta') }}">
                        <button type="submit" class="btn btn-sm btn-primary px-3 fw-semibold">
                            <i class="fas fa-filter"></i>
                        </button>
                        @if(request()->anyFilled(['buscar', 'tipo_especifico', 'producto_id', 'fecha_desde', 'fecha_hasta']))
                        <a href="{{ route('inventario.movimientos', ['tab' => $tab]) }}" class="btn btn-sm btn-light border" title="Limpiar filtros">
                            <i class="fas fa-times"></i>
                        </a>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- 5. TABLA UNIFICADA DE MOVIMIENTOS -->
    <div class="table-container-card">
        <div class="p-3 border-bottom d-flex justify-content-between align-items-center bg-light">
            <div class="fw-bold text-dark d-flex align-items-center gap-2">
                <i class="fas fa-receipt text-primary"></i>
                <span>Historial de Movimientos de Inventario</span>
            </div>
            <span class="badge bg-light text-secondary border px-2 py-1" style="font-size: 0.78rem;">
                {{ $movimientos->total() }} movimientos registrados
            </span>
        </div>

        <div class="table-responsive">
            <table class="table table-custom table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th>Fecha & Hora</th>
                        <th>Producto / Accesorio</th>
                        <th>Tipo de Movimiento</th>
                        <th class="text-center">Cantidad</th>
                        <th class="text-end">Costo / Precio Unit.</th>
                        <th>Motivo / Comprobante</th>
                        <th>Responsable</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($movimientos as $mov)
                    @php
                        $esIngreso = in_array($mov->tipo, ['compra', 'ajuste_positivo', 'anulacion_venta', 'inicial']);
                        $esVenta = $mov->tipo === 'venta';
                        $cant = abs($mov->cantidad);
                    @endphp
                    <tr>
                        <td>
                            <div class="fw-semibold text-dark">{{ $mov->created_at->format('d/m/Y') }}</div>
                            <small class="text-muted">{{ $mov->created_at->format('H:i') }}</small>
                        </td>
                        <td>
                            @if($mov->producto)
                                <div class="fw-bold text-dark">{{ $mov->producto->nombre }}</div>
                                <small class="text-muted font-monospace">{{ $mov->producto->codigo }}</small>
                                @if($mov->producto->categoria)
                                    <span class="badge bg-light text-secondary border ms-1" style="font-size: 0.7rem;">
                                        {{ $mov->producto->categoria->nombre }}
                                    </span>
                                @endif
                            @else
                                <span class="text-muted fst-italic">Producto eliminado</span>
                            @endif
                        </td>
                        <td>
                            @if($mov->tipo === 'compra')
                                <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1">
                                    <i class="fas fa-shopping-cart me-1"></i> Compra a Proveedor
                                </span>
                            @elseif($mov->tipo === 'venta')
                                <span class="badge bg-info-subtle text-info border border-info-subtle px-2 py-1">
                                    <i class="fas fa-cash-register me-1"></i> Despacho por Venta
                                </span>
                            @elseif($mov->tipo === 'ajuste_positivo')
                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1">
                                    <i class="fas fa-plus-circle me-1"></i> Ingreso Manual (+)
                                </span>
                            @elseif($mov->tipo === 'ajuste_negativo')
                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1">
                                    <i class="fas fa-minus-circle me-1"></i> Merma / Baja (-)
                                </span>
                            @elseif($mov->tipo === 'anulacion_venta')
                                <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle px-2 py-1">
                                    <i class="fas fa-undo me-1"></i> Devolución de Venta
                                </span>
                            @else
                                <span class="badge bg-secondary px-2 py-1">{{ ucfirst($mov->tipo) }}</span>
                            @endif
                        </td>
                        <td class="text-center">
                            @if($esIngreso)
                                <span class="badge-qty-in">+{{ $cant }}</span>
                            @else
                                <span class="badge-qty-out">-{{ $cant }}</span>
                            @endif
                            <div class="small text-muted mt-1" style="font-size: 0.72rem;">
                                {{ $mov->stock_anterior }} &rarr; <strong>{{ $mov->stock_nuevo }}</strong>
                            </div>
                        </td>
                        <td class="text-end">
                            @if($esVenta)
                                <span class="fw-bold text-dark">Bs. {{ number_format($mov->precio_unitario ?? 0, 2) }}</span>
                                <div class="small text-muted" style="font-size: 0.7rem;">(Precio Venta)</div>
                            @else
                                <span class="text-muted">Bs. {{ number_format($mov->costo_unitario ?? 0, 2) }}</span>
                                <div class="small text-muted" style="font-size: 0.7rem;">(Costo Compra)</div>
                            @endif
                        </td>
                        <td>
                            <div class="text-dark" style="max-width: 250px; font-size: 0.85rem;">
                                {{ $mov->motivo ?: 'Sin descripción' }}
                            </div>
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div style="width: 26px; height: 26px; border-radius: 6px; background: #e2e8f0; display: flex; align-items: center; justify-content: center; font-size: 0.7rem; font-weight: 700;">
                                    {{ strtoupper(substr($mov->usuario->name ?? 'S', 0, 1)) }}
                                </div>
                                <small class="text-dark fw-semibold">{{ $mov->usuario->name ?? 'Sistema' }}</small>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5">
                            <i class="fas fa-inbox fs-1 text-muted opacity-50 mb-3 d-block"></i>
                            <h6 class="text-secondary fw-semibold">No se encontraron movimientos registrados</h6>
                            <p class="text-muted small mb-0">No hay entradas ni salidas que coincidan con los filtros seleccionados.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($movimientos->hasPages())
        <div class="p-3 border-top d-flex justify-content-end bg-light">
            {{ $movimientos->links() }}
        </div>
        @endif
    </div>

</div>

<!-- MODAL 1: REGISTRAR INGRESO MANUAL (+) -->
<div class="modal fade" id="modalIngresoManual" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-0 bg-success text-white rounded-top-4 px-4 py-3">
                <div class="d-flex align-items-center gap-2">
                    <i class="fas fa-plus-circle fs-5"></i>
                    <h5 class="modal-title fw-bold">Registrar Ingreso Manual (+)</h5>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('inventario.movimiento.store') }}" method="POST">
                @csrf
                <input type="hidden" name="tipo" value="ajuste_positivo">

                <div class="modal-body p-4">
                    <div class="alert alert-success-subtle text-success-emphasis border border-success-subtle rounded-3 small p-3 mb-3">
                        <i class="fas fa-info-circle me-1"></i>
                        Esta acción incrementará directamente el stock físico del producto en el inventario.
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small text-dark">Producto / Accesorio <span class="text-danger">*</span></label>
                        <select name="producto_id" class="form-select" required>
                            <option value="">-- Seleccionar producto --</option>
                            @foreach($productos as $p)
                                <option value="{{ $p->id }}">
                                    {{ $p->nombre }} [Stock: {{ $p->stock }} | Costo: Bs. {{ number_format($p->precio_compra, 2) }}]
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-dark">Cantidad a Ingresar <span class="text-danger">*</span></label>
                            <input type="number" name="cantidad" class="form-control" min="1" placeholder="Ej: 5" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-dark">Costo Unitario (Bs.)</label>
                            <input type="number" step="0.01" name="costo_unitario" class="form-control" placeholder="Opcional">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small text-dark">Motivo o Justificación <span class="text-danger">*</span></label>
                        <select name="motivo" class="form-select" required>
                            <option value="">-- Seleccione motivo --</option>
                            <option value="Conteo de inventario físico (Sobrante)">Conteo de inventario físico (Sobrante)</option>
                            <option value="Recepción de mercadería extraordinaria">Recepción de mercadería extraordinaria</option>
                            <option value="Devolución de cliente aprobada">Devolución de cliente aprobada</option>
                            <option value="Stock inicial por inventario físico">Stock inicial por inventario físico</option>
                            <option value="Ajuste administrativo autorizado">Ajuste administrativo autorizado</option>
                        </select>
                    </div>

                    <div class="mb-0">
                        <label class="form-label fw-bold small text-dark">Observaciones / Notas</label>
                        <textarea name="observacion" class="form-control" rows="2" placeholder="Detalles de la entrada o comprobante..."></textarea>
                    </div>
                </div>

                <div class="modal-footer border-0 bg-light rounded-bottom-4 px-4 py-3">
                    <button type="button" class="btn btn-secondary rounded-3 px-3" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success rounded-3 px-4 fw-bold">
                        <i class="fas fa-check-circle me-1"></i> Confirmar Ingreso
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- MODAL 2: REGISTRAR EGRESO / MERMA (-) -->
<div class="modal fade" id="modalEgresoManual" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-0 bg-danger text-white rounded-top-4 px-4 py-3">
                <div class="d-flex align-items-center gap-2">
                    <i class="fas fa-minus-circle fs-5"></i>
                    <h5 class="modal-title fw-bold">Registrar Egreso / Baja de Stock (-)</h5>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('inventario.movimiento.store') }}" method="POST">
                @csrf
                <input type="hidden" name="tipo" value="ajuste_negativo">

                <div class="modal-body p-4">
                    <div class="alert alert-danger-subtle text-danger-emphasis border border-danger-subtle rounded-3 small p-3 mb-3">
                        <i class="fas fa-exclamation-triangle me-1"></i>
                        Esta acción restará unidades del stock disponible del producto.
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small text-dark">Producto / Accesorio <span class="text-danger">*</span></label>
                        <select name="producto_id" class="form-select" required>
                            <option value="">-- Seleccionar producto --</option>
                            @foreach($productos as $p)
                                <option value="{{ $p->id }}" {{ $p->stock <= 0 ? 'disabled' : '' }}>
                                    {{ $p->nombre }} [Stock disponible: {{ $p->stock }}]
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small text-dark">Cantidad a Retirar <span class="text-danger">*</span></label>
                        <input type="number" name="cantidad" class="form-control" min="1" placeholder="Ej: 1" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small text-dark">Motivo o Causa de la Baja <span class="text-danger">*</span></label>
                        <select name="motivo" class="form-select" required>
                            <option value="">-- Seleccione causa --</option>
                            <option value="Merma o daño físico (golpe/pantalla averiada)">Merma o daño físico (golpe/pantalla averiada)</option>
                            <option value="Falla técnica de fábrica / Garantía a servicio">Falla técnica de fábrica / Garantía a servicio</option>
                            <option value="Ajuste por faltante en conteo de inventario">Ajuste por faltante en conteo de inventario</option>
                            <option value="Equipo retirado para muestra o exhibición">Equipo retirado para muestra o exhibición</option>
                            <option value="Obsolescencia o baja definitiva de almacén">Obsolescencia o baja definitiva de almacén</option>
                        </select>
                    </div>

                    <div class="mb-0">
                        <label class="form-label fw-bold small text-dark">Observaciones / Detalle Técnico</label>
                        <textarea name="observacion" class="form-control" rows="2" placeholder="Número de serie o detalle de avería..."></textarea>
                    </div>
                </div>

                <div class="modal-footer border-0 bg-light rounded-bottom-4 px-4 py-3">
                    <button type="button" class="btn btn-secondary rounded-3 px-3" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger rounded-3 px-4 fw-bold">
                        <i class="fas fa-check-circle me-1"></i> Confirmar Salida
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
