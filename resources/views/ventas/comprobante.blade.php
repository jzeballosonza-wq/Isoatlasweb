<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comprobante de Venta Horizontal #{{ str_pad($venta->id, 6, '0', STR_PAD_LEFT) }} | Tienda Isoatlas</title>

    <!-- Google Fonts: Plus Jakarta Sans & JetBrains Mono -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- FontAwesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        :root {
            --font-sans: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
            --font-mono: 'JetBrains Mono', 'Courier New', monospace;
            --primary-color: #1e293b;
            --primary-accent: #4f46e5;
            --border-color: #cbd5e1;
            --border-dark: #334155;
            --bg-light: #f8fafc;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            background-color: #0f172a;
            font-family: var(--font-sans);
            color: #0f172a;
            padding: 24px 16px 60px 16px;
            display: flex;
            flex-direction: column;
            align-items: center;
            min-height: 100vh;
        }

        /* BARRA SUPERIOR DE ACCIONES (NO IMPRIMIBLE) */
        .actions-toolbar {
            width: 100%;
            max-width: 960px;
            background: rgba(30, 41, 59, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 16px;
            padding: 12px 20px;
            margin-bottom: 24px;
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.5);
            z-index: 100;
        }

        .actions-group {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .btn-action {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 16px;
            border-radius: 10px;
            font-size: 0.85rem;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            border: none;
            transition: all 0.2s ease;
        }

        .btn-print {
            background: linear-gradient(135deg, #4f46e5 0%, #6366f1 100%);
            color: #ffffff;
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.35);
        }

        .btn-print:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(99, 102, 241, 0.5);
            color: #ffffff;
        }

        .btn-secondary-action {
            background: rgba(255, 255, 255, 0.08);
            color: #cbd5e1;
            border: 1px solid rgba(255, 255, 255, 0.12);
        }

        .btn-secondary-action:hover {
            background: rgba(255, 255, 255, 0.16);
            color: #ffffff;
        }

        /* CONTENEDOR HORIZONTAL DEL COMPROBANTE */
        .voucher-horizontal-card {
            width: 100%;
            max-width: 960px;
            background: #ffffff;
            border-radius: 8px;
            box-shadow: 0 20px 40px -10px rgba(0, 0, 0, 0.6), 0 0 0 1px rgba(255, 255, 255, 0.1);
            padding: 28px 32px;
            color: #000000;
            position: relative;
        }

        /* 1. CABECERA HORIZONTAL */
        .voucher-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 20px;
            border-bottom: 2px solid #000000;
            padding-bottom: 14px;
            margin-bottom: 14px;
        }

        .company-identity {
            display: flex;
            align-items: flex-start;
            gap: 14px;
        }

        .company-logo-box {
            width: 48px;
            height: 48px;
            background: #000000;
            color: #ffffff;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            flex-shrink: 0;
        }

        .company-name {
            font-size: 22px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: -0.01em;
            margin: 0;
            line-height: 1.1;
        }

        .company-tagline {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            color: #334155;
            letter-spacing: 0.08em;
            margin-top: 2px;
        }

        .company-meta {
            font-size: 10.5px;
            color: #333333;
            margin-top: 4px;
            line-height: 1.4;
        }

        /* RECUADRO FISCAL DEL DOCUMENTO (DERECHA) */
        .fiscal-box {
            border: 2px solid #000000;
            border-radius: 8px;
            padding: 10px 18px;
            text-align: center;
            min-width: 250px;
            background: #fdfdfd;
        }

        .fiscal-nit {
            font-family: var(--font-mono);
            font-weight: 800;
            font-size: 13px;
            letter-spacing: 0.05em;
        }

        .fiscal-title {
            font-size: 13px;
            font-weight: 800;
            text-transform: uppercase;
            margin: 4px 0;
            padding: 3px 0;
            border-top: 1px dashed #000000;
            border-bottom: 1px dashed #000000;
            letter-spacing: 0.03em;
        }

        .fiscal-number {
            font-family: var(--font-mono);
            font-size: 18px;
            font-weight: 800;
            color: #000000;
            letter-spacing: 0.02em;
        }

        .fiscal-copy {
            font-size: 9px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: #475569;
        }

        /* 2. GRILLA DE INFORMACIÓN DEL CLIENTE Y TRANSACCIÓN */
        .info-grid-container {
            border: 1px solid #000000;
            border-radius: 6px;
            padding: 10px 14px;
            margin-bottom: 14px;
            background: #fafafa;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 11px;
        }

        .info-table td {
            padding: 4px 6px;
            vertical-align: middle;
        }

        .info-label {
            font-weight: 700;
            color: #1e293b;
            text-transform: uppercase;
            font-size: 10px;
            letter-spacing: 0.02em;
        }

        .info-val {
            font-weight: 600;
            color: #000000;
        }

        .info-val-mono {
            font-family: var(--font-mono);
            font-weight: 700;
        }

        /* 3. TABLA HORIZONTAL DE PRODUCTOS */
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 14px;
            font-size: 11.5px;
        }

        .items-table thead th {
            background: #f1f5f9;
            color: #000000;
            font-weight: 800;
            text-transform: uppercase;
            font-size: 10px;
            letter-spacing: 0.04em;
            padding: 7px 8px;
            border: 1px solid #000000;
        }

        .items-table tbody td {
            padding: 7px 8px;
            border: 1px solid #cbd5e1;
            vertical-align: middle;
        }

        .items-table tbody tr:nth-child(even) {
            background-color: #fcfcfc;
        }

        .col-item-num {
            width: 35px;
            text-align: center;
            font-family: var(--font-mono);
            font-weight: 600;
            color: #475569;
        }

        .col-item-sku {
            width: 110px;
            text-align: center;
            font-family: var(--font-mono);
            font-weight: 700;
        }

        .col-item-desc {
            font-weight: 600;
        }

        .col-item-cat {
            font-size: 9.5px;
            color: #475569;
            font-weight: 500;
        }

        .col-item-qty {
            width: 65px;
            text-align: center;
            font-family: var(--font-mono);
            font-weight: 700;
        }

        .col-item-price {
            width: 110px;
            text-align: right;
            font-family: var(--font-mono);
            font-weight: 600;
        }

        .col-item-subtotal {
            width: 120px;
            text-align: right;
            font-family: var(--font-mono);
            font-weight: 800;
            color: #000000;
        }

        /* 4. SECCIÓN INFERIOR HORIZONTAL: LETRAS, BARCODE & TOTALES */
        .voucher-footer-grid {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 20px;
            margin-top: 8px;
        }

        .footer-left-col {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .footer-right-col {
            width: 310px;
            flex-shrink: 0;
        }

        /* IMPORTE EN LETRAS */
        .amount-in-words-box {
            border: 1px solid #cbd5e1;
            background: #fafafa;
            border-radius: 6px;
            padding: 8px 12px;
            font-size: 10.5px;
            line-height: 1.4;
        }

        .amount-in-words-box strong {
            text-transform: uppercase;
            font-size: 9.5px;
            color: #334155;
            display: block;
            margin-bottom: 2px;
        }

        /* TÉRMINOS Y GARANTÍAS */
        .terms-box {
            border: 1px dashed #cbd5e1;
            border-radius: 6px;
            padding: 8px 12px;
            font-size: 9.5px;
            color: #334155;
            line-height: 1.35;
        }

        .terms-box strong {
            color: #000000;
        }

        /* CÓDIGO DE BARRAS */
        .barcode-horizontal-box {
            display: flex;
            align-items: center;
            gap: 12px;
            padding-top: 4px;
        }

        .barcode-svg {
            height: 32px;
            width: 180px;
        }

        .barcode-label {
            font-family: var(--font-mono);
            font-size: 9.5px;
            font-weight: 700;
            letter-spacing: 0.08em;
            color: #1e293b;
        }

        /* TOTALES EN CUADRO FINANCIERO */
        .totals-summary-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 11.5px;
            border: 1px solid #000000;
            border-radius: 6px;
            overflow: hidden;
        }

        .totals-summary-table td {
            padding: 5px 10px;
            border-bottom: 1px solid #e2e8f0;
        }

        .totals-summary-table .row-total-destacado {
            background: #000000;
            color: #ffffff;
            border-top: 2px solid #000000;
        }

        .totals-summary-table .row-total-destacado td {
            padding: 8px 10px;
            border-bottom: none;
        }

        .totals-label {
            font-weight: 700;
            font-size: 10.5px;
        }

        .totals-val {
            text-align: right;
            font-family: var(--font-mono);
            font-weight: 700;
        }

        .total-amount-grand {
            font-family: var(--font-mono);
            font-size: 18px;
            font-weight: 800;
            letter-spacing: -0.02em;
        }

        /* 5. FIRMAS DE CONFORMIDAD HORIZONTALES */
        .signatures-horizontal-row {
            display: flex;
            justify-content: space-between;
            gap: 40px;
            margin-top: 36px;
            padding-top: 10px;
        }

        .sig-box {
            flex: 1;
            text-align: center;
        }

        .sig-line {
            border-top: 1px solid #000000;
            margin-bottom: 4px;
            width: 80%;
            margin-left: auto;
            margin-right: auto;
        }

        .sig-label {
            font-size: 9.5px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            color: #333333;
        }

        .sig-sub {
            font-size: 8.5px;
            color: #64748b;
        }

        /* ======================================================== */
        /* CONFIGURACIÓN EXACTA DE IMPRESIÓN HORIZONTAL / LANDSCAPE */
        /* ======================================================== */
        @media print {
            body {
                background: #ffffff !important;
                padding: 0 !important;
                margin: 0 !important;
                display: block !important;
            }

            .actions-toolbar {
                display: none !important;
            }

            .voucher-horizontal-card {
                max-width: 100% !important;
                width: 100% !important;
                box-shadow: none !important;
                border: none !important;
                border-radius: 0 !important;
                padding: 0 4mm !important;
                margin: 0 !important;
            }

            @page {
                size: landscape;
                margin: 8mm 10mm;
            }
        }
    </style>
