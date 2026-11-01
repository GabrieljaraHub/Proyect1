<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'Dashboard') | TechStock SENATI</title>

    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap 5.3.3 CSS & Bootstrap Icons 1.11.3 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        :root {
            --sidebar-width: 260px;
            --sidebar-bg: #0f172a;
            --sidebar-hover: #1e293b;
            --sidebar-active: #4f46e5;
            --app-bg: #f8fafc;
            --surface-card: #ffffff;
            --border-subtle: #e2e8f0;
            --primary-accent: #4f46e5;
            --primary-hover: #4338ca;
            --text-heading: #0f172a;
            --text-muted: #64748b;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--app-bg);
            color: #334155;
            min-height: 100vh;
            display: flex;
            margin: 0;
            overflow-x: hidden;
            -webkit-font-smoothing: antialiased;
        }

        /* Sidebar TechStock */
        .app-sidebar {
            width: var(--sidebar-width);
            background-color: var(--sidebar-bg);
            color: #e2e8f0;
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            z-index: 1040;
            display: flex;
            flex-direction: column;
            transition: all 0.3s ease;
            box-shadow: 4px 0 20px rgba(0, 0, 0, 0.06);
        }

        .sidebar-brand {
            padding: 1.4rem 1.25rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            text-decoration: none;
        }

        .sidebar-brand-icon {
            width: 38px;
            height: 38px;
            background: linear-gradient(135deg, #4f46e5 0%, #3b82f6 100%);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            font-size: 1.25rem;
            box-shadow: 0 4px 10px rgba(79, 70, 229, 0.4);
        }

        .sidebar-brand-title {
            font-weight: 800;
            font-size: 1.15rem;
            color: #ffffff;
            letter-spacing: -0.02em;
        }

        .sidebar-brand-subtitle {
            font-size: 0.68rem;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: #94a3b8;
            font-weight: 700;
        }

        .sidebar-nav {
            list-style: none;
            padding: 1.2rem 0.85rem;
            margin: 0;
            flex-grow: 1;
            overflow-y: auto;
        }

        .nav-category-header {
            font-size: 0.68rem;
            font-weight: 700;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.09em;
            padding: 0.75rem 0.6rem 0.35rem 0.6rem;
        }

        .sidebar-item {
            margin-bottom: 0.3rem;
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.65rem 0.9rem;
            color: #94a3b8;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.88rem;
            border-radius: 9px;
            transition: all 0.2s ease;
        }

        .sidebar-link i {
            font-size: 1.15rem;
            color: #64748b;
            transition: color 0.2s ease;
        }

        .sidebar-link:hover {
            color: #ffffff;
            background-color: var(--sidebar-hover);
        }

        .sidebar-link:hover i {
            color: #ffffff;
        }

        .sidebar-link.active {
            color: #ffffff;
            background-color: var(--primary-accent);
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.35);
        }

        .sidebar-link.active i {
            color: #ffffff;
        }

        .sidebar-footer {
            padding: 1.1rem 1.25rem;
            border-top: 1px solid rgba(255, 255, 255, 0.08);
            background-color: rgba(0, 0, 0, 0.2);
        }

        /* Layout Main Wrapper */
        .app-wrapper {
            margin-left: var(--sidebar-width);
            flex-grow: 1;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            transition: margin-left 0.3s ease;
        }

        /* Top Navbar */
        .app-topbar {
            height: 64px;
            background: #ffffff;
            border-bottom: 1px solid var(--border-subtle);
            padding: 0 1.75rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 1030;
        }

        /* Tarjetas Dashboard */
        .card-modern {
            background-color: var(--surface-card);
            border-radius: 16px;
            border: 1px solid var(--border-subtle);
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05), 0 20px 25px -5px rgba(0, 0, 0, 0.02);
            overflow: hidden;
        }

        .stat-card {
            background: #ffffff;
            border-radius: 14px;
            border: 1px solid var(--border-subtle);
            padding: 1.25rem 1.5rem;
            box-shadow: 0 1px 2px rgba(0,0,0,0.03);
            display: flex;
            align-items: center;
            gap: 1.25rem;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.04);
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
        }

        /* Botones */
        .btn-modern-primary {
            background-color: var(--primary-accent);
            border-color: var(--primary-accent);
            color: #ffffff;
            font-weight: 600;
            font-size: 0.9rem;
            padding: 0.65rem 1.35rem;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(79, 70, 229, 0.2);
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            text-decoration: none;
        }

        .btn-modern-primary:hover {
            background-color: var(--primary-hover);
            color: #ffffff;
            box-shadow: 0 4px 10px rgba(79, 70, 229, 0.35);
            transform: translateY(-1px);
        }

        .btn-modern-secondary {
            background-color: #f1f5f9;
            border: 1px solid #cbd5e1;
            color: #475569;
            font-weight: 600;
            font-size: 0.9rem;
            padding: 0.65rem 1.25rem;
            border-radius: 10px;
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            text-decoration: none;
        }

        .btn-modern-secondary:hover {
            background-color: #e2e8f0;
            color: #1e293b;
        }

        /* Inputs y Selects */
        .form-label-modern {
            font-size: 0.84rem;
            font-weight: 700;
            color: #334155;
            margin-bottom: 0.45rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .form-control-modern, .form-select-modern {
            border: 1.5px solid #cbd5e1;
            border-radius: 10px;
            padding: 0.68rem 0.95rem;
            font-size: 0.93rem;
            color: #1e293b;
            background-color: #ffffff;
            transition: all 0.2s ease-in-out;
        }

        .form-control-modern:focus, .form-select-modern:focus {
            border-color: #6366f1;
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.14);
            outline: none;
        }

        .input-group-modern-text {
            background-color: #f8fafc;
            border: 1.5px solid #cbd5e1;
            border-right: none;
            border-radius: 10px 0 0 10px;
            font-weight: 600;
            color: #64748b;
        }

        .input-group-modern-text + .form-control-modern {
            border-radius: 0 10px 10px 0;
        }

        /* Badges Suaves */
        .badge-soft-emerald {
            background-color: #ecfdf5;
            color: #047857;
            border: 1px solid #a7f3d0;
            font-weight: 600;
            font-size: 0.78rem;
            padding: 0.35rem 0.65rem;
            border-radius: 30px;
        }

        .badge-soft-rose {
            background-color: #fff1f2;
            color: #be123c;
            border: 1px solid #fecdd3;
            font-weight: 600;
            font-size: 0.78rem;
            padding: 0.35rem 0.65rem;
            border-radius: 30px;
        }

        .badge-soft-indigo {
            background-color: #eef2ff;
            color: #4338ca;
            border: 1px solid #c7d2fe;
            font-weight: 600;
            font-size: 0.78rem;
            padding: 0.35rem 0.65rem;
            border-radius: 30px;
        }

        .badge-soft-slate {
            background-color: #f8fafc;
            color: #475569;
            border: 1px solid #e2e8f0;
            font-weight: 600;
            font-size: 0.78rem;
            padding: 0.35rem 0.65rem;
            border-radius: 30px;
        }

        /* Tablas */
        .table-modern {
            margin-bottom: 0;
            border-collapse: separate;
            border-spacing: 0;
        }

        .table-modern thead th {
            background-color: #f8fafc;
            color: #475569;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            padding: 1rem 1.25rem;
            border-bottom: 1px solid var(--border-subtle);
            border-top: none;
            white-space: nowrap;
        }

        .table-modern tbody tr:hover {
            background-color: #f8fafc;
        }

        .table-modern tbody td {
            padding: 1.05rem 1.25rem;
            vertical-align: middle;
            border-bottom: 1px solid #f1f5f9;
            font-size: 0.88rem;
        }

        .btn-action-icon {
            width: 32px;
            height: 32px;
            padding: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            font-size: 0.85rem;
            transition: all 0.15s ease;
        }

        .btn-action-edit {
            background-color: #f0fdf4;
            color: #15803d;
            border: 1px solid #bbf7d0;
        }
        .btn-action-edit:hover {
            background-color: #16a34a;
            color: #ffffff;
        }

        .btn-action-delete {
            background-color: #fff1f2;
            color: #be123c;
            border: 1px solid #fecdd3;
        }
        .btn-action-delete:hover {
            background-color: #e11d48;
            color: #ffffff;
        }

        .alert-modern {
            border-radius: 12px;
            padding: 1rem 1.25rem;
            font-size: 0.9rem;
            font-weight: 500;
        }
        .alert-modern-success {
            background-color: #ecfdf5;
            border: 1px solid #a7f3d0;
            color: #065f46;
        }
        .alert-modern-danger {
            background-color: #fff1f2;
            border: 1px solid #fecdd3;
            color: #9f1239;
        }

        /* =============================================
           RESPONSIVE — Mobile & Tablet
        ============================================= */

        /* Overlay backdrop para sidebar en móvil */
        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.55);
            z-index: 1039;
            backdrop-filter: blur(2px);
            -webkit-backdrop-filter: blur(2px);
            animation: fadeInOverlay 0.25s ease;
        }
        .sidebar-overlay.active { display: block; }
        @keyframes fadeInOverlay {
            from { opacity: 0; }
            to   { opacity: 1; }
        }

        @media (max-width: 991.98px) {
            /* Sidebar oculto por defecto en móvil */
            .app-sidebar {
                transform: translateX(-100%);
                z-index: 1040;
                width: min(260px, 85vw); /* nunca más ancho que la pantalla */
            }
            .app-sidebar.show-mobile {
                transform: translateX(0);
                box-shadow: 8px 0 32px rgba(0, 0, 0, 0.25);
            }
            /* El wrapper ocupa todo el ancho */
            .app-wrapper {
                margin-left: 0;
            }
            /* Topbar: padding lateral reducido */
            .app-topbar {
                padding: 0 1rem;
            }
            /* Contenido principal: padding móvil */
            main.container-fluid {
                padding-left: 0.85rem !important;
                padding-right: 0.85rem !important;
            }
        }

        @media (max-width: 575.98px) {
            /* KPI cards: 1 columna plena en xs */
            .stat-card {
                padding: 1rem 1.1rem;
                gap: 0.9rem;
            }
            .stat-icon {
                width: 42px;
                height: 42px;
                font-size: 1.15rem;
                flex-shrink: 0;
            }
            /* Botones de header apilados verticalmente en xs */
            .page-header-actions {
                flex-direction: column;
                width: 100%;
            }
            .page-header-actions .btn-modern-primary,
            .page-header-actions .btn-modern-secondary {
                width: 100%;
                justify-content: center;
            }
            /* Footer: centrado en xs */
            footer .container-fluid {
                text-align: center;
            }
        }

        @media (min-width: 576px) and (max-width: 767.98px) {
            /* Tablet pequeña: padding lateral medio */
            main.container-fluid {
                padding-left: 1.25rem !important;
                padding-right: 1.25rem !important;
            }
        }
    </style>
