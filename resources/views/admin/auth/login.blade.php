<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Login - Fuzura Store</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <!-- Custom Theme CSS -->
    <link rel="stylesheet" href="{{ asset('css/fuzura.css') }}">
</head>
<body class="d-flex align-items-center justify-content-center min-vh-100" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="card border-0 rounded-4 shadow-lg overflow-hidden bg-white">
                    <div class="p-4 text-center text-white" style="background: linear-gradient(135deg, #4f46e5 0%, #3730a3 100%);">
                        <div class="d-inline-flex p-3 rounded-circle bg-white bg-opacity-10 mb-2">
                            <i class="fas fa-shield-halved fs-2 text-warning"></i>
                        </div>
                        <h4 class="fw-bold mb-0">Fuzura Admin Console</h4>
                        <small class="text-white-50">Staff & Administrator Access</small>
                    </div>

                    <div class="card-body p-4 p-md-5">
                        @include('partials.alerts')

                        <form action="{{ route('admin.login.post') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label fw-semibold small text-muted">Admin Email</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 text-muted"><i class="fas fa-envelope"></i></span>
                                    <input type="email" name="email" id="adminEmail" class="form-control border-start-0 ps-0" 
                                           value="{{ old('email') }}" placeholder="admin@fuzura.com" required autofocus>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold small text-muted">Password</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 text-muted"><i class="fas fa-lock"></i></span>
                                    <input type="password" name="password" id="adminPassword" class="form-control border-start-0 ps-0" 
                                           placeholder="••••••••" required>
                                </div>
                            </div>

                            <div class="d-flex align-items-center justify-content-between mb-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="rememberMe">
                                    <label class="form-check-label small text-muted" for="rememberMe">Keep me logged in</label>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary-custom w-100 py-2 fs-6">
                                <i class="fas fa-arrow-right-to-bracket me-2"></i> Sign In to Console
                            </button>
                        </form>

                        <!-- Demo Credentials Auto-Fill Box -->
                        <div class="mt-4 p-3 bg-light rounded-3 text-center border">
                            <small class="text-muted d-block mb-2">Demo Administrator Credentials:</small>
                            <button type="button" class="btn btn-sm btn-outline-primary px-3 fw-bold" onclick="fillAdminDemo()">
                                <i class="fas fa-key me-1"></i> Auto-fill Demo Admin
                            </button>
                        </div>
                    </div>

                    <div class="card-footer bg-light py-3 text-center border-top small text-muted">
                        <a href="{{ route('home') }}" class="text-decoration-none text-muted">
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
