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
</style>

<div class="cat-form-container">

    <!-- BREADCRUMB -->
    <div class="d-flex align-items-center justify-content-between mb-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none text-muted">Inicio</a></li>
                <li class="breadcrumb-item"><a href="{{ route('categorias.index') }}" class="text-decoration-none text-muted">Categorías</a></li>
                <li class="breadcrumb-item active text-primary fw-semibold" aria-current="page">Editar: {{ $categoria->nombre }}</li>
            </ol>
        </nav>
        <a href="{{ route('categorias.index') }}" class="btn btn-outline-secondary rounded-pill px-3 btn-sm">
            <i class="fas fa-arrow-left me-1"></i> Volver
        </a>
    </div>

    <div class="form-section-card">

        <!-- HEADER -->
        <div class="d-flex align-items-center gap-3 pb-3 mb-4 border-bottom">
            <div style="width: 52px; height: 52px; border-radius: 14px; background: {{ $categoria->avatar_gradient }}; color: #fff; display: flex; align-items: center; justify-content: center; font-size: 1.4rem; box-shadow: 0 6px 15px rgba(0, 0, 0, 0.15);">
                <i class="{{ $categoria->icono }}"></i>
            </div>
            <div>
                <h4 class="fw-bold mb-1 text-dark">Editar Categoría: {{ $categoria->nombre }}</h4>
                <p class="text-muted mb-0" style="font-size: 0.88rem;">Modifique la denominación o descripción de esta categoría de productos y accesorios.</p>
            </div>
        </div>

        <!-- INFO CALLOUT -->
        <div class="d-flex align-items-center justify-content-between p-3 rounded-4 bg-light border mb-4">
            <div class="d-flex align-items-center gap-2">
                <i class="fas fa-info-circle text-primary fs-5"></i>
                <span class="text-dark fw-semibold" style="font-size: 0.88rem;">
                    Esta categoría cuenta actualmente con <b>{{ $categoria->productos_count ?? $categoria->productos()->count() }} productos / artículos</b> asignados.
                </span>
            </div>
            <a href="{{ route('categorias.show', $categoria) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3" style="font-size: 0.8rem;">
                <i class="fas fa-list me-1"></i> Ver Catálogo
            </a>
        </div>

        <!-- FORM -->
        <form method="POST" action="{{ route('categorias.update', $categoria) }}">
            @csrf
            @method('PUT')

            <!-- Nombre de la Categoría -->
            <div class="mb-3">
                <label for="nombre" class="form-label fw-semibold text-dark" style="font-size: 0.88rem;">
                    Nombre de la Categoría <span class="text-danger">*</span>
                </label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0 text-muted ps-3">
                        <i class="fas fa-tag"></i>
                    </span>
                    <input type="text" name="nombre" id="nombre" class="form-control bg-light border-start-0 @error('nombre') is-invalid @enderror" placeholder="Ej: Teclados, Mouse, Auriculares, Cámaras Web..." value="{{ old('nombre', $categoria->nombre) }}" required>
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
                <textarea name="descripcion" id="descripcion" rows="3" class="form-control bg-light @error('descripcion') is-invalid @enderror" placeholder="Breve descripción del tipo de equipos incluidos...">{{ old('descripcion', $categoria->descripcion) }}</textarea>
                <div class="form-text text-muted" style="font-size: 0.8rem;">
                    Modifique las observaciones o especificaciones clave de esta gama.
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
                <button type="submit" class="btn btn-warning rounded-pill px-4 fw-bold shadow-sm text-dark" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); border: none;">
                    <i class="fas fa-save me-1"></i> Actualizar Cambios
                </button>
            </div>

        </form>

    </div>

</div>

@endsection