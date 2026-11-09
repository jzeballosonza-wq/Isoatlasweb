@extends('dashboard')

@section('content')

<style>
    .show-prov-container {
        padding: 1.5rem 1rem;
    }

    .prov-hero-profile {
        background: #ffffff;
        border-radius: 20px;
        border: 1px solid #f1f5f9;
        box-shadow: 0 4px 25px rgba(0, 0, 0, 0.03);
        padding: 1.75rem;
        margin-bottom: 1.5rem;
        position: relative;
    }

    .prov-profile-avatar {
        width: 68px;
        height: 68px;
        border-radius: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.6rem;
        font-weight: 800;
        color: #ffffff;
        flex-shrink: 0;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12);
    }

    .kpi-mini-card {
        background: #ffffff;
        border-radius: 16px;
        border: 1px solid #f1f5f9;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
        padding: 1.25rem;
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
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
        padding: 1rem 1.2rem;
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9;
        font-size: 0.88rem;
    }

    .table-custom tbody tr:hover td {
        background-color: #f8fafc;
    }
</style>

<div class="show-prov-container">

    <!-- BREADCRUMB / VOLVER -->
    <div class="mb-3 d-flex justify-content-between align-items-center">
        <a href="{{ route('proveedores.index') }}" class="text-decoration-none text-muted small fw-semibold d-inline-flex align-items-center gap-1">
            <i class="fas fa-arrow-left"></i> Volver al directorio de proveedores
        </a>

        <div class="d-flex gap-2">
            <a href="{{ route('proveedores.edit', $proveedore->id) }}" class="btn btn-sm btn-outline-warning rounded-3 px-3 fw-semibold">
                <i class="fas fa-edit me-1"></i> Editar Información
            </a>
            @if(auth()->user()->tienePermiso('compras'))
            <a href="{{ route('compras.create') }}" class="btn btn-sm text-white rounded-3 px-3 fw-semibold shadow-sm" style="background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%);">
                <i class="fas fa-shopping-cart me-1"></i> Nueva Compra a este Proveedor
            </a>
            @endif
        </div>
    </div>

    <!-- 1. HERO PROFILE CARD -->
    <div class="prov-hero-profile">
        <div class="row align-items-center g-3">
            <div class="col-lg-7">
                <div class="d-flex align-items-center gap-3">
                    <div class="prov-profile-avatar" style="background: {{ $proveedore->avatar_gradient }};">
                        {{ $proveedore->iniciales }}
                    </div>
                    <div>
                        <div class="d-flex align-items-center gap-2 flex-wrap">
                            <h2 class="h4 fw-bold text-dark mb-0">{{ $proveedore->nombre }}</h2>
                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-2 py-1 small">
                                <i class="fas fa-truck-loading me-1"></i> Proveedor Oficial
                            </span>
                        </div>
                        <div class="text-muted small mt-1 d-flex align-items-center gap-2 flex-wrap">
                            <span><i class="fas fa-map-marker-alt text-danger me-1"></i> {{ $proveedore->direccion }}</span>
                            @if($proveedore->contacto)
                                <span>•</span>
                                <span><i class="fas fa-user-tie text-secondary me-1"></i> {{ $proveedore->contacto }}</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- CANALES DE CONTACTO RÁPIDO -->
            <div class="col-lg-5">
                <div class="d-flex flex-wrap justify-content-lg-end gap-2">
                    <a href="tel:{{ $proveedore->telefono }}" class="btn btn-light border rounded-3 px-3 py-2 text-dark small fw-semibold d-inline-flex align-items-center gap-2">
                        <i class="fas fa-phone-alt text-primary"></i>
                        <span>{{ $proveedore->telefono }}</span>
                    </a>

                    @if($proveedore->celular)
                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $proveedore->celular) }}" target="_blank" class="btn btn-light border border-success-subtle rounded-3 px-3 py-2 text-success small fw-semibold d-inline-flex align-items-center gap-2">
                        <i class="fab fa-whatsapp"></i>
                        <span>WhatsApp ({{ $proveedore->celular }})</span>
                    </a>
                    @endif

                    @if($proveedore->email)
                    <a href="mailto:{{ $proveedore->email }}" class="btn btn-light border rounded-3 px-3 py-2 text-secondary small fw-semibold d-inline-flex align-items-center gap-2">
                        <i class="far fa-envelope text-primary"></i>
                        <span>{{ $proveedore->email }}</span>
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- 2. KPI METRICS -->
    <div class="row g-3 mb-4">
        <!-- Total Órdenes -->
        <div class="col-xl-3 col-sm-6">
            <div class="kpi-mini-card">
                <div class="text-muted small text-uppercase fw-bold" style="font-size: 0.75rem;">Órdenes Realizadas</div>
                <div class="h3 fw-bold text-dark my-1">{{ $totalCompras }}</div>
                <div class="text-muted small">Total pedidos registrados</div>
            </div>
        </div>

        <!-- Total Invertido -->
        <div class="col-xl-3 col-sm-6">
            <div class="kpi-mini-card">
                <div class="text-muted small text-uppercase fw-bold" style="font-size: 0.75rem;">Monto Total Facturado</div>
                <div class="h3 fw-bold text-success my-1">Bs. {{ number_format($totalInvertido, 2) }}</div>
                <div class="text-muted small">Inversión acumulada</div>
            </div>
        </div>

        <!-- Promedio por Compra -->
        <div class="col-xl-3 col-sm-6">
            <div class="kpi-mini-card">
                <div class="text-muted small text-uppercase fw-bold" style="font-size: 0.75rem;">Ticket Promedio</div>
                <div class="h3 fw-bold text-primary my-1">Bs. {{ number_format($promedioPorOrden, 2) }}</div>
                <div class="text-muted small">Promedio por orden de compra</div>
            </div>
        </div>

        <!-- Última Compra -->
        <div class="col-xl-3 col-sm-6">
            <div class="kpi-mini-card">
                <div class="text-muted small text-uppercase fw-bold" style="font-size: 0.75rem;">Última Operación</div>
                <div class="h4 fw-bold text-dark my-1">
                    {{ $ultimaCompra ? \Carbon\Carbon::parse($ultimaCompra->fecha)->format('d/m/Y') : 'Sin compras' }}
                </div>
                <div class="text-muted small">
                    {{ $ultimaCompra ? 'Comprobante: ' . $ultimaCompra->comprobante : 'Aún sin transacciones' }}
                </div>
            </div>
        </div>
    </div>

    <!-- 3. HISTORIAL DE ÓRDENES DE COMPRA -->
    <div class="table-container-card">
        <div class="p-3 bg-light border-bottom d-flex justify-content-between align-items-center flex-wrap gap-2">
            <h5 class="fw-bold text-dark mb-0 d-flex align-items-center gap-2" style="font-size: 0.95rem;">
                <i class="fas fa-history text-primary"></i> Historial de Compras Realizadas a este Proveedor
            </h5>
            <span class="badge bg-white text-secondary border px-2 py-1 small">
                {{ $proveedore->compras->count() }} orden(es) en total
            </span>
        </div>

        <div class="table-responsive">
            <table class="table table-custom align-middle">
                <thead>
                    <tr>
                        <th width="60" class="text-center">#</th>
                        <th>Fecha</th>
                        <th>N° Comprobante / Factura</th>
                        <th>Ítems Comprados</th>
                        <th class="text-end">Monto Total</th>
                        <th width="120" class="text-end">Acción</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($proveedore->compras as $index => $compra)
                    <tr>
                        <td class="text-center text-muted fw-bold">{{ $index + 1 }}</td>
                        <td>
                            <div class="fw-semibold text-dark">{{ \Carbon\Carbon::parse($compra->fecha)->format('d/m/Y') }}</div>
                            <small class="text-muted">{{ \Carbon\Carbon::parse($compra->created_at)->format('H:i A') }}</small>
                        </td>
                        <td>
                            <span class="badge bg-light text-dark border font-monospace" style="font-size: 0.8rem;">
                                <i class="fas fa-receipt text-primary me-1"></i> {{ $compra->comprobante }}
                            </span>
                        </td>
                        <td>
                            <div class="small text-muted">
                                {{ $compra->detalles->count() }} producto(s) incluidos
                            </div>
                            <div class="d-flex flex-wrap gap-1 mt-1">
                                @foreach($compra->detalles->take(3) as $det)
                                    <span class="badge bg-light text-secondary border" style="font-size: 0.72rem;">
                                        {{ $det->cantidad }}x {{ $det->producto->nombre ?? 'Producto' }}
                                    </span>
                                @endforeach
                                @if($compra->detalles->count() > 3)
                                    <span class="badge bg-secondary-subtle text-secondary" style="font-size: 0.72rem;">+{{ $compra->detalles->count() - 3 }} más</span>
                                @endif
                            </div>
                        </td>
                        <td class="text-end fw-bold text-dark" style="font-size: 0.95rem;">
                            Bs. {{ number_format($compra->total, 2) }}
                        </td>
                        <td class="text-end">
                            <a href="{{ route('compras.show', $compra->id) }}" class="btn btn-sm btn-outline-primary rounded-3 px-3">
                                <i class="fas fa-eye me-1"></i> Ver Detalle
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            <div class="py-3">
                                <i class="fas fa-shopping-cart fa-2x text-muted mb-2 d-block"></i>
                                <h6 class="fw-bold text-dark mb-1">Sin órdenes de compra</h6>
                                <p class="small text-muted mb-3">No se han registrado compras con este proveedor todavía.</p>
                                @if(auth()->user()->tienePermiso('compras'))
                                <a href="{{ route('compras.create') }}" class="btn btn-sm btn-primary rounded-3 px-3 shadow-sm">
                                    <i class="fas fa-plus me-1"></i> Registrar Primera Compra
                                </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

@endsection
