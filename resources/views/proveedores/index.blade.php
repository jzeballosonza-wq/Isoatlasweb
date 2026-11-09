@extends('dashboard')

@section('content')

<style>
    :root {
        --prov-primary: #0284c7;
        --prov-primary-dark: #0369a1;
        --prov-success: #10b981;
        --prov-warning: #f59e0b;
        --prov-danger: #ef4444;
        --prov-purple: #7c3aed;
    }

    .proveedores-container {
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

    .kpi-icon-blue {
        background: linear-gradient(135deg, #0284c7 0%, #38bdf8 100%);
        box-shadow: 0 6px 15px rgba(2, 132, 199, 0.25);
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

    /* TABLE MODERN */
    .table-container-card {
        background: #ffffff;
        border-radius: 18px;
        border: 1px solid #f1f5f9;
        box-shadow: 0 4px 25px rgba(0, 0, 0, 0.03);
        overflow: hidden;
    }

    .table-custom thead th {
        background: #f8fafc;
        color: #475569;
        font-weight: 700;
        font-size: 0.78rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        padding: 1rem 1.2rem;
        border-bottom: 1px solid #e2e8f0;
        white-space: nowrap;
    }

    .table-custom tbody td {
        padding: 1rem 1.2rem;
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9;
        font-size: 0.88rem;
    }

    .table-custom tbody tr:hover td {
        background-color: #f8fafc;
    }

    /* AVATAR CIRCLE */
    .prov-avatar {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
        font-weight: 700;
        color: #ffffff;
        flex-shrink: 0;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }
</style>

<div class="proveedores-container">

    <!-- 1. ENCABEZADO Y ACCIONES PRINCIPALES -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <div class="kpi-icon-box kpi-icon-blue" style="width: 36px; height: 36px; font-size: 1rem; border-radius: 10px;">
                    <i class="fas fa-truck-loading"></i>
                </div>
                <h2 class="h4 fw-bold mb-0 text-dark">Directorio de Proveedores</h2>
            </div>
            <p class="text-muted small mb-0">
                Gestión de proveedores de accesorios electrónicos, marcas de periféricos y contactos de abastecimiento.
            </p>
        </div>
        <div class="d-flex flex-wrap align-items-center gap-2">
            @if(auth()->user()->tienePermiso('compras'))
            <a href="{{ route('compras.create') }}" class="btn btn-outline-secondary rounded-3 px-3 py-2 fw-semibold d-inline-flex align-items-center gap-2" style="font-size: 0.88rem;">
                <i class="fas fa-shopping-cart text-primary"></i>
                <span>Nueva Compra</span>
            </a>
            @endif

            <a href="{{ route('proveedores.create') }}" class="btn text-white rounded-3 px-3 py-2 fw-semibold d-inline-flex align-items-center gap-2" style="background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%); font-size: 0.88rem; box-shadow: 0 4px 14px rgba(2, 132, 199, 0.35);">
                <i class="fas fa-plus"></i>
                <span>Nuevo Proveedor</span>
            </a>
        </div>
    </div>

    <!-- FLASH MESSAGES -->
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-4 mb-4" role="alert">
        <div class="d-flex align-items-center gap-2">
            <i class="fas fa-check-circle fs-5 text-success"></i>
            <div class="fw-semibold">{{ session('success') }}</div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm rounded-4 mb-4" role="alert">
        <div class="d-flex align-items-center gap-2">
            <i class="fas fa-exclamation-triangle fs-5 text-danger"></i>
            <div class="fw-semibold">{{ session('error') }}</div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <!-- 2. KPI STATS CARDS -->
    <div class="row g-3 mb-4">
        <!-- Total Proveedores -->
        <div class="col-xl-3 col-sm-6">
            <div class="kpi-card">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="kpi-label">Proveedores Totales</div>
                        <div class="kpi-value">{{ $totalProveedores }}</div>
                        <div class="kpi-subtext">Empresas en directorio</div>
                    </div>
                    <div class="kpi-icon-box kpi-icon-blue">
                        <i class="fas fa-building"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Proveedores Activos con Compras -->
        <div class="col-xl-3 col-sm-6">
            <div class="kpi-card">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="kpi-label">Proveedores Activos</div>
                        <div class="kpi-value text-success">{{ $proveedoresActivos }}</div>
                        <div class="kpi-subtext">Con compras asociadas</div>
                    </div>
                    <div class="kpi-icon-box kpi-icon-emerald">
                        <i class="fas fa-handshake"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Órdenes de Compra -->
        <div class="col-xl-3 col-sm-6">
            <div class="kpi-card">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="kpi-label">Órdenes Realizadas</div>
                        <div class="kpi-value text-indigo">{{ $totalCompras }}</div>
                        <div class="kpi-subtext">Pedidos de abastecimiento</div>
                    </div>
                    <div class="kpi-icon-box kpi-icon-indigo">
                        <i class="fas fa-boxes-stacked"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Invertido -->
        <div class="col-xl-3 col-sm-6">
            <div class="kpi-card">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="kpi-label">Total Comprado</div>
                        <div class="kpi-value text-dark" style="font-size: 1.3rem;">
                            Bs. {{ number_format($montoTotalComprado, 2) }}
                        </div>
                        <div class="kpi-subtext">Inversión acumulada</div>
                    </div>
                    <div class="kpi-icon-box kpi-icon-amber">
                        <i class="fas fa-coins"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 3. PANEL DE BÚSQUEDA Y FILTROS -->
    <div class="filter-card">
        <div class="p-3 border-bottom bg-light d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div class="d-flex align-items-center gap-2">
                <i class="fas fa-search text-primary"></i>
                <span class="fw-bold text-dark" style="font-size: 0.9rem;">Búsqueda y Filtros de Proveedores</span>
            </div>
            <span class="badge bg-light text-secondary border px-2 py-1" id="provCounterText" style="font-size: 0.75rem;">
                Mostrando {{ count($proveedores) }} proveedores
            </span>
        </div>
        <div class="p-3">
            <div class="row g-3">
                <!-- Buscador -->
                <div class="col-md-7 col-lg-8">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0 text-muted">
                            <i class="fas fa-search"></i>
                        </span>
                        <input type="text" id="provSearchInput" class="form-control border-start-0 ps-0" placeholder="Buscar por empresa, contacto, teléfono, celular o correo..." onkeyup="filterProvTable()">
                    </div>
                </div>

                <!-- Filtro Actividad -->
                <div class="col-md-5 col-lg-4">
                    <div class="d-flex gap-2">
                        <select id="provStatusSelect" class="form-select" onchange="filterProvTable()">
                            <option value="">Todos los Proveedores</option>
                            <option value="con_compras">Con Órdenes de Compra</option>
                            <option value="sin_compras">Sin Compras Aún</option>
                        </select>

                        <button type="button" class="btn btn-outline-secondary px-3" onclick="resetProvFilters()" title="Limpiar filtros">
                            <i class="fas fa-undo"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 4. TABLA DE PROVEEDORES -->
    <div class="table-container-card">
        <div class="table-responsive">
            <table class="table table-custom align-middle" id="provTable">
                <thead>
                    <tr>
                        <th width="50" class="text-center">#</th>
                        <th>Empresa / Proveedor</th>
                        <th>Persona de Contacto</th>
                        <th>Teléfonos & WhatsApp</th>
                        <th>Correo Electrónico</th>
                        <th class="text-center">Órdenes</th>
                        <th class="text-end">Total Comprado</th>
                        <th width="140" class="text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody id="provTableBody">
                    @forelse($proveedores as $key => $p)
                    @php
                        $comprasCount = $p->compras_count ?? 0;
                        $totalInvertido = $p->compras_sum_total ?? 0;
                        $hasCompras = $comprasCount > 0 ? 'con_compras' : 'sin_compras';
                    @endphp
                    <tr class="prov-row"
                        data-name="{{ mb_strtolower($p->nombre) }}"
                        data-contact="{{ mb_strtolower($p->contacto ?? '') }}"
                        data-phone="{{ mb_strtolower($p->telefono) }} {{ mb_strtolower($p->celular ?? '') }}"
                        data-email="{{ mb_strtolower($p->email ?? '') }}"
                        data-address="{{ mb_strtolower($p->direccion) }}"
                        data-status="{{ $hasCompras }}">

                        <td class="text-center text-muted fw-bold">{{ $key + 1 }}</td>

                        <!-- Empresa -->
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                <div class="prov-avatar" style="background: {{ $p->avatar_gradient }};">
                                    {{ $p->iniciales }}
                                </div>
                                <div>
                                    <a href="{{ route('proveedores.show', $p->id) }}" class="fw-bold text-dark text-decoration-none hover-primary" style="font-size: 0.92rem;">
                                        {{ $p->nombre }}
                                    </a>
                                    <div class="text-muted small d-flex align-items-center gap-1 mt-1" style="font-size: 0.75rem;">
                                        <i class="fas fa-map-marker-alt text-danger"></i>
                                        <span>{{ Str::limit($p->direccion, 35) }}</span>
                                    </div>
                                </div>
                            </div>
                        </td>

                        <!-- Contacto -->
                        <td>
                            @if($p->contacto)
                                <div class="fw-semibold text-dark" style="font-size: 0.85rem;">
                                    <i class="fas fa-user-tie text-secondary me-1"></i> {{ $p->contacto }}
                                </div>
                            @else
                                <span class="text-muted small fst-italic">Sin contacto registrado</span>
                            @endif
                        </td>

                        <!-- Teléfono / Celular -->
                        <td>
                            <div class="d-flex flex-column gap-1">
                                <div class="d-flex align-items-center gap-1 small">
                                    <i class="fas fa-phone-alt text-primary" style="font-size: 0.75rem;"></i>
                                    <span class="fw-medium text-dark">{{ $p->telefono }}</span>
                                </div>
                                @if($p->celular)
                                <div class="d-flex align-items-center gap-1 small">
                                    <i class="fab fa-whatsapp text-success" style="font-size: 0.85rem;"></i>
                                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $p->celular) }}" target="_blank" class="text-decoration-none text-success fw-semibold">
                                        {{ $p->celular }}
                                    </a>
                                </div>
                                @endif
                            </div>
                        </td>

                        <!-- Email -->
                        <td>
                            @if($p->email)
                                <a href="mailto:{{ $p->email }}" class="text-decoration-none text-secondary small d-inline-flex align-items-center gap-1">
                                    <i class="far fa-envelope text-primary"></i>
                                    <span>{{ $p->email }}</span>
                                </a>
                            @else
                                <span class="text-muted small fst-italic">Sin correo</span>
                            @endif
                        </td>

                        <!-- Órdenes de compra -->
                        <td class="text-center">
                            @if($comprasCount > 0)
                                <a href="{{ route('proveedores.show', $p->id) }}" class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-2 py-1 text-decoration-none" title="Ver compras registradas">
                                    <i class="fas fa-shopping-bag me-1"></i> {{ $comprasCount }} {{ Str::plural('compra', $comprasCount) }}
                                </a>
                            @else
                                <span class="badge bg-light text-muted border rounded-pill px-2 py-1">
                                    0 compras
                                </span>
                            @endif
                        </td>

                        <!-- Total comprado -->
                        <td class="text-end fw-bold {{ $totalInvertido > 0 ? 'text-dark' : 'text-muted' }}">
                            Bs. {{ number_format($totalInvertido, 2) }}
                        </td>

                        <!-- Acciones -->
                        <td class="text-end">
                            <div class="d-inline-flex align-items-center gap-1">
                                <!-- Ver Ficha -->
                                <a href="{{ route('proveedores.show', $p->id) }}" class="btn btn-sm btn-light border rounded-3 text-primary px-2" title="Ver Ficha y Compras">
                                    <i class="fas fa-eye"></i>
                                </a>

                                <!-- Editar -->
                                <a href="{{ route('proveedores.edit', $p->id) }}" class="btn btn-sm btn-light border rounded-3 text-warning px-2" title="Editar Proveedor">
                                    <i class="fas fa-edit"></i>
                                </a>

                                <!-- Eliminar -->
                                <button type="button" class="btn btn-sm btn-light border rounded-3 text-danger px-2" title="Eliminar Proveedor" onclick="confirmDeleteProv({{ $p->id }}, '{{ addslashes($p->nombre) }}', {{ $comprasCount }})">
                                    <i class="fas fa-trash-alt"></i>
                                </button>

                                <form id="delete-prov-form-{{ $p->id }}" action="{{ route('proveedores.destroy', $p->id) }}" method="POST" class="d-none">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr id="emptyProvRow">
                        <td colspan="8" class="text-center py-5 text-muted">
                            <div class="py-4">
                                <div class="kpi-icon-box kpi-icon-blue mx-auto mb-3" style="width: 56px; height: 56px;">
                                    <i class="fas fa-truck-loading fs-4"></i>
                                </div>
                                <h6 class="fw-bold text-dark">No hay proveedores registrados</h6>
                                <p class="small text-muted mb-3">Registra los distribuidores mayoristas de accesorios y componentes para gestionar compras.</p>
                                <a href="{{ route('proveedores.create') }}" class="btn btn-primary rounded-3 px-4 shadow-sm">
                                    <i class="fas fa-plus me-1"></i> Registrar Primer Proveedor
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse

                    <tr id="noProvResultsRow" style="display: none;">
                        <td colspan="8" class="text-center py-5 text-muted">
                            <div class="py-3">
                                <i class="fas fa-search fa-2x text-muted mb-2 d-block"></i>
                                <h6 class="fw-bold text-dark mb-1">Sin coincidencias</h6>
                                <p class="small text-muted mb-0">No se encontraron proveedores con los criterios de búsqueda aplicados.</p>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

