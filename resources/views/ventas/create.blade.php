@extends('dashboard')

@section('content')

<style>
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

    /* CANTIDAD BUTTONS */
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
        background: linear-gradient(135deg, #6366f1 0%, #3b82f6 100%);
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
</style>

<div class="container-fluid px-3 px-md-4 py-3">

    <!-- HEADER BAR -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <a href="{{ route('ventas.index') }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                    <i class="fas fa-arrow-left me-1"></i> Volver a Ventas
                </a>
                @if(isset($cajaActiva) && $cajaActiva)
                    <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-1 rounded-pill d-inline-flex align-items-center gap-1.5 shadow-xs">
                        <i class="fas fa-circle text-success animate-pulse" style="font-size: 0.5rem;"></i>
                        <span>Caja Activa: <strong>{{ $cajaActiva->caja_nombre }}</strong></span>
                    </span>
                @else
                    <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle px-2.5 py-1 rounded-pill small">
                        <i class="fas fa-info-circle me-1"></i> Sin turno de caja abierto
                    </span>
                @endif
            </div>
            <h2 class="fw-extrabold text-dark m-0" style="font-size: 1.55rem; letter-spacing: -0.02em;">
                Terminal de Punto de Venta (POS)
            </h2>
        </div>

        <div class="d-flex align-items-center gap-2">
            <span class="badge bg-light text-muted border px-3 py-2 fw-semibold">
                <i class="far fa-calendar-alt me-1 text-primary"></i> Fecha: {{ now()->format('d/m/Y') }}
            </span>
            <button type="button" class="btn btn-outline-primary rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#modalCatalogo">
                <i class="fas fa-boxes me-1"></i> Abrir Catálogo (F4)
            </button>
        </div>
    </div>

    <!-- MAIN 2-COLUMN LAYOUT -->
    <div class="row g-4">

        <!-- COLUMNA IZQUIERDA: BÚSQUEDA Y CARRITO DE PRODUCTOS -->
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
                                        <span class="badge bg-success" id="previewStock">Stock: 10</span>
                                        <span class="fw-bold text-primary" id="previewPrecio" style="font-size: 0.95rem;">Bs. 0.00</span>
                                    </div>
                                </div>
                            </div>

                            <button type="button" class="btn btn-success fw-bold px-3" onclick="agregarProductoActual()">
                                <i class="fas fa-cart-plus me-1"></i> Agregar al Carrito
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
                        Detalle de la Venta (<span id="cartItemsCount">0</span> productos)
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
                                    <th class="text-center" style="width: 50px;">#</th>
                                    <th>Producto</th>
                                    <th class="text-center" style="width: 120px;">Precio Unit.</th>
                                    <th class="text-center" style="width: 140px;">Cantidad</th>
                                    <th class="text-end" style="width: 120px;">Subtotal</th>
                                    <th class="text-center" style="width: 60px;"></th>
                                </tr>
                            </thead>
                            <tbody id="cartTableBody">
                                <!-- VISTAS DINÁMICAS POR JS -->
                            </tbody>
                        </table>
                    </div>

                    <!-- ESTADO VACÍO -->
                    <div id="cartEmptyState" class="empty-cart-state">
                        <div class="mb-3">
                            <i class="fas fa-shopping-basket fa-3x" style="color: #cbd5e1;"></i>
                        </div>
                        <h6 class="fw-bold text-secondary">El carrito de venta está vacío</h6>
                        <p class="small text-muted mb-3">Escanea el código de barras o busca en el catálogo para añadir artículos.</p>
                        <button type="button" class="btn btn-sm btn-primary rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#modalCatalogo">
                            <i class="fas fa-search me-1"></i> Explorar Catálogo de Productos
                        </button>
                    </div>

                </div>

                <!-- FOOTER DE TOTALES RÁPIDOS -->
                <div class="p-3 bg-light border-top d-flex justify-content-between align-items-center">
                    <span class="text-muted small fw-semibold">
                        Total Unidades: <strong class="text-dark" id="totalUnidadesFooter">0</strong>
                    </span>
                    <span class="fw-bold text-dark">
                        Subtotal Carrito: <span class="text-primary" id="subtotalCarritoFooter">Bs. 0.00</span>
                    </span>
                </div>

            </div>

        </div>

        <!-- COLUMNA DERECHA: CLIENTE, LIQUIDACIÓN Y PAGO -->
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
                    <div class="mb-3">
                        <label class="form-label fw-bold text-muted" style="font-size: 0.75rem;">FECHA DE LA VENTA</label>
                        <input type="date" id="fechaVenta" class="form-control" value="{{ date('Y-m-d') }}">
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
                        <div class="total-display-label">TOTAL A COBRAR</div>
                        <div class="total-display-amount">
                            <span style="font-size: 1.4rem;">Bs.</span> <span id="granTotalDisplay">0.00</span>
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
                    <button type="button" class="btn btn-primary btn-lg w-100 fw-bold py-3 shadow-sm rounded-3" id="btnGuardarVenta" onclick="procesarVenta()" style="background: linear-gradient(135deg, #059669 0%, #10b981 100%); border: none; font-size: 1.05rem;">
                        <i class="fas fa-check-circle me-2"></i> CONFIRMAR Y REGISTRAR VENTA
                    </button>

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
                        <small class="text-muted">Selecciona productos para añadirlos inmediatamente al carrito</small>
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
                                <th class="text-center">Stock</th>
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
                                    @if($prod->stock <= 0)
                                        <span class="badge bg-danger rounded-pill px-2 py-1">Agotado (0)</span>
                                    @elseif($prod->stock <= ($prod->stock_minimo ?? 3))
                                        <span class="badge bg-warning text-dark rounded-pill px-2 py-1">{{ $prod->stock }} unids</span>
                                    @else
                                        <span class="badge bg-success rounded-pill px-2 py-1">{{ $prod->stock }} unids</span>
                                    @endif
                                </td>
                                <td class="text-end fw-bold text-primary" style="font-size: 0.95rem;">
                                    Bs. {{ number_format($prod->precio_venta, 2) }}
                                </td>
                                <td class="text-center">
                                    @if($prod->stock > 0)
                                        <button type="button" class="btn btn-sm btn-primary rounded-pill px-3 fw-semibold" onclick="agregarDesdeCatalogo({{ json_encode($prod) }})">
                                            <i class="fas fa-plus me-1"></i> Añadir
                                        </button>
                                    @else
                                        <button type="button" class="btn btn-sm btn-secondary rounded-pill px-3" disabled>
                                            Sin Stock
                                        </button>
                                    @endif
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
let carrito = [];
let productoEncontrado = null;

// TOAST NOTIFIER
const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 2500,
    timerProgressBar: true
});

