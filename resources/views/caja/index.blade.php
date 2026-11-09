@extends('dashboard')

@section('content')

<style>
    .caja-container {
        padding: 1.5rem 1rem;
    }

    .kpi-caja-card {
        background: #ffffff;
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
        padding: 1.25rem;
        transition: all 0.25s ease;
        position: relative;
        overflow: hidden;
    }

    .kpi-caja-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 25px -5px rgba(0, 0, 0, 0.08);
    }

    .caja-status-banner {
        border-radius: 20px;
        padding: 2rem;
        color: #ffffff;
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
    }

    .caja-banner-open {
        background: linear-gradient(135deg, #065f46 0%, #059669 50%, #10b981 100%);
        box-shadow: 0 15px 30px -10px rgba(16, 185, 129, 0.4);
    }

    .caja-banner-closed {
        background: linear-gradient(135deg, #1e293b 0%, #334155 50%, #475569 100%);
        box-shadow: 0 15px 30px -10px rgba(30, 41, 59, 0.3);
    }

    .denomination-row {
        background: #f8fafc;
        border-radius: 12px;
        padding: 0.75rem 1rem;
        margin-bottom: 0.6rem;
        border: 1px solid #e2e8f0;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
    }

    .denomination-tag {
        font-weight: 800;
        font-size: 0.95rem;
        min-width: 110px;
        color: #1e293b;
    }

    .denomination-input {
        width: 100px;
        text-align: center;
        font-weight: 700;
    }

    .denomination-subtotal {
        font-weight: 800;
        min-width: 110px;
        text-align: right;
        color: #059669;
        font-size: 0.95rem;
    }

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
</style>

<div class="caja-container">

    <!-- 1. ENCABEZADO -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <div class="kpi-caja-card p-2 rounded-3 text-white bg-dark d-inline-flex" style="width: 38px; height: 38px; font-size: 1.1rem; align-items: center; justify-content: center;">
                    <i class="fas fa-cash-register"></i>
                </div>
                <h2 class="h4 fw-bold mb-0 text-dark">Módulo de Arqueo y Control de Caja</h2>
                @if($cajaAbierta)
                    <span class="badge bg-success text-white px-2 py-1 ms-2">
                        <i class="fas fa-circle text-white me-1 animate-pulse" style="font-size: 0.6rem;"></i> Turno Activo
                    </span>
                @else
                    <span class="badge bg-secondary text-white px-2 py-1 ms-2">
                        <i class="fas fa-lock me-1"></i> Caja Cerrada
                    </span>
                @endif
            </div>
            <p class="text-muted small mb-0">
                Apertura diaria de turno, registro de ingresos extras, control de retiros/gastos de caja chica, y arqueo con desglose de billetes y monedas.
            </p>
        </div>

        <div class="d-flex flex-wrap align-items-center gap-2">
            @if(!$cajaAbierta)
                <button type="button" class="btn text-white rounded-3 px-4 py-2 fw-bold d-inline-flex align-items-center gap-2 shadow-sm" style="background: linear-gradient(135deg, #059669 0%, #10b981 100%);" data-bs-toggle="modal" data-bs-target="#modalAperturarCaja">
                    <i class="fas fa-door-open"></i>
                    <span>Aperturar Turno de Caja</span>
                </button>
            @else
                <!-- Registrar Ingreso Extra -->
                <button type="button" class="btn btn-outline-success rounded-3 px-3 py-2 fw-semibold d-inline-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#modalMovimientoIngreso">
                    <i class="fas fa-plus-circle"></i>
                    <span>Ingreso de Efectivo (+)</span>
                </button>

                <!-- Registrar Egreso de Caja Menor -->
                <button type="button" class="btn btn-outline-danger rounded-3 px-3 py-2 fw-semibold d-inline-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#modalMovimientoEgreso">
                    <i class="fas fa-minus-circle"></i>
                    <span>Retiro / Gasto Menor (-)</span>
                </button>

                <!-- Realizar Arqueo y Cierre -->
                <button type="button" class="btn text-white rounded-3 px-3 py-2 fw-bold d-inline-flex align-items-center gap-2 shadow-sm" style="background: linear-gradient(135deg, #b91c1c 0%, #dc2626 50%, #ef4444 100%);" data-bs-toggle="modal" data-bs-target="#modalArqueoCierre">
                    <i class="fas fa-lock"></i>
                    <span>Realizar Arqueo y Cerrar Caja</span>
                </button>
            @endif
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

    <!-- 2. BANNER DE ESTADO DE LA CAJA ACTIVA -->
    @if($cajaAbierta)
    <div class="caja-status-banner caja-banner-open">
        <div class="row align-items-center g-3">
            <div class="col-lg-7">
                <span class="badge bg-white text-success fw-bold px-3 py-1 rounded-pill mb-2">
                    <i class="fas fa-circle text-success me-1"></i> SESIÓN ABIERTA EN CURSO
                </span>
                <h3 class="fw-bold mb-1">{{ $cajaAbierta->caja_nombre }}</h3>
                <p class="text-white opacity-90 small mb-2">
                    <i class="fas fa-user-tie me-1"></i> Cajero responsable: <strong>{{ $cajaAbierta->usuarioApertura->name }}</strong>
                    <span class="mx-2">|</span>
                    <i class="fas fa-clock me-1"></i> Apertura: <strong>{{ $cajaAbierta->fecha_apertura->format('d/m/Y H:i') }}</strong>
                    ({{ $cajaAbierta->fecha_apertura->diffForHumans() }})
                </p>
                @if($cajaAbierta->observaciones_apertura)
                    <p class="small text-white opacity-75 fst-italic mb-0">
                        Nota de apertura: "{{ $cajaAbierta->observaciones_apertura }}"
                    </p>
                @endif
            </div>

            <div class="col-lg-5 text-lg-end">
                <span class="small text-white opacity-75 text-uppercase fw-bold letter-spacing d-block mb-1">
                    Saldo Esperado en Caja (En Vivo)
                </span>
                <div class="display-6 fw-bold text-white mb-2">
                    Bs. {{ number_format($saldoEsperadoEnVivo, 2) }}
                </div>
                <div class="d-flex justify-content-lg-end gap-2 flex-wrap mb-3">
                    <span class="badge bg-white bg-opacity-25 text-white px-2 py-1 small">
                        Inicial: Bs. {{ number_format($cajaAbierta->monto_inicial, 2) }}
                    </span>
                    <span class="badge bg-white bg-opacity-25 text-white px-2 py-1 small">
                        + Ventas: Bs. {{ number_format($ventasTurnoTotal, 2) }}
                    </span>
                    <span class="badge bg-white bg-opacity-25 text-white px-2 py-1 small">
                        - Gastos: Bs. {{ number_format($egresosGastosTurno, 2) }}
                    </span>
                </div>

                <!-- BOTÓN PRINCIPAL DE CIERRE DENTRO DEL BANNER -->
                <div class="d-flex justify-content-lg-end gap-2 flex-wrap">
                    <button type="button" class="btn btn-warning text-dark fw-bold rounded-pill px-4 py-2 shadow d-inline-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#modalArqueoCierre" style="font-size: 0.95rem;">
                        <i class="fas fa-lock text-danger"></i>
                        <span>Realizar Arqueo y Cerrar Caja</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- 3. CARDS DE DETALLE DEL TURNO ACTIVO -->
    <div class="row g-3 mb-4">
        <!-- Saldo Base / Inicial -->
        <div class="col-xl-3 col-sm-6">
            <div class="kpi-caja-card">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="text-muted small fw-bold text-uppercase">Fondo Inicial de Caja</div>
                        <div class="h4 fw-bold text-dark mb-0">Bs. {{ number_format($cajaAbierta->monto_inicial, 2) }}</div>
                        <small class="text-muted">Cambio base al abrir</small>
                    </div>
                    <div style="width: 44px; height: 44px; border-radius: 12px; background: #eff6ff; color: #3b82f6; display: flex; align-items: center; justify-content: center; font-size: 1.25rem;">
                        <i class="fas fa-coins"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Ventas en Efectivo del Turno -->
        <div class="col-xl-3 col-sm-6">
            <div class="kpi-caja-card">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="text-muted small fw-bold text-uppercase">Ventas del Turno (+)</div>
                        <div class="h4 fw-bold text-success mb-0">+Bs. {{ number_format($ventasTurnoTotal, 2) }}</div>
                        <small class="text-muted">{{ $ventasTurno->count() }} ventas realizadas</small>
                    </div>
                    <div style="width: 44px; height: 44px; border-radius: 12px; background: #ecfdf5; color: #10b981; display: flex; align-items: center; justify-content: center; font-size: 1.25rem;">
                        <i class="fas fa-shopping-bag"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Ingresos Extras de Dinero -->
        <div class="col-xl-3 col-sm-6">
            <div class="kpi-caja-card">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="text-muted small fw-bold text-uppercase">Ingresos Extras (+)</div>
                        <div class="h4 fw-bold text-primary mb-0">+Bs. {{ number_format($ingresosExtraTurno, 2) }}</div>
                        <small class="text-muted">Inyecciones o cambio extra</small>
                    </div>
                    <div style="width: 44px; height: 44px; border-radius: 12px; background: #f5f3ff; color: #8b5cf6; display: flex; align-items: center; justify-content: center; font-size: 1.25rem;">
                        <i class="fas fa-arrow-down"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Egresos / Gastos Menores -->
        <div class="col-xl-3 col-sm-6">
            <div class="kpi-caja-card">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="text-muted small fw-bold text-uppercase">Retiros / Gastos (-)</div>
                        <div class="h4 fw-bold text-danger mb-0">-Bs. {{ number_format($egresosGastosTurno, 2) }}</div>
                        <small class="text-muted">Caja menor / envíos / varios</small>
                    </div>
                    <div style="width: 44px; height: 44px; border-radius: 12px; background: #fef2f2; color: #ef4444; display: flex; align-items: center; justify-content: center; font-size: 1.25rem;">
                        <i class="fas fa-arrow-up"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- BARRA DESTACADA DE ACCIÓN Y CIERRE DEL TURNO ACTIVO -->
    <div class="card border-0 shadow-sm rounded-4 mb-4 p-3 bg-white" style="border-left: 5px solid #dc2626 !important;">
        <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
            <div class="d-flex align-items-center gap-3">
                <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 48px; height: 48px; background: #fee2e2; color: #dc2626; font-size: 1.3rem;">
                    <i class="fas fa-vault"></i>
                </div>
                <div>
                    <div class="fw-bold text-dark fs-6">Turno Activo: {{ $cajaAbierta->caja_nombre }}</div>
                    <div class="text-muted small">
                        Saldo esperado en caja: <strong class="text-success">Bs. {{ number_format($saldoEsperadoEnVivo, 2) }}</strong>. Realice el arqueo físico y cierre de turno al finalizar la jornada.
                    </div>
                </div>
            </div>
            <div class="d-flex align-items-center gap-2 flex-wrap">
                <button type="button" class="btn btn-outline-success btn-sm rounded-3 px-3 py-2 fw-semibold d-inline-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#modalMovimientoIngreso">
                    <i class="fas fa-plus-circle"></i> Ingreso (+)
                </button>
                <button type="button" class="btn btn-outline-danger btn-sm rounded-3 px-3 py-2 fw-semibold d-inline-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#modalMovimientoEgreso">
                    <i class="fas fa-minus-circle"></i> Retiro (-)
                </button>
                <button type="button" class="btn text-white rounded-3 px-4 py-2 fw-bold d-inline-flex align-items-center gap-2 shadow-sm" style="background: linear-gradient(135deg, #b91c1c 0%, #dc2626 50%, #ef4444 100%);" data-bs-toggle="modal" data-bs-target="#modalArqueoCierre">
                    <i class="fas fa-lock"></i>
                    <span>Realizar Arqueo y Cerrar Caja</span>
                </button>
            </div>
        </div>
    </div>

    <!-- MOVIMIENTOS DEL TURNO ACTUAL -->
    @if($cajaAbierta->movimientos->count() > 0)
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-header bg-white border-0 py-3 px-4 d-flex justify-content-between align-items-center">
            <h6 class="fw-bold text-dark mb-0">
                <i class="fas fa-receipt text-primary me-2"></i> Movimientos Extraordinarios de este Turno
            </h6>
            <span class="badge bg-light text-secondary border px-2 py-1 small">
                {{ $cajaAbierta->movimientos->count() }} registros
            </span>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 px-4">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">Hora</th>
                        <th>Tipo</th>
                        <th>Concepto / Motivo</th>
                        <th>Comprobante</th>
                        <th>Responsable</th>
                        <th class="text-end pe-4">Monto</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($cajaAbierta->movimientos as $mov)
                    <tr>
                        <td class="ps-4 text-muted small">{{ $mov->created_at->format('H:i') }}</td>
                        <td>
                            @if($mov->tipo === 'ingreso')
                                <span class="badge bg-success-subtle text-success border border-success-subtle">
                                    <i class="fas fa-arrow-down me-1"></i> Ingreso Extra
                                </span>
                            @else
                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle">
                                    <i class="fas fa-arrow-up me-1"></i> Retiro / Gasto
                                </span>
                            @endif
                        </td>
                        <td class="fw-semibold text-dark">{{ $mov->concepto }}</td>
                        <td class="text-muted small">{{ $mov->comprobante ?: 'N/A' }}</td>
                        <td class="small">{{ $mov->usuario->name ?? 'Usuario' }}</td>
                        <td class="text-end pe-4 fw-bold {{ $mov->tipo === 'ingreso' ? 'text-success' : 'text-danger' }}">
                            {{ $mov->tipo === 'ingreso' ? '+' : '-' }}Bs. {{ number_format($mov->monto, 2) }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    @else
    <!-- BANNER DE CAJA CERRADA -->
    <div class="caja-status-banner caja-banner-closed">
        <div class="row align-items-center g-3">
            <div class="col-lg-8">
                <span class="badge bg-white text-dark fw-bold px-3 py-1 rounded-pill mb-2">
                    <i class="fas fa-lock me-1"></i> CAJA CERRADA
                </span>
                <h3 class="fw-bold mb-1">No hay turno de caja abierto en este momento</h3>
                <p class="text-white opacity-90 small mb-0">
                    Aperture un nuevo turno de caja indicando el saldo base en efectivo disponible para comenzar las operaciones del día y registrar transacciones con arqueo auditado.
                </p>
            </div>
            <div class="col-lg-4 text-lg-end">
                <button type="button" class="btn btn-light btn-lg rounded-pill px-4 fw-bold shadow" data-bs-toggle="modal" data-bs-target="#modalAperturarCaja">
                    <i class="fas fa-door-open text-success me-2"></i> Aperturar Caja Ahora
                </button>
            </div>
        </div>
    </div>
    @endif

    <!-- 4. HISTORIAL DE ARQUEOS Y CIERRES ANTERIORES -->
    <div class="table-container-card">
        <div class="p-3 border-bottom d-flex justify-content-between align-items-center bg-light">
            <div class="fw-bold text-dark d-flex align-items-center gap-2">
                <i class="fas fa-history text-secondary"></i>
                <span>Historial de Arqueos y Cierres de Caja</span>
            </div>
            <span class="badge bg-light text-secondary border px-2 py-1" style="font-size: 0.78rem;">
                {{ $historialCajas->total() }} turnos cerrados
            </span>
        </div>

        <!-- FILTROS DE HISTORIAL -->
        <div class="p-3 border-bottom bg-white">
            <form method="GET" action="{{ route('caja.index') }}" class="row g-2 align-items-end">
                <div class="col-md-3">
                    <label class="form-label small text-muted mb-1">Búsqueda rápida</label>
                    <input type="text" name="buscar" class="form-control form-control-sm" placeholder="Caja, cajero..." value="{{ request('buscar') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label small text-muted mb-1">Resultado de Cuadre</label>
                    <select name="resultado" class="form-select form-select-sm">
                        <option value="">Todos los resultados</option>
                        <option value="cuadrada" {{ request('resultado') === 'cuadrada' ? 'selected' : '' }}>✅ Cajas Cuadradas (Bs. 0.00)</option>
                        <option value="sobrante" {{ request('resultado') === 'sobrante' ? 'selected' : '' }}>🔵 Con Sobrante (+)</option>
                        <option value="faltante" {{ request('resultado') === 'faltante' ? 'selected' : '' }}>🚨 Con Faltante (-)</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label small text-muted mb-1">Desde</label>
                    <input type="date" name="fecha_desde" class="form-control form-control-sm" value="{{ request('fecha_desde') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label small text-muted mb-1">Hasta</label>
                    <input type="date" name="fecha_hasta" class="form-control form-control-sm" value="{{ request('fecha_hasta') }}">
                </div>
                <div class="col-md-2">
                    <div class="d-flex gap-1">
                        <button type="submit" class="btn btn-sm btn-primary w-100 fw-semibold">
                            <i class="fas fa-filter me-1"></i> Filtrar
                        </button>
                        @if(request()->anyFilled(['buscar', 'resultado', 'fecha_desde', 'fecha_hasta']))
                        <a href="{{ route('caja.index') }}" class="btn btn-sm btn-light border" title="Limpiar">
                            <i class="fas fa-times"></i>
                        </a>
                        @endif
                    </div>
                </div>
            </form>
        </div>

        <!-- TABLA HISTORIAL -->
        <div class="table-responsive">
            <table class="table table-custom table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th>N° Arqueo</th>
                        <th>Caja</th>
                        <th>Cajero(s)</th>
                        <th>Apertura / Cierre</th>
                        <th class="text-end">Monto Inicial</th>
                        <th class="text-end">Ventas Efectivo</th>
                        <th class="text-end">Monto Esperado</th>
                        <th class="text-end">Real Contado</th>
                        <th class="text-center">Diferencia</th>
                        <th class="text-center">Comprobante</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($historialCajas as $sesion)
                    <tr>
                        <td>
                            <strong class="text-dark font-monospace">#{{ str_pad($sesion->id, 5, '0', STR_PAD_LEFT) }}</strong>
                        </td>
                        <td>
                            <div class="fw-bold text-dark">{{ $sesion->caja_nombre }}</div>
                        </td>
                        <td>
                            <div class="small fw-semibold text-dark">
                                <i class="fas fa-user-circle text-muted me-1"></i> {{ $sesion->usuarioApertura->name ?? 'N/A' }}
                            </div>
                            @if($sesion->usuarioCierre && $sesion->usuarioCierre->id !== $sesion->user_id)
                                <div class="text-muted" style="font-size: 0.72rem;">
                                    Cerró: {{ $sesion->usuarioCierre->name }}
                                </div>
                            @endif
                        </td>
                        <td>
                            <div class="small text-dark">{{ $sesion->fecha_apertura->format('d/m/Y H:i') }}</div>
                            <div class="small text-muted" style="font-size: 0.72rem;">
                                Fin: {{ $sesion->fecha_cierre ? $sesion->fecha_cierre->format('d/m/Y H:i') : '--' }}
                            </div>
                        </td>
                        <td class="text-end text-muted">
                            Bs. {{ number_format($sesion->monto_inicial, 2) }}
                        </td>
                        <td class="text-end fw-bold text-success">
                            Bs. {{ number_format($sesion->total_ventas_efectivo, 2) }}
                        </td>
                        <td class="text-end fw-bold text-secondary">
                            Bs. {{ number_format($sesion->monto_esperado, 2) }}
                        </td>
                        <td class="text-end fw-bold text-dark">
                            Bs. {{ number_format($sesion->monto_real, 2) }}
                        </td>
                        <td class="text-center">
                            @php $res = $sesion->resultado_arqueo; @endphp
                            <span class="badge {{ $res['badge'] }} px-2 py-1">
                                <i class="{{ $res['icon'] }} me-1"></i>
                                {{ $res['label'] }}
                            </span>
                        </td>
                        <td class="text-center">
                            <a href="{{ route('caja.show', $sesion->id) }}" class="btn btn-sm btn-outline-dark rounded-pill px-3" title="Ver Comprobante de Arqueo">
                                <i class="fas fa-file-invoice me-1"></i> Ver Detalle
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="text-center py-5">
                            <i class="fas fa-vault fs-1 text-muted opacity-50 mb-3 d-block"></i>
                            <h6 class="text-secondary fw-semibold">No hay arqueos registrados en el historial</h6>
                            <p class="text-muted small mb-0">Cuando realices el primer cierre de turno, aparecerá aquí con su comprobante auditado.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($historialCajas->hasPages())
        <div class="p-3 border-top d-flex justify-content-end bg-light">
            {{ $historialCajas->links() }}
        </div>
        @endif
    </div>

</div>

<!-- MODAL 1: APERTURAR CAJA -->
<div class="modal fade" id="modalAperturarCaja" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form action="{{ route('caja.aperturar') }}" method="POST" class="modal-content border-0 shadow-lg rounded-4">
            @csrf
            <div class="modal-header border-0 bg-success text-white rounded-top-4 px-4 py-3">
                <div class="d-flex align-items-center gap-2">
                    <i class="fas fa-door-open fs-5"></i>
                    <h5 class="modal-title fw-bold">Aperturar Turno de Caja</h5>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="alert alert-success-subtle text-success-emphasis border border-success-subtle rounded-3 small p-3 mb-3">
                    <i class="fas fa-info-circle me-1"></i>
                    <strong>Inicio de Turno:</strong> Ingrese el monto de efectivo base disponible en gaveta para dar inicio a la jornada operativa y registrar transacciones.
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold small text-dark">Nombre o Punto de Caja <span class="text-danger">*</span></label>
                    <input type="text" name="caja_nombre" class="form-control" value="Caja Principal" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold small text-dark">Monto Inicial en Efectivo (Bs.) <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text bg-light fw-bold">Bs.</span>
                        <input type="number" step="0.50" min="0" name="monto_inicial" class="form-control form-control-lg fw-bold text-success" placeholder="0.00" value="0.00" required>
                    </div>
                    <small class="text-muted">Dinero físico disponible para cambio al inicio de jornada.</small>
                </div>

                <div class="mb-0">
                    <label class="form-label fw-bold small text-dark">Observaciones de Apertura</label>
                    <textarea name="observaciones_apertura" class="form-control" rows="2" placeholder="Notas sobre el estado de la caja o entrega de turno..."></textarea>
                </div>
            </div>
            <div class="modal-footer border-0 bg-light rounded-bottom-4 px-4 py-3">
                <button type="button" class="btn btn-secondary rounded-3 px-3" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-success rounded-3 px-4 fw-bold">
                    <i class="fas fa-check-circle me-1"></i> Abrir Turno de Caja
                </button>
            </div>
        </form>
    </div>
</div>

@if($cajaAbierta)
<!-- MODAL 2: INGRESO MANUAL DE EFECTIVO -->
<!-- MODAL 2: INGRESO MANUAL DE EFECTIVO -->
<div class="modal fade" id="modalMovimientoIngreso" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form action="{{ route('caja.movimiento.store') }}" method="POST" class="modal-content border-0 shadow-lg rounded-4">
            @csrf
            <input type="hidden" name="caja_sesion_id" value="{{ $cajaAbierta->id }}">
            <input type="hidden" name="tipo" value="ingreso">

            <div class="modal-header border-0 bg-success text-white rounded-top-4 px-4 py-3">
                <div class="d-flex align-items-center gap-2">
                    <i class="fas fa-arrow-down fs-5"></i>
                    <h5 class="modal-title fw-bold">Ingreso Extraordinario de Efectivo (+)</h5>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body p-4">
                <div class="mb-3">
                    <label class="form-label fw-bold small text-dark">Concepto / Motivo <span class="text-danger">*</span></label>
                    <input type="text" name="concepto" class="form-control" placeholder="Ej: Inyección de cambio por falta de monedas, cobro extra..." required>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label fw-bold small text-dark">Monto (Bs.) <span class="text-danger">*</span></label>
                        <input type="number" step="0.50" min="0.5" name="monto" class="form-control fw-bold text-success" placeholder="0.00" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold small text-dark">N° Comprobante / Recibo</label>
                        <input type="text" name="comprobante" class="form-control" placeholder="Opcional">
                    </div>
                </div>

                <div class="mb-0">
                    <label class="form-label fw-bold small text-dark">Observación</label>
                    <textarea name="observacion" class="form-control" rows="2" placeholder="Detalles adicionales..."></textarea>
                </div>
            </div>
            <div class="modal-footer border-0 bg-light rounded-bottom-4 px-4 py-3">
                <button type="button" class="btn btn-secondary rounded-3 px-3" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-success rounded-3 px-4 fw-bold">
                    <i class="fas fa-save me-1"></i> Guardar Ingreso
                </button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL 3: RETIRO / GASTO MENOR DE CAJA -->
<div class="modal fade" id="modalMovimientoEgreso" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form action="{{ route('caja.movimiento.store') }}" method="POST" class="modal-content border-0 shadow-lg rounded-4">
            @csrf
            <input type="hidden" name="caja_sesion_id" value="{{ $cajaAbierta->id }}">
            <input type="hidden" name="tipo" value="egreso">

            <div class="modal-header border-0 bg-danger text-white rounded-top-4 px-4 py-3">
                <div class="d-flex align-items-center gap-2">
                    <i class="fas fa-arrow-up fs-5"></i>
                    <h5 class="modal-title fw-bold">Retiro de Efectivo / Gasto de Caja (-)</h5>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body p-4">
                <div class="mb-3">
                    <label class="form-label fw-bold small text-dark">Concepto / Motivo <span class="text-danger">*</span></label>
                    <input type="text" name="concepto" class="form-control" placeholder="Ej: Pago de flete / delivery, compra de suministros de limpieza..." required>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label fw-bold small text-dark">Monto a Retirar (Bs.) <span class="text-danger">*</span></label>
                        <input type="number" step="0.50" min="0.5" name="monto" class="form-control fw-bold text-danger" placeholder="0.00" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold small text-dark">N° Factura / Recibo de Gasto</label>
                        <input type="text" name="comprobante" class="form-control" placeholder="Opcional">
                    </div>
                </div>

                <div class="mb-0">
                    <label class="form-label fw-bold small text-dark">Observación</label>
                    <textarea name="observacion" class="form-control" rows="2" placeholder="Justificación del gasto..."></textarea>
                </div>
            </div>
            <div class="modal-footer border-0 bg-light rounded-bottom-4 px-4 py-3">
                <button type="button" class="btn btn-secondary rounded-3 px-3" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-danger rounded-3 px-4 fw-bold">
                    <i class="fas fa-save me-1"></i> Guardar Egreso
                </button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL 4: ARQUEO INTERACTIVO Y CIERRE DE CAJA -->
<div class="modal fade" id="modalArqueoCierre" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <form action="{{ route('caja.cerrar', $cajaAbierta->id) }}" method="POST" class="modal-content border-0 shadow-lg rounded-4" style="max-height: 90vh; display: flex; flex-direction: column;">
            @csrf
            <div class="modal-header border-0 bg-dark text-white rounded-top-4 px-4 py-3 flex-shrink-0">
                <div class="d-flex align-items-center gap-2">
                    <i class="fas fa-vault text-warning fs-5"></i>
                    <h5 class="modal-title fw-bold">Arqueo Físico y Cierre de Caja</h5>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4" style="overflow-y: auto; max-height: calc(90vh - 140px); -webkit-overflow-scrolling: touch;">
                    <!-- Resumen del Sistema -->
                    <div class="card border-0 bg-light rounded-3 p-3 mb-4">
                        <div class="row text-center g-2">
                            <div class="col-3">
                                <span class="text-muted small d-block">Saldo Inicial:</span>
                                <strong class="text-dark">Bs. {{ number_format($cajaAbierta->monto_inicial, 2) }}</strong>
                            </div>
                            <div class="col-3">
                                <span class="text-muted small d-block">Ventas Efectivo:</span>
                                <strong class="text-success">+Bs. {{ number_format($ventasTurnoTotal, 2) }}</strong>
                            </div>
                            <div class="col-3">
                                <span class="text-muted small d-block">Movs. Extras:</span>
                                <strong class="text-primary">+Bs. {{ number_format($ingresosExtraTurno - $egresosGastosTurno, 2) }}</strong>
                            </div>
                            <div class="col-3">
                                <span class="text-muted small d-block">Total Esperado:</span>
                                <strong class="text-primary fs-6" id="labelEsperadoModal" data-esperado="{{ $saldoEsperadoEnVivo }}">
                                    Bs. {{ number_format($saldoEsperadoEnVivo, 2) }}
                                </strong>
                            </div>
                        </div>
                    </div>

                    <!-- Calculadora Interactiva de Billetes y Monedas -->
                    <h6 class="fw-bold text-dark mb-2 d-flex align-items-center gap-2">
                        <i class="fas fa-calculator text-primary"></i> Desglose de Efectivo Contado Físicamente:
                    </h6>
                    <p class="text-muted small mb-3">Cuenta los billetes y monedas que tienes físicamente en la gaveta. El total se calculará de forma automática.</p>

                    <!-- BILLETES -->
                    <div class="small fw-bold text-muted text-uppercase mb-2">Billetes Nacionales:</div>
                    <div class="row g-2 mb-3">
                        <div class="col-md-6">
                            <div class="denomination-row">
                                <span class="denomination-tag">Bs. 200</span>
                                <input type="number" min="0" name="b200" class="form-control form-control-sm denomination-input" value="0" oninput="calcularArqueo()">
                                <span class="denomination-subtotal" id="sub_b200">Bs. 0.00</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="denomination-row">
                                <span class="denomination-tag">Bs. 100</span>
                                <input type="number" min="0" name="b100" class="form-control form-control-sm denomination-input" value="0" oninput="calcularArqueo()">
                                <span class="denomination-subtotal" id="sub_b100">Bs. 0.00</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="denomination-row">
                                <span class="denomination-tag">Bs. 50</span>
                                <input type="number" min="0" name="b50" class="form-control form-control-sm denomination-input" value="0" oninput="calcularArqueo()">
                                <span class="denomination-subtotal" id="sub_b50">Bs. 0.00</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="denomination-row">
                                <span class="denomination-tag">Bs. 20</span>
                                <input type="number" min="0" name="b20" class="form-control form-control-sm denomination-input" value="0" oninput="calcularArqueo()">
                                <span class="denomination-subtotal" id="sub_b20">Bs. 0.00</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="denomination-row">
                                <span class="denomination-tag">Bs. 10</span>
                                <input type="number" min="0" name="b10" class="form-control form-control-sm denomination-input" value="0" oninput="calcularArqueo()">
                                <span class="denomination-subtotal" id="sub_b10">Bs. 0.00</span>
                            </div>
                        </div>
                    </div>

                    <!-- MONEDAS -->
                    <div class="small fw-bold text-muted text-uppercase mb-2">Monedas Fraccionarias:</div>
                    <div class="row g-2 mb-4">
                        <div class="col-md-6">
                            <div class="denomination-row">
                                <span class="denomination-tag">Bs. 5.00</span>
                                <input type="number" min="0" name="m5" class="form-control form-control-sm denomination-input" value="0" oninput="calcularArqueo()">
                                <span class="denomination-subtotal" id="sub_m5">Bs. 0.00</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="denomination-row">
                                <span class="denomination-tag">Bs. 2.00</span>
                                <input type="number" min="0" name="m2" class="form-control form-control-sm denomination-input" value="0" oninput="calcularArqueo()">
                                <span class="denomination-subtotal" id="sub_m2">Bs. 0.00</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="denomination-row">
                                <span class="denomination-tag">Bs. 1.00</span>
                                <input type="number" min="0" name="m1" class="form-control form-control-sm denomination-input" value="0" oninput="calcularArqueo()">
                                <span class="denomination-subtotal" id="sub_m1">Bs. 0.00</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="denomination-row">
                                <span class="denomination-tag">Bs. 0.50</span>
                                <input type="number" min="0" name="m050" class="form-control form-control-sm denomination-input" value="0" oninput="calcularArqueo()">
                                <span class="denomination-subtotal" id="sub_m050">Bs. 0.00</span>
                            </div>
                        </div>
                    </div>

                    <!-- PANEL DE RESULTADO EN VIVO -->
                    <div class="card border-0 rounded-4 p-3 mb-3" style="background: #0f172a; color: #fff;">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <span class="text-muted small text-uppercase fw-bold d-block">TOTAL FÍSICO CONTADO:</span>
                                <div class="input-group mt-1">
                                    <span class="input-group-text bg-dark text-white border-secondary">Bs.</span>
                                    <input type="number" step="0.50" min="0" name="monto_real" id="inputMontoReal" class="form-control form-control-lg fw-bold text-success bg-dark text-white border-secondary" value="0.00" oninput="calcularDiferenciaManual()" required>
                                </div>
                            </div>
                            <div class="col-md-6 text-md-end mt-3 mt-md-0">
                                <span class="text-muted small text-uppercase fw-bold d-block">ESTADO DE CUADRE:</span>
                                <div id="panelDiferenciaTexto" class="h4 fw-bold mb-0 text-success mt-1">
                                    <i class="fas fa-check-circle me-1"></i> Caja Cuadrada (Bs. 0.00)
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-0">
                        <label class="form-label fw-bold small text-dark">Observaciones o Justificación de Cierre</label>
                        <textarea name="observaciones_cierre" class="form-control" rows="2" placeholder="Explique si hubo faltante, sobrante o notas de arqueo..."></textarea>
                    </div>
                </div>

                <div class="modal-footer border-0 bg-light rounded-bottom-4 px-4 py-3 flex-shrink-0">
                    <button type="button" class="btn btn-secondary rounded-3 px-3" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-dark rounded-3 px-4 fw-bold">
                        <i class="fas fa-vault me-1 text-warning"></i> Confirmar Cierre y Arqueo
                    </button>
                </div>
        </form>
    </div>
</div>

<script>
    function calcularArqueo() {
        const valores = {
            'b200': 200,
            'b100': 100,
            'b50': 50,
            'b20': 20,
            'b10': 10,
            'm5': 5,
            'm2': 2,
            'm1': 1,
            'm050': 0.50
        };

        let total = 0;

        for (const [key, val] of Object.entries(valores)) {
            const input = document.querySelector(`input[name="${key}"]`);
            const subSpan = document.getElementById(`sub_${key}`);
            const cant = parseInt(input ? input.value : 0) || 0;
            const sub = cant * val;
            total += sub;
            if (subSpan) {
                subSpan.innerText = 'Bs. ' + sub.toFixed(2);
            }
        }

        const inputReal = document.getElementById('inputMontoReal');
        if (inputReal) {
            inputReal.value = total.toFixed(2);
        }

        actualizarDiferenciaUI(total);
    }

    function calcularDiferenciaManual() {
        const inputReal = document.getElementById('inputMontoReal');
        const total = parseFloat(inputReal ? inputReal.value : 0) || 0;
        actualizarDiferenciaUI(total);
    }

    function actualizarDiferenciaUI(totalReal) {
        const elEsperado = document.getElementById('labelEsperadoModal');
        const esperado = parseFloat(elEsperado ? elEsperado.getAttribute('data-esperado') : 0) || 0;
        const diff = totalReal - esperado;
        const panelTexto = document.getElementById('panelDiferenciaTexto');

        if (!panelTexto) return;

        if (Math.abs(diff) < 0.01) {
            panelTexto.className = 'h4 fw-bold mb-0 text-success mt-1';
            panelTexto.innerHTML = '<i class="fas fa-check-double me-1"></i> Caja Cuadrada (Bs. 0.00)';
        } else if (diff > 0) {
            panelTexto.className = 'h4 fw-bold mb-0 text-info mt-1';
            panelTexto.innerHTML = `<i class="fas fa-plus-circle me-1"></i> Sobrante: +Bs. ${diff.toFixed(2)}`;
        } else {
            panelTexto.className = 'h4 fw-bold mb-0 text-danger mt-1';
            panelTexto.innerHTML = `<i class="fas fa-exclamation-triangle me-1"></i> Faltante: -Bs. ${Math.abs(diff).toFixed(2)}`;
        }
    }
</script>
@endif

@endsection
