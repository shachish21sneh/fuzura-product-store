@extends('layouts.customer')

@section('title', 'Customer Profile')
@section('page_title', 'My Profile & Account Security')

@section('content')
<div class="container-fluid px-0">
    <div class="row g-4">
        <!-- Profile Form -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white h-100">
                <h5 class="fw-bold text-dark mb-1"><i class="fas fa-user-circle text-primary me-2"></i> Contact Details</h5>
                <p class="text-muted small mb-4">Keep your contact information up-to-date for warranty replacement notifications.</p>

                <form action="{{ route('customer.profile.update') }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Full Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $customer->name) }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Email Address <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control" value="{{ old('email', $customer->email) }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Mobile Number <span class="text-danger">*</span></label>
                        <input type="text" name="mobile" class="form-control" value="{{ old('mobile', $customer->mobile) }}" required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label small fw-semibold">Mailing Address</label>
                        <textarea name="address" rows="2" class="form-control">{{ old('address', $customer->address) }}</textarea>
                    </div>

                    <button type="submit" class="btn btn-primary-custom px-4">
                        <i class="fas fa-check me-1"></i> Update Profile
                    </button>
                </form>
            </div>
        </div>

        <!-- Password Form -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white h-100">
                <h5 class="fw-bold text-dark mb-1"><i class="fas fa-lock text-warning me-2"></i> Security & Password</h5>
                <p class="text-muted small mb-4">Change your account password regularly to keep your registrations secure.</p>

                <form action="{{ route('customer.password.update') }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Current Password <span class="text-danger">*</span></label>
                        <input type="password" name="current_password" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">New Password <span class="text-danger">*</span></label>
                        <input type="password" name="password" class="form-control" placeholder="Min. 6 characters" required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label small fw-semibold">Confirm New Password <span class="text-danger">*</span></label>
                        <input type="password" name="password_confirmation" class="form-control" required>
                    </div>

                    <button type="submit" class="btn btn-warning fw-bold px-4">
                        <i class="fas fa-key me-1"></i> Change Password
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
