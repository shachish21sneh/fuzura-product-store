@extends('layouts.customer')

@section('title', 'Register New Product')
@section('page_title', 'Register Hardware for Warranty')

@section('content')
<div class="container-fluid px-0">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5 bg-white">
                <div class="d-flex align-items-center gap-3 mb-4 pb-3 border-bottom">
                    <div class="p-3 rounded-circle bg-warning-subtle text-warning">
                        <i class="fas fa-file-signature fs-3"></i>
                    </div>
                    <div>
                        <h4 class="fw-bold mb-0 text-dark">Hardware Registration Form</h4>
                        <p class="text-muted small mb-0">Fill in purchase information and upload your invoice bill to activate coverage.</p>
                    </div>
                </div>

                <form action="{{ route('customer.products.store') }}" method="POST" enctype="multipart/form-data" id="registrationForm">
                    @csrf

                    <!-- 1. Serial Number Verification Step -->
                    <div class="p-4 bg-light rounded-4 mb-4 border">
                        <h6 class="fw-bold text-dark mb-3"><i class="fas fa-barcode text-primary me-2"></i> 1. Product Identification</h6>
                        <div class="row g-3">
                            <div class="col-md-7">
                                <label class="form-label fw-semibold small">Product Serial Number <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="text" name="product_serial_number" id="serialInput" class="form-control font-monospace" 
                                           placeholder="e.g. FZ-SN-1005 or FZ-SN-2003" value="{{ old('product_serial_number') }}" required>
                                    <button type="button" class="btn btn-outline-primary fw-semibold px-3" id="btnVerifySerial">
                                        <i class="fas fa-check-double me-1"></i> Verify Serial
                                    </button>
                                </div>
                                <div id="serialFeedback" class="small mt-1"></div>
                            </div>

                            <div class="col-md-5">
                                <label class="form-label fw-semibold small">Product Model <span class="text-danger">*</span></label>
                                <input type="text" name="product_model" id="modelInput" class="form-control bg-white" 
                                       value="{{ old('product_model') }}" placeholder="Auto-fills upon verification" required readonly>
                            </div>
                        </div>
                    </div>

                    <!-- 2. Customer Contact Details -->
                    <div class="p-4 bg-light rounded-4 mb-4 border">
                        <h6 class="fw-bold text-dark mb-3"><i class="fas fa-user-check text-success me-2"></i> 2. Owner Contact Information</h6>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold small">Full Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control" value="{{ old('name', $customer->name) }}" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold small">Mobile Number <span class="text-danger">*</span></label>
                                <input type="text" name="mobile" class="form-control" value="{{ old('mobile', $customer->mobile) }}" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold small">Email Address <span class="text-danger">*</span></label>
                                <input type="email" name="email" class="form-control" value="{{ old('email', $customer->email) }}" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold small">Mailing Address</label>
                                <input type="text" name="address" class="form-control" value="{{ old('address', $customer->address) }}" placeholder="Street Address, City, State...">
                            </div>
                        </div>
                    </div>

                    <!-- 3. Purchase & Invoice Details -->
                    <div class="p-4 bg-light rounded-4 mb-4 border">
                        <h6 class="fw-bold text-dark mb-3"><i class="fas fa-store text-info me-2"></i> 3. Dealer & Invoice Upload</h6>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small">Dealer / Store Name <span class="text-danger">*</span></label>
                                <input type="text" name="dealer_name" id="dealerNameInput" class="form-control" 
                                       value="{{ old('dealer_name') }}" placeholder="e.g. Apex Electronics Mart" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small">Purchase / Bill Date <span class="text-danger">*</span></label>
                                <input type="date" name="purchase_date" id="purchaseDateInput" class="form-control" 
                                       value="{{ old('purchase_date', date('Y-m-d')) }}" max="{{ date('Y-m-d') }}" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold small">Dealer Store Address</label>
                                <input type="text" name="dealer_address" id="dealerAddressInput" class="form-control" 
                                       value="{{ old('dealer_address') }}" placeholder="e.g. Suite 400, Commercial Blvd, City">
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold small">Upload Purchase Bill / Tax Invoice <span class="text-danger">*</span></label>
                                <input type="file" name="bill" class="form-control" accept="image/jpeg,image/png,application/pdf" required>
                                <small class="text-muted" style="font-size: 0.75rem;">Supported formats: JPEG, PNG, PDF (Max file size: 5MB).</small>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center pt-3">
                        <a href="{{ route('customer.dashboard') }}" class="btn btn-light border px-4">Cancel</a>
                        <button type="submit" class="btn btn-primary-custom px-5 py-2 fs-6" id="btnSubmitRegistration">
                            <i class="fas fa-shield-check me-2"></i> Activate Warranty Coverage
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    function verifySerial() {
        const serial = $('#serialInput').val().trim();
        if (!serial) {
            $('#serialFeedback').html('<span class="text-danger"><i class="fas fa-exclamation-circle me-1"></i> Please enter a serial number.</span>');
            return;
        }

        $('#serialFeedback').html('<span class="text-muted"><i class="fas fa-spinner fa-spin me-1"></i> Checking database...</span>');

        $.post('{{ route('customer.products.check_serial') }}', { serial_number: serial }, function(res) {
            if (res.valid) {
                $('#serialFeedback').html(`<span class="text-success fw-bold"><i class="fas fa-check-circle me-1"></i> ${res.message} (${res.warranty_period_years} Years Warranty)</span>`);
                $('#modelInput').val(res.model_number);
                if (res.dealer_name && !$('#dealerNameInput').val()) {
                    $('#dealerNameInput').val(res.dealer_name);
                }
                if (res.dealer_address && !$('#dealerAddressInput').val()) {
                    $('#dealerAddressInput').val(res.dealer_address);
                }
                if (res.bill_date) {
                    $('#purchaseDateInput').val(res.bill_date);
                }
            } else {
                $('#serialFeedback').html(`<span class="text-danger fw-semibold"><i class="fas fa-circle-xmark me-1"></i> ${res.message}</span>`);
                $('#modelInput').val('');
            }
        }).fail(function(xhr) {
            const err = xhr.responseJSON ? xhr.responseJSON.message : 'Error validating serial.';
            $('#serialFeedback').html(`<span class="text-danger"><i class="fas fa-circle-xmark me-1"></i> ${err}</span>`);
        });
    }

    $('#btnVerifySerial').on('click', verifySerial);
    $('#serialInput').on('blur', function() {
        if ($(this).val().trim().length >= 4) {
            verifySerial();
        }
    });
});
</script>
@endpush
