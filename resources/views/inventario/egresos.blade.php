@extends('dashboard')

@section('content')

<style>
    .egresos-container {
        padding: 1.5rem 1rem;
    }

    .kpi-card-out {
        background: #ffffff;
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
        padding: 1.25rem;
        transition: all 0.25s ease;
        position: relative;
        overflow: hidden;
    }

    .kpi-card-out:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 25px -5px rgba(0, 0, 0, 0.08);
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

    .kpi-icon-box-purple {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.35rem;
        color: #ffffff;
        background: linear-gradient(135deg, #7c3aed 0%, #a855f7 100%);
        box-shadow: 0 6px 15px rgba(124, 58, 237, 0.25);
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

<div class="egresos-container">

    <!-- 1. ENCABEZADO Y ACCIONES -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <div class="kpi-icon-box-rose" style="width: 38px; height: 38px; font-size: 1.1rem; border-radius: 10px;">
                    <i class="fas fa-arrow-up-long"></i>
                </div>
                <h2 class="h4 fw-bold mb-0 text-dark">Módulo de Egresos de Stock</h2>
                <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1 ms-2" style="font-size: 0.75rem;">
                    Salidas de Almacén
                </span>
            </div>
            <p class="text-muted small mb-0">
                Auditoría y control de salidas de productos y accesorios: despachos por ventas, bajas por merma técnica, averías y garantías.
            </p>
        </div>

        <div class="d-flex flex-wrap align-items-center gap-2">
            <!-- Botón Registrar Egreso Manual -->
            <button type="button" class="btn text-white rounded-3 px-3 py-2 fw-semibold d-inline-flex align-items-center gap-2 shadow-sm" style="background: linear-gradient(135deg, #dc2626 0%, #ef4444 100%);" data-bs-toggle="modal" data-bs-target="#modalEgresoManual">
                <i class="fas fa-minus-circle"></i>
                <span>Registrar Egreso / Baja (-)</span>
            </button>

            <!-- Botón Realizar Venta -->
            @if(auth()->user()->tienePermiso('ventas'))
            <a href="{{ route('ventas.create') }}" class="btn btn-outline-danger rounded-3 px-3 py-2 fw-semibold d-inline-flex align-items-center gap-2 shadow-sm">
                <i class="fas fa-cash-register"></i>
                <span>Nueva Venta</span>
            </a>
            @endif

            <!-- Enlace a Control de Inventario -->
            <a href="{{ route('inventario.index') }}" class="btn btn-outline-secondary rounded-3 px-3 py-2 fw-semibold d-inline-flex align-items-center gap-2">
                <i class="fas fa-boxes-stacked"></i>
                <span>Volver a Inventario</span>
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
        <!-- Egresos del Mes -->
        <div class="col-xl-3 col-sm-6">
            <div class="kpi-card-out">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="kpi-label">Egresos del Mes</div>
                        <div class="kpi-value text-danger">-{{ number_format($egresosMes) }} <span style="font-size: 0.85rem; font-weight: 600;">unids</span></div>
                        <div class="text-muted small mt-1">Ventas y salidas totales</div>
                    </div>
                    <div class="kpi-icon-box-rose">
                        <i class="fas fa-calendar-minus"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Egresos de Hoy -->
        <div class="col-xl-3 col-sm-6">
            <div class="kpi-card-out">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="kpi-label">Egresos de Hoy</div>
                        <div class="kpi-value text-dark">-{{ number_format($egresosHoy) }} <span style="font-size: 0.85rem; font-weight: 600;">unids</span></div>
                        <div class="text-muted small mt-1">Salidas registradas hoy</div>
                    </div>
                    <div class="kpi-icon-box-purple">
                        <i class="fas fa-clock"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Despachadas por Ventas -->
        <div class="col-xl-3 col-sm-6">
            <div class="kpi-card-out">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="kpi-label">Salidas por Ventas (Mes)</div>
                        <div class="kpi-value text-success" style="font-size: 1.35rem;">
                            {{ number_format($unidadesPorVentas) }} <span style="font-size: 0.85rem; font-weight: 600;">artículos</span>
                        </div>
                        <div class="text-muted small mt-1">Entregadas a clientes</div>
                    </div>
                    <div class="kpi-icon-box-amber">
                        <i class="fas fa-hand-holding-dollar"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Salidas por Mermas / Daño -->
        <div class="col-xl-3 col-sm-6">
            <div class="kpi-card-out">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="kpi-label">Mermas / Ajustes (-)</div>
                        <div class="kpi-value text-danger" style="font-size: 1.35rem;">
                            {{ number_format($unidadesPorMermas) }} <span style="font-size: 0.85rem; font-weight: 600;">unids</span>
                        </div>
                        <div class="text-muted small mt-1">Bajas técnicas / mermas</div>
                    </div>
                    <div class="kpi-icon-box-rose">
                        <i class="fas fa-triangle-exclamation"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 3. FILTROS AVANZADOS DE EGRESOS -->
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-3">
            <form method="GET" action="{{ route('inventario.egresos') }}" class="row g-3 align-items-end">
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

                <!-- Tipo de origen / motivo -->
                <div class="col-md-2">
                    <label class="form-label small text-muted mb-1 fw-semibold">Tipo de Salida</label>
                    <select name="tipo_origen" class="form-select form-select-sm">
                        <option value="">Todas las salidas</option>
                        <option value="ventas" {{ request('tipo_origen') === 'ventas' ? 'selected' : '' }}>Salidas por Venta</option>
                        <option value="mermas" {{ request('tipo_origen') === 'mermas' ? 'selected' : '' }}>Mermas / Bajas Técnicas</option>
                        <option value="devolucion_proveedor" {{ request('tipo_origen') === 'devolucion_proveedor' ? 'selected' : '' }}>Devolución a Proveedor</option>
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
                        @if(request()->anyFilled(['buscar', 'tipo_origen', 'producto_id', 'fecha_desde', 'fecha_hasta']))
                        <a href="{{ route('inventario.egresos') }}" class="btn btn-sm btn-light border" title="Limpiar filtros">
                            <i class="fas fa-times"></i>
                        </a>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- 4. TABLA DE EGRESOS -->
    <div class="table-container-card">
        <div class="p-3 border-bottom d-flex justify-content-between align-items-center bg-light">
            <div class="fw-bold text-dark d-flex align-items-center gap-2">
                <i class="fas fa-arrow-circle-up text-danger"></i>
                <span>Historial de Salidas Registradas</span>
            </div>
            <span class="badge bg-light text-secondary border px-2 py-1" style="font-size: 0.78rem;">
                {{ $egresos->total() }} salidas en total
            </span>
        </div>

        <div class="table-responsive">
            <table class="table table-custom table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th>Fecha & Hora</th>
                        <th>Producto / Accesorio</th>
                        <th>Tipo de Salida</th>
                        <th class="text-center">Cantidad (-)</th>
                        <th class="text-end">Precio / Costo Unit.</th>
                        <th>Motivo / Justificación</th>
                        <th>Responsable</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($egresos as $egreso)
                    <tr>
                        <td>
                            <div class="fw-semibold text-dark">{{ $egreso->created_at->format('d/m/Y') }}</div>
                            <small class="text-muted">{{ $egreso->created_at->format('H:i') }}</small>
                        </td>
                        <td>
                            @if($egreso->producto)
                                <div class="fw-bold text-dark">{{ $egreso->producto->nombre }}</div>
                                <small class="text-muted font-monospace">{{ $egreso->producto->codigo }}</small>
                                @if($egreso->producto->categoria)
                                    <span class="badge bg-light text-secondary border ms-1" style="font-size: 0.7rem;">
                                        {{ $egreso->producto->categoria->nombre }}
                                    </span>
                                @endif
                            @else
                                <span class="text-muted fst-italic">Producto eliminado</span>
                            @endif
                        </td>
                        <td>
                            @if($egreso->tipo === 'venta')
                                <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1">
                                    <i class="fas fa-cash-register me-1"></i> Despacho por Venta
                                </span>
                            @elseif($egreso->tipo === 'ajuste_negativo')
                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1">
                                    <i class="fas fa-minus-circle me-1"></i> Merma / Baja Técnica
                                </span>
                            @elseif($egreso->tipo === 'anulacion_compra')
                                <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle px-2 py-1">
                                    <i class="fas fa-undo me-1"></i> Devolución a Proveedor
                                </span>
                            @else
                                <span class="badge bg-secondary px-2 py-1">{{ ucfirst($egreso->tipo) }}</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <span class="badge-qty-out">
                                -{{ abs($egreso->cantidad) }}
                            </span>
                            <div class="small text-muted mt-1" style="font-size: 0.72rem;">
                                {{ $egreso->stock_anterior }} &rarr; <strong>{{ $egreso->stock_nuevo }}</strong>
                            </div>
                        </td>
                        <td class="text-end">
                            @if($egreso->tipo === 'venta')
                                <span class="fw-bold text-dark">Bs. {{ number_format($egreso->precio_unitario ?? 0, 2) }}</span>
                                <div class="small text-muted" style="font-size: 0.7rem;">(Precio Venta)</div>
                            @else
                                <span class="text-muted">Bs. {{ number_format($egreso->costo_unitario ?? 0, 2) }}</span>
                                <div class="small text-muted" style="font-size: 0.7rem;">(Costo Compra)</div>
                            @endif
                        </td>
                        <td>
                            <div class="text-dark" style="max-width: 250px; font-size: 0.85rem;">
                                {{ $egreso->motivo ?: 'Sin descripción' }}
                            </div>
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div style="width: 26px; height: 26px; border-radius: 6px; background: #fee2e2; color: #dc2626; display: flex; align-items: center; justify-content: center; font-size: 0.7rem; font-weight: 700;">
                                    {{ strtoupper(substr($egreso->usuario->name ?? 'S', 0, 1)) }}
                                </div>
                                <small class="text-dark fw-semibold">{{ $egreso->usuario->name ?? 'Sistema' }}</small>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5">
                            <i class="fas fa-box-open fs-1 text-muted opacity-50 mb-3 d-block"></i>
                            <h6 class="text-secondary fw-semibold">No se encontraron registros de egresos</h6>
                            <p class="text-muted small mb-0">No hay salidas de stock registradas con los filtros seleccionados.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($egresos->hasPages())
        <div class="p-3 border-top d-flex justify-content-end bg-light">
            {{ $egresos->links() }}
        </div>
        @endif
    </div>

</div>

<!-- MODAL REGISTRAR EGRESO MANUAL (-) -->
<div class="modal fade" id="modalEgresoManual" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-0 bg-danger text-white rounded-top-4 px-4 py-3">
                <div class="d-flex align-items-center gap-2">
                    <i class="fas fa-minus-circle fs-5"></i>
                    <h5 class="modal-title fw-bold">Registrar Egreso / Baja de Stock (-)</h5>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('inventario.movimiento.store') }}" method="POST">
                @csrf
                <input type="hidden" name="tipo" value="ajuste_negativo">

                <div class="modal-body p-4">
                    <div class="alert alert-danger-subtle text-danger-emphasis border border-danger-subtle rounded-3 small p-3 mb-3">
                        <i class="fas fa-exclamation-triangle me-1"></i>
                        Esta acción restará inmediatamente unidades del stock disponible del producto. No se puede egresar una cantidad mayor al stock actual.
                    </div>

                    <!-- Seleccionar Producto -->
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-dark">Producto / Accesorio <span class="text-danger">*</span></label>
                        <select name="producto_id" class="form-select" required>
                            <option value="">-- Seleccionar producto con stock disponible --</option>
                            @foreach($productos as $p)
                                <option value="{{ $p->id }}" {{ $p->stock <= 0 ? 'disabled' : '' }}>
                                    {{ $p->nombre }} [Stock disponible: {{ $p->stock }}]
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="row g-3 mb-3">
                        <!-- Cantidad a egresar -->
                        <div class="col-md-12">
                            <label class="form-label fw-bold small text-dark">Cantidad a Retirar / Dar de Baja <span class="text-danger">*</span></label>
                            <input type="number" name="cantidad" class="form-control" min="1" placeholder="Ej: 1" required>
                        </div>
                    </div>

                    <!-- Motivo -->
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

                    <!-- Observaciones adicionales -->
                    <div class="mb-0">
                        <label class="form-label fw-bold small text-dark">Observaciones / Detalle Técnico</label>
                        <textarea name="observacion" class="form-control" rows="2" placeholder="Describa el estado del equipo, número de serie o informe técnico..."></textarea>
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
