@extends('dashboard')

@section('content')

<style>
    .ingresos-container {
        padding: 1.5rem 1rem;
    }

    .kpi-card-in {
        background: #ffffff;
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
        padding: 1.25rem;
        transition: all 0.25s ease;
        position: relative;
        overflow: hidden;
    }

    .kpi-card-in:hover {
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

    .kpi-icon-box-indigo {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.35rem;
        color: #ffffff;
        background: linear-gradient(135deg, #4f46e5 0%, #6366f1 100%);
        box-shadow: 0 6px 15px rgba(79, 70, 229, 0.25);
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
</style>

<div class="ingresos-container">

    <!-- 1. ENCABEZADO Y ACCIONES -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <div class="kpi-icon-box-emerald" style="width: 38px; height: 38px; font-size: 1.1rem; border-radius: 10px;">
                    <i class="fas fa-arrow-down-long"></i>
                </div>
                <h2 class="h4 fw-bold mb-0 text-dark">Módulo de Ingresos de Stock</h2>
                <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 ms-2" style="font-size: 0.75rem;">
                    Entradas a Almacén
                </span>
            </div>
            <p class="text-muted small mb-0">
                Historial cronológico de abastecimiento: compras a proveedores, ajustes positivos manuales, stock inicial y devoluciones.
            </p>
        </div>

        <div class="d-flex flex-wrap align-items-center gap-2">
            <!-- Botón Registrar Ingreso Manual -->
            <button type="button" class="btn text-white rounded-3 px-3 py-2 fw-semibold d-inline-flex align-items-center gap-2 shadow-sm" style="background: linear-gradient(135deg, #059669 0%, #10b981 100%);" data-bs-toggle="modal" data-bs-target="#modalIngresoManual">
                <i class="fas fa-plus-circle"></i>
                <span>Registrar Ingreso Manual (+)</span>
            </button>

            <!-- Botón Registrar Compra a Proveedor -->
            @if(auth()->user()->tienePermiso('compras'))
            <a href="{{ route('compras.create') }}" class="btn btn-outline-success rounded-3 px-3 py-2 fw-semibold d-inline-flex align-items-center gap-2 shadow-sm">
                <i class="fas fa-shopping-cart"></i>
                <span>Nueva Compra</span>
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
        <!-- Ingresos del Mes -->
        <div class="col-xl-3 col-sm-6">
            <div class="kpi-card-in">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="kpi-label">Ingresos del Mes</div>
                        <div class="kpi-value text-success">+{{ number_format($ingresosMes) }} <span style="font-size: 0.85rem; font-weight: 600;">unids</span></div>
                        <div class="text-muted small mt-1">Compras y ajustes positivos</div>
                    </div>
                    <div class="kpi-icon-box-emerald">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Ingresos de Hoy -->
        <div class="col-xl-3 col-sm-6">
            <div class="kpi-card-in">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="kpi-label">Ingresos de Hoy</div>
                        <div class="kpi-value text-dark">+{{ number_format($ingresosHoy) }} <span style="font-size: 0.85rem; font-weight: 600;">unids</span></div>
                        <div class="text-muted small mt-1">Entradas recibidas hoy</div>
                    </div>
                    <div class="kpi-icon-box-indigo">
                        <i class="fas fa-clock"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Inversión en Ingresos del Mes -->
        <div class="col-xl-3 col-sm-6">
            <div class="kpi-card-in">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="kpi-label">Inversión Ingresos (Mes)</div>
                        <div class="kpi-value text-success" style="font-size: 1.3rem;">
                            Bs. {{ number_format($inversionIngresosMes, 2) }}
                        </div>
                        <div class="text-muted small mt-1">Costo valorizado de entradas</div>
                    </div>
                    <div class="kpi-icon-box-amber">
                        <i class="fas fa-coins"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Desglose Origen -->
        <div class="col-xl-3 col-sm-6">
            <div class="kpi-card-in">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="kpi-label">Desglose de Entradas</div>
                        <div class="kpi-value text-dark" style="font-size: 1.15rem;">
                            {{ number_format($unidadesPorCompras) }} <span style="font-size: 0.75rem; color: #64748b;">compras</span> / {{ number_format($unidadesPorAjustes) }} <span style="font-size: 0.75rem; color: #64748b;">ajustes</span>
                        </div>
                        <div class="text-muted small mt-1">Total histórico: +{{ number_format($totalIngresosUnidades) }} unids</div>
                    </div>
                    <div class="kpi-icon-box-emerald">
                        <i class="fas fa-dolly"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 3. FILTROS AVANZADOS DE INGRESOS -->
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-3">
            <form method="GET" action="{{ route('inventario.ingresos') }}" class="row g-3 align-items-end">
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

                <!-- Tipo de origen -->
                <div class="col-md-2">
                    <label class="form-label small text-muted mb-1 fw-semibold">Origen de Entrada</label>
                    <select name="tipo_origen" class="form-select form-select-sm">
                        <option value="">Todos los orígenes</option>
                        <option value="compras" {{ request('tipo_origen') === 'compras' ? 'selected' : '' }}>Compras a proveedores</option>
                        <option value="ajustes" {{ request('tipo_origen') === 'ajustes' ? 'selected' : '' }}>Ajustes positivos manuales</option>
                        <option value="devoluciones" {{ request('tipo_origen') === 'devoluciones' ? 'selected' : '' }}>Devoluciones de venta</option>
                        <option value="inicial" {{ request('tipo_origen') === 'inicial' ? 'selected' : '' }}>Stock inicial</option>
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
                        <a href="{{ route('inventario.ingresos') }}" class="btn btn-sm btn-light border" title="Limpiar filtros">
                            <i class="fas fa-times"></i>
                        </a>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- 4. TABLA DE INGRESOS -->
    <div class="table-container-card">
        <div class="p-3 border-bottom d-flex justify-content-between align-items-center bg-light">
            <div class="fw-bold text-dark d-flex align-items-center gap-2">
                <i class="fas fa-list-check text-success"></i>
                <span>Historial de Entradas Registradas</span>
            </div>
            <span class="badge bg-light text-secondary border px-2 py-1" style="font-size: 0.78rem;">
                {{ $ingresos->total() }} entradas en total
            </span>
        </div>

        <div class="table-responsive">
            <table class="table table-custom table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th>Fecha & Hora</th>
                        <th>Producto / Accesorio</th>
                        <th>Tipo de Entrada</th>
                        <th class="text-center">Cantidad (+)</th>
                        <th class="text-end">Costo Unit.</th>
                        <th class="text-end">Costo Total</th>
                        <th>Motivo / Referencia</th>
                        <th>Responsable</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($ingresos as $ingreso)
                    <tr>
                        <td>
                            <div class="fw-semibold text-dark">{{ $ingreso->created_at->format('d/m/Y') }}</div>
                            <small class="text-muted">{{ $ingreso->created_at->format('H:i') }}</small>
                        </td>
                        <td>
                            @if($ingreso->producto)
                                <div class="fw-bold text-dark">{{ $ingreso->producto->nombre }}</div>
                                <small class="text-muted font-monospace">{{ $ingreso->producto->codigo }}</small>
                                @if($ingreso->producto->categoria)
                                    <span class="badge bg-light text-secondary border ms-1" style="font-size: 0.7rem;">
                                        {{ $ingreso->producto->categoria->nombre }}
                                    </span>
                                @endif
                            @else
                                <span class="text-muted fst-italic">Producto eliminado</span>
                            @endif
                        </td>
                        <td>
                            @if($ingreso->tipo === 'compra')
                                <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1">
                                    <i class="fas fa-shopping-cart me-1"></i> Compra a Proveedor
                                </span>
                            @elseif($ingreso->tipo === 'ajuste_positivo')
                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1">
                                    <i class="fas fa-plus-circle me-1"></i> Ajuste Positivo
                                </span>
                            @elseif($ingreso->tipo === 'anulacion_venta')
                                <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle px-2 py-1">
                                    <i class="fas fa-undo me-1"></i> Devolución de Venta
                                </span>
                            @elseif($ingreso->tipo === 'inicial')
                                <span class="badge bg-info-subtle text-info border border-info-subtle px-2 py-1">
                                    <i class="fas fa-boxes me-1"></i> Stock Inicial
                                </span>
                            @else
                                <span class="badge bg-secondary px-2 py-1">{{ ucfirst($ingreso->tipo) }}</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <span class="badge-qty-in">
                                +{{ $ingreso->cantidad }}
                            </span>
                            <div class="small text-muted mt-1" style="font-size: 0.72rem;">
                                {{ $ingreso->stock_anterior }} &rarr; <strong>{{ $ingreso->stock_nuevo }}</strong>
                            </div>
                        </td>
                        <td class="text-end">
                            Bs. {{ number_format($ingreso->costo_unitario ?? 0, 2) }}
                        </td>
                        <td class="text-end fw-bold text-success">
                            Bs. {{ number_format(($ingreso->cantidad * ($ingreso->costo_unitario ?? 0)), 2) }}
                        </td>
                        <td>
                            <div class="text-dark" style="max-width: 250px; font-size: 0.85rem;">
                                {{ $ingreso->motivo ?: 'Sin descripción' }}
                            </div>
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div style="width: 26px; height: 26px; border-radius: 6px; background: #e2e8f0; display: flex; align-items: center; justify-content: center; font-size: 0.7rem; font-weight: 700;">
                                    {{ strtoupper(substr($ingreso->usuario->name ?? 'S', 0, 1)) }}
                                </div>
                                <small class="text-dark fw-semibold">{{ $ingreso->usuario->name ?? 'Sistema' }}</small>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-5">
                            <i class="fas fa-inbox fs-1 text-muted opacity-50 mb-3 d-block"></i>
                            <h6 class="text-secondary fw-semibold">No se encontraron registros de ingresos</h6>
                            <p class="text-muted small mb-0">No hay entradas de stock registradas con los filtros seleccionados.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($ingresos->hasPages())
        <div class="p-3 border-top d-flex justify-content-end bg-light">
            {{ $ingresos->links() }}
        </div>
        @endif
    </div>

</div>

<!-- MODAL REGISTRAR INGRESO MANUAL (+) -->
<div class="modal fade" id="modalIngresoManual" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-0 bg-success text-white rounded-top-4 px-4 py-3">
                <div class="d-flex align-items-center gap-2">
                    <i class="fas fa-plus-circle fs-5"></i>
                    <h5 class="modal-title fw-bold">Registrar Ingreso Manual (+)</h5>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('inventario.movimiento.store') }}" method="POST">
                @csrf
                <input type="hidden" name="tipo" value="ajuste_positivo">

                <div class="modal-body p-4">
                    <div class="alert alert-success-subtle text-success-emphasis border border-success-subtle rounded-3 small p-3 mb-3">
                        <i class="fas fa-info-circle me-1"></i>
                        Esta acción incrementará directamente el stock físico del producto en el inventario y quedará registrada en el Kardex y la Bitácora.
                    </div>

                    <!-- Seleccionar Producto -->
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-dark">Producto / Accesorio <span class="text-danger">*</span></label>
                        <select name="producto_id" class="form-select" required>
                            <option value="">-- Seleccionar producto --</option>
                            @foreach($productos as $p)
                                <option value="{{ $p->id }}">
                                    {{ $p->nombre }} (Stock actual: {{ $p->stock }} | Costo: Bs. {{ number_format($p->precio_compra, 2) }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="row g-3 mb-3">
                        <!-- Cantidad a ingresar -->
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-dark">Cantidad a Ingresar <span class="text-danger">*</span></label>
                            <input type="number" name="cantidad" class="form-control" min="1" placeholder="Ej: 5" required>
                        </div>
                        <!-- Costo Unitario de Referencia -->
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-dark">Costo Unitario (Bs.)</label>
                            <input type="number" step="0.01" name="costo_unitario" class="form-control" placeholder="Opcional (toma costo actual)">
                        </div>
                    </div>

                    <!-- Motivo -->
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

                    <!-- Observaciones adicionales -->
                    <div class="mb-0">
                        <label class="form-label fw-bold small text-dark">Observaciones / Notas Adicionales</label>
                        <textarea name="observacion" class="form-control" rows="2" placeholder="Detalles de la entrada, número de guía o nota de remisión..."></textarea>
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

@endsection