</div>

<!-- SCRIPTS DE INTERACTIVIDAD -->
<script>
function filterProvTable() {
    const textQuery = document.getElementById('provSearchInput').value.toLowerCase().trim();
    const statusFilter = document.getElementById('provStatusSelect').value.trim();
    const rows = document.querySelectorAll('.prov-row');
    let visibleCount = 0;

    rows.forEach(row => {
        const name = row.getAttribute('data-name') || '';
        const contact = row.getAttribute('data-contact') || '';
        const phone = row.getAttribute('data-phone') || '';
        const email = row.getAttribute('data-email') || '';
        const address = row.getAttribute('data-address') || '';
        const status = row.getAttribute('data-status') || '';

        const matchesText = textQuery === '' || name.includes(textQuery) || contact.includes(textQuery) || phone.includes(textQuery) || email.includes(textQuery) || address.includes(textQuery);
        const matchesStatus = statusFilter === '' || status === statusFilter;

        if (matchesText && matchesStatus) {
            row.style.display = '';
            visibleCount++;
        } else {
            row.style.display = 'none';
        }
    });

    const noResultsRow = document.getElementById('noProvResultsRow');
    if (noResultsRow) {
        noResultsRow.style.display = (visibleCount === 0 && rows.length > 0) ? '' : 'none';
    }

    const counterText = document.getElementById('provCounterText');
    if (counterText) {
        counterText.textContent = `Mostrando ${visibleCount} de ${rows.length} proveedores`;
    }
}

function resetProvFilters() {
    document.getElementById('provSearchInput').value = '';
    document.getElementById('provStatusSelect').value = '';
    filterProvTable();
}

function confirmDeleteProv(provId, provName, comprasCount) {
    if (comprasCount > 0) {
        Swal.fire({
            title: 'Acción no permitida',
            html: `No puedes eliminar al proveedor <strong>${provName}</strong> porque tiene <strong>${comprasCount}</strong> orden(es) de compra registradas en el historial.`,
            icon: 'error',
            confirmButtonColor: '#0284c7',
            confirmButtonText: 'Entendido',
            customClass: { popup: 'rounded-4' }
        });
        return;
    }

    Swal.fire({
        title: '¿Eliminar proveedor?',
        html: `Estás a punto de eliminar a <strong>${provName}</strong> del directorio.<br><span class="text-danger small">Esta acción no se puede deshacer.</span>`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#64748b',
        confirmButtonText: '<i class="fas fa-trash me-1"></i> Sí, eliminar',
        cancelButtonText: 'Cancelar',
        customClass: { popup: 'rounded-4' }
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('delete-prov-form-' + provId).submit();
        }
    });
}
</script>

@endsection