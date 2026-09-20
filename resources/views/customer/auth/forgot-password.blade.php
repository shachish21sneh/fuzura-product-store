@extends('layouts.public')

@section('title', 'Forgot Password - Fuzura')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card border-0 rounded-4 shadow-sm p-4 p-md-5 bg-white">
                <div class="text-center mb-4">
                    <div class="d-inline-flex p-3 rounded-circle bg-warning-subtle text-warning mb-2">
                        <i class="fas fa-key fs-2"></i>
                    </div>
                    <h3 class="fw-bold text-dark mb-1">Reset Password</h3>
                    <p class="text-muted small">Enter your email address and we'll send you instructions to reset your password.</p>
                </div>

                <form action="{{ route('customer.forgot_password.post') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label class="form-label fw-semibold small text-muted">Account Email</label>
                        <input type="email" name="email" class="form-control" placeholder="john.doe@example.com" required autofocus>
                    </div>

                    <button type="submit" class="btn btn-primary-custom w-100 py-2">
                        <i class="fas fa-paper-plane me-2"></i> Send Reset Link
                    </button>
                </form>

                <div class="mt-4 text-center small text-muted">
                    Remember your credentials? 
                    <a href="{{ route('customer.login') }}" class="fw-bold text-decoration-none">Back to Login</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
