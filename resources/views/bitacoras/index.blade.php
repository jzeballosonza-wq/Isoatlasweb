@extends('dashboard')

@section('content')
<div class="container-fluid px-3 px-md-4 py-3">

    <!-- 1. ENCABEZADO Y ACCIONES PRINCIPALES -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <span class="badge bg-indigo-subtle text-indigo px-2.5 py-1.5 rounded-pill fw-semibold" style="background: rgba(99, 102, 241, 0.1); color: #6366f1; font-size: 0.75rem;">
                    <i class="fas fa-shield-alt me-1"></i> Auditoría y Seguridad
                </span>
                <span class="text-muted" style="font-size: 0.8rem;">|</span>
                <span class="text-muted" style="font-size: 0.8rem;">Control de Trazabilidad</span>
            </div>
            <h1 class="h3 fw-bold text-dark mb-1" style="letter-spacing: -0.02em;">
                Bitácora de Actividades
            </h1>
            <p class="text-muted mb-0" style="font-size: 0.88rem;">
                Registro cronológico detallado de transacciones, accesos y modificaciones efectuadas en el sistema.
            </p>
        </div>

        <div class="d-flex flex-wrap align-items-center gap-2">
            <!-- Botón Exportar CSV -->
            <a href="{{ route('bitacoras.exportar', request()->query()) }}" class="btn btn-outline-success rounded-pill px-3 py-2 fw-semibold shadow-sm d-inline-flex align-items-center gap-2" style="font-size: 0.85rem;" title="Exportar registros filtrados a CSV para Excel">
                <i class="fas fa-file-csv fs-6"></i>
                <span>Exportar CSV</span>
            </a>

            <!-- Botón Imprimir Reporte -->
            <button type="button" onclick="window.print()" class="btn btn-outline-secondary rounded-pill px-3 py-2 fw-semibold shadow-sm d-inline-flex align-items-center gap-2" style="font-size: 0.85rem;" title="Imprimir listado actual">
                <i class="fas fa-print"></i>
                <span class="d-none d-sm-inline">Imprimir</span>
            </button>

            @if(auth()->check() && auth()->user()->esAdmin())
            <!-- Botón Depurar Logs (Solo Administradores) -->
            <button type="button" class="btn btn-outline-danger rounded-pill px-3 py-2 fw-semibold shadow-sm d-inline-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#limpiarBitacoraModal" style="font-size: 0.85rem;">
                <i class="fas fa-broom"></i>
                <span>Depurar Logs</span>
            </button>
            @endif
        </div>
    </div>

    <!-- 2. TARJETAS KPI DE ESTADÍSTICAS -->
    <div class="row g-3 mb-4">
        <!-- KPI 1: Total Registros -->
        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-4 h-100 p-3" style="background: #ffffff; border: 1px solid #f1f5f9 !important;">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="text-muted text-uppercase fw-bold" style="font-size: 0.72rem; letter-spacing: 0.05em;">Total Eventos</span>
                        <h2 class="fw-bolder text-dark mb-0 mt-1" style="font-size: 1.7rem;">
                            {{ number_format($totalRegistros) }}
                        </h2>
                        <small class="text-muted" style="font-size: 0.76rem;">Histórico en base de datos</small>
                    </div>
                    <div class="d-flex align-items-center justify-content-center rounded-4 text-white shadow-sm" style="width: 48px; height: 48px; background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);">
                        <i class="fas fa-layer-group fs-5"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- KPI 2: Actividad Hoy -->
        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-4 h-100 p-3" style="background: #ffffff; border: 1px solid #f1f5f9 !important;">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="text-muted text-uppercase fw-bold" style="font-size: 0.72rem; letter-spacing: 0.05em;">Actividad Hoy</span>
                        <h2 class="fw-bolder text-success mb-0 mt-1" style="font-size: 1.7rem;">
                            {{ number_format($registrosHoy) }}
                        </h2>
                        <small class="text-muted" style="font-size: 0.76rem;">Eventos registrados hoy</small>
                    </div>
                    <div class="d-flex align-items-center justify-content-center rounded-4 text-white shadow-sm" style="width: 48px; height: 48px; background: linear-gradient(135deg, #059669 0%, #10b981 100%);">
                        <i class="fas fa-clock fs-5"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- KPI 3: Operaciones Críticas -->
        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-4 h-100 p-3" style="background: #ffffff; border: 1px solid #f1f5f9 !important;">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="text-muted text-uppercase fw-bold" style="font-size: 0.72rem; letter-spacing: 0.05em;">Operaciones Críticas</span>
                        <h2 class="fw-bolder text-danger mb-0 mt-1" style="font-size: 1.7rem;">
                            {{ number_format($accionesCriticas) }}
                        </h2>
                        <small class="text-muted" style="font-size: 0.76rem;">Eliminaciones y restauraciones</small>
                    </div>
                    <div class="d-flex align-items-center justify-content-center rounded-4 text-white shadow-sm" style="width: 48px; height: 48px; background: linear-gradient(135deg, #dc2626 0%, #ef4444 100%);">
                        <i class="fas fa-trash-alt fs-5"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- KPI 4: Módulo Más Activo -->
        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-4 h-100 p-3" style="background: #ffffff; border: 1px solid #f1f5f9 !important;">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="text-muted text-uppercase fw-bold" style="font-size: 0.72rem; letter-spacing: 0.05em;">Módulo Más Activo</span>
                        <h3 class="fw-bolder text-primary mb-0 mt-1 text-truncate" style="font-size: 1.25rem; max-width: 150px;" title="{{ $moduloMasActivo }}">
                            {{ $moduloMasActivo }}
                        </h3>
                        <small class="text-muted" style="font-size: 0.76rem;">{{ number_format($moduloMasActivoCount) }} registros</small>
                    </div>
                    <div class="d-flex align-items-center justify-content-center rounded-4 text-white shadow-sm" style="width: 48px; height: 48px; background: linear-gradient(135deg, #d97706 0%, #f59e0b 100%);">
                        <i class="fas fa-chart-pie fs-5"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 3. PANEL DE BÚSQUEDA Y FILTROS AVANZADOS -->
    <div class="card border-0 shadow-sm rounded-4 mb-4" style="background: #ffffff; border: 1px solid #e2e8f0 !important;">
        <div class="card-body p-3 p-md-4">
            <form method="GET" action="{{ route('bitacoras.index') }}" id="filtroBitacoraForm">

                <!-- Fila 1: Búsqueda y Rango Rápido -->
                <div class="row g-3 align-items-center mb-3">
                    <div class="col-lg-6">
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0 text-muted rounded-start-pill ps-3">
                                <i class="fas fa-search"></i>
                            </span>
                            <input type="text" 
                                   name="buscar" 
                                   class="form-control border-start-0 border-end-0 py-2" 
                                   placeholder="Buscar por descripción, usuario, módulo, acción o IP..." 
                                   value="{{ request('buscar') }}"
                                   style="font-size: 0.88rem;">
                            @if(request('buscar'))
                                <a href="{{ route('bitacoras.index', request()->except('buscar')) }}" class="input-group-text bg-white border-start-0 text-muted text-decoration-none">
                                    <i class="fas fa-times"></i>
                                </a>
                            @endif
                            <button type="submit" class="btn btn-primary rounded-end-pill px-4 fw-semibold" style="background: var(--primary-gradient); border: none; font-size: 0.88rem;">
                                Buscar
                            </button>
                        </div>
                    </div>

                    <!-- Botones de Rango Rápido -->
                    <div class="col-lg-6">
                        <div class="d-flex flex-wrap align-items-center justify-content-lg-end gap-1">
                            <span class="text-muted fw-bold me-1" style="font-size: 0.75rem; text-transform: uppercase;">Atajos:</span>
                            <button type="submit" name="rango_rapido" value="" class="btn btn-sm {{ !request('rango_rapido') && !request('fecha_desde') ? 'btn-dark' : 'btn-light' }} rounded-pill px-2.5 py-1" style="font-size: 0.78rem;">
                                Todos
                            </button>
                            <button type="submit" name="rango_rapido" value="hoy" class="btn btn-sm {{ request('rango_rapido') === 'hoy' ? 'btn-primary' : 'btn-light' }} rounded-pill px-2.5 py-1" style="font-size: 0.78rem;">
                                Hoy
                            </button>
                            <button type="submit" name="rango_rapido" value="esta_semana" class="btn btn-sm {{ request('rango_rapido') === 'esta_semana' ? 'btn-primary' : 'btn-light' }} rounded-pill px-2.5 py-1" style="font-size: 0.78rem;">
                                Esta Semana
                            </button>
                            <button type="submit" name="rango_rapido" value="este_mes" class="btn btn-sm {{ request('rango_rapido') === 'este_mes' ? 'btn-primary' : 'btn-light' }} rounded-pill px-2.5 py-1" style="font-size: 0.78rem;">
                                Este Mes
                            </button>
                            <button type="submit" name="rango_rapido" value="ultimos_30_dias" class="btn btn-sm {{ request('rango_rapido') === 'ultimos_30_dias' ? 'btn-primary' : 'btn-light' }} rounded-pill px-2.5 py-1" style="font-size: 0.78rem;">
                                Últimos 30 días
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Fila 2: Filtros desplegables específicos -->
                <div class="row g-2 pt-2 border-top">
                    <!-- Filtro Módulo -->
                    <div class="col-6 col-md-4 col-lg-2">
                        <label class="form-label text-muted fw-bold mb-1" style="font-size: 0.72rem; text-transform: uppercase;">Módulo</label>
                        <select name="modulo" class="form-select form-select-sm rounded-3" onchange="document.getElementById('filtroBitacoraForm').submit()">
                            <option value="">Todos los módulos</option>
                            @foreach($modulosList as $mod)
                                <option value="{{ $mod }}" {{ request('modulo') === $mod ? 'selected' : '' }}>
                                    {{ $mod }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Filtro Tipo de Acción -->
                    <div class="col-6 col-md-4 col-lg-2">
                        <label class="form-label text-muted fw-bold mb-1" style="font-size: 0.72rem; text-transform: uppercase;">Acción</label>
                        <select name="accion" class="form-select form-select-sm rounded-3" onchange="document.getElementById('filtroBitacoraForm').submit()">
                            <option value="">Todas las acciones</option>
                            @foreach($accionesList as $acc)
                                <option value="{{ $acc }}" {{ request('accion') === $acc ? 'selected' : '' }}>
                                    {{ $acc }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Filtro Usuario Responsable -->
                    <div class="col-6 col-md-4 col-lg-2">
                        <label class="form-label text-muted fw-bold mb-1" style="font-size: 0.72rem; text-transform: uppercase;">Usuario</label>
                        <select name="user_id" class="form-select form-select-sm rounded-3" onchange="document.getElementById('filtroBitacoraForm').submit()">
                            <option value="">Todos los usuarios</option>
                            <option value="sistema" {{ request('user_id') === 'sistema' ? 'selected' : '' }}>Sistema / Automático</option>
                            @foreach($usuariosList as $u)
                                <option value="{{ $u->id }}" {{ request('user_id') == $u->id ? 'selected' : '' }}>
                                    {{ $u->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Rango de Fechas: Desde -->
                    <div class="col-6 col-md-4 col-lg-2">
                        <label class="form-label text-muted fw-bold mb-1" style="font-size: 0.72rem; text-transform: uppercase;">Fecha Desde</label>
                        <input type="date" name="fecha_desde" class="form-control form-control-sm rounded-3" value="{{ request('fecha_desde') }}" onchange="document.getElementById('filtroBitacoraForm').submit()">
                    </div>

                    <!-- Rango de Fechas: Hasta -->
                    <div class="col-6 col-md-4 col-lg-2">
                        <label class="form-label text-muted fw-bold mb-1" style="font-size: 0.72rem; text-transform: uppercase;">Fecha Hasta</label>
                        <input type="date" name="fecha_hasta" class="form-control form-control-sm rounded-3" value="{{ request('fecha_hasta') }}" onchange="document.getElementById('filtroBitacoraForm').submit()">
                    </div>

                    <!-- Orden & Botón Limpiar -->
                    <div class="col-12 col-md-4 col-lg-2 d-flex align-items-end gap-2">
                        <div class="w-100">
                            <label class="form-label text-muted fw-bold mb-1" style="font-size: 0.72rem; text-transform: uppercase;">Orden</label>
                            <select name="orden" class="form-select form-select-sm rounded-3" onchange="document.getElementById('filtroBitacoraForm').submit()">
                                <option value="recientes" {{ request('orden', 'recientes') === 'recientes' ? 'selected' : '' }}>Más Recientes</option>
                                <option value="antiguos" {{ request('orden') === 'antiguos' ? 'selected' : '' }}>Más Antiguos</option>
                                <option value="modulo_asc" {{ request('modulo_asc') === 'modulo_asc' ? 'selected' : '' }}>Módulo (A-Z)</option>
                                <option value="accion_asc" {{ request('orden') === 'accion_asc' ? 'selected' : '' }}>Acción (A-Z)</option>
                            </select>
                        </div>
                        @if(request()->hasAny(['buscar', 'modulo', 'accion', 'user_id', 'fecha_desde', 'fecha_hasta', 'rango_rapido', 'orden', 'per_page']))
                        <a href="{{ route('bitacoras.index') }}" class="btn btn-sm btn-outline-danger rounded-3" title="Restablecer todos los filtros" style="padding: 5px 10px;">
                            <i class="fas fa-undo"></i>
                        </a>
                        @endif
                    </div>
                </div>

            </form>
        </div>
    </div>

    <!-- 4. TABLA DE REGISTROS DE AUDITORÍA -->
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden" style="background: #ffffff; border: 1px solid #e2e8f0 !important;">
        
        <!-- Header de la tabla con contador y registros por página -->
        <div class="card-header bg-white border-bottom py-3 px-4 d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-2">
            <div class="d-flex align-items-center gap-2">
                <i class="fas fa-list-ul text-primary"></i>
                <h5 class="mb-0 fw-bold text-dark" style="font-size: 0.95rem;">
                    Registros Encontrados
                </h5>
                <span class="badge bg-primary-subtle text-primary rounded-pill px-2 py-1 fw-bold" style="background: rgba(99, 102, 241, 0.12); color: #4f46e5; font-size: 0.75rem;">
                    {{ $bitacoras->total() }} eventos
                </span>
            </div>

            <!-- Selector de cantidad por página -->
            <div class="d-flex align-items-center gap-2">
                <span class="text-muted" style="font-size: 0.8rem;">Mostrar:</span>
                <select class="form-select form-select-sm rounded-3" style="width: 75px; font-size: 0.8rem;" onchange="location.href = this.value;">
                    @foreach([10, 15, 25, 50, 100] as $cantidad)
                        <option value="{{ request()->fullUrlWithQuery(['per_page' => $cantidad]) }}" {{ request('per_page', 15) == $cantidad ? 'selected' : '' }}>
                            {{ $cantidad }}
                        </option>
                    @endforeach
                </select>
                <span class="text-muted" style="font-size: 0.8rem;">por pág.</span>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" style="font-size: 0.86rem;" id="tablaBitacora">
                    <thead class="bg-light text-muted text-uppercase fw-bold" style="font-size: 0.72rem; letter-spacing: 0.05em; border-bottom: 2px solid #e2e8f0;">
                        <tr>
                            <th class="py-3 px-3 text-center" style="width: 60px;">#ID</th>
                            <th class="py-3 px-3" style="width: 170px;">Fecha y Hora</th>
                            <th class="py-3 px-3" style="width: 220px;">Usuario Responsable</th>
                            <th class="py-3 px-3" style="width: 150px;">Módulo</th>
                            <th class="py-3 px-3 text-center" style="width: 130px;">Acción</th>
                            <th class="py-3 px-3">Descripción / Detalle del Evento</th>
                            <th class="py-3 px-3 text-center" style="width: 120px;">Dirección IP</th>
                            <th class="py-3 px-3 text-center no-print" style="width: 80px;">Detalle</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($bitacoras as $b)
                            @php
                                $badge = $b->badge_info;
                                $modInfo = $b->modulo_info;
                            @endphp
                            <tr class="transition-colors hover:bg-slate-50">
                                <!-- ID -->
                                <td class="px-3 py-3 text-center text-muted fw-semibold" style="font-size: 0.8rem;">
                                    #{{ $b->id }}
                                </td>

                                <!-- Fecha y Hora -->
                                <td class="px-3 py-3 text-nowrap">
                                    <div class="fw-semibold text-dark" style="font-size: 0.84rem;">
                                        <i class="far fa-calendar-alt text-muted me-1"></i>
                                        {{ $b->created_at ? $b->created_at->format('d/m/Y') : '-' }}
                                    </div>
                                    <div class="text-muted d-flex align-items-center gap-1 mt-0.5" style="font-size: 0.75rem;" title="{{ $b->fecha_formateada }}">
                                        <i class="far fa-clock"></i>
                                        <span>{{ $b->created_at ? $b->created_at->format('H:i:s') : '' }}</span>
                                        <span class="text-secondary opacity-75">({{ $b->tiempo_relativo }})</span>
                                    </div>
                                </td>

                                <!-- Usuario Responsable -->
                                <td class="px-3 py-3">
                                    @if($b->usuario)
                                        <div class="d-flex align-items-center gap-2">
                                            <div style="width: 32px; height: 32px; border-radius: 50%; background: {{ $b->usuario->avatar_gradient }}; color: #ffffff; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.75rem; flex-shrink: 0; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                                                {{ $b->usuario->iniciales }}
                                            </div>
                                            <div style="min-width: 0;">
                                                <div class="fw-bold text-dark text-truncate" style="font-size: 0.85rem;" title="{{ $b->usuario->name }}">
                                                    {{ $b->usuario->name }}
                                                </div>
                                                <div class="text-muted text-truncate" style="font-size: 0.72rem;">
                                                    {{ $b->usuario->rol->nombre ?? 'Usuario Registrado' }}
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <div class="d-flex align-items-center gap-2">
                                            <div style="width: 32px; height: 32px; border-radius: 50%; background: #64748b; color: #ffffff; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.75rem; flex-shrink: 0;">
                                                <i class="fas fa-robot"></i>
                                            </div>
                                            <div>
                                                <div class="fw-bold text-secondary" style="font-size: 0.85rem;">Sistema</div>
                                                <div class="text-muted" style="font-size: 0.72rem;">Proceso Automático</div>
                                            </div>
                                        </div>
                                    @endif
                                </td>

                                <!-- Módulo -->
                                <td class="px-3 py-3 text-nowrap">
                                    <span class="badge bg-light text-dark border px-2.5 py-1.5 rounded-pill d-inline-flex align-items-center gap-1.5" style="font-size: 0.78rem;">
                                        <i class="{{ $modInfo['icon'] }}" style="color: {{ $modInfo['color'] }};"></i>
                                        <span>{{ $b->modulo }}</span>
                                    </span>
                                </td>

                                <!-- Acción -->
                                <td class="px-3 py-3 text-center text-nowrap">
                                    <span class="badge {{ $badge['badge_class'] }} px-2.5 py-1.5 rounded-pill d-inline-flex align-items-center gap-1" style="font-size: 0.75rem;">
                                        <i class="{{ $badge['icon'] }}"></i>
                                        <span>{{ $b->accion }}</span>
                                    </span>
                                </td>

                                <!-- Descripción -->
                                <td class="px-3 py-3">
                                    <div class="text-dark fw-medium text-break" style="font-size: 0.85rem; line-height: 1.4;">
                                        {{ $b->descripcion ?: 'Operación completada sin comentarios adicionales.' }}
                                    </div>
                                </td>

                                <!-- IP -->
                                <td class="px-3 py-3 text-center text-nowrap">
                                    <span class="badge bg-light text-secondary border px-2 py-1 font-monospace" style="font-size: 0.75rem;">
                                        {{ $b->ip ?: '127.0.0.1' }}
                                    </span>
                                </td>

                                <!-- Botón Detalle -->
                                <td class="px-3 py-3 text-center text-nowrap no-print">
                                    <button type="button" 
                                            class="btn btn-sm btn-outline-primary rounded-circle d-inline-flex align-items-center justify-content-center btn-ver-detalle" 
                                            style="width: 32px; height: 32px;"
                                            data-id="{{ $b->id }}"
                                            title="Ver ficha completa de auditoría">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-5">
                                    <div class="py-4">
                                        <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-light text-muted mb-3" style="width: 64px; height: 64px;">
                                            <i class="fas fa-history fa-2x"></i>
                                        </div>
                                        <h5 class="fw-bold text-dark mb-1">No se encontraron eventos en la bitácora</h5>
                                        <p class="text-muted mb-3" style="font-size: 0.88rem;">
                                            No hay registros que coincidan con los filtros de búsqueda aplicados.
                                        </p>
                                        @if(request()->hasAny(['buscar', 'modulo', 'accion', 'user_id', 'fecha_desde', 'fecha_hasta', 'rango_rapido']))
                                            <a href="{{ route('bitacoras.index') }}" class="btn btn-primary btn-sm rounded-pill px-4 fw-semibold" style="background: var(--primary-gradient); border: none;">
                                                <i class="fas fa-undo me-1"></i> Restablecer Filtros
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

        <!-- Paginación -->
        @if($bitacoras->hasPages())
        <div class="card-footer bg-white border-top py-3 px-4 d-flex flex-column flex-sm-row justify-content-between align-items-center gap-3">
            <div class="text-muted" style="font-size: 0.82rem;">
                Mostrando <strong>{{ $bitacoras->firstItem() ?? 0 }}</strong> a <strong>{{ $bitacoras->lastItem() ?? 0 }}</strong> de <strong>{{ $bitacoras->total() }}</strong> registros
            </div>
            <div>
                {{ $bitacoras->links('pagination::bootstrap-5') }}
            </div>
        </div>
        @endif
    </div>

</div>

<!-- ======================================================== -->
<!-- 🔍 MODAL: DETALLE DE AUDITORÍA (AJAX / INTERACTIVO) -->
<!-- ======================================================== -->
<div class="modal fade" id="detalleBitacoraModal" tabindex="-1" aria-labelledby="detalleBitacoraModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            
            <div class="modal-header bg-dark text-white py-3 px-4" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%) !important;">
                <div class="d-flex align-items-center gap-2">
                    <div style="width: 36px; height: 36px; border-radius: 10px; background: rgba(99, 102, 241, 0.2); color: #818cf8; display: flex; align-items: center; justify-content: center; font-size: 1.1rem;">
                        <i class="fas fa-history"></i>
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold mb-0" id="detalleBitacoraModalLabel">
                            Detalle de Auditoría <span id="modalRegistroId" class="text-warning"></span>
                        </h5>
                        <small class="text-slate-400" style="color: #94a3b8; font-size: 0.75rem;">Ficha técnica del evento registrado en bitácora</small>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body p-4" id="modalDetalleContenido">
                <!-- Estado de Carga -->
                <div id="modalLoading" class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Cargando...</span>
                    </div>
                    <p class="text-muted mt-2 mb-0" style="font-size: 0.85rem;">Consultando detalles de auditoría...</p>
                </div>

                <!-- Contenido Dinámico -->
                <div id="modalBodyData" style="display: none;">
                    
                    <!-- Fila Superior: Usuario y Datos Clave -->
                    <div class="row g-3 mb-4">
                        <!-- Tarjeta de Usuario -->
                        <div class="col-md-6">
                            <div class="p-3 rounded-4 h-100" style="background: #f8fafc; border: 1px solid #e2e8f0;">
                                <small class="text-muted text-uppercase fw-bold d-block mb-2" style="font-size: 0.7rem; letter-spacing: 0.05em;">Operador Responsable</small>
                                <div class="d-flex align-items-center gap-3">
                                    <div id="modalUserAvatar" style="width: 46px; height: 46px; border-radius: 12px; color: #fff; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 1.1rem; flex-shrink: 0; box-shadow: 0 4px 10px rgba(0,0,0,0.15);">
                                        US
                                    </div>
                                    <div style="min-width: 0;">
                                        <h6 class="fw-bold text-dark mb-0 text-truncate" id="modalUserName">Nombre Usuario</h6>
                                        <small class="text-muted d-block text-truncate" id="modalUserEmail">email@ejemplo.com</small>
                                        <span class="badge bg-secondary rounded-pill px-2 py-0.5 mt-1" id="modalUserRole" style="font-size: 0.7rem;">Rol</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Tarjeta de Metadatos de Red y Tiempo -->
                        <div class="col-md-6">
                            <div class="p-3 rounded-4 h-100" style="background: #f8fafc; border: 1px solid #e2e8f0;">
                                <small class="text-muted text-uppercase fw-bold d-block mb-2" style="font-size: 0.7rem; letter-spacing: 0.05em;">Metadatos de Registro</small>
                                
                                <div class="d-flex flex-column gap-2" style="font-size: 0.82rem;">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="text-muted"><i class="far fa-calendar-alt me-1 text-primary"></i> Fecha y Hora:</span>
                                        <span class="fw-semibold text-dark" id="modalFechaHora">-</span>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="text-muted"><i class="far fa-clock me-1 text-info"></i> Tiempo Relativo:</span>
                                        <span class="badge bg-light text-secondary border" id="modalTiempoRelativo">-</span>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="text-muted"><i class="fas fa-network-wired me-1 text-success"></i> Dirección IP:</span>
                                        <span class="font-monospace fw-bold text-dark" id="modalIpAddress">-</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Fila Media: Módulo y Acción -->
                    <div class="row g-3 mb-4">
                        <div class="col-6">
                            <div class="p-3 rounded-4" style="background: #ffffff; border: 1px solid #e2e8f0;">
                                <small class="text-muted text-uppercase fw-bold d-block mb-1" style="font-size: 0.7rem;">Módulo Afectado</small>
                                <div class="d-flex align-items-center gap-2 mt-1" id="modalModuloBox">
                                    <i id="modalModuloIcon" class="fas fa-layer-group fs-5 text-primary"></i>
                                    <span class="fw-bold text-dark fs-6" id="modalModuloName">Módulo</span>
                                </div>
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="p-3 rounded-4" style="background: #ffffff; border: 1px solid #e2e8f0;">
                                <small class="text-muted text-uppercase fw-bold d-block mb-1" style="font-size: 0.7rem;">Tipo de Operación</small>
                                <div class="mt-1" id="modalAccionBox">
                                    <span class="badge bg-primary px-3 py-1.5 rounded-pill fs-6 fw-bold" id="modalAccionName">Acción</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Fila Inferior: Descripción Detallada -->
                    <div class="mb-2">
                        <label class="text-muted text-uppercase fw-bold d-block mb-2" style="font-size: 0.72rem; letter-spacing: 0.05em;">
                            <i class="fas fa-file-alt text-primary me-1"></i> Descripción Completa del Evento
                        </label>
                        <div class="p-3 rounded-4 bg-light border text-dark fw-medium" id="modalDescripcionTexto" style="font-size: 0.9rem; line-height: 1.5; white-space: pre-wrap; font-family: inherit;">
                            Detalle de la acción realizada...
                        </div>
                    </div>

                </div>
            </div>

            <div class="modal-footer bg-light border-top py-2.5 px-4 d-flex justify-content-between">
                <a href="#" id="modalDirectLink" class="btn btn-outline-secondary btn-sm rounded-pill px-3 fw-semibold">
                    <i class="fas fa-external-link-alt me-1"></i> Ver Ficha Individual
                </a>
                <button type="button" class="btn btn-secondary btn-sm rounded-pill px-4 fw-semibold" data-bs-dismiss="modal">
                    Cerrar
                </button>
            </div>

        </div>
    </div>
</div>

<!-- ======================================================== -->
<!-- 🧹 MODAL: DEPURAR / LIMPIAR LOGS (SOLO ADMIN) -->
<!-- ======================================================== -->
@if(auth()->check() && auth()->user()->esAdmin())
<div class="modal fade" id="limpiarBitacoraModal" tabindex="-1" aria-labelledby="limpiarBitacoraModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            
            <form method="POST" action="{{ route('bitacoras.limpiar') }}" id="formDepurarBitacora">
                @csrf

                <div class="modal-header bg-danger text-white py-3 px-4" style="background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%) !important;">
                    <div class="d-flex align-items-center gap-2">
                        <div style="width: 36px; height: 36px; border-radius: 10px; background: rgba(255, 255, 255, 0.2); color: #fff; display: flex; align-items: center; justify-content: center; font-size: 1.1rem;">
                            <i class="fas fa-broom"></i>
                        </div>
                        <div>
                            <h5 class="modal-title fw-bold mb-0" id="limpiarBitacoraModalLabel">
                                Depuración de Registros Antiguos
                            </h5>
                            <small class="text-white-50" style="font-size: 0.75rem;">Mantenimiento y optimización de base de datos</small>
                        </div>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body p-4">
                    
                    <div class="alert alert-warning border-0 rounded-4 d-flex align-items-start gap-3 p-3 mb-3" style="background: #fef3c7; color: #92400e;">
                        <i class="fas fa-exclamation-triangle fs-4 text-warning mt-1 flex-shrink-0"></i>
                        <div style="font-size: 0.82rem;">
                            <strong>¡Atención!</strong> La eliminación de registros antiguos de auditoría es una operación permanente. Esta acción quedará automáticamente registrada en la bitácora para fines de control.
                        </div>
                    </div>

                    <label class="form-label text-dark fw-bold mb-2" style="font-size: 0.88rem;">
                        Seleccione el criterio de depuración:
                    </label>

                    <div class="d-flex flex-column gap-2 mb-3">
                        <div class="form-check p-2.5 rounded-3 border bg-light">
                            <input class="form-check-input ms-1 me-2" type="radio" name="periodo" id="p30" value="30">
                            <label class="form-check-label fw-semibold text-dark" for="p30" style="font-size: 0.85rem;">
                                Eliminar registros con más de 30 días de antigüedad
                            </label>
                        </div>

                        <div class="form-check p-2.5 rounded-3 border bg-light">
                            <input class="form-check-input ms-1 me-2" type="radio" name="periodo" id="p60" value="60">
                            <label class="form-check-label fw-semibold text-dark" for="p60" style="font-size: 0.85rem;">
                                Eliminar registros con más de 60 días de antigüedad
                            </label>
                        </div>

                        <div class="form-check p-2.5 rounded-3 border bg-light">
                            <input class="form-check-input ms-1 me-2" type="radio" name="periodo" id="p90" value="90" checked>
                            <label class="form-check-label fw-semibold text-dark" for="p90" style="font-size: 0.85rem;">
                                Eliminar registros con más de 90 días de antigüedad <span class="badge bg-primary rounded-pill ms-1" style="font-size: 0.65rem;">Recomendado</span>
                            </label>
                        </div>

                        <div class="form-check p-2.5 rounded-3 border bg-light">
                            <input class="form-check-input ms-1 me-2" type="radio" name="periodo" id="p180" value="180">
                            <label class="form-check-label fw-semibold text-dark" for="p180" style="font-size: 0.85rem;">
                                Eliminar registros con más de 180 días (6 meses)
                            </label>
                        </div>

                        <div class="form-check p-2.5 rounded-3 border bg-light">
                            <input class="form-check-input ms-1 me-2" type="radio" name="periodo" id="p365" value="365">
                            <label class="form-check-label fw-semibold text-dark" for="p365" style="font-size: 0.85rem;">
                                Eliminar registros con más de 1 año (365 días)
                            </label>
                        </div>

                        <div class="form-check p-2.5 rounded-3 border bg-light">
                            <input class="form-check-input ms-1 me-2" type="radio" name="periodo" id="ptodos" value="todos_excepto_100">
                            <label class="form-check-label fw-semibold text-danger" for="ptodos" style="font-size: 0.85rem;">
                                Mantener únicamente los últimos 100 registros recientes (Depuración máxima)
                            </label>
                        </div>
                    </div>

                </div>

                <div class="modal-footer bg-light border-top py-2.5 px-4 d-flex justify-content-between">
                    <button type="button" class="btn btn-secondary btn-sm rounded-pill px-3 fw-semibold" data-bs-dismiss="modal">
                        Cancelar
                    </button>
                    <button type="button" class="btn btn-danger btn-sm rounded-pill px-4 fw-bold shadow-sm" id="btnConfirmarDepuracion">
                        <i class="fas fa-trash-alt me-1"></i> Ejecutar Depuración
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>
@endif

<!-- ESTILOS DE IMPRESIÓN Y MICROANIMACIONES -->
<style>
@media print {
    .main-sidebar, .main-header, .navbar, .no-print, .card-header, .card-footer, form, .btn, .live-clock, .hero-banner {
        display: none !important;
    }
    .content-wrapper, body {
        background: #ffffff !important;
        margin: 0 !important;
        padding: 0 !important;
    }
    .card {
        box-shadow: none !important;
        border: none !important;
    }
    #tablaBitacora {
        width: 100% !important;
        font-size: 10pt !important;
    }
}
</style>

<!-- SCRIPTS PARA MODAL AJAX Y CONFIRMACIONES -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    const detalleModal = new bootstrap.Modal(document.getElementById('detalleBitacoraModal'));
    const modalLoading = document.getElementById('modalLoading');
    const modalBodyData = document.getElementById('modalBodyData');

    // Botones de Ver Detalle
    document.querySelectorAll('.btn-ver-detalle').forEach(btn => {
        btn.addEventListener('click', function () {
            const id = this.getAttribute('data-id');
            
            // Abrir modal y mostrar estado de carga
            modalLoading.style.display = 'block';
            modalBodyData.style.display = 'none';
            document.getElementById('modalRegistroId').textContent = '#' + id;
            document.getElementById('modalDirectLink').href = '/bitacoras/' + id;
            detalleModal.show();

            // Petición AJAX al controlador
            fetch(`/bitacoras/${id}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success && data.bitacora) {
                    const b = data.bitacora;

                    // Usuario
                    document.getElementById('modalUserName').textContent = b.usuario ? b.usuario.name : 'Sistema / Automático';
                    document.getElementById('modalUserEmail').textContent = b.usuario ? b.usuario.email : 'Proceso interno';
                    document.getElementById('modalUserRole').textContent = b.usuario ? b.usuario.rol : 'Sistema';
                    
                    const avatar = document.getElementById('modalUserAvatar');
                    if (b.usuario) {
                        avatar.textContent = b.usuario.iniciales;
                        avatar.style.background = b.usuario.avatar_gradient;
                    } else {
                        avatar.innerHTML = '<i class="fas fa-robot"></i>';
                        avatar.style.background = '#64748b';
                    }

                    // Metadatos
                    document.getElementById('modalFechaHora').textContent = b.created_at;
                    document.getElementById('modalTiempoRelativo').textContent = b.tiempo_relativo;
                    document.getElementById('modalIpAddress').textContent = b.ip;

                    // Módulo y Acción
                    document.getElementById('modalModuloName').textContent = b.modulo;
                    const modIcon = document.getElementById('modalModuloIcon');
                    modIcon.className = b.modulo_info.icon + ' fs-5';
                    modIcon.style.color = b.modulo_info.color;

                    const accionBadge = document.getElementById('modalAccionName');
                    accionBadge.textContent = b.accion;
                    accionBadge.className = 'badge px-3 py-1.5 rounded-pill fs-6 fw-bold ' + b.badge.badge_class;

                    // Descripción
                    document.getElementById('modalDescripcionTexto').textContent = b.descripcion;

                    // Mostrar datos
                    modalLoading.style.display = 'none';
                    modalBodyData.style.display = 'block';
                }
            })
            .catch(err => {
                modalLoading.innerHTML = '<div class="text-danger py-4"><i class="fas fa-exclamation-circle fa-2x mb-2 d-block"></i> No fue posible cargar la información del registro.</div>';
            });
        });
    });

    // Confirmación para Depuración de Bitácora
    const btnDepurar = document.getElementById('btnConfirmarDepuracion');
    if (btnDepurar) {
        btnDepurar.addEventListener('click', function () {
            Swal.fire({
                title: '¿Confirmar depuración?',
                text: "Los registros seleccionados se eliminarán de forma irreversible.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Sí, ejecutar depuración',
                cancelButtonText: 'Cancelar',
                customClass: {
                    popup: 'rounded-4'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('formDepurarBitacora').submit();
                }
            });
        });
    }
});
</script>
@endsection