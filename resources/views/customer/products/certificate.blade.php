@extends('layouts.customer')

@section('title', 'Warranty Certificate - ' . $warranty->product_serial_number)
@section('page_title', 'Certificate of Warranty Coverage')

@section('content')
<div class="container-fluid px-0">

    <!-- Action Bar (Not visible in print) -->
    <div class="d-flex justify-content-between align-items-center mb-4 no-print">
        <a href="{{ route('customer.products.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i> Back to My Products
        </a>
        <button type="button" class="btn btn-primary-custom px-4" onclick="window.print()">
            <i class="fas fa-print me-1"></i> Print / Download Certificate
        </button>
    </div>

    <!-- Printable Certificate Frame -->
    <div class="certificate-frame">
        <div class="certificate-watermark">FUZURA OFFICIAL</div>

        <!-- Certificate Header -->
        <div class="text-center mb-4 pb-3 border-bottom border-2 border-dark">
            <div class="d-flex align-items-center justify-content-center gap-2 mb-2">
                <i class="fas fa-shield-halved fs-1 text-dark"></i>
                <h2 class="fw-extrabold mb-0 text-dark tracking-wide">FUZURA HARDWARE SYSTEMS</h2>
            </div>
            <p class="text-uppercase fw-bold text-secondary mb-0 letter-spacing-2" style="letter-spacing: 0.2em; font-size: 0.85rem;">
                Official Certificate of Limited Hardware Warranty
            </p>
            <div class="font-monospace small text-muted mt-1">CERTIFICATE REF: FZ-CERT-{{ str_pad($warranty->id, 6, '0', STR_PAD_LEFT) }}</div>
        </div>

        <!-- Certificate Body -->
        <div class="py-3">
            <p class="text-center fs-6 text-dark mb-4">
                This document certifies that the hardware equipment identified below is enrolled under authentic manufacturer limited warranty coverage pursuant to Fuzura global warranty terms and conditions.
            </p>

            <div class="row g-4 p-4 rounded-3 border border-secondary border-opacity-25 bg-light mb-4">
                <div class="col-6">
                    <small class="text-muted d-block text-uppercase fw-semibold" style="font-size: 0.72rem;">Registered Product Serial</small>
                    <span class="fs-5 fw-bold font-monospace text-dark">{{ $warranty->product_serial_number }}</span>
                </div>
                <div class="col-6">
                    <small class="text-muted d-block text-uppercase fw-semibold" style="font-size: 0.72rem;">Equipment Model</small>
                    <span class="fs-5 fw-bold text-dark">{{ $warranty->product_model }}</span>
                </div>

                <div class="col-6">
                    <small class="text-muted d-block text-uppercase fw-semibold" style="font-size: 0.72rem;">Registered Owner</small>
                    <span class="fw-bold text-dark">{{ $warranty->customer_name }}</span>
                    <small class="text-muted d-block">{{ $warranty->customer_email }}</small>
                </div>
                <div class="col-6">
                    <small class="text-muted d-block text-uppercase fw-semibold" style="font-size: 0.72rem;">Authorized Dealer</small>
                    <span class="fw-bold text-dark">{{ $warranty->dealer_name }}</span>
                    <small class="text-muted d-block">{{ $warranty->dealer_address ?? 'Authorized Reseller Network' }}</small>
                </div>

                <div class="col-6">
                    <small class="text-muted d-block text-uppercase fw-semibold" style="font-size: 0.72rem;">Purchase Date</small>
                    <span class="fw-bold text-dark">{{ $warranty->purchase_date->format('d F, Y') }}</span>
                </div>
                <div class="col-6">
                    <small class="text-muted d-block text-uppercase fw-semibold" style="font-size: 0.72rem;">Warranty Expiry Date</small>
                    <span class="fw-bold {{ $warranty->isExpired() ? 'text-danger' : 'text-success' }}">
                        {{ $warranty->warranty_end_date->format('d F, Y') }}
                    </span>
                </div>
            </div>

            <!-- Barcode & Stamp Row -->
            <div class="row align-items-center pt-3">
                <div class="col-6 text-start">
                    <div class="font-monospace fw-bold small text-muted mb-1">SERIAL BARCODE STAMP</div>
                    <div class="p-2 bg-white border border-dark d-inline-block font-monospace fs-4 text-center tracking-widest" style="letter-spacing: 0.35em;">
                        ||||| ||| |||| || ||||| ||||
                    </div>
                    <div class="small font-monospace text-muted mt-1">{{ $warranty->product_serial_number }}</div>
                </div>
                <div class="col-6 text-end">
                    <div class="d-inline-block border border-2 border-dark rounded-circle p-3 text-center" style="width: 100px; height: 100px;">
                        <i class="fas fa-award fs-3 text-dark mb-1"></i>
                        <div class="small fw-bold text-dark" style="font-size: 0.65rem; line-height: 1.1;">FUZURA<br>OFFICIAL<br>SEAL</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Certificate Footer Notice -->
        <div class="mt-4 pt-3 border-top border-dark text-center small text-muted">
            For warranty claims, technical inspections, or replacement servicing, please present this certificate alongside your serial number.
            Online validation available 24/7 at: <strong class="text-dark">{{ url('/search?serial_number=' . $warranty->product_serial_number) }}</strong>
        </div>
    </div>
</div>
@endsection
