@extends('layouts.public')

@section('title', 'Customer Sign Up - Fuzura Enterprise Portal')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-7 col-lg-6">
            <div class="bento-card p-4 p-md-5 shadow-sm">
                <div class="text-center mb-4">
                    <div class="brand-emblem mx-auto mb-3" style="width: 52px; height: 52px; font-size: 1.5rem;">
                        <i class="fas fa-user-plus"></i>
                    </div>
                    <h3 class="fw-extrabold text-dark mb-1 tracking-tight">Create Customer Account</h3>
                    <p class="text-muted small">Register to protect your authentic Fuzura equipment with lifetime tracking.</p>
                </div>

                <form action="{{ route('customer.register.post') }}" method="POST">
                    @csrf
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label fw-bold small text-dark">Full Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control rounded-3 py-2.5" value="{{ old('name') }}" placeholder="e.g. Rahul Sharma" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-dark">Email Address <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control rounded-3 py-2.5" value="{{ old('email') }}" placeholder="e.g. rahul@example.com" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-dark">Mobile Number <span class="text-danger">*</span></label>
                            <input type="text" name="mobile" class="form-control rounded-3 py-2.5 font-mono" value="{{ old('mobile') }}" placeholder="+91-9876543210" required>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-bold small text-dark">Communication Address</label>
                            <textarea name="address" rows="2" class="form-control rounded-3 py-2.5" placeholder="Flat, Street Address, City, State, PIN...">{{ old('address') }}</textarea>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-dark">Password <span class="text-danger">*</span></label>
                            <input type="password" name="password" class="form-control rounded-3 py-2.5" placeholder="Min. 6 characters" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-dark">Confirm Password <span class="text-danger">*</span></label>
                            <input type="password" name="password_confirmation" class="form-control rounded-3 py-2.5" placeholder="Re-type password" required>
                        </div>

                        <div class="col-12 mt-4">
                            <button type="submit" class="btn btn-primary-custom w-100 py-2.5 fs-6">
                                <i class="fas fa-check-circle me-2"></i> Register Account
                            </button>
                        </div>
                    </div>
                </form>

                <div class="mt-4 text-center small text-muted">
                    Already registered? 
                    <a href="{{ route('customer.login') }}" class="fw-bold text-decoration-none text-primary ms-1">Sign In Here</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
