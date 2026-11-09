@extends('dashboard')

@section('content')
<div class="container-fluid py-4 px-3 px-md-4">

    <!-- HEADER / BREADCRUMB -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <a href="{{ route('productos.index') }}" class="btn btn-sm btn-light border shadow-sm text-secondary px-2 py-1 rounded-3">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <h2 class="h4 fw-bold text-dark mb-0">Editar Producto</h2>
                <span class="badge bg-warning-subtle text-warning border border-warning-subtle rounded-pill px-3 py-1 fs-8">
                    <i class="fas fa-edit me-1"></i> ID #{{ $producto->id }}
                </span>
                <span class="badge bg-secondary-subtle text-secondary font-monospace rounded-pill px-2.5 py-1 fs-8">
                    {{ $producto->codigo }}
                </span>
            </div>
            <p class="text-muted small mb-0">Modifique los datos técnicos, precios y parámetros de control de inventario de este accesorio o producto.</p>
        </div>
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('productos.show', $producto) }}" class="btn btn-outline-info rounded-3 px-3 py-2">
                <i class="fas fa-eye me-1"></i> Ver Ficha
            </a>
            <a href="{{ route('productos.index') }}" class="btn btn-outline-secondary rounded-3 px-3 py-2">
                <i class="fas fa-times me-1"></i> Cancelar
            </a>
            <button type="button" onclick="document.getElementById('formEditarProducto').submit();" class="btn btn-warning text-dark fw-bold rounded-3 px-4 py-2 shadow-sm">
                <i class="fas fa-check-circle me-1"></i> Guardar Cambios
            </button>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show rounded-4 border-0 shadow-sm mb-4" role="alert">
            <div class="d-flex align-items-start gap-3">
                <div class="p-2 bg-danger text-white rounded-circle fs-6">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <div>
                    <h6 class="fw-bold mb-1">Existen errores en el formulario:</h6>
                    <ul class="mb-0 ps-3 small">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <form method="POST" action="{{ route('productos.update', $producto) }}" enctype="multipart/form-data" id="formEditarProducto">
        @csrf
        @method('PUT')

        <div class="row g-4">
            
            <!-- COLUMNA PRINCIPAL (CAMPOS TÉCNICOS Y PRECIOS) -->
            <div class="col-lg-8">
                
                <!-- CARD 1: INFORMACIÓN GENERAL -->
                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-header bg-transparent border-0 pt-4 px-4 pb-2">
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge bg-primary-subtle text-primary p-2 rounded-3">
                                <i class="fas fa-boxes-stacked fs-6"></i>
                            </span>
                            <div>
                                <h5 class="fw-bold text-dark mb-0">Información General del Producto</h5>
                                <small class="text-muted">Categoría asignada, SKU e identificación comercial</small>
                            </div>
                        </div>
                    </div>
                    <div class="card-body px-4 pb-4">
                        <div class="row g-3">
                            <div class="col-md-5">
                                <label class="form-label fw-semibold text-secondary small mb-1">Categoría <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 rounded-start-3 text-muted">
                                        <i class="fas fa-tags"></i>
                                    </span>
                                    <select name="categoria_id" class="form-select border-start-0 rounded-end-3" required id="categoriaSelect">
                                        @foreach($categorias as $c)
                                            <option value="{{ $c->id }}" {{ old('categoria_id', $producto->categoria_id) == $c->id ? 'selected' : '' }} data-prefix="{{ strtoupper(substr($c->nombre, 0, 3)) }}">
                                                {{ $c->nombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-7">
                                <label class="form-label fw-semibold text-secondary small mb-1">Código / SKU de Barras <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 rounded-start-3 text-muted">
                                        <i class="fas fa-barcode"></i>
                                    </span>
                                    <input type="text" name="codigo" id="codigoInput" class="form-control border-start-0 border-end-0 font-monospace fw-bold text-primary" placeholder="Ej: TEC-2026-001" value="{{ old('codigo', $producto->codigo) }}" required>
                                    <button class="btn btn-outline-secondary rounded-end-3 px-3" type="button" id="btnGenerarSku" title="Regenerar código automático">
                                        <i class="fas fa-magic me-1"></i> Auto SKU
                                    </button>
                                </div>
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-semibold text-secondary small mb-1">Nombre del Producto <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 rounded-start-3 text-muted">
                                        <i class="fas fa-heading"></i>
                                    </span>
                                    <input type="text" name="nombre" class="form-control border-start-0 rounded-end-3 fw-semibold" placeholder="Nombre completo del producto..." value="{{ old('nombre', $producto->nombre) }}" required>
                                </div>
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-semibold text-secondary small mb-1">Descripción / Especificaciones Técnicas</label>
                                <textarea name="descripcion" class="form-control rounded-3" rows="3" placeholder="Detalles de conectividad, switches, DPI, sensor, puertos y características...">{{ old('descripcion', $producto->descripcion) }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- CARD 2: PRECIOS Y MARGEN DE GANANCIA -->
                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-header bg-transparent border-0 pt-4 px-4 pb-2">
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge bg-success-subtle text-success p-2 rounded-3">
                                <i class="fas fa-coins fs-6"></i>
                            </span>
                            <div>
                                <h5 class="fw-bold text-dark mb-0">Estructura Comercial & Precios</h5>
                                <small class="text-muted">Calculadora en vivo de margen de ganancia</small>
                            </div>
                        </div>
                    </div>
                    <div class="card-body px-4 pb-4">
                        <div class="row g-3 align-items-center mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-secondary small mb-1">Precio de Compra (Costo) <span class="text-danger">*</span></label>
                                <div class="input-group input-group-lg">
                                    <span class="input-group-text bg-light border-end-0 rounded-start-3 fw-bold text-muted">Bs.</span>
                                    <input type="number" step="0.01" min="0" name="precio_compra" id="precioCompraInput" class="form-control border-start-0 rounded-end-3 fw-bold text-dark fs-5" placeholder="0.00" value="{{ old('precio_compra', $producto->precio_compra) }}" required>
                                </div>
                                <div class="form-text small">Costo unitario registrado del proveedor.</div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-secondary small mb-1">Precio de Venta al Público <span class="text-danger">*</span></label>
                                <div class="input-group input-group-lg">
                                    <span class="input-group-text bg-success-subtle text-success border-end-0 rounded-start-3 fw-bold">Bs.</span>
                                    <input type="number" step="0.01" min="0" name="precio_venta" id="precioVentaInput" class="form-control border-start-0 rounded-end-3 fw-bold text-success fs-5" placeholder="0.00" value="{{ old('precio_venta', $producto->precio_venta) }}" required>
                                </div>
                                <div class="form-text small">Precio al consumidor final.</div>
                            </div>
                        </div>

                        <!-- CALCULADORA DE MARGEN LIVE PREVIEW -->
                        <div class="p-3 bg-light rounded-4 border">
                            <div class="row g-3 text-center">
                                <div class="col-4 border-end">
                                    <div class="text-muted small fw-semibold">Ganancia Bruta Unit.</div>
                                    <div class="h5 fw-bold text-dark mb-0 mt-1" id="calcGananciaBs">Bs. 0.00</div>
                                </div>
                                <div class="col-4 border-end">
                                    <div class="text-muted small fw-semibold">Margen Comercial</div>
                                    <div class="h5 fw-bold text-primary mb-0 mt-1" id="calcMargenPct">0.0%</div>
                                </div>
                                <div class="col-4">
                                    <div class="text-muted small fw-semibold">Rentabilidad</div>
                                    <span class="badge bg-secondary rounded-pill px-3 py-1 fs-8 mt-1" id="calcRentabilidadBadge">Sin datos</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- CARD 3: CONTROL DE STOCK E INVENTARIO -->
                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-header bg-transparent border-0 pt-4 px-4 pb-2">
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge bg-warning-subtle text-warning p-2 rounded-3">
                                <i class="fas fa-boxes-stacked fs-6"></i>
                            </span>
                            <div>
                                <h5 class="fw-bold text-dark mb-0">Control de Existencias & Alertas</h5>
                                <small class="text-muted">Ajuste de inventario físico y umbrales de reorden</small>
                            </div>
                        </div>
                    </div>
                    <div class="card-body px-4 pb-4">
                        <div class="row g-3">
                            <!-- Stock Actual -->
                            <div class="col-md-3">
                                <label class="form-label fw-semibold text-secondary small mb-1">Stock Actual <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 rounded-start-3 text-muted">
                                        <i class="fas fa-layer-group"></i>
                                    </span>
                                    <input type="number" min="0" name="stock" class="form-control border-start-0 rounded-end-3 fw-bold {{ $producto->stock <= 0 ? 'text-danger' : 'text-dark' }}" placeholder="0" value="{{ old('stock', $producto->stock) }}" required>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label fw-semibold text-secondary small mb-1">Stock Mínimo (Alerta)</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 rounded-start-3 text-muted">
                                        <i class="fas fa-bell"></i>
                                    </span>
                                    <input type="number" min="0" name="stock_minimo" class="form-control border-start-0 rounded-end-3" placeholder="5" value="{{ old('stock_minimo', $producto->stock_minimo) }}">
                                </div>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label fw-semibold text-secondary small mb-1">Stock Máximo</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 rounded-start-3 text-muted">
                                        <i class="fas fa-boxes-stacked"></i>
                                    </span>
                                    <input type="number" min="0" name="stock_maximo" class="form-control border-start-0 rounded-end-3" placeholder="50" value="{{ old('stock_maximo', $producto->stock_maximo) }}">
                                </div>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label fw-semibold text-secondary small mb-1">Fecha de Ingreso</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 rounded-start-3 text-muted">
                                        <i class="fas fa-calendar-alt"></i>
                                    </span>
                                    <input type="date" name="fecha_ingreso" class="form-control border-start-0 rounded-end-3" value="{{ old('fecha_ingreso', $producto->fecha_ingreso) }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- COLUMNA LATERAL (IMAGEN Y ACCIONES) -->
            <div class="col-lg-4">
                
                <!-- CARD: FOTOGRAFÍA DEL PRODUCTO -->
                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-header bg-transparent border-0 pt-4 px-4 pb-2">
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge bg-info-subtle text-info p-2 rounded-3">
                                <i class="fas fa-image fs-6"></i>
                            </span>
                            <div>
                                <h5 class="fw-bold text-dark mb-0">Fotografía del Producto</h5>
                                <small class="text-muted">Reemplazar o mantener imagen actual</small>
                            </div>
                        </div>
                    </div>
                    <div class="card-body px-4 pb-4 text-center">
                        
                        <!-- DROPZONE / PREVIEW AREA -->
                        <div class="border border-2 border-dashed rounded-4 p-4 text-center bg-light position-relative mb-3 image-dropzone" id="dropArea" style="cursor: pointer;">
                            <input type="file" name="imagen" id="imagenInput" class="position-absolute top-0 start-0 w-100 h-100 opacity-0" accept="image/jpeg,image/png,image/webp,image/jpg" style="cursor: pointer;">
                            
                            <div id="previewContainer">
                                @if($producto->imagen_url)
                                    <img id="imgPreview" src="{{ $producto->imagen_url }}" alt="{{ $producto->nombre }}" class="img-fluid rounded-3 shadow-sm mb-2" style="max-height: 180px; object-fit: contain;">
                                    <p class="text-muted small mb-0"><i class="fas fa-camera me-1"></i> Haga clic o arrastre para cambiar la imagen</p>
                                @else
                                    <div class="w-12 h-12 bg-white rounded-circle shadow-sm d-flex align-items-center justify-content-center mx-auto mb-2 text-primary" style="width: 56px; height: 56px;">
                                        <i class="fas fa-cloud-arrow-up fs-4"></i>
                                    </div>
                                    <h6 class="fw-bold text-dark mb-1">Sin imagen asignada</h6>
                                    <p class="text-muted small mb-0">Haz clic para subir una foto</p>
                                @endif
                            </div>
                        </div>

                        <span class="badge bg-secondary-subtle text-secondary rounded-pill px-3 py-1 fs-8">
                            PNG, JPG, WEBP (Máx. 2MB)
                        </span>

                    </div>
                </div>

                <!-- CARD: HISTORIAL DE ACTIVIDAD -->
                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-body p-4">
                        <h6 class="fw-bold text-dark mb-3 d-flex align-items-center gap-2">
                            <i class="fas fa-clock-rotate-left text-primary"></i> Metadatos del Registro
                        </h6>
                        <div class="d-flex justify-content-between py-2 border-bottom small">
                            <span class="text-muted">Fecha Creación:</span>
                            <span class="fw-semibold text-dark">{{ $producto->created_at ? $producto->created_at->format('d/m/Y H:i') : 'N/A' }}</span>
                        </div>
                        <div class="d-flex justify-content-between py-2 border-bottom small">
                            <span class="text-muted">Última Modificación:</span>
                            <span class="fw-semibold text-dark">{{ $producto->updated_at ? $producto->updated_at->format('d/m/Y H:i') : 'N/A' }}</span>
                        </div>
                        <div class="d-flex justify-content-between py-2 small">
                            <span class="text-muted">Ventas Registradas:</span>
                            <span class="badge bg-primary-subtle text-primary">{{ $producto->detalleVentas()->count() }} ítems</span>
                        </div>
                    </div>
                </div>

                <!-- BOTONES DE ACCIÓN -->
                <div class="card border-0 shadow-sm rounded-4 sticky-top" style="top: 20px; z-index: 10;">
                    <div class="card-body p-3">
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-warning btn-lg rounded-3 fw-bold shadow-sm text-dark">
                                <i class="fas fa-save me-2"></i> Guardar Cambios
                            </button>
                            <a href="{{ route('productos.index') }}" class="btn btn-light border rounded-3 text-secondary">
                                <i class="fas fa-arrow-left me-1"></i> Volver al Catálogo
                            </a>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </form>

</div>

<!-- STYLES & JS SCRIPTS -->
<style>
    .image-dropzone {
        transition: all 0.2s ease-in-out;
    }
    .image-dropzone:hover {
        background-color: #f1f5f9 !important;
        border-color: #f59e0b !important;
    }
    .fs-8 { font-size: 0.78rem; }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    
    // 1. Vista previa interactiva de la imagen
    const imagenInput = document.getElementById('imagenInput');
    const imgPreview = document.getElementById('imgPreview');

    imagenInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(event) {
                if (imgPreview) {
                    imgPreview.src = event.target.result;
                } else {
                    location.reload(); // Simple reload fallback if placeholder was active
                }
            };
            reader.readAsDataURL(file);
        }
    });

    // 2. Generador de SKU Automático
    const btnGenerarSku = document.getElementById('btnGenerarSku');
    const codigoInput = document.getElementById('codigoInput');
    const categoriaSelect = document.getElementById('categoriaSelect');

    btnGenerarSku.addEventListener('click', function() {
        let prefix = 'LAP';
        if (categoriaSelect.selectedIndex >= 0) {
            const opt = categoriaSelect.options[categoriaSelect.selectedIndex];
            prefix = opt.getAttribute('data-prefix') || 'PRD';
        }
        const randomNum = Math.floor(1000 + Math.random() * 9000);
        const year = new Date().getFullYear();
        codigoInput.value = `${prefix}-${year}-${randomNum}`;
        codigoInput.classList.add('is-valid');
        setTimeout(() => codigoInput.classList.remove('is-valid'), 2000);
    });

    // 3. Calculadora de Margen en Vivo
    const precioCompraInput = document.getElementById('precioCompraInput');
    const precioVentaInput = document.getElementById('precioVentaInput');
    const calcGananciaBs = document.getElementById('calcGananciaBs');
    const calcMargenPct = document.getElementById('calcMargenPct');
    const calcRentabilidadBadge = document.getElementById('calcRentabilidadBadge');

    function actualizarCalculadora() {
        const compra = parseFloat(precioCompraInput.value) || 0;
        const venta = parseFloat(precioVentaInput.value) || 0;

        if (compra > 0 && venta > 0) {
            const ganancia = venta - compra;
            const margen = (ganancia / venta) * 100;

            calcGananciaBs.textContent = `Bs. ${ganancia.toFixed(2)}`;
            calcMargenPct.textContent = `${margen.toFixed(1)}%`;

            if (ganancia < 0) {
                calcGananciaBs.className = 'h5 fw-bold text-danger mb-0 mt-1';
                calcMargenPct.className = 'h5 fw-bold text-danger mb-0 mt-1';
                calcRentabilidadBadge.className = 'badge bg-danger rounded-pill px-3 py-1 fs-8 mt-1';
                calcRentabilidadBadge.textContent = 'Pérdida';
            } else if (margen < 15) {
                calcGananciaBs.className = 'h5 fw-bold text-warning mb-0 mt-1';
                calcMargenPct.className = 'h5 fw-bold text-warning mb-0 mt-1';
                calcRentabilidadBadge.className = 'badge bg-warning text-dark rounded-pill px-3 py-1 fs-8 mt-1';
                calcRentabilidadBadge.textContent = 'Margen Bajo';
            } else if (margen <= 30) {
                calcGananciaBs.className = 'h5 fw-bold text-primary mb-0 mt-1';
                calcMargenPct.className = 'h5 fw-bold text-primary mb-0 mt-1';
                calcRentabilidadBadge.className = 'badge bg-primary rounded-pill px-3 py-1 fs-8 mt-1';
                calcRentabilidadBadge.textContent = 'Margen Bueno';
            } else {
                calcGananciaBs.className = 'h5 fw-bold text-success mb-0 mt-1';
                calcMargenPct.className = 'h5 fw-bold text-success mb-0 mt-1';
                calcRentabilidadBadge.className = 'badge bg-success rounded-pill px-3 py-1 fs-8 mt-1';
                calcRentabilidadBadge.textContent = 'Excelente Margen';
            }
        } else {
            calcGananciaBs.textContent = 'Bs. 0.00';
            calcMargenPct.textContent = '0.0%';
            calcGananciaBs.className = 'h5 fw-bold text-dark mb-0 mt-1';
            calcMargenPct.className = 'h5 fw-bold text-primary mb-0 mt-1';
            calcRentabilidadBadge.className = 'badge bg-secondary rounded-pill px-3 py-1 fs-8 mt-1';
            calcRentabilidadBadge.textContent = 'Sin datos';
        }
    }

    precioCompraInput.addEventListener('input', actualizarCalculadora);
    precioVentaInput.addEventListener('input', actualizarCalculadora);
    actualizarCalculadora();
});
</script>
@endsection