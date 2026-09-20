@extends('layouts.admin')

@section('title', 'Serial Number Deep Search')
@section('page_title', 'Serial Number Deep Inspector')

@section('content')
<div class="container-fluid px-0">

    <!-- Search Box Card -->
    <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-white">
        <h5 class="fw-bold mb-1 text-dark">Hardware Serial Number Deep Search</h5>
        <p class="text-muted small mb-3">Search any serial number to inspect its hardware profile, sales invoice, warranty claim, and complete replacement timeline.</p>

        <form action="{{ route('admin.search.index') }}" method="GET" class="row g-2">
            <div class="col-md-9">
                <div class="input-group input-group-lg border rounded-3 overflow-hidden">
                    <span class="input-group-text bg-white border-0 text-muted ps-3"><i class="fas fa-barcode"></i></span>
                    <input type="text" name="serial_number" class="form-control border-0 shadow-none ps-2" 
                           value="{{ $searchedSerial }}" placeholder="Enter Serial Number (e.g. FZ-SN-1002)" required>
                </div>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary-custom w-100 py-3 d-flex align-items-center justify-content-center gap-2">
                    <i class="fas fa-search"></i> Inspect Serial
                </button>
            </div>
        </form>

        <div class="d-flex flex-wrap align-items-center gap-2 mt-3 pt-2 border-top small text-muted">
            <span class="fw-semibold">Sample Serials:</span>
            <a href="{{ route('admin.search.index', ['serial_number' => 'FZ-SN-1002']) }}" class="badge bg-light text-dark border text-decoration-none py-1 px-2">FZ-SN-1002 (Replaced)</a>
            <a href="{{ route('admin.search.index', ['serial_number' => 'FZ-SN-1004']) }}" class="badge bg-light text-dark border text-decoration-none py-1 px-2">FZ-SN-1004 (Active Unit)</a>
            <a href="{{ route('admin.search.index', ['serial_number' => 'FZ-SN-2001']) }}" class="badge bg-light text-dark border text-decoration-none py-1 px-2">FZ-SN-2001 (Sold/Registered)</a>
            <a href="{{ route('admin.search.index', ['serial_number' => 'FZ-SN-3001']) }}" class="badge bg-light text-dark border text-decoration-none py-1 px-2">FZ-SN-3001 (Expired)</a>
        </div>
    </div>

    @if ($searchedSerial !== null)
        @if (!empty($result) && $result['found'])
            <div class="row g-4">
                <!-- Summary Card -->
                <div class="col-12">
                    <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white">
                        <div class="p-4 text-white d-flex flex-wrap justify-content-between align-items-center gap-3"
                             style="background: {{ $result['warranty_status'] === 'active' ? 'linear-gradient(135deg, #059669 0%, #10b981 100%)' : ($result['warranty_status'] === 'expired' ? 'linear-gradient(135deg, #b91c1c 0%, #ef4444 100%)' : 'linear-gradient(135deg, #1e293b 0%, #334155 100%)') }};">
                            <div>
                                <span class="badge bg-white bg-opacity-25 text-white px-2 py-1 text-uppercase fw-semibold mb-2">
                                    {{ $result['has_replacements'] ? 'Multi-Level Replacement Chain' : 'Single Unit Record' }}
                                </span>
                                <h3 class="fw-bold mb-0 font-monospace">{{ $searchedSerial }}</h3>
                                <small class="text-white text-opacity-75">
                                    Model: {{ $result['product']->model_number }} | Warranty: {{ $result['warranty_period_years'] }} Year(s)
                                </small>
                            </div>

                            <div class="text-lg-end">
                                @if ($result['warranty_status'] === 'active')
                                    <span class="badge bg-white text-success fs-6 px-3 py-2 fw-bold">
                                        <i class="fas fa-shield-check me-1"></i> WARRANTY ACTIVE
                                    </span>
                                    <div class="small mt-1 text-white fw-medium">
                                        {{ $result['days_remaining'] }} Days Remaining (Expires: {{ $result['warranty_end_date'] }})
                                    </div>
                                @elseif ($result['warranty_status'] === 'expired')
                                    <span class="badge bg-white text-danger fs-6 px-3 py-2 fw-bold">
                                        <i class="fas fa-triangle-exclamation me-1"></i> WARRANTY EXPIRED
                                    </span>
                                    <div class="small mt-1 text-white fw-medium">
                                        Expired on: {{ $result['warranty_end_date'] }}
                                    </div>
                                @else
                                    <span class="badge bg-white text-secondary fs-6 px-3 py-2 fw-bold">
                                        <i class="fas fa-box me-1"></i> IN STOCK (AVAILABLE)
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="card-body p-4">
                            <div class="row g-3">
                                <div class="col-md-3">
                                    <small class="text-muted d-block text-uppercase fw-semibold" style="font-size: 0.72rem;">Original Serial</small>
                                    <span class="font-monospace fw-bold text-dark">{{ $result['root_serial'] }}</span>
                                </div>
                                <div class="col-md-3">
                                    <small class="text-muted d-block text-uppercase fw-semibold" style="font-size: 0.72rem;">Current Active Unit</small>
                                    <span class="font-monospace fw-bold text-success">{{ $result['active_serial'] }}</span>
                                </div>
                                <div class="col-md-3">
                                    <small class="text-muted d-block text-uppercase fw-semibold" style="font-size: 0.72rem;">Total Chain Swaps</small>
                                    <span class="fw-bold text-dark">{{ $result['total_replacements'] }} Replacement(s)</span>
                                </div>
                                <div class="col-md-3">
                                    <small class="text-muted d-block text-uppercase fw-semibold" style="font-size: 0.72rem;">Registration Status</small>
                                    <div>
                                        @if ($result['registration'])
                                            <span class="badge bg-success-subtle text-success fw-bold">Registered</span>
                                        @else
                                            <span class="badge bg-warning-subtle text-warning fw-bold">Unregistered</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Timeline Visualizer Column -->
                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm rounded-4 p-4 bg-white h-100">
                        <h6 class="fw-bold text-dark mb-3">
                            <i class="fas fa-sitemap text-primary me-2"></i> Complete Replacement History Timeline
                        </h6>
                        <x-replacement-timeline :timeline="$result['timeline']" :searchedSerial="$result['searched_serial']" />
                    </div>
                </div>

                <!-- Hardware & Warranty Details Sidebar -->
                <div class="col-lg-4">
                    <!-- Hardware Specs Card -->
                    <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-white">
                        <h6 class="fw-bold text-dark mb-3"><i class="fas fa-microchip text-primary me-2"></i> Hardware Master Details</h6>
                        <ul class="list-unstyled small d-flex flex-column gap-2 mb-0">
                            <li class="d-flex justify-content-between">
                                <span class="text-muted">Mfg Date:</span>
                                <span class="fw-semibold text-dark">{{ $result['product']->manufacturing_date ? $result['product']->manufacturing_date->format('d M, Y') : 'N/A' }}</span>
                            </li>
                            <li class="d-flex justify-content-between">
                                <span class="text-muted">Spare Vendor:</span>
                                <span class="fw-semibold text-dark">{{ $result['product']->spare_vendor ?? 'Direct MFG' }}</span>
                            </li>
                            <li class="pt-2 border-top">
                                <span class="text-muted d-block">Spare Details:</span>
                                <span class="text-dark">{{ $result['product']->spare_details ?? 'Standard assembly' }}</span>
                            </li>
                        </ul>
                    </div>

                    <!-- Customer & Dealer Card -->
                    <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
                        <h6 class="fw-bold text-dark mb-3"><i class="fas fa-user-tag text-success me-2"></i> Sale & Customer Info</h6>
                        @if ($result['sale'])
                            <ul class="list-unstyled small d-flex flex-column gap-2 mb-0">
                                <li class="d-flex justify-content-between">
                                    <span class="text-muted">Customer:</span>
                                    <span class="fw-semibold text-dark">{{ $result['sale']->customer_name }}</span>
                                </li>
                                <li class="d-flex justify-content-between">
                                    <span class="text-muted">Mobile:</span>
                                    <span class="fw-semibold text-dark">{{ $result['sale']->customer_mobile }}</span>
                                </li>
                                <li class="d-flex justify-content-between">
                                    <span class="text-muted">Dealer:</span>
                                    <span class="fw-semibold text-dark">{{ $result['sale']->dealer_name }}</span>
                                </li>
                                <li class="d-flex justify-content-between">
                                    <span class="text-muted">Invoice #:</span>
                                    <span class="fw-semibold font-monospace">{{ $result['sale']->invoice_number ?? 'N/A' }}</span>
                                </li>
                            </ul>
                        @else
                            <p class="text-muted small mb-0">No sales record registered for this product.</p>
                        @endif
                    </div>
                </div>
            </div>
        @else
            <!-- Not Found State -->
            <div class="card border-0 shadow-sm rounded-4 p-5 text-center bg-white">
                <i class="fas fa-circle-xmark text-danger display-4 mb-3"></i>
                <h4 class="fw-bold text-dark">No Product Found</h4>
                <p class="text-muted small mb-0">The serial number <strong>"{{ $searchedSerial }}"</strong> does not exist in the database.</p>
            </div>
        @endif
    @endif
</div>
@endsection
