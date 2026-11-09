@extends('dashboard')

@section('content')

<style>
    /* VARIABLES & DESIGN TOKENS */
    :root {
        --cmp-primary: #4f46e5;
        --cmp-primary-dark: #3730a3;
        --cmp-success: #10b981;
        --cmp-info: #0284c7;
        --cmp-warning: #f59e0b;
        --cmp-danger: #ef4444;
        --cmp-dark: #0f172a;
        --cmp-card-bg: #ffffff;
        --cmp-border: #f1f5f9;
    }

    .compras-container {
        padding: 1.25rem 0.75rem;
    }

    /* CARD STYLING */
    .cmp-card {
        background: #ffffff;
        border-radius: 16px;
        border: 1px solid #f1f5f9;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
        margin-bottom: 20px;
        overflow: hidden;
    }

    .cmp-card-header {
        padding: 16px 20px;
        background: #ffffff;
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .cmp-card-title {
        font-size: 0.95rem;
        font-weight: 700;
        color: #1e293b;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .cmp-card-body {
        padding: 20px;
    }

    /* PRODUCT THUMBNAIL */
    .product-thumb {
        width: 44px;
        height: 44px;
        border-radius: 10px;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        flex-shrink: 0;
    }

    .product-thumb img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    /* QUANTITY CONTROLS */
    .qty-control {
        display: inline-flex;
        align-items: center;
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        overflow: hidden;
        background: #ffffff;
    }

    .qty-btn {
        background: #f8fafc;
        border: none;
        width: 28px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #475569;
        font-size: 0.85rem;
        cursor: pointer;
        transition: all 0.15s ease;
    }

    .qty-btn:hover {
        background: #e2e8f0;
        color: #0f172a;
    }

    .qty-input {
        width: 48px;
        height: 32px;
        text-align: center;
        border: none;
        font-weight: 700;
        font-size: 0.9rem;
        color: #1e293b;
    }

    .qty-input:focus {
        outline: none;
        background: #f1f5f9;
    }

    /* PRICE EDIT INPUT */
    .price-edit-input {
        width: 100px;
        height: 32px;
        text-align: right;
        font-weight: 700;
        font-size: 0.9rem;
        border-radius: 8px;
        border: 1px solid #cbd5e1;
        padding: 2px 8px;
        color: #0f172a;
    }

    .price-edit-input:focus {
        border-color: #4f46e5;
        box-shadow: 0 0 0 2px rgba(79, 70, 229, 0.15);
        outline: none;
    }

    /* TOTAL DISPLAY BANNER */
    .total-display-box {
        background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 50%, #312e81 100%);
        border-radius: 14px;
        padding: 20px;
        color: #ffffff;
        text-align: center;
        margin-bottom: 20px;
        box-shadow: 0 10px 25px -5px rgba(49, 46, 129, 0.35);
    }

    .total-display-label {
        font-size: 0.78rem;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: #c7d2fe;
        font-weight: 700;
        margin-bottom: 4px;
    }

    .total-display-amount {
        font-size: 2.2rem;
        font-weight: 800;
        letter-spacing: -0.02em;
        color: #ffffff;
        line-height: 1.1;
    }

    /* PROVEEDOR ACTIVE CARD */
    .provider-active-box {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 14px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        margin-bottom: 16px;
    }

    .provider-avatar {
        width: 44px;
        height: 44px;
        border-radius: 10px;
        background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%);
        color: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 1.1rem;
        flex-shrink: 0;
    }

    /* GRADIENT BUTTONS */
    .btn-gradient-primary {
        background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
        border: none;
        color: #ffffff;
        font-weight: 600;
        box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3);
        transition: all 0.2s ease;
    }

    .btn-gradient-primary:hover {
        background: linear-gradient(135deg, #4338ca 0%, #6d28d9 100%);
        color: #ffffff;
        box-shadow: 0 6px 18px rgba(79, 70, 229, 0.4);
        transform: translateY(-1px);
    }

    /* CATALOG MODAL CARDS */
    .catalog-item-card {
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 12px;
        transition: all 0.2s ease;
        background: #ffffff;
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .catalog-item-card:hover {
        border-color: #4f46e5;
        transform: translateY(-2px);
        box-shadow: 0 8px 16px rgba(79, 70, 229, 0.08);
    }

    .catalog-category-tab {
        font-size: 0.8rem;
        font-weight: 600;
        border-radius: 20px;
        padding: 5px 14px;
        border: 1px solid #e2e8f0;
        background: #ffffff;
        color: #475569;
        cursor: pointer;
        transition: all 0.15s ease;
    }

    .catalog-category-tab:hover,
    .catalog-category-tab.active {
        background: #4f46e5;
        border-color: #4f46e5;
        color: #ffffff;
    }

    .empty-cart-state {
        text-align: center;
        padding: 50px 20px;
        color: #94a3b8;
    }

    .stock-badge-transition {
        font-size: 0.75rem;
        padding: 2px 7px;
        border-radius: 6px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }
</style>

<div class="container-fluid compras-container">

    <!-- HEADER BAR -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <a href="{{ route('compras.index') }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                    <i class="fas fa-arrow-left me-1"></i> Volver a Compras
                </a>
                <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1 rounded-pill">
                    <i class="fas fa-box-open me-1"></i> Ingreso de Inventario
                </span>
            </div>
            <h2 class="fw-bold text-dark m-0 d-flex align-items-center gap-2" style="font-size: 1.55rem; letter-spacing: -0.02em;">
                <i class="fas fa-cart-arrow-down text-primary"></i> Registro de Nueva Compra
            </h2>
            <p class="text-muted small m-0 mt-1">
                Registra compras de mercadería a proveedores y actualiza automáticamente el stock del catálogo.
            </p>
        </div>

        <div class="d-flex align-items-center gap-2">
            <span class="badge bg-light text-muted border px-3 py-2 fw-semibold">
                <i class="far fa-calendar-alt me-1 text-primary"></i> Fecha: {{ now()->format('d/m/Y') }}
            </span>
            <button type="button" class="btn btn-outline-primary rounded-pill px-3 shadow-sm" data-bs-toggle="modal" data-bs-target="#modalCatalogo">
                <i class="fas fa-boxes me-1"></i> Abrir Catálogo (F4)
            </button>
        </div>
    </div>

    <!-- MAIN 2-COLUMN LAYOUT -->
    <div class="row g-4">

        <!-- COLUMNA IZQUIERDA: BÚSQUEDA Y CARRITO DE PRODUCTOS (70%) -->
        <div class="col-xl-8 col-lg-7">

            <!-- BARRA DE BÚSQUEDA / ESCÁNER -->
            <div class="cmp-card">
                <div class="cmp-card-body p-3">
                    <div class="row g-2 align-items-center">

                        <!-- Cantidad rápida -->
                        <div class="col-sm-2 col-3">
                            <label class="form-label text-muted fw-bold mb-1" style="font-size: 0.75rem;">CANTIDAD</label>
                            <input type="number" id="cantidadRapida" class="form-control fw-bold text-center" value="1" min="1" max="9999">
                        </div>

                        <!-- Lector de Código de Barras / Código SKU -->
                        <div class="col-sm-7 col-9">
                            <label class="form-label text-muted fw-bold mb-1" style="font-size: 0.75rem;">CÓDIGO DE BARRAS / CÓDIGO PRODUCTO (F2)</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="fas fa-barcode text-primary"></i>
                                </span>
                                <input type="text"
                                       id="codigoInput"
                                       class="form-control border-start-0"
                                       placeholder="Escanea o escribe el código (ej. PROD-001) y presiona Enter..."
                                       autofocus
                                       autocomplete="off">
                                <button class="btn btn-primary px-3 fw-bold" type="button" onclick="buscarPorCodigo()">
                                    <i class="fas fa-search me-1"></i> Buscar
                                </button>
                            </div>
                        </div>

                        <!-- Botón Catálogo -->
                        <div class="col-sm-3 col-12">
                            <label class="form-label d-none d-sm-block mb-1" style="font-size: 0.75rem;">&nbsp;</label>
                            <button type="button" class="btn btn-light border w-100 fw-semibold text-secondary d-flex align-items-center justify-content-center gap-2" data-bs-toggle="modal" data-bs-target="#modalCatalogo">
                                <i class="fas fa-th-large text-primary"></i>
                                <span>Ver Catálogo</span>
                            </button>
                        </div>

                    </div>
                </div>
            </div>

            <!-- TABLA DE DETALLE DE COMPRA -->
            <div class="cmp-card">
                <div class="cmp-card-header">
                    <div class="cmp-card-title">
                        <i class="fas fa-boxes text-primary"></i>
                        <span>Productos a Ingresar al Inventario</span>
                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill ms-2" id="badgeTotalItems">
                            0 productos
                        </span>
                    </div>

                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="limpiarCarrito()" id="btnLimpiarCarrito" style="display: none;">
                        <i class="fas fa-trash-alt me-1"></i> Vaciar
                    </button>
                </div>

                <div class="table-responsive">
                    <table class="table align-middle mb-0" id="tablaDetalleCompra">
                        <thead class="table-light">
                            <tr style="font-size: 0.78rem; text-transform: uppercase; letter-spacing: 0.05em; color: #475569;">
                                <th class="text-center" style="width: 45px;">#</th>
                                <th>Producto</th>
                                <th class="text-center" style="width: 140px;">Stock Proyectado</th>
                                <th class="text-center" style="width: 130px;">Cantidad</th>
                                <th class="text-end" style="width: 130px;">Costo Unit. (Bs.)</th>
                                <th class="text-end" style="width: 120px;">Subtotal</th>
                                <th class="text-center" style="width: 50px;"></th>
                            </tr>
                        </thead>
                        <tbody id="carritoDetalleBody">
                            <!-- Inyectado dinámicamente -->
                        </tbody>
                    </table>
                </div>

                <!-- EMPTY STATE CUANDO NO HAY PRODUCTOS -->
                <div id="emptyCartMessage" class="empty-cart-state">
                    <div style="font-size: 3rem; color: #cbd5e1; margin-bottom: 12px;">
                        <i class="fas fa-dolly-flatbed"></i>
                    </div>
                    <h5 class="fw-bold text-dark mb-1">El carrito de compra está vacío</h5>
                    <p class="text-muted small mb-3">
                        Escanea un código de barras o selecciona productos del catálogo para agregarlos.
                    </p>
                    <button type="button" class="btn btn-outline-primary btn-sm rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#modalCatalogo">
                        <i class="fas fa-search me-1"></i> Explorar Catálogo de Productos
                    </button>
                </div>
            </div>

        </div>

        <!-- COLUMNA DERECHA: PROVEEDOR, DATOS Y LIQUIDACIÓN (30%) -->
        <div class="col-xl-4 col-lg-5">

            <!-- SELECCIÓN DE PROVEEDOR -->
            <div class="cmp-card">
                <div class="cmp-card-header">
                    <div class="cmp-card-title">
                        <i class="fas fa-truck text-primary"></i>
                        <span>Proveedor</span>
                    </div>

                    <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#modalProveedores">
                        <i class="fas fa-exchange-alt me-1"></i> Cambiar
                    </button>
                </div>

                <div class="cmp-card-body">
                    <!-- HIDDEN FIELD -->
                    <input type="hidden" id="proveedor_id" value="{{ $proveedores->first()->id ?? '' }}">

                    <!-- TARJETA DEL PROVEEDOR SELECCIONADO -->
                    <div class="provider-active-box" id="providerCardBox">
                        <div class="d-flex align-items-center gap-3">
                            <div class="provider-avatar" id="providerAvatar">
                                {{ strtoupper(substr($proveedores->first()->nombre ?? 'P', 0, 2)) }}
                            </div>
                            <div>
                                <h6 class="fw-bold text-dark m-0 mb-1" id="providerNombre">
                                    {{ $proveedores->first()->nombre ?? 'Seleccione Proveedor' }}
                                </h6>
                                <div class="text-muted small" id="providerContacto">
                                    <i class="fas fa-phone-alt me-1 text-primary" style="font-size: 0.72rem;"></i>
                                    {{ $proveedores->first()->telefono ?? 'Sin teléfono' }}
                                </div>
                            </div>
                        </div>

                        <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2 py-1" style="font-size: 0.72rem;">
                            <i class="fas fa-check-circle me-1"></i>Activo
                        </span>
                    </div>

                    <!-- SELECTOR RÁPIDO ALTERNATIVO -->
                    <div>
                        <label class="form-label small fw-bold text-secondary mb-1">
                            Selección rápida de proveedor
                        </label>
                        <select class="form-select form-select-sm" id="proveedorSelectDropdown" onchange="seleccionarProveedorPorSelect(this.value)">
                            @foreach($proveedores as $p)
                                <option value="{{ $p->id }}"
                                        data-nombre="{{ $p->nombre }}"
                                        data-telefono="{{ $p->telefono ?? 'Sin teléfono' }}"
                                        data-email="{{ $p->email ?? 'Sin email' }}"
                                        data-direccion="{{ $p->direccion ?? '' }}"
                                        {{ $loop->first ? 'selected' : '' }}>
                                    {{ $p->nombre }} ({{ $p->telefono ?? 'Teléfono N/D' }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <!-- COMPROBANTE Y DETALLES DE COMPRA -->
            <div class="cmp-card">
                <div class="cmp-card-header">
                    <div class="cmp-card-title">
                        <i class="fas fa-file-invoice text-primary"></i>
                        <span>Datos del Comprobante</span>
                    </div>
                </div>

                <div class="cmp-card-body">
                    <div class="row g-3">
                        <div class="col-6">
                            <label class="form-label small fw-bold text-secondary mb-1">
                                <i class="fas fa-calendar-alt me-1 text-primary"></i>Fecha de Compra
                            </label>
                            <input type="date"
                                   id="fechaCompra"
                                   class="form-control form-control-sm fw-semibold"
                                   value="{{ date('Y-m-d') }}">
                        </div>

                        <div class="col-6">
                            <label class="form-label small fw-bold text-secondary mb-1">
                                <i class="fas fa-receipt me-1 text-primary"></i>N° Factura / Nota
                            </label>
                            <input type="text"
                                   id="comprobanteCompra"
                                   class="form-control form-control-sm"
                                   placeholder="Ej: FAC-9021">
                        </div>
                    </div>
                </div>
            </div>

            <!-- RESUMEN FINANCIERO Y LIQUIDACIÓN -->
            <div class="cmp-card">
                <div class="cmp-card-header">
                    <div class="cmp-card-title">
                        <i class="fas fa-calculator text-primary"></i>
                        <span>Liquidación Total</span>
                    </div>
                </div>

                <div class="cmp-card-body">
                    <div class="d-flex justify-content-between align-items-center mb-2 text-muted small">
                        <span>Total Ítems Distintos:</span>
                        <span class="fw-bold text-dark" id="summaryTotalItems">0</span>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-3 text-muted small">
                        <span>Total Unidades a Ingresar:</span>
                        <span class="fw-bold text-dark" id="summaryTotalUnidades">0</span>
                    </div>

                    <div class="total-display-box">
                        <div class="total-display-label">Gran Total de Compra</div>
                        <div class="total-display-amount" id="totalDisplayAmount">Bs. 0.00</div>
                    </div>

                    <button type="button"
                            class="btn btn-gradient-primary btn-lg w-100 rounded-3 shadow d-flex align-items-center justify-content-center gap-2 py-3"
                            id="btnRegistrarCompra"
                            onclick="confirmarYGuardarCompra()">
                        <i class="fas fa-check-circle fs-5"></i>
                        <span class="fw-bold">Registrar Compra e Ingresar Stock</span>
                    </button>
                    <div class="text-center text-muted small mt-2">
                        Atajo de teclado: <kbd>Ctrl</kbd> + <kbd>Enter</kbd>
                    </div>
                </div>
            </div>

        </div>

    </div>

</div>

<!-- ============================================================== -->
<!-- MODAL 1: CATÁLOGO INTERACTIVO DE PRODUCTOS (F4) -->
<!-- ============================================================== -->
<div class="modal fade" id="modalCatalogo" tabindex="-1" aria-labelledby="modalCatalogoLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            
            <div class="modal-header bg-dark text-white p-3 px-4">
                <div class="d-flex align-items-center gap-2">
                    <div class="p-2 rounded-3 bg-primary text-white">
                        <i class="fas fa-boxes"></i>
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold m-0" style="font-size: 1.15rem;">Catálogo de Productos para Compra</h5>
                        <p class="text-muted small m-0" style="font-size: 0.78rem;">Selecciona los productos y añade unidades a la orden de compra</p>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body p-4 bg-light">
                <!-- BUSCADOR EN EL MODAL -->
                <div class="row g-3 mb-3 align-items-center">
                    <div class="col-md-7">
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0 text-muted">
                                <i class="fas fa-search"></i>
                            </span>
                            <input type="text"
                                   id="buscarEnCatalogo"
                                   class="form-control border-start-0 ps-0"
                                   placeholder="Buscar producto por nombre, código o descripción..."
                                   onkeyup="filtrarCatalogoModal()">
                        </div>
                    </div>

                    <div class="col-md-5 text-end">
                        <span class="badge bg-white text-dark border px-3 py-2 rounded-pill fw-semibold" id="catalogoTotalBadge">
                            {{ $productos->count() }} productos disponibles
                        </span>
                    </div>
                </div>

                <!-- CATEGORY TABS -->
                <div class="d-flex flex-wrap gap-2 mb-4">
                    <button type="button" class="catalog-category-tab active" onclick="filtrarCategoriaCatalogo('todas', this)">
                        <i class="fas fa-layer-group me-1"></i> Todas
                    </button>
                    @foreach($categorias as $cat)
                        <button type="button" class="catalog-category-tab" onclick="filtrarCategoriaCatalogo('{{ $cat->id }}', this)">
                            {{ $cat->nombre }}
                        </button>
                    @endforeach
                </div>

                <!-- GRID DE PRODUCTOS -->
                <div class="row g-3" id="catalogoGrid">
                    @forelse($productos as $prod)
                    <div class="col-12 col-sm-6 col-md-4 col-xl-3 catalogo-prod-item"
                         data-categoria="{{ $prod->categoria_id }}"
                         data-nombre="{{ strtolower($prod->nombre) }}"
                         data-codigo="{{ strtolower($prod->codigo) }}"
                         data-descripcion="{{ strtolower($prod->descripcion ?? '') }}">
                        
                        <div class="catalog-item-card">
                            <div>
                                <div class="d-flex align-items-start justify-content-between mb-2">
                                    <span class="badge bg-light text-muted border font-monospace" style="font-size: 0.72rem;">
                                        {{ $prod->codigo }}
                                    </span>
                                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle" style="font-size: 0.7rem;">
                                        {{ $prod->categoria->nombre ?? 'General' }}
                                    </span>
                                </div>

                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <div class="product-thumb">
                                        @if($prod->imagen && file_exists(public_path('storage/' . $prod->imagen)))
                                            <img src="{{ asset('storage/' . $prod->imagen) }}" alt="{{ $prod->nombre }}">
                                        @else
                                            <i class="fas fa-box-open text-muted" style="font-size: 1.3rem;"></i>
                                        @endif
                                    </div>
                                    <h6 class="fw-bold text-dark m-0 text-truncate" title="{{ $prod->nombre }}" style="font-size: 0.88rem;">
                                        {{ $prod->nombre }}
                                    </h6>
                                </div>

                                <div class="d-flex justify-content-between align-items-center small text-muted mb-2">
                                    <span>Stock Actual:</span>
                                    <span class="fw-bold text-dark badge bg-light border">{{ $prod->stock }} unids.</span>
                                </div>

                                <div class="d-flex justify-content-between align-items-center small text-muted mb-3">
                                    <span>Último Costo:</span>
                                    <span class="fw-bold text-success">Bs. {{ number_format($prod->precio_compra, 2) }}</span>
                                </div>
                            </div>

                            <button type="button"
                                    class="btn btn-outline-primary btn-sm w-100 rounded-3 fw-bold d-flex align-items-center justify-content-center gap-1"
                                    onclick="agregarAlCarrito({{ json_encode($prod) }})">
                                <i class="fas fa-plus"></i> Agregar
                            </button>
                        </div>

                    </div>
                    @empty
                    <div class="col-12 text-center py-5 text-muted">
                        <i class="fas fa-box-open fs-1 mb-2"></i>
                        <p>No hay productos registrados en el catálogo.</p>
                    </div>
                    @endforelse
                </div>
            </div>

            <div class="modal-footer bg-white p-3">
                <button type="button" class="btn btn-secondary btn-sm rounded-pill px-4" data-bs-dismiss="modal">
                    Listo / Cerrar
                </button>
            </div>

        </div>
    </div>
</div>

<!-- ============================================================== -->
<!-- MODAL 2: SELECCIÓN DE PROVEEDORES -->
<!-- ============================================================== -->
<div class="modal fade" id="modalProveedores" tabindex="-1" aria-labelledby="modalProveedoresLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            
            <div class="modal-header bg-dark text-white p-3 px-4">
                <div class="d-flex align-items-center gap-2">
                    <div class="p-2 rounded-3 bg-primary text-white">
                        <i class="fas fa-truck"></i>
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold m-0" style="font-size: 1.15rem;">Seleccionar Proveedor</h5>
                        <p class="text-muted small m-0" style="font-size: 0.78rem;">Elige la empresa proveedora para esta compra</p>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body p-4">
                <div class="mb-3">
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0 text-muted">
                            <i class="fas fa-search"></i>
                        </span>
                        <input type="text"
                               id="buscarProveedorModal"
                               class="form-control border-start-0 ps-0"
                               placeholder="Buscar proveedor por nombre, teléfono o email..."
                               onkeyup="filtrarProveedoresModal()">
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" id="tablaProveedoresModal">
                        <thead class="table-light">
                            <tr style="font-size: 0.78rem; text-transform: uppercase; color: #475569;">
                                <th>Proveedor</th>
                                <th>Teléfono / Contacto</th>
                                <th>Email</th>
                                <th class="text-center" style="width: 110px;">Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($proveedores as $prov)
                            <tr class="proveedor-modal-row"
                                data-nombre="{{ strtolower($prov->nombre) }}"
                                data-telefono="{{ strtolower($prov->telefono ?? '') }}"
                                data-email="{{ strtolower($prov->email ?? '') }}">
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="rounded-circle bg-primary-subtle text-primary d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; font-weight: 700; font-size: 0.8rem;">
                                            {{ strtoupper(substr($prov->nombre, 0, 2)) }}
                                        </div>
                                        <div>
                                            <div class="fw-bold text-dark">{{ $prov->nombre }}</div>
                                            <div class="text-muted small" style="font-size: 0.75rem;">{{ $prov->direccion ?? 'Sin dirección' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="text-dark small fw-semibold">
                                        <i class="fas fa-phone-alt me-1 text-primary" style="font-size: 0.72rem;"></i>
                                        {{ $prov->telefono ?? 'N/D' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="text-muted small">{{ $prov->email ?? 'N/D' }}</span>
                                </td>
                                <td class="text-center">
                                    <button type="button"
                                            class="btn btn-primary btn-sm rounded-pill px-3"
                                            onclick="seleccionarProveedorModal({{ json_encode($prov) }})"
                                            data-bs-dismiss="modal">
                                        Seleccionar
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="modal-footer bg-light p-3">
                <button type="button" class="btn btn-secondary btn-sm rounded-pill px-4" data-bs-dismiss="modal">
                    Cerrar
                </button>
            </div>

        </div>
    </div>
</div>

<!-- ============================================================== -->
<!-- JAVASCRIPT LOGIC -->
<!-- ============================================================== -->
<script>
// Estado de la compra
let carrito = [];

// Catálogo completo precargado en memoria
const productosCatalogo = {!! json_encode($productos) !!};

// 1. ATAJOS DE TECLADO
document.addEventListener('keydown', function(e) {
    // F2 -> Enfocar código
    if (e.key === 'F2') {
        e.preventDefault();
        document.getElementById('codigoInput').focus();
    }
    // F4 -> Abrir catálogo
    if (e.key === 'F4') {
        e.preventDefault();
        let modalCatalogo = new bootstrap.Modal(document.getElementById('modalCatalogo'));
        modalCatalogo.show();
    }
    // Ctrl + Enter o F8 -> Guardar compra
    if ((e.ctrlKey && e.key === 'Enter') || e.key === 'F8') {
        e.preventDefault();
        confirmarYGuardarCompra();
    }
});

// 2. BUSCADOR POR CÓDIGO (CON ENTER)
document.getElementById('codigoInput').addEventListener('keydown', function(e) {
    if (e.key === 'Enter') {
        e.preventDefault();
        buscarPorCodigo();
    }
});

function buscarPorCodigo() {
    let codigo = document.getElementById('codigoInput').value.trim();
    if (!codigo) {
        document.getElementById('codigoInput').focus();
        return;
    }

    let cantRapida = parseInt(document.getElementById('cantidadRapida').value) || 1;

    // Buscar en catálogo local primero
    let producto = productosCatalogo.find(p => p.codigo && p.codigo.toLowerCase() === codigo.toLowerCase());

    if (producto) {
        agregarAlCarrito(producto, cantRapida);
        document.getElementById('codigoInput').value = '';
        document.getElementById('codigoInput').focus();
    } else {
        // Consultar al backend por si hay nuevo producto
        fetch('/buscar-producto/' + encodeURIComponent(codigo))
            .then(res => res.json())
            .then(data => {
                if (data && data.id) {
                    agregarAlCarrito(data, cantRapida);
                    document.getElementById('codigoInput').value = '';
                    document.getElementById('codigoInput').focus();
                } else {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Producto no encontrado',
                        text: 'No se encontró ningún producto con el código: "' + codigo + '"',
                        confirmButtonColor: '#4f46e5'
                    });
                }
            })
            .catch(err => {
                Swal.fire({
                    icon: 'error',
                    title: 'Error de búsqueda',
                    text: 'Ocurrió un error al buscar el producto.',
                    confirmButtonColor: '#ef4444'
                });
            });
    }
}

// 3. AGREGAR AL CARRITO
function agregarAlCarrito(producto, cantidad = 1) {
    let index = carrito.findIndex(item => item.id === producto.id);

    if (index !== -1) {
        // Ya está en el carrito, incrementar cantidad
        carrito[index].cantidad += cantidad;
        carrito[index].subtotal = carrito[index].cantidad * carrito[index].precio;
    } else {
        // Nuevo item en carrito
        let precioCompra = parseFloat(producto.precio_compra) || 0;
        carrito.push({
            id: producto.id,
            codigo: producto.codigo,
            nombre: producto.nombre,
            imagen: producto.imagen || null,
            stock_actual: parseInt(producto.stock) || 0,
            precio: precioCompra,
            cantidad: cantidad,
            subtotal: cantidad * precioCompra
        });
    }

    // Toast feedback
    Swal.fire({
        toast: true,
        position: 'top-end',
        icon: 'success',
        title: `"${producto.nombre}" agregado (+${cantidad})`,
        showConfirmButton: false,
        timer: 1500
    });

    renderCarrito();
}

// 4. CAMBIAR CANTIDAD (+/- o Input)
function cambiarCantidad(index, delta) {
    if (delta !== 0) {
        carrito[index].cantidad += delta;
    }
    if (carrito[index].cantidad < 1) {
        carrito[index].cantidad = 1;
    }
    carrito[index].subtotal = carrito[index].cantidad * carrito[index].precio;
    renderCarrito();
}

function cambiarCantidadInput(index, val) {
    let cant = parseInt(val) || 1;
    if (cant < 1) cant = 1;
    carrito[index].cantidad = cant;
    carrito[index].subtotal = carrito[index].cantidad * carrito[index].precio;
    renderCarrito();
}

// 5. CAMBIAR PRECIO DE COMPRA UNITARIO
function cambiarPrecioInput(index, val) {
    let precio = parseFloat(val) || 0;
    if (precio < 0) precio = 0;
    carrito[index].precio = precio;
    carrito[index].subtotal = carrito[index].cantidad * precio;
    renderCarrito();
}

// 6. ELIMINAR ITEM
function eliminarDelCarrito(index) {
    carrito.splice(index, 1);
    renderCarrito();
}

// 7. VACIAR CARRITO
function limpiarCarrito() {
    if (carrito.length === 0) return;

    Swal.fire({
        title: '¿Vaciar carrito de compras?',
        text: 'Se eliminarán todos los productos seleccionados.',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#64748b',
        confirmButtonText: 'Sí, vaciar',
        cancelButtonText: 'Cancelar'
    }).then((res) => {
        if (res.isConfirmed) {
            carrito = [];
            renderCarrito();
        }
    });
}

// 8. RENDERIZAR TABLA DE CARRITO Y TOTALES
function renderCarrito() {
    let tbody = document.getElementById('carritoDetalleBody');
    let emptyMsg = document.getElementById('emptyCartMessage');
    let btnLimpiar = document.getElementById('btnLimpiarCarrito');

    if (carrito.length === 0) {
        tbody.innerHTML = '';
        emptyMsg.style.display = 'block';
        btnLimpiar.style.display = 'none';

        document.getElementById('badgeTotalItems').innerText = '0 productos';
        document.getElementById('summaryTotalItems').innerText = '0';
        document.getElementById('summaryTotalUnidades').innerText = '0';
        document.getElementById('totalDisplayAmount').innerText = 'Bs. 0.00';
        return;
    }

    emptyMsg.style.display = 'none';
    btnLimpiar.style.display = 'inline-block';

    let html = '';
    let granTotal = 0;
    let totalUnidades = 0;

    carrito.forEach((item, index) => {
        granTotal += item.subtotal;
        totalUnidades += item.cantidad;
        let stockProyectado = item.stock_actual + item.cantidad;

        html += `
        <tr>
            <td class="text-center text-muted fw-bold">${index + 1}</td>
            <td>
                <div class="d-flex align-items-center gap-2">
                    <div class="product-thumb">
                        <i class="fas fa-box-open text-primary"></i>
                    </div>
                    <div>
                        <div class="fw-bold text-dark">${item.nombre}</div>
                        <div class="text-muted small font-monospace" style="font-size: 0.72rem;">Cód: ${item.codigo}</div>
                    </div>
                </div>
            </td>
            <td class="text-center">
                <span class="badge bg-light text-secondary border me-1" title="Stock en almacén">
                    ${item.stock_actual}
                </span>
                <i class="fas fa-arrow-right text-muted" style="font-size: 0.65rem;"></i>
                <span class="badge bg-success-subtle text-success border border-success-subtle ms-1 fw-bold" title="Stock resultante tras compra">
                    ${stockProyectado}
                </span>
            </td>
            <td class="text-center">
                <div class="qty-control">
                    <button type="button" class="qty-btn" onclick="cambiarCantidad(${index}, -1)">
                        <i class="fas fa-minus"></i>
                    </button>
                    <input type="number"
                           class="qty-input"
                           value="${item.cantidad}"
                           min="1"
                           onchange="cambiarCantidadInput(${index}, this.value)">
                    <button type="button" class="qty-btn" onclick="cambiarCantidad(${index}, 1)">
                        <i class="fas fa-plus"></i>
                    </button>
                </div>
            </td>
            <td class="text-end">
                <input type="number"
                       step="0.01"
                       min="0.01"
                       class="price-edit-input"
                       value="${item.precio.toFixed(2)}"
                       onchange="cambiarPrecioInput(${index}, this.value)">
            </td>
            <td class="text-end fw-extrabold text-dark font-monospace" style="font-size: 0.95rem;">
                Bs. ${item.subtotal.toFixed(2)}
            </td>
            <td class="text-center">
                <button type="button" class="btn btn-outline-danger btn-sm p-1 rounded-3" style="width: 28px; height: 28px;" onclick="eliminarDelCarrito(${index})" title="Quitar de la orden">
                    <i class="fas fa-times"></i>
                </button>
            </td>
        </tr>
        `;
    });

    tbody.innerHTML = html;

    // Actualizar badges y display de liquidación
    document.getElementById('badgeTotalItems').innerText = `${carrito.length} ${carrito.length === 1 ? 'producto' : 'productos'}`;
    document.getElementById('summaryTotalItems').innerText = carrito.length;
    document.getElementById('summaryTotalUnidades').innerText = totalUnidades;
    document.getElementById('totalDisplayAmount').innerText = 'Bs. ' + granTotal.toLocaleString('es-BO', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
}

// 9. PROVEEDORES: SELECCIÓN DESDE DROPDOWN
function seleccionarProveedorPorSelect(provId) {
    let select = document.getElementById('proveedorSelectDropdown');
    let opt = select.options[select.selectedIndex];

    document.getElementById('proveedor_id').value = provId;
    document.getElementById('providerNombre').innerText = opt.getAttribute('data-nombre') || 'Proveedor';
    document.getElementById('providerContacto').innerHTML = `<i class="fas fa-phone-alt me-1 text-primary"></i> ` + (opt.getAttribute('data-telefono') || 'Sin teléfono');
    document.getElementById('providerAvatar').innerText = (opt.getAttribute('data-nombre') || 'P').substring(0, 2).toUpperCase();
}

// 10. PROVEEDORES: SELECCIÓN DESDE MODAL
function seleccionarProveedorModal(prov) {
    document.getElementById('proveedor_id').value = prov.id;
    document.getElementById('providerNombre').innerText = prov.nombre;
    document.getElementById('providerContacto').innerHTML = `<i class="fas fa-phone-alt me-1 text-primary"></i> ` + (prov.telefono || 'Sin teléfono');
    document.getElementById('providerAvatar').innerText = prov.nombre.substring(0, 2).toUpperCase();

    // Actualizar también el select
    document.getElementById('proveedorSelectDropdown').value = prov.id;
}

// 11. FILTRO DE PROVEEDORES EN EL MODAL
function filtrarProveedoresModal() {
    let text = document.getElementById('buscarProveedorModal').value.toLowerCase().trim();
    let rows = document.querySelectorAll('#tablaProveedoresModal tbody tr.proveedor-modal-row');

    rows.forEach(r => {
        let nombre = r.getAttribute('data-nombre') || '';
        let tel = r.getAttribute('data-telefono') || '';
        let email = r.getAttribute('data-email') || '';
        let match = nombre.includes(text) || tel.includes(text) || email.includes(text);
        r.style.display = match ? '' : 'none';
    });
}

// 12. FILTROS DEL MODAL CATÁLOGO (POR CATEGORÍA Y BUSCADOR)
let categoriaActualCatalogo = 'todas';

function filtrarCategoriaCatalogo(catId, btnElement) {
    categoriaActualCatalogo = catId;
    document.querySelectorAll('.catalog-category-tab').forEach(b => b.classList.remove('active'));
    btnElement.classList.add('active');
    filtrarCatalogoModal();
}

function filtrarCatalogoModal() {
    let query = document.getElementById('buscarEnCatalogo').value.toLowerCase().trim();
    let items = document.querySelectorAll('#catalogoGrid .catalogo-prod-item');
    let visibleCount = 0;

    items.forEach(item => {
        let cat = item.getAttribute('data-categoria') || '';
        let nombre = item.getAttribute('data-nombre') || '';
        let codigo = item.getAttribute('data-codigo') || '';
        let desc = item.getAttribute('data-descripcion') || '';

        let matchCat = (categoriaActualCatalogo === 'todas') || (cat === categoriaActualCatalogo);
        let matchQuery = !query || nombre.includes(query) || codigo.includes(query) || desc.includes(query);

        if (matchCat && matchQuery) {
            item.style.display = '';
            visibleCount++;
        } else {
            item.style.display = 'none';
        }
    });

    document.getElementById('catalogoTotalBadge').innerText = `${visibleCount} productos encontrados`;
}

// 13. CONFIRMAR Y GUARDAR COMPRA
function confirmarYGuardarCompra() {
    if (carrito.length === 0) {
        Swal.fire({
            icon: 'warning',
            title: 'Carrito vacío',
            text: 'Debes agregar al menos un producto a la compra antes de registrarla.',
            confirmButtonColor: '#4f46e5'
        });
        return;
    }

    let proveedorId = document.getElementById('proveedor_id').value;
    if (!proveedorId) {
        Swal.fire({
            icon: 'warning',
            title: 'Selecciona un proveedor',
            text: 'Por favor selecciona el proveedor emisor de la compra.',
            confirmButtonColor: '#4f46e5'
        });
        return;
    }

    let fecha = document.getElementById('fechaCompra').value;
    let comprobante = document.getElementById('comprobanteCompra').value.trim();
    let proveedorNombre = document.getElementById('providerNombre').innerText;

    let totalCalculado = carrito.reduce((acc, curr) => acc + curr.subtotal, 0);
    let totalUnidades = carrito.reduce((acc, curr) => acc + curr.cantidad, 0);

    Swal.fire({
        title: '¿Registrar esta Compra?',
        html: `
            <div class="text-start p-3 bg-light rounded-3 my-2" style="font-size: 0.9rem;">
                <p class="mb-1"><strong>Proveedor:</strong> ${proveedorNombre}</p>
                <p class="mb-1"><strong>Fecha:</strong> ${fecha}</p>
                <p class="mb-1"><strong>Comprobante:</strong> ${comprobante || 'Sin comprobante'}</p>
                <p class="mb-1"><strong>Unidades a ingresar:</strong> ${totalUnidades} unidades</p>
                <hr class="my-2">
                <p class="mb-0 fs-6 text-success"><strong>Monto Total:</strong> Bs. ${totalCalculado.toFixed(2)}</p>
            </div>
            <p class="small text-muted mb-0">El stock de los productos seleccionados aumentará inmediatamente.</p>
        `,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#4f46e5',
        cancelButtonColor: '#64748b',
        confirmButtonText: '<i class="fas fa-check me-1"></i> Sí, confirmar compra',
        cancelButtonText: 'Cancelar',
        customClass: {
            popup: 'rounded-4'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            enviarCompraServidor(proveedorId, fecha, comprobante, totalCalculado);
        }
    });
}

// 14. ENVIAR DATOS VÍA AJAX AL BACKEND
function enviarCompraServidor(proveedorId, fecha, comprobante, total) {
    let btn = document.getElementById('btnRegistrarCompra');
    btn.disabled = true;
    btn.innerHTML = `<span class="spinner-border spinner-border-sm me-2"></span> Procesando compra...`;

    // Formatear detalles
    let detallesPayload = carrito.map(item => ({
        id: item.id,
        cantidad: item.cantidad,
        precio: item.precio
    }));

    fetch("{{ route('compras.store') }}", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": "{{ csrf_token() }}",
            "Accept": "application/json"
        },
        body: JSON.stringify({
            proveedor_id: proveedorId,
            fecha: fecha,
            comprobante: comprobante,
            total: total,
            detalles: detallesPayload
        })
    })
    .then(async response => {
        const data = await response.json();

        if (!response.ok || !data.success) {
            throw new Error(data.error || 'Ocurrió un error al procesar la compra.');
        }

        Swal.fire({
            icon: 'success',
            title: '¡Compra Registrada con Éxito!',
            text: 'El inventario y stock de los productos han sido actualizados satisfactoriamente.',
            confirmButtonColor: '#4f46e5',
            confirmButtonText: 'Ver Listado de Compras',
            customClass: {
                popup: 'rounded-4'
            }
        }).then(() => {
            window.location.href = "{{ route('compras.index') }}";
        });
    })
    .catch(error => {
        btn.disabled = false;
        btn.innerHTML = `<i class="fas fa-check-circle fs-5"></i> <span class="fw-bold">Registrar Compra e Ingresar Stock</span>`;

        Swal.fire({
            icon: 'error',
            title: 'Error en la Compra',
            text: error.message,
            confirmButtonColor: '#ef4444'
        });
    });
}

// Inicializar render
renderCarrito();
</script>

@endsection