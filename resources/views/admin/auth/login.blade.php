<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Portal Login - Fuzura Store</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <!-- Custom Theme CSS -->
    <link rel="stylesheet" href="{{ asset('css/fuzura.css') }}">
</head>
<body class="d-flex flex-column min-vh-100 hero-cosmic" style="overflow-y: auto; overflow-x: hidden;">
    <div class="container py-4 py-md-5 my-auto">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="card border-0 rounded-4 shadow-lg overflow-hidden bg-white" style="box-shadow: 0 25px 60px -15px rgba(0,0,0,0.5) !important;">
                    <div class="p-4 text-center text-white" style="background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 50%, #312e81 100%);">
                        <div class="brand-emblem mx-auto mb-2" style="width: 48px; height: 48px; font-size: 1.4rem;">
                            <i class="fas fa-shield-halved"></i>
                        </div>
                        <h4 class="fw-extrabold mb-0 text-white tracking-tight">Fuzura Admin Console</h4>
                        <small style="color: #94a3b8; font-size: 0.78rem; text-transform: uppercase; letter-spacing: 0.08em; font-weight: 600;">Authorized Staff Only</small>
                    </div>

                    <div class="card-body p-4 p-md-5">
                        @include('partials.alerts')

                        <form action="{{ route('admin.login.post') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label fw-bold small text-dark">Admin Email Address</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 text-muted"><i class="fas fa-envelope"></i></span>
                                    <input type="email" name="email" id="adminEmail" class="form-control border-start-0 ps-0" 
                                           value="{{ old('email') }}" placeholder="admin@fuzura.com" required autofocus>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold small text-dark">Security Password</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 text-muted"><i class="fas fa-lock"></i></span>
                                    <input type="password" name="password" id="adminPassword" class="form-control border-start-0 ps-0" 
                                           placeholder="••••••••" required>
                                </div>
                            </div>

                            <div class="d-flex align-items-center justify-content-between mb-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="rememberMe">
                                    <label class="form-check-label small text-muted" for="rememberMe">Keep console session active</label>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary-custom w-100 py-2.5 fs-6">
                                <i class="fas fa-arrow-right-to-bracket me-2"></i> Sign In to Console
                            </button>
                        </form>

                        <!-- Demo Credentials Auto-Fill Box -->
                        <div class="mt-4 p-3 rounded-3 text-center border" style="background: #f8fafc;">
                            <small class="text-muted d-block mb-2 font-mono">Demo Admin: admin@fuzura.com</small>
                            <button type="button" class="btn btn-sm btn-outline-primary px-3 fw-bold rounded-pill" onclick="fillAdminDemo()">
                                <i class="fas fa-bolt me-1 text-warning"></i> 1-Click Auto-fill Demo
                            </button>
                        </div>
                    </div>

                    <div class="card-footer py-3 text-center border-top small" style="background: #f8fafc;">
                        <a href="{{ route('home') }}" class="text-decoration-none text-muted" style="transition: color 0.2s;" onmouseover="this.style.color='#0f172a'" onmouseout="this.style.color='#64748b'">
                            <i class="fas fa-arrow-left me-1"></i> Back to Public Website
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function fillAdminDemo() {
            document.getElementById('adminEmail').value = 'admin@fuzura.com';
            document.getElementById('adminPassword').value = 'password123';
        }
    </script>
</body>
</html>
