@extends('layouts.public')

@section('title', 'Fuzura - Enterprise Product Registration & Warranty Management System')

@section('content')
<!-- Hero Section -->
<section class="hero-cosmic text-white" style="padding: 3.5rem 0 3rem;">
    <div class="container">
        <div class="row align-items-center g-4 g-xl-5">
            <div class="col-lg-7">
                <div class="hero-pill-badge mb-3">
                    <span class="pulse-led"></span>
                    <span>Live Verification Engine • Recursive RMA Traversal v2.4</span>
                </div>
                
                <h1 class="fw-extrabold mb-3 text-white" style="letter-spacing: -0.035em; line-height: 1.18; font-size: clamp(2.1rem, 3.4vw, 2.85rem);">
                    Protect & Trace Hardware with <span class="text-gradient-accent">Zero Friction</span>
                </h1>
                
                <p class="lead mb-4" style="color: #cbd5e1; font-size: 1.08rem; line-height: 1.65; max-width: 580px;">
                    Enterprise serial verification, transparent multi-level replacement tracking, and automated digital certificate issuance powered by Fuzura's immutable ledger.
                </p>

                <!-- Command-Palette Search Console -->
                <div class="search-console-card">
                    <form action="{{ route('public.search') }}" method="GET" class="unified-search-bar">
                        <div class="search-bar-icon">
                            <i class="fas fa-barcode"></i>
                        </div>
                        <input type="text" name="serial_number" class="search-bar-input" 
                               placeholder="Enter Product Serial Number (e.g. FZ-SN-1002)" required autocomplete="off">
                        <button type="submit" class="search-bar-btn">
                            <i class="fas fa-bolt"></i>
                            <span>Verify Status</span>
                        </button>
                    </form>

                    <!-- Quick Sample Serials (Spacious Layout) -->
                    <div class="demo-serials-container">
                        <div class="demo-serials-label">
                            <i class="fas fa-flask text-warning"></i>
                            <span>Try Demo:</span>
                        </div>
                        <div class="demo-serials-chips">
                            <a href="{{ route('public.search', ['serial_number' => 'FZ-SN-1002']) }}" class="sample-chip">
                                <i class="fas fa-repeat text-warning"></i>
                                <span>FZ-SN-1002</span>
                                <span class="chip-context">(Chain Step)</span>
                            </a>
                            <a href="{{ route('public.search', ['serial_number' => 'FZ-SN-1004']) }}" class="sample-chip">
                                <i class="fas fa-circle-check text-success"></i>
                                <span>FZ-SN-1004</span>
                                <span class="chip-context">(Active Unit)</span>
                            </a>
                            <a href="{{ route('public.search', ['serial_number' => 'FZ-SN-2001']) }}" class="sample-chip">
                                <i class="fas fa-shield text-info"></i>
                                <span>FZ-SN-2001</span>
                                <span class="chip-context">(Active)</span>
                            </a>
                            <a href="{{ route('public.search', ['serial_number' => 'FZ-SN-3001']) }}" class="sample-chip">
                                <i class="fas fa-clock text-danger"></i>
                                <span>FZ-SN-3001</span>
                                <span class="chip-context">(Expired)</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Hero Right: Interactive Telemetry HUD -->
            <div class="col-lg-5 d-none d-lg-block">
                <div class="telemetry-deck">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="d-flex align-items-center gap-2">
                            <div class="brand-emblem" style="width: 32px; height: 32px; font-size: 0.95rem;">
                                <i class="fas fa-microchip"></i>
                            </div>
                            <div>
                                <h6 class="mb-0 fw-bold text-white small lh-1">Node Telemetry HUD</h6>
                                <small style="color: #94a3b8; font-size: 0.72rem;">Recursive Chain Engine</small>
                            </div>
                        </div>
                        <span class="badge" style="background: rgba(16, 185, 129, 0.15); color: #34d399; border: 1px solid rgba(16, 185, 129, 0.3); font-size: 0.72rem; padding: 0.35rem 0.65rem;">
                            <span class="pulse-led me-1 d-inline-block"></span> Operational
                        </span>
                    </div>

                    <!-- Visual Chain Flow (Clean 4-Node Pipeline, No Awkward Wrapping) -->
                    <div class="telemetry-node-chain">
                        <div class="telemetry-header-row">
                            <span class="telemetry-pipeline-title">
                                <i class="fas fa-network-wired text-indigo-400"></i> Live Replacement Pipeline
                            </span>
                            <span class="telemetry-nodes-pill">
                                <i class="fas fa-layer-group text-indigo-300"></i>
                                <span>4 Verified Nodes</span>
                            </span>
                        </div>
                        
                        <div class="telemetry-pipeline">
                            <div class="pipeline-node pipeline-node-orig" title="Original Sale">
                                <span class="pipeline-tag pipeline-tag-orig">Original</span>
                                <span class="pipeline-serial text-white">FZ-1001</span>
                            </div>
                            <i class="fas fa-chevron-right pipeline-arrow"></i>
                            <div class="pipeline-node pipeline-node-rep" title="Replacement #1">
                                <span class="pipeline-tag pipeline-tag-rep">RMA #1</span>
                                <span class="pipeline-serial text-white">FZ-1002</span>
                            </div>
                            <i class="fas fa-chevron-right pipeline-arrow"></i>
                            <div class="pipeline-node pipeline-node-rep" title="Replacement #2">
                                <span class="pipeline-tag pipeline-tag-rep">RMA #2</span>
                                <span class="pipeline-serial text-white">FZ-1003</span>
                            </div>
                            <i class="fas fa-chevron-right pipeline-arrow"></i>
                            <div class="pipeline-node pipeline-node-active" title="Current Active In-Use Device">
                                <span class="pipeline-tag pipeline-tag-active">Active</span>
                                <span class="pipeline-serial text-success">FZ-1004</span>
                            </div>
                        </div>

                        <div class="telemetry-footer-status">
                            <span class="fw-medium" style="color: #cbd5e1;">Active Warranty:</span>
                            <span class="text-success fw-bold font-mono d-flex align-items-center gap-1.5">
                                <i class="fas fa-shield-check"></i> 547 Days Active (Till 2027)
                            </span>
                        </div>
                    </div>

                    <!-- Metrics Grid -->
                    <div class="row g-2.5 text-center">
                        <div class="col-4">
                            <div class="telemetry-tile">
                                <div class="fw-extrabold text-white fs-5 font-mono lh-1 mb-1">{{ $totalProducts }}+</div>
                                <small style="color: #94a3b8; font-size: 0.72rem;">Catalog SKUs</small>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="telemetry-tile">
                                <div class="fw-extrabold text-white fs-5 font-mono lh-1 mb-1">{{ $totalSales }}+</div>
                                <small style="color: #94a3b8; font-size: 0.72rem;">Units Tracked</small>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="telemetry-tile">
                                <div class="fw-extrabold text-white fs-5 font-mono lh-1 mb-1">{{ $totalRegistered }}+</div>
                                <small style="color: #94a3b8; font-size: 0.72rem;">Certificates</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Trust Ribbon -->
