@extends('dashboard')

@section('content')

<style>
    .costos-container {
        padding: 1.5rem 1rem;
    }

    .kpi-costos-card {
        background: #ffffff;
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
        padding: 1.25rem;
        transition: all 0.25s ease;
        position: relative;
        overflow: hidden;
    }

    .kpi-costos-card:hover {
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

    .edu-card {
        background: linear-gradient(135deg, #1e1b4b 0%, #312e81 60%, #4338ca 100%);
        border-radius: 18px;
        padding: 1.75rem;
        color: #ffffff;
        box-shadow: 0 10px 25px -5px rgba(67, 56, 202, 0.3);
        margin-bottom: 1.75rem;
    }

    .step-badge {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 0.9rem;
        flex-shrink: 0;
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
</style>

<div class="costos-container">

    <!-- 1. ENCABEZADO -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <div class="kpi-icon-box-indigo" style="width: 38px; height: 38px; font-size: 1.1rem; border-radius: 10px;">
                    <i class="fas fa-chart-line"></i>
                </div>
                <h2 class="h4 fw-bold mb-0 text-dark">Análisis de Costos, Compras Caras y Protección de Ganancia</h2>
            </div>
            <p class="text-muted small mb-0">
                Herramienta para detectar compras a sobrecosto, evitar ventas a pérdida y recalcular el precio de venta sugerido.
            </p>
        </div>

        <div class="d-flex flex-wrap align-items-center gap-2">
            <a href="{{ route('inventario.index') }}" class="btn btn-outline-secondary rounded-3 px-3 py-2 fw-semibold d-inline-flex align-items-center gap-2">
                <i class="fas fa-boxes-stacked"></i>
                <span>Inventario General</span>
            </a>
            <a href="{{ route('inventario.valorizado') }}" class="btn btn-outline-primary rounded-3 px-3 py-2 fw-semibold d-inline-flex align-items-center gap-2">
                <i class="fas fa-file-invoice-dollar"></i>
                <span>Inventario Valorizado</span>
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

    <!-- 2. GUÍA EDUCATIVA PRÁCTICA: ¿CÓMO PROTEGER TU GANANCIA? -->
    <div class="edu-card">
        <div class="d-flex align-items-center gap-2 mb-2">
            <span class="badge bg-warning text-dark fw-bold px-2 py-1">
                <i class="fas fa-shield-alt me-1"></i> ESTRATEGIA FINANCIERA
            </span>
            <h5 class="fw-bold mb-0">¿Cómo saber qué productos o accesorios compraste caros y evitar que afecte tu ganancia?</h5>
        </div>
        <p class="text-light opacity-90 small mb-4" style="max-width: 900px;">
            Cuando un proveedor te sube el precio de compra (por ejemplo, un periférico que costaba Bs. 150 ahora te lo cobran a Bs. 220), si mantienes tu precio de venta en Bs. 4,000, <strong>tu ganancia se reduce drásticamente de Bs. 1,000 a solo Bs. 200</strong>. Sigue estas 3 reglas para no perder dinero:
        </p>

        <div class="row g-3">
            <div class="col-md-4">
                <div class="p-3 rounded-3" style="background: rgba(255, 255, 255, 0.08); border: 1px solid rgba(255, 255, 255, 0.15);">
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <div class="step-badge text-warning bg-warning bg-opacity-25">1</div>
                        <h6 class="fw-bold mb-0 text-white">Monitorea el Semáforo</h6>
                    </div>
                    <p class="small text-light opacity-75 mb-0">
                        Observa los productos con etiqueta <span class="badge bg-danger">Sobrecosto Crítico</span> o <span class="badge bg-warning text-dark">Margen Comprimido</span> en la tabla inferior.
                    </p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="p-3 rounded-3" style="background: rgba(255, 255, 255, 0.08); border: 1px solid rgba(255, 255, 255, 0.15);">
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <div class="step-badge text-info bg-info bg-opacity-25">2</div>
                        <h6 class="fw-bold mb-0 text-white">Aplica Margen de Seguridad</h6>
                    </div>
                    <p class="small text-light opacity-75 mb-0">
                        No absorbas el costo caro: traslada el incremento ajustando el precio con la columna <strong>"Precio Sugerido" (+25% o +35%)</strong>.
                    </p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="p-3 rounded-3" style="background: rgba(255, 255, 255, 0.08); border: 1px solid rgba(255, 255, 255, 0.15);">
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <div class="step-badge text-success bg-success bg-opacity-25">3</div>
                        <h6 class="fw-bold mb-0 text-white">Usa Costo Promedio o FIFO</h6>
                    </div>
                    <p class="small text-light opacity-75 mb-0">
                        Vende primero los productos antiguos al precio normal (FIFO), y las nuevas que compraste más caras véndelas con el nuevo precio ajustado.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- 3. KPI STATS CARDS -->
    <div class="row g-3 mb-4">
        <!-- Total Productos Analizados -->
        <div class="col-xl-3 col-sm-6">
            <div class="kpi-costos-card">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="kpi-label">Productos Analizados</div>
                        <div class="kpi-value text-dark">{{ $totalProductosAnalizados }} <span style="font-size: 0.85rem; font-weight: 600;">modelos</span></div>
                        <div class="text-muted small mt-1">Catálogo activo evaluado</div>
                    </div>
                    <div class="kpi-icon-box-indigo">
                        <i class="fas fa-boxes-stacked"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sobrecosto Crítico / Venta a Pérdida -->
        <div class="col-xl-3 col-sm-6">
            <div class="kpi-costos-card">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="kpi-label text-danger">Sobrecosto Crítico / Pérdida</div>
                        <div class="kpi-value text-danger">{{ $criticosCount }} <span style="font-size: 0.85rem; font-weight: 600;">alertas</span></div>
                        <div class="text-muted small mt-1">Costo &ge; Venta o margen &le; 5%</div>
                    </div>
                    <div class="kpi-icon-box-rose">
                        <i class="fas fa-skull-crossbones"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Margen Comprimido -->
        <div class="col-xl-3 col-sm-6">
            <div class="kpi-costos-card">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="kpi-label text-warning">Margen Comprimido (&lt;20%)</div>
                        <div class="kpi-value text-warning">{{ $bajosCount }} <span style="font-size: 0.85rem; font-weight: 600;">modelos</span></div>
                        <div class="text-muted small mt-1">Ganancia reducida</div>
                    </div>
                    <div class="kpi-icon-box-amber">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Margen Saludable -->
        <div class="col-xl-3 col-sm-6">
            <div class="kpi-costos-card">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="kpi-label text-success">Margen Saludable (&ge;20%)</div>
                        <div class="kpi-value text-success">{{ $optimosCount }} <span style="font-size: 0.85rem; font-weight: 600;">modelos</span></div>
                        <div class="text-muted small mt-1">Rentabilidad protegida</div>
                    </div>
                    <div class="kpi-icon-box-emerald">
                        <i class="fas fa-check-circle"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 4. FILTROS AVANZADOS -->
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-3">
            <form method="GET" action="{{ route('inventario.costos') }}" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label small text-muted mb-1 fw-semibold">Búsqueda rápida</label>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-white border-end-0 text-muted">
                            <i class="fas fa-search"></i>
                        </span>
                        <input type="text" name="buscar" class="form-control border-start-0" placeholder="SKU o nombre del producto / accesorio..." value="{{ request('buscar') }}">
                    </div>
                </div>

                <div class="col-md-3">
                    <label class="form-label small text-muted mb-1 fw-semibold">Semáforo de Rentabilidad</label>
                    <select name="nivel_riesgo" class="form-select form-select-sm">
                        <option value="">Todos los niveles</option>
                        <option value="critico" {{ request('nivel_riesgo') === 'critico' ? 'selected' : '' }}>🚨 Solo Sobrecostos Críticos (Peligro)</option>
                        <option value="bajo" {{ request('nivel_riesgo') === 'bajo' ? 'selected' : '' }}>⚠️ Solo Márgenes Comprimidos (&lt;20%)</option>
                        <option value="optimo" {{ request('nivel_riesgo') === 'optimo' ? 'selected' : '' }}>✅ Solo Márgenes Saludables (&ge;20%)</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label small text-muted mb-1 fw-semibold">Categoría</label>
                    <select name="categoria_id" class="form-select form-select-sm">
                        <option value="">Todas las categorías</option>
                        @foreach($categorias as $cat)
                            <option value="{{ $cat->id }}" {{ request('categoria_id') == $cat->id ? 'selected' : '' }}>{{ $cat->nombre }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-sm btn-primary w-100 fw-semibold">
                            <i class="fas fa-filter me-1"></i> Filtrar
                        </button>
                        @if(request()->anyFilled(['buscar', 'nivel_riesgo', 'categoria_id']))
                        <a href="{{ route('inventario.costos') }}" class="btn btn-sm btn-light border" title="Limpiar filtros">
                            <i class="fas fa-times"></i>
                        </a>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- 5. TABLA COMPARATIVA DE COSTOS Y MÁRGENES -->
    <div class="table-container-card">
        <div class="p-3 border-bottom d-flex justify-content-between align-items-center bg-light">
            <div class="fw-bold text-dark d-flex align-items-center gap-2">
                <i class="fas fa-calculator text-primary"></i>
                <span>Matriz de Costos de Compra vs Precios de Venta y Márgenes</span>
            </div>
            <span class="badge bg-light text-secondary border px-2 py-1" style="font-size: 0.78rem;">
                {{ $analisis->count() }} productos listados
            </span>
        </div>

        <div class="table-responsive">
            <table class="table table-custom table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th>Producto / Accesorio</th>
                        <th class="text-center">Stock</th>
                        <th class="text-end">Costo Compra (Bs.)</th>
                        <th class="text-end">Precio Venta (Bs.)</th>
                        <th class="text-end">Ganancia Neta (Bs.)</th>
                        <th class="text-center">% Margen</th>
                        <th class="text-center">Diagnóstico</th>
                        <th class="text-end">Precio Sugerido (+25% / +35%)</th>
                        <th class="text-center">Acción</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($analisis as $item)
                    <tr class="{{ $item->alerta === 'critico' ? 'table-danger bg-danger-subtle' : '' }}">
                        <td>
                            <div class="fw-bold text-dark">{{ $item->producto->nombre }}</div>
                            <small class="text-muted font-monospace">{{ $item->producto->codigo }}</small>
                            @if($item->producto->categoria)
                                <span class="badge bg-light text-secondary border ms-1" style="font-size: 0.7rem;">
                                    {{ $item->producto->categoria->nombre }}
                                </span>
                            @endif
                        </td>
                        <td class="text-center">
                            <span class="badge {{ $item->producto->stock > 0 ? 'bg-secondary' : 'bg-danger' }}">
                                {{ $item->producto->stock }} unids
                            </span>
                        </td>
                        <td class="text-end fw-bold text-secondary">
                            Bs. {{ number_format($item->costo, 2) }}
                        </td>
                        <td class="text-end fw-bold text-dark">
                            Bs. {{ number_format($item->venta, 2) }}
                        </td>
                        <td class="text-end fw-bold {{ $item->ganancia <= 0 ? 'text-danger' : 'text-success' }}">
                            Bs. {{ number_format($item->ganancia, 2) }}
                        </td>
                        <td class="text-center">
                            <span class="fw-bold {{ $item->margen_porcentaje < 10 ? 'text-danger' : ($item->margen_porcentaje < 20 ? 'text-warning' : 'text-success') }}">
                                {{ $item->margen_porcentaje }}%
                            </span>
                        </td>
                        <td class="text-center">
                            <span class="badge {{ $item->alertaBadge }} px-2 py-1">
                                @if($item->alerta === 'critico')
                                    <i class="fas fa-exclamation-circle me-1"></i>
                                @elseif($item->alerta === 'bajo')
                                    <i class="fas fa-exclamation-triangle me-1"></i>
                                @else
                                    <i class="fas fa-check-circle me-1"></i>
                                @endif
                                {{ $item->alertaLabel }}
                            </span>
                        </td>
                        <td class="text-end">
                            <div class="fw-bold text-primary">Bs. {{ number_format($item->sugerido25, 2) }} <span class="small text-muted font-monospace">(+25%)</span></div>
                            <div class="small text-success">Bs. {{ number_format($item->sugerido35, 2) }} <span class="text-muted font-monospace">(+35%)</span></div>
                        </td>
                        <td class="text-center">
                            <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-3 fw-semibold" data-bs-toggle="modal" data-bs-target="#modalAjustarPrecio{{ $item->producto->id }}">
                                <i class="fas fa-edit me-1"></i> Proteger Ganancia
                            </button>

                            <!-- MODAL DE AJUSTE DE PRECIO EN 1 CLIC -->
                            <div class="modal fade text-start" id="modalAjustarPrecio{{ $item->producto->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content border-0 shadow-lg rounded-4">
                                        <div class="modal-header border-0 bg-dark text-white rounded-top-4 px-4 py-3">
                                            <div class="d-flex align-items-center gap-2">
                                                <i class="fas fa-shield-alt text-warning"></i>
                                                <h5 class="modal-title fw-bold">Ajustar Precio de Venta</h5>
                                            </div>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                        </div>
                                        <form action="{{ route('inventario.actualizarPrecio', $item->producto->id) }}" method="POST">
                                            @csrf
                                            <div class="modal-body p-4">
                                                <h6 class="fw-bold text-dark mb-1">{{ $item->producto->nombre }}</h6>
                                                <p class="text-muted small mb-3">Código: {{ $item->producto->codigo }} | Stock: {{ $item->producto->stock }} unids</p>

                                                <div class="row g-2 mb-3 p-3 bg-light rounded-3">
                                                    <div class="col-6">
                                                        <span class="small text-muted d-block">Costo de compra actual:</span>
                                                        <strong class="text-dark">Bs. {{ number_format($item->costo, 2) }}</strong>
                                                    </div>
                                                    <div class="col-6">
                                                        <span class="small text-muted d-block">Precio de venta actual:</span>
                                                        <strong class="text-dark">Bs. {{ number_format($item->venta, 2) }}</strong>
                                                    </div>
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label fw-bold small text-dark">Precios Sugeridos por Margen:</label>
                                                    <div class="d-flex gap-2">
                                                        <button type="button" class="btn btn-sm btn-outline-primary flex-fill" onclick="document.getElementById('inputPrecio{{ $item->producto->id }}').value = '{{ $item->sugerido25 }}'">
                                                            Bs. {{ number_format($item->sugerido25, 2) }} <br><small>(+25% margen)</small>
                                                        </button>
                                                        <button type="button" class="btn btn-sm btn-outline-success flex-fill" onclick="document.getElementById('inputPrecio{{ $item->producto->id }}').value = '{{ $item->sugerido35 }}'">
                                                            Bs. {{ number_format($item->sugerido35, 2) }} <br><small>(+35% margen)</small>
                                                        </button>
                                                    </div>
                                                </div>

                                                <div class="mb-0">
                                                    <label class="form-label fw-bold small text-dark">Nuevo Precio de Venta (Bs.) <span class="text-danger">*</span></label>
                                                    <input type="number" step="0.01" name="precio_venta" id="inputPrecio{{ $item->producto->id }}" class="form-control form-control-lg fw-bold text-primary" value="{{ $item->venta }}" required>
                                                    <small class="text-muted">Ingresa el nuevo precio para recuperar tu margen y guardar.</small>
                                                </div>
                                            </div>
                                            <div class="modal-footer border-0 bg-light rounded-bottom-4 px-4 py-3">
                                                <button type="button" class="btn btn-secondary rounded-3 px-3" data-bs-dismiss="modal">Cancelar</button>
                                                <button type="submit" class="btn btn-primary rounded-3 px-4 fw-bold">
                                                    <i class="fas fa-save me-1"></i> Guardar Nuevo Precio
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center py-5">
                            <i class="fas fa-check-circle fs-1 text-success opacity-50 mb-3 d-block"></i>
                            <h6 class="text-secondary fw-semibold">No se encontraron productos con estos criterios</h6>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

@endsection
