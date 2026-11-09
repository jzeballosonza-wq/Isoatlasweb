@extends('dashboard')

@section('content')

<style>
    /* VARIABLES & POS DESIGN TOKENS */
    :root {
        --pos-primary: #4f46e5;
        --pos-primary-dark: #3730a3;
        --pos-success: #10b981;
        --pos-info: #0284c7;
        --pos-warning: #f59e0b;
        --pos-danger: #ef4444;
        --pos-dark: #0f172a;
    }

    .pos-card {
        background: #ffffff;
        border-radius: 16px;
        border: 1px solid #f1f5f9;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
        margin-bottom: 20px;
        overflow: hidden;
    }

    .pos-card-header {
        padding: 16px 20px;
        background: #ffffff;
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .pos-card-title {
        font-size: 1rem;
        font-weight: 700;
        color: #1e293b;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .pos-card-body {
        padding: 20px;
    }

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

    /* QUANTITY BUTTONS */
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
        width: 45px;
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

    /* RESUMEN DE PAGO */
    .total-display-box {
        background: linear-gradient(135deg, #1e1b4b 0%, #312e81 50%, #4338ca 100%);
        border-radius: 14px;
        padding: 20px;
        color: #ffffff;
        text-align: center;
        margin-bottom: 20px;
        box-shadow: 0 10px 25px -5px rgba(67, 56, 202, 0.3);
    }

    .total-display-label {
        font-size: 0.8rem;
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

    /* CLIENT CARD */
    .client-active-box {
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

    .client-avatar {
        width: 42px;
        height: 42px;
        border-radius: 10px;
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 1.1rem;
        flex-shrink: 0;
    }

    /* PAYMENT METHODS BUTTONS */
    .pay-btn-group .btn-check:checked + .btn {
        background-color: #4f46e5;
        border-color: #4f46e5;
        color: #ffffff;
        font-weight: 600;
        box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3);
    }

    /* BILLETES RÁPIDOS */
    .cash-quick-btn {
        background: #f1f5f9;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 5px 10px;
        font-size: 0.78rem;
        font-weight: 700;
        color: #475569;
        cursor: pointer;
        transition: all 0.15s ease;
    }

    .cash-quick-btn:hover {
        background: #e2e8f0;
        color: #1e293b;
        transform: translateY(-1px);
    }

    .empty-cart-state {
        text-align: center;
        padding: 50px 20px;
        color: #94a3b8;
    }

    /* GRADIENT UPDATE BUTTON */
    .btn-gradient-success {
        background: linear-gradient(135deg, #059669 0%, #10b981 100%);
        border: none;
        color: #ffffff;
        font-weight: 700;
        box-shadow: 0 4px 14px rgba(16, 185, 129, 0.35);
        transition: all 0.2s ease;
    }

    .btn-gradient-success:hover {
        background: linear-gradient(135deg, #047857 0%, #059669 100%);
        color: #ffffff;
        box-shadow: 0 6px 20px rgba(16, 185, 129, 0.45);
        transform: translateY(-1px);
    }
</style>

<div class="container-fluid px-3 px-md-4 py-3">

    <!-- HEADER BAR -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <a href="{{ route('ventas.index') }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                    <i class="fas fa-arrow-left me-1"></i> Volver a Ventas
                </a>
                <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-2.5 py-1 rounded-pill fw-bold" style="background: rgba(245, 158, 11, 0.12); color: #d97706;">
                    <i class="fas fa-edit me-1"></i> Modo Edición • Venta #{{ $venta->id }}
                </span>
                @if($venta->comprobante)
                    <a href="{{ route('ventas.comprobante', $venta->id) }}" target="_blank" class="badge bg-primary-subtle text-primary border border-primary-subtle text-decoration-none px-2.5 py-1 rounded-pill fw-semibold">
                        <i class="fas fa-file-invoice me-1"></i> Ver Comprobante
                    </a>
                @endif
            </div>
            <h2 class="fw-extrabold text-dark m-0" style="font-size: 1.55rem; letter-spacing: -0.02em;">
                Modificar Venta y Comprobante N° {{ $venta->id }}
            </h2>
            <p class="text-muted small m-0 mt-1">
                Añade o retira productos, ajusta cantidades y actualiza la liquidación. El inventario se recalculará de forma segura.
            </p>
        </div>

        <div class="d-flex align-items-center gap-2">
            <span class="badge bg-light text-muted border px-3 py-2 fw-semibold">
                <i class="far fa-calendar-alt me-1 text-primary"></i> Fecha Original: {{ \Carbon\Carbon::parse($venta->fecha)->format('d/m/Y') }}
            </span>
            <button type="button" class="btn btn-outline-primary rounded-pill px-3 shadow-sm" data-bs-toggle="modal" data-bs-target="#modalCatalogo">
                <i class="fas fa-boxes me-1"></i> Catálogo de Productos (F4)
            </button>
        </div>
    </div>

    <!-- MAIN 2-COLUMN LAYOUT -->
    <div class="row g-4">

        <!-- COLUMNA IZQUIERDA: BÚSQUEDA Y CARRITO DE PRODUCTOS (70%) -->
        <div class="col-xl-8 col-lg-7">

            <!-- BARRA DE BÚSQUEDA / ESCÁNER -->
            <div class="pos-card">
                <div class="pos-card-body p-3">
                    <div class="row g-2 align-items-center">

                        <!-- Cantidad rápida -->
                        <div class="col-sm-2 col-3">
                            <label class="form-label text-muted fw-bold mb-1" style="font-size: 0.75rem;">CANTIDAD</label>
                            <input type="number" id="cantidadInput" class="form-control fw-bold text-center" value="1" min="1" max="999">
                        </div>

                        <!-- Lector de Código de Barras / Código -->
                        <div class="col-sm-7 col-9">
                            <label class="form-label text-muted fw-bold mb-1" style="font-size: 0.75rem;">CÓDIGO DE BARRAS / CÓDIGO PRODUCTO (F2)</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="fas fa-barcode text-primary"></i>
                                </span>
                                <input type="text" id="codigoInput" class="form-control border-start-0" placeholder="Escanea el código o escribe el código..." autofocus autocomplete="off">
                                <button class="btn btn-primary px-3 fw-bold" type="button" id="btnBuscarCodigo" onclick="buscarPorCodigo()">
                                    <i class="fas fa-search me-1"></i> Buscar
                                </button>
                            </div>
                        </div>

                        <!-- Botón Catálogo -->
                        <div class="col-sm-3 col-12">
                            <label class="form-label d-none d-sm-block text-transparent mb-1" style="font-size: 0.75rem;">ACCIÓN</label>
                            <button type="button" class="btn btn-dark w-100 fw-semibold" data-bs-toggle="modal" data-bs-target="#modalCatalogo">
                                <i class="fas fa-boxes-stacked me-1 text-warning"></i> Catálogo
                            </button>
                        </div>

                    </div>

                    <!-- Previsualización instantánea de producto encontrado -->
                    <div id="previewProductoBox" class="mt-3 p-3 bg-light rounded-3 border d-none">
                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                            <div class="d-flex align-items-center gap-3">
                                <div class="product-thumb bg-white" id="previewThumb">
                                    <i class="fas fa-box-open text-muted"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0 fw-bold text-dark" id="previewNombre">Nombre del Producto</h6>
                                    <div class="d-flex align-items-center gap-2 mt-1">
                                        <span class="badge bg-secondary" id="previewCodigo">COD-001</span>
                                        <span class="badge bg-success" id="previewStock">Stock Disponible: 10</span>
                                        <span class="fw-bold text-primary" id="previewPrecio" style="font-size: 0.95rem;">Bs. 0.00</span>
                                    </div>
                                </div>
                            </div>

                            <button type="button" class="btn btn-success fw-bold px-3" onclick="agregarProductoActual()">
                                <i class="fas fa-cart-plus me-1"></i> Añadir a la Venta
                            </button>
                        </div>
                    </div>

                </div>
            </div>

            <!-- TABLA DEL CARRITO -->
            <div class="pos-card">
                <div class="pos-card-header">
                    <h3 class="pos-card-title">
                        <i class="fas fa-shopping-cart text-primary"></i>
                        Productos en la Venta (<span id="cartItemsCount">0</span> artículos)
                    </h3>
                    <button type="button" class="btn btn-xs btn-outline-danger rounded-pill px-2 py-1" onclick="vaciarCarrito()" id="btnVaciarCarrito" style="display: none;">
                        <i class="fas fa-trash-alt me-1"></i> Vaciar Carrito
                    </button>
                </div>

                <div class="pos-card-body p-0">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0" id="tablaDetalle">
                            <thead class="table-light">
                                <tr style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em; color: #64748b;">
                                    <th class="text-center" style="width: 45px;">#</th>
                                    <th>Producto</th>
                                    <th class="text-center" style="width: 130px;">Stock Disp.</th>
                                    <th class="text-end" style="width: 120px;">Precio Unit.</th>
                                    <th class="text-center" style="width: 140px;">Cantidad</th>
                                    <th class="text-end" style="width: 120px;">Subtotal</th>
                                    <th class="text-center" style="width: 50px;"></th>
                                </tr>
                            </thead>
                            <tbody id="cartTableBody">
                                <!-- INYECTADO POR JS -->
                            </tbody>
                        </table>
                    </div>

                    <!-- ESTADO VACÍO -->
                    <div id="cartEmptyState" class="empty-cart-state" style="display: none;">
                        <div class="mb-3">
                            <i class="fas fa-shopping-basket fa-3x" style="color: #cbd5e1;"></i>
                        </div>
                        <h6 class="fw-bold text-secondary">No hay productos en esta venta</h6>
                        <p class="small text-muted mb-3">Escanea el código de barras o busca en el catálogo para añadir artículos.</p>
                        <button type="button" class="btn btn-sm btn-primary rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#modalCatalogo">
                            <i class="fas fa-search me-1"></i> Explorar Catálogo de Productos
                        </button>
                    </div>

                </div>

                <!-- FOOTER DE TOTALES RÁPIDOS -->
                <div class="p-3 bg-light border-top d-flex justify-content-between align-items-center">
                    <span class="text-muted small fw-semibold">
                        Total Unidades Vendidas: <strong class="text-dark" id="totalUnidadesFooter">0</strong>
                    </span>
                    <span class="fw-bold text-dark">
                        Subtotal Carrito: <span class="text-primary" id="subtotalCarritoFooter">Bs. 0.00</span>
                    </span>
                </div>

            </div>

        </div>

        <!-- COLUMNA DERECHA: CLIENTE, LIQUIDACIÓN Y PAGO (30%) -->
        <div class="col-xl-4 col-lg-5">

            <!-- 1. INFORMACIÓN DEL CLIENTE -->
            <div class="pos-card">
                <div class="pos-card-header">
                    <h3 class="pos-card-title">
                        <i class="fas fa-user-check text-primary"></i>
                        Datos del Cliente (F8)
                    </h3>
                    <div class="dropdown">
                        <button class="btn btn-xs btn-outline-primary rounded-pill px-2 py-1 dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            Opciones
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0">
                            <li>
                                <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#modalClientes">
                                    <i class="fas fa-search me-2 text-primary"></i> Buscar Cliente
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="#" onclick="asignarClienteGeneral()">
                                    <i class="fas fa-user-tag me-2 text-success"></i> Cliente Casual (Sin NIT)
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#modalNuevoCliente">
                                    <i class="fas fa-user-plus me-2 text-info"></i> + Registrar Nuevo Cliente
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="pos-card-body">

                    <!-- TARJETA DEL CLIENTE ACTIVO -->
                    <div class="client-active-box" id="clientActiveCard">
                        <div class="d-flex align-items-center gap-3 overflow-hidden">
                            <div class="client-avatar" id="clientAvatarLetter">C</div>
                            <div class="overflow-hidden">
                                <h6 class="mb-0 fw-bold text-dark text-truncate" id="clientDisplayNombre">CLIENTE GENERAL</h6>
                                <div class="small text-muted" id="clientDisplayNit">NIT/CI: S/N</div>
                            </div>
                        </div>

                        <button type="button" class="btn btn-sm btn-outline-secondary rounded-circle" data-bs-toggle="modal" data-bs-target="#modalClientes" title="Cambiar Cliente">
                            <i class="fas fa-pen"></i>
                        </button>
                    </div>

                    <!-- INPUTS OCULTOS DE CLIENTE -->
                    <input type="hidden" id="cliente_id" value="1">
                    <input type="hidden" id="cliente_nombre" value="CLIENTE GENERAL">
                    <input type="hidden" id="nit" value="0">

                    <!-- FECHA DE VENTA -->
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label fw-bold text-muted" style="font-size: 0.75rem;">FECHA DE LA VENTA</label>
                            <input type="date" id="fechaVenta" class="form-control form-control-sm fw-semibold" value="{{ $venta->fecha }}">
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-bold text-muted" style="font-size: 0.75rem;">N° COMPROBANTE / FACTURA</label>
                            <input type="text" id="comprobanteVenta" class="form-control form-control-sm" placeholder="Ej: VTA-001" value="{{ $venta->comprobante }}">
                        </div>
                    </div>

                    <!-- TIPO DE PAGO -->
                    <div class="mb-2">
                        <label class="form-label fw-bold text-muted" style="font-size: 0.75rem;">MÉTODO DE PAGO</label>
                        <div class="btn-group w-100 pay-btn-group" role="group">
                            <input type="radio" class="btn-check" name="metodoPago" id="payEfectivo" autocomplete="off" checked>
                            <label class="btn btn-outline-secondary btn-sm py-2" for="payEfectivo">
                                <i class="fas fa-money-bill-wave me-1"></i> Efectivo
                            </label>

                            <input type="radio" class="btn-check" name="metodoPago" id="payQR" autocomplete="off">
                            <label class="btn btn-outline-secondary btn-sm py-2" for="payQR">
                                <i class="fas fa-qrcode me-1"></i> QR / Transf.
                            </label>

                            <input type="radio" class="btn-check" name="metodoPago" id="payTarjeta" autocomplete="off">
                            <label class="btn btn-outline-secondary btn-sm py-2" for="payTarjeta">
                                <i class="fas fa-credit-card me-1"></i> Tarjeta
                            </label>
                        </div>
                    </div>

                </div>
            </div>

            <!-- 2. LIQUIDACIÓN FINANCIERA & COBRO -->
            <div class="pos-card">
                <div class="pos-card-body">

                    <!-- GRAN TOTAL -->
                    <div class="total-display-box">
                        <div class="total-display-label">TOTAL ACTUALIZADO A COBRAR</div>
                        <div class="total-display-amount">
                            <span style="font-size: 1.4rem;">Bs.</span> <span id="granTotalDisplay">{{ number_format($venta->total, 2) }}</span>
                        </div>
                    </div>

                    <!-- CALCULADORA DE CAMBIO / VUELTO -->
                    <div class="mb-3">
                        <label class="form-label fw-bold text-muted d-flex justify-content-between" style="font-size: 0.75rem;">
                            <span>PAGO EN EFECTIVO (MONTO RECIBIDO)</span>
                            <span class="text-primary cursor-pointer" onclick="pagarExacto()" style="cursor: pointer;">Pagar Exacto</span>
                        </label>
                        <div class="input-group mb-2">
                            <span class="input-group-text bg-light fw-bold">Bs.</span>
                            <input type="number" id="montoRecibido" class="form-control form-control-lg fw-bold" placeholder="0.00" step="0.5" oninput="calcularCambio()">
                        </div>

                        <!-- Billetes rápidos de Bolivia -->
                        <div class="d-flex flex-wrap gap-1 mb-3">
                            <button type="button" class="cash-quick-btn" onclick="sumarEfectivo(20)">+20 Bs</button>
                            <button type="button" class="cash-quick-btn" onclick="sumarEfectivo(50)">+50 Bs</button>
                            <button type="button" class="cash-quick-btn" onclick="sumarEfectivo(100)">+100 Bs</button>
                            <button type="button" class="cash-quick-btn" onclick="sumarEfectivo(200)">+200 Bs</button>
                            <button type="button" class="cash-quick-btn" onclick="pagarExacto()">Exacto</button>
                        </div>

                        <!-- Vuelto a entregar -->
                        <div class="p-3 bg-light rounded-3 border d-flex justify-content-between align-items-center">
                            <span class="fw-bold text-secondary" style="font-size: 0.85rem;">Cambio / Vuelto:</span>
                            <span class="fw-bold" id="cambioDisplay" style="font-size: 1.15rem; color: #059669;">
                                Bs. 0.00
                            </span>
                        </div>
                    </div>

                    <!-- BOTÓN PRINCIPAL DE GUARDAR VENTA -->
                    <button type="button"
                            class="btn btn-gradient-success btn-lg w-100 py-3 shadow-sm rounded-3 d-flex align-items-center justify-content-center gap-2"
                            id="btnGuardarVenta"
                            onclick="confirmarYActualizarVenta()">
                        <i class="fas fa-save fs-5"></i>
                        <span>GUARDAR CAMBIOS Y RECALCULAR STOCK</span>
                    </button>
                    
                    <div class="text-center text-muted small mt-2">
                        Atajo de teclado: <kbd>Ctrl</kbd> + <kbd>Enter</kbd>
                    </div>

                </div>
            </div>

        </div>

    </div>

</div>

<!-- ========================================== -->
<!-- MODAL 1: CATÁLOGO DE PRODUCTOS / ACCESORIOS -->
<!-- ========================================== -->
<div class="modal fade" id="modalCatalogo" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg rounded-4">

            <div class="modal-header bg-light border-bottom px-4 py-3">
                <div class="d-flex align-items-center gap-2">
                    <div style="width: 36px; height: 36px; border-radius: 10px; background: var(--primary-gradient); color: #fff; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-boxes-stacked"></i>
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold text-dark mb-0">Catálogo de Productos & Accesorios</h5>
                        <small class="text-muted">Selecciona productos para añadirlos a la venta actual</small>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body p-4">

                <!-- FILTROS DE BÚSQUEDA -->
                <div class="row g-3 mb-4">
                    <div class="col-md-8">
                        <div class="input-group">
                            <span class="input-group-text bg-white">
                                <i class="fas fa-search text-muted"></i>
                            </span>
                            <input type="text" id="filtroCatalogoTexto" class="form-control" placeholder="Buscar por código, nombre de producto, accesorio o marca..." onkeyup="filtrarCatalogo()">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <select id="filtroCatalogoCategoria" class="form-select" onchange="filtrarCatalogo()">
                            <option value="">Todas las Categorías</option>
                            @foreach($categorias ?? [] as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- TABLA DEL CATÁLOGO -->
                <div class="table-responsive">
                    <table class="table table-hover align-middle border" id="tablaCatalogo">
                        <thead class="table-light">
                            <tr style="font-size: 0.78rem; text-transform: uppercase; letter-spacing: 0.05em; color: #64748b;">
                                <th style="width: 70px;">Imagen</th>
                                <th>Código</th>
                                <th>Nombre & Especificaciones</th>
                                <th>Categoría</th>
                                <th class="text-center">Stock Disp.</th>
                                <th class="text-end">Precio Venta</th>
                                <th class="text-center" style="width: 110px;">Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($productos ?? [] as $prod)
                            <tr class="item-catalogo" 
                                data-id="{{ $prod->id }}"
                                data-codigo="{{ strtolower($prod->codigo) }}"
                                data-nombre="{{ strtolower($prod->nombre) }}"
                                data-desc="{{ strtolower($prod->descripcion ?? '') }}"
                                data-categoria="{{ $prod->categoria_id }}"
                                data-precio="{{ $prod->precio_venta }}"
                                data-stock="{{ $prod->stock }}">
                                
                                <td>
                                    <div class="product-thumb">
                                        @if($prod->imagen)
                                            <img src="{{ asset('storage/' . $prod->imagen) }}" alt="{{ $prod->nombre }}">
                                        @else
                                            <i class="fas fa-box-open text-muted"></i>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border">{{ $prod->codigo }}</span>
                                </td>
                                <td>
                                    <div class="fw-bold text-dark" style="font-size: 0.88rem;">{{ $prod->nombre }}</div>
                                    <small class="text-muted text-truncate d-inline-block" style="max-width: 320px;">
                                        {{ $prod->descripcion ?? 'Sin descripción adicional' }}
                                    </small>
                                </td>
                                <td>
                                    <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle">
                                        {{ $prod->categoria->nombre ?? 'General' }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    @php
                                        // Verificar si este producto ya estaba en la venta original
                                        $enVentaOriginal = collect($detalles)->firstWhere('id', $prod->id);
                                        $stockParaEdicion = $prod->stock + ($enVentaOriginal ? $enVentaOriginal['cantidad'] : 0);
                                    @endphp
                                    @if($stockParaEdicion <= 0)
                                        <span class="badge bg-danger rounded-pill px-2 py-1">Agotado (0)</span>
                                    @elseif($stockParaEdicion <= ($prod->stock_minimo ?? 3))
                                        <span class="badge bg-warning text-dark rounded-pill px-2 py-1">{{ $stockParaEdicion }} unids</span>
                                    @else
                                        <span class="badge bg-success rounded-pill px-2 py-1">{{ $stockParaEdicion }} unids</span>
                                    @endif
                                </td>
                                <td class="text-end fw-bold text-primary" style="font-size: 0.95rem;">
                                    Bs. {{ number_format($prod->precio_venta, 2) }}
                                </td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-sm btn-primary rounded-pill px-3 fw-semibold" onclick="agregarDesdeCatalogo({{ json_encode($prod) }})">
                                        <i class="fas fa-plus me-1"></i> Añadir
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">
                                    No hay productos registrados en el inventario.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>

            <div class="modal-footer bg-light border-top px-4 py-3">
                <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">
                    Cerrar
                </button>
            </div>

        </div>
    </div>
</div>

<!-- ========================================== -->
<!-- MODAL 2: SELECCIONAR CLIENTE -->
<!-- ========================================== -->
<div class="modal fade" id="modalClientes" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg rounded-4">

            <div class="modal-header bg-light border-bottom px-4 py-3">
                <div class="d-flex align-items-center gap-2">
                    <div style="width: 36px; height: 36px; border-radius: 10px; background: var(--info-gradient); color: #fff; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-user-friends"></i>
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold text-dark mb-0">Seleccionar Cliente</h5>
                        <small class="text-muted">Elige un cliente registrado o regístralo al instante</small>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body p-4">

                <!-- BÚSQUEDA Y BOTÓN NUEVO -->
                <div class="d-flex gap-2 mb-3">
                    <div class="input-group flex-grow-1">
                        <span class="input-group-text bg-white">
                            <i class="fas fa-search text-muted"></i>
                        </span>
                        <input type="text" id="buscarClienteInput" class="form-control" placeholder="Escribe el nombre o NIT/CI del cliente..." onkeyup="filtrarClientesModal()">
                    </div>
                    <button type="button" class="btn btn-outline-success fw-semibold" onclick="abrirModalNuevoCliente()">
                        <i class="fas fa-user-plus me-1"></i> Nuevo Cliente
                    </button>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle border text-center" id="tablaModalClientes">
                        <thead class="table-light">
                            <tr style="font-size: 0.78rem; text-transform: uppercase; color: #64748b;">
                                <th>#</th>
                                <th class="text-start">Nombre Completo</th>
                                <th>NIT / CI</th>
                                <th>Teléfono</th>
                                <th>Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- CLIENTE GENERAL POR DEFECTO -->
                            <tr class="fila-cliente" data-nombre="cliente general casual" data-nit="0 sin nit">
                                <td><span class="badge bg-primary">0</span></td>
                                <td class="text-start fw-bold text-dark">
                                    <i class="fas fa-user-tag text-primary me-1"></i> CLIENTE GENERAL (CASUAL)
                                </td>
                                <td><span class="badge bg-light text-dark border">S/N</span></td>
                                <td>-</td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-primary rounded-pill px-3" onclick="seleccionarCliente('1', 'CLIENTE GENERAL', '0')" data-bs-dismiss="modal">
                                        Elegir
                                    </button>
                                </td>
                            </tr>

                            @foreach($clientes ?? [] as $idx => $c)
                            <tr class="fila-cliente" data-nombre="{{ strtolower($c->nombre) }}" data-nit="{{ strtolower($c->nit ?? '') }}">
                                <td>{{ $idx + 1 }}</td>
                                <td class="text-start fw-semibold text-dark">{{ $c->nombre }}</td>
                                <td>
                                    <span class="badge bg-light text-dark border">{{ $c->nit ?: 'Sin NIT' }}</span>
                                </td>
                                <td>{{ $c->telefono ?: '-' }}</td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-3" onclick="seleccionarCliente('{{ $c->id }}', '{{ addslashes($c->nombre) }}', '{{ $c->nit ?: '0' }}')" data-bs-dismiss="modal">
                                        Seleccionar
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            </div>

            <div class="modal-footer bg-light border-top px-4 py-3">
                <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">
                    Cerrar
                </button>
            </div>

        </div>
    </div>
</div>

<!-- ========================================== -->
<!-- MODAL 3: REGISTRO RÁPIDO DE CLIENTE (AJAX) -->
<!-- ========================================== -->
<div class="modal fade" id="modalNuevoCliente" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow-lg rounded-4">

            <div class="modal-header bg-light border-bottom px-4 py-3">
                <h5 class="modal-title fw-bold text-dark">
                    <i class="fas fa-user-plus text-success me-2"></i> Registrar Nuevo Cliente
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form id="formNuevoCliente" onsubmit="guardarClienteRapido(event)">
                @csrf
                <div class="modal-body p-4">

                    <div class="mb-3">
                        <label class="form-label fw-bold text-dark" style="font-size: 0.85rem;">Nombre o Razón Social *</label>
                        <input type="text" name="nombre" id="nuevoClienteNombre" class="form-control" placeholder="Ej: Juan Pérez o Empresa SRL" required>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-6">
                            <label class="form-label fw-bold text-dark" style="font-size: 0.85rem;">NIT / CI</label>
                            <input type="text" name="nit" id="nuevoClienteNit" class="form-control" placeholder="Ej: 1234567">
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-bold text-dark" style="font-size: 0.85rem;">Teléfono / WhatsApp</label>
                            <input type="text" name="telefono" id="nuevoClienteTelefono" class="form-control" placeholder="Ej: 78901234">
                        </div>
                    </div>

                    <div class="mb-2">
                        <label class="form-label fw-bold text-dark" style="font-size: 0.85rem;">Correo Electrónico</label>
                        <input type="email" name="email" id="nuevoClienteEmail" class="form-control" placeholder="cliente@correo.com">
                    </div>

                </div>

                <div class="modal-footer bg-light border-top px-4 py-3">
                    <button type="button" class="btn btn-secondary rounded-pill px-3" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success rounded-pill px-4 fw-bold" id="btnGuardarClienteAjax">
                        <i class="fas fa-save me-1"></i> Guardar y Seleccionar
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>

<!-- ========================================== -->
<!-- JAVASCRIPT DEL MÓDULO DE VENTAS (POS) -->
<!-- ========================================== -->
<script>
// Carrito inicializado con los ítems existentes de la venta
let carrito = {!! json_encode($detalles) !!};
let productoEncontrado = null;

// Catálogo de productos precargado
const productosCatalogo = {!! json_encode($productos) !!};

// Inicializar tabla al cargar
document.addEventListener('DOMContentLoaded', function() {
    renderizarCarrito();
});

// Toast Notifier
const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 2000,
    timerProgressBar: true
});

// 1. ATAJOS DE TECLADO
document.addEventListener('keydown', function(e) {
    if (e.key === 'F2') {
        e.preventDefault();
        document.getElementById('codigoInput').focus();
    }
    if (e.key === 'F4') {
        e.preventDefault();
        const modal = new bootstrap.Modal(document.getElementById('modalCatalogo'));
        modal.show();
    }
    if (e.key === 'F8') {
        e.preventDefault();
        const modal = new bootstrap.Modal(document.getElementById('modalClientes'));
        modal.show();
    }
    if ((e.ctrlKey && e.key === 'Enter') || e.key === 'F9') {
        e.preventDefault();
        confirmarYActualizarVenta();
    }
});

// 2. BUSCADOR POR CÓDIGO
document.getElementById('codigoInput').addEventListener('keydown', function(e) {
    if (e.key === 'Enter') {
        e.preventDefault();
        buscarPorCodigo();
    }
});

function buscarPorCodigo() {
    const codigo = document.getElementById('codigoInput').value.trim();
    if (!codigo) {
        Toast.fire({ icon: 'warning', title: 'Ingresa un código de barras' });
        return;
    }

    let prod = productosCatalogo.find(p => p.codigo.toLowerCase() === codigo.toLowerCase());

    if (prod) {
        mostrarPreviewProducto(prod);
    } else {
        fetch(`/buscar-producto-venta/${encodeURIComponent(codigo)}`)
            .then(res => res.json())
            .then(data => {
                if (data && data.id) {
                    mostrarPreviewProducto(data);
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Producto no encontrado',
                        text: `No existe ningún producto con el código "${codigo}".`,
                        confirmButtonColor: '#4f46e5'
                    });
                }
            })
            .catch(() => {
                Toast.fire({ icon: 'error', title: 'Error al buscar el producto' });
            });
    }
}

function mostrarPreviewProducto(prod) {
    productoEncontrado = prod;

    // Calcular el stock disponible para este producto considerando si ya estaba en la venta original
    let itemEnCarrito = carrito.find(it => it.id === prod.id);
    let stockBase = parseInt(prod.stock) || 0;
    let stockMaximo = itemEnCarrito ? (itemEnCarrito.stock_disponible || (stockBase + itemEnCarrito.cantidad)) : stockBase;

    document.getElementById('previewNombre').innerText = prod.nombre;
    document.getElementById('previewCodigo').innerText = prod.codigo;
    document.getElementById('previewStock').innerText = `Stock Disponible: ${stockMaximo}`;
    document.getElementById('previewStock').className = stockMaximo > 0 ? 'badge bg-success' : 'badge bg-danger';
    document.getElementById('previewPrecio').innerText = `Bs. ${parseFloat(prod.precio_venta).toFixed(2)}`;

    const thumb = document.getElementById('previewThumb');
    if (prod.imagen) {
        thumb.innerHTML = `<img src="/storage/${prod.imagen}" alt="${prod.nombre}">`;
    } else {
        thumb.innerHTML = `<i class="fas fa-box-open text-muted"></i>`;
    }

    document.getElementById('previewProductoBox').classList.remove('d-none');
    agregarProductoActual();
}

function agregarProductoActual() {
    if (!productoEncontrado) return;

    let cant = parseInt(document.getElementById('cantidadInput').value) || 1;
    let itemExistente = carrito.find(it => it.id === productoEncontrado.id);

    let stockBase = parseInt(productoEncontrado.stock) || 0;
    let stockDisponible = itemExistente 
        ? (itemExistente.stock_disponible || (stockBase + itemExistente.cantidad)) 
        : stockBase;

    let cantidadTotalDeseada = (itemExistente ? itemExistente.cantidad : 0) + cant;

    if (cantidadTotalDeseada > stockDisponible) {
        Swal.fire({
            icon: 'warning',
            title: 'Stock Insuficiente',
            text: `Solo dispones de ${stockDisponible} unidad(es) de "${productoEncontrado.nombre}" en inventario.`,
            confirmButtonColor: '#4f46e5'
        });
        return;
    }

    if (itemExistente) {
        itemExistente.cantidad += cant;
        itemExistente.subtotal = itemExistente.cantidad * itemExistente.precio;
    } else {
        let precio = parseFloat(productoEncontrado.precio_venta) || 0;
        carrito.push({
            id: productoEncontrado.id,
            codigo: productoEncontrado.codigo,
            nombre: productoEncontrado.nombre,
            categoria: productoEncontrado.categoria ? productoEncontrado.categoria.nombre : 'General',
            precio: precio,
            cantidad: cant,
            subtotal: cant * precio,
            imagen: productoEncontrado.imagen || null,
            stock_disponible: stockDisponible,
            stock_actual: stockBase
        });
    }

    // Resetear buscador
    document.getElementById('codigoInput').value = '';
    document.getElementById('cantidadInput').value = '1';
    document.getElementById('previewProductoBox').classList.add('d-none');
    productoEncontrado = null;

    renderizarCarrito();
    Toast.fire({ icon: 'success', title: 'Producto añadido al carrito' });
}

function agregarDesdeCatalogo(prod) {
    let itemExistente = carrito.find(it => it.id === prod.id);
    let stockBase = parseInt(prod.stock) || 0;
    let stockDisponible = itemExistente 
        ? (itemExistente.stock_disponible || (stockBase + itemExistente.cantidad)) 
        : stockBase;

    let cantidadActual = itemExistente ? itemExistente.cantidad : 0;

    if (cantidadActual + 1 > stockDisponible) {
        Toast.fire({ icon: 'warning', title: `Stock máximo alcanzado (${stockDisponible} unids)` });
        return;
    }

    if (itemExistente) {
        itemExistente.cantidad += 1;
        itemExistente.subtotal = itemExistente.cantidad * itemExistente.precio;
    } else {
        let precio = parseFloat(prod.precio_venta) || 0;
        carrito.push({
            id: prod.id,
            codigo: prod.codigo,
            nombre: prod.nombre,
            categoria: prod.categoria ? prod.categoria.nombre : 'General',
            precio: precio,
            cantidad: 1,
            subtotal: precio,
            imagen: prod.imagen || null,
            stock_disponible: stockDisponible,
            stock_actual: stockBase
        });
    }

    renderizarCarrito();
    Toast.fire({ icon: 'success', title: `Añadido: ${prod.nombre}` });
}

// 3. RENDERIZAR TABLA DE PRODUCTOS
function renderizarCarrito() {
    const tbody = document.getElementById('cartTableBody');
    const emptyState = document.getElementById('cartEmptyState');
    const btnVaciar = document.getElementById('btnVaciarCarrito');
    const countSpan = document.getElementById('cartItemsCount');

    if (carrito.length === 0) {
        tbody.innerHTML = '';
        emptyState.style.display = 'block';
        btnVaciar.style.display = 'none';
        countSpan.innerText = '0';
        actualizarLiquidacion(0, 0);
        return;
    }

    emptyState.style.display = 'none';
    btnVaciar.style.display = 'inline-block';
    countSpan.innerText = carrito.length;

    let html = '';
    let granTotal = 0;
    let totalUnidades = 0;

    carrito.forEach((item, index) => {
        let precio = parseFloat(item.precio) || 0;
        let cant = parseInt(item.cantidad) || 1;
        let subtotal = cant * precio;
        granTotal += subtotal;
        totalUnidades += cant;

        let stockDisp = item.stock_disponible !== undefined ? item.stock_disponible : (item.stock_actual || 0);

        let imgHtml = item.imagen 
            ? `<img src="/storage/${item.imagen}" alt="${item.nombre}">` 
            : `<i class="fas fa-box-open text-muted"></i>`;

        html += `
            <tr class="align-middle">
                <td class="text-center text-muted fw-bold" style="font-size: 0.8rem;">${index + 1}</td>
                <td>
                    <div class="d-flex align-items-center gap-2">
                        <div class="product-thumb">
                            ${imgHtml}
                        </div>
                        <div style="min-width: 0;">
                            <div class="fw-bold text-dark text-truncate" style="max-width: 250px;" title="${item.nombre}">
                                ${item.nombre}
                            </div>
                            <div class="text-muted small" style="font-size: 0.72rem;">
                                <span class="badge bg-light text-muted border font-monospace me-1">${item.codigo}</span>
                                <span>${item.categoria || ''}</span>
                            </div>
                        </div>
                    </div>
                </td>
                <td class="text-center">
                    <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2 py-1" style="font-size: 0.75rem;">
                        ${stockDisp} unids.
                    </span>
                </td>
                <td class="text-end fw-semibold text-secondary" style="font-size: 0.88rem;">
                    Bs. ${precio.toFixed(2)}
                </td>
                <td class="text-center">
                    <div class="qty-control">
                        <button type="button" class="qty-btn" onclick="ajustarCantidad(${index}, -1)">
                            <i class="fas fa-minus"></i>
                        </button>
                        <input type="number" 
                               class="qty-input" 
                               value="${cant}" 
                               min="1" 
                               max="${stockDisp}" 
                               onchange="cambiarCantidadDirecta(${index}, this.value)">
                        <button type="button" class="qty-btn" onclick="ajustarCantidad(${index}, 1)">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                </td>
                <td class="text-end fw-bold text-primary" style="font-size: 0.95rem;">
                    Bs. ${subtotal.toLocaleString('es-BO', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}
                </td>
                <td class="text-center">
                    <button type="button" 
                            class="btn btn-sm btn-outline-danger rounded-circle d-inline-flex align-items-center justify-content-center" 
                            style="width: 30px; height: 30px;" 
                            onclick="eliminarItem(${index})" 
                            title="Quitar producto de la venta">
                        <i class="fas fa-trash-alt" style="font-size: 0.78rem;"></i>
                    </button>
                </td>
            </tr>
        `;
    });

    tbody.innerHTML = html;
    actualizarLiquidacion(totalUnidades, granTotal);
}

// 4. AJUSTE DE CANTIDADES
function ajustarCantidad(index, delta) {
    if (!carrito[index]) return;

    let nueva = carrito[index].cantidad + delta;
    let stockMax = carrito[index].stock_disponible !== undefined ? carrito[index].stock_disponible : 999;

    if (nueva > stockMax) {
        Toast.fire({ icon: 'warning', title: `Stock máximo disponible: ${stockMax} unids` });
        return;
    }

    if (nueva >= 1) {
        carrito[index].cantidad = nueva;
        carrito[index].subtotal = nueva * carrito[index].precio;
        renderizarCarrito();
    }
}

function cambiarCantidadDirecta(index, valor) {
    let cant = parseInt(valor);
    if (isNaN(cant) || cant < 1) cant = 1;

    let stockMax = carrito[index].stock_disponible !== undefined ? carrito[index].stock_disponible : 999;
    if (cant > stockMax) {
        cant = stockMax;
        Toast.fire({ icon: 'warning', title: `Stock máximo disponible: ${stockMax} unids` });
    }

    if (carrito[index]) {
        carrito[index].cantidad = cant;
        carrito[index].subtotal = cant * carrito[index].precio;
        renderizarCarrito();
    }
}

function eliminarItem(index) {
    if (carrito[index]) {
        let nombre = carrito[index].nombre;
        carrito.splice(index, 1);
        renderizarCarrito();
        Toast.fire({ icon: 'info', title: `Eliminado: ${nombre}` });
    }
}

function vaciarCarrito() {
    Swal.fire({
        title: '¿Vaciar la venta?',
        text: 'Se removerán todos los artículos cargados en este comprobante.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#64748b',
        confirmButtonText: 'Sí, vaciar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            carrito = [];
            renderizarCarrito();
            Toast.fire({ icon: 'info', title: 'Venta vaciada' });
        }
    });
}

// 5. LIQUIDACIÓN FINANCIERA & CÁLCULO DE VUELTO
function actualizarLiquidacion(unidades, total) {
    document.getElementById('totalUnidadesFooter').innerText = unidades;
    document.getElementById('subtotalCarritoFooter').innerText = `Bs. ${total.toLocaleString('es-BO', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;
    document.getElementById('granTotalDisplay').innerText = total.toLocaleString('es-BO', { minimumFractionDigits: 2, maximumFractionDigits: 2 });

    calcularCambio();
}

function calcularCambio() {
    let total = carrito.reduce((sum, it) => sum + (it.cantidad * it.precio), 0);
    let recibidoInput = document.getElementById('montoRecibido');
    let recibido = parseFloat(recibidoInput.value) || 0;

    let display = document.getElementById('cambioDisplay');

    if (recibido === 0) {
        display.innerText = 'Bs. 0.00';
        display.style.color = '#059669';
        return;
    }

    let cambio = recibido - total;

    if (cambio >= 0) {
        display.innerText = `Bs. ${cambio.toFixed(2)}`;
        display.style.color = '#059669';
    } else {
        display.innerText = `Faltan Bs. ${Math.abs(cambio).toFixed(2)}`;
        display.style.color = '#ef4444';
    }
}

function pagarExacto() {
    let total = carrito.reduce((sum, it) => sum + (it.cantidad * it.precio), 0);
    document.getElementById('montoRecibido').value = total.toFixed(2);
    calcularCambio();
}

function sumarEfectivo(monto) {
    let recibidoInput = document.getElementById('montoRecibido');
    let actual = parseFloat(recibidoInput.value) || 0;
    recibidoInput.value = (actual + monto).toFixed(2);
    calcularCambio();
}

// 6. CLIENTE SELECTOR
function seleccionarCliente(id, nombre, nit) {
    document.getElementById('cliente_id').value = id;
    document.getElementById('cliente_nombre').value = nombre;
    document.getElementById('nit').value = nit;

    document.getElementById('clientDisplayNombre').innerText = nombre;
    document.getElementById('clientDisplayNit').innerText = `NIT/CI: ${nit || 'S/N'}`;
    document.getElementById('clientAvatarLetter').innerText = nombre.charAt(0).toUpperCase();

    Toast.fire({ icon: 'success', title: `Cliente: ${nombre}` });
}

function asignarClienteGeneral() {
    seleccionarCliente('1', 'CLIENTE GENERAL', '0');
}

function filtrarClientesModal() {
    let query = document.getElementById('buscarClienteInput').value.toLowerCase().trim();
    let filas = document.querySelectorAll('.fila-cliente');
    filas.forEach(f => {
        let nombre = f.getAttribute('data-nombre');
        let nit = f.getAttribute('data-nit');
        if (nombre.includes(query) || nit.includes(query)) {
            f.style.display = '';
        } else {
            f.style.display = 'none';
        }
    });
}

function abrirModalNuevoCliente() {
    let modalClientes = bootstrap.Modal.getInstance(document.getElementById('modalClientes'));
    if (modalClientes) modalClientes.hide();

    let modalNuevo = new bootstrap.Modal(document.getElementById('modalNuevoCliente'));
    modalNuevo.show();
}

function guardarClienteRapido(e) {
    e.preventDefault();

    let btn = document.getElementById('btnGuardarClienteAjax');
    btn.disabled = true;
    btn.innerHTML = `<span class="spinner-border spinner-border-sm me-1"></span> Guardando...`;

    let form = document.getElementById('formNuevoCliente');
    let formData = new FormData(form);

    fetch("{{ route('clientes.store') }}", {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        },
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        btn.disabled = false;
        btn.innerHTML = `<i class="fas fa-save me-1"></i> Guardar y Seleccionar`;

        if (data.success && data.cliente) {
            seleccionarCliente(data.cliente.id, data.cliente.nombre, data.cliente.nit || '0');
            let modalNuevo = bootstrap.Modal.getInstance(document.getElementById('modalNuevoCliente'));
            if (modalNuevo) modalNuevo.hide();
            form.reset();
            Toast.fire({ icon: 'success', title: 'Cliente registrado y seleccionado' });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error al registrar',
                text: data.mensaje || 'Revisa los datos ingresados.'
            });
        }
    })
    .catch(() => {
        btn.disabled = false;
        btn.innerHTML = `<i class="fas fa-save me-1"></i> Guardar y Seleccionar`;
        Toast.fire({ icon: 'error', title: 'Error de servidor' });
    });
}

// 7. FILTROS CATÁLOGO
function filtrarCatalogo() {
    let query = document.getElementById('filtroCatalogoTexto').value.toLowerCase().trim();
    let catId = document.getElementById('filtroCatalogoCategoria').value;

    let items = document.querySelectorAll('.item-catalogo');
    items.forEach(it => {
        let codigo = it.getAttribute('data-codigo');
        let nombre = it.getAttribute('data-nombre');
        let desc = it.getAttribute('data-desc');
        let cat = it.getAttribute('data-categoria');

        let matchTexto = !query || codigo.includes(query) || nombre.includes(query) || desc.includes(query);
        let matchCat = !catId || cat === catId;

        if (matchTexto && matchCat) {
            it.style.display = '';
        } else {
            it.style.display = 'none';
        }
    });
}

// 8. CONFIRMAR Y ACTUALIZAR VENTA
function confirmarYActualizarVenta() {
    if (carrito.length === 0) {
        Swal.fire({
            icon: 'warning',
            title: 'Venta vacía',
            text: 'Debes añadir al menos un producto a la venta.',
            confirmButtonColor: '#4f46e5'
        });
        return;
    }

    let fecha = document.getElementById('fechaVenta').value;
    let comprobante = document.getElementById('comprobanteVenta').value;
    let totalMonto = carrito.reduce((sum, it) => sum + (it.cantidad * it.precio), 0);

    Swal.fire({
        title: '¿Confirmar actualización de venta?',
        html: `Se actualizará la <b>Venta N° {{ $venta->id }}</b> por un total de <b>Bs. ${totalMonto.toFixed(2)}</b> y se sincronizará el stock en inventario.`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#10b981',
        cancelButtonColor: '#64748b',
        confirmButtonText: '<i class="fas fa-save me-1"></i> Sí, guardar cambios',
        cancelButtonText: 'Revisar',
        customClass: { popup: 'rounded-4' }
    }).then((result) => {
        if (result.isConfirmed) {
            enviarActualizacionVenta(fecha, comprobante, totalMonto);
        }
    });
}

function enviarActualizacionVenta(fecha, comprobante, totalMonto) {
    let btn = document.getElementById('btnGuardarVenta');
    btn.disabled = true;
    btn.innerHTML = `<span class="spinner-border spinner-border-sm me-2"></span> Actualizando venta y stock...`;

    let payload = {
        _method: 'PUT',
        fecha: fecha,
        comprobante: comprobante,
        total: totalMonto,
        detalles: carrito.map(it => ({
            id: it.id,
            cantidad: parseInt(it.cantidad),
            precio: parseFloat(it.precio)
        }))
    };

    fetch("{{ route('ventas.update', $venta->id) }}", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify(payload)
    })
    .then(res => res.json())
    .then(data => {
        btn.disabled = false;
        btn.innerHTML = `<i class="fas fa-save fs-5"></i> <span>GUARDAR CAMBIOS Y RECALCULAR STOCK</span>`;

        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: '¡Venta Actualizada con Éxito!',
                text: data.mensaje || 'La venta y el inventario han sido actualizados correctamente.',
                showCancelButton: true,
                confirmButtonColor: '#4f46e5',
                cancelButtonColor: '#10b981',
                confirmButtonText: '<i class="fas fa-list me-1"></i> Volver al Listado',
                cancelButtonText: '<i class="fas fa-file-invoice me-1"></i> Ver Comprobante',
                customClass: { popup: 'rounded-4' }
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "{{ route('ventas.index') }}";
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    window.location.href = data.comprobante_url || "{{ route('ventas.comprobante', $venta->id) }}";
                }
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'No se pudo actualizar',
                text: data.error || 'Ocurrió un error al procesar la actualización.',
                confirmButtonColor: '#ef4444'
            });
        }
    })
    .catch(err => {
        btn.disabled = false;
        btn.innerHTML = `<i class="fas fa-save fs-5"></i> <span>GUARDAR CAMBIOS Y RECALCULAR STOCK</span>`;
        Swal.fire({
            icon: 'error',
            title: 'Error de Servidor',
            text: 'No se pudo comunicar con el servidor.',
            confirmButtonColor: '#ef4444'
        });
    });
}
</script>

@endsection