<section class="trust-ribbon" style="padding: 1.25rem 0;">
    <div class="container">
        <div class="row g-3 justify-content-between align-items-center text-center text-md-start">
            <div class="col-6 col-md-3">
                <div class="trust-item justify-content-center justify-content-md-start">
                    <i class="fas fa-fingerprint"></i>
                    <span>Tamper-Proof Serial Ledger</span>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="trust-item justify-content-center justify-content-md-start">
                    <i class="fas fa-diagram-project"></i>
                    <span>Recursive Replacement Trees</span>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="trust-item justify-content-center justify-content-md-start">
                    <i class="fas fa-certificate"></i>
                    <span>Official PDF Certificates</span>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="trust-item justify-content-center justify-content-md-start">
                    <i class="fas fa-store"></i>
                    <span>Authorized Dealer Network</span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- How It Works Section (Bento Grid) -->
<section style="background-color: #f8fafc; padding: 4rem 0 3.75rem;">
    <div class="container">
        <div class="text-center mx-auto mb-5" style="max-width: 620px;">
            <span class="badge mb-2.5" style="background: rgba(99, 102, 241, 0.1); color: #4f46e5; font-weight: 700; font-size: 0.78rem; letter-spacing: 0.08em; text-transform: uppercase; padding: 0.4rem 0.95rem; border-radius: 9999px;">
                Frictionless Workflow
            </span>
            <h2 class="fw-extrabold text-dark mb-2" style="letter-spacing: -0.025em; font-size: 2rem;">How Product Registration Works</h2>
            <p class="text-muted mb-0" style="font-size: 1rem; line-height: 1.6;">Register genuine Fuzura equipment in under 2 minutes to protect your hardware investment with instant certificate generation.</p>
        </div>

        <div class="row g-4">
            <div class="col-md-4">
                <div class="bento-card h-100">
                    <div class="d-flex justify-content-between align-items-center mb-3.5">
                        <div class="feature-icon-box icon-box-primary">
                            <i class="fas fa-bag-shopping"></i>
                        </div>
                        <span class="step-num-badge">01</span>
                    </div>
                    <h5 class="fw-bold text-dark mb-2 fs-6">Purchase Authentic Gear</h5>
                    <p class="text-muted small mb-0 lh-base" style="font-size: 0.88rem; line-height: 1.6;">
                        Acquire authentic Fuzura equipment from any authorized dealer, retail partner, or official distributor.
                    </p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="bento-card h-100">
                    <div class="d-flex justify-content-between align-items-center mb-3.5">
                        <div class="feature-icon-box icon-box-cyan">
                            <i class="fas fa-file-invoice-dollar"></i>
                        </div>
                        <span class="step-num-badge">02</span>
                    </div>
                    <h5 class="fw-bold text-dark mb-2 fs-6">Submit Invoice & Serial</h5>
                    <p class="text-muted small mb-0 lh-base" style="font-size: 0.88rem; line-height: 1.6;">
                        Log in to your customer portal, enter your serial number, and upload purchase invoice for automated verification.
                    </p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="bento-card h-100">
                    <div class="d-flex justify-content-between align-items-center mb-3.5">
                        <div class="feature-icon-box icon-box-success">
                            <i class="fas fa-shield-halved"></i>
                        </div>
                        <span class="step-num-badge">03</span>
                    </div>
                    <h5 class="fw-bold text-dark mb-2 fs-6">Enjoy Guaranteed Coverage</h5>
                    <p class="text-muted small mb-0 lh-base" style="font-size: 0.88rem; line-height: 1.6;">
                        Instantly access and print your official digital warranty certificate with seamless replacement support.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Enterprise Feature Highlights: Unlimited Replacement Chains -->
