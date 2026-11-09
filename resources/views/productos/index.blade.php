@extends('dashboard')

@section('content')

<style>
    :root {
        --prod-primary: #6366f1;
        --prod-primary-dark: #4338ca;
        --prod-success: #10b981;
        --prod-warning: #f59e0b;
        --prod-danger: #ef4444;
        --prod-purple: #7c3aed;
        --prod-info: #0284c7;
    }

    .productos-container {
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

    .kpi-icon-purple {
        background: linear-gradient(135deg, #7c3aed 0%, #a855f7 100%);
        box-shadow: 0 6px 15px rgba(124, 58, 237, 0.25);
    }

    .kpi-icon-indigo {
        background: linear-gradient(135deg, #4f46e5 0%, #6366f1 100%);
        box-shadow: 0 6px 15px rgba(79, 70, 229, 0.25);
    }

    .kpi-icon-emerald {
        background: linear-gradient(135deg, #059669 0%, #10b981 100%);
        box-shadow: 0 6px 15px rgba(16, 185, 129, 0.25);
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

    .filter-header {
        padding: 1rem 1.25rem;
        background: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    /* TABLE CARD */
    .table-container-card {
        background: #ffffff;
        border-radius: 16px;
        border: 1px solid #f1f5f9;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
        overflow: hidden;
    }

    .table-custom {
        margin-bottom: 0;
    }

    .table-custom thead th {
        background: #f8fafc;
        color: #475569;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        padding: 14px 16px;
        border-bottom: 1px solid #e2e8f0;
        vertical-align: middle;
    }

    .table-custom tbody tr:hover {
        background-color: #f8fafc;
    }

    .table-custom tbody td {
        padding: 14px 16px;
        vertical-align: middle;
        font-size: 0.88rem;
        border-bottom: 1px solid #f1f5f9;
    }

    /* THUMBNAIL IMAGE */
    .prod-thumb-box {
        width: 48px;
        height: 48px;
        border-radius: 10px;
        background: #f1f5f9;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        border: 1px solid #e2e8f0;
        flex-shrink: 0;
    }

    .prod-thumb-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.2s ease;
    }

    .prod-thumb-box:hover .prod-thumb-img {
        transform: scale(1.15);
    }

    /* STOCK BAR */
    .stock-mini-bar {
        height: 4px;
        border-radius: 4px;
        background-color: #e2e8f0;
        overflow: hidden;
        margin-top: 4px;
    }

    .stock-mini-fill {
        height: 100%;
        border-radius: 4px;
    }

    /* COPY BUTTON */
    .btn-copy-code {
        cursor: pointer;
        padding: 2px 5px;
        border-radius: 4px;
        border: none;
        background: transparent;
        color: #94a3b8;
    }

    .btn-copy-code:hover {
        background: #f1f5f9;
        color: #4f46e5;
    }

    /* ACTION BUTTONS */
    .btn-action-icon {
        width: 34px;
        height: 34px;
        border-radius: 9px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 0.85rem;
        border: 1px solid #e2e8f0;
        background: #ffffff;
        color: #475569;
        transition: all 0.2s ease;
        text-decoration: none;
    }

    .btn-action-icon:hover {
        background: #f8fafc;
        transform: translateY(-2px);
    }

    .btn-action-view:hover {
        background: #e0f2fe;
        color: #0284c7;
        border-color: #bae6fd;
    }

    .btn-action-edit:hover {
        background: #eef2ff;
        color: #4f46e5;
        border-color: #c7d2fe;
    }

    .btn-action-delete:hover {
        background: #fef2f2;
        color: #ef4444;
        border-color: #fecaca;
    }
</style>

<div class="productos-container">

    <!-- 1. ENCABEZADO Y ACCIONES PRINCIPALES -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <div class="kpi-icon-box kpi-icon-purple" style="width: 36px; height: 36px; font-size: 1rem; border-radius: 10px;">
                    <i class="fas fa-boxes-stacked"></i>
                </div>
                <h2 class="h4 fw-bold mb-0 text-dark">Catálogo & Inventario de Productos</h2>
            </div>
            <p class="text-muted small mb-0">
                Control físico de existencias, costos, márgenes comerciales y alertas de stock de accesorios y productos.
            </p>
        </div>
        <div class="d-flex flex-wrap align-items-center gap-2">
            <a href="{{ route('inventario.index') }}" class="btn btn-outline-primary rounded-3 px-3 py-2 fw-semibold d-inline-flex align-items-center gap-2" style="font-size: 0.88rem;">
                <i class="fas fa-boxes-stacked text-primary"></i>
                <span>Movimientos & Kardex</span>
            </a>

            @if(auth()->user()->tienePermiso('categorias'))
            <a href="{{ route('categorias.index') }}" class="btn btn-outline-secondary rounded-3 px-3 py-2 fw-semibold d-inline-flex align-items-center gap-2" style="font-size: 0.88rem;">
                <i class="fas fa-tags text-secondary"></i>
                <span>Categorías</span>
            </a>
            @endif

            <a href="{{ route('productos.create') }}" class="btn text-white rounded-3 px-3 py-2 fw-semibold d-inline-flex align-items-center gap-2" style="background: linear-gradient(135deg, #7c3aed 0%, #4f46e5 100%); font-size: 0.88rem; box-shadow: 0 4px 14px rgba(124, 58, 237, 0.35);">
                <i class="fas fa-plus"></i>
                <span>Nuevo Producto</span>
            </a>
        </div>
    </div>

    <!-- 2. KPI STATS CARDS -->
    <div class="row g-3 mb-4">
        <!-- Total Modelos -->
        <div class="col-xl-3 col-sm-6">
            <div class="kpi-card">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="kpi-label">Catálogo Registrado</div>
                        <div class="kpi-value text-dark">{{ $totalModelos ?? 0 }}</div>
                        <div class="kpi-subtext mt-1">
                            <i class="fas fa-boxes-stacked text-primary me-1"></i> Artículos registrados
                        </div>
                    </div>
                    <div class="kpi-icon-box kpi-icon-purple">
                        <i class="fas fa-boxes"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Unidades en Stock -->
        <div class="col-xl-3 col-sm-6">
            <div class="kpi-card">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="kpi-label">Stock en Almacén</div>
                        <div class="kpi-value text-primary">{{ number_format($totalUnidades ?? 0) }}</div>
                        <div class="kpi-subtext mt-1">
                            <i class="fas fa-cube text-primary me-1"></i> Unidades disponibles
                        </div>
                    </div>
                    <div class="kpi-icon-box kpi-icon-indigo">
                        <i class="fas fa-warehouse"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Valor del Inventario -->
        <div class="col-xl-3 col-sm-6">
            <div class="kpi-card">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="kpi-label">Valor del Inventario</div>
                        <div class="kpi-value text-success">
                            Bs. {{ number_format($valorInventario ?? 0, 2) }}
                        </div>
                        <div class="kpi-subtext mt-1">
                            <i class="fas fa-coins text-success me-1"></i> Costo de adquisición total
                        </div>
                    </div>
                    <div class="kpi-icon-box kpi-icon-emerald">
                        <i class="fas fa-wallet"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Alertas de Stock Bajo -->
        <div class="col-xl-3 col-sm-6">
            <div class="kpi-card">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="kpi-label">Alertas de Stock</div>
                        <div class="kpi-value {{ ($stockBajoCount + $agotadosCount) > 0 ? 'text-warning' : 'text-success' }}">
                            {{ $stockBajoCount + $agotadosCount }}
                        </div>
                        <div class="kpi-subtext mt-1">
                            @if($agotadosCount > 0)
                                <span class="text-danger fw-semibold"><i class="fas fa-times-circle me-1"></i> {{ $agotadosCount }} agotados</span>
                            @elseif($stockBajoCount > 0)
                                <span class="text-warning fw-semibold"><i class="fas fa-exclamation-triangle me-1"></i> {{ $stockBajoCount }} bajo mínimo</span>
                            @else
                                <span class="text-success"><i class="fas fa-check-circle me-1"></i> Niveles óptimos</span>
                            @endif
                        </div>
                    </div>
                    <div class="kpi-icon-box kpi-icon-amber">
                        <i class="fas fa-bell"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 3. PANEL DE BÚSQUEDA Y FILTROS -->
    <div class="filter-card mb-4">
        <div class="filter-header">
            <div class="d-flex align-items-center gap-2">
                <i class="fas fa-filter text-primary"></i>
                <span class="fw-bold text-dark" style="font-size: 0.9rem;">Búsqueda y Filtros de Inventario</span>
            </div>
            <span class="badge bg-light text-secondary border px-2 py-1" id="prodCounterText" style="font-size: 0.75rem;">
                Mostrando {{ count($productos) }} productos
            </span>
        </div>
        <div class="p-3">
            <div class="row g-3">
                <!-- Buscador -->
                <div class="col-md-5 col-lg-5">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0 text-muted">
                            <i class="fas fa-search"></i>
                        </span>
                        <input type="text" id="prodSearchInput" class="form-control border-start-0 ps-0" placeholder="Buscar por modelo, código SKU o características..." onkeyup="filterProdTable()">
                    </div>
                </div>

                <!-- Filtro Categoría -->
                <div class="col-md-4 col-lg-4">
                    <select id="prodCatSelect" class="form-select" onchange="filterProdTable()">
                        <option value="">Todas las Categorías</option>
                        @foreach($categorias as $c)
                            <option value="{{ mb_strtolower($c->nombre) }}">{{ $c->nombre }} ({{ $c->productos_count }})</option>
                        @endforeach
                    </select>
                </div>

                <!-- Filtro Stock -->
                <div class="col-md-3 col-lg-3">
                    <div class="d-flex gap-2">
                        <select id="prodStockSelect" class="form-select" onchange="filterProdTable()">
                            <option value="">Todos los Niveles</option>
                            <option value="optimo">Stock Óptimo</option>
                            <option value="bajo">Stock Bajo (Alerta)</option>
                            <option value="agotado">Agotados (0)</option>
                        </select>

                        <button type="button" class="btn btn-outline-secondary px-3" onclick="resetProdFilters()" title="Limpiar filtros">
                            <i class="fas fa-undo"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 4. TABLA DE PRODUCTOS -->
    <div class="table-container-card">
        <div class="table-responsive">
            <table class="table table-custom align-middle" id="prodTable">
                <thead>
                    <tr>
                        <th width="50" class="text-center">#</th>
                        <th width="80" class="text-center">Foto</th>
                        <th width="140">Código SKU</th>
                        <th>Producto / Modelo</th>
                        <th>Categoría</th>
                        <th width="160">Stock Disponible</th>
                        <th class="text-end">P. Compra</th>
                        <th class="text-end">P. Venta</th>
                        <th class="text-center">Rentabilidad</th>
                        <th width="130" class="text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody id="prodTableBody">
                    @forelse($productos as $key => $p)
                    @php
                        $estadoStock = $p->estado_stock;
                        $catNombre = $p->categoria->nombre ?? 'General';
                        $catSlug = mb_strtolower($catNombre);
                        $ganancia = $p->ganancia;
                        $margen = $p->margen_porcentaje;
                        $imgUrl = $p->imagen_url;
                    @endphp
                    <tr class="prod-row" 
                        data-name="{{ mb_strtolower($p->nombre) }}" 
                        data-code="{{ mb_strtolower($p->codigo) }}" 
                        data-desc="{{ mb_strtolower($p->descripcion ?? '') }}"
                        data-cat="{{ $catSlug }}" 
                        data-stock-status="{{ $estadoStock['clave'] }}">
                        
                        <td class="text-center text-muted fw-bold">{{ $key + 1 }}</td>

                        <!-- Thumbnail Imagen -->
                        <td class="text-center">
                            <div class="prod-thumb-box mx-auto">
                                @if($imgUrl)
                                    <img src="{{ $imgUrl }}" alt="{{ $p->nombre }}" class="prod-thumb-img">
                                @else
                                    <i class="fas fa-box-open text-muted" style="font-size: 1.25rem;"></i>
                                @endif
                            </div>
                        </td>

                        <!-- Código SKU -->
                        <td>
                            <div class="d-flex align-items-center gap-1">
                                <span class="badge bg-light text-dark border font-monospace" style="font-size: 0.78rem;">
                                    {{ $p->codigo }}
                                </span>
                                <button type="button" class="btn-copy-code" onclick="copyCode('{{ $p->codigo }}', this)" title="Copiar código">
                                    <i class="far fa-copy"></i>
                                </button>
                            </div>
                        </td>

                        <!-- Nombre Producto -->
                        <td>
                            <div class="fw-bold text-dark mb-0" style="font-size: 0.9rem;">
                                {{ $p->nombre }}
                            </div>
                            @if($p->descripcion)
                                <small class="text-muted text-truncate d-block" style="max-width: 250px; font-size: 0.75rem;">
                                    {{ Str::limit($p->descripcion, 55) }}
                                </small>
                            @endif
                        </td>

                        <!-- Categoría -->
                        <td>
                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-2 py-1" style="font-size: 0.74rem;">
                                <i class="fas fa-tag me-1"></i> {{ $catNombre }}
                            </span>
                        </td>

                        <!-- Stock con Nivel Visual -->
                        <td>
                            <div class="d-flex align-items-center justify-content-between mb-1">
                                <span class="badge {{ $estadoStock['badge_class'] }} rounded-pill px-2 py-1" style="font-size: 0.72rem;">
                                    <i class="{{ $estadoStock['icon'] }} me-1"></i> {{ $p->stock }} u.
                                </span>
                                <small class="text-muted" style="font-size: 0.7rem;">Mín: {{ $p->stock_minimo ?? 5 }}</small>
                            </div>
                            <div class="stock-mini-bar">
                                <div class="stock-mini-fill" style="width: {{ $p->nivel_stock_porcentaje }}%; background-color: {{ $estadoStock['color'] }};"></div>
                            </div>
                        </td>

                        <!-- Precio Compra -->
                        <td class="text-end text-secondary small">
                            Bs. {{ number_format($p->precio_compra, 2) }}
                        </td>

                        <!-- Precio Venta -->
                        <td class="text-end fw-bold text-dark">
                            Bs. {{ number_format($p->precio_venta, 2) }}
                        </td>

                        <!-- Rentabilidad / Margen -->
                        <td class="text-center">
                            <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1" style="font-size: 0.74rem;" title="Ganancia estimada por unidad">
                                +{{ $margen }}% (Bs. {{ number_format($ganancia, 2) }})
                            </span>
                        </td>

                        <!-- Acciones -->
                        <td class="text-end">
                            <div class="d-inline-flex gap-1">
                                <!-- Modal Ver Detalle -->
                                <button type="button" class="btn-action-icon btn-action-view" title="Ver Ficha Técnica"
                                    onclick="openProdModal({{ json_encode([
                                        'id' => $p->id,
                                        'codigo' => $p->codigo,
                                        'nombre' => $p->nombre,
                                        'categoria' => $catNombre,
                                        'descripcion' => $p->descripcion ?? 'Sin especificaciones técnicas adicionales.',
                                        'stock' => $p->stock,
                                        'stock_minimo' => $p->stock_minimo ?? 5,
                                        'stock_maximo' => $p->stock_maximo ?? 100,
                                        'precio_compra' => number_format($p->precio_compra, 2),
                                        'precio_venta' => number_format($p->precio_venta, 2),
                                        'ganancia' => number_format($ganancia, 2),
                                        'margen' => $margen,
                                        'fecha_ingreso' => $p->fecha_ingreso ? \Carbon\Carbon::parse($p->fecha_ingreso)->format('d/m/Y') : 'N/A',
                                        'imagen_url' => $imgUrl,
                                        'estado_label' => $estadoStock['label'],
                                        'estado_badge' => $estadoStock['badge_class']
                                    ]) }})">
                                    <i class="fas fa-eye"></i>
                                </button>


                                <!-- Editar -->
                                <a href="{{ route('productos.edit', $p) }}" class="btn-action-icon btn-action-edit" title="Editar Producto">
                                    <i class="fas fa-pen"></i>
                                </a>

                                <!-- Eliminar -->
                                <button type="button" class="btn-action-icon btn-action-delete" title="Eliminar Producto"
                                    onclick="confirmDeleteProd('{{ $p->id }}', '{{ addslashes($p->nombre) }}', '{{ $p->codigo }}')">
                                    <i class="fas fa-trash"></i>
                                </button>
                                <form id="delete-prod-form-{{ $p->id }}" action="{{ route('productos.destroy', $p) }}" method="POST" style="display: none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="text-center py-5 text-muted">
                            <div class="py-4">
                                <i class="fas fa-box-open fa-3x text-muted mb-3 d-block opacity-50"></i>
                                <h6 class="fw-bold text-dark">No hay productos registrados en el inventario</h6>
                                <p class="small text-muted mb-3">Comienza registrando los productos y accesorios en el catálogo.</p>
                                <a href="{{ route('productos.create') }}" class="btn btn-primary btn-sm rounded-pill px-3">
                                    <i class="fas fa-plus me-1"></i> Registrar Producto
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse

                    <tr id="noProdResultsRow" style="display: none;">
                        <td colspan="10" class="text-center py-5 text-muted">
                            <i class="fas fa-search fa-2x text-muted mb-2 d-block opacity-50"></i>
                            <p class="mb-0 fw-semibold text-secondary">No se encontraron productos que coincidan con la búsqueda o filtro aplicado.</p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

