<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Fuzura Product Registration & Warranty Management')</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/fuzura.css') }}">
    @stack('styles')
</head>
<body class="d-flex flex-column min-vh-100 bg-light">

    <!-- Public Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark py-3 sticky-top shadow-sm" style="background-color: #0f172a !important;">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-2 fw-bold text-white fs-4" href="{{ route('home') }}">
                <i class="fas fa-shield-halved text-primary fs-3" style="color: #6366f1 !important;"></i>
                <span>FUZURA<span class="text-primary" style="color: #818cf8 !important;">STORE</span></span>
            </a>

            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#publicNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="publicNav">
                <ul class="navbar-nav mx-auto mb-2 mb-lg-0 fw-semibold">
                    <li class="nav-item">
                        <a class="nav-link px-3 {{ request()->routeIs('home') ? 'active text-white' : 'text-light-50' }}" href="{{ route('home') }}">
                            <i class="fas fa-home me-1 opacity-75"></i> Home
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link px-3 {{ request()->routeIs('public.search') ? 'active text-white' : 'text-light-50' }}" href="{{ route('public.search') }}">
                            <i class="fas fa-search me-1 opacity-75"></i> Verify Serial
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link px-3 {{ request()->routeIs('public.about') ? 'active text-white' : 'text-light-50' }}" href="{{ route('public.about') }}">
                            About Us
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link px-3 {{ request()->routeIs('public.contact') ? 'active text-white' : 'text-light-50' }}" href="{{ route('public.contact') }}">
                            Contact Us
                        </a>
                    </li>
                </ul>

                <div class="d-flex align-items-center gap-2 mt-3 mt-lg-0">
                    @if (Auth::guard('admin')->check())
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-warning btn-sm px-3 fw-bold">
                            <i class="fas fa-user-shield me-1"></i> Admin Console
                        </a>
                    @elseif (Auth::guard('customer')->check())
                        <div class="dropdown">
                            <button class="btn btn-outline-light btn-sm dropdown-toggle px-3 fw-semibold" type="button" data-bs-toggle="dropdown">
                                <i class="fas fa-user-circle me-1"></i> {{ Auth::guard('customer')->user()->name }}
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow">
                                <li><a class="dropdown-item" href="{{ route('customer.dashboard') }}"><i class="fas fa-gauge-high me-2 text-primary"></i> Dashboard</a></li>
                                <li><a class="dropdown-item" href="{{ route('customer.products.index') }}"><i class="fas fa-boxes-stacked me-2 text-info"></i> My Products</a></li>
                                <li><a class="dropdown-item" href="{{ route('customer.products.register') }}"><i class="fas fa-plus-circle me-2 text-success"></i> Register Product</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{ route('customer.logout') }}" method="POST">
                                        @csrf
                                        <button class="dropdown-item text-danger" type="submit"><i class="fas fa-sign-out-alt me-2"></i> Logout</button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    @else
                        <a href="{{ route('customer.login') }}" class="btn btn-outline-light btn-sm px-3">
                            <i class="fas fa-sign-in-alt me-1"></i> Customer Login
                        </a>
                        <a href="{{ route('customer.register') }}" class="btn btn-primary-custom btn-sm px-3">
                            Sign Up
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content Container -->
    <main class="flex-grow-1">
        <div class="container mt-3">
            @include('partials.alerts')
        </div>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-dark text-white pt-5 pb-4 mt-5" style="background-color: #0b1120 !important; border-top: 1px solid rgba(255,255,255,0.08);">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-4">
                    <h5 class="fw-bold mb-3 d-flex align-items-center gap-2">
                        <i class="fas fa-shield-halved text-primary" style="color: #6366f1 !important;"></i>
                        FUZURA STORE
                    </h5>
                    <p class="text-muted small pe-lg-4">
                        Enterprise product serial tracking and automated warranty management. Verifiable timelines, instant registration, and transparent replacement history.
                    </p>
                    <div class="d-flex gap-3 text-muted">
                        <a href="#" class="text-muted text-decoration-none"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="text-muted text-decoration-none"><i class="fab fa-linkedin"></i></a>
                        <a href="#" class="text-muted text-decoration-none"><i class="fab fa-github"></i></a>
                    </div>
                </div>

                <div class="col-6 col-lg-2">
                    <h6 class="text-white fw-bold mb-3">Quick Links</h6>
                    <ul class="list-unstyled small d-flex flex-column gap-2 text-muted">
                        <li><a href="{{ route('home') }}" class="text-muted text-decoration-none">Home</a></li>
                        <li><a href="{{ route('public.search') }}" class="text-muted text-decoration-none">Verify Serial</a></li>
                        <li><a href="{{ route('public.about') }}" class="text-muted text-decoration-none">About Us</a></li>
                        <li><a href="{{ route('public.contact') }}" class="text-muted text-decoration-none">Contact Support</a></li>
                    </ul>
                </div>

                <div class="col-6 col-lg-3">
                    <h6 class="text-white fw-bold mb-3">Customer Portal</h6>
                    <ul class="list-unstyled small d-flex flex-column gap-2 text-muted">
                        <li><a href="{{ route('customer.login') }}" class="text-muted text-decoration-none">Customer Login</a></li>
                        <li><a href="{{ route('customer.register') }}" class="text-muted text-decoration-none">Register Account</a></li>
                        <li><a href="{{ route('customer.products.register') }}" class="text-muted text-decoration-none">Register Product</a></li>
                        <li><a href="{{ route('admin.login') }}" class="text-muted text-decoration-none">Staff / Admin Login</a></li>
                    </ul>
                </div>

                <div class="col-lg-3">
                    <h6 class="text-white fw-bold mb-3">Support Helpdesk</h6>
                    <p class="text-muted small mb-1"><i class="fas fa-phone-alt me-2 text-primary"></i> 1-800-FUZURA-CARE</p>
                    <p class="text-muted small mb-1"><i class="fas fa-envelope me-2 text-primary"></i> warranty@fuzura.com</p>
                    <p class="text-muted small"><i class="fas fa-clock me-2 text-primary"></i> Mon - Sat: 9:00 AM - 7:00 PM</p>
                </div>
            </div>

            <hr class="my-4" style="border-color: rgba(255,255,255,0.08);">

            <div class="d-flex flex-wrap justify-content-between align-items-center small text-muted">
                <div>&copy; {{ date('Y') }} Fuzura Product Store & Warranty Management. All rights reserved.</div>
                <div class="d-flex gap-3">
                    <a href="{{ route('admin.login') }}" class="text-muted text-decoration-none"><i class="fas fa-lock me-1"></i> Admin Access</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- jQuery & Bootstrap 5 JS Bundle -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('js/fuzura.js') }}"></script>
    @stack('scripts')
</body>
</html>
