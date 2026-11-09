@extends('dashboard')

@section('content')

<style>
    .valorizado-container {
        padding: 1.5rem 1rem;
    }

    .report-hero-card {
        background: #ffffff;
        border-radius: 20px;
        border: 1px solid #f1f5f9;
        box-shadow: 0 4px 25px rgba(0, 0, 0, 0.03);
        padding: 1.75rem;
        margin-bottom: 1.5rem;
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
    }

    .table-custom tbody td {
        padding: 0.85rem 1.2rem;
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9;
        font-size: 0.88rem;
    }

    .table-custom tbody tr:hover td {
        background-color: #f8fafc;
    }

    @media print {
        .main-sidebar, .main-header, .btn, .filter-card, .breadcrumb-nav {
            display: none !important;
        }
        .content-wrapper {
            margin-left: 0 !important;
            padding: 0 !important;
            background: #ffffff !important;
        }
    }
</style>

<div class="valorizado-container">

    <!-- BREADCRUMB / VOLVER -->
    <div class="mb-3 d-flex justify-content-between align-items-center breadcrumb-nav">
        <a href="{{ route('inventario.index') }}" class="text-decoration-none text-muted small fw-semibold d-inline-flex align-items-center gap-1">
            <i class="fas fa-arrow-left"></i> Volver a Control de Inventario
        </a>

        <div class="d-flex gap-2">
            <button type="button" class="btn btn-sm btn-outline-secondary rounded-3 px-3" onclick="window.print()">
                <i class="fas fa-print me-1"></i> Imprimir Reporte
            </button>
            <a href="{{ route('productos.create') }}" class="btn btn-sm btn-primary rounded-3 px-3 shadow-sm">
                <i class="fas fa-plus me-1"></i> Nuevo Producto
            </a>
        </div>
    </div>

    <!-- 1. ENCABEZADO REPORTE -->
    <div class="report-hero-card">
        <div class="d-flex align-items-center gap-3">
            <div class="kpi-icon-box" style="width: 48px; height: 48px; background: linear-gradient(135deg, #059669 0%, #10b981 100%); color: #fff; border-radius: 14px; display: flex; align-items: center; justify-content: center; font-size: 1.35rem;">
                <i class="fas fa-file-invoice-dollar"></i>
            </div>
            <div>
                <h2 class="h4 fw-bold text-dark mb-0">Inventario Físico y Valorizado</h2>
                <p class="text-muted small mb-0">Estado patrimonial de existencias, valuación de activos a costo y margen proyectado de venta.</p>
            </div>
        </div>
    </div>

    <!-- 2. RESUMEN FINANCIERO -->
    <div class="row g-3 mb-4">
        <!-- Total Modelos -->
        <div class="col-xl-3 col-sm-6">
            <div class="kpi-card">
                <div class="kpi-label">Modelos Registrados</div>
                <div class="kpi-value text-dark">{{ $totalItems }} <span style="font-size: 0.85rem; color: #64748b;">SKUs</span></div>
                <div class="kpi-subtext">{{ number_format($totalUnidades) }} unidades físicas totales</div>
            </div>
        </div>

        <!-- Inversión Total (Costo) -->
        <div class="col-xl-3 col-sm-6">
            <div class="kpi-card">
                <div class="kpi-label">Capital Invertido (Costo)</div>
                <div class="kpi-value text-primary" style="font-size: 1.3rem;">
                    Bs. {{ number_format($totalInversion, 2) }}
                </div>
                <div class="kpi-subtext">Costo adquisición en almacén</div>
            </div>
        </div>

        <!-- Valoración a Precio Venta -->
        <div class="col-xl-3 col-sm-6">
            <div class="kpi-card">
                <div class="kpi-label">Valorización Comercial (Venta)</div>
                <div class="kpi-value text-dark" style="font-size: 1.3rem;">
                    Bs. {{ number_format($totalValorVenta, 2) }}
                </div>
                <div class="kpi-subtext">Valor proyectado de facturación</div>
            </div>
        </div>

        <!-- Margen Bruto Proyectado -->
        <div class="col-xl-3 col-sm-6">
            <div class="kpi-card">
                <div class="kpi-label">Ganancia Bruta Proyectada</div>
                <div class="kpi-value text-success" style="font-size: 1.3rem;">
                    Bs. {{ number_format($totalGananciaProyectada, 2) }}
                </div>
                <div class="kpi-subtext">
                    {{ $totalInversion > 0 ? round(($totalGananciaProyectada / $totalInversion) * 100, 1) : 0 }}% rentabilidad estimada
                </div>
            </div>
        </div>
    </div>

    <!-- 3. TABLA DETALLADA DE PRODUCTOS VALORIZADOS -->
    <div class="table-container-card">
        <div class="table-responsive">
            <table class="table table-custom align-middle">
                <thead>
                    <tr>
                        <th width="50" class="text-center">#</th>
                        <th>Código SKU</th>
                        <th>Producto / Modelo</th>
                        <th>Categoría</th>
                        <th class="text-center">Stock Actual</th>
                        <th class="text-end">Costo Unit.</th>
                        <th class="text-end">P. Venta Unit.</th>
                        <th class="text-end">Inversión (Bs.)</th>
                        <th class="text-end">Valor Venta (Bs.)</th>
                        <th class="text-end">Margen Estimado</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($productos as $key => $p)
                    @php
                        $inversionItem = $p->stock * $p->precio_compra;
                        $ventaItem = $p->stock * $p->precio_venta;
                        $margenItem = max(0, $ventaItem - $inversionItem);
                    @endphp
                    <tr>
                        <td class="text-center text-muted fw-bold">{{ $key + 1 }}</td>
                        <td>
                            <span class="badge bg-light text-dark border font-monospace">{{ $p->codigo }}</span>
                        </td>
                        <td>
                            <a href="{{ route('inventario.kardex', $p->id) }}" class="fw-bold text-dark text-decoration-none hover-primary">
                                {{ $p->nombre }}
                            </a>
                        </td>
                        <td>
                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-2 py-1 small">
                                {{ $p->categoria->nombre ?? 'General' }}
                            </span>
                        </td>
                        <td class="text-center">
                            <span class="badge {{ $p->stock <= ($p->stock_minimo ?? 5) ? 'bg-warning-subtle text-warning-emphasis border border-warning-subtle' : 'bg-light text-dark border' }} rounded-pill px-2 py-1 font-monospace">
                                {{ $p->stock }} u.
                            </span>
                        </td>
                        <td class="text-end text-muted small">
                            Bs. {{ number_format($p->precio_compra, 2) }}
                        </td>
                        <td class="text-end fw-semibold text-dark">
                            Bs. {{ number_format($p->precio_venta, 2) }}
                        </td>
                        <td class="text-end fw-bold text-primary">
                            Bs. {{ number_format($inversionItem, 2) }}
                        </td>
                        <td class="text-end fw-bold text-dark">
                            Bs. {{ number_format($ventaItem, 2) }}
                        </td>
                        <td class="text-end fw-bold text-success">
                            Bs. {{ number_format($margenItem, 2) }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="text-center py-5 text-muted">
                            No hay productos registrados en el inventario.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr class="table-light fw-bold">
                        <td colspan="4" class="text-end">TOTALES CONSOLIDADOS:</td>
                        <td class="text-center font-monospace">{{ number_format($totalUnidades) }} u.</td>
                        <td colspan="2"></td>
                        <td class="text-end text-primary">Bs. {{ number_format($totalInversion, 2) }}</td>
                        <td class="text-end text-dark">Bs. {{ number_format($totalValorVenta, 2) }}</td>
                        <td class="text-end text-success">Bs. {{ number_format($totalGananciaProyectada, 2) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

</div>

@endsection
