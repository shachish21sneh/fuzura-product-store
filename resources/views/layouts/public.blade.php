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
    <nav class="navbar navbar-expand-lg navbar-dark navbar-glass py-3 sticky-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-2 text-white text-decoration-none" href="{{ route('home') }}">
                <div class="brand-emblem">
                    <i class="fas fa-shield-halved"></i>
                </div>
                <div class="d-flex flex-column">
                    <span class="fw-extrabold tracking-tight fs-4 lh-1 text-white">
                        FUZURA<span class="text-gradient-accent ms-1">STORE</span>
                    </span>
                    <small style="font-size: 0.65rem; letter-spacing: 0.12em; color: #94a3b8; font-weight: 700; text-transform: uppercase;">Enterprise Warranty Ledger</small>
                </div>
            </a>

            <button class="navbar-toggler border-0 shadow-none text-white" type="button" data-bs-toggle="collapse" data-bs-target="#publicNav">
                <i class="fas fa-bars fs-4"></i>
            </button>

            <div class="collapse navbar-collapse" id="publicNav">
                <ul class="navbar-nav mx-auto mb-2 mb-lg-0 gap-1">
                    <li class="nav-item">
                        <a class="nav-link nav-link-glass {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">
                            <i class="fas fa-house-chimney me-1.5 opacity-75"></i> Home
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-link-glass {{ request()->routeIs('public.search') ? 'active' : '' }}" href="{{ route('public.search') }}">
                            <i class="fas fa-fingerprint me-1.5 opacity-75"></i> Verify Serial
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-link-glass {{ request()->routeIs('public.about') ? 'active' : '' }}" href="{{ route('public.about') }}">
                            <i class="fas fa-layer-group me-1.5 opacity-75"></i> About Us
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-link-glass {{ request()->routeIs('public.contact') ? 'active' : '' }}" href="{{ route('public.contact') }}">
                            <i class="fas fa-headset me-1.5 opacity-75"></i> Contact Support
                        </a>
                    </li>
                </ul>

                <div class="d-flex align-items-center gap-2 mt-3 mt-lg-0">
                    @if (Auth::guard('admin')->check())
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-warning btn-sm px-3 fw-bold rounded-3 shadow-sm">
                            <i class="fas fa-shield-alt me-1"></i> Admin Console
                        </a>
                    @elseif (Auth::guard('customer')->check())
                        <div class="dropdown">
                            <button class="btn btn-glass btn-sm dropdown-toggle px-3 d-flex align-items-center gap-2" type="button" data-bs-toggle="dropdown">
                                <i class="fas fa-circle-user text-info"></i>
                                <span>{{ Auth::guard('customer')->user()->name }}</span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 rounded-3 p-2 mt-2">
                                <li><a class="dropdown-item rounded-2 py-2" href="{{ route('customer.dashboard') }}"><i class="fas fa-gauge-high me-2 text-primary"></i> Dashboard</a></li>
                                <li><a class="dropdown-item rounded-2 py-2" href="{{ route('customer.products.index') }}"><i class="fas fa-boxes-stacked me-2 text-info"></i> My Products</a></li>
                                <li><a class="dropdown-item rounded-2 py-2" href="{{ route('customer.products.register') }}"><i class="fas fa-plus-circle me-2 text-success"></i> Register Product</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{ route('customer.logout') }}" method="POST">
                                        @csrf
                                        <button class="dropdown-item rounded-2 py-2 text-danger" type="submit"><i class="fas fa-arrow-right-from-bracket me-2"></i> Logout</button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    @else
                        <a href="{{ route('customer.login') }}" class="btn btn-glass btn-sm px-3">
                            <i class="fas fa-arrow-right-to-bracket me-1 opacity-75"></i> Customer Login
                        </a>
                        <a href="{{ route('customer.register') }}" class="btn btn-primary-custom btn-sm px-3">
                            <i class="fas fa-user-plus me-1"></i> Sign Up
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
    <footer class="text-white pt-5 pb-4 mt-5" style="background-color: #080c15 !important; border-top: 1px solid rgba(255,255,255,0.08);">
        <div class="container">
            <div class="row g-4 justify-content-between">
                <div class="col-lg-4">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <div class="brand-emblem" style="width: 32px; height: 32px; font-size: 0.95rem;">
                            <i class="fas fa-shield-halved"></i>
                        </div>
                        <span class="fw-extrabold fs-5 text-white">
                            FUZURA<span class="text-gradient-accent ms-1">STORE</span>
                        </span>
                    </div>
                    <p class="text-slate-400 small pe-lg-4 lh-base" style="color: #94a3b8;">
                        Enterprise product serial registry and automated warranty lifecycle tracking. Verifiable N-level replacement chains, tamper-proof timelines, and instant certificate issuance.
                    </p>
                    <div class="d-flex align-items-center gap-2 mt-3">
                        <span class="pulse-led"></span>
                        <small style="color: #34d399; font-weight: 600; font-size: 0.78rem;">All Verification Nodes Operational</small>
                    </div>
                </div>

                <div class="col-6 col-lg-2">
                    <h6 class="text-white fw-bold mb-3 small text-uppercase tracking-wider">Quick Links</h6>
                    <ul class="list-unstyled small d-flex flex-column gap-2" style="color: #94a3b8;">
                        <li><a href="{{ route('home') }}" class="text-decoration-none" style="color: #94a3b8; transition: color 0.2s;" onmouseover="this.style.color='#fff'" onmouseout="this.style.color='#94a3b8'">Platform Home</a></li>
                        <li><a href="{{ route('public.search') }}" class="text-decoration-none" style="color: #94a3b8; transition: color 0.2s;" onmouseover="this.style.color='#fff'" onmouseout="this.style.color='#94a3b8'">Verify Serial</a></li>
                        <li><a href="{{ route('public.about') }}" class="text-decoration-none" style="color: #94a3b8; transition: color 0.2s;" onmouseover="this.style.color='#fff'" onmouseout="this.style.color='#94a3b8'">About Us</a></li>
                        <li><a href="{{ route('public.contact') }}" class="text-decoration-none" style="color: #94a3b8; transition: color 0.2s;" onmouseover="this.style.color='#fff'" onmouseout="this.style.color='#94a3b8'">Contact Support</a></li>
                    </ul>
                </div>

                <div class="col-6 col-lg-2">
                    <h6 class="text-white fw-bold mb-3 small text-uppercase tracking-wider">Customer Portal</h6>
                    <ul class="list-unstyled small d-flex flex-column gap-2" style="color: #94a3b8;">
                        <li><a href="{{ route('customer.login') }}" class="text-decoration-none" style="color: #94a3b8; transition: color 0.2s;" onmouseover="this.style.color='#fff'" onmouseout="this.style.color='#94a3b8'">Customer Login</a></li>
                        <li><a href="{{ route('customer.register') }}" class="text-decoration-none" style="color: #94a3b8; transition: color 0.2s;" onmouseover="this.style.color='#fff'" onmouseout="this.style.color='#94a3b8'">Create Account</a></li>
                        <li><a href="{{ route('customer.products.register') }}" class="text-decoration-none" style="color: #94a3b8; transition: color 0.2s;" onmouseover="this.style.color='#fff'" onmouseout="this.style.color='#94a3b8'">Register Product</a></li>
                        <li><a href="{{ route('admin.login') }}" class="text-decoration-none" style="color: #94a3b8; transition: color 0.2s;" onmouseover="this.style.color='#fff'" onmouseout="this.style.color='#94a3b8'">Admin Login</a></li>
                    </ul>
                </div>

                <div class="col-lg-3">
                    <h6 class="text-white fw-bold mb-3 small text-uppercase tracking-wider">Support Helpdesk</h6>
                    <p class="small mb-1.5" style="color: #94a3b8;"><i class="fas fa-phone-volume me-2 text-primary"></i> 1-800-FUZURA-CARE</p>
                    <p class="small mb-1.5" style="color: #94a3b8;"><i class="fas fa-envelope-open-text me-2 text-primary"></i> warranty@fuzura.in</p>
                    <p class="small" style="color: #94a3b8;"><i class="fas fa-business-time me-2 text-primary"></i> Mon - Sat: 9:00 AM - 7:00 PM IST</p>
                    <div class="d-flex gap-2 mt-3">
                        <span class="badge" style="background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.1); color: #cbd5e1; font-weight: 500;">
                            <i class="fas fa-lock me-1 text-success"></i> 256-Bit SSL Secured
                        </span>
                    </div>
                </div>
            </div>

            <hr class="my-4" style="border-color: rgba(255,255,255,0.08);">

            <div class="d-flex flex-wrap justify-content-between align-items-center small" style="color: #64748b;">
                <div>&copy; {{ date('Y') }} Fuzura Product Store & Warranty Management. All rights reserved.</div>
                <div class="d-flex gap-3">
                    <a href="{{ route('admin.login') }}" class="text-decoration-none" style="color: #64748b; transition: color 0.2s;" onmouseover="this.style.color='#fff'" onmouseout="this.style.color='#64748b'">
                        <i class="fas fa-shield-halved me-1"></i> Admin Console
                    </a>
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
