@extends('layouts.admin')

@section('title', 'Admin Profile')
@section('page_title', 'Administrator Profile & Security')

@section('content')
<div class="container-fluid px-0">
    <div class="row g-4">
        <!-- Edit Profile Details -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white h-100">
                <h5 class="fw-bold text-dark mb-1"><i class="fas fa-user-gear text-primary me-2"></i> Profile Information</h5>
                <p class="text-muted small mb-4">Update your administrator contact details and credentials.</p>

                <form action="{{ route('admin.profile.update') }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Administrator Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $admin->name) }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Email Address <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control" value="{{ old('email', $admin->email) }}" required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label small fw-semibold">Contact Phone Number</label>
                        <input type="text" name="phone" class="form-control" value="{{ old('phone', $admin->phone) }}">
                    </div>

                    <button type="submit" class="btn btn-primary-custom px-4">
                        <i class="fas fa-check me-1"></i> Save Changes
                    </button>
                </form>
            </div>
        </div>

        <!-- Change Password -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white h-100">
                <h5 class="fw-bold text-dark mb-1"><i class="fas fa-lock text-warning me-2"></i> Change Password</h5>
                <p class="text-muted small mb-4">Ensure your account uses a strong and secure password.</p>

                <form action="{{ route('admin.password.update') }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Current Password <span class="text-danger">*</span></label>
                        <input type="password" name="current_password" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">New Password <span class="text-danger">*</span></label>
                        <input type="password" name="password" class="form-control" required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label small fw-semibold">Confirm New Password <span class="text-danger">*</span></label>
                        <input type="password" name="password_confirmation" class="form-control" required>
                    </div>

                    <button type="submit" class="btn btn-warning fw-bold px-4">
                        <i class="fas fa-key me-1"></i> Update Password
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
