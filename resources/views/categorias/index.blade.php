@extends('dashboard')

@section('content')

<style>
    :root {
        --cat-primary: #ec4899;
        --cat-primary-dark: #db2777;
        --cat-indigo: #6366f1;
        --cat-emerald: #10b981;
        --cat-amber: #f59e0b;
        --cat-blue: #0284c7;
    }

    .categorias-container {
        padding: 1.5rem 1rem;
    }

    /* KPI STAT CARDS */
    .kpi-card {
        background: #ffffff;
        border-radius: 16px;
        border: 1px solid #f1f5f9;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
        padding: 1.25rem;
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
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
        font-size: 1.3rem;
        color: #ffffff;
        flex-shrink: 0;
    }

    .kpi-icon-pink {
        background: linear-gradient(135deg, #ec4899 0%, #db2777 100%);
        box-shadow: 0 6px 15px rgba(236, 72, 153, 0.28);
    }

    .kpi-icon-indigo {
        background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
        box-shadow: 0 6px 15px rgba(99, 102, 241, 0.28);
    }

    .kpi-icon-emerald {
        background: linear-gradient(135deg, #059669 0%, #10b981 100%);
        box-shadow: 0 6px 15px rgba(16, 185, 129, 0.28);
    }

    .kpi-icon-amber {
        background: linear-gradient(135deg, #d97706 0%, #f59e0b 100%);
        box-shadow: 0 6px 15px rgba(245, 158, 11, 0.28);
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
        font-size: 1.55rem;
        font-weight: 800;
        color: #0f172a;
        line-height: 1.2;
    }

    .kpi-subtext {
        font-size: 0.78rem;
        color: #94a3b8;
        font-weight: 500;
        margin-top: 5px;
    }

    /* FILTER CARD */
    .filter-card {
        background: #ffffff;
        border-radius: 16px;
        border: 1px solid #f1f5f9;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
        margin-bottom: 1.5rem;
        padding: 1.25rem;
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
        padding: 14px 18px;
        border-bottom: 1px solid #e2e8f0;
        vertical-align: middle;
        white-space: nowrap;
    }

    .table-custom tbody tr:hover {
        background-color: #f8fafc;
    }

    .table-custom tbody td {
        padding: 14px 18px;
        vertical-align: middle;
        font-size: 0.88rem;
        border-bottom: 1px solid #f1f5f9;
    }

    /* CATEGORY AVATAR */
    .cat-avatar {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #ffffff;
        font-weight: 700;
        font-size: 1.15rem;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        flex-shrink: 0;
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
        background: #fdf2f8;
        color: #db2777;
        border-color: #fbcfe8;
    }

    .btn-action-delete:hover {
        background: #fef2f2;
        color: #ef4444;
        border-color: #fecaca;
    }
</style>

<div class="categorias-container">

    <!-- 1. ENCABEZADO Y ACCIONES PRINCIPALES (MISMO DISEÑO ESTANDARIZADO) -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <div class="kpi-icon-box kpi-icon-pink" style="width: 36px; height: 36px; font-size: 1rem; border-radius: 10px;">
                    <i class="fas fa-tags"></i>
                </div>
                <h2 class="h4 fw-bold mb-0 text-dark">Categorías de Productos & Accesorios</h2>
            </div>
            <p class="text-muted small mb-0">Organice y clasifique el catálogo por líneas comerciales, periféricos, audio, accesorios y componentes.</p>
        </div>
        <div class="d-flex flex-wrap align-items-center gap-2">
            <a href="{{ route('productos.index') }}" class="btn btn-outline-secondary rounded-3 px-3 py-2 fw-semibold d-inline-flex align-items-center gap-2" style="font-size: 0.88rem;">
                <i class="fas fa-boxes-stacked text-primary"></i>
                <span>Ver Catálogo</span>
            </a>

            @if(auth()->user()->esAdmin() || auth()->user()->tienePermiso('categorias'))
            <a href="{{ route('categorias.create') }}" class="btn text-white rounded-3 px-3 py-2 fw-semibold d-inline-flex align-items-center gap-2" style="background: linear-gradient(135deg, #ec4899 0%, #db2777 100%); font-size: 0.88rem; box-shadow: 0 4px 14px rgba(236, 72, 153, 0.35);">
                <i class="fas fa-plus"></i>
                <span>Nueva Categoría</span>
            </a>
            @endif
        </div>
    </div>

    <!-- 2. ALERTAS / FLASH MESSAGES -->
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show rounded-4 border-0 shadow-sm d-flex align-items-center gap-3 p-3 mb-4" role="alert" style="background: #ecfdf5; color: #065f46;">
        <div style="width: 36px; height: 36px; border-radius: 10px; background: #10b981; color: #fff; display: flex; align-items: center; justify-content: center; font-size: 1rem; flex-shrink: 0;">
            <i class="fas fa-check"></i>
        </div>
        <div class="flex-grow-1">
            <h6 class="mb-0 fw-bold">Operación Exitosa</h6>
            <div style="font-size: 0.88rem;">{{ session('success') }}</div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show rounded-4 border-0 shadow-sm d-flex align-items-center gap-3 p-3 mb-4" role="alert" style="background: #fff1f2; color: #9f1239;">
        <div style="width: 36px; height: 36px; border-radius: 10px; background: #f43f5e; color: #fff; display: flex; align-items: center; justify-content: center; font-size: 1rem; flex-shrink: 0;">
            <i class="fas fa-exclamation-triangle"></i>
        </div>
        <div class="flex-grow-1">
            <h6 class="mb-0 fw-bold">Atención</h6>
            <div style="font-size: 0.88rem;">{{ session('error') }}</div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <!-- 3. KPI STAT CARDS (4 TARJETAS ESTANDARIZADAS) -->
    <div class="row g-3 mb-4">
        <!-- Total Categorías -->
        <div class="col-xl-3 col-sm-6">
            <div class="kpi-card">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <div>
                        <div class="kpi-label">Categorías Activas</div>
                        <div class="kpi-value">{{ $totalCategorias }}</div>
                    </div>
                    <div class="kpi-icon-box kpi-icon-pink">
                        <i class="fas fa-tags"></i>
                    </div>
                </div>
                <div class="kpi-subtext">
                    <i class="fas fa-layer-group text-pink me-1" style="color: #ec4899;"></i> Segmentos en el catálogo
                </div>
            </div>
        </div>

        <!-- Artículos / Modelos -->
        <div class="col-xl-3 col-sm-6">
            <div class="kpi-card">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <div>
                        <div class="kpi-label">Artículos Clasificados</div>
                        <div class="kpi-value">{{ $totalModelos }}</div>
                    </div>
                    <div class="kpi-icon-box kpi-icon-indigo">
                        <i class="fas fa-boxes-stacked"></i>
                    </div>
                </div>
                <div class="kpi-subtext">
                    <i class="fas fa-check-double text-indigo me-1" style="color: #6366f1;"></i> Productos distribuidos
                </div>
            </div>
        </div>

        <!-- Stock Total Acumulado -->
        <div class="col-xl-3 col-sm-6">
            <div class="kpi-card">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <div>
                        <div class="kpi-label">Stock Acumulado</div>
                        <div class="kpi-value text-success">{{ number_format($totalStockGlobal) }} <span style="font-size: 0.85rem; font-weight: 600; color: #64748b;">uds.</span></div>
                    </div>
                    <div class="kpi-icon-box kpi-icon-emerald">
                        <i class="fas fa-boxes-stacked"></i>
                    </div>
                </div>
                <div class="kpi-subtext">
                    <i class="fas fa-warehouse text-success me-1"></i> Disponibles en almacén
                </div>
            </div>
        </div>

        <!-- Gama Líder en Variedad -->
        <div class="col-xl-3 col-sm-6">
            <div class="kpi-card">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <div class="overflow-hidden pe-2">
                        <div class="kpi-label">Líder en Variedad</div>
                        <div class="kpi-value text-truncate" style="font-size: 1.25rem;" title="{{ $categoriaLider->nombre ?? 'Ninguna' }}">
                            {{ $categoriaLider->nombre ?? 'Sin datos' }}
                        </div>
                    </div>
                    <div class="kpi-icon-box kpi-icon-amber">
                        <i class="fas fa-crown"></i>
                    </div>
                </div>
                <div class="kpi-subtext">
                    <i class="fas fa-chart-pie text-warning me-1"></i> {{ $categoriaLider->productos_count ?? 0 }} modelos asignados
                </div>
            </div>
        </div>
    </div>

    <!-- 4. BARRA DE FILTROS Y BÚSQUEDA -->
    <div class="filter-card">
        <form method="GET" action="{{ route('categorias.index') }}" class="row g-2 align-items-center">
            <div class="col-lg-6 col-md-6">
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0 text-muted rounded-start-3 ps-3">
                        <i class="fas fa-search"></i>
                    </span>
                    <input type="text" name="buscar" value="{{ request('buscar') }}" class="form-control bg-light border-start-0 rounded-end-3" placeholder="Buscar categoría por nombre o descripción...">
                </div>
            </div>

            <div class="col-lg-4 col-md-4">
                <select name="orden" class="form-select rounded-3" onchange="this.form.submit()">
                    <option value="nombre_asc" {{ request('orden') == 'nombre_asc' ? 'selected' : '' }}>Ordenar: Nombre (A - Z)</option>
                    <option value="nombre_desc" {{ request('orden') == 'nombre_desc' ? 'selected' : '' }}>Ordenar: Nombre (Z - A)</option>
                    <option value="mas_productos" {{ request('orden') == 'mas_productos' ? 'selected' : '' }}>Más Modelos / Artículos</option>
                    <option value="menos_productos" {{ request('orden') == 'menos_productos' ? 'selected' : '' }}>Menos Modelos</option>
                    <option value="recientes" {{ request('orden') == 'recientes' ? 'selected' : '' }}>Más Recientes</option>
                </select>
            </div>

            <div class="col-lg-2 col-md-2 d-flex gap-2">
                <button type="submit" class="btn text-white rounded-3 w-100 fw-semibold" style="background: linear-gradient(135deg, #ec4899 0%, #db2777 100%); border: none;">
                    <i class="fas fa-filter me-1"></i> Filtrar
                </button>
                @if(request()->filled('buscar') || request()->filled('orden'))
                <a href="{{ route('categorias.index') }}" class="btn btn-outline-secondary rounded-3 px-3" title="Limpiar filtros">
                    <i class="fas fa-times"></i>
                </a>
                @endif
            </div>
        </form>
    </div>

    <!-- 5. TABLA ESTANDARIZADA DE CATEGORÍAS -->
    <div class="table-container-card">
        <div class="p-3 px-4 border-bottom bg-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold text-dark d-flex align-items-center gap-2">
                <i class="fas fa-tags text-pink" style="color: #ec4899;"></i>
                Listado de Categorías
            </h5>
            <span class="badge bg-light text-secondary border px-3 py-2 rounded-pill font-monospace" style="font-size: 0.8rem;">
                {{ $categorias->count() }} registradas
            </span>
        </div>

        <div class="table-responsive">
            <table class="table table-custom align-middle">
                <thead>
                    <tr>
                        <th class="ps-4" style="width: 50px;"># ID</th>
                        <th>Categoría</th>
                        <th>Descripción</th>
                        <th class="text-center">Productos Asignados</th>
                        <th class="text-center">Stock Físico</th>
                        <th class="text-end">Inversión Valorizada</th>
                        <th class="text-center pe-4" style="width: 140px;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categorias as $cat)
                    @php
                        $stockCat = $cat->stock_total_acumulado ?? $cat->productos->sum('stock');
                        $valorCat = $cat->valor_inventario_acumulado ?? $cat->productos->sum(function($p) { return $p->stock * $p->precio_compra; });
                    @endphp
                    <tr>
                        <td class="ps-4 fw-bold text-muted font-monospace" style="font-size: 0.82rem;">
                            #{{ str_pad($cat->id, 3, '0', STR_PAD_LEFT) }}
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                <div class="cat-avatar" style="background: {{ $cat->avatar_gradient }};">
                                    <i class="{{ $cat->icono }}"></i>
                                </div>
                                <div>
                                    <a href="{{ route('categorias.show', $cat) }}" class="fw-bold text-dark text-decoration-none d-block hover-pink" style="font-size: 0.92rem;">
                                        {{ $cat->nombre }}
                                    </a>
                                    <small class="text-muted">Creado el {{ $cat->created_at->format('d/m/Y') }}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            @if($cat->descripcion)
                                <span class="text-secondary" style="max-width: 320px; display: inline-block; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="{{ $cat->descripcion }}">
                                    {{ $cat->descripcion }}
                                </span>
                            @else
                                <span class="badge bg-light text-muted border border-dashed px-2 py-1" style="font-size: 0.75rem;">Sin descripción</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <a href="{{ route('productos.index', ['categoria_id' => $cat->id]) }}" class="badge rounded-pill text-decoration-none px-3 py-2 {{ $cat->productos_count > 0 ? 'bg-primary-subtle text-primary border border-primary-subtle' : 'bg-light text-muted border' }}" title="Ver productos asignados a esta categoría">
                                <i class="fas fa-boxes-stacked me-1"></i> {{ $cat->productos_count }} artículos
                            </a>
                        </td>
                        <td class="text-center">
                            @if($stockCat > 0)
                                <span class="fw-bold text-success">
                                    {{ number_format($stockCat) }} <small class="text-muted fw-normal">uds.</small>
                                </span>
                            @else
                                <span class="badge bg-secondary-subtle text-secondary px-2 py-1">0 uds.</span>
                            @endif
                        </td>
                        <td class="text-end fw-bold text-dark">
                            Bs. {{ number_format($valorCat, 2) }}
                        </td>
                        <td class="text-center pe-4">
                            <div class="d-inline-flex gap-1">
                                <!-- Ver Ficha Detallada -->
                                <a href="{{ route('categorias.show', $cat) }}" class="btn-action-icon btn-action-view" title="Ver catálogo de la categoría">
                                    <i class="fas fa-eye"></i>
                                </a>

                                <!-- Editar Categoría -->
                                @if(auth()->user()->esAdmin() || auth()->user()->tienePermiso('categorias'))
                                <a href="{{ route('categorias.edit', $cat) }}" class="btn-action-icon btn-action-edit" title="Editar categoría">
                                    <i class="fas fa-pen"></i>
                                </a>

                                <!-- Eliminar Categoría -->
                                <button type="button" class="btn-action-icon btn-action-delete" title="Eliminar categoría" onclick="confirmDelete('{{ $cat->id }}', '{{ addslashes($cat->nombre) }}', {{ $cat->productos_count }})">
                                    <i class="fas fa-trash"></i>
                                </button>

                                <form id="delete-form-{{ $cat->id }}" action="{{ route('categorias.destroy', $cat) }}" method="POST" style="display: none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5">
                            <div class="py-4">
                                <div style="width: 64px; height: 64px; border-radius: 50%; background: #fdf2f8; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px; color: #ec4899; font-size: 1.8rem;">
                                    <i class="fas fa-tags"></i>
                                </div>
                                <h5 class="fw-bold text-dark">No se encontraron categorías</h5>
                                <p class="text-muted mb-3" style="font-size: 0.88rem;">No hay categorías registradas o que coincidan con la búsqueda.</p>
                                @if(auth()->user()->esAdmin() || auth()->user()->tienePermiso('categorias'))
                                <a href="{{ route('categorias.create') }}" class="btn btn-sm text-white rounded-pill px-4" style="background: linear-gradient(135deg, #ec4899 0%, #db2777 100%);">
                                    <i class="fas fa-plus me-1"></i> Crear Primera Categoría
                                </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

<!-- SWEETALERT2 INTEGRATION -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function confirmDelete(id, nombre, productosCount) {
        if (productosCount > 0) {
            Swal.fire({
                title: '¡Acción no permitida!',
                html: `La categoría <b>"${nombre}"</b> contiene <b>${productosCount} producto(s)</b> asignado(s).<br><br>No puedes eliminarla sin antes reasignar o borrar sus productos asociados.`,
                icon: 'warning',
                confirmButtonColor: '#ec4899',
                confirmButtonText: 'Entendido'
            });
            return;
        }

        Swal.fire({
            title: '¿Eliminar Categoría?',
            html: `¿Estás seguro de eliminar la categoría <b>"${nombre}"</b>?<br><small class="text-muted">Esta acción no se puede deshacer.</small>`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#64748b',
            confirmButtonText: '<i class="fas fa-trash me-1"></i> Sí, eliminar',
            cancelButtonText: 'Cancelar',
            reverseButtons: true,
            customClass: {
                popup: 'rounded-4 shadow'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + id).submit();
            }
        });
    }
</script>

@endsection