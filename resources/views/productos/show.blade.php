@extends('dashboard')

@section('content')
<div class="container-fluid py-4 px-3 px-md-4">

    <!-- HEADER / ACTIONS -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <a href="{{ route('productos.index') }}" class="btn btn-sm btn-light border shadow-sm text-secondary px-2 py-1 rounded-3">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <h2 class="h4 fw-bold text-dark mb-0">{{ $producto->nombre }}</h2>
                <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-3 py-1 fs-8">
                    <i class="fas fa-tag me-1"></i> {{ $producto->categoria ? $producto->categoria->nombre : 'Sin categoría' }}
                </span>
                
                @if($producto->estado_stock === 'optimo')
                    <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3 py-1 fs-8">
                        <i class="fas fa-check-circle me-1"></i> Stock Óptimo ({{ $producto->stock }} un.)
                    </span>
                @elseif($producto->estado_stock === 'bajo')
                    <span class="badge bg-warning-subtle text-warning border border-warning-subtle rounded-pill px-3 py-1 fs-8">
                        <i class="fas fa-exclamation-triangle me-1"></i> Stock Bajo ({{ $producto->stock }} un.)
                    </span>
                @else
                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-3 py-1 fs-8">
                        <i class="fas fa-times-circle me-1"></i> Agotado (0 un.)
                    </span>
                @endif
            </div>
            <p class="text-muted small mb-0">Ficha técnica detallada, métricas financieras y trazabilidad de movimientos de inventario.</p>
        </div>
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('productos.edit', $producto) }}" class="btn btn-warning text-dark fw-semibold rounded-3 px-3 py-2 shadow-sm">
                <i class="fas fa-edit me-1"></i> Editar Producto
            </a>
            <button type="button" class="btn btn-outline-secondary rounded-3 px-3 py-2" onclick="window.print()">
                <i class="fas fa-print me-1"></i> Imprimir Ficha
            </button>
            <a href="{{ route('productos.index') }}" class="btn btn-light border rounded-3 px-3 py-2 text-secondary">
                <i class="fas fa-list me-1"></i> Volver a Lista
            </a>
        </div>
    </div>

    <div class="row g-4">
        
        <!-- COLUMNA IZQUIERDA: FOTO, SKU Y ESTADO DE STOCK -->
        <div class="col-lg-4">
            
            <!-- CARD: IMAGEN Y CÓDIGO SKU -->
            <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">
                <div class="bg-light p-4 text-center border-bottom position-relative">
                    @if($producto->imagen_url)
                        <img src="{{ $producto->imagen_url }}" alt="{{ $producto->nombre }}" class="img-fluid rounded-3 shadow-sm" style="max-height: 240px; object-fit: contain;">
                    @else
                        <div class="py-5 text-muted">
                            <i class="fas fa-box-open fs-1 opacity-25 mb-2"></i>
                            <div class="small fw-semibold">Sin imagen de producto</div>
                        </div>
                    @endif
                </div>
                <div class="card-body p-4 text-center">
                    <div class="d-inline-block p-2 bg-light border rounded-3 mb-2">
                        <div class="text-muted small fw-bold text-uppercase mb-1">Código de Barras / SKU</div>
                        <div class="font-monospace fw-bolder text-primary fs-5">{{ $producto->codigo }}</div>
                    </div>
                    <p class="text-muted small mb-0">Registrado el {{ $producto->created_at ? $producto->created_at->format('d/m/Y') : 'N/A' }}</p>
                </div>
            </div>

            <!-- CARD: SALUD DEL INVENTARIO -->
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body p-4">
                    <h6 class="fw-bold text-dark mb-3 d-flex align-items-center gap-2">
                        <i class="fas fa-boxes-stacked text-primary"></i> Existencias en Almacén
                    </h6>
                    
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted small">Nivel de Almacén:</span>
                        <span class="fw-bold text-dark">{{ $producto->stock }} / {{ $producto->stock_maximo ?? 50 }} unidades</span>
                    </div>

                    <div class="progress rounded-pill mb-3" style="height: 10px;">
                        <div class="progress-bar {{ $producto->stock <= 0 ? 'bg-danger' : ($producto->stock <= $producto->stock_minimo ? 'bg-warning' : 'bg-success') }}" 
                             role="progressbar" 
                             style="width: {{ min(100, $producto->nivel_stock_porcentaje) }}%;"></div>
                    </div>

                    <div class="row g-2 text-center pt-2 border-top">
                        <div class="col-4 border-end">
                            <div class="text-muted small" style="font-size: 0.75rem;">MÍNIMO</div>
                            <div class="fw-bold text-danger">{{ $producto->stock_minimo ?? 5 }}</div>
                        </div>
                        <div class="col-4 border-end">
                            <div class="text-muted small" style="font-size: 0.75rem;">ACTUAL</div>
                            <div class="fw-bold text-dark">{{ $producto->stock }}</div>
                        </div>
                        <div class="col-4">
                            <div class="text-muted small" style="font-size: 0.75rem;">MÁXIMO</div>
                            <div class="fw-bold text-primary">{{ $producto->stock_maximo ?? 50 }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- CARD: VALORIZACIÓN PATRIMONIAL -->
            <div class="card border-0 shadow-sm rounded-4 bg-gradient-valuation text-white">
                <div class="card-body p-4">
                    <h6 class="fw-bold text-white-50 mb-3 text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.5px;">
                        <i class="fas fa-vault me-1"></i> Capital en Stock
                    </h6>
                    <div class="mb-3">
                        <div class="small text-white-50">Inversión Total (Costo x Stock)</div>
                        <div class="h4 fw-bold mb-0">Bs. {{ number_format($producto->stock * $producto->precio_compra, 2) }}</div>
                    </div>
                    <div>
                        <div class="small text-white-50">Retorno Proyectado (Venta x Stock)</div>
                        <div class="h5 fw-bold text-warning mb-0">Bs. {{ number_format($producto->stock * $producto->precio_venta, 2) }}</div>
                    </div>
                </div>
            </div>

        </div>

        <!-- COLUMNA DERECHA: FINANZAS, FICHA TÉCNICA Y HISTORIAL -->
        <div class="col-lg-8">
            
            <!-- CARD: KPIS FINANCIEROS Y MARGEN -->
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body p-4">
                    <h6 class="fw-bold text-dark mb-3 d-flex align-items-center gap-2">
                        <i class="fas fa-coins text-success"></i> Estructura Financiera por Unidad
                    </h6>
                    <div class="row g-3 text-center">
                        <div class="col-md-3 col-6">
                            <div class="p-3 bg-light rounded-4 border">
                                <div class="text-muted small">Costo Compra</div>
                                <div class="h5 fw-bold text-dark mb-0 mt-1">Bs. {{ number_format($producto->precio_compra, 2) }}</div>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="p-3 bg-success-subtle rounded-4 border border-success-subtle">
                                <div class="text-success small fw-semibold">Precio Venta</div>
                                <div class="h5 fw-bold text-success mb-0 mt-1">Bs. {{ number_format($producto->precio_venta, 2) }}</div>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="p-3 bg-primary-subtle rounded-4 border border-primary-subtle">
                                <div class="text-primary small fw-semibold">Ganancia Unitaria</div>
                                <div class="h5 fw-bold text-primary mb-0 mt-1">+ Bs. {{ number_format($producto->ganancia, 2) }}</div>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="p-3 bg-info-subtle rounded-4 border border-info-subtle">
                                <div class="text-info small fw-semibold">Margen %</div>
                                <div class="h5 fw-bold text-info mb-0 mt-1">{{ number_format($producto->margen_porcentaje, 1) }}%</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- CARD: ESPECIFICACIONES TÉCNICAS COMPLETAS -->
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body p-4">
                    <h6 class="fw-bold text-dark mb-3 d-flex align-items-center gap-2">
                        <i class="fas fa-microchip text-primary"></i> Especificaciones y Ficha Técnica
                    </h6>
                    <div class="p-3 bg-light rounded-4 border">
                        @if($producto->descripcion)
                            <p class="mb-0 text-dark" style="white-space: pre-line; line-height: 1.6;">{{ $producto->descripcion }}</p>
                        @else
                            <div class="text-muted small fst-italic">No se registró una descripción técnica adicional para este producto.</div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- CARD: HISTORIAL DE VENTAS Y COMPRAS -->
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-transparent border-0 pt-4 px-4 pb-0">
                    <ul class="nav nav-pills card-header-pills" id="productHistoryTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active rounded-pill px-4 fw-semibold" id="ventas-tab" data-bs-toggle="tab" data-bs-target="#ventas-tab-pane" type="button" role="tab">
                                <i class="fas fa-receipt me-1"></i> Historial de Ventas ({{ $producto->detalleVentas->count() }})
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link rounded-pill px-4 fw-semibold" id="compras-tab" data-bs-toggle="tab" data-bs-target="#compras-tab-pane" type="button" role="tab">
                                <i class="fas fa-truck-ramp-box me-1"></i> Entradas / Compras ({{ $producto->detalleCompras->count() }})
                            </button>
                        </li>
                    </ul>
                </div>
                <div class="card-body p-4">
                    <div class="tab-content" id="productHistoryTabsContent">
                        
                        <!-- TAB: VENTAS -->
                        <div class="tab-pane fade show active" id="ventas-tab-pane" role="tabpanel" tabindex="0">
                            @if($producto->detalleVentas->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle mb-0">
                                        <thead class="table-light small text-muted">
                                            <tr>
                                                <th>Nº Venta</th>
                                                <th>Fecha</th>
                                                <th>Cantidad</th>
                                                <th>Precio Unit.</th>
                                                <th>Total</th>
                                            </tr>
                                        </thead>
                                        <tbody class="small">
                                            @foreach($producto->detalleVentas as $det)
                                                <tr>
                                                    <td class="fw-bold text-primary">#{{ $det->venta_id }}</td>
                                                    <td>{{ $det->created_at ? $det->created_at->format('d/m/Y H:i') : 'N/A' }}</td>
                                                    <td><span class="badge bg-secondary rounded-pill">{{ $det->cantidad }} un.</span></td>
                                                    <td>Bs. {{ number_format($det->precio_unitario ?? $det->precio ?? $producto->precio_venta, 2) }}</td>
                                                    <td class="fw-bold text-success">Bs. {{ number_format(($det->cantidad) * ($det->precio_unitario ?? $det->precio ?? $producto->precio_venta), 2) }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-center py-4 text-muted">
                                    <i class="fas fa-shopping-cart fs-2 opacity-25 mb-2"></i>
                                    <p class="small mb-0">Este producto aún no registra ventas en el sistema.</p>
                                </div>
                            @endif
                        </div>

                        <!-- TAB: COMPRAS -->
                        <div class="tab-pane fade" id="compras-tab-pane" role="tabpanel" tabindex="0">
                            @if($producto->detalleCompras->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle mb-0">
                                        <thead class="table-light small text-muted">
                                            <tr>
                                                <th>Nº Compra</th>
                                                <th>Fecha</th>
                                                <th>Cantidad</th>
                                                <th>Costo Unit.</th>
                                                <th>Total</th>
                                            </tr>
                                        </thead>
                                        <tbody class="small">
                                            @foreach($producto->detalleCompras as $comp)
                                                <tr>
                                                    <td class="fw-bold text-dark">#{{ $comp->compra_id }}</td>
                                                    <td>{{ $comp->created_at ? $comp->created_at->format('d/m/Y H:i') : 'N/A' }}</td>
                                                    <td><span class="badge bg-info-subtle text-info rounded-pill">{{ $comp->cantidad }} un.</span></td>
                                                    <td>Bs. {{ number_format($comp->precio_compra ?? $producto->precio_compra, 2) }}</td>
                                                    <td class="fw-bold text-dark">Bs. {{ number_format(($comp->cantidad) * ($comp->precio_compra ?? $producto->precio_compra), 2) }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-center py-4 text-muted">
                                    <i class="fas fa-box-open fs-2 opacity-25 mb-2"></i>
                                    <p class="small mb-0">No se encontraron registros de compras de proveedores para este modelo.</p>
                                </div>
                            @endif
                        </div>

                    </div>
                </div>
            </div>

        </div>

    </div>

</div>

<style>
    .bg-gradient-valuation {
        background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
    }
    .fs-8 { font-size: 0.78rem; }
</style>
@endsection