// 1. ATAJOS DE TECLADO
document.addEventListener('keydown', function(e) {
    // F2: Enfocar escáner
    if (e.key === 'F2') {
        e.preventDefault();
        document.getElementById('codigoInput').focus();
    }
    // F4: Abrir catálogo
    if (e.key === 'F4') {
        e.preventDefault();
        const modal = new bootstrap.Modal(document.getElementById('modalCatalogo'));
        modal.show();
    }
    // F8: Abrir clientes
    if (e.key === 'F8') {
        e.preventDefault();
        const modal = new bootstrap.Modal(document.getElementById('modalClientes'));
        modal.show();
    }
    // Enter en input de código
    if (e.target.id === 'codigoInput' && e.key === 'Enter') {
        e.preventDefault();
        buscarPorCodigo(true); // true = auto agregar
    }
});

// 2. BUSCAR PRODUCTO POR CÓDIGO (Lector o Manual)
function buscarPorCodigo(autoAgregar = false) {
    const codigo = document.getElementById('codigoInput').value.trim();
    if (!codigo) {
        Toast.fire({ icon: 'warning', title: 'Ingresa o escanea un código' });
        return;
    }

    fetch('/buscar-producto-venta/' + encodeURIComponent(codigo))
        .then(res => res.json())
        .then(data => {
            if (data && data.id) {
                productoEncontrado = data;

                if (autoAgregar) {
                    agregarProductoActual();
                    document.getElementById('codigoInput').value = '';
                    document.getElementById('codigoInput').focus();
                } else {
                    mostrarPreview(data);
                }
            } else {
                Toast.fire({
                    icon: 'error',
                    title: 'Producto no encontrado con el código: ' + codigo
                });
                ocultarPreview();
            }
        })
        .catch(err => {
            console.error(err);
            Toast.fire({ icon: 'error', title: 'Error al buscar el producto' });
        });
}