</div>

<!-- MODAL FICHA TÉCNICA DEL PRODUCTO -->
<div class="modal fade" id="prodDetailModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 rounded-4 shadow-lg overflow-hidden">
            <div class="modal-header text-white p-4" style="background: linear-gradient(135deg, #7c3aed 0%, #4f46e5 100%);">
                <div class="d-flex align-items-center gap-3">
                    <div style="width: 44px; height: 44px; border-radius: 12px; background: rgba(255,255,255,0.2); display: flex; align-items: center; justify-content: center; font-size: 1.25rem;">
                        <i class="fas fa-boxes-stacked"></i>
                    </div>
                    <div>
                        <span class="badge bg-white bg-opacity-20 text-white rounded-pill px-2 py-0.5 small mb-1" id="mProdCategory">Categoría</span>
                        <h5 class="modal-title fw-bold mb-0" id="mProdName">Nombre del Producto</h5>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body p-4">
                <div class="row g-4">
                    <!-- Imagen Grande -->
                    <div class="col-md-5 text-center">
                        <div class="p-3 bg-light rounded-4 border d-flex align-items-center justify-content-center" style="height: 220px;">
                            <img id="mProdImage" src="" alt="" style="max-height: 100%; max-width: 100%; object-fit: contain; border-radius: 10px;">
                            <div id="mProdNoImage" class="text-muted text-center" style="display: none;">
                                <i class="fas fa-box-open fa-4x opacity-25 d-block mb-2"></i>
                                <span class="small">Sin imagen registrada</span>
                            </div>
                        </div>

                        <div class="mt-3 p-2 bg-light rounded-3 border">
                            <span class="text-muted small">Código / SKU:</span>
                            <div class="fw-bold font-monospace text-primary fs-6" id="mProdCode">-</div>
                        </div>
                    </div>

                    <!-- Datos Comerciales y Stock -->
                    <div class="col-md-7">
                        <div class="row g-2 mb-3">
                            <div class="col-6">
                                <div class="p-3 bg-light rounded-3 border">
                                    <small class="text-muted d-block" style="font-size: 0.72rem; text-transform: uppercase; font-weight: 700;">Stock en Almacén</small>
                                    <div class="d-flex align-items-center justify-content-between mt-1">
                                        <span class="fs-5 fw-bold text-dark" id="mProdStock">0</span>
                                        <span class="badge" id="mProdStockBadge">Óptimo</span>
                                    </div>
                                    <small class="text-muted d-block mt-1" style="font-size: 0.7rem;" id="mProdStockThresholds">Min: 5 | Max: 100</small>
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="p-3 bg-light rounded-3 border">
                                    <small class="text-muted d-block" style="font-size: 0.72rem; text-transform: uppercase; font-weight: 700;">Fecha de Ingreso</small>
                                    <div class="fs-6 fw-bold text-dark mt-1" id="mProdDate">-</div>
                                    <small class="text-muted d-block" style="font-size: 0.7rem;">Registro en almacén</small>
                                </div>
                            </div>
                        </div>

                        <!-- Precios y Márgenes -->
                        <div class="p-3 bg-light rounded-3 border mb-3">
                            <div class="row text-center">
                                <div class="col-4 border-end">
                                    <small class="text-muted d-block" style="font-size: 0.7rem; text-transform: uppercase; font-weight: 700;">Costo Compra</small>
                                    <span class="fw-bold text-secondary" id="mProdBuyPrice">Bs. 0.00</span>
                                </div>
                                <div class="col-4 border-end">
                                    <small class="text-muted d-block" style="font-size: 0.7rem; text-transform: uppercase; font-weight: 700;">Precio Venta</small>
                                    <span class="fw-bold text-primary" id="mProdSellPrice">Bs. 0.00</span>
                                </div>
                                <div class="col-4">
                                    <small class="text-muted d-block" style="font-size: 0.7rem; text-transform: uppercase; font-weight: 700;">Ganancia Neta</small>
                                    <span class="fw-bold text-success" id="mProdProfit">Bs. 0.00</span>
                                </div>
                            </div>
                        </div>

                        <!-- Especificaciones / Descripción -->
                        <div>
                            <small class="text-muted d-block mb-1" style="font-size: 0.72rem; text-transform: uppercase; font-weight: 700;">Especificaciones Técnicas</small>
                            <div class="p-3 bg-light rounded-3 border small text-secondary" id="mProdDesc" style="max-height: 100px; overflow-y: auto;">
                                -
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer bg-light p-3 border-top d-flex justify-content-between">
                <a href="#" id="mProdEditBtn" class="btn btn-outline-primary rounded-pill px-3 btn-sm">
                    <i class="fas fa-edit me-1"></i> Modificar Producto
                </a>
                <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<!-- SCRIPTS DE INTERACTIVIDAD -->
