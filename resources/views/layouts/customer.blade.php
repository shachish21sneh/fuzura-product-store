<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Customer Portal') - Fuzura Store</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
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

        <!-- Customer Sidebar -->
        <aside class="app-sidebar shadow" style="background: #1e1b4b !important;">
            <div class="sidebar-brand">
                <i class="fas fa-shield-halved" style="color: #a5b4fc;"></i>
                <div>
                    <div>FUZURA</div>
                    <div style="font-size: 0.65rem; letter-spacing: 0.12em; color: #c7d2fe; font-weight: 700;">CUSTOMER PORTAL</div>
                </div>
            </div>

            <div class="sidebar-nav">
                <div class="nav-category">Overview</div>
                <a href="{{ route('customer.dashboard') }}" class="nav-link-custom {{ request()->routeIs('customer.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-gauge-high"></i>
                    <span>Dashboard</span>
                </a>

                <div class="nav-category">Warranties</div>
                <a href="{{ route('customer.products.register') }}" class="nav-link-custom {{ request()->routeIs('customer.products.register') ? 'active' : '' }}">
                    <i class="fas fa-plus-circle text-warning"></i>
                    <span>Register New Product</span>
                </a>
                <a href="{{ route('customer.products.index') }}" class="nav-link-custom {{ request()->routeIs('customer.products.index') ? 'active' : '' }}">
                    <i class="fas fa-boxes-stacked"></i>
                    <span>My Products</span>
                </a>
                <a href="{{ route('public.search') }}" target="_blank" class="nav-link-custom">
                    <i class="fas fa-magnifying-glass"></i>
                    <span>Verify Serial Status</span>
                </a>

                <div class="nav-category">Account</div>
                <a href="{{ route('customer.profile') }}" class="nav-link-custom {{ request()->routeIs('customer.profile') ? 'active' : '' }}">
                    <i class="fas fa-user-circle"></i>
                    <span>My Profile</span>
                </a>
                <a href="{{ route('home') }}" class="nav-link-custom">
                    <i class="fas fa-house"></i>
                    <span>Return to Home</span>
                </a>
            </div>

            <div class="p-3 border-top border-white border-opacity-10 text-center">
                <div class="small text-white fw-bold text-truncate">{{ Auth::guard('customer')->user()->name }}</div>
                <div class="text-white-50" style="font-size: 0.75rem;">{{ Auth::guard('customer')->user()->mobile }}</div>
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
                    <h5 class="mb-0 fw-bold text-dark">@yield('page_title', 'Customer Portal')</h5>
                </div>

                <div class="d-flex align-items-center gap-3">
                    <a href="{{ route('customer.products.register') }}" class="btn btn-primary-custom btn-sm px-3">
                        <i class="fas fa-plus me-1"></i> Register Product
                    </a>

                    <div class="dropdown">
                        <button class="btn btn-light rounded-circle p-2 shadow-sm dropdown-toggle border" type="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user-circle text-primary fs-5"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                            <li class="dropdown-header">
                                <div class="fw-bold">{{ Auth::guard('customer')->user()->name }}</div>
                                <div class="small text-muted">{{ Auth::guard('customer')->user()->email }}</div>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item" href="{{ route('customer.profile') }}">
                                    <i class="fas fa-user me-2 text-muted"></i> Profile Settings
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="{{ route('customer.logout') }}" method="POST">
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

            <!-- Main Content -->
            <main class="app-content">
                @include('partials.alerts')
                @yield('content')
            </main>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('js/fuzura.js') }}"></script>
    @stack('scripts')
</body>
</html>