function mostrarPreview(prod) {
    const box = document.getElementById('previewProductoBox');
    document.getElementById('previewNombre').innerText = prod.nombre;
    document.getElementById('previewCodigo').innerText = 'COD: ' + prod.codigo;
    document.getElementById('previewStock').innerText = 'Stock: ' + prod.stock;
    document.getElementById('previewPrecio').innerText = 'Bs. ' + parseFloat(prod.precio_venta).toFixed(2);

    const thumb = document.getElementById('previewThumb');
    if (prod.imagen) {
        thumb.innerHTML = `<img src="/storage/${prod.imagen}" alt="">`;
    } else {
        thumb.innerHTML = `<i class="fas fa-box-open text-muted"></i>`;
    }

    box.classList.remove('d-none');
}

function ocultarPreview() {
    productoEncontrado = null;
    document.getElementById('previewProductoBox').classList.add('d-none');
}

// 3. AGREGAR PRODUCTO AL CARRITO
function agregarProductoActual() {
    if (!productoEncontrado) {
        Toast.fire({ icon: 'warning', title: 'Primero busca un producto' });
        return;
    }

    const cantidadInput = parseInt(document.getElementById('cantidadInput').value) || 1;
    agregarAlCarrito(productoEncontrado, cantidadInput);
    ocultarPreview();
    document.getElementById('cantidadInput').value = '1';
}

function agregarDesdeCatalogo(prod) {
    agregarAlCarrito(prod, 1);
    Toast.fire({
        icon: 'success',
        title: `"${prod.nombre}" añadido al carrito`
    });
}

function agregarAlCarrito(prod, cantidadDeseada) {
    if (prod.stock <= 0) {
        Swal.fire({
            icon: 'error',
            title: 'Sin Stock',
            text: `El producto "${prod.nombre}" se encuentra agotado.`,
            confirmButtonColor: '#ef4444'
        });
        return;
    }

    const index = carrito.findIndex(item => item.id === prod.id);

    if (index !== -1) {
        // Ya existe en carrito: validar stock acumulado
        const nuevaCant = carrito[index].cantidad + cantidadDeseada;
        if (nuevaCant > prod.stock) {
            Swal.fire({
                icon: 'warning',
                title: 'Límite de Stock',
                text: `Solo hay ${prod.stock} unidades disponibles de "${prod.nombre}". Ya tienes ${carrito[index].cantidad} en el carrito.`,
                confirmButtonColor: '#f59e0b'
            });
            return;
        }
        carrito[index].cantidad = nuevaCant;
        carrito[index].subtotal = nuevaCant * carrito[index].precio;
    } else {
        // Nuevo item en carrito
        if (cantidadDeseada > prod.stock) {
            Swal.fire({
                icon: 'warning',
                title: 'Stock Insuficiente',
                text: `Solo hay ${prod.stock} unidades disponibles de "${prod.nombre}".`,
                confirmButtonColor: '#f59e0b'
            });
            return;
        }

        carrito.push({
            id: prod.id,
            codigo: prod.codigo,
            nombre: prod.nombre,
            descripcion: prod.descripcion || '',
            imagen: prod.imagen || null,
            stock: prod.stock,
            precio: parseFloat(prod.precio_venta),
            cantidad: cantidadDeseada,
            subtotal: cantidadDeseada * parseFloat(prod.precio_venta)
        });
    }

    renderizarCarrito();
}