<script>
function filterProdTable() {
    const textQuery = document.getElementById('prodSearchInput').value.toLowerCase().trim();
    const catFilter = document.getElementById('prodCatSelect').value.toLowerCase().trim();
    const stockFilter = document.getElementById('prodStockSelect').value.toLowerCase().trim();
    const rows = document.querySelectorAll('.prod-row');
    let visibleCount = 0;

    rows.forEach(row => {
        const name = row.getAttribute('data-name') || '';
        const code = row.getAttribute('data-code') || '';
        const desc = row.getAttribute('data-desc') || '';
        const cat = row.getAttribute('data-cat') || '';
        const stockStatus = row.getAttribute('data-stock-status') || '';

        const matchesText = textQuery === '' || name.includes(textQuery) || code.includes(textQuery) || desc.includes(textQuery);
        const matchesCat = catFilter === '' || cat === catFilter;
        const matchesStock = stockFilter === '' || stockStatus === stockFilter;

        if (matchesText && matchesCat && matchesStock) {
            row.style.display = '';
            visibleCount++;
        } else {
            row.style.display = 'none';
        }
    });

    const noResultsRow = document.getElementById('noProdResultsRow');
    if (noResultsRow) {
        noResultsRow.style.display = (visibleCount === 0 && rows.length > 0) ? '' : 'none';
    }

    const counterText = document.getElementById('prodCounterText');
    if (counterText) {
        counterText.textContent = `Mostrando ${visibleCount} de ${rows.length} productos`;
    }
}

