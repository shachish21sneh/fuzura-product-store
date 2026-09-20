@extends('layouts.public')

@section('title', 'Contact Support - Fuzura Product Store')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 rounded-4 shadow-sm p-4 p-md-5 bg-white">
                <div class="text-center mb-4">
                    <span class="badge bg-primary-subtle text-primary fw-bold px-3 py-1 mb-2">Helpdesk Support</span>
                    <h2 class="fw-bold text-dark">Get in Touch With Us</h2>
                    <p class="text-muted small">Have questions regarding product registration or warranty claim? Send us a message.</p>
                </div>

                <form action="{{ route('public.contact.send') }}" method="POST">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Full Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" placeholder="John Doe" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Email Address <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control" placeholder="john@example.com" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold small">Subject <span class="text-danger">*</span></label>
                            <input type="text" name="subject" class="form-control" placeholder="Warranty Claim Inquiry for Serial..." required>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold small">Message <span class="text-danger">*</span></label>
                            <textarea name="message" rows="4" class="form-control" placeholder="Please describe your query in detail..." required></textarea>
                        </div>
                        <div class="col-12 text-center mt-4">
                            <button type="submit" class="btn btn-primary-custom px-5 py-2">
                                <i class="fas fa-paper-plane me-1"></i> Send Message
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