// 4. RENDERIZAR TABLA DE CARRITO
function renderizarCarrito() {
    const tbody = document.getElementById('cartTableBody');
    const emptyState = document.getElementById('cartEmptyState');
    const btnVaciar = document.getElementById('btnVaciarCarrito');

    if (carrito.length === 0) {
        tbody.innerHTML = '';
        emptyState.style.display = 'block';
        btnVaciar.style.display = 'none';
        actualizarTotales(0, 0);
        return;
    }

    emptyState.style.display = 'none';
    btnVaciar.style.display = 'inline-block';

    let html = '';
    let totalGeneral = 0;
    let totalUnidades = 0;

    carrito.forEach((item, idx) => {
        totalGeneral += item.subtotal;
        totalUnidades += item.cantidad;

        const imgHtml = item.imagen 
            ? `<img src="/storage/${item.imagen}" alt="">` 
            : `<i class="fas fa-box-open text-muted"></i>`;

        html += `
        <tr>
            <td class="text-center fw-bold text-muted" style="font-size: 0.85rem;">${idx + 1}</td>
            <td>
                <div class="d-flex align-items-center gap-2">
                    <div class="product-thumb" style="width: 36px; height: 36px;">
                        ${imgHtml}
                    </div>
                    <div>
                        <div class="fw-bold text-dark" style="font-size: 0.85rem;">${item.nombre}</div>
                        <small class="text-muted">Cod: ${item.codigo} | Stock: ${item.stock}</small>
                    </div>
                </div>
            </td>
            <td class="text-center fw-semibold" style="font-size: 0.88rem;">
                Bs. ${item.precio.toFixed(2)}
            </td>
            <td class="text-center">
                <div class="qty-control">
                    <button type="button" class="qty-btn" onclick="modificarCantidad(${idx}, -1)">
                        <i class="fas fa-minus"></i>
                    </button>
                    <input type="text" class="qty-input" value="${item.cantidad}" onchange="cambiarCantidadDirecta(${idx}, this.value)">
                    <button type="button" class="qty-btn" onclick="modificarCantidad(${idx}, 1)">
                        <i class="fas fa-plus"></i>
                    </button>
                </div>
            </td>
            <td class="text-end fw-bold text-dark" style="font-size: 0.92rem;">
                Bs. ${item.subtotal.toFixed(2)}
            </td>
            <td class="text-center">
                <button type="button" class="btn btn-sm btn-outline-danger border-0 rounded-circle" onclick="eliminarDelCarrito(${idx})" title="Quitar">
                    <i class="fas fa-trash-alt"></i>
                </button>
            </td>
        </tr>
        `;
    });

    tbody.innerHTML = html;
    actualizarTotales(totalGeneral, totalUnidades);
}

function modificarCantidad(index, delta) {
    const item = carrito[index];
    const nuevaCantidad = item.cantidad + delta;

    if (nuevaCantidad <= 0) {
        eliminarDelCarrito(index);
        return;
    }

    if (nuevaCantidad > item.stock) {
        Toast.fire({
            icon: 'warning',
            title: `Máximo disponible: ${item.stock} unidades`
        });
        return;
    }

    item.cantidad = nuevaCantidad;
    item.subtotal = nuevaCantidad * item.precio;
    renderizarCarrito();
}

function cambiarCantidadDirecta(index, valor) {
    const item = carrito[index];
    let num = parseInt(valor) || 1;

    if (num <= 0) num = 1;
    if (num > item.stock) {
        Toast.fire({
            icon: 'warning',
            title: `Máximo disponible: ${item.stock} unidades`
        });
        num = item.stock;
    }

    item.cantidad = num;
    item.subtotal = num * item.precio;
    renderizarCarrito();
}

function eliminarDelCarrito(index) {
    carrito.splice(index, 1);
    renderizarCarrito();
    Toast.fire({ icon: 'info', title: 'Producto removido' });
}

function vaciarCarrito() {
    if (carrito.length === 0) return;

    Swal.fire({
        title: '¿Vaciar carrito?',
        text: 'Se eliminarán todos los artículos agregados.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, vaciar',
        cancelButtonText: 'Cancelar',
        confirmButtonColor: '#ef4444'
    }).then(res => {
        if (res.isConfirmed) {
            carrito = [];
            renderizarCarrito();
            Toast.fire({ icon: 'success', title: 'Carrito vaciado' });
        }
    });
}

// 5. TOTALES Y CÁLCULO DE CAMBIO
function actualizarTotales(totalGeneral, totalUnidades) {
    document.getElementById('cartItemsCount').innerText = carrito.length;
    document.getElementById('totalUnidadesFooter').innerText = totalUnidades;
    document.getElementById('subtotalCarritoFooter').innerText = 'Bs. ' + totalGeneral.toFixed(2);
    document.getElementById('granTotalDisplay').innerText = totalGeneral.toFixed(2);

    calcularCambio();
}

function calcularCambio() {
    const total = parseFloat(document.getElementById('granTotalDisplay').innerText) || 0;
    const recibido = parseFloat(document.getElementById('montoRecibido').value) || 0;
    const cambioDisplay = document.getElementById('cambioDisplay');

    if (recibido === 0 || total === 0) {
        cambioDisplay.innerText = 'Bs. 0.00';
        cambioDisplay.style.color = '#059669';
        return;
    }

    const cambio = recibido - total;

    if (cambio >= 0) {
        cambioDisplay.innerText = 'Bs. ' + cambio.toFixed(2);
        cambioDisplay.style.color = '#059669'; // Verde
    } else {
        const falta = Math.abs(cambio);
        cambioDisplay.innerText = 'Faltan: Bs. ' + falta.toFixed(2);
        cambioDisplay.style.color = '#dc2626'; // Rojo
    }
}

