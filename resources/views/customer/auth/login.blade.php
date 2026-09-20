@extends('layouts.public')

@section('title', 'Customer Login - Fuzura Enterprise Portal')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="bento-card p-4 p-md-5 shadow-sm">
                <div class="text-center mb-4">
                    <div class="brand-emblem mx-auto mb-3" style="width: 52px; height: 52px; font-size: 1.5rem;">
                        <i class="fas fa-shield-halved"></i>
                    </div>
                    <h3 class="fw-extrabold text-dark mb-1 tracking-tight">Customer Portal</h3>
                    <p class="text-muted small">Manage your registered equipment, view coverage, and print certificates.</p>
                </div>

                <form action="{{ route('customer.login.post') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-dark">Registered Email</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-muted"><i class="fas fa-envelope"></i></span>
                            <input type="email" name="email" id="custEmail" class="form-control border-start-0 ps-0" 
                                   value="{{ old('email') }}" placeholder="john.doe@example.com" required autofocus>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="d-flex justify-content-between">
                            <label class="form-label fw-bold small text-dark">Password</label>
                            <a href="{{ route('customer.forgot_password') }}" class="small text-decoration-none fw-semibold">Forgot password?</a>
                        </div>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-muted"><i class="fas fa-lock"></i></span>
                            <input type="password" name="password" id="custPassword" class="form-control border-start-0 ps-0" 
                                   placeholder="••••••••" required>
                        </div>
                    </div>

                    <div class="form-check mb-4">
                        <input class="form-check-input" type="checkbox" name="remember" id="rememberMe">
                        <label class="form-check-label small text-muted" for="rememberMe">Keep me signed in</label>
                    </div>

                    <button type="submit" class="btn btn-primary-custom w-100 py-2.5 fs-6">
                        <i class="fas fa-arrow-right-to-bracket me-2"></i> Log In to Portal
                    </button>
                </form>

                <!-- Demo Credentials Quick Fill -->
                <div class="mt-4 p-3 rounded-3 text-center border" style="background: #f8fafc;">
                    <small class="text-muted d-block mb-2 font-mono">Demo: john.doe@example.com</small>
                    <button type="button" class="btn btn-sm btn-outline-primary px-3 fw-bold rounded-pill" onclick="fillCustomerDemo()">
                        <i class="fas fa-bolt me-1 text-warning"></i> 1-Click Auto-fill Demo
                    </button>
                </div>

                <div class="mt-4 text-center small text-muted">
                    New customer? 
                    <a href="{{ route('customer.register') }}" class="fw-bold text-decoration-none text-primary ms-1">Create an Account</a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function fillCustomerDemo() {
    document.getElementById('custEmail').value = 'john.doe@example.com';
    document.getElementById('custPassword').value = 'password123';
}
</script>
@endsection
