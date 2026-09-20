@extends('layouts.public')

@section('title', 'About Us - Fuzura Product Store')

@section('content')
<div class="container py-5">
    <div class="row align-items-center g-5 mb-5">
        <div class="col-lg-6">
            <span class="badge bg-primary-subtle text-primary fw-bold px-3 py-1 mb-2">About Fuzura</span>
            <h1 class="display-5 fw-bold text-dark mb-3">Enterprise Grade Product & Warranty Transparency</h1>
            <p class="lead text-muted mb-4">
                At Fuzura, we believe product ownership should come with total peace of mind. Our state-of-the-art warranty tracking platform ensures every serial number, sale, and replacement is permanently verifiable.
            </p>
            <div class="row g-3">
                <div class="col-sm-6">
                    <div class="d-flex align-items-start gap-2">
                        <i class="fas fa-check-circle text-success fs-5 mt-1"></i>
                        <div>
                            <strong class="d-block text-dark">Immutable Records</strong>
                            <small class="text-muted">Direct traceability from factory to end user.</small>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="d-flex align-items-start gap-2">
                        <i class="fas fa-check-circle text-success fs-5 mt-1"></i>
                        <div>
                            <strong class="d-block text-dark">Instant Replacements</strong>
                            <small class="text-muted">Unbroken chain history for warranty swaps.</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card border-0 rounded-4 shadow-sm p-4 bg-white">
                <div class="p-4 bg-light rounded-3 text-center">
                    <i class="fas fa-shield-halved text-primary display-4 mb-3" style="color: #6366f1 !important;"></i>
                    <h4 class="fw-bold text-dark">Zero-Dispute Warranty</h4>
                    <p class="text-muted small mb-0">
                        Our recursive replacement algorithms eliminate lost warranty tickets, ensuring you always keep full coverage across any number of device swaps.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