function sumarEfectivo(monto) {
    const actual = parseFloat(document.getElementById('montoRecibido').value) || 0;
    document.getElementById('montoRecibido').value = (actual + monto).toFixed(2);
    calcularCambio();
}

function pagarExacto() {
    const total = parseFloat(document.getElementById('granTotalDisplay').innerText) || 0;
    document.getElementById('montoRecibido').value = total.toFixed(2);
    calcularCambio();
}

// 6. GESTIÓN DE CLIENTES
function seleccionarCliente(id, nombre, nit) {
    document.getElementById('cliente_id').value = id;
    document.getElementById('cliente_nombre').value = nombre;
    document.getElementById('nit').value = nit;

    document.getElementById('clientDisplayNombre').innerText = nombre;
    document.getElementById('clientDisplayNit').innerText = 'NIT/CI: ' + (nit && nit !== '0' ? nit : 'S/N');
    document.getElementById('clientAvatarLetter').innerText = nombre.charAt(0).toUpperCase();

    Toast.fire({ icon: 'success', title: 'Cliente asignado: ' + nombre });
}

function asignarClienteGeneral() {
    seleccionarCliente('1', 'CLIENTE GENERAL', '0');
}

function abrirModalNuevoCliente() {
    const modalClientes = bootstrap.Modal.getInstance(document.getElementById('modalClientes'));
    if (modalClientes) modalClientes.hide();

    const modalNuevo = new bootstrap.Modal(document.getElementById('modalNuevoCliente'));
    modalNuevo.show();
}

function guardarClienteRapido(e) {
    e.preventDefault();
    const btn = document.getElementById('btnGuardarClienteAjax');
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Guardando...';

    const nombre = document.getElementById('nuevoClienteNombre').value;
    const nit = document.getElementById('nuevoClienteNit').value;
    const telefono = document.getElementById('nuevoClienteTelefono').value;
    const email = document.getElementById('nuevoClienteEmail').value;

    fetch("{{ route('clientes.store') }}", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "Accept": "application/json",
            "X-CSRF-TOKEN": "{{ csrf_token() }}"
        },
        body: JSON.stringify({
            nombre: nombre,
            nit: nit,
            telefono: telefono,
            email: email
        })
    })
    .then(res => res.json())
    .then(data => {
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-save me-1"></i> Guardar y Seleccionar';

        if (data.success && data.cliente) {
            // Seleccionar inmediatamente
            seleccionarCliente(data.cliente.id, data.cliente.nombre, data.cliente.nit || '0');

            // Cerrar modal
            const modalNuevo = bootstrap.Modal.getInstance(document.getElementById('modalNuevoCliente'));
            if (modalNuevo) modalNuevo.hide();

            // Limpiar form
            document.getElementById('formNuevoCliente').reset();

            Swal.fire({
                icon: 'success',
                title: 'Cliente Creado',
                text: `El cliente "${data.cliente.nombre}" ha sido registrado y seleccionado.`,
                confirmButtonColor: '#10b981'
            });
        } else {
            throw new Error(data.message || 'No se pudo registrar el cliente.');
        }
    })
    .catch(err => {
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-save me-1"></i> Guardar y Seleccionar';
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: err.message,
            confirmButtonColor: '#ef4444'
        });
    });
}

// 7. FILTRAR TABLAS EN MODALES
function filtrarCatalogo() {
    const texto = document.getElementById('filtroCatalogoTexto').value.toLowerCase();
    const catId = document.getElementById('filtroCatalogoCategoria').value;
    const filas = document.querySelectorAll('#tablaCatalogo tbody .item-catalogo');

    filas.forEach(fila => {
        const codigo = fila.dataset.codigo;
        const nombre = fila.dataset.nombre;
        const desc = fila.dataset.desc;
        const cat = fila.dataset.categoria;

        const coincideTexto = codigo.includes(texto) || nombre.includes(texto) || desc.includes(texto);
        const coincideCat = catId === '' || cat === catId;

        if (coincideTexto && coincideCat) {
            fila.style.display = '';
        } else {
            fila.style.display = 'none';
        }
    });
}