function resetProdFilters() {
    document.getElementById('prodSearchInput').value = '';
    document.getElementById('prodCatSelect').value = '';
    document.getElementById('prodStockSelect').value = '';
    filterProdTable();
}

function copyCode(text, btnElement) {
    navigator.clipboard.writeText(text).then(() => {
        const icon = btnElement.querySelector('i');
        const oldClass = icon.className;
        icon.className = 'fas fa-check text-success';
        setTimeout(() => {
            icon.className = oldClass;
        }, 1500);
    });
}

function confirmDeleteProd(prodId, prodName, prodCode) {
    Swal.fire({
        title: '¿Eliminar producto?',
        html: `Estás a punto de eliminar <strong>${prodName}</strong> [${prodCode}].<br><span class="text-danger small">Esta acción no se puede deshacer si el producto no tiene transacciones.</span>`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#64748b',
        confirmButtonText: '<i class="fas fa-trash me-1"></i> Sí, eliminar',
        cancelButtonText: 'Cancelar',
        customClass: {
            popup: 'rounded-4'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('delete-prod-form-' + prodId).submit();
        }
    });
}

function openProdModal(prod) {
    document.getElementById('mProdName').textContent = prod.nombre;
    document.getElementById('mProdCategory').textContent = prod.categoria;
    document.getElementById('mProdCode').textContent = prod.codigo;
    document.getElementById('mProdStock').textContent = `${prod.stock} u.`;
    document.getElementById('mProdStockThresholds').textContent = `Mín: ${prod.stock_minimo} | Máx: ${prod.stock_maximo}`;
    document.getElementById('mProdDate').textContent = prod.fecha_ingreso;
    document.getElementById('mProdBuyPrice').textContent = `Bs. ${prod.precio_compra}`;
    document.getElementById('mProdSellPrice').textContent = `Bs. ${prod.precio_venta}`;
    document.getElementById('mProdProfit').textContent = `Bs. ${prod.ganancia} (+${prod.margen}%)`;
    document.getElementById('mProdDesc').textContent = prod.descripcion;
    document.getElementById('mProdEditBtn').href = `/productos/${prod.id}/edit`;

    const badge = document.getElementById('mProdStockBadge');
    badge.className = `badge ${prod.estado_badge}`;
    badge.textContent = prod.estado_label;

    const img = document.getElementById('mProdImage');
    const noImg = document.getElementById('mProdNoImage');
    if (prod.imagen_url && prod.imagen_url.trim() !== '') {
        img.src = prod.imagen_url;
        img.style.display = 'block';
        noImg.style.display = 'none';
    } else {
        img.style.display = 'none';
        noImg.style.display = 'block';
    }

    const modal = new bootstrap.Modal(document.getElementById('prodDetailModal'));
    modal.show();
}
</script>

@endsection