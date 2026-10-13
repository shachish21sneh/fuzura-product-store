@extends('layouts.public')

@section('title', 'Contact Support & Helpdesk - ' . setting('site_name', 'Fuzura Store'))

@section('content')
<!-- Contact Hero -->
<section class="py-5" style="background: linear-gradient(135deg, #080c15 0%, #0f172a 100%); color: #fff; border-bottom: 1px solid rgba(255,255,255,0.06);">
    <div class="container py-4 text-center" style="max-width: 700px;">
        <span class="badge mb-3" style="background: rgba(99, 102, 241, 0.15); border: 1px solid rgba(99, 102, 241, 0.3); color: #a5b4fc; font-weight: 700; font-size: 0.8rem; padding: 0.4rem 0.9rem; border-radius: 9999px;">
            <i class="fas fa-headset me-1 text-warning"></i> 24/7 SUPPORT HELPDESK
        </span>
        <h1 class="display-5 fw-extrabold mb-3" style="letter-spacing: -0.035em;">
            How Can We <span class="text-gradient-accent">Help You?</span>
        </h1>
        <p class="lead mb-0" style="color: #cbd5e1; font-size: 1.1rem; line-height: 1.6;">
            Have questions regarding product registration, replacement status, or authorized dealers? Our dedicated support engineering team is here for you.
        </p>
    </div>
</section>

<!-- Contact Form & Info Section -->
<section class="py-5" style="background-color: #f8fafc;">
    <div class="container py-3">
        <div class="row g-4 justify-content-center">
            <div class="col-lg-4">
                <div class="bento-card h-100">
                    <h5 class="fw-bold text-dark mb-4">Direct Contact Channels</h5>
                    
                    <div class="d-flex align-items-start gap-3 mb-4">
                        <div class="feature-icon-box icon-box-primary mb-0" style="width: 44px; height: 44px; font-size: 1.1rem;">
                            <i class="fas fa-phone-volume"></i>
                        </div>
                        <div>
                            <strong class="d-block text-dark small">Toll-Free Support Line</strong>
                            <a href="tel:{{ setting('support_phone', '1-800-FUZURA-CARE') }}" class="text-muted small text-decoration-none">
                                {{ setting('support_phone', '1-800-FUZURA-CARE') }}
                            </a>
                        </div>
                    </div>

                    <div class="d-flex align-items-start gap-3 mb-4">
                        <div class="feature-icon-box icon-box-cyan mb-0" style="width: 44px; height: 44px; font-size: 1.1rem;">
                            <i class="fas fa-envelope-open-text"></i>
                        </div>
                        <div>
                            <strong class="d-block text-dark small">Official Email Desk</strong>
                            <a href="mailto:{{ setting('support_email', 'warranty@fuzura.in') }}" class="text-muted small text-decoration-none">
                                {{ setting('support_email', 'warranty@fuzura.in') }}
                            </a>
                        </div>
                    </div>

                    <div class="d-flex align-items-start gap-3 mb-4">
                        <div class="feature-icon-box icon-box-success mb-0" style="width: 44px; height: 44px; font-size: 1.1rem;">
                            <i class="fas fa-business-time"></i>
                        </div>
                        <div>
                            <strong class="d-block text-dark small">Operational Desk Hours</strong>
                            <span class="text-muted small">{!! nl2br(e(setting('support_hours', 'Monday to Saturday, 9:00 AM – 7:00 PM IST'))) !!}</span>
                        </div>
                    </div>

                    @if (setting('office_address'))
                        <div class="d-flex align-items-start gap-3 pt-3 border-top">
                            <div class="feature-icon-box icon-box-purple mb-0" style="width: 44px; height: 44px; font-size: 1.1rem;">
                                <i class="fas fa-building"></i>
                            </div>
                            <div>
                                <strong class="d-block text-dark small">Headquarters Office</strong>
                                <span class="text-muted small">{{ setting('office_address') }}</span>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <div class="col-lg-7">
                <div class="bento-card">
                    <h5 class="fw-bold text-dark mb-2">Send an Official Inquiry</h5>
                    <p class="text-muted small mb-4">Fill out the secure form below and our warranty response team will reply within 24 business hours.</p>

                    <form action="{{ route('public.contact.send') }}" method="POST">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small text-dark">Full Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control rounded-3 py-2.5" placeholder="e.g. Rahul Sharma" value="{{ old('name') }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small text-dark">Email Address <span class="text-danger">*</span></label>
                                <input type="email" name="email" class="form-control rounded-3 py-2.5" placeholder="e.g. rahul@example.com" value="{{ old('email') }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small text-dark">Contact Phone (Optional)</label>
                                <input type="text" name="phone" class="form-control rounded-3 py-2.5" placeholder="e.g. +91 98765 43210" value="{{ old('phone') }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small text-dark">Subject <span class="text-danger">*</span></label>
                                <input type="text" name="subject" class="form-control rounded-3 py-2.5" placeholder="e.g. Warranty Claim for Serial #..." value="{{ old('subject') }}" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold small text-dark">Inquiry Message <span class="text-danger">*</span></label>
                                <textarea name="message" rows="4" class="form-control rounded-3 py-2.5" placeholder="Please describe your query with purchase details or serial numbers..." required>{{ old('message') }}</textarea>
                            </div>
                            <div class="col-12 mt-4">
                                <button type="submit" class="btn btn-primary-custom px-5 py-2.5 w-100">
                                    <i class="fas fa-paper-plane me-1.5"></i> Submit Support Ticket
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
