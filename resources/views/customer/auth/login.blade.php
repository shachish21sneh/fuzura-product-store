@extends('layouts.public')

@section('title', 'Customer Login - Fuzura')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card border-0 rounded-4 shadow-sm p-4 p-md-5 bg-white">
                <div class="text-center mb-4">
                    <div class="d-inline-flex p-3 rounded-circle bg-primary-subtle text-primary mb-2">
                        <i class="fas fa-user-circle fs-2"></i>
                    </div>
                    <h3 class="fw-bold text-dark mb-1">Customer Sign In</h3>
                    <p class="text-muted small">Access your registered hardware units and warranty certificates.</p>
                </div>

                <form action="{{ route('customer.login.post') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-muted">Email Address</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-muted"><i class="fas fa-envelope"></i></span>
                            <input type="email" name="email" id="custEmail" class="form-control border-start-0 ps-0" 
                                   value="{{ old('email') }}" placeholder="john.doe@example.com" required autofocus>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="d-flex justify-content-between">
                            <label class="form-label fw-semibold small text-muted">Password</label>
                            <a href="{{ route('customer.forgot_password') }}" class="small text-decoration-none">Forgot password?</a>
                        </div>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-muted"><i class="fas fa-lock"></i></span>
                            <input type="password" name="password" id="custPassword" class="form-control border-start-0 ps-0" 
                                   placeholder="••••••••" required>
                        </div>
                    </div>

                    <div class="form-check mb-4">
                        <input class="form-check-input" type="checkbox" name="remember" id="rememberMe">
                        <label class="form-check-label small text-muted" for="rememberMe">Remember me</label>
                    </div>

                    <button type="submit" class="btn btn-primary-custom w-100 py-2 fs-6">
                        <i class="fas fa-arrow-right-to-bracket me-2"></i> Log In
                    </button>
                </form>

                <!-- Demo Credentials Quick Fill -->
                <div class="mt-4 p-3 bg-light rounded-3 text-center border">
                    <small class="text-muted d-block mb-2">Demo Customer Account:</small>
                    <button type="button" class="btn btn-sm btn-outline-primary px-3 fw-bold" onclick="fillCustomerDemo()">
                        <i class="fas fa-key me-1"></i> Auto-fill Demo Customer
                    </button>
                </div>

                <div class="mt-4 text-center small text-muted">
                    Don't have an account yet? 
                    <a href="{{ route('customer.register') }}" class="fw-bold text-decoration-none">Sign Up Here</a>
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
