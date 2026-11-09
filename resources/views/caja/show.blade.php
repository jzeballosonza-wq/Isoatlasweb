@extends('dashboard')

@section('content')

<style>
    .voucher-card {
        background: #ffffff;
        border-radius: 20px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
        padding: 2.5rem;
        max-width: 900px;
        margin: 0 auto;
    }

    .voucher-header {
        border-bottom: 2px dashed #e2e8f0;
        padding-bottom: 1.5rem;
        margin-bottom: 1.5rem;
    }

    .balance-box {
        background: #f8fafc;
        border-radius: 14px;
        padding: 1.25rem;
        border: 1px solid #e2e8f0;
    }

    .signature-line {
        border-top: 1px solid #94a3b8;
        width: 80%;
        margin: 3.5rem auto 0.5rem auto;
    }

    @media print {
        body * {
            visibility: hidden;
        }
        .voucher-card, .voucher-card * {
            visibility: visible;
        }
        .voucher-card {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            border: none;
            box-shadow: none;
            padding: 0;
        }
        .no-print {
            display: none !important;
        }
    }
</style>

<div class="container py-4">

    <!-- BOTONES DE ACCIÓN SUPERIOR -->
    <div class="d-flex justify-content-between align-items-center mb-4 no-print" style="max-width: 900px; margin: 0 auto;">
        <a href="{{ route('caja.index') }}" class="btn btn-outline-secondary rounded-3 px-3 fw-semibold">
            <i class="fas fa-arrow-left me-1"></i> Volver a Control de Caja
        </a>
        <div class="d-flex gap-2">
            <button type="button" onclick="window.print()" class="btn btn-primary rounded-3 px-4 fw-bold shadow-sm">
                <i class="fas fa-print me-1"></i> Imprimir Comprobante
            </button>
        </div>
    </div>

    <!-- FLASH MESSAGES -->
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-4 mb-4 no-print" role="alert" style="max-width: 900px; margin: 0 auto;">
        <div class="d-flex align-items-center gap-2">
            <i class="fas fa-check-circle fs-5 text-success"></i>
            <div class="fw-semibold">{{ session('success') }}</div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <!-- TARJETA COMPROBANTE -->
    <div class="voucher-card">

        <!-- CABECERA DE LA TIENDA -->
        <div class="voucher-header d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3">
            <div>
                <div class="d-flex align-items-center gap-2 mb-1">
                    <div style="width: 36px; height: 36px; border-radius: 10px; background: linear-gradient(135deg, #1e1b4b 0%, #4338ca 100%); color: #fff; display: flex; align-items: center; justify-content: center; font-size: 1.1rem;">
                        <i class="fas fa-plug-circle-bolt"></i>
                    </div>
                    <h4 class="fw-bold mb-0 text-dark">Tienda Isoatlas</h4>
                </div>
                <div class="text-muted small">SISTEMA INTEGRADO DE GESTIÓN & ELECTRÓNICA</div>
                <div class="text-muted small">COMPROBANTE OFICIAL DE ARQUEO Y CIERRE DE CAJA</div>
            </div>

            <div class="text-sm-end">
                <div class="badge bg-dark text-white px-3 py-2 fs-6 rounded-pill mb-1">
                    ARQUEO #{{ str_pad($caja->id, 5, '0', STR_PAD_LEFT) }}
                </div>
                <div class="small text-muted font-monospace d-block">
                    Emisión: {{ now()->format('d/m/Y H:i:s') }}
                </div>
            </div>
        </div>

        <!-- DATOS DEL TURNO Y ESTADO -->
        <div class="row g-3 mb-4">
            <div class="col-md-6">
                <table class="table table-sm table-borderless mb-0">
                    <tr>
                        <td class="text-muted small py-1" style="width: 140px;">Caja / Punto:</td>
                        <td class="fw-bold text-dark py-1">{{ $caja->caja_nombre }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted small py-1">Cajero Apertura:</td>
                        <td class="fw-semibold text-dark py-1">{{ $caja->usuarioApertura->name ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted small py-1">Cajero/Sup. Cierre:</td>
                        <td class="fw-semibold text-dark py-1">{{ $caja->usuarioCierre->name ?? 'Mismo cajero' }}</td>
                    </tr>
                </table>
            </div>

            <div class="col-md-6">
                <table class="table table-sm table-borderless mb-0">
                    <tr>
                        <td class="text-muted small py-1" style="width: 140px;">Apertura:</td>
                        <td class="fw-semibold text-dark py-1">{{ $caja->fecha_apertura->format('d/m/Y H:i') }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted small py-1">Cierre:</td>
                        <td class="fw-semibold text-dark py-1">{{ $caja->fecha_cierre ? $caja->fecha_cierre->format('d/m/Y H:i') : '--' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted small py-1">Estado de Arqueo:</td>
                        <td class="py-1">
                            @php $res = $caja->resultado_arqueo; @endphp
                            <span class="badge {{ $res['badge'] }} px-2 py-1">
                                <i class="{{ $res['icon'] }} me-1"></i> {{ $res['label'] }}
                            </span>
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- RESUMEN DE CONCILIACIÓN DE EFECTIVO -->
        <h6 class="fw-bold text-dark mb-2">
            <i class="fas fa-coins text-warning me-1"></i> Balance de Movimientos y Conciliación
        </h6>
        <div class="balance-box mb-4">
            <div class="row g-3">
                <div class="col-sm-6 col-lg-3">
                    <span class="text-muted small d-block">Saldo Inicial:</span>
                    <h5 class="fw-bold text-dark mb-0">Bs. {{ number_format($caja->monto_inicial, 2) }}</h5>
                </div>
                <div class="col-sm-6 col-lg-3">
                    <span class="text-muted small d-block">(+) Ventas Efectivo:</span>
                    <h5 class="fw-bold text-success mb-0">+Bs. {{ number_format($caja->total_ventas_efectivo, 2) }}</h5>
                    <small class="text-muted">{{ $caja->ventas->count() }} comprobantes</small>
                </div>
                <div class="col-sm-6 col-lg-3">
                    <span class="text-muted small d-block">(+) Ingresos Extras:</span>
                    <h5 class="fw-bold text-primary mb-0">+Bs. {{ number_format($caja->total_ingresos_extra, 2) }}</h5>
                </div>
                <div class="col-sm-6 col-lg-3">
                    <span class="text-muted small d-block">(-) Egresos / Gastos:</span>
                    <h5 class="fw-bold text-danger mb-0">-Bs. {{ number_format($caja->total_egresos_gastos, 2) }}</h5>
                </div>
            </div>

            <hr class="my-3">

            <div class="row g-3 align-items-center">
                <div class="col-md-4">
                    <span class="text-muted small d-block">Total Esperado por Sistema:</span>
                    <h4 class="fw-bold text-secondary mb-0">Bs. {{ number_format($caja->monto_esperado, 2) }}</h4>
                </div>
                <div class="col-md-4">
                    <span class="text-muted small d-block">Total Físico Real Contado:</span>
                    <h4 class="fw-bold text-dark mb-0">Bs. {{ number_format($caja->monto_real, 2) }}</h4>
                </div>
                <div class="col-md-4">
                    <span class="text-muted small d-block">Diferencia de Arqueo:</span>
                    <h4 class="fw-bold mb-0 {{ abs($caja->diferencia) < 0.01 ? 'text-success' : ($caja->diferencia > 0 ? 'text-info' : 'text-danger') }}">
                        @if(abs($caja->diferencia) < 0.01)
                            Bs. 0.00 (Exacto)
                        @elseif($caja->diferencia > 0)
                            +Bs. {{ number_format($caja->diferencia, 2) }} (Sobrante)
                        @else
                            -Bs. {{ number_format(abs($caja->diferencia), 2) }} (Faltante)
                        @endif
                    </h4>
                </div>
            </div>
        </div>

        <!-- DESGLOSE FÍSICO DE BILLETES Y MONEDAS -->
        @if($caja->desglose_billetes)
        <h6 class="fw-bold text-dark mb-2">
            <i class="fas fa-calculator text-primary me-1"></i> Desglose de Conteo Físico
        </h6>
        <div class="table-responsive mb-4">
            <table class="table table-bordered table-sm text-center mb-0" style="font-size: 0.85rem;">
                <thead class="bg-light">
                    <tr>
                        <th>Denominación</th>
                        <th>Bs. 200</th>
                        <th>Bs. 100</th>
                        <th>Bs. 50</th>
                        <th>Bs. 20</th>
                        <th>Bs. 10</th>
                        <th>Bs. 5</th>
                        <th>Bs. 2</th>
                        <th>Bs. 1</th>
                        <th>Bs. 0.50</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="fw-bold">Cantidad:</td>
                        <td>{{ $caja->desglose_billetes['b200'] ?? 0 }}</td>
                        <td>{{ $caja->desglose_billetes['b100'] ?? 0 }}</td>
                        <td>{{ $caja->desglose_billetes['b50'] ?? 0 }}</td>
                        <td>{{ $caja->desglose_billetes['b20'] ?? 0 }}</td>
                        <td>{{ $caja->desglose_billetes['b10'] ?? 0 }}</td>
                        <td>{{ $caja->desglose_billetes['m5'] ?? 0 }}</td>
                        <td>{{ $caja->desglose_billetes['m2'] ?? 0 }}</td>
                        <td>{{ $caja->desglose_billetes['m1'] ?? 0 }}</td>
                        <td>{{ $caja->desglose_billetes['m050'] ?? 0 }}</td>
                    </tr>
                    <tr class="fw-bold text-success bg-light bg-opacity-50">
                        <td>Subtotal:</td>
                        <td>Bs. {{ number_format(($caja->desglose_billetes['b200'] ?? 0) * 200, 2) }}</td>
                        <td>Bs. {{ number_format(($caja->desglose_billetes['b100'] ?? 0) * 100, 2) }}</td>
                        <td>Bs. {{ number_format(($caja->desglose_billetes['b50'] ?? 0) * 50, 2) }}</td>
                        <td>Bs. {{ number_format(($caja->desglose_billetes['b20'] ?? 0) * 20, 2) }}</td>
                        <td>Bs. {{ number_format(($caja->desglose_billetes['b10'] ?? 0) * 10, 2) }}</td>
                        <td>Bs. {{ number_format(($caja->desglose_billetes['m5'] ?? 0) * 5, 2) }}</td>
                        <td>Bs. {{ number_format(($caja->desglose_billetes['m2'] ?? 0) * 2, 2) }}</td>
                        <td>Bs. {{ number_format(($caja->desglose_billetes['m1'] ?? 0) * 1, 2) }}</td>
                        <td>Bs. {{ number_format(($caja->desglose_billetes['m050'] ?? 0) * 0.50, 2) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
        @endif

        <!-- OBSERVACIONES DE CIERRE -->
        @if($caja->observaciones_cierre)
        <div class="p-3 bg-light rounded-3 mb-4">
            <span class="small fw-bold text-muted d-block mb-1">Observaciones / Justificación de Cierre:</span>
            <p class="mb-0 text-dark small">{{ $caja->observaciones_cierre }}</p>
        </div>
        @endif

        <!-- FIRMAS DE CONFORMIDAD -->
        <div class="row pt-4 text-center">
            <div class="col-6">
                <div class="signature-line"></div>
                <strong class="d-block text-dark small">{{ $caja->usuarioApertura->name ?? 'Cajero' }}</strong>
                <span class="text-muted small">Cajero Responsable (Entregué Conforme)</span>
            </div>
            <div class="col-6">
                <div class="signature-line"></div>
                <strong class="d-block text-dark small">{{ $caja->usuarioCierre->name ?? 'Administrador' }}</strong>
                <span class="text-muted small">Supervisor / Administración (Recibí Conforme)</span>
            </div>
        </div>

    </div>

</div>

@endsection
