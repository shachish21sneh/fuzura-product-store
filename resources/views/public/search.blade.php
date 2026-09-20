@extends('layouts.public')

@section('title', 'Verify Product Serial & Warranty Timeline - Fuzura')

@section('content')
<!-- Search Hero Header -->
<section class="py-5" style="background: linear-gradient(135deg, #080c15 0%, #0f172a 100%); color: #fff; border-bottom: 1px solid rgba(255,255,255,0.06);">
    <div class="container py-3">
        <div class="row justify-content-center text-center">
            <div class="col-lg-8">
                <span class="badge mb-3" style="background: rgba(99, 102, 241, 0.15); border: 1px solid rgba(99, 102, 241, 0.3); color: #a5b4fc; font-weight: 700; font-size: 0.8rem; padding: 0.4rem 0.9rem; border-radius: 9999px;">
                    <i class="fas fa-fingerprint me-1 text-warning"></i> PUBLIC VERIFICATION CONSOLE
                </span>
                <h1 class="display-5 fw-extrabold mb-3" style="letter-spacing: -0.035em;">
                    Verify Product Serial & <span class="text-gradient-accent">Warranty History</span>
                </h1>
                <p class="lead mb-4" style="color: #cbd5e1; font-size: 1.05rem;">
                    Instant tamper-proof check for authenticity, active coverage, and full N-tier replacement history.
                </p>

                <!-- Search Card -->
                <div class="search-console-card p-3 p-sm-4 text-start mx-auto" style="max-width: 720px;">
                    <form action="{{ route('public.search') }}" method="GET" class="d-flex flex-column flex-sm-row gap-2">
                        <div class="input-group flex-grow-1">
                            <span class="input-group-text bg-transparent border-0 text-muted ps-2 pe-0">
                                <i class="fas fa-barcode fs-4" style="color: #818cf8;"></i>
                            </span>
                            <input type="text" name="serial_number" class="form-control search-console-input font-mono" 
                                   value="{{ $searchedSerial }}" placeholder="Enter Serial (e.g. FZ-SN-1002)" required autocomplete="off">
                        </div>
                        <button type="submit" class="btn btn-primary-custom px-4 py-3 d-flex align-items-center justify-content-center gap-2">
                            <i class="fas fa-bolt text-warning"></i>
                            <span>Verify Unit</span>
                        </button>
                    </form>

                    <!-- Sample Chips for Testing -->
                    <div class="d-flex flex-wrap align-items-center gap-2 mt-3 pt-3" style="border-top: 1px solid rgba(255,255,255,0.08);">
                        <span class="small fw-semibold" style="color: #94a3b8; font-size: 0.78rem;">Quick Test Chips:</span>
                        <a href="{{ route('public.search', ['serial_number' => 'FZ-SN-1002']) }}" class="sample-chip">
                            <i class="fas fa-repeat text-warning"></i> FZ-SN-1002 <span class="opacity-75">(Step 1)</span>
                        </a>
                        <a href="{{ route('public.search', ['serial_number' => 'FZ-SN-1004']) }}" class="sample-chip">
                            <i class="fas fa-circle-check text-success"></i> FZ-SN-1004 <span class="opacity-75">(Active)</span>
                        </a>
                        <a href="{{ route('public.search', ['serial_number' => 'FZ-SN-2001']) }}" class="sample-chip">
                            <i class="fas fa-shield text-info"></i> FZ-SN-2001 <span class="opacity-75">(Active)</span>
                        </a>
                        <a href="{{ route('public.search', ['serial_number' => 'FZ-SN-3001']) }}" class="sample-chip">
                            <i class="fas fa-clock text-danger"></i> FZ-SN-3001 <span class="opacity-75">(Expired)</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Search Results Area -->