<section class="bg-white border-top border-bottom" style="padding: 4rem 0 3.75rem;">
    <div class="container">
        <div class="row align-items-center g-4 g-lg-5">
            <div class="col-lg-6">
                <span class="badge mb-2.5" style="background: rgba(16, 185, 129, 0.1); color: #059669; font-weight: 700; font-size: 0.78rem; letter-spacing: 0.08em; text-transform: uppercase; padding: 0.4rem 0.95rem; border-radius: 9999px;">
                    Exclusive Architecture
                </span>
                <h2 class="fw-extrabold text-dark mb-2.5" style="letter-spacing: -0.025em; font-size: 2rem;">
                    Unlimited Replacement Chain Resolution
                </h2>
                <p class="text-muted mb-4" style="line-height: 1.65; font-size: 0.98rem;">
                    Standard warranty tools lose customer history when equipment is swapped. Fuzura uses graph traversal algorithms to link every serial back to the original tax invoice.
                </p>
                
                <div class="d-flex flex-column gap-3">
                    <div class="d-flex align-items-start gap-3">
                        <div class="rounded-circle d-flex align-items-center justify-content-center text-primary" style="width: 36px; height: 36px; background: #eef2ff; flex-shrink: 0; font-size: 0.9rem;">
                            <i class="fas fa-link"></i>
                        </div>
                        <div>
                            <strong class="text-dark d-block small mb-0.5">Search Any Serial in the Chain</strong>
                            <span class="text-muted small" style="font-size: 0.86rem; line-height: 1.55;">Searching an old replaced serial automatically reveals the full history and identifies the currently active unit.</span>
                        </div>
                    </div>
                    
                    <div class="d-flex align-items-start gap-3">
                        <div class="rounded-circle d-flex align-items-center justify-content-center text-success" style="width: 36px; height: 36px; background: #ecfdf5; flex-shrink: 0; font-size: 0.9rem;">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                        <div>
                            <strong class="text-dark d-block small mb-0.5">Zero Lost Coverage Days</strong>
                            <span class="text-muted small" style="font-size: 0.86rem; line-height: 1.55;">Warranty expiration date is strictly preserved from the root purchase, ensuring zero warranty disputes.</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="p-4 rounded-4" style="background: #0f172a; border: 1px solid rgba(255,255,255,0.1); box-shadow: 0 20px 45px rgba(0,0,0,0.22);">
                    <div class="d-flex justify-content-between align-items-center mb-3.5">
                        <span class="text-white small fw-bold"><i class="fas fa-network-wired text-primary me-2"></i>Multi-Level Tree Simulator</span>
                        <span class="badge bg-success font-mono" style="font-size: 0.72rem; padding: 0.35rem 0.65rem;">100% Chain Integrity</span>
                    </div>
                    
                    <div class="timeline-container py-1">
                        <div class="timeline-node mb-3">
                            <div class="timeline-marker" style="width: 32px; height: 32px; left: -2.75rem; font-size: 0.75rem;">0</div>
                            <div class="p-2.5 rounded-3" style="background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.08);">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <span class="fw-bold text-white font-mono small">FZ-SN-1001</span>
                                    <span class="badge bg-secondary" style="font-size: 0.65rem;">Original Sale</span>
                                </div>
                                <small class="text-muted d-block" style="font-size: 0.72rem;">Customer: John Doe • Dealer: Apex Electronics</small>
                            </div>
                        </div>

                        <div class="timeline-node mb-3">
                            <div class="timeline-marker" style="width: 32px; height: 32px; left: -2.75rem; font-size: 0.75rem;">1</div>
                            <div class="p-2.5 rounded-3" style="background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.08);">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <span class="fw-bold text-white font-mono small">FZ-SN-1002</span>
                                    <span class="badge bg-warning text-dark" style="font-size: 0.65rem;">Replacement #1</span>
                                </div>
                                <small class="text-muted d-block" style="font-size: 0.72rem;">Replaced: Screen Flickering</small>
                            </div>
                        </div>

                        <div class="timeline-node active-unit mb-0">
                            <div class="timeline-marker" style="width: 32px; height: 32px; left: -2.75rem; font-size: 0.75rem;"><i class="fas fa-check"></i></div>
                            <div class="p-2.5 rounded-3" style="background: rgba(16, 185, 129, 0.14); border: 1px solid rgba(16, 185, 129, 0.4);">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <span class="fw-bold text-white font-mono small">FZ-SN-1004</span>
                                    <span class="badge bg-success" style="font-size: 0.65rem;">Active In-Use Unit</span>
                                </div>
                                <small class="text-success d-block" style="font-size: 0.72rem;">Full Warranty In Force • Valid till 2027</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Call to Action Banner -->
