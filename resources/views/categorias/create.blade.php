@extends('dashboard')

@section('content')

<style>
    .cat-form-container {
        padding: 1.5rem 1rem;
        max-width: 860px;
        margin: 0 auto;
    }

    .form-section-card {
        background: #ffffff;
        border-radius: 20px;
        border: 1px solid #f1f5f9;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04);
        padding: 2rem;
    }

    .suggestion-chip {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 14px;
        border-radius: 50px;
        font-size: 0.78rem;
        font-weight: 600;
        background: #f1f5f9;
        color: #334155;
        border: 1px solid #e2e8f0;
        cursor: pointer;
        transition: all 0.2s ease;
        user-select: none;
    }

    .suggestion-chip:hover {
        background: #e0f2fe;
        color: #0284c7;
        border-color: #bae6fd;
        transform: translateY(-2px);
    }
</style>

<div class="cat-form-container">

    <!-- BREADCRUMB -->
    <div class="d-flex align-items-center justify-content-between mb-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none text-muted">Inicio</a></li>
                <li class="breadcrumb-item"><a href="{{ route('categorias.index') }}" class="text-decoration-none text-muted">Categorías</a></li>
                <li class="breadcrumb-item active text-primary fw-semibold" aria-current="page">Nueva Categoría</li>
            </ol>
        </nav>
        <a href="{{ route('categorias.index') }}" class="btn btn-outline-secondary rounded-pill px-3 btn-sm">
            <i class="fas fa-arrow-left me-1"></i> Volver
        </a>
    </div>

    <div class="form-section-card">

        <!-- HEADER -->
        <div class="d-flex align-items-center gap-3 pb-3 mb-4 border-bottom">
            <div style="width: 48px; height: 48px; border-radius: 14px; background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%); color: #fff; display: flex; align-items: center; justify-content: center; font-size: 1.3rem; box-shadow: 0 6px 15px rgba(2, 132, 199, 0.3);">
                <i class="fas fa-tags"></i>
            </div>
            <div>
                <h4 class="fw-bold mb-1 text-dark">Registrar Nueva Categoría</h4>
                <p class="text-muted mb-0" style="font-size: 0.88rem;">Complete la información para crear un nuevo segmento de clasificación en el catálogo.</p>
            </div>
        </div>

        <!-- QUICK SUGGESTIONS -->
        <div class="mb-4 p-3 rounded-4 bg-light border">
            <label class="d-block fw-bold text-dark mb-2" style="font-size: 0.8rem;">
                <i class="fas fa-lightbulb text-warning me-1"></i> Sugerencias de categorías frecuentes (haz clic para autocompletar):
            </label>
            <div class="d-flex flex-wrap gap-2">
                <span class="suggestion-chip" onclick="fillCategory('Teclados Mecánicos & Gamer', 'Teclados mecánicos, de membrana, inalámbricos e iluminación RGB para gaming y oficina.')">
                    <i class="fas fa-keyboard text-primary"></i> Teclados
                </span>
                <span class="suggestion-chip" onclick="fillCategory('Mouse & Periféricos de Puntero', 'Mouse gamer, ergonómico, óptico e inalámbrico de alta resolución y DPI ajustable.')">
                    <i class="fas fa-mouse text-info"></i> Mouse
                </span>
                <span class="suggestion-chip" onclick="fillCategory('Auriculares, Headsets & Audio', 'Audífonos gamer con micrófono, diademas, auriculares Bluetooth y altavoces.')">
                    <i class="fas fa-headphones text-danger"></i> Auriculares & Audio
                </span>
                <span class="suggestion-chip" onclick="fillCategory('Cámaras Web & Streaming', 'Cámaras web Full HD / 4K, micrófonos de condensador y accesorios para videollamada y streaming.')">
                    <i class="fas fa-video text-success"></i> Cámaras Web
                </span>
                <span class="suggestion-chip" onclick="fillCategory('Cables, Hubs & Conectividad', 'Cables HDMI, Tipo-C, adaptadores multipuerto, hubs USB y cargadores de carga rápida.')">
                    <i class="fas fa-plug text-warning"></i> Cables & Hubs
                </span>
                <span class="suggestion-chip" onclick="fillCategory('Almacenamiento & Unidades', 'Discos de estado sólido SSD, NVMe, unidades USB y tarjetas de memoria flash.')">
                    <i class="fas fa-hdd text-secondary"></i> Almacenamiento
                </span>
                <span class="suggestion-chip" onclick="fillCategory('Monitores & Pantallas', 'Monitores gamer de alta tasa de refresco (Hz), pantallas para oficina y brazos para escritorio.')">
                    <i class="fas fa-tv text-purple"></i> Monitores
                </span>
                <span class="suggestion-chip" onclick="fillCategory('Laptops & Computadoras', 'Equipos portátiles, laptops gamer, ultrabooks y accesorios para PC.')">
                    <i class="fas fa-laptop text-primary"></i> Laptops & PC
                </span>
            </div>
        </div>

        <!-- FORM -->
        <form method="POST" action="{{ route('categorias.store') }}" id="formCategoria">
            @csrf

            <!-- Nombre de la Categoría -->
            <div class="mb-3">
                <label for="nombre" class="form-label fw-semibold text-dark" style="font-size: 0.88rem;">
                    Nombre de la Categoría <span class="text-danger">*</span>
                </label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0 text-muted ps-3">
                        <i class="fas fa-tag"></i>
                    </span>
                    <input type="text" name="nombre" id="nombre" class="form-control bg-light border-start-0 @error('nombre') is-invalid @enderror" placeholder="Ej: Teclados, Mouse, Auriculares, Cámaras Web..." value="{{ old('nombre') }}" required autofocus>
                </div>
                @error('nombre')
                    <div class="text-danger mt-1" style="font-size: 0.82rem;">{{ $message }}</div>
                @enderror
            </div>

            <!-- Descripción -->
            <div class="mb-4">
                <label for="descripcion" class="form-label fw-semibold text-dark" style="font-size: 0.88rem;">
                    Descripción u Observaciones (Opcional)
                </label>
                <textarea name="descripcion" id="descripcion" rows="3" class="form-control bg-light @error('descripcion') is-invalid @enderror" placeholder="Breve descripción del tipo de accesorios o productos incluidos en esta categoría...">{{ old('descripcion') }}</textarea>
                <div class="form-text text-muted" style="font-size: 0.8rem;">
                    Ayuda a los vendedores y almaceneros a clasificar correctamente cada producto o accesorio.
                </div>
                @error('descripcion')
                    <div class="text-danger mt-1" style="font-size: 0.82rem;">{{ $message }}</div>
                @enderror
            </div>

            <!-- ACTIONS -->
            <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                <a href="{{ route('categorias.index') }}" class="btn btn-light rounded-pill px-4 fw-semibold text-secondary">
                    Cancelar
                </a>
                <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm" style="background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%); border: none;">
                    <i class="fas fa-save me-1"></i> Guardar Categoría
                </button>
            </div>

        </form>

    </div>

</div>

<script>
    function fillCategory(nombre, descripcion) {
        document.getElementById('nombre').value = nombre;
        document.getElementById('descripcion').value = descripcion;
        document.getElementById('nombre').focus();
    }
</script>

@endsection