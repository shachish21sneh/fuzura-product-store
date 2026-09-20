@extends('layouts.public')

@section('title', 'Fuzura - Product Registration & Warranty Management System')

@section('content')
<!-- Hero Section -->
<section class="py-5 text-white" style="background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 60%, #312e81 100%);">
    <div class="container py-5">
        <div class="row align-items-center g-5">
            <div class="col-lg-7">
                <span class="badge bg-indigo-500 bg-opacity-25 text-white px-3 py-2 rounded-pill fw-semibold mb-3 border border-light border-opacity-25">
                    <i class="fas fa-shield-check me-1 text-warning"></i> Next-Gen Warranty Lifecycle Tracking
                </span>
                <h1 class="display-4 fw-extrabold mb-3 text-white">
                    Protect & Verify Your Products with <span style="color: #a5b4fc;">Zero Friction</span>
                </h1>
                <p class="lead text-light text-opacity-75 mb-4">
                    Instant product warranty verification, unlimited replacement chain tracking, and digital certificate management powered by Fuzura.
                </p>

                <!-- Prominent Public Search Bar -->
                <div class="card p-2 p-sm-3 shadow-lg border-0 rounded-4 bg-white">
                    <form action="{{ route('public.search') }}" method="GET" class="d-flex flex-column flex-sm-row gap-2">
                        <div class="input-group input-group-lg flex-grow-1">
                            <span class="input-group-text bg-transparent border-0 text-muted ps-3">
                                <i class="fas fa-barcode fs-4"></i>
                            </span>
                            <input type="text" name="serial_number" class="form-control border-0 shadow-none ps-2" 
                                   placeholder="Enter Product Serial Number (e.g. FZ-SN-1001)" required>
                        </div>
                        <button type="submit" class="btn btn-primary-custom px-4 py-3 d-flex align-items-center justify-content-center gap-2 fs-6">
                            <i class="fas fa-search"></i>
                            <span>Check Warranty</span>
                        </button>
                    </form>

                    <!-- Quick Sample Chips -->
                    <div class="mt-2 pt-2 border-top border-light-subtle d-flex flex-wrap align-items-center gap-2 small text-muted">
                        <span class="fw-semibold">Try sample serials:</span>
                        <a href="{{ route('public.search', ['serial_number' => 'FZ-SN-1002']) }}" class="badge bg-light text-dark border text-decoration-none py-1 px-2">FZ-SN-1002 (Replaced)</a>
                        <a href="{{ route('public.search', ['serial_number' => 'FZ-SN-1004']) }}" class="badge bg-light text-dark border text-decoration-none py-1 px-2">FZ-SN-1004 (Active Unit)</a>
                        <a href="{{ route('public.search', ['serial_number' => 'FZ-SN-2001']) }}" class="badge bg-light text-dark border text-decoration-none py-1 px-2">FZ-SN-2001 (Active)</a>
                        <a href="{{ route('public.search', ['serial_number' => 'FZ-SN-3001']) }}" class="badge bg-light text-dark border text-decoration-none py-1 px-2">FZ-SN-3001 (Expired)</a>
                    </div>
                </div>
            </div>

            <div class="col-lg-5 d-none d-lg-block">
                <div class="card bg-white bg-opacity-10 backdrop-blur border border-white border-opacity-10 rounded-4 p-4 shadow-lg text-white">
                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <div class="d-flex align-items-center gap-2">
                            <i class="fas fa-certificate text-warning fs-3"></i>
                            <div>
                                <h6 class="mb-0 fw-bold">Live Status Monitor</h6>
                                <small class="text-light-50">Realtime Chain Traversal</small>
                            </div>
                        </div>
                        <span class="badge bg-success bg-opacity-75">Operational</span>
                    </div>

                    <div class="p-3 bg-dark bg-opacity-50 rounded-3 mb-3 border border-white border-opacity-10">
                        <small class="text-light-50 d-block mb-1">Multi-Level Replacement Engine</small>
                        <div class="d-flex align-items-center gap-2 font-monospace small">
                            <span class="badge bg-secondary">Original</span>
                            <i class="fas fa-arrow-right text-muted"></i>
                            <span class="badge bg-secondary">Rep #1</span>
                            <i class="fas fa-arrow-right text-muted"></i>
                            <span class="badge bg-success">Active Leaf</span>
                        </div>
                    </div>

                    <div class="row g-3 text-center">
                        <div class="col-4">
                            <div class="p-2 rounded bg-white bg-opacity-10">
                                <h5 class="mb-0 fw-bold">{{ $totalProducts }}+</h5>
                                <small class="text-light-50">Catalog Units</small>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="p-2 rounded bg-white bg-opacity-10">
                                <h5 class="mb-0 fw-bold">{{ $totalSales }}+</h5>
                                <small class="text-light-50">Units Sold</small>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="p-2 rounded bg-white bg-opacity-10">
                                <h5 class="mb-0 fw-bold">{{ $totalRegistered }}+</h5>
                                <small class="text-light-50">Warranties</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- How It Works Section -->
<section class="py-5 bg-white">
    <div class="container py-4">
        <div class="text-center max-w-700 mx-auto mb-5">
            <span class="text-primary fw-bold text-uppercase small">Simple & Seamless Process</span>
            <h2 class="fw-bold mt-1">How Customer Product Registration Works</h2>
            <p class="text-muted">Register your product in under 2 minutes to protect your investment.</p>
        </div>

        <div class="row g-4">
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm rounded-4 p-4 text-center">
                    <div class="mx-auto mb-3 bg-primary-subtle text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 64px; height: 64px;">
                        <i class="fas fa-shopping-bag fs-4"></i>
                    </div>
                    <h5 class="fw-bold">1. Purchase Product</h5>
                    <p class="text-muted small">
                        Purchase any authentic Fuzura equipment from our authorized dealers network.
                    </p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm rounded-4 p-4 text-center">
                    <div class="mx-auto mb-3 bg-success-subtle text-success rounded-circle d-flex align-items-center justify-content-center" style="width: 64px; height: 64px;">
                        <i class="fas fa-file-invoice-dollar fs-4"></i>
                    </div>
                    <h5 class="fw-bold">2. Upload Bill & Register</h5>
                    <p class="text-muted small">
                        Log in to your customer portal, enter the serial number and upload your purchase invoice.
                    </p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm rounded-4 p-4 text-center">
                    <div class="mx-auto mb-3 bg-warning-subtle text-warning rounded-circle d-flex align-items-center justify-content-center" style="width: 64px; height: 64px;">
                        <i class="fas fa-award fs-4"></i>
                    </div>
                    <h5 class="fw-bold">3. Enjoy Full Warranty</h5>
                    <p class="text-muted small">
                        Instantly obtain your digital warranty certificate with hassle-free replacement service.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Call to Action Banner -->
<section class="py-5 bg-light">
    <div class="container py-3">
        <div class="card border-0 rounded-4 shadow-sm p-4 p-md-5" style="background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);">
            <div class="row align-items-center g-4 text-white">
                <div class="col-lg-8">
                    <h3 class="fw-bold mb-2">Have you purchased a new product recently?</h3>
                    <p class="text-light text-opacity-75 mb-0">
                        Create an account or login to activate your manufacturer warranty coverage and track your device lifecycle.
                    </p>
                </div>
                <div class="col-lg-4 text-lg-end">
                    <a href="{{ route('customer.register') }}" class="btn btn-primary-custom px-4 py-2 me-2">Register Product</a>
                    <a href="{{ route('customer.login') }}" class="btn btn-outline-light px-4 py-2">Customer Login</a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
