@extends('dashboard')

@section('content')

<style>
    .cat-profile-container {
        padding: 1.5rem 1rem;
    }

    .cat-avatar-lg {
        width: 64px;
        height: 64px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #ffffff;
        font-size: 1.75rem;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        flex-shrink: 0;
    }

    .stat-badge-box {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        padding: 1rem;
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }
</style>

<div class="cat-profile-container">

    <!-- BREADCRUMB & BACK BUTTON -->
    <div class="d-flex align-items-center justify-content-between mb-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none text-muted">Inicio</a></li>
                <li class="breadcrumb-item"><a href="{{ route('categorias.index') }}" class="text-decoration-none text-muted">Categorías</a></li>
                <li class="breadcrumb-item active text-primary fw-semibold" aria-current="page">{{ $categoria->nombre }}</li>
            </ol>
        </nav>
        <a href="{{ route('categorias.index') }}" class="btn btn-outline-secondary rounded-pill px-3 btn-sm">
            <i class="fas fa-arrow-left me-1"></i> Volver al Listado
        </a>
    </div>

    <!-- CATEGORY HEADER CARD -->
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4" style="background: #ffffff;">
        <div class="card-body p-4">
            <div class="row align-items-center g-3">
                <div class="col-lg-8 d-flex align-items-center gap-3">
                    <div class="cat-avatar-lg" style="background: {{ $categoria->avatar_gradient }};">
                        <i class="{{ $categoria->icono }}"></i>
                    </div>
                    <div>
                        <div class="d-flex align-items-center gap-2 mb-1">
                            <h3 class="fw-bold mb-0 text-dark">{{ $categoria->nombre }}</h3>
                            <span class="badge bg-light text-secondary border px-2 py-1" style="font-size: 0.75rem;">ID #{{ str_pad($categoria->id, 3, '0', STR_PAD_LEFT) }}</span>
                        </div>
                        <p class="text-muted mb-0" style="font-size: 0.92rem; max-width: 650px;">
                            {{ $categoria->descripcion ?: 'Sin descripción detallada registrada para esta categoría de productos.' }}
                        </p>
                    </div>
                </div>

                <div class="col-lg-4 text-lg-end">
                    <div class="d-flex flex-wrap gap-2 justify-content-lg-end">
                        <a href="{{ route('productos.create') }}?categoria_id={{ $categoria->id }}" class="btn btn-primary rounded-pill px-3 py-2 fw-semibold" style="font-size: 0.85rem; background: #0284c7; border: none;">
                            <i class="fas fa-plus-circle me-1"></i> Agregar Producto
                        </a>
                        <a href="{{ route('categorias.edit', $categoria) }}" class="btn btn-outline-warning rounded-pill px-3 py-2 fw-semibold text-dark" style="font-size: 0.85rem;">
                            <i class="fas fa-pen me-1"></i> Editar
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-footer bg-light px-4 py-3 border-top">
            <div class="row g-3">
                <!-- Total Modelos -->
                <div class="col-md-3 col-6">
                    <div class="stat-badge-box">
                        <small class="text-muted text-uppercase fw-bold" style="font-size: 0.72rem;">Modelos Registrados</small>
                        <div class="fw-bold text-dark fs-5 mt-1">
                            <i class="fas fa-boxes-stacked text-primary me-1"></i> {{ $totalModelos }} <small class="text-muted fw-normal" style="font-size: 0.8rem;">artículos</small>
                        </div>
                    </div>
                </div>

                <!-- Stock Físico -->
                <div class="col-md-3 col-6">
                    <div class="stat-badge-box">
                        <small class="text-muted text-uppercase fw-bold" style="font-size: 0.72rem;">Stock en Almacén</small>
                        <div class="fw-bold text-success fs-5 mt-1">
                            <i class="fas fa-boxes-stacked me-1"></i> {{ number_format($totalUnidades) }} <small class="text-muted fw-normal" style="font-size: 0.8rem;">unidades</small>
                        </div>
                    </div>
                </div>

                <!-- Valor Inventario -->
                <div class="col-md-3 col-6">
                    <div class="stat-badge-box">
                        <small class="text-muted text-uppercase fw-bold" style="font-size: 0.72rem;">Inversión al Costo</small>
                        <div class="fw-bold text-dark fs-5 mt-1">
                            <i class="fas fa-coins text-warning me-1"></i> Bs. {{ number_format($valorInventario, 2) }}
                        </div>
                    </div>
                </div>

                <!-- Estado Stock Crítico -->
                <div class="col-md-3 col-6">
                    <div class="stat-badge-box">
                        <small class="text-muted text-uppercase fw-bold" style="font-size: 0.72rem;">Alertas de Stock</small>
                        <div class="fw-bold {{ ($stockBajoCount + $agotadosCount) > 0 ? 'text-danger' : 'text-success' }} fs-5 mt-1">
                            <i class="fas {{ ($stockBajoCount + $agotadosCount) > 0 ? 'fa-exclamation-circle' : 'fa-check-circle' }} me-1"></i>
                            {{ $stockBajoCount + $agotadosCount }} <small class="text-muted fw-normal" style="font-size: 0.8rem;">críticos</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- PRODUCTS IN THIS CATEGORY -->
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
        <div class="card-header bg-white py-3 px-4 border-bottom d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold text-dark d-flex align-items-center gap-2">
                <i class="fas fa-boxes-stacked text-primary"></i>
                Productos y Accesorios en "{{ $categoria->nombre }}"
            </h5>
            <span class="badge bg-light text-secondary border px-3 py-1 rounded-pill">
                {{ $productos->count() }} artículos listados
            </span>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead style="background: #f8fafc; font-size: 0.78rem; text-transform: uppercase; letter-spacing: 0.04em; color: #64748b;">
                    <tr>
                        <th class="ps-4 py-3">Código / SKU</th>
                        <th class="py-3">Producto / Modelo</th>
                        <th class="text-end py-3">Precio Compra</th>
                        <th class="text-end py-3">Precio Venta</th>
                        <th class="text-center py-3">Stock Disponible</th>
                        <th class="text-center py-3">Estado</th>
                        <th class="text-center py-3 pe-4">Acciones</th>
                    </tr>
                </thead>
                <tbody style="font-size: 0.88rem; color: #334155;">
                    @forelse($productos as $prod)
                    <tr>
                        <td class="ps-4 font-monospace fw-bold text-primary" style="font-size: 0.82rem;">
                            {{ $prod->codigo }}
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                @if($prod->imagen)
                                    <img src="{{ asset('storage/' . $prod->imagen) }}" alt="{{ $prod->nombre }}" class="rounded-3 shadow-xs" style="width: 42px; height: 42px; object-fit: cover; border: 1px solid #e2e8f0;">
                                @else
                                    <div style="width: 42px; height: 42px; border-radius: 10px; background: #f1f5f9; display: flex; align-items: center; justify-content: center; color: #94a3b8; font-size: 1.1rem; border: 1px solid #e2e8f0;">
                                        <i class="fas fa-box-open"></i>
                                    </div>
                                @endif
                                <div>
                                    <a href="{{ route('productos.show', $prod) }}" class="fw-bold text-dark text-decoration-none hover-primary d-block">
                                        {{ $prod->nombre }}
                                    </a>
                                    @if($prod->descripcion)
                                        <small class="text-muted text-truncate d-block" style="max-width: 250px;">{{ $prod->descripcion }}</small>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="text-end text-muted">
                            Bs. {{ number_format($prod->precio_compra, 2) }}
                        </td>
                        <td class="text-end fw-bold text-dark">
                            Bs. {{ number_format($prod->precio_venta, 2) }}
                        </td>
                        <td class="text-center">
                            <span class="fw-bold {{ $prod->stock <= 0 ? 'text-danger' : ($prod->stock <= $prod->stock_minimo ? 'text-warning-emphasis' : 'text-success') }}">
                                {{ $prod->stock }} uds.
                            </span>
                        </td>
                        <td class="text-center">
                            @if($prod->stock <= 0)
                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1 rounded-pill">Agotado</span>
                            @elseif($prod->stock <= $prod->stock_minimo)
                                <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle px-2 py-1 rounded-pill">Stock Bajo</span>
                            @else
                                <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 rounded-pill">Óptimo</span>
                            @endif
                        </td>
                        <td class="text-center pe-4">
                            <div class="d-inline-flex gap-1">
                                <a href="{{ route('productos.show', $prod) }}" class="btn btn-xs btn-outline-info rounded-pill px-2 py-1" style="font-size: 0.75rem;" title="Ver Ficha">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('productos.edit', $prod) }}" class="btn btn-xs btn-outline-warning rounded-pill px-2 py-1" style="font-size: 0.75rem;" title="Editar">
                                    <i class="fas fa-pen"></i>
                                </a>
                                <a href="{{ route('inventario.kardex', $prod->id) }}" class="btn btn-xs btn-outline-primary rounded-pill px-2 py-1" style="font-size: 0.75rem;" title="Ver Kardex">
                                    <i class="fas fa-exchange-alt"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5">
                            <div class="py-4">
                                <i class="fas fa-box-open fa-3x text-muted mb-3 d-block"></i>
                                <h6 class="fw-bold text-dark">No hay productos en esta categoría</h6>
                                <p class="text-muted mb-3" style="font-size: 0.85rem;">Aún no se han registrado productos clasificados bajo la categoría "{{ $categoria->nombre }}".</p>
                                <a href="{{ route('productos.create') }}?categoria_id={{ $categoria->id }}" class="btn btn-sm btn-primary rounded-pill px-4">
                                    <i class="fas fa-plus-circle me-1"></i> Registrar Producto en esta Categoría
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

@endsection