<section class="py-5" style="background-color: #f8fafc;">
    <div class="container">
        @if ($searchedSerial !== null)
            @if (!empty($result) && $result['found'])
                <div class="row g-4">
                    <!-- Summary Card -->
                    <div class="col-lg-12">
                        <div class="bento-card p-0 overflow-hidden shadow-sm border-0">
                            <div class="p-4 text-white d-flex flex-wrap align-items-center justify-content-between gap-3" 
                                 style="background: {{ $result['warranty_status'] === 'active' ? 'linear-gradient(135deg, #065f46 0%, #059669 50%, #10b981 100%)' : ($result['warranty_status'] === 'expired' ? 'linear-gradient(135deg, #7f1d1d 0%, #b91c1c 50%, #ef4444 100%)' : 'linear-gradient(135deg, #0f172a 0%, #1e293b 100%)') }};">
                                <div>
                                    <span class="badge px-3 py-1.5 text-uppercase fw-bold mb-2 font-mono" style="background: rgba(255,255,255,0.2); border: 1px solid rgba(255,255,255,0.3); font-size: 0.72rem;">
                                        <i class="fas fa-shield-halved me-1"></i>
                                        {{ $result['has_replacements'] ? 'Verified Multi-Level Chain' : 'Single Product Record' }}
                                    </span>
                                    <h2 class="fw-extrabold mb-1 font-mono text-white">{{ $searchedSerial }}</h2>
                                    <small class="text-white text-opacity-90">
                                        Model: <strong class="text-white">{{ $result['product']->model_number }}</strong> ({{ $result['product']->category }}) • Manufacturer Warranty: {{ $result['warranty_period_years'] }} Year(s)
                                    </small>
                                </div>

                                <div class="text-lg-end">
                                    @if ($result['warranty_status'] === 'active')
                                        <span class="badge bg-white text-success fs-6 px-3 py-2 fw-bold shadow-sm">
                                            <i class="fas fa-circle-check me-1"></i> WARRANTY ACTIVE
                                        </span>
                                        <div class="small mt-1 text-white fw-medium">
                                            <span class="fw-bold font-mono">{{ $result['days_remaining'] }}</span> Days Remaining (Valid till: {{ $result['warranty_end_date'] }})
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
                            <div class="p-4 bg-white border-top">
                                <div class="row g-4 text-center text-sm-start">
                                    <div class="col-sm-6 col-md-3 border-end-md">
                                        <small class="text-muted text-uppercase fw-bold" style="font-size: 0.72rem; letter-spacing: 0.05em;">Original Sold Serial</small>
                                        <div class="fw-bold text-dark font-mono fs-6">{{ $result['root_serial'] }}</div>
                                    </div>
                                    <div class="col-sm-6 col-md-3 border-end-md">
                                        <small class="text-muted text-uppercase fw-bold" style="font-size: 0.72rem; letter-spacing: 0.05em;">Current Active Unit</small>
                                        <div class="fw-bold text-success font-mono fs-6">{{ $result['active_serial'] }}</div>
                                    </div>
                                    <div class="col-sm-6 col-md-3 border-end-md">
                                        <small class="text-muted text-uppercase fw-bold" style="font-size: 0.72rem; letter-spacing: 0.05em;">Original Tax Invoice Date</small>
                                        <div class="fw-bold text-dark fs-6">{{ $result['warranty_start_date'] ?? 'Pending Registration' }}</div>
                                    </div>
                                    <div class="col-sm-6 col-md-3">
                                        <small class="text-muted text-uppercase fw-bold" style="font-size: 0.72rem; letter-spacing: 0.05em;">Registration Status</small>
                                        <div>
                                            @if ($result['registration'])
                                                <span class="badge badge-soft-success fw-bold font-mono">VERIFIED REGISTERED</span>
                                            @else
                                                <span class="badge badge-soft-warning fw-bold font-mono">UNREGISTERED</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Complete Replacement Timeline Section -->
                    <div class="col-lg-8">
                        <div class="bento-card h-100">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <h5 class="fw-bold text-dark mb-0">
                                    <i class="fas fa-network-wired text-primary me-2"></i>
                                    Product Lifecycle & Replacement Lineage
                                </h5>
                                <span class="badge badge-soft-primary font-mono">
                                    {{ count($result['timeline']) }} Node(s) in Chain
                                </span>
                            </div>

                            <p class="text-muted small mb-4">
                                This immutable graph maps every physical device iteration from the first retail sale through warranty swaps up to the terminal in-use unit.
                            </p>

                            <!-- Timeline Component -->
                            <x-replacement-timeline :timeline="$result['timeline']" :searchedSerial="$result['searched_serial']" />
                        </div>
                    </div>

                    <!-- Registration & Customer Details Sidebar -->
                    <div class="col-lg-4">
                        <div class="bento-card mb-4">
                            <h6 class="fw-bold text-dark mb-3">
                                <i class="fas fa-store text-primary me-2"></i> Authorized Dealer Record
                            </h6>

                            @if ($result['sale'])
                                <ul class="list-unstyled small d-flex flex-column gap-2.5 mb-0">
                                    <li class="d-flex justify-content-between">
                                        <span class="text-muted">Dealer Name:</span>
                                        <span class="fw-bold text-dark">{{ $result['sale']->dealer_name }}</span>
                                    </li>
                                    <li class="d-flex justify-content-between">
                                        <span class="text-muted">Bill Date:</span>
                                        <span class="fw-bold text-dark">{{ $result['sale']->bill_date->format('d M, Y') }}</span>
                                    </li>
                                    @if ($result['sale']->invoice_number)
                                        <li class="d-flex justify-content-between">
                                            <span class="text-muted">Invoice Number:</span>
                                            <span class="fw-bold font-mono text-primary">{{ $result['sale']->invoice_number }}</span>
                                        </li>
                                    @endif
                                    @if ($result['sale']->dealer_address)
                                        <li class="pt-2 border-top">
                                            <span class="text-muted d-block mb-1">Dealer Location:</span>
                                            <span class="text-dark">{{ $result['sale']->dealer_address }}</span>
                                        </li>
                                    @endif
                                </ul>
                            @else
                                <p class="text-muted small mb-0">No direct point-of-sale record registered yet.</p>
                            @endif
                        </div>

                        @if ($result['registration'])
                            <div class="bento-card">
                                <h6 class="fw-bold text-dark mb-3">
                                    <i class="fas fa-certificate text-success me-2"></i> Customer Registration
                                </h6>

                                <ul class="list-unstyled small d-flex flex-column gap-2.5 mb-3">
                                    <li class="d-flex justify-content-between">
                                        <span class="text-muted">Customer Name:</span>
                                        <span class="fw-bold text-dark">{{ $result['registration']->customer_name }}</span>
                                    </li>
                                    <li class="d-flex justify-content-between">
                                        <span class="text-muted">Contact Mobile:</span>
                                        <span class="fw-bold font-mono text-dark">{{ substr($result['registration']->customer_mobile, 0, 4) }}****{{ substr($result['registration']->customer_mobile, -2) }}</span>
                                    </li>
                                    <li class="d-flex justify-content-between">
                                        <span class="text-muted">Activation Date:</span>
                                        <span class="fw-bold text-dark">{{ $result['registration']->created_at->format('d M, Y') }}</span>
                                    </li>
                                </ul>

                                @if ($result['registration']->bill_path)
                                    <div class="p-2.5 rounded-3 text-center" style="background: #f0fdf4; border: 1px solid #bbf7d0;">
                                        <small class="text-success fw-semibold d-block mb-1">Verified Purchase Bill</small>
                                        <span class="badge bg-success font-mono">
                                            <i class="fas fa-file-shield me-1"></i> Original Bill Stored
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
                        <div class="bento-card p-5 text-center">
                            <div class="mx-auto mb-3 text-danger rounded-circle d-flex align-items-center justify-content-center" 
                                 style="width: 72px; height: 72px; background: #fef2f2; border: 1px solid #fecaca;">
                                <i class="fas fa-circle-xmark fs-2"></i>
                            </div>
                            <h4 class="fw-bold text-dark mb-2">No Matching Serial Found</h4>
                            <p class="text-muted small mb-4">
                                We could not locate any registered product or replacement event matching serial 
                                <strong class="text-dark font-mono">"{{ $searchedSerial }}"</strong>.
                            </p>

                            <div class="p-3 rounded-3 text-start small mb-4" style="background: #f8fafc; border: 1px solid #e2e8f0;">
                                <strong class="d-block mb-2 text-dark"><i class="fas fa-lightbulb text-warning me-1"></i> Verification Tips:</strong>
                                <ul class="mb-0 ps-3 text-muted">
                                    <li>Double check characters (e.g. number 0 vs letter O).</li>
                                    <li>Refer to the barcode label on your packaging box or warranty card.</li>
                                    <li>Confirm your dealer has recorded the product sale.</li>
                                </ul>
                            </div>

                            <div class="d-flex justify-content-center gap-2">
                                <a href="{{ route('public.search') }}" class="btn btn-outline-secondary px-3">Try Another Serial</a>
                                <a href="{{ route('public.contact') }}" class="btn btn-primary-custom px-3">Contact Support Desk</a>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        @endif
    </div>
</section>
@endsection