</head>
<body>
    <!-- Sidebar Navegación TechStock -->
    <aside class="app-sidebar" id="appSidebar">
        <!-- Logo Corporativo -->
        <a href="{{ route('dashboard') }}" class="sidebar-brand">
            <div class="sidebar-brand-icon">
                <i class="bi bi-cpu-fill"></i>
            </div>
            <div>
                <div class="sidebar-brand-title">TechStock</div>
                <div class="sidebar-brand-subtitle">Inventario SENATI</div>
            </div>
        </a>

        <!-- Lista de Navegación -->
        <ul class="sidebar-nav">
            <li class="nav-category-header">Menú Principal</li>
            <li class="sidebar-item">
                <a href="{{ route('dashboard') }}" class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="bi bi-grid-1x2-fill"></i>
                    <span>Inicio / Dashboard</span>
                </a>
            </li>
            <li class="sidebar-item">
                <a href="{{ route('products.index') }}" class="sidebar-link {{ request()->routeIs('products.*') ? 'active' : '' }}">
                    <i class="bi bi-box-seam"></i>
                    <span>Productos</span>
                </a>
            </li>
            <li class="sidebar-item">
                <a href="{{ route('categories.index') }}" class="sidebar-link {{ request()->routeIs('categories.*') ? 'active' : '' }}">
                    <i class="bi bi-tags"></i>
                    <span>Categorías</span>
                </a>
            </li>
            <li class="sidebar-item">
                <a href="{{ route('suppliers.index') }}" class="sidebar-link {{ request()->routeIs('suppliers.*') ? 'active' : '' }}">
                    <i class="bi bi-truck"></i>
                    <span>Proveedores</span>
                </a>
            </li>

            <li class="nav-category-header mt-3">Operaciones & Kardex</li>
            <li class="sidebar-item">
                <a href="{{ route('stock-movements.index') }}" class="sidebar-link {{ request()->routeIs('stock-movements.*') ? 'active' : '' }}">
                    <i class="bi bi-arrow-left-right"></i>
                    <span>Movimientos</span>
                </a>
            </li>
        </ul>

        <!-- Footer del Sidebar: Usuario Gabriel Tito Jara Ccoyso y Logout -->
        <div class="sidebar-footer">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <div class="d-flex align-items-center gap-2 overflow-hidden">
                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center flex-shrink-0" style="width: 36px; height: 36px; font-weight: 700;">
                        {{ strtoupper(substr(Auth::user()->name ?? 'G', 0, 1)) }}
                    </div>
                    <div class="overflow-hidden">
                        <div class="text-white small fw-bold text-truncate" title="{{ Auth::user()->name ?? 'Gabriel Tito Jara Ccoyso' }}">
                            {{ Auth::user()->name ?? 'Gabriel Tito Jara Ccoyso' }}
                        </div>
                        <div class="text-muted text-truncate" style="font-size: 0.72rem;">
                            {{ Auth::user()->email ?? 'admin@ejemplo.com' }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Botón de Cerrar Sesión -->
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-sm btn-outline-danger w-100 py-1 d-flex align-items-center justify-content-center gap-1" style="font-size: 0.8rem;">
                    <i class="bi bi-box-arrow-right"></i>
                    <span>Cerrar Sesión</span>
                </button>
            </form>
        </div>
    </aside>

    <!-- Contenedor Principal (Wrapper) -->
    <div class="app-wrapper">
        <!-- Top Bar Superior -->
        <nav class="app-topbar">
            <div class="d-flex align-items-center gap-3">
                <!-- Botón hamburguesa — visible en tablet y móvil -->
                <button class="btn btn-sm btn-light border d-lg-none d-flex align-items-center justify-content-center"
                        type="button"
                        id="sidebarToggle"
                        aria-label="Abrir menú de navegación"
                        style="width: 38px; height: 38px; border-radius: 10px;">
                    <i class="bi bi-list fs-5" id="hamburgerIcon"></i>
                </button>
                <div class="d-none d-sm-block">
                    <span class="text-muted small fw-semibold">Sistema de Inventario de Accesorios Informáticos</span>
                </div>
            </div>

            <div class="d-flex align-items-center gap-3">
                <div class="d-none d-md-flex align-items-center gap-2 px-3 py-1 bg-light rounded-pill border">
                    <span class="badge bg-success rounded-circle p-1" style="width: 8px; height: 8px;"></span>
                    <span class="text-muted small fw-semibold">SQLite Base Local</span>
                </div>
                <div class="dropdown">
                    <button class="btn btn-sm btn-light border rounded-pill dropdown-toggle d-flex align-items-center gap-2 py-1 px-3" type="button" data-bs-toggle="dropdown">
                        <i class="bi bi-person-circle text-primary"></i>
                        <span class="fw-semibold small">{{ Auth::user()->name ?? 'Gabriel Tito' }}</span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                        <li><h6 class="dropdown-header">Sesión Iniciada</h6></li>
                        <li><span class="dropdown-item-text small text-muted">{{ Auth::user()->email ?? 'admin@ejemplo.com' }}</span></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger small">
                                    <i class="bi bi-box-arrow-right me-2"></i> Cerrar Sesión
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <!-- Contenido Dinámico -->
        <main class="container-fluid px-3 px-sm-3 px-md-4 py-4 flex-grow-1" style="min-width: 0;">
            @if(session('success'))
                <div class="alert alert-modern alert-modern-success alert-dismissible fade show d-flex align-items-center mb-4" role="alert">
                    <i class="bi bi-check-circle-fill me-2 fs-5 text-success"></i>
                    <div class="flex-grow-1">{{ session('success') }}</div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-modern alert-modern-danger alert-dismissible fade show d-flex align-items-center mb-4" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2 fs-5 text-danger"></i>
                    <div class="flex-grow-1">{{ session('error') }}</div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </main>

        <!-- Footer -->
        <footer class="py-3 mt-auto bg-white border-top">
            <div class="container-fluid px-4 d-flex flex-column flex-sm-row justify-content-between align-items-center gap-2 text-muted small">
                <div>&copy; {{ date('Y') }} <strong>TechStock &bull; SENATI</strong> - Inventario de Accesorios Informáticos</div>
                <div><i class="bi bi-shield-check text-primary me-1"></i>Docente Técnico: Gabriel Tito Jara Ccoyso</div>
            </div>
        </footer>
    </div>

    <!-- Bootstrap 5.3.3 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        (function () {
            const toggle   = document.getElementById('sidebarToggle');
            const sidebar  = document.getElementById('appSidebar');
            const hamburger = document.getElementById('hamburgerIcon');

            // Crear overlay dinámicamente
            const overlay = document.createElement('div');
            overlay.className = 'sidebar-overlay';
            overlay.setAttribute('aria-hidden', 'true');
            document.body.appendChild(overlay);

            function openSidebar() {
                sidebar.classList.add('show-mobile');
                overlay.classList.add('active');
                document.body.style.overflow = 'hidden'; // evitar scroll del fondo
                if (hamburger) hamburger.className = 'bi bi-x-lg fs-5';
            }

            function closeSidebar() {
                sidebar.classList.remove('show-mobile');
                overlay.classList.remove('active');
                document.body.style.overflow = '';
                if (hamburger) hamburger.className = 'bi bi-list fs-5';
            }

            if (toggle && sidebar) {
                toggle.addEventListener('click', () => {
                    sidebar.classList.contains('show-mobile') ? closeSidebar() : openSidebar();
                });
            }

            // Cerrar al hacer click en el overlay
            overlay.addEventListener('click', closeSidebar);

            // Cerrar con tecla Escape
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape' && sidebar.classList.contains('show-mobile')) {
                    closeSidebar();
                }
            });

            // Cerrar sidebar al navegar a otra página en móvil (click en enlace del sidebar)
            sidebar.querySelectorAll('.sidebar-link').forEach(link => {
                link.addEventListener('click', () => {
                    if (window.innerWidth < 992) closeSidebar();
                });
            });

            // Al redimensionar la ventana, resetear estado del sidebar
            window.addEventListener('resize', () => {
                if (window.innerWidth >= 992) {
                    closeSidebar();
                }
            });
        })();
    </script>
</body>
</html>