<section style="background-color: #f8fafc; padding: 3.5rem 0 4.5rem;">
    <div class="container">
        <div class="cta-cosmic-card">
            <div class="row align-items-center g-4 text-white position-relative" style="z-index: 2;">
                <div class="col-lg-8">
                    <span class="badge mb-2.5" style="background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.15); color: #93c5fd; font-size: 0.75rem; font-weight: 600; padding: 0.35rem 0.85rem;">
                        INSTANT ACTIVATION
                    </span>
                    <h3 class="fw-extrabold mb-2 fs-3" style="letter-spacing: -0.025em;">
                        Have you purchased a new Fuzura product?
                    </h3>
                    <p class="mb-0 text-slate-300" style="color: #cbd5e1; font-size: 1rem; line-height: 1.6;">
                        Create your verified account in 30 seconds to activate manufacturer warranty coverage and track your complete hardware lifecycle.
                    </p>
                </div>
                <div class="col-lg-4 text-lg-end">
                    <div class="d-flex flex-column flex-sm-row justify-content-lg-end gap-2.5">
                        <a href="{{ route('customer.register') }}" class="btn btn-primary-custom px-4 py-2.5">
                            <i class="fas fa-plus-circle me-1"></i> Register Product
                        </a>
                        <a href="{{ route('customer.login') }}" class="btn btn-glass px-4 py-2.5">
                            Customer Portal
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
