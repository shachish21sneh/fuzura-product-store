@extends('layouts.public')

@section('title', 'Verify Product Serial & Warranty Timeline - Fuzura')

@section('content')
<div class="container py-5">
    <!-- Search Bar Card -->
    <div class="row justify-content-center mb-5">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 rounded-4 p-4">
                <div class="text-center mb-4">
                    <span class="badge bg-primary-subtle text-primary fw-bold text-uppercase px-3 py-1 mb-2">Public Verification</span>
                    <h3 class="fw-bold mb-1">Verify Product Serial Number</h3>
                    <p class="text-muted small">Enter your unit's serial number to check authenticity, warranty status, and complete replacement history.</p>
                </div>

                <form action="{{ route('public.search') }}" method="GET">
                    <div class="input-group input-group-lg shadow-sm rounded-3 overflow-hidden border">
                        <span class="input-group-text bg-white border-0 text-muted ps-3">
                            <i class="fas fa-barcode"></i>
                        </span>
                        <input type="text" name="serial_number" class="form-control border-0 shadow-none ps-2" 
                               value="{{ $searchedSerial }}" placeholder="e.g. FZ-SN-1002" required>
                        <button type="submit" class="btn btn-primary-custom px-4">
                            <i class="fas fa-search me-1"></i> Search
                        </button>
                    </div>
                </form>

                <!-- Sample Chips for Testing -->
                <div class="d-flex flex-wrap align-items-center gap-2 mt-3 pt-2 border-top small text-muted">
                    <span class="fw-semibold">Try sample serials:</span>
                    <a href="{{ route('public.search', ['serial_number' => 'FZ-SN-1002']) }}" class="badge bg-light text-dark border text-decoration-none py-1 px-2">FZ-SN-1002 (Replaced Step)</a>
                    <a href="{{ route('public.search', ['serial_number' => 'FZ-SN-1004']) }}" class="badge bg-light text-dark border text-decoration-none py-1 px-2">FZ-SN-1004 (Active Unit)</a>
                    <a href="{{ route('public.search', ['serial_number' => 'FZ-SN-2001']) }}" class="badge bg-light text-dark border text-decoration-none py-1 px-2">FZ-SN-2001 (Active)</a>
                    <a href="{{ route('public.search', ['serial_number' => 'FZ-SN-3001']) }}" class="badge bg-light text-dark border text-decoration-none py-1 px-2">FZ-SN-3001 (Expired)</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Search Results Area -->
    @if ($searchedSerial !== null)
        @if (!empty($result) && $result['found'])
            <div class="row g-4">
                <!-- Summary Card -->
                <div class="col-lg-12">
                    <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
                        <div class="p-4 text-white d-flex flex-wrap align-items-center justify-content-between gap-3" 
                             style="background: {{ $result['warranty_status'] === 'active' ? 'linear-gradient(135deg, #059669 0%, #10b981 100%)' : ($result['warranty_status'] === 'expired' ? 'linear-gradient(135deg, #b91c1c 0%, #ef4444 100%)' : 'linear-gradient(135deg, #1e293b 0%, #334155 100%)') }};">
                            <div>
                                <span class="badge bg-white bg-opacity-25 text-white px-2 py-1 text-uppercase fw-semibold mb-2">
                                    {{ $result['has_replacements'] ? 'Replacement Chain Verified' : 'Product Found' }}
                                </span>
                                <h3 class="fw-bold mb-0 font-monospace">{{ $searchedSerial }}</h3>
                                <small class="text-white text-opacity-75">
                                    Model: {{ $result['product']->model_number }} | Warranty Period: {{ $result['warranty_period_years'] }} Year(s)
                                </small>
                            </div>

                            <div class="text-lg-end">
                                @if ($result['warranty_status'] === 'active')
                                    <span class="badge bg-white text-success fs-6 px-3 py-2 fw-bold shadow-sm">
                                        <i class="fas fa-shield-check me-1"></i> WARRANTY ACTIVE
                                    </span>
                                    <div class="small mt-1 text-white fw-medium">
                                        {{ $result['days_remaining'] }} Days Remaining (Expires: {{ $result['warranty_end_date'] }})
                                    </div>
                                @elseif ($result['warranty_status'] === 'expired')
                                    <span class="badge bg-white text-danger fs-6 px-3 py-2 fw-bold shadow-sm">
                                        <i class="fas fa-triangle-exclamation me-1"></i> WARRANTY EXPIRED
                                    </span>
                                    <div class="small mt-1 text-white fw-medium">
                                        Expired on: {{ $result['warranty_end_date'] }}
                                    </div>
                                @else
                                    <span class="badge bg-white text-secondary fs-6 px-3 py-2 fw-bold shadow-sm">
                                        <i class="fas fa-boxes-stacked me-1"></i> AVAILABLE IN STOCK
                                    </span>
                                    <div class="small mt-1 text-white fw-medium">Not yet sold or registered</div>
                                @endif
                            </div>
                        </div>

                        <!-- Meta Bar -->
                        <div class="card-body p-4 bg-white">
                            <div class="row g-4 text-center text-sm-start">
                                <div class="col-sm-6 col-md-3 border-end-md">
                                    <small class="text-muted text-uppercase fw-semibold" style="font-size: 0.72rem;">Original Serial</small>
                                    <div class="fw-bold text-dark font-monospace">{{ $result['root_serial'] }}</div>
                                </div>
                                <div class="col-sm-6 col-md-3 border-end-md">
                                    <small class="text-muted text-uppercase fw-semibold" style="font-size: 0.72rem;">Current Active Unit</small>
                                    <div class="fw-bold text-success font-monospace">{{ $result['active_serial'] }}</div>
                                </div>
                                <div class="col-sm-6 col-md-3 border-end-md">
                                    <small class="text-muted text-uppercase fw-semibold" style="font-size: 0.72rem;">Purchase / Bill Date</small>
                                    <div class="fw-bold text-dark">{{ $result['warranty_start_date'] ?? 'N/A' }}</div>
                                </div>
                                <div class="col-sm-6 col-md-3">
                                    <small class="text-muted text-uppercase fw-semibold" style="font-size: 0.72rem;">Registration Status</small>
                                    <div>
                                        @if ($result['registration'])
                                            <span class="badge bg-success-subtle text-success fw-bold">Registered</span>
                                        @else
                                            <span class="badge bg-warning-subtle text-warning fw-bold">Not Registered</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Complete Replacement Timeline Section -->
                <div class="col-lg-8">
                    <div class="card shadow-sm border-0 rounded-4 p-4 h-100 bg-white">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <h5 class="fw-bold text-dark mb-0">
                                <i class="fas fa-clock-rotate-left text-primary me-2"></i>
                                Product Lifecycle & Replacement Timeline
                            </h5>
                            <span class="badge bg-light text-dark border">
                                {{ count($result['timeline']) }} Record(s) in Chain
                            </span>
                        </div>

                        <p class="text-muted small mb-4">
                            Timeline traces every unit from original sale through all warranty replacements up to the active device.
                        </p>

                        <!-- Timeline Component -->
                        <x-replacement-timeline :timeline="$result['timeline']" :searchedSerial="$result['searched_serial']" />
                    </div>
                </div>

                <!-- Registration & Customer Details Sidebar -->
                <div class="col-lg-4">
                    <div class="card shadow-sm border-0 rounded-4 p-4 mb-4 bg-white">
                        <h6 class="fw-bold text-dark mb-3">
                            <i class="fas fa-store text-primary me-2"></i> Dealer & Sale Info
                        </h6>

                        @if ($result['sale'])
                            <ul class="list-unstyled small d-flex flex-column gap-2 mb-0">
                                <li class="d-flex justify-content-between">
                                    <span class="text-muted">Dealer:</span>
                                    <span class="fw-semibold text-dark">{{ $result['sale']->dealer_name }}</span>
                                </li>
                                <li class="d-flex justify-content-between">
                                    <span class="text-muted">Bill Date:</span>
                                    <span class="fw-semibold text-dark">{{ $result['sale']->bill_date->format('d M, Y') }}</span>
                                </li>
                                @if ($result['sale']->invoice_number)
                                    <li class="d-flex justify-content-between">
                                        <span class="text-muted">Invoice #:</span>
                                        <span class="fw-semibold font-monospace">{{ $result['sale']->invoice_number }}</span>
                                    </li>
                                @endif
                                @if ($result['sale']->dealer_address)
                                    <li class="pt-2 border-top">
                                        <span class="text-muted d-block">Dealer Address:</span>
                                        <span class="text-dark">{{ $result['sale']->dealer_address }}</span>
                                    </li>
                                @endif
                            </ul>
                        @else
                            <p class="text-muted small mb-0">No direct sales record recorded for this product yet.</p>
                        @endif
                    </div>

                    @if ($result['registration'])
                        <div class="card shadow-sm border-0 rounded-4 p-4 bg-white">
                            <h6 class="fw-bold text-dark mb-3">
                                <i class="fas fa-id-card text-success me-2"></i> Warranty Registration
                            </h6>

                            <ul class="list-unstyled small d-flex flex-column gap-2 mb-3">
                                <li class="d-flex justify-content-between">
                                    <span class="text-muted">Customer Name:</span>
                                    <span class="fw-semibold text-dark">{{ $result['registration']->customer_name }}</span>
                                </li>
                                <li class="d-flex justify-content-between">
                                    <span class="text-muted">Mobile:</span>
                                    <span class="fw-semibold text-dark">{{ substr($result['registration']->customer_mobile, 0, 4) }}****{{ substr($result['registration']->customer_mobile, -2) }}</span>
                                </li>
                                <li class="d-flex justify-content-between">
                                    <span class="text-muted">Registered On:</span>
                                    <span class="fw-semibold text-dark">{{ $result['registration']->created_at->format('d M, Y') }}</span>
                                </li>
                            </ul>

                            @if ($result['registration']->bill_path)
                                <div class="border rounded-3 p-2 text-center bg-light">
                                    <small class="text-muted d-block mb-2">Original Purchase Bill Verified</small>
                                    <span class="badge bg-secondary">
                                        <i class="fas fa-file-check me-1"></i> Bill Attached on Record
                                    </span>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        @else
            <!-- Product Not Found Empty State -->
            <div class="row justify-content-center">
                <div class="col-lg-6">
                    <div class="card shadow-sm border-0 rounded-4 p-5 text-center bg-white">
                        <div class="mx-auto mb-3 text-danger rounded-circle bg-danger-subtle d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                            <i class="fas fa-circle-xmark fs-1"></i>
                        </div>
                        <h4 class="fw-bold text-dark mb-2">No Product Found</h4>
                        <p class="text-muted small mb-4">
                            We could not locate any product or replacement record matching serial number 
                            <strong class="text-dark font-monospace">"{{ $searchedSerial }}"</strong>.
                        </p>

                        <div class="p-3 bg-light rounded-3 text-start small mb-4">
                            <strong class="d-block mb-1 text-dark"><i class="fas fa-lightbulb text-warning me-1"></i> Helpful Tips:</strong>
                            <ul class="mb-0 ps-3 text-muted">
                                <li>Check for typos or letters vs numbers (e.g. 0 vs O).</li>
                                <li>Check the back or underside label of your hardware unit.</li>
                                <li>If newly purchased, verify with your authorized dealer.</li>
                            </ul>
                        </div>

                        <div class="d-flex justify-content-center gap-2">
                            <a href="{{ route('public.search') }}" class="btn btn-outline-secondary px-3">Try Another Search</a>
                            <a href="{{ route('public.contact') }}" class="btn btn-primary-custom px-3">Contact Support</a>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endif
</div>
@endsection
