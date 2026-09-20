@extends('layouts.public')

@section('title', 'About Us - Fuzura Enterprise Product & Warranty Platform')

@section('content')
<!-- About Hero -->
<section class="py-5" style="background: linear-gradient(135deg, #080c15 0%, #0f172a 100%); color: #fff; border-bottom: 1px solid rgba(255,255,255,0.06);">
    <div class="container py-4">
        <div class="row align-items-center g-5">
            <div class="col-lg-7">
                <span class="badge mb-3" style="background: rgba(99, 102, 241, 0.15); border: 1px solid rgba(99, 102, 241, 0.3); color: #a5b4fc; font-weight: 700; font-size: 0.8rem; padding: 0.4rem 0.9rem; border-radius: 9999px;">
                    <i class="fas fa-shield-halved me-1 text-warning"></i> ABOUT FUZURA STORE
                </span>
                <h1 class="display-4 fw-extrabold mb-3" style="letter-spacing: -0.035em; line-height: 1.2;">
                    Enterprise Product & Warranty <span class="text-gradient-accent">Transparency</span>
                </h1>
                <p class="lead mb-0" style="color: #cbd5e1; font-size: 1.15rem; line-height: 1.7;">
                    We believe consumer and enterprise electronics ownership should come with absolute peace of mind. Fuzura provides an immutable serial registry and graph-based replacement traversal engine.
                </p>
            </div>
            <div class="col-lg-5">
                <div class="p-4 rounded-4 text-center" style="background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.08); backdrop-filter: blur(16px);">
                    <div class="brand-emblem mx-auto mb-3" style="width: 58px; height: 58px; font-size: 1.75rem;">
                        <i class="fas fa-microchip"></i>
                    </div>
                    <h5 class="fw-bold text-white mb-1">Decentralized Traceability</h5>
                    <p class="small text-muted mb-0" style="color: #94a3b8 !important;">
                        Every product serial, warranty certificate, and replacement event is permanently recorded with microsecond timestamps.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- About Pillars -->
<section class="py-5" style="background-color: #f8fafc;">
    <div class="container py-4">
        <div class="row g-4">
            <div class="col-md-4">
                <div class="bento-card h-100">
                    <div class="feature-icon-box icon-box-primary">
                        <i class="fas fa-fingerprint"></i>
                    </div>
                    <h5 class="fw-bold text-dark mb-2">Immutable Serial Records</h5>
                    <p class="text-muted small mb-0 lh-base">
                        Direct traceability from factory production to authorized distributor, dealer invoice, and end-user registration with zero duplication.
                    </p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="bento-card h-100">
                    <div class="feature-icon-box icon-box-cyan">
                        <i class="fas fa-diagram-project"></i>
                    </div>
                    <h5 class="fw-bold text-dark mb-2">Recursive Replacement Logic</h5>
                    <p class="text-muted small mb-0 lh-base">
                        When hardware is serviced or replaced, our algorithm preserves the unbroken lineage back to the root purchase invoice.
                    </p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="bento-card h-100">
                    <div class="feature-icon-box icon-box-success">
                        <i class="fas fa-award"></i>
                    </div>
                    <h5 class="fw-bold text-dark mb-2">Instant Dispute-Free Claims</h5>
                    <p class="text-muted small mb-0 lh-base">
                        Customers and dealers access identical verifiable timelines, eliminating lost paperwork and claim rejections.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
