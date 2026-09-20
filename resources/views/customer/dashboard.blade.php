@extends('layouts.customer')

@section('title', 'Customer Dashboard')
@section('page_title', 'My Hardware & Warranties')

@section('content')
<div class="container-fluid px-0">

    <!-- Welcome Card -->
    <div class="card border-0 rounded-4 shadow-sm p-4 mb-4 text-white" 
         style="background: linear-gradient(135deg, #312e81 0%, #1e1b4b 100%);">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
            <div>
                <span class="badge bg-white bg-opacity-25 text-white px-2 py-1 text-uppercase fw-semibold mb-2">Customer Portal</span>
                <h3 class="fw-bold mb-1">Welcome, {{ $customer->name }}!</h3>
                <p class="text-light text-opacity-75 small mb-0">
                    Track your registered hardware, check active warranty periods, and download official certificates.
                </p>
            </div>
            <div>
                <a href="{{ route('customer.products.register') }}" class="btn btn-warning fw-bold px-4 py-2 shadow-sm">
                    <i class="fas fa-plus-circle me-1"></i> Register New Equipment
                </a>
            </div>
        </div>
    </div>

    <!-- Stats Row -->
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="metric-card h-100">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="metric-title">My Registered Units</div>
                        <div class="metric-value">{{ $totalRegistered }}</div>
                    </div>
                    <div class="metric-icon-box bg-primary-subtle text-primary">
                        <i class="fas fa-boxes-stacked"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="metric-card h-100">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="metric-title">Active Coverage</div>
                        <div class="metric-value text-success">{{ $activeWarranties }}</div>
                    </div>
                    <div class="metric-icon-box bg-success-subtle text-success">
                        <i class="fas fa-shield-check"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="metric-card h-100">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="metric-title">Expired Coverage</div>
                        <div class="metric-value text-danger">{{ $expiredWarranties }}</div>
                    </div>
                    <div class="metric-icon-box bg-danger-subtle text-danger">
                        <i class="fas fa-calendar-xmark"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="metric-card h-100">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="metric-title">Pending Claims</div>
                        <div class="metric-value text-warning">{{ $pendingRegistrations }}</div>
                    </div>
                    <div class="metric-icon-box bg-warning-subtle text-warning">
                        <i class="fas fa-clock"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- My Products Overview Table -->
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h6 class="fw-bold mb-0 text-dark">Recent Equipment Registrations</h6>
            <a href="{{ route('customer.products.index') }}" class="btn btn-sm btn-outline-primary px-3">View All Products</a>
        </div>

        <div class="table-responsive">
            <table class="table table-custom mb-0 align-middle">
                <thead>
                    <tr>
                        <th>Product Serial</th>
                        <th>Model</th>
                        <th>Purchase Date</th>
                        <th>Warranty Expiry</th>
                        <th>Status</th>
                        <th class="text-end">Certificate</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($recentRegistrations as $reg)
                        @php
                            $isExp = $reg->isExpired();
                        @endphp
                        <tr>
                            <td>
                                <span class="font-monospace fw-bold text-dark">{{ $reg->product_serial_number }}</span>
                            </td>
                            <td><span class="badge bg-primary-subtle text-primary">{{ $reg->product_model }}</span></td>
                            <td>{{ $reg->purchase_date->format('d M, Y') }}</td>
                            <td>
                                <div class="fw-semibold {{ $isExp ? 'text-danger' : 'text-success' }}">
                                    {{ $reg->warranty_end_date->format('d M, Y') }}
                                </div>
                                <small class="{{ $isExp ? 'text-danger' : 'text-muted' }}">
                                    {{ $isExp ? 'Coverage Expired' : $reg->daysRemaining() . ' days remaining' }}
                                </small>
                            </td>
                            <td>
                                @if ($reg->status === 'approved')
                                    <span class="badge badge-soft-success fw-bold"><i class="fas fa-check-circle me-1"></i> Active</span>
                                @elseif ($reg->status === 'pending')
                                    <span class="badge badge-soft-warning fw-bold"><i class="fas fa-clock me-1"></i> Pending</span>
                                @else
                                    <span class="badge badge-soft-danger fw-bold"><i class="fas fa-ban me-1"></i> Rejected</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <a href="{{ route('customer.products.certificate', $reg->id) }}" class="btn btn-sm btn-outline-secondary py-1 px-3">
                                    <i class="fas fa-certificate text-warning me-1"></i> Certificate
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="fas fa-shield-halved fs-1 text-secondary opacity-50 mb-3 d-block"></i>
                                <h5>No Products Registered Yet</h5>
                                <p class="small mb-3">Register your first purchased equipment to activate manufacturer warranty.</p>
                                <a href="{{ route('customer.products.register') }}" class="btn btn-primary-custom btn-sm">
                                    Register Equipment Now
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