function filtrarClientesModal() {
    const texto = document.getElementById('buscarClienteInput').value.toLowerCase();
    const filas = document.querySelectorAll('#tablaModalClientes tbody .fila-cliente');

    filas.forEach(fila => {
        const nombre = fila.dataset.nombre || '';
        const nit = fila.dataset.nit || '';

        if (nombre.includes(texto) || nit.includes(texto)) {
            fila.style.display = '';
        } else {
            fila.style.display = 'none';
        }
    });
}

// 8. PROCESAR Y GUARDAR VENTA
function procesarVenta() {
    if (carrito.length === 0) {
        Swal.fire({
            icon: 'warning',
            title: 'Carrito Vacío',
            text: 'Debes añadir al menos un producto para registrar una venta.',
            confirmButtonColor: '#f59e0b'
        });
        return;
    }

    const clienteId = document.getElementById('cliente_id').value;
    const clienteNombre = document.getElementById('cliente_nombre').value;
    const fecha = document.getElementById('fechaVenta').value;
    const nit = document.getElementById('nit').value || '0';
    const total = parseFloat(document.getElementById('granTotalDisplay').innerText) || 0;

    if (!fecha) {
        Swal.fire({ icon: 'warning', title: 'Fecha requerida', text: 'Por favor selecciona la fecha de la venta.' });
        return;
    }

    // Modal de confirmación final
    Swal.fire({
        title: '¿Confirmar Venta?',
        html: `
            <div class="text-start p-2 bg-light rounded-3">
                <p class="mb-1"><strong>Cliente:</strong> ${clienteNombre}</p>
                <p class="mb-1"><strong>NIT/CI:</strong> ${nit !== '0' ? nit : 'Sin NIT'}</p>
                <p class="mb-1"><strong>Artículos:</strong> ${carrito.length} productos</p>
                <h5 class="mt-2 text-primary"><strong>Total a Cobrar: Bs. ${total.toFixed(2)}</strong></h5>
            </div>
        `,
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: '<i class="fas fa-check"></i> Sí, Emitir Venta',
        cancelButtonText: 'Cancelar',
        confirmButtonColor: '#10b981',
        cancelButtonColor: '#64748b'
    }).then(result => {
        if (result.isConfirmed) {
            ejecutarGuardado(clienteId, fecha, nit, total);
        }
    });
}

function ejecutarGuardado(clienteId, fecha, nit, total) {
    const btn = document.getElementById('btnGuardarVenta');
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Procesando Venta...';

    fetch("{{ route('ventas.store') }}", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "Accept": "application/json",
            "X-CSRF-TOKEN": "{{ csrf_token() }}"
        },
        body: JSON.stringify({
            cliente_id: clienteId,
            fecha: fecha,
            comprobante: nit,
            total: total,
            detalles: carrito
        })
    })
    .then(async response => {
        const data = await response.json();
        if (!response.ok || data.success === false) {
            throw new Error(data.error || data.message || 'No se pudo completar la venta');
        }
        return data;
    })
    .then(data => {
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-check-circle me-2"></i> CONFIRMAR Y REGISTRAR VENTA';

        Swal.fire({
            icon: 'success',
            title: '¡Venta Registrada con Éxito!',
            text: data.mensaje || 'La venta se ha procesado y el inventario ha sido actualizado.',
            showDenyButton: true,
            showCancelButton: true,
            confirmButtonText: '<i class="fas fa-print me-1"></i> Imprimir Comprobante',
            denyButtonText: '<i class="fas fa-plus me-1"></i> Nueva Venta',
            cancelButtonText: '<i class="fas fa-list me-1"></i> Ir a Ventas',
            confirmButtonColor: '#10b981',
            denyButtonColor: '#4f46e5',
            cancelButtonColor: '#64748b',
            allowOutsideClick: false
        }).then(res => {
            if (res.isConfirmed) {
                window.open(data.comprobante_url, '_blank');
                window.location.reload();
            } else if (res.isDenied) {
                window.location.reload();
            } else {
                window.location.href = "{{ route('ventas.index') }}";
            }
        });
    })
    .catch(error => {
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-check-circle me-2"></i> CONFIRMAR Y REGISTRAR VENTA';

        Swal.fire({
            icon: 'error',
            title: 'Error al Registrar',
            text: error.message,
            confirmButtonColor: '#ef4444'
        });
    });
}
</script>

@endsection