</head>

<body>

    @php
        // Cálculo de totales y datos del cliente
        $nombreCliente = 'CLIENTE GENERAL';
        $nitCliente = 'S/N (Sin NIT)';
        $telefonoCliente = '';

        if ($cliente) {
            $nombreCliente = $cliente->nombre;
            $nitCliente = $cliente->nit ?: ($venta->comprobante ?: 'S/N');
            $telefonoCliente = $cliente->telefono ?? '';
        } elseif (!empty($venta->comprobante) && $venta->comprobante !== 'Sin NIT' && $venta->comprobante !== '0') {
            $nombreCliente = 'CLIENTE PARTICULAR';
            $nitCliente = $venta->comprobante;
        }

        $cajeroNombre = $venta->cajaSesion->usuarioApertura->name ?? auth()->user()->name ?? 'Caja Central';
        $cajaNombre = $venta->cajaSesion->caja_nombre ?? 'Caja Principal';
        $totalUnidades = $venta->detalles->sum('cantidad');

        // Función para expresar el monto en palabras
        if (!function_exists('montoEnPalabrasBolivianos')) {
            function montoEnPalabrasBolivianos($numero) {
                $unidades = ['', 'un', 'dos', 'tres', 'cuatro', 'cinco', 'seis', 'siete', 'ocho', 'nueve', 'diez', 'once', 'doce', 'trece', 'catorce', 'quince', 'dieciséis', 'diecisiete', 'dieciocho', 'diecinueve'];
                $decenas = ['', 'diez', 'veinte', 'treinta', 'cuarenta', 'cincuenta', 'sesenta', 'setenta', 'ochenta', 'noventa'];
                $centenas = ['', 'ciento', 'doscientos', 'trescientos', 'cuatrocientos', 'quinientos', 'seiscientos', 'setecientos', 'ochocientos', 'novecientos'];

                $entero = floor($numero);
                $centavos = round(($numero - $entero) * 100);
                $centavosStr = str_pad($centavos, 2, '0', STR_PAD_RIGHT) . '/100 Bolivianos';

                if ($entero == 0) return 'Cero ' . $centavosStr;
                if ($entero == 100) return 'Cien ' . $centavosStr;

                $texto = '';
                if ($entero >= 1000) {
                    $miles = floor($entero / 1000);
                    $entero = $entero % 1000;
                    if ($miles == 1) {
                        $texto .= 'Mil ';
                    } else {
                        $texto .= ($unidades[$miles] ?? $miles) . ' Mil ';
                    }
                }
                if ($entero >= 100) {
                    $c = floor($entero / 100);
                    $entero = $entero % 100;
                    $texto .= $centenas[$c] . ' ';
                }
                if ($entero > 0) {
                    if ($entero < 20) {
                        $texto .= $unidades[$entero] . ' ';
                    } else {
                        $d = floor($entero / 10);
                        $u = $entero % 10;
                        if ($u == 0) {
                            $texto .= $decenas[$d] . ' ';
                        } else {
                            $texto .= ($d == 2 ? 'veinti' . $unidades[$u] : $decenas[$d] . ' y ' . $unidades[$u]) . ' ';
                        }
                    }
                }

                return ucfirst(trim($texto)) . ' ' . $centavosStr;
            }
        }
    @endphp

    <!-- BARRA SUPERIOR DE ACCIONES (NO IMPRIMIBLE) -->
    <div class="actions-toolbar">
        <div class="actions-group">
            <button type="button" class="btn-action btn-print" onclick="window.print()" title="Imprimir Comprobante Horizontal (Ctrl + P)">
                <i class="fas fa-print"></i>
                <span>Imprimir Comprobante (Horizontal)</span>
            </button>

            <a href="{{ route('ventas.create') }}" class="btn-action btn-secondary-action" title="Iniciar una nueva venta">
                <i class="fas fa-plus"></i>
                <span>Nueva Venta</span>
            </a>

            <a href="{{ route('ventas.index') }}" class="btn-action btn-secondary-action" title="Ir al historial de ventas">
                <i class="fas fa-arrow-left"></i>
                <span>Volver</span>
            </a>
        </div>

        <div class="actions-group">
            <span style="color: #94a3b8; font-size: 0.78rem; font-weight: 600;">
                <i class="fas fa-file-invoice me-1 text-primary"></i> Formato: <strong>Horizontal (Media Carta / A5)</strong>
            </span>
        </div>
    </div>

    <!-- VOUCHER HORIZONTAL CARD -->
    <main class="voucher-horizontal-card">

        <!-- 1. ENCABEZADO HORIZONTAL -->
        <header class="voucher-header">
            <div class="company-identity">
                <div class="company-logo-box">
                    <i class="fas fa-plug-circle-bolt"></i>
                </div>
                <div>
                    <h1 class="company-name">TIENDA ISOATLAS</h1>
                    <div class="company-tagline">Venta de Accesorios & Electrónica</div>
                    <div class="company-meta">
                        <div>Av. Principal #450 • Galería Tech Local 12 • Santa Cruz de la Sierra, Bolivia</div>
                        <div>WhatsApp / Pedidos: <strong>(+591) 70712345</strong> &bull; Email: contacto@tiendaisoatlas.com</div>
                    </div>
                </div>
            </div>

            <!-- RECUADRO FISCAL / COMPROBANTE -->
            <div class="fiscal-box">
                <div class="fiscal-nit">NIT: 1028475029</div>
                <div class="fiscal-title">RECIBO DE VENTA</div>
                <div class="fiscal-number">N° {{ str_pad($venta->id, 6, '0', STR_PAD_LEFT) }}</div>
                <div class="fiscal-copy">ORIGINAL &bull; VENTA COMERCIAL</div>
            </div>
        </header>

        <!-- 2. GRILLA HORIZONTAL DE DATOS DEL CLIENTE Y FECHA -->
        <section class="info-grid-container">
            <table class="info-table">
                <tr>
                    <td style="width: 15%;" class="info-label">Señor(es):</td>
                    <td style="width: 45%;" class="info-val"><strong>{{ strtoupper($nombreCliente) }}</strong></td>
                    <td style="width: 15%;" class="info-label">Fecha Emisión:</td>
                    <td style="width: 25%;" class="info-val-mono">{{ \Carbon\Carbon::parse($venta->fecha)->format('d/m/Y') }} &bull; {{ $venta->created_at->format('H:i') }}</td>
                </tr>
                <tr>
                    <td class="info-label">NIT / CI:</td>
                    <td class="info-val-mono">{{ $nitCliente }}</td>
                    <td class="info-label">Caja / Turno:</td>
                    <td class="info-val">{{ $cajaNombre }}</td>
                </tr>
                <tr>
                    <td class="info-label">Teléfono:</td>
                    <td class="info-val">{{ $telefonoCliente ?: 'No registrado' }}</td>
                    <td class="info-label">Atendido por:</td>
                    <td class="info-val">{{ strtoupper($cajeroNombre) }}</td>
                </tr>
            </table>
        </section>

        <!-- 3. TABLA HORIZONTAL DE PRODUCTOS -->
        <table class="items-table">
            <thead>
                <tr>
                    <th class="col-item-num">#</th>
                    <th class="col-item-sku">CÓDIGO / SKU</th>
                    <th class="col-item-desc">DESCRIPCIÓN DEL ARTÍCULO O ACCESORIO</th>
                    <th class="col-item-qty">CANT.</th>
                    <th class="col-item-price">P. UNITARIO</th>
                    <th class="col-item-subtotal">SUBTOTAL</th>
                </tr>
            </thead>
            <tbody>
                @foreach($venta->detalles as $idx => $detalle)
                    @php 
                        $subtotalLinea = $detalle->cantidad * $detalle->precio;
                    @endphp
                    <tr>
                        <td class="col-item-num">{{ $idx + 1 }}</td>
                        <td class="col-item-sku">{{ $detalle->producto->codigo ?? 'S/C' }}</td>
                        <td class="col-item-desc">
                            <strong>{{ $detalle->producto->nombre ?? 'Producto Eliminado' }}</strong>
                            @if(isset($detalle->producto->categoria))
                                <span class="col-item-cat">&bull; Categoría: {{ $detalle->producto->categoria->nombre }}</span>
                            @endif
                        </td>
                        <td class="col-item-qty">{{ $detalle->cantidad }}</td>
                        <td class="col-item-price">Bs. {{ number_format($detalle->precio, 2) }}</td>
                        <td class="col-item-subtotal">Bs. {{ number_format($subtotalLinea, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- 4. SECCIÓN INFERIOR HORIZONTAL: LETRAS, BARCODE & TOTALES -->
        <div class="voucher-footer-grid">
            
            <!-- COLUMNA IZQUIERDA: MONTO EN PALABRAS, GARANTÍA Y CÓDIGO DE BARRAS -->
            <div class="footer-left-col">
                <div class="amount-in-words-box">
                    <strong>Importe Total en Palabras:</strong>
                    <div>{{ montoEnPalabrasBolivianos($venta->total) }}</div>
                </div>

                <div class="terms-box">
                    <div><strong>Condiciones & Garantía:</strong> Garantía de funcionamiento técnico por 30 días contra fallas de fábrica presentando este comprobante original. No cubre golpes, caídas o manipulación indebida.</div>
                </div>

                <div class="barcode-horizontal-box">
                    <svg class="barcode-svg" viewBox="0 0 180 32" preserveAspectRatio="none">
                        <rect x="0" y="0" width="3" height="32" fill="#000"/>
                        <rect x="5" y="0" width="2" height="32" fill="#000"/>
                        <rect x="9" y="0" width="4" height="32" fill="#000"/>
                        <rect x="15" y="0" width="1" height="32" fill="#000"/>
                        <rect x="18" y="0" width="3" height="32" fill="#000"/>
                        <rect x="23" y="0" width="2" height="32" fill="#000"/>
                        <rect x="27" y="0" width="4" height="32" fill="#000"/>
                        <rect x="33" y="0" width="2" height="32" fill="#000"/>
                        <rect x="37" y="0" width="1" height="32" fill="#000"/>
                        <rect x="40" y="0" width="4" height="32" fill="#000"/>
                        <rect x="46" y="0" width="2" height="32" fill="#000"/>
                        <rect x="50" y="0" width="3" height="32" fill="#000"/>
                        <rect x="55" y="0" width="1" height="32" fill="#000"/>
                        <rect x="58" y="0" width="4" height="32" fill="#000"/>
                        <rect x="64" y="0" width="2" height="32" fill="#000"/>
                        <rect x="68" y="0" width="3" height="32" fill="#000"/>
                        <rect x="73" y="0" width="2" height="32" fill="#000"/>
                        <rect x="77" y="0" width="1" height="32" fill="#000"/>
                        <rect x="80" y="0" width="4" height="32" fill="#000"/>
                        <rect x="86" y="0" width="2" height="32" fill="#000"/>
                        <rect x="90" y="0" width="3" height="32" fill="#000"/>
                        <rect x="95" y="0" width="2" height="32" fill="#000"/>
                        <rect x="99" y="0" width="4" height="32" fill="#000"/>
                        <rect x="105" y="0" width="1" height="32" fill="#000"/>
                        <rect x="108" y="0" width="3" height="32" fill="#000"/>
                        <rect x="113" y="0" width="2" height="32" fill="#000"/>
                        <rect x="117" y="0" width="4" height="32" fill="#000"/>
                        <rect x="123" y="0" width="2" height="32" fill="#000"/>
                        <rect x="127" y="0" width="3" height="32" fill="#000"/>
                        <rect x="132" y="0" width="1" height="32" fill="#000"/>
                        <rect x="135" y="0" width="4" height="32" fill="#000"/>
                        <rect x="141" y="0" width="2" height="32" fill="#000"/>
                        <rect x="145" y="0" width="3" height="32" fill="#000"/>
                        <rect x="150" y="0" width="2" height="32" fill="#000"/>
                        <rect x="154" y="0" width="1" height="32" fill="#000"/>
                        <rect x="157" y="0" width="4" height="32" fill="#000"/>
                        <rect x="163" y="0" width="2" height="32" fill="#000"/>
                        <rect x="167" y="0" width="3" height="32" fill="#000"/>
                        <rect x="172" y="0" width="1" height="32" fill="#000"/>
                        <rect x="175" y="0" width="4" height="32" fill="#000"/>
                    </svg>
                    <div class="barcode-label">*VTA-{{ str_pad($venta->id, 6, '0', STR_PAD_LEFT) }}*</div>
                </div>
            </div>

            <!-- COLUMNA DERECHA: CUADRO FINANCIERO DE TOTALES -->
            <div class="footer-right-col">
                <table class="totals-summary-table">
                    <tr>
                        <td class="totals-label">Subtotal Artículos:</td>
                        <td class="totals-val">Bs. {{ number_format($venta->total, 2) }}</td>
                    </tr>
                    <tr>
                        <td class="totals-label">Total Unidades:</td>
                        <td class="totals-val">{{ $totalUnidades }} uds.</td>
                    </tr>
                    <tr>
                        <td class="totals-label">Descuento Comercial:</td>
                        <td class="totals-val">Bs. 0.00</td>
                    </tr>
                    <tr>
                        <td class="totals-label">Forma de Pago:</td>
                        <td class="totals-val" style="color: #059669; font-weight: 800;">CONTADO / EFECTIVO / QR</td>
                    </tr>
                    <tr class="row-total-destacado">
                        <td class="totals-label" style="font-size: 13px;">TOTAL A PAGAR:</td>
                        <td class="totals-val total-amount-grand">Bs. {{ number_format($venta->total, 2) }}</td>
                    </tr>
                </table>
            </div>

        </div>

        <!-- 5. FIRMAS DE CONFORMIDAD HORIZONTALES -->
        <div class="signatures-horizontal-row">
            <div class="sig-box">
                <div class="sig-line"></div>
                <div class="sig-label">Entregué Conforme</div>
                <div class="sig-sub">Tienda Isoatlas &bull; {{ $cajeroNombre }}</div>
            </div>
            <div class="sig-box">
                <div class="sig-line"></div>
                <div class="sig-label">Recibí Conforme</div>
                <div class="sig-sub">Firma del Cliente / Aceptación</div>
            </div>
        </div>

    </main>

</body>

</html>
