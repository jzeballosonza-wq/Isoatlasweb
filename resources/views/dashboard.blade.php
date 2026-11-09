<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Gestión | Tienda Isoatlas</title>

    <!-- Google Font: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- AdminLTE -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">

    <!-- Bootstrap 5 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">

    <!-- FontAwesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        :root {
            --font-main: 'Plus Jakarta Sans', sans-serif;
            --sidebar-bg: #0f172a;
            --sidebar-active: #6366f1;
            --sidebar-hover: #1e293b;
            --content-bg: #f8fafc;
            --card-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.04), 0 8px 10px -6px rgba(0, 0, 0, 0.04);
            --card-shadow-hover: 0 20px 25px -5px rgba(0, 0, 0, 0.08), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            --primary-gradient: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            --success-gradient: linear-gradient(135deg, #059669 0%, #10b981 100%);
            --warning-gradient: linear-gradient(135deg, #d97706 0%, #f59e0b 100%);
            --danger-gradient: linear-gradient(135deg, #dc2626 0%, #ef4444 100%);
            --info-gradient: linear-gradient(135deg, #0284c7 0%, #38bdf8 100%);
            --purple-gradient: linear-gradient(135deg, #7c3aed 0%, #a855f7 100%);
        }

        body {
            font-family: var(--font-main);
            background-color: var(--content-bg);
            color: #334155;
            -webkit-font-smoothing: antialiased;
        }

        .content-wrapper {
            background-color: var(--content-bg);
            min-height: 100vh;
        }

        /* NAVBAR */
        .main-header.navbar {
            background: #ffffff !important;
            border-bottom: 1px solid #e2e8f0;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.02);
            padding: 0.6rem 1.25rem;
        }

        .navbar-light .navbar-nav .nav-link {
            color: #475569;
            font-weight: 500;
        }

        /* SIDEBAR */
        .main-sidebar {
            background: var(--sidebar-bg) !important;
            border-right: 1px solid #1e293b;
        }

        .brand-link {
            border-bottom: 1px solid #1e293b !important;
            padding: 1.1rem 1.25rem;
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none !important;
        }

        .brand-icon-box {
            width: 38px;
            height: 38px;
            background: var(--primary-gradient);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            font-size: 1.1rem;
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.35);
        }

        .brand-text {
            color: #f8fafc !important;
            font-weight: 700;
            font-size: 1.15rem;
            letter-spacing: -0.02em;
        }

        .user-panel {
            background: rgba(30, 41, 59, 0.6);
            border-radius: 12px;
            padding: 12px;
            margin: 15px 10px;
            border: 1px solid rgba(255, 255, 255, 0.05) !important;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .user-avatar-badge {
            width: 42px;
            height: 42px;
            border-radius: 10px;
            background: linear-gradient(135deg, #6366f1 0%, #3b82f6 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            color: #ffffff;
            font-size: 1.1rem;
        }

        .user-info-text {
            overflow: hidden;
        }

        .user-name {
            color: #f8fafc;
            font-weight: 600;
            font-size: 0.9rem;
            margin-bottom: 2px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .user-role-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            font-size: 0.72rem;
            color: #94a3b8;
        }

        .user-role-badge .status-dot {
            width: 7px;
            height: 7px;
            background: #10b981;
            border-radius: 50%;
            display: inline-block;
        }

        /* SIDEBAR NAV */
        .nav-header {
            color: #64748b !important;
            font-size: 0.7rem !important;
            font-weight: 700 !important;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            padding: 1.2rem 1rem 0.4rem 1rem !important;
        }

        .nav-sidebar .nav-item {
            margin-bottom: 4px;
            padding: 0 8px;
        }

        .nav-sidebar .nav-link {
            color: #94a3b8 !important;
            border-radius: 10px;
            padding: 9px 14px;
            font-size: 0.88rem;
            font-weight: 500;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
        }

        .nav-sidebar .nav-link i.nav-icon {
            font-size: 0.95rem;
            margin-right: 12px;
            width: 30px;
            height: 30px;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.25s ease;
            flex-shrink: 0;
        }

        .nav-sidebar .nav-link:hover i.nav-icon {
            transform: scale(1.12);
        }

        .nav-sidebar .nav-link.active i.nav-icon {
            color: #ffffff !important;
            background: rgba(255, 255, 255, 0.22) !important;
        }

        /* 🎨 Colores vibrantes y badges para los logos de cada módulo */
        .icon-dashboard   { color: #818cf8 !important; background: rgba(99, 102, 241, 0.18); }
        .icon-ventas      { color: #34d399 !important; background: rgba(16, 185, 129, 0.18); }
        .icon-compras     { color: #60a5fa !important; background: rgba(59, 130, 246, 0.18); }
        .icon-clientes    { color: #22d3ee !important; background: rgba(6, 182, 212, 0.18); }
        .icon-proveedores { color: #fbbf24 !important; background: rgba(245, 158, 11, 0.18); }
        .icon-caja        { color: #facc15 !important; background: rgba(234, 179, 8, 0.22); }
        .icon-productos, .icon-laptops { color: #c084fc !important; background: rgba(168, 85, 247, 0.18); }
        .icon-inventario  { color: #2dd4bf !important; background: rgba(13, 148, 136, 0.18); }
        .icon-movimientos { color: #38bdf8 !important; background: rgba(14, 165, 233, 0.18); }
        .icon-costos      { color: #fb923c !important; background: rgba(249, 115, 22, 0.18); }
        .icon-categorias  { color: #f472b6 !important; background: rgba(236, 72, 153, 0.18); }
        .icon-usuarios    { color: #60a5fa !important; background: rgba(59, 130, 246, 0.18); }
        .icon-roles       { color: #a78bfa !important; background: rgba(139, 92, 246, 0.18); }
        .icon-bitacoras   { color: #cbd5e1 !important; background: rgba(148, 163, 184, 0.18); }
        .icon-backups     { color: #38bdf8 !important; background: rgba(2, 132, 199, 0.18); }

        /* =================================================== */
        /* 📂 SIDEBAR DESPLEGABLE / TREEVIEW ACCORDION */
        /* =================================================== */
        .nav-sidebar .nav-item.has-treeview {
            margin-bottom: 4px;
        }

        .nav-sidebar .nav-item.has-treeview > .nav-link {
            cursor: pointer;
            user-select: none;
            display: flex;
            align-items: center;
            border-radius: 10px;
            transition: all 0.25s ease;
        }

        .nav-sidebar .nav-item.has-treeview > .nav-link p {
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
            margin: 0;
            font-weight: 600;
        }

        .nav-sidebar .nav-item.has-treeview > .nav-link .tree-arrow {
            transition: transform 0.28s ease, color 0.2s ease;
            font-size: 0.8rem;
            color: #64748b;
            margin-left: auto;
        }

        .nav-sidebar .nav-item.has-treeview.menu-open > .nav-link .tree-arrow {
            transform: rotate(-90deg);
            color: #c7d2fe;
        }

        .nav-sidebar .nav-item.has-treeview.menu-open > .nav-link {
            background: rgba(255, 255, 255, 0.05);
            color: #f8fafc !important;
        }

        .nav-sidebar .nav-item.has-treeview > .nav-link.active {
            background: rgba(99, 102, 241, 0.16) !important;
            border-left: 3px solid #818cf8;
            color: #ffffff !important;
        }

        .nav-sidebar .nav-treeview {
            padding: 3px 0 6px 0;
            margin: 4px 0 6px 20px;
            list-style: none;
            border-left: 2px dashed rgba(148, 163, 184, 0.22);
            display: none;
        }

        .nav-sidebar .nav-item.has-treeview.menu-open > .nav-treeview {
            display: block;
        }

        .nav-sidebar .nav-treeview .nav-item {
            padding: 0;
            margin-bottom: 2px;
        }

        .nav-sidebar .nav-treeview .nav-link {
            padding: 7px 12px 7px 14px;
            font-size: 0.83rem;
            color: #94a3b8 !important;
            border-radius: 8px;
            display: flex;
            align-items: center;
            gap: 9px;
            transition: all 0.2s ease;
        }

        .nav-sidebar .nav-treeview .nav-link:hover {
            color: #ffffff !important;
            background: rgba(255, 255, 255, 0.08);
            transform: translateX(3px);
        }

        .nav-sidebar .nav-treeview .nav-link.active {
            color: #ffffff !important;
            background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%) !important;
            box-shadow: 0 4px 14px rgba(99, 102, 241, 0.35);
            font-weight: 600;
        }

        .nav-sidebar .nav-treeview .nav-link .sub-bullet {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: rgba(148, 163, 184, 0.6);
            display: inline-block;
            transition: all 0.2s ease;
            flex-shrink: 0;
        }

        .nav-sidebar .nav-treeview .nav-link:hover .sub-bullet {
            background: #818cf8;
            transform: scale(1.3);
        }

        .nav-sidebar .nav-treeview .nav-link.active .sub-bullet {
            background: #ffffff;
            box-shadow: 0 0 6px rgba(255, 255, 255, 0.8);
        }

        .sidebar-badge-pill {
            font-size: 0.68rem;
            padding: 2px 7px;
            border-radius: 20px;
            font-weight: 700;
            letter-spacing: 0.03em;
            margin-left: auto;
        }

        .logout-box {
            padding: 15px;
            margin-top: 20px;
            margin-bottom: 15px;
        }

        .btn-logout {
            background: rgba(239, 68, 68, 0.12);
            color: #f87171;
            border: 1px solid rgba(239, 68, 68, 0.2);
            border-radius: 10px;
            padding: 10px;
            font-weight: 600;
            font-size: 0.85rem;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            width: 100%;
        }

        .btn-logout:hover {
            background: #ef4444;
            color: #ffffff;
            border-color: #ef4444;
        }

        /* HERO WELCOME CARD */
        .hero-banner {
            background: linear-gradient(135deg, #1e1b4b 0%, #312e81 40%, #4338ca 100%);
            border-radius: 20px;
            padding: 30px;
            color: #ffffff;
            position: relative;
            overflow: hidden;
            box-shadow: 0 20px 30px -10px rgba(67, 56, 202, 0.35);
            margin-bottom: 25px;
        }

        .hero-banner::after {
            content: '';
            position: absolute;
            top: -40px;
            right: -40px;
            width: 250px;
            height: 250px;
            background: radial-gradient(circle, rgba(129, 140, 248, 0.25) 0%, rgba(255, 255, 255, 0) 70%);
            border-radius: 50%;
            pointer-events: none;
        }

        .hero-title {
            font-size: 1.65rem;
            font-weight: 800;
            letter-spacing: -0.03em;
            margin-bottom: 6px;
        }

        .hero-subtitle {
            color: #c7d2fe;
            font-size: 0.95rem;
            margin-bottom: 18px;
            max-width: 600px;
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: rgba(255, 255, 255, 0.12);
            backdrop-filter: blur(10px);
            padding: 5px 12px;
            border-radius: 50px;
            font-size: 0.75rem;
            font-weight: 600;
            color: #e0e7ff;
            border: 1px solid rgba(255, 255, 255, 0.15);
        }

        .hero-actions {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .btn-hero-primary {
            background: #ffffff;
            color: #4338ca;
            font-weight: 700;
            font-size: 0.85rem;
            padding: 9px 18px;
            border-radius: 10px;
            border: none;
            transition: all 0.2s ease;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-hero-primary:hover {
            background: #f8fafc;
            transform: translateY(-2px);
            color: #3730a3;
        }

        .btn-hero-secondary {
            background: rgba(255, 255, 255, 0.15);
            color: #ffffff;
            font-weight: 600;
            font-size: 0.85rem;
            padding: 9px 16px;
            border-radius: 10px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(8px);
            transition: all 0.2s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-hero-secondary:hover {
            background: rgba(255, 255, 255, 0.25);
            color: #ffffff;
            transform: translateY(-2px);
        }

        /* KPI STAT CARDS */
        .stat-card {
            background: #ffffff;
            border-radius: 16px;
            padding: 22px;
            box-shadow: var(--card-shadow);
            border: 1px solid #f1f5f9;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
            margin-bottom: 24px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--card-shadow-hover);
        }

        .stat-card-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 12px;
        }

        .stat-label {
            font-size: 0.8rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #64748b;
            margin-bottom: 4px;
        }

        .stat-value {
            font-size: 1.75rem;
            font-weight: 800;
            color: #0f172a;
            line-height: 1.2;
            letter-spacing: -0.03em;
        }

        .stat-icon-wrapper {
            width: 50px;
            height: 50px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.35rem;
            color: #ffffff;
            flex-shrink: 0;
        }

        .stat-footer {
            margin-top: 14px;
            padding-top: 12px;
            border-top: 1px solid #f8fafc;
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 0.78rem;
            color: #64748b;
        }

        .stat-pill {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 3px 8px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.72rem;
        }

        .stat-pill-success {
            background: #ecfdf5;
            color: #059669;
        }

        .stat-pill-warning {
            background: #fffbeb;
            color: #d97706;
        }

        .stat-pill-danger {
            background: #fef2f2;
            color: #dc2626;
        }

        .stat-pill-info {
            background: #eff6ff;
            color: #2563eb;
        }

        /* CUSTOM MODERN CARDS */
        .dash-card {
            background: #ffffff;
            border-radius: 16px;
            border: 1px solid #f1f5f9;
            box-shadow: var(--card-shadow);
            margin-bottom: 24px;
            overflow: hidden;
        }

        .dash-card-header {
            padding: 18px 22px;
            border-bottom: 1px solid #f1f5f9;
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: #ffffff;
        }

        .dash-card-title {
            font-size: 1rem;
            font-weight: 700;
            color: #1e293b;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .dash-card-title i {
            color: #6366f1;
        }

        .dash-card-body {
            padding: 22px;
        }

        /* MODERN TABLES */
        .table-dash {
            margin-bottom: 0;
        }

        .table-dash th {
            font-size: 0.72rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #64748b;
            background: #f8fafc;
            border-top: none;
            border-bottom: 1px solid #e2e8f0;
            padding: 10px 14px;
        }

        .table-dash td {
            padding: 12px 14px;
            vertical-align: middle;
            font-size: 0.85rem;
            color: #334155;
            border-bottom: 1px solid #f1f5f9;
        }

        .table-dash tr:last-child td {
            border-bottom: none;
        }

        .table-dash tr:hover td {
            background-color: #f8fafc;
        }

        /* QUICK LAUNCHPAD */
        .quick-tile {
            background: #ffffff;
            border-radius: 14px;
            border: 1px solid #f1f5f9;
            padding: 16px 14px;
            text-align: center;
            text-decoration: none !important;
            color: #334155 !important;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 10px;
            transition: all 0.25s ease;
            box-shadow: 0 2px 8px rgba(0,0,0,0.02);
            height: 100%;
        }

        .quick-tile:hover {
            transform: translateY(-4px);
            border-color: #cbd5e1;
            box-shadow: 0 10px 20px -5px rgba(0,0,0,0.06);
            color: #4f46e5 !important;
        }

        .quick-tile-icon {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            color: #ffffff;
            transition: all 0.2s ease;
        }

        .quick-tile:hover .quick-tile-icon {
            transform: scale(1.1);
        }

        .quick-tile-title {
            font-size: 0.8rem;
            font-weight: 600;
            margin: 0;
        }

        /* TIMELINE BITACORA */
        .timeline-item-custom {
            display: flex;
            gap: 14px;
            padding-bottom: 16px;
            margin-bottom: 16px;
            border-bottom: 1px dashed #e2e8f0;
        }

        .timeline-item-custom:last-child {
            border-bottom: none;
            padding-bottom: 0;
            margin-bottom: 0;
        }

        .timeline-icon-box {
            width: 34px;
            height: 34px;
            border-radius: 10px;
            background: #f1f5f9;
            color: #475569;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.85rem;
            flex-shrink: 0;
        }

        .timeline-content {
            flex-grow: 1;
        }

        .timeline-title {
            font-size: 0.84rem;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 2px;
        }

        .timeline-desc {
            font-size: 0.76rem;
            color: #64748b;
            margin-bottom: 4px;
        }

        .timeline-time {
            font-size: 0.7rem;
            color: #94a3b8;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        /* CLOCK & LIVE BADGE */
        .live-clock {
            background: #f1f5f9;
            border-radius: 8px;
            padding: 6px 14px;
            font-size: 0.8rem;
            font-weight: 600;
            color: #475569;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
    </style>
</head>

<body class="hold-transition sidebar-mini layout-fixed">

<div class="wrapper">

    <!-- NAVBAR -->
    <nav class="main-header navbar navbar-expand navbar-light">

        <!-- Left navbar links -->
        <ul class="navbar-nav align-items-center">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button">
                    <i class="fas fa-bars"></i>
                </a>
            </li>
            <li class="nav-item d-none d-sm-inline-block ps-2">
                <span class="fw-semibold text-secondary" style="font-size: 0.9rem;">
                    @if(request()->routeIs('dashboard'))
                        Panel de Control
                    @else
                        Módulo de Administración
                    @endif
                </span>
            </li>
        </ul>

        <!-- Right navbar links -->
        <ul class="navbar-nav ms-auto align-items-center gap-2">

            <!-- Live Clock Widget -->
            <li class="nav-item d-none d-md-block">
                <div class="live-clock" id="clockWidget">
                    <i class="far fa-clock text-primary"></i>
                    <span id="liveTime">--:--:--</span>
                    <span class="text-muted">|</span>
                    <span id="liveDate">--/--/----</span>
                </div>
            </li>

            @if(auth()->user()->tienePermiso('ventas'))
            <li class="nav-item d-none d-sm-block">
                <a href="{{ route('ventas.create') }}" class="btn btn-sm btn-primary rounded-pill px-3 fw-bold" style="background: var(--primary-gradient); border: none;">
                    <i class="fas fa-plus-circle me-1"></i> Nueva Venta
                </a>
            </li>
            @endif

            <!-- User Dropdown Menu -->
            <li class="nav-item dropdown">
                <a class="nav-link d-flex align-items-center gap-2" data-bs-toggle="dropdown" href="#" style="cursor: pointer;">
                    <div style="width: 32px; height: 32px; border-radius: 8px; background: var(--primary-gradient); color: #fff; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.85rem;">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <span class="d-none d-md-inline fw-semibold text-dark" style="font-size: 0.88rem;">
                        {{ auth()->user()->name }}
                    </span>
                    <i class="fas fa-chevron-down text-muted" style="font-size: 0.7rem;"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-end shadow-sm border-0 rounded-3 mt-2" style="min-width: 200px;">
                    <div class="px-3 py-2 border-bottom">
                        <p class="mb-0 fw-bold text-dark" style="font-size: 0.85rem;">{{ auth()->user()->name }}</p>
                        <small class="text-muted">{{ auth()->user()->email }}</small>
                    </div>
                    <a href="{{ route('dashboard') }}" class="dropdown-item py-2" style="font-size: 0.85rem;">
                        <i class="fas fa-home me-2 text-primary"></i> Panel Principal
                    </a>
                    <div class="dropdown-divider my-1"></div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="dropdown-item py-2 text-danger fw-semibold" style="font-size: 0.85rem;">
                            <i class="fas fa-sign-out-alt me-2"></i> Cerrar Sesión
                        </button>
                    </form>
                </div>
            </li>

        </ul>
    </nav>

    <!-- SIDEBAR -->
    <aside class="main-sidebar sidebar-dark-primary elevation-2">

        <!-- LOGO BRAND -->
        <a href="{{ route('dashboard') }}" class="brand-link">
            <div class="brand-icon-box">
                <i class="fas fa-plug-circle-bolt"></i>
            </div>
            <div>
                <span class="brand-text d-block">Tienda Isoatlas</span>
                <small style="color: #64748b; font-size: 0.7rem; font-weight: 600; display: block; margin-top: -2px;">ACCESORIOS & ELECTRÓNICA</small>
            </div>
        </a>

        <!-- SIDEBAR CONTAINER -->
        <div class="sidebar">

            <!-- USER PANEL -->
            <div class="user-panel">
                <div class="user-avatar-badge">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <div class="user-info-text">
                    <div class="user-name" title="{{ auth()->user()->name }}">
                        {{ auth()->user()->name }}
                    </div>
                    <div class="user-role-badge">
                        <span class="status-dot"></span>
                        <span>{{ auth()->user()->rol->nombre ?? 'Usuario Activo' }}</span>
                    </div>
                </div>
            </div>

            <!-- MENU -->
            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

                    <!-- 1. DASHBOARD -->
                    <li class="nav-item">
                        <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-th-large icon-dashboard"></i>
                            <p>Panel Principal</p>
                        </a>
                    </li>

                    <!-- 2. VENTAS & FACTURACIÓN (DESPLEGABLE) -->
                    @if(auth()->user()->esAdmin() || auth()->user()->tienePermiso('ventas') || auth()->user()->tienePermiso('clientes'))
                    @php
                        $ventasOpen = request()->routeIs('ventas.*') || request()->routeIs('clientes.*');
                    @endphp
                    <li class="nav-item has-treeview {{ $ventasOpen ? 'menu-open' : '' }}">
                        <a href="javascript:void(0)" class="nav-link {{ $ventasOpen ? 'active' : '' }}">
                            <i class="nav-icon fas fa-cash-register icon-ventas"></i>
                            <p>
                                <span>Ventas & Clientes</span>
                                <i class="right fas fa-angle-left tree-arrow"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview" style="{{ $ventasOpen ? 'display: block;' : '' }}">
                            @if(auth()->user()->esAdmin() || auth()->user()->tienePermiso('ventas'))
                            <li class="nav-item">
                                <a href="{{ route('ventas.create') }}" class="nav-link {{ request()->routeIs('ventas.create') ? 'active' : '' }}">
                                    <span class="sub-bullet"></span>
                                    <p>Nueva Venta <span class="sidebar-badge-pill bg-success text-white">POS</span></p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('ventas.index') }}" class="nav-link {{ request()->routeIs('ventas.index') || request()->routeIs('ventas.show') || request()->routeIs('ventas.edit') ? 'active' : '' }}">
                                    <span class="sub-bullet"></span>
                                    <p>Historial de Ventas</p>
                                </a>
                            </li>
                            @endif

                            @if(auth()->user()->esAdmin() || auth()->user()->tienePermiso('clientes'))
                            <li class="nav-item">
                                <a href="{{ route('clientes.index') }}" class="nav-link {{ request()->routeIs('clientes.*') ? 'active' : '' }}">
                                    <span class="sub-bullet"></span>
                                    <p>Directorio de Clientes</p>
                                </a>
                            </li>
                            @endif
                        </ul>
                    </li>
                    @endif

                    <!-- 3. CAJA Y FINANZAS (DESPLEGABLE) -->
                    @if(auth()->user()->esAdmin() || auth()->user()->tienePermiso('caja'))
                    @php
                        $cajaOpen = request()->routeIs('caja.*');
                    @endphp
                    <li class="nav-item has-treeview {{ $cajaOpen ? 'menu-open' : '' }}">
                        <a href="javascript:void(0)" class="nav-link {{ $cajaOpen ? 'active' : '' }}">
                            <i class="nav-icon fas fa-vault icon-caja"></i>
                            <p>
                                <span>Arqueo & Caja</span>
                                <i class="right fas fa-angle-left tree-arrow"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview" style="{{ $cajaOpen ? 'display: block;' : '' }}">
                            <li class="nav-item">
                                <a href="{{ route('caja.index') }}" class="nav-link {{ request()->routeIs('caja.index') ? 'active' : '' }}">
                                    <span class="sub-bullet"></span>
                                    <p>Control y Cierre de Caja</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                    @endif

                    <!-- 4. INVENTARIO & PRODUCTOS (DESPLEGABLE) -->
                    @if(auth()->user()->esAdmin() || auth()->user()->tienePermiso('productos') || auth()->user()->tienePermiso('inventario') || auth()->user()->tienePermiso('categorias'))
                    @php
                        $invOpen = request()->routeIs('productos.*') || request()->routeIs('inventario.*') || request()->routeIs('categorias.*');
                    @endphp
                    <li class="nav-item has-treeview {{ $invOpen ? 'menu-open' : '' }}">
                        <a href="javascript:void(0)" class="nav-link {{ $invOpen ? 'active' : '' }}">
                            <i class="nav-icon fas fa-boxes-stacked icon-inventario"></i>
                            <p>
                                <span>Inventario & Stock</span>
                                <i class="right fas fa-angle-left tree-arrow"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview" style="{{ $invOpen ? 'display: block;' : '' }}">
                            @if(auth()->user()->esAdmin() || auth()->user()->tienePermiso('productos'))
                            <li class="nav-item">
                                <a href="{{ route('productos.index') }}" class="nav-link {{ request()->routeIs('productos.index') || request()->routeIs('productos.show') || request()->routeIs('productos.edit') ? 'active' : '' }}">
                                    <span class="sub-bullet"></span>
                                    <p>Catálogo de Productos</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('productos.create') }}" class="nav-link {{ request()->routeIs('productos.create') ? 'active' : '' }}">
                                    <span class="sub-bullet"></span>
                                    <p>Registrar Producto</p>
                                </a>
                            </li>
                            @endif

                            @if(auth()->user()->esAdmin() || auth()->user()->tienePermiso('inventario') || auth()->user()->tienePermiso('productos'))
                            <li class="nav-item">
                                <a href="{{ route('inventario.index') }}" class="nav-link {{ request()->routeIs('inventario.index') || request()->routeIs('inventario.kardex') || request()->routeIs('inventario.valorizado') ? 'active' : '' }}">
                                    <span class="sub-bullet"></span>
                                    <p>Kardex & Control Físico</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('inventario.movimientos') }}" class="nav-link {{ request()->routeIs('inventario.movimientos') || request()->routeIs('inventario.ingresos') || request()->routeIs('inventario.egresos') ? 'active' : '' }}">
                                    <span class="sub-bullet"></span>
                                    <p>Ingresos y Egresos</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('inventario.costos') }}" class="nav-link {{ request()->routeIs('inventario.costos') ? 'active' : '' }}">
                                    <span class="sub-bullet"></span>
                                    <p>Análisis de Costos</p>
                                </a>
                            </li>
                            @endif

                            @if(auth()->user()->esAdmin() || auth()->user()->tienePermiso('categorias'))
                            <li class="nav-item">
                                <a href="{{ route('categorias.index') }}" class="nav-link {{ request()->routeIs('categorias.*') ? 'active' : '' }}">
                                    <span class="sub-bullet"></span>
                                    <p>Categorías y Familias</p>
                                </a>
                            </li>
                            @endif
                        </ul>
                    </li>
                    @endif

                    <!-- 5. COMPRAS & PROVEEDORES (DESPLEGABLE) -->
                    @if(auth()->user()->esAdmin() || auth()->user()->tienePermiso('compras') || auth()->user()->tienePermiso('proveedores'))
                    @php
                        $comprasOpen = request()->routeIs('compras.*') || request()->routeIs('proveedores.*');
                    @endphp
                    <li class="nav-item has-treeview {{ $comprasOpen ? 'menu-open' : '' }}">
                        <a href="javascript:void(0)" class="nav-link {{ $comprasOpen ? 'active' : '' }}">
                            <i class="nav-icon fas fa-shopping-cart icon-compras"></i>
                            <p>
                                <span>Compras & Proveedores</span>
                                <i class="right fas fa-angle-left tree-arrow"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview" style="{{ $comprasOpen ? 'display: block;' : '' }}">
                            @if(auth()->user()->esAdmin() || auth()->user()->tienePermiso('compras'))
                            <li class="nav-item">
                                <a href="{{ route('compras.create') }}" class="nav-link {{ request()->routeIs('compras.create') ? 'active' : '' }}">
                                    <span class="sub-bullet"></span>
                                    <p>Nueva Compra</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('compras.index') }}" class="nav-link {{ request()->routeIs('compras.index') || request()->routeIs('compras.show') || request()->routeIs('compras.edit') ? 'active' : '' }}">
                                    <span class="sub-bullet"></span>
                                    <p>Historial de Compras</p>
                                </a>
                            </li>
                            @endif

                            @if(auth()->user()->tienePermiso('proveedores'))
                            <li class="nav-item">
                                <a href="{{ route('proveedores.index') }}" class="nav-link {{ request()->routeIs('proveedores.*') ? 'active' : '' }}">
                                    <span class="sub-bullet"></span>
                                    <p>Directorio Proveedores</p>
                                </a>
                            </li>
                            @endif
                        </ul>
                    </li>
                    @endif

                    <!-- 6. SISTEMA Y SEGURIDAD (DESPLEGABLE) -->
                    @if(auth()->user()->esAdmin() || auth()->user()->tienePermiso('usuarios') || auth()->user()->tienePermiso('roles') || auth()->user()->tienePermiso('bitacoras') || auth()->user()->tienePermiso('backups'))
                    @php
                        $segOpen = request()->routeIs('users.*') || request()->routeIs('roles.*') || request()->routeIs('bitacoras.*') || request()->routeIs('backups.*');
                    @endphp
                    <li class="nav-item has-treeview {{ $segOpen ? 'menu-open' : '' }}">
                        <a href="javascript:void(0)" class="nav-link {{ $segOpen ? 'active' : '' }}">
                            <i class="nav-icon fas fa-shield-alt icon-roles"></i>
                            <p>
                                <span>Sistema & Seguridad</span>
                                <i class="right fas fa-angle-left tree-arrow"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview" style="{{ $segOpen ? 'display: block;' : '' }}">
                            @if(auth()->user()->tienePermiso('usuarios'))
                            <li class="nav-item">
                                <a href="{{ route('users.index') }}" class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
                                    <span class="sub-bullet"></span>
                                    <p>Usuarios</p>
                                </a>
                            </li>
                            @endif

                            @if(auth()->user()->tienePermiso('roles'))
                            <li class="nav-item">
                                <a href="{{ route('roles.index') }}" class="nav-link {{ request()->routeIs('roles.*') ? 'active' : '' }}">
                                    <span class="sub-bullet"></span>
                                    <p>Roles y Permisos</p>
                                </a>
                            </li>
                            @endif

                            @if(auth()->user()->tienePermiso('bitacoras'))
                            <li class="nav-item">
                                <a href="{{ route('bitacoras.index') }}" class="nav-link {{ request()->routeIs('bitacoras.*') ? 'active' : '' }}">
                                    <span class="sub-bullet"></span>
                                    <p>Bitácora de Auditoría</p>
                                </a>
                            </li>
                            @endif

                            @if(auth()->user()->tienePermiso('backups'))
                            <li class="nav-item">
                                <a href="{{ route('backups.index') }}" class="nav-link {{ request()->routeIs('backups.*') ? 'active' : '' }}">
                                    <span class="sub-bullet"></span>
                                    <p>Copias de Seguridad</p>
                                </a>
                            </li>
                            @endif
                        </ul>
                    </li>
                    @endif

                </ul>
            </nav>

            <!-- LOGOUT BUTTON -->
            <div class="logout-box">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-logout">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Cerrar Sesión</span>
                    </button>
                </form>
            </div>

        </div>
    </aside>

    <!-- CONTENT WRAPPER -->
    <div class="content-wrapper">

        <section class="content py-4">

            @if(request()->routeIs('dashboard'))
            @php
                $userAuth = auth()->user();
                $esAdmin = $userAuth->esAdmin();

                // Permisos individuales
                $canVentas = $esAdmin || $userAuth->tienePermiso('ventas');
                $canCompras = $esAdmin || $userAuth->tienePermiso('compras');
                $canProductos = $esAdmin || $userAuth->tienePermiso('productos');
                $canInventario = $esAdmin || $userAuth->tienePermiso('inventario');
                $canCatalogo = $canProductos || $canInventario;
                $canCategorias = $esAdmin || $userAuth->tienePermiso('categorias') || $canProductos;
                $canBitacoras = $esAdmin || $userAuth->tienePermiso('bitacoras');

                // Conteo de tarjetas KPI visibles para adaptar dinámicamente las columnas
                $kpiCount = ($canVentas ? 1 : 0) + ($canCompras ? 1 : 0) + ($canCatalogo ? 1 : 0) + ($canCatalogo ? 1 : 0);
                $kpiColClass = match($kpiCount) {
                    1 => 'col-12 col-md-8 col-xl-6 mx-auto',
                    2 => 'col-12 col-md-6',
                    3 => 'col-12 col-md-4',
                    default => 'col-xl-3 col-md-6',
                };

                // Control de visibilidad de secciones y gráficos
                $showGraficoComercial = $canVentas || $canCompras;
                $showGraficoCategorias = $canCategorias;
                $hasChartsRow = $showGraficoComercial || $showGraficoCategorias;

                $showStockCritico = $canCatalogo;
                $showUltimasVentas = $canVentas;
                $hasOperativeTablesRow = $showStockCritico || $showUltimasVentas;

                $showUltimasCompras = $canCompras;
                $showBitacora = $canBitacoras;
                $hasComprasBitacoraRow = $showUltimasCompras || $showBitacora;
            @endphp

            <div class="container-fluid px-3 px-md-4">

                <!-- 1. HERO BANNER -->
                <div class="hero-banner">
                    <div class="row align-items-center">
                        <div class="{{ $canVentas ? 'col-lg-8' : 'col-lg-12' }}">
                            <div class="hero-badge mb-2">
                                <i class="fas fa-bolt text-warning"></i>
                                <span>Panel de Control - Isoatlas Tech</span>
                            </div>
                            <h1 class="hero-title">
                                ¡Bienvenido de nuevo, {{ auth()->user()->name }}! 👋
                            </h1>
                            <p class="hero-subtitle">
                                Monitorea tus ventas, control de inventario de accesorios y periféricos electrónicos, compras a proveedores y métricas comerciales en tiempo real.
                            </p>

                            <div class="hero-actions">
                                @if($canVentas)
                                <a href="{{ route('ventas.create') }}" class="btn-hero-primary">
                                    <i class="fas fa-cash-register"></i> Realizar Venta
                                </a>
                                @endif

                                @if($canProductos)
                                <a href="{{ route('productos.create') }}" class="btn-hero-secondary">
                                    <i class="fas fa-plus-circle"></i> Nuevo Producto
                                </a>
                                @endif

                                @if($canCompras)
                                <a href="{{ route('compras.create') }}" class="btn-hero-secondary">
                                    <i class="fas fa-shopping-cart"></i> Registrar Compra
                                </a>
                                @endif
                            </div>
                        </div>

                        @if($canVentas)
                        <div class="col-lg-4 text-center text-lg-end d-none d-lg-block">
                            <div style="background: rgba(255, 255, 255, 0.08); border-radius: 18px; padding: 18px 24px; display: inline-block; border: 1px solid rgba(255, 255, 255, 0.15); backdrop-filter: blur(8px);">
                                <div class="text-white-50" style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em; font-weight: 700;">Ventas del Día</div>
                                <div class="text-white fw-bold my-1" style="font-size: 1.8rem;">
                                    Bs. {{ number_format($ventasHoyMonto ?? 0, 2) }}
                                </div>
                                <div class="text-white-50" style="font-size: 0.8rem;">
                                    <i class="fas fa-receipt me-1 text-success"></i> {{ $ventasHoyCantidad ?? 0 }} comprobantes emitidos hoy
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- 2. KPI STAT CARDS (Condicionados por permiso) -->
                @if($kpiCount > 0)
                <div class="row g-4 mb-4">
                    <!-- Total Ventas -->
                    @if($canVentas)
                    <div class="{{ $kpiColClass }}">
                        <div class="stat-card">
                            <div class="stat-card-header">
                                <div>
                                    <div class="stat-label">Total Ventas (Ingresos)</div>
                                    <div class="stat-value text-success">
                                        Bs. {{ number_format($montoTotalVentas ?? 0, 2) }}
                                    </div>
                                </div>
                                <div class="stat-icon-wrapper" style="background: var(--success-gradient); box-shadow: 0 6px 15px rgba(16, 185, 129, 0.35);">
                                    <i class="fas fa-wallet"></i>
                                </div>
                            </div>
                            <div class="stat-footer">
                                <span><i class="fas fa-file-invoice text-muted me-1"></i> {{ $totalVentas ?? 0 }} transacciones</span>
                                <a href="{{ route('ventas.index') }}" class="stat-pill stat-pill-success text-decoration-none">
                                    Ver ventas <i class="fas fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Total Compras -->
                    @if($canCompras)
                    <div class="{{ $kpiColClass }}">
                        <div class="stat-card">
                            <div class="stat-card-header">
                                <div>
                                    <div class="stat-label">Total Compras (Inversión)</div>
                                    <div class="stat-value text-primary">
                                        Bs. {{ number_format($montoTotalCompras ?? 0, 2) }}
                                    </div>
                                </div>
                                <div class="stat-icon-wrapper" style="background: var(--primary-gradient); box-shadow: 0 6px 15px rgba(79, 70, 229, 0.35);">
                                    <i class="fas fa-shopping-bag"></i>
                                </div>
                            </div>
                            <div class="stat-footer">
                                <span><i class="fas fa-truck text-muted me-1"></i> {{ $totalCompras ?? 0 }} pedidos</span>
                                <a href="{{ route('compras.index') }}" class="stat-pill stat-pill-info text-decoration-none">
                                    Ver compras <i class="fas fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Inventario / Accesorios -->
                    @if($canCatalogo)
                    <div class="{{ $kpiColClass }}">
                        <div class="stat-card">
                            <div class="stat-card-header">
                                <div>
                                    <div class="stat-label">Catálogo & Stock</div>
                                    <div class="stat-value text-dark">
                                        {{ $totalProductos ?? 0 }} <span style="font-size: 0.95rem; font-weight: 600; color: #64748b;">artículos</span>
                                    </div>
                                </div>
                                <div class="stat-icon-wrapper" style="background: var(--purple-gradient); box-shadow: 0 6px 15px rgba(124, 58, 237, 0.35);">
                                    <i class="fas fa-boxes-stacked"></i>
                                </div>
                            </div>
                            <div class="stat-footer">
                                <span><i class="fas fa-boxes text-muted me-1"></i> {{ $stockTotalUnidades ?? 0 }} unidades en stock</span>
                                <a href="{{ route('productos.index') }}" class="stat-pill stat-pill-info text-decoration-none">
                                    Catálogo <i class="fas fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Alertas de Stock Bajo -->
                    <div class="{{ $kpiColClass }}">
                        <div class="stat-card">
                            <div class="stat-card-header">
                                <div>
                                    <div class="stat-label">Alerta Stock Bajo</div>
                                    <div class="stat-value {{ ($totalStockBajo ?? 0) > 0 ? 'text-danger' : 'text-success' }}">
                                        {{ $totalStockBajo ?? 0 }} <span style="font-size: 0.95rem; font-weight: 600; color: #64748b;">artículos</span>
                                    </div>
                                </div>
                                <div class="stat-icon-wrapper" style="background: {{ ($totalStockBajo ?? 0) > 0 ? 'var(--danger-gradient)' : 'var(--success-gradient)' }}; box-shadow: 0 6px 15px rgba(239, 68, 68, 0.35);">
                                    <i class="fas {{ ($totalStockBajo ?? 0) > 0 ? 'fa-exclamation-triangle' : 'fa-check-circle' }}"></i>
                                </div>
                            </div>
                            <div class="stat-footer">
                                <span>{{ ($totalStockBajo ?? 0) > 0 ? 'Requiere reposición inmediata' : 'Niveles de stock óptimos' }}</span>
                                <a href="{{ route('productos.index') }}" class="stat-pill {{ ($totalStockBajo ?? 0) > 0 ? 'stat-pill-danger' : 'stat-pill-success' }} text-decoration-none">
                                    Revisar <i class="fas fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
                @endif

                <!-- 3. CHARTS ROW (Evolución Comercial y Modelos por Categoría) -->
                @if($hasChartsRow)
                <div class="row g-4 mb-4">
                    <!-- Balance Ventas vs Compras: Requiere permiso de Ventas o Compras -->
                    @if($showGraficoComercial)
                    <div class="{{ $showGraficoCategorias ? 'col-lg-8' : 'col-12' }}">
                        <div class="dash-card">
                            <div class="dash-card-header">
                                <h3 class="dash-card-title">
                                    <i class="fas fa-chart-line text-primary"></i>
                                    Evolución Comercial (Últimos 6 Meses)
                                </h3>
                                <span class="badge bg-light text-secondary border px-2 py-1">Ventas vs Compras (Bs.)</span>
                            </div>
                            <div class="dash-card-body">
                                <div style="position: relative; height: 300px; width: 100%;">
                                    <canvas id="ventasComprasChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Distribución por Categorías: Requiere permiso de Categorías o Productos -->
                    @if($showGraficoCategorias)
                    <div class="{{ $showGraficoComercial ? 'col-lg-4' : 'col-12 col-lg-8 mx-auto' }}">
                        <div class="dash-card">
                            <div class="dash-card-header">
                                <h3 class="dash-card-title">
                                    <i class="fas fa-chart-pie text-purple"></i>
                                    Modelos por Categoría
                                </h3>
                                <span class="badge bg-light text-secondary border px-2 py-1">Top Categorías</span>
                            </div>
                            <div class="dash-card-body">
                                <div style="position: relative; height: 300px; width: 100%;">
                                    <canvas id="categoriasDoughnutChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
                @endif

                <!-- 4. OPERATIONAL TABLES ROW -->
                @if($hasOperativeTablesRow)
                <div class="row g-4 mb-4">

                    <!-- Productos con Stock Crítico: Requiere permiso de Catálogo / Inventario -->
                    @if($showStockCritico)
                    <div class="{{ $showUltimasVentas ? 'col-lg-6' : 'col-12' }}">
                        <div class="dash-card">
                            <div class="dash-card-header">
                                <h3 class="dash-card-title">
                                    <i class="fas fa-exclamation-circle text-danger"></i>
                                    Productos con Stock Crítico / Bajo
                                </h3>
                                @if($canProductos)
                                <a href="{{ route('productos.index') }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3" style="font-size: 0.75rem;">
                                    Ver Todo
                                </a>
                                @endif
                            </div>
                            <div class="dash-card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-dash align-middle">
                                        <thead>
                                            <tr>
                                                <th>Producto / Código</th>
                                                <th>Categoría</th>
                                                <th class="text-center">Stock Actual</th>
                                                <th class="text-end">Acción</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($productosBajoStock ?? [] as $prod)
                                             <tr>
                                                <td>
                                                    <div class="d-flex align-items-center gap-2">
                                                        <div style="width: 36px; height: 36px; border-radius: 8px; background: #f1f5f9; display: flex; align-items: center; justify-content: center; overflow: hidden;">
                                                            @if($prod->imagen)
                                                                <img src="{{ asset('storage/' . $prod->imagen) }}" alt="" style="width: 100%; height: 100%; object-fit: cover;">
                                                            @else
                                                                <i class="fas fa-box-open text-muted"></i>
                                                            @endif
                                                        </div>
                                                        <div>
                                                            <div class="fw-bold text-dark" style="font-size: 0.85rem;">{{ $prod->nombre }}</div>
                                                            <small class="text-muted">Cod: {{ $prod->codigo }}</small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="badge bg-light text-dark border">{{ $prod->categoria->nombre ?? 'General' }}</span>
                                                </td>
                                                <td class="text-center">
                                                    <span class="badge {{ $prod->stock <= 0 ? 'bg-danger' : 'bg-warning text-dark' }} px-2 py-1 rounded-pill">
                                                        {{ $prod->stock }} unids
                                                    </span>
                                                </td>
                                                <td class="text-end">
                                                    @if($canCompras)
                                                    <a href="{{ route('compras.create') }}" class="btn btn-xs btn-outline-primary rounded-pill px-2 py-1" style="font-size: 0.75rem;" title="Comprar Stock">
                                                        <i class="fas fa-plus"></i> Reabastecer
                                                    </a>
                                                    @endif
                                                </td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="4" class="text-center py-4 text-muted">
                                                    <i class="fas fa-check-circle text-success fa-2x mb-2 d-block"></i>
                                                    Todos los productos cuentan con stock suficiente.
                                                </td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Últimas Ventas Realizadas: Requiere permiso de Ventas -->
                    @if($showUltimasVentas)
                    <div class="{{ $showStockCritico ? 'col-lg-6' : 'col-12' }}">
                        <div class="dash-card">
                            <div class="dash-card-header">
                                <h3 class="dash-card-title">
                                    <i class="fas fa-receipt text-success"></i>
                                    Últimas Ventas Registradas
                                </h3>
                                <a href="{{ route('ventas.index') }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3" style="font-size: 0.75rem;">
                                    Ver Todo
                                </a>
                            </div>
                            <div class="dash-card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-dash align-middle">
                                        <thead>
                                            <tr>
                                                <th>Comprobante</th>
                                                <th>Fecha</th>
                                                <th>Monto Total</th>
                                                <th class="text-end">Comprobante</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($ultimasVentas ?? [] as $venta)
                                            <tr>
                                                <td>
                                                    <div class="fw-bold text-dark" style="font-size: 0.85rem;">
                                                        <i class="fas fa-file-invoice text-success me-1"></i>
                                                        {{ $venta->comprobante ?? ('VENTA #' . $venta->id) }}
                                                    </div>
                                                </td>
                                                <td>
                                                    <small class="text-muted">
                                                        {{ \Carbon\Carbon::parse($venta->fecha)->format('d/m/Y') }}
                                                    </small>
                                                </td>
                                                <td>
                                                    <span class="fw-bold text-success" style="font-size: 0.9rem;">
                                                        Bs. {{ number_format($venta->total, 2) }}
                                                    </span>
                                                </td>
                                                <td class="text-end">
                                                    <a href="{{ route('ventas.comprobante', $venta->id) }}" target="_blank" class="btn btn-xs btn-outline-success rounded-pill px-2 py-1" style="font-size: 0.75rem;" title="Ver Comprobante">
                                                        <i class="fas fa-print"></i> Ver
                                                    </a>
                                                </td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="4" class="text-center py-4 text-muted">
                                                    <i class="fas fa-receipt fa-2x mb-2 d-block text-muted"></i>
                                                    Aún no se han registrado ventas en el sistema.
                                                </td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                </div>
                @endif

                <!-- 5. COMPRAS Y BITÁCORA ROW -->
                @if($hasComprasBitacoraRow)
                <div class="row g-4 mb-4">

                    <!-- Últimas Compras: Requiere permiso de Compras -->
                    @if($showUltimasCompras)
                    <div class="{{ $showBitacora ? 'col-lg-6' : 'col-12' }}">
                        <div class="dash-card">
                            <div class="dash-card-header">
                                <h3 class="dash-card-title">
                                    <i class="fas fa-truck-loading text-primary"></i>
                                    Últimas Compras a Proveedores
                                </h3>
                                <a href="{{ route('compras.index') }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3" style="font-size: 0.75rem;">
                                    Ver Todo
                                </a>
                            </div>
                            <div class="dash-card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-dash align-middle">
                                        <thead>
                                            <tr>
                                                <th>Proveedor</th>
                                                <th>Comprobante</th>
                                                <th>Fecha</th>
                                                <th class="text-end">Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($ultimasCompras ?? [] as $compra)
                                            <tr>
                                                <td>
                                                    <div class="fw-bold text-dark" style="font-size: 0.85rem;">
                                                        {{ $compra->proveedor->nombre ?? 'Proveedor General' }}
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="badge bg-light text-secondary border">
                                                        {{ $compra->comprobante ?? ('COMPRA #' . $compra->id) }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <small class="text-muted">
                                                        {{ \Carbon\Carbon::parse($compra->fecha)->format('d/m/Y') }}
                                                    </small>
                                                </td>
                                                <td class="text-end fw-bold text-primary">
                                                    Bs. {{ number_format($compra->total, 2) }}
                                                </td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="4" class="text-center py-4 text-muted">
                                                    <i class="fas fa-shopping-cart fa-2x mb-2 d-block text-muted"></i>
                                                    No hay compras registradas recientemente.
                                                </td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Bitácora de Actividad Reciente: Requiere permiso de Bitácora / Auditoría -->
                    @if($showBitacora)
                    <div class="{{ $showUltimasCompras ? 'col-lg-6' : 'col-12' }}">
                        <div class="dash-card">
                            <div class="dash-card-header">
                                <h3 class="dash-card-title">
                                    <i class="fas fa-history text-secondary"></i>
                                    Registro de Actividad (Bitácora)
                                </h3>
                                <a href="{{ route('bitacoras.index') }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3" style="font-size: 0.75rem;">
                                    Auditoría Completa
                                </a>
                            </div>
                            <div class="dash-card-body">
                                @forelse($ultimasBitacoras ?? [] as $log)
                                <div class="timeline-item-custom">
                                    <div class="timeline-icon-box">
                                        <i class="fas fa-user-shield"></i>
                                    </div>
                                    <div class="timeline-content">
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                             <span class="timeline-title">{{ $log->usuario->name ?? 'Usuario del Sistema' }}</span>
                                            <span class="badge bg-light text-primary border" style="font-size: 0.7rem;">{{ strtoupper($log->modulo ?? 'SISTEMA') }}</span>
                                        </div>
                                        <div class="timeline-desc">{{ $log->descripcion ?? $log->accion }}</div>
                                        <div class="timeline-time">
                                            <i class="far fa-clock"></i>
                                            <span>{{ \Carbon\Carbon::parse($log->created_at)->diffForHumans() }}</span>
                                            <span class="ms-2 text-muted">IP: {{ $log->ip ?? '127.0.0.1' }}</span>
                                        </div>
                                    </div>
                                </div>
                                @empty
                                <div class="text-center py-4 text-muted">
                                    <i class="fas fa-shield-alt fa-2x mb-2 d-block text-muted"></i>
                                    Sin registros recientes de auditoría en bitácora.
                                </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                    @endif

                </div>
                @endif

                <!-- Aviso si el usuario no tiene permisos para tarjetas analíticas pero sí a otros módulos -->
                @if(!$hasChartsRow && $kpiCount === 0 && !$hasOperativeTablesRow && !$hasComprasBitacoraRow)
                <div class="dash-card text-center py-5 mb-4 shadow-sm border-0">
                    <div class="mb-3 text-primary">
                        <div style="width: 70px; height: 70px; border-radius: 20px; background: rgba(37, 99, 235, 0.1); display: inline-flex; align-items: center; justify-content: center; font-size: 30px;">
                            <i class="fas fa-user-shield text-primary"></i>
                        </div>
                    </div>
                    <h4 class="fw-bold text-dark">Panel Operativo Personalizado</h4>
                    <p class="text-muted mx-auto mb-0" style="max-width: 520px; font-size: 0.95rem;">
                        Tu cuenta tiene acceso asignado a módulos de gestión específicos. Utiliza los accesos directos a continuación o el menú lateral para realizar tus operaciones.
                    </p>
                </div>
                @endif

                <!-- 6. ACCESO RÁPIDO A MÓDULOS -->
                <div class="dash-card">
                    <div class="dash-card-header">
                        <h3 class="dash-card-title">
                            <i class="fas fa-rocket text-primary"></i>
                            Acceso Directo a Módulos del Sistema
                        </h3>
                    </div>
                    <div class="dash-card-body">
                        <div class="row g-3">

                            @if(auth()->user()->tienePermiso('ventas'))
                            <div class="col-6 col-md-4 col-lg-2">
                                <a href="{{ route('ventas.index') }}" class="quick-tile">
                                    <div class="quick-tile-icon" style="background: var(--success-gradient);">
                                        <i class="fas fa-cash-register"></i>
                                    </div>
                                    <h4 class="quick-tile-title">Ventas</h4>
                                </a>
                            </div>
                            @endif

                            @if(auth()->user()->tienePermiso('compras'))
                            <div class="col-6 col-md-4 col-lg-2">
                                <a href="{{ route('compras.index') }}" class="quick-tile">
                                    <div class="quick-tile-icon" style="background: var(--primary-gradient);">
                                        <i class="fas fa-shopping-cart"></i>
                                    </div>
                                    <h4 class="quick-tile-title">Compras</h4>
                                </a>
                            </div>
                            @endif

                            @if(auth()->user()->tienePermiso('productos'))
                            <div class="col-6 col-md-4 col-lg-2">
                                <a href="{{ route('productos.index') }}" class="quick-tile">
                                    <div class="quick-tile-icon" style="background: var(--purple-gradient);">
                                        <i class="fas fa-boxes-stacked"></i>
                                    </div>
                                    <h4 class="quick-tile-title">Productos</h4>
                                </a>
                            </div>
                            @endif

                            @if(auth()->user()->esAdmin() || auth()->user()->tienePermiso('caja'))
                            <div class="col-6 col-md-4 col-lg-2">
                                <a href="{{ route('caja.index') }}" class="quick-tile">
                                    <div class="quick-tile-icon" style="background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);">
                                        <i class="fas fa-vault text-warning"></i>
                                    </div>
                                    <h4 class="quick-tile-title">Arqueo Caja</h4>
                                </a>
                            </div>
                            @endif

                            @if(auth()->user()->esAdmin() || auth()->user()->tienePermiso('inventario') || auth()->user()->tienePermiso('productos'))
                            <div class="col-6 col-md-4 col-lg-2">
                                <a href="{{ route('inventario.index') }}" class="quick-tile">
                                    <div class="quick-tile-icon" style="background: linear-gradient(135deg, #0d9488 0%, #0f766e 100%);">
                                        <i class="fas fa-boxes-stacked"></i>
                                    </div>
                                    <h4 class="quick-tile-title">Inventario</h4>
                                </a>
                            </div>

                            <div class="col-6 col-md-4 col-lg-2">
                                <a href="{{ route('inventario.movimientos') }}" class="quick-tile">
                                    <div class="quick-tile-icon" style="background: linear-gradient(135deg, #0284c7 0%, #06b6d4 100%);">
                                        <i class="fas fa-arrows-up-down"></i>
                                    </div>
                                    <h4 class="quick-tile-title">Ingresos & Egresos</h4>
                                </a>
                            </div>

                            <div class="col-6 col-md-4 col-lg-2">
                                <a href="{{ route('inventario.costos') }}" class="quick-tile">
                                    <div class="quick-tile-icon" style="background: linear-gradient(135deg, #d97706 0%, #f59e0b 100%);">
                                        <i class="fas fa-chart-line"></i>
                                    </div>
                                    <h4 class="quick-tile-title">Costos & Margen</h4>
                                </a>
                            </div>
                            @endif

                            @if(auth()->user()->tienePermiso('clientes'))
                            <div class="col-6 col-md-4 col-lg-2">
                                <a href="{{ route('clientes.index') }}" class="quick-tile">
                                    <div class="quick-tile-icon" style="background: var(--info-gradient);">
                                        <i class="fas fa-user-friends"></i>
                                    </div>
                                    <h4 class="quick-tile-title">Clientes</h4>
                                </a>
                            </div>
                            @endif

                            @if(auth()->user()->tienePermiso('proveedores'))
                            <div class="col-6 col-md-4 col-lg-2">
                                <a href="{{ route('proveedores.index') }}" class="quick-tile">
                                    <div class="quick-tile-icon" style="background: var(--warning-gradient);">
                                        <i class="fas fa-truck-loading"></i>
                                    </div>
                                    <h4 class="quick-tile-title">Proveedores</h4>
                                </a>
                            </div>
                            @endif

                            @if(auth()->user()->tienePermiso('categorias'))
                            <div class="col-6 col-md-4 col-lg-2">
                                <a href="{{ route('categorias.index') }}" class="quick-tile">
                                    <div class="quick-tile-icon" style="background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%);">
                                        <i class="fas fa-tags"></i>
                                    </div>
                                    <h4 class="quick-tile-title">Categorías</h4>
                                </a>
                            </div>
                            @endif

                            @if(auth()->user()->tienePermiso('usuarios'))
                            <div class="col-6 col-md-4 col-lg-2">
                                <a href="{{ route('users.index') }}" class="quick-tile">
                                    <div class="quick-tile-icon" style="background: linear-gradient(135deg, #475569 0%, #1e293b 100%);">
                                        <i class="fas fa-users-cog"></i>
                                    </div>
                                    <h4 class="quick-tile-title">Usuarios</h4>
                                </a>
                            </div>
                            @endif

                            @if(auth()->user()->tienePermiso('roles'))
                            <div class="col-6 col-md-4 col-lg-2">
                                <a href="{{ route('roles.index') }}" class="quick-tile">
                                    <div class="quick-tile-icon" style="background: linear-gradient(135deg, #64748b 0%, #334155 100%);">
                                        <i class="fas fa-shield-alt"></i>
                                    </div>
                                    <h4 class="quick-tile-title">Roles</h4>
                                </a>
                            </div>
                            @endif

                            @if(auth()->user()->tienePermiso('bitacoras'))
                            <div class="col-6 col-md-4 col-lg-2">
                                <a href="{{ route('bitacoras.index') }}" class="quick-tile">
                                    <div class="quick-tile-icon" style="background: linear-gradient(135deg, #6b7280 0%, #374151 100%);">
                                        <i class="fas fa-history"></i>
                                    </div>
                                    <h4 class="quick-tile-title">Bitácora</h4>
                                </a>
                            </div>
                            @endif

                            @if(auth()->user()->tienePermiso('backups'))
                            <div class="col-6 col-md-4 col-lg-2">
                                <a href="{{ route('backups.index') }}" class="quick-tile">
                                    <div class="quick-tile-icon" style="background: linear-gradient(135deg, #8b5cf6 0%, #6d28d9 100%);">
                                        <i class="fas fa-database"></i>
                                    </div>
                                    <h4 class="quick-tile-title">Backups</h4>
                                </a>
                            </div>
                            @endif

                        </div>
                    </div>
                </div>

            </div>
            @endif

            <!-- RENDER DE OTRAS VISTAS DEL SISTEMA -->
            @yield('content')

        </section>

    </div>

</div>

<!-- SCRIPTS -->
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- GESTOR DEL MENÚ LATERAL DESPLEGABLE -->
<script>
$(document).ready(function () {
    // Interacción suave para el menú tipo acordeón / desplegable
    $('.nav-sidebar .has-treeview > .nav-link').on('click', function (e) {
        e.preventDefault();
        e.stopPropagation();

        var $parentLi = $(this).closest('.has-treeview');
        var $treeview = $parentLi.children('.nav-treeview');

        if ($parentLi.hasClass('menu-open')) {
            $treeview.slideUp(220, function () {
                $parentLi.removeClass('menu-open');
            });
        } else {
            $treeview.slideDown(220, function () {
                $parentLi.addClass('menu-open');
            });
        }
    });
});
</script>

<!-- RELOJ EN TIEMPO REAL -->
<script>
    function updateClock() {
        const now = new Date();
        const hours = String(now.getHours()).padStart(2, '0');
        const minutes = String(now.getMinutes()).padStart(2, '0');
        const seconds = String(now.getSeconds()).padStart(2, '0');
        
        const day = String(now.getDate()).padStart(2, '0');
        const month = String(now.getMonth() + 1).padStart(2, '0');
        const year = now.getFullYear();

        const timeElem = document.getElementById('liveTime');
        const dateElem = document.getElementById('liveDate');

        if (timeElem) timeElem.textContent = `${hours}:${minutes}:${seconds}`;
        if (dateElem) dateElem.textContent = `${day}/${month}/${year}`;
    }
    setInterval(updateClock, 1000);
    updateClock();
</script>

@if(request()->routeIs('dashboard'))
<!-- GRÁFICOS CHART.JS -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    // 1. Gráfico de Ventas vs Compras
    const ctxVentas = document.getElementById('ventasComprasChart');
    if (ctxVentas) {
        const labelsMeses = {!! json_encode($chartMeses ?? ['Mes 1', 'Mes 2', 'Mes 3', 'Mes 4', 'Mes 5', 'Mes 6']) !!};
        const dataVentas = {!! json_encode($chartVentas ?? [0, 0, 0, 0, 0, 0]) !!};
        const dataCompras = {!! json_encode($chartCompras ?? [0, 0, 0, 0, 0, 0]) !!};

        new Chart(ctxVentas, {
            type: 'line',
            data: {
                labels: labelsMeses,
                datasets: [
                    {
                        label: 'Ventas (Bs.)',
                        data: dataVentas,
                        borderColor: '#10b981',
                        backgroundColor: 'rgba(16, 185, 129, 0.12)',
                        fill: true,
                        tension: 0.35,
                        pointBackgroundColor: '#10b981',
                        pointBorderColor: '#ffffff',
                        pointBorderWidth: 2,
                        pointRadius: 5,
                        pointHoverRadius: 7
                    },
                    {
                        label: 'Compras (Bs.)',
                        data: dataCompras,
                        borderColor: '#6366f1',
                        backgroundColor: 'rgba(99, 102, 241, 0.08)',
                        fill: true,
                        tension: 0.35,
                        pointBackgroundColor: '#6366f1',
                        pointBorderColor: '#ffffff',
                        pointBorderWidth: 2,
                        pointRadius: 5,
                        pointHoverRadius: 7
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            font: { family: "'Plus Jakarta Sans', sans-serif", weight: '600', size: 12 },
                            usePointStyle: true,
                            padding: 20
                        }
                    },
                    tooltip: {
                        backgroundColor: '#0f172a',
                        titleFont: { family: "'Plus Jakarta Sans', sans-serif", weight: '700' },
                        bodyFont: { family: "'Plus Jakarta Sans', sans-serif" },
                        padding: 12,
                        cornerRadius: 8,
                        callbacks: {
                            label: function(context) {
                                return context.dataset.label + ': Bs. ' + context.parsed.y.toLocaleString('es-BO', { minimumFractionDigits: 2 });
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: '#f1f5f9' },
                        ticks: {
                            font: { family: "'Plus Jakarta Sans', sans-serif", size: 11 },
                            callback: function(value) { return 'Bs. ' + value; }
                        }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { font: { family: "'Plus Jakarta Sans', sans-serif", size: 11 } }
                    }
                }
            }
        });
    }

    // 2. Gráfico Doughnut por Categorías
    const ctxCat = document.getElementById('categoriasDoughnutChart');
    if (ctxCat) {
        const labelsCat = {!! json_encode(!empty($catLabels) ? $catLabels : ['Teclados & Mouse', 'Auriculares & Audio', 'Cámaras & Video', 'Cables & Adaptadores']) !!};
        const dataCat = {!! json_encode(!empty($catData) ? $catData : [12, 19, 7, 5]) !!};

        new Chart(ctxCat, {
            type: 'doughnut',
            data: {
                labels: labelsCat,
                datasets: [{
                    data: dataCat,
                    backgroundColor: [
                        '#6366f1',
                        '#10b981',
                        '#f59e0b',
                        '#0ea5e9',
                        '#ec4899',
                        '#8b5cf6'
                    ],
                    borderWidth: 2,
                    borderColor: '#ffffff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            font: { family: "'Plus Jakarta Sans', sans-serif", size: 11, weight: '500' },
                            usePointStyle: true,
                            padding: 12
                        }
                    }
                },
                cutout: '68%'
            }
        });
    }
});
</script>
@endif

<!-- SWEETALERT NOTIFICACIONES -->
@if(session('success'))
<script>
Swal.fire({
    icon: 'success',
    title: 'Operación Exitosa',
    text: "{{ session('success') }}",
    confirmButtonText: 'Aceptar',
    confirmButtonColor: '#4f46e5',
    customClass: {
        popup: 'rounded-4'
    }
});
</script>
@endif

@if(session('error'))
<script>
Swal.fire({
    icon: 'error',
    title: 'Ocurrió un error',
    text: "{{ session('error') }}",
    confirmButtonText: 'Aceptar',
    confirmButtonColor: '#ef4444',
    customClass: {
        popup: 'rounded-4'
    }
});
</script>
@endif

</body>
</html>