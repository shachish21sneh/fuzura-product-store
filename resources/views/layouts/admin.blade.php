<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Console') - Fuzura Store</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <!-- DataTables Bootstrap 5 CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <!-- Custom Theme CSS -->
    <link rel="stylesheet" href="{{ asset('css/fuzura.css') }}">
    @stack('styles')
</head>
<body>
    <div class="app-wrapper">

        <!-- Mobile Backdrop -->
        <div class="sidebar-backdrop"></div>

        <!-- Sidebar Navigation -->
        <aside class="app-sidebar shadow">
            <div class="sidebar-brand">
                <div class="brand-emblem" style="width: 36px; height: 36px; font-size: 1.05rem;">
                    <i class="fas fa-shield-halved"></i>
                </div>
                <div>
                    <div class="fw-extrabold lh-1 text-white">FUZURA<span class="text-gradient-accent ms-1">STORE</span></div>
                    <div style="font-size: 0.62rem; letter-spacing: 0.12em; color: #818cf8; font-weight: 700; text-transform: uppercase;">Admin Console</div>
                </div>
            </div>

            <div class="sidebar-nav">
                <div class="nav-category">Main</div>
                <a href="{{ route('admin.dashboard') }}" class="nav-link-custom {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-chart-pie"></i>
                    <span>Dashboard</span>
                </a>

                <div class="nav-category">Inventory & Operations</div>
                <a href="{{ route('admin.products.index') }}" class="nav-link-custom {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                    <i class="fas fa-box-archive"></i>
                    <span>Product Master</span>
                </a>
                <a href="{{ route('admin.sales.index') }}" class="nav-link-custom {{ request()->routeIs('admin.sales.*') ? 'active' : '' }}">
                    <i class="fas fa-cart-shopping"></i>
                    <span>Sales Management</span>
                </a>
                <a href="{{ route('admin.replacements.index') }}" class="nav-link-custom {{ request()->routeIs('admin.replacements.*') ? 'active' : '' }}">
                    <i class="fas fa-repeat"></i>
                    <span>Replacements</span>
                </a>

                <div class="nav-category">Warranty & Customers</div>
                <a href="{{ route('admin.warranties.index') }}" class="nav-link-custom {{ request()->routeIs('admin.warranties.*') ? 'active' : '' }}">
                    <i class="fas fa-certificate"></i>
                    <span>Warranty Claims</span>
                </a>
                <a href="{{ route('admin.customers.index') }}" class="nav-link-custom {{ request()->routeIs('admin.customers.*') ? 'active' : '' }}">
                    <i class="fas fa-users"></i>
                    <span>Customer List</span>
                </a>
                <a href="{{ route('admin.search.index') }}" class="nav-link-custom {{ request()->routeIs('admin.search.*') ? 'active' : '' }}">
                    <i class="fas fa-magnifying-glass"></i>
                    <span>Serial Search</span>
                </a>

                <div class="nav-category">Analytics & Settings</div>
                <a href="{{ route('admin.reports.index') }}" class="nav-link-custom {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
                    <i class="fas fa-file-invoice"></i>
                    <span>Reports & Export</span>
                </a>
                <a href="{{ route('admin.profile') }}" class="nav-link-custom {{ request()->routeIs('admin.profile') ? 'active' : '' }}">
                    <i class="fas fa-user-gear"></i>
                    <span>Profile & Security</span>
                </a>
                <a href="{{ route('home') }}" target="_blank" class="nav-link-custom">
                    <i class="fas fa-arrow-up-right-from-square"></i>
                    <span>Visit Public Site</span>
                </a>
            </div>

            <!-- Admin Footer Info -->
            <div class="p-3 border-top border-secondary border-opacity-25 text-center">
                <div class="small text-light text-truncate">{{ Auth::guard('admin')->user()->name }}</div>
                <div class="text-muted" style="font-size: 0.7rem;">Super Administrator</div>
            </div>
        </aside>

        <!-- Main Workspace -->
        <div class="app-main">
            <!-- Topbar -->
            <header class="app-topbar">
                <div class="d-flex align-items-center gap-3">
                    <button class="btn btn-light d-lg-none" id="sidebarToggle">
                        <i class="fas fa-bars"></i>
                    </button>
                    <h5 class="mb-0 fw-bold text-dark d-none d-sm-block">@yield('page_title', 'Admin Console')</h5>
                </div>

                <div class="d-flex align-items-center gap-3">
                    <a href="{{ route('admin.search.index') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3 d-none d-md-flex align-items-center gap-2">
                        <i class="fas fa-search text-muted"></i>
                        <span>Search Serial #</span>
                    </a>

                    <div class="dropdown">
                        <button class="btn btn-light rounded-circle p-2 shadow-sm dropdown-toggle text-dark border" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-user text-primary"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                            <li class="dropdown-header">
                                <div class="fw-bold">{{ Auth::guard('admin')->user()->name }}</div>
                                <div class="small text-muted">{{ Auth::guard('admin')->user()->email }}</div>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item" href="{{ route('admin.profile') }}">
                                    <i class="fas fa-user-gear me-2 text-muted"></i> Profile Settings
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="{{ route('admin.logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger">
                                        <i class="fas fa-sign-out-alt me-2"></i> Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="app-content">
                @include('partials.alerts')
                @yield('content')
            </main>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('js/fuzura.js') }}"></script>
    @stack('scripts')
</body>
</html>
