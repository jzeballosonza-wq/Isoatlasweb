<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Comprobante de Arqueo #{{ str_pad($caja->id, 5, '0', STR_PAD_LEFT) }} | Tienda Isoatlas</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body { font-family: Arial, sans-serif; font-size: 13px; color: #000; padding: 20px; }
        .voucher-header { border-bottom: 2px solid #000; padding-bottom: 10px; margin-bottom: 15px; }
        .balance-box { border: 1px solid #000; padding: 12px; margin-bottom: 15px; }
        .signature-line { border-top: 1px solid #000; width: 75%; margin: 50px auto 5px auto; }
        @media print {
            .no-print { display: none !important; }
        }
    </style>
</head>
<body onload="window.print()">

    <div class="no-print mb-3 text-end">
        <button onclick="window.print()" class="btn btn-primary btn-sm">Imprimir</button>
        <button onclick="window.close()" class="btn btn-secondary btn-sm">Cerrar</button>
    </div>

    <div class="voucher-header d-flex justify-content-between align-items-center">
        <div>
            <h4 class="mb-0 fw-bold">TIENDA ISOATLAS</h4>
            <div class="small">SISTEMA INTEGRADO DE GESTIÓN &bull; CONTROL DE CAJA</div>
            <div class="small fw-bold">COMPROBANTE DE ARQUEO Y CIERRE DE CAJA</div>
        </div>
        <div class="text-end">
            <h5 class="mb-0 fw-bold">ARQUEO #{{ str_pad($caja->id, 5, '0', STR_PAD_LEFT) }}</h5>
            <div class="small">Fecha Emisión: {{ now()->format('d/m/Y H:i') }}</div>
        </div>
    </div>

    <table class="table table-sm table-borderless mb-3">
        <tr>
            <td style="width: 15%;"><strong>Caja:</strong></td>
            <td style="width: 35%;">{{ $caja->caja_nombre }}</td>
            <td style="width: 15%;"><strong>Apertura:</strong></td>
            <td style="width: 35%;">{{ $caja->fecha_apertura->format('d/m/Y H:i') }}</td>
        </tr>
        <tr>
            <td><strong>Cajero:</strong></td>
            <td>{{ $caja->usuarioApertura->name ?? 'N/A' }}</td>
            <td><strong>Cierre:</strong></td>
            <td>{{ $caja->fecha_cierre ? $caja->fecha_cierre->format('d/m/Y H:i') : '--' }}</td>
        </tr>
        <tr>
            <td><strong>Supervisor:</strong></td>
            <td>{{ $caja->usuarioCierre->name ?? 'Mismo cajero' }}</td>
            <td><strong>Resultado:</strong></td>
            <td>
                @php $res = $caja->resultado_arqueo; @endphp
                <strong>{{ strtoupper($res['label']) }}</strong>
            </td>
        </tr>
    </table>

    <div class="balance-box">
        <h6 class="fw-bold mb-2">CONCILIACIÓN DE EFECTIVO</h6>
        <div class="row text-center mb-2">
            <div class="col-3">
                <span class="small d-block text-muted">Saldo Inicial</span>
                <strong>Bs. {{ number_format($caja->monto_inicial, 2) }}</strong>
            </div>
            <div class="col-3">
                <span class="small d-block text-muted">(+) Ventas Efectivo</span>
                <strong>Bs. {{ number_format($caja->total_ventas_efectivo, 2) }}</strong>
            </div>
            <div class="col-3">
                <span class="small d-block text-muted">(+) Ingresos Extras</span>
                <strong>Bs. {{ number_format($caja->total_ingresos_extra, 2) }}</strong>
            </div>
            <div class="col-3">
                <span class="small d-block text-muted">(-) Egresos / Gastos</span>
                <strong>Bs. {{ number_format($caja->total_egresos_gastos, 2) }}</strong>
            </div>
        </div>
        <hr class="my-2">
        <div class="row text-center">
            <div class="col-4">
                <span class="small d-block text-muted">Total Esperado</span>
                <h5>Bs. {{ number_format($caja->monto_esperado, 2) }}</h5>
            </div>
            <div class="col-4">
                <span class="small d-block text-muted">Total Físico Contado</span>
                <h5>Bs. {{ number_format($caja->monto_real, 2) }}</h5>
            </div>
            <div class="col-4">
                <span class="small d-block text-muted">Diferencia</span>
                <h5 class="{{ abs($caja->diferencia) < 0.01 ? 'text-success' : 'text-danger' }}">
                    Bs. {{ number_format($caja->diferencia, 2) }}
                </h5>
            </div>
        </div>
    </div>

    @if($caja->desglose_billetes)
    <h6 class="fw-bold mb-1">CONTEO FÍSICO DETALLADO:</h6>
    <table class="table table-bordered table-sm text-center mb-3">
        <thead>
            <tr>
                <th>Denominación</th>
                <th>200</th>
                <th>100</th>
                <th>50</th>
                <th>20</th>
                <th>10</th>
                <th>5</th>
                <th>2</th>
                <th>1</th>
                <th>0.50</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Cantidad</td>
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
        </tbody>
    </table>
    @endif

    @if($caja->observaciones_cierre)
    <div class="mb-4">
        <strong>Observaciones de Cierre:</strong>
        <p class="mb-0">{{ $caja->observaciones_cierre }}</p>
    </div>
    @endif

    <div class="row text-center mt-5">
        <div class="col-6">
            <div class="signature-line"></div>
            <strong>{{ $caja->usuarioApertura->name ?? 'Cajero' }}</strong><br>
            <span class="small">Cajero Responsable</span>
        </div>
        <div class="col-6">
            <div class="signature-line"></div>
            <strong>{{ $caja->usuarioCierre->name ?? 'Supervisor' }}</strong><br>
            <span class="small">Supervisor / Administración</span>
        </div>
    </div>

</body>
</html>
