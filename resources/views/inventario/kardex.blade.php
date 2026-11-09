@extends('dashboard')

@section('content')

<style>
    .kardex-container {
        padding: 1.5rem 1rem;
    }

    .kardex-hero-card {
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
        padding: 1rem 1.2rem;
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9;
        font-size: 0.88rem;
    }

    .table-custom tbody tr:hover td {
        background-color: #f8fafc;
    }
</style>

<div class="kardex-container">

    <!-- BREADCRUMB / VOLVER -->
    <div class="mb-3 d-flex justify-content-between align-items-center">
        <a href="{{ route('inventario.index') }}" class="text-decoration-none text-muted small fw-semibold d-inline-flex align-items-center gap-1">
            <i class="fas fa-arrow-left"></i> Volver a Control de Inventario
        </a>

        @if($productoSeleccionado)
        <div class="d-flex gap-2">
            <a href="{{ route('productos.show', $productoSeleccionado->id) }}" class="btn btn-sm btn-outline-secondary rounded-3 px-3">
                <i class="fas fa-boxes-stacked me-1"></i> Ficha del Producto
            </a>
            <button type="button" class="btn btn-sm btn-primary rounded-3 px-3" onclick="window.print()">
                <i class="fas fa-print me-1"></i> Imprimir Kardex
            </button>
        </div>
        @endif
    </div>

    <!-- 1. SELECTOR DE PRODUCTO Y HERO -->
    <div class="kardex-hero-card">
        <div class="row align-items-center g-3">
            <div class="col-lg-5">
                <div class="d-flex align-items-center gap-2 mb-1">
                    <div class="kpi-icon-box" style="width: 36px; height: 36px; background: linear-gradient(135deg, #7c3aed 0%, #a855f7 100%); color: #fff; border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-book-open"></i>
                    </div>
                    <h2 class="h5 fw-bold mb-0 text-dark">Kardex de Movimientos Físicos</h2>
                </div>
                <p class="text-muted small mb-0">Seleccione un producto para auditar su trazabilidad completa de entradas y salidas.</p>
            </div>

            <div class="col-lg-7">
                <form method="GET" action="{{ route('inventario.kardex') }}" class="row g-2 align-items-center justify-content-lg-end">
                    <div class="col-sm-8">
                        <select name="producto_id" class="form-select" onchange="this.form.submit()">
                            <option value="">Seleccione un producto para consultar...</option>
                            @foreach($todosProductos as $tp)
                                <option value="{{ $tp->id }}" {{ ($productoSeleccionado && $productoSeleccionado->id == $tp->id) ? 'selected' : '' }}>
                                    {{ $tp->nombre }} [{{ $tp->codigo }}] — Stock: {{ $tp->stock }} u.
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-sm-4">
                        <button type="submit" class="btn btn-primary w-100 rounded-3">
                            <i class="fas fa-search me-1"></i> Consultar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @if($productoSeleccionado)
    <!-- 2. TARJETA RESUMEN DEL PRODUCTO SELECCIONADO -->
    <div class="row g-3 mb-4">
        <!-- Stock Actual -->
        <div class="col-md-3">
            <div class="kpi-card">
                <div class="kpi-label">Stock Actual Disponible</div>
                <div class="kpi-value text-dark">{{ $productoSeleccionado->stock }} <span style="font-size: 0.9rem; color: #64748b;">unids</span></div>
                <div class="kpi-subtext">Mínimo sugerido: {{ $productoSeleccionado->stock_minimo ?? 5 }} unids</div>
            </div>
        </div>

        <!-- Total Entradas Históricas -->
        <div class="col-md-3">
            <div class="kpi-card">
                <div class="kpi-label">Total Entradas Históricas</div>
                <div class="kpi-value text-success">+{{ $totalEntradas }} <span style="font-size: 0.9rem;">unids</span></div>
                <div class="kpi-subtext">Compras y ajustes positivos</div>
            </div>
        </div>

        <!-- Total Salidas Históricas -->
        <div class="col-md-3">
            <div class="kpi-card">
                <div class="kpi-label">Total Salidas Históricas</div>
                <div class="kpi-value text-danger">-{{ $totalSalidas }} <span style="font-size: 0.9rem;">unids</span></div>
                <div class="kpi-subtext">Ventas y mermas registradas</div>
            </div>
        </div>

        <!-- Valor en Inventario -->
        <div class="col-md-3">
            <div class="kpi-card">
                <div class="kpi-label">Capital en Stock (Costo)</div>
                <div class="kpi-value text-primary" style="font-size: 1.25rem;">
                    Bs. {{ number_format($productoSeleccionado->stock * $productoSeleccionado->precio_compra, 2) }}
                </div>
                <div class="kpi-subtext">Costo Unit: Bs. {{ number_format($productoSeleccionado->precio_compra, 2) }}</div>
            </div>
        </div>
    </div>

    <!-- 3. TABLA CRONOLÓGICA DEL KARDEX -->
    <div class="table-container-card">
        <div class="p-3 bg-light border-bottom d-flex justify-content-between align-items-center flex-wrap gap-2">
            <h5 class="fw-bold text-dark mb-0 d-flex align-items-center gap-2" style="font-size: 0.95rem;">
                <i class="fas fa-list text-primary"></i> Libro Mayor / Kardex: {{ $productoSeleccionado->nombre }} [{{ $productoSeleccionado->codigo }}]
            </h5>
            <span class="badge bg-white text-secondary border px-2 py-1 small">
                {{ $movimientos->count() }} transacciones registradas
            </span>
        </div>

        <div class="table-responsive">
            <table class="table table-custom align-middle">
                <thead>
                    <tr>
                        <th width="50" class="text-center">#</th>
                        <th>Fecha y Hora</th>
                        <th>Concepto / Tipo de Movimiento</th>
                        <th>Motivo / Referencia</th>
                        <th class="text-center">Entrada (+)</th>
                        <th class="text-center">Salida (-)</th>
                        <th class="text-center">Saldo / Stock</th>
                        <th>Responsable</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($movimientos as $index => $m)
                    @php
                        $tipoInfo = $m->tipo_info;
                        $esIngreso = in_array($m->tipo, ['compra', 'ajuste_positivo', 'anulacion_venta', 'inicial']);
                    @endphp
                    <tr>
                        <td class="text-center text-muted fw-bold">{{ $index + 1 }}</td>
                        <td>
                            <div class="fw-semibold text-dark">{{ $m->created_at->format('d/m/Y') }}</div>
                            <small class="text-muted">{{ $m->created_at->format('H:i A') }}</small>
                        </td>
                        <td>
                            <span class="badge {{ $tipoInfo['badge_bs'] }} rounded-pill px-3 py-1" style="font-size: 0.75rem;">
                                <i class="{{ $tipoInfo['icon'] }} me-1"></i> {{ $tipoInfo['label'] }}
                            </span>
                        </td>
                        <td>
                            <div class="text-dark small">{{ $m->motivo ?: 'Sin observación' }}</div>
                        </td>
                        <td class="text-center">
                            @if($esIngreso)
                                <span class="fw-bold text-success">+{{ $m->cantidad }}</span>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td class="text-center">
                            @if(!$esIngreso)
                                <span class="fw-bold text-danger">-{{ $m->cantidad }}</span>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <span class="badge bg-dark text-white font-monospace px-2 py-1" style="font-size: 0.82rem;">
                                {{ $m->stock_nuevo }} u.
                            </span>
                        </td>
                        <td>
                            <small class="text-secondary">{{ $m->usuario->name ?? 'Sistema' }}</small>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-5 text-muted">
                            <div class="py-3">
                                <i class="fas fa-history fa-2x text-muted mb-2 d-block"></i>
                                <h6 class="fw-bold text-dark mb-1">Sin movimientos registrados</h6>
                                <p class="small text-muted mb-0">No se han producido entradas o salidas para este producto.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @else
    <div class="text-center py-5">
        <div class="py-5">
            <i class="fas fa-box-open fa-3x text-muted mb-3 d-block"></i>
            <h5 class="fw-bold text-dark">Seleccione un producto para ver su Kardex</h5>
            <p class="text-muted small">Utilice el selector superior para cargar el historial de transacciones de un equipo.</p>
        </div>
    </div>
    @endif

</div>

@endsection
