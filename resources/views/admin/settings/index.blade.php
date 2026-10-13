@extends('layouts.admin')

@section('title', 'System Settings & Platform Configurations')
@section('page_title', 'System & Dynamic Configuration')

@section('content')
<div class="container-fluid px-0">

    <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-white">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
            <div>
                <h5 class="fw-bold mb-1 text-dark">Platform Settings & Dynamic Content</h5>
                <p class="text-muted small mb-0">Control brand information, public support helpdesk channels, homepage headlines, and verification demo settings dynamically.</p>
            </div>
            <div class="d-flex align-items-center gap-2">
                <span class="badge bg-success-subtle text-success border border-success border-opacity-25 px-3 py-2">
                    <i class="fas fa-bolt me-1"></i> Dynamic Cache Active
                </span>
            </div>
        </div>
    </div>

    <form action="{{ route('admin.settings.update') }}" method="POST">
        @csrf
        @method('PUT')

        <div class="row g-4">
            <!-- Left Column: Settings Sections -->
            <div class="col-lg-8">

                <!-- 1. General & Brand Settings -->
                <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-white">
                    <div class="d-flex align-items-center gap-2 mb-3 pb-2 border-bottom">
                        <div class="feature-icon-box icon-box-primary mb-0" style="width: 36px; height: 36px; font-size: 0.95rem;">
                            <i class="fas fa-sliders"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold text-dark mb-0">Brand & Core Configuration</h6>
                            <small class="text-muted">Master platform naming and global defaults</small>
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small text-dark">Platform Brand Name</label>
                            <input type="text" name="site_name" class="form-control" 
                                   value="{{ setting('site_name', 'Fuzura Store') }}" required>
                            <small class="text-muted" style="font-size: 0.75rem;">Shown across navigation, emails, and browser titles.</small>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold small text-dark">Default Hardware Warranty (Years)</label>
                            <input type="number" name="default_warranty_years" class="form-control" min="1" max="10" 
                                   value="{{ setting('default_warranty_years', '2') }}" required>
                            <small class="text-muted" style="font-size: 0.75rem;">Default duration applied when registering new hardware models.</small>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-semibold small text-dark">Platform Tagline / Subtitle</label>
                            <input type="text" name="site_tagline" class="form-control" 
                                   value="{{ setting('site_tagline', 'Enterprise Product Registration & Warranty Management System') }}" required>
                        </div>
                    </div>
                </div>

                <!-- 2. Support & Helpdesk Channels -->
                <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-white">
                    <div class="d-flex align-items-center gap-2 mb-3 pb-2 border-bottom">
                        <div class="feature-icon-box icon-box-cyan mb-0" style="width: 36px; height: 36px; font-size: 0.95rem;">
                            <i class="fas fa-headset"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold text-dark mb-0">Support & Helpdesk Channels</h6>
                            <small class="text-muted">Direct contact endpoints displayed on public header, footer, and contact desk</small>
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small text-dark">Support Toll-Free Phone</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted"><i class="fas fa-phone"></i></span>
                                <input type="text" name="support_phone" class="form-control" 
                                       value="{{ setting('support_phone', '1-800-FUZURA-CARE') }}" required>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold small text-dark">Official Support Email</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted"><i class="fas fa-envelope"></i></span>
                                <input type="email" name="support_email" class="form-control" 
                                       value="{{ setting('support_email', 'warranty@fuzura.in') }}" required>
                            </div>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-semibold small text-dark">Helpdesk Operational Hours</label>
                            <input type="text" name="support_hours" class="form-control" 
                                   value="{{ setting('support_hours', 'Monday to Saturday, 9:00 AM – 7:00 PM IST') }}" required>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-semibold small text-dark">Corporate Office / Headquarters Address</label>
                            <textarea name="office_address" rows="2" class="form-control">{{ setting('office_address', 'Fuzura Technology Park, Industrial Hub, New Delhi, 110020, India') }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- 3. Homepage & Verification Engine -->
                <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-white">
                    <div class="d-flex align-items-center gap-2 mb-3 pb-2 border-bottom">
                        <div class="feature-icon-box icon-box-success mb-0" style="width: 36px; height: 36px; font-size: 0.95rem;">
                            <i class="fas fa-globe"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold text-dark mb-0">Public Homepage & Verification Console</h6>
                            <small class="text-muted">Hero presentation and sample serial chips</small>
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label fw-semibold small text-dark">Homepage Hero Headline</label>
                            <input type="text" name="hero_title" class="form-control" 
                                   value="{{ setting('hero_title', 'Protect & Trace Hardware with Zero Friction') }}" required>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-semibold small text-dark">Homepage Hero Subtitle</label>
                            <textarea name="hero_subtitle" rows="2" class="form-control">{{ setting('hero_subtitle', 'Enterprise serial verification, transparent multi-level replacement tracking, and automated digital certificate issuance powered by Fuzura\'s immutable ledger.') }}</textarea>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-semibold small text-dark">Custom Demo Serials (Optional)</label>
                            <input type="text" name="demo_serials" class="form-control font-monospace" 
                                   placeholder="Leave empty for auto-detect, or enter comma-separated: FZ-SN-1002, FZ-SN-1004" 
                                   value="{{ setting('demo_serials', '') }}">
                            <small class="text-muted" style="font-size: 0.75rem;">
                                If left blank, the system automatically detects active replacement chain, warranty registered, and sold serials from your database.
                            </small>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Right Column: Save & Info Deck -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm rounded-4 p-4 bg-white sticky-top" style="top: 90px;">
                    <h6 class="fw-bold text-dark mb-3">Publish Changes</h6>
                    <p class="text-muted small mb-4">
                        Modifying these settings immediately invalidates system cache and updates all public pages and customer portals in real time.
                    </p>

                    <button type="submit" class="btn btn-primary-custom w-100 py-2.5 mb-3 shadow-sm">
                        <i class="fas fa-floppy-disk me-1.5"></i> Save & Publish Settings
                    </button>

                    <div class="p-3 bg-light rounded-3">
                        <div class="d-flex align-items-center gap-2 mb-2 text-dark fw-bold small">
                            <i class="fas fa-database text-primary"></i> Zero Static Data Guarantee
                        </div>
                        <p class="small text-muted mb-0" style="font-size: 0.78rem; line-height: 1.5;">
                            All contact cards, company titles, helpline details, and demo serial chips are dynamically queried through the database layer with zero hardcoded artifacts.
                        </p>
                    </div>
                </div>
            </div>
        </div>

    </form>

</div>
@endsection
