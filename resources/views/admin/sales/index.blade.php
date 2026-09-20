@extends('layouts.admin')

@section('title', 'Sales Management')
@section('page_title', 'Product Sales & Invoicing')

@section('content')
<div class="container-fluid px-0">

    <!-- Header & Actions -->
    <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-white">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
            <div>
                <h5 class="fw-bold mb-1 text-dark">Product Sales Registry</h5>
                <p class="text-muted small mb-0">Record sold units, link dealers and end-customers, and initiate product replacements.</p>
            </div>
            <button type="button" class="btn btn-primary-custom" data-bs-toggle="modal" data-bs-target="#recordSaleModal">
                <i class="fas fa-cart-plus me-1"></i> Record New Sale
            </button>
        </div>

        <!-- Filter Form -->
        <form action="{{ route('admin.sales.index') }}" method="GET" class="row g-3 mt-2 pt-3 border-top">
            <div class="col-md-5">
                <div class="input-group">
                    <span class="input-group-text bg-light text-muted border-end-0"><i class="fas fa-search"></i></span>
                    <input type="text" name="search" class="form-control border-start-0 ps-0" 
                           placeholder="Search Serial, Model, Customer, Dealer, Invoice..." value="{{ request('search') }}">
                </div>
            </div>

            <div class="col-md-3">
                <input type="date" name="from_date" class="form-control" value="{{ request('from_date') }}" title="From Date">
            </div>

            <div class="col-md-2">
                <input type="date" name="to_date" class="form-control" value="{{ request('to_date') }}" title="To Date">
            </div>

            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-secondary w-100"><i class="fas fa-filter me-1"></i> Filter</button>
                <a href="{{ route('admin.sales.index') }}" class="btn btn-light border" title="Reset"><i class="fas fa-rotate-left"></i></a>
            </div>
        </form>
    </div>

    <!-- Sales Table Card -->
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white">
        <div class="table-responsive">
            <table class="table table-custom mb-0 align-middle">
                <thead>
                    <tr>
                        <th>Invoice / Bill Date</th>
                        <th>Product Serial</th>
                        <th>Model</th>
                        <th>Dealer Name</th>
                        <th>Customer Info</th>
                        <th>Replacements</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($sales as $sale)
                        <tr>
                            <td>
                                <div class="fw-bold text-dark">{{ $sale->bill_date->format('d M, Y') }}</div>
                                <small class="text-muted font-monospace">{{ $sale->invoice_number ?? 'No Invoice #' }}</small>
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="font-monospace fw-bold text-dark">{{ $sale->product_serial_number }}</span>
                                    <button type="button" class="btn btn-sm btn-light py-0 px-1 text-muted" onclick="copyToClipboard('{{ $sale->product_serial_number }}')">
                                        <i class="far fa-copy"></i>
                                    </button>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-primary-subtle text-primary fw-semibold">{{ $sale->product_model }}</span>
                            </td>
                            <td>
                                <div class="fw-medium text-dark">{{ $sale->dealer_name }}</div>
                                <small class="text-muted d-block text-truncate" style="max-width: 150px;">{{ $sale->dealer_address ?? 'Address N/A' }}</small>
                            </td>
                            <td>
                                <div class="fw-semibold text-dark">{{ $sale->customer_name }}</div>
                                <small class="text-muted"><i class="fas fa-phone-alt me-1" style="font-size: 0.7rem;"></i>{{ $sale->customer_mobile }}</small>
                            </td>
                            <td>
                                @if ($sale->replacements->count() > 0)
                                    <span class="badge bg-warning-subtle text-warning fw-bold">
                                        <i class="fas fa-repeat me-1"></i> {{ $sale->replacements->count() }} Swap(s)
                                    </span>
                                @else
                                    <span class="badge bg-light text-muted border">Original</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <div class="d-flex justify-content-end gap-1">
                                    <!-- Replace Product Trigger Button (Every Sale Row) -->
                                    <button type="button" class="btn btn-sm btn-warning fw-bold px-2 py-1" 
                                            onclick="openReplacementModal({{ $sale->id }}, '{{ $sale->product_serial_number }}', '{{ $sale->dealer_name }}', '{{ $sale->customer_name }}')">
                                        <i class="fas fa-repeat me-1"></i> Replace
                                    </button>

                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-light rounded-circle border" type="button" data-bs-toggle="dropdown">
                                            <i class="fas fa-ellipsis-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                                            <li>
                                                <a class="dropdown-item" href="{{ route('admin.search.index', ['serial_number' => $sale->product_serial_number]) }}">
                                                    <i class="fas fa-magnifying-glass me-2 text-primary"></i> View Timeline
                                                </a>
                                            </li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li>
                                                <form action="{{ route('admin.sales.destroy', $sale->id) }}" method="POST" id="delete-sale-{{ $sale->id }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="dropdown-item text-danger" onclick="confirmDelete('delete-sale-{{ $sale->id }}', 'Delete this sale record? (Product will return to stock)')">
                                                        <i class="fas fa-trash me-2"></i> Delete Sale
                                                    </button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="fas fa-cart-shopping fs-1 mb-3 text-secondary opacity-50 d-block"></i>
                                <h5>No Sales Records</h5>
                                <p class="small mb-3">Record your first equipment sale using available products.</p>
                                <button type="button" class="btn btn-primary-custom btn-sm" data-bs-toggle="modal" data-bs-target="#recordSaleModal">
                                    Record Sale
                                </button>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($sales->hasPages())
            <div class="card-footer bg-white py-3 border-top d-flex justify-content-between align-items-center">
                <small class="text-muted">Showing {{ $sales->firstItem() }} to {{ $sales->lastItem() }} of {{ $sales->total() }} sales</small>
                {{ $sales->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Record Sale Modal -->
<div class="modal fade" id="recordSaleModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header bg-light">
                <h5 class="modal-title fw-bold"><i class="fas fa-cart-plus text-primary me-2"></i> Record Product Sale</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.sales.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Product Serial Number <span class="text-danger">*</span></label>
                            <select name="product_serial_number" id="saleSerialNumberSelect" class="form-select font-monospace" required>
                                <option value="">Select Available In-Stock Serial...</option>
                                @foreach ($availableProducts as $prod)
                                    <option value="{{ $prod->serial_number }}" data-model="{{ $prod->model_number }}">
                                        {{ $prod->serial_number }} (Model: {{ $prod->model_number }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Product Model</label>
                            <input type="text" id="saleModelDisplay" class="form-control bg-light" placeholder="Select a serial number first..." readonly>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Bill / Invoice Date <span class="text-danger">*</span></label>
                            <input type="date" name="bill_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Invoice Number</label>
                            <input type="text" name="invoice_number" class="form-control" placeholder="e.g. INV-2026-0012">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Dealer Name <span class="text-danger">*</span></label>
                            <input type="text" name="dealer_name" class="form-control" placeholder="e.g. Apex Electronics Mart" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Dealer Address</label>
                            <input type="text" name="dealer_address" class="form-control" placeholder="e.g. 100 Commercial Blvd, City">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Customer Full Name <span class="text-danger">*</span></label>
                            <input type="text" name="customer_name" class="form-control" placeholder="e.g. Robert Johnson" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Customer Mobile <span class="text-danger">*</span></label>
                            <input type="text" name="customer_mobile" class="form-control" placeholder="e.g. +1-555-0922" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold small">Customer Address</label>
                            <textarea name="customer_address" rows="2" class="form-control" placeholder="e.g. 450 North Hill Road, Suite 2A..."></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary-custom">Record Sale</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Product Replacement Modal (Triggered by Replace Button on Sale) -->
<div class="modal fade" id="replaceModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header bg-warning bg-opacity-10 border-bottom border-warning border-opacity-25">
                <h5 class="modal-title fw-bold text-dark"><i class="fas fa-repeat text-warning me-2"></i> Register Product Replacement</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.replacements.store') }}" method="POST">
                @csrf
                <input type="hidden" name="sale_id" id="repSaleId">
                <div class="modal-body p-4">
                    <div class="p-3 bg-light rounded-3 mb-3 border">
                        <div class="row g-2 small">
                            <div class="col-6">
                                <span class="text-muted d-block">Old Unit Being Replaced:</span>
                                <strong class="font-monospace text-danger fs-6" id="repOldSerialDisplay"></strong>
                                <input type="hidden" name="old_serial_number" id="repOldSerialInput">
                            </div>
                            <div class="col-6">
                                <span class="text-muted d-block">Customer:</span>
                                <span class="fw-semibold text-dark" id="repCustomerDisplay"></span>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Select New Replacement Serial <span class="text-danger">*</span></label>
                        <select name="new_serial_number" id="repNewSerialSelect" class="form-select font-monospace" required>
                            <option value="">Loading available in-stock units...</option>
                        </select>
                        <small class="text-muted" style="font-size: 0.75rem;">Only in-stock (available) units can be provided as replacement.</small>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Replacement Date <span class="text-danger">*</span></label>
                            <input type="date" name="replacement_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Dealer Handling Swap</label>
                            <input type="text" name="dealer_name" id="repDealerInput" class="form-control">
                        </div>
                    </div>

                    <div class="mb-2">
                        <label class="form-label fw-semibold small">Reason / Remarks for Replacement <span class="text-danger">*</span></label>
                        <textarea name="remarks" rows="3" class="form-control" placeholder="Describe fault, failure diagnostic, or warranty claim reason..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning fw-bold px-4">Execute Replacement</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Auto-update model input when serial is selected in Record Sale modal
    $('#saleSerialNumberSelect').on('change', function() {
        const selected = $(this).find(':selected');
        const model = selected.data('model');
        $('#saleModelDisplay').val(model || '');
    });
});

function openReplacementModal(saleId, oldSerial, dealer, customer) {
    $('#repSaleId').val(saleId);
    $('#repOldSerialDisplay').text(oldSerial);
    $('#repOldSerialInput').val(oldSerial);
    $('#repDealerInput').val(dealer);
    $('#repCustomerDisplay').text(customer);

    // Fetch available replacement serials via AJAX
    $('#repNewSerialSelect').html('<option value="">Loading available units...</option>');
    $.get('/admin/replacements/eligible/list?old_serial=' + encodeURIComponent(oldSerial), function(res) {
        if (res.success && res.products.length > 0) {
            let options = '<option value="">Select New Replacement Serial...</option>';
            res.products.forEach(function(p) {
                options += `<option value="${p.serial_number}">${p.serial_number} (Model: ${p.model_number})</option>`;
            });
            $('#repNewSerialSelect').html(options);
        } else {
            $('#repNewSerialSelect').html('<option value="">No available units in stock for replacement</option>');
        }
    });

    new bootstrap.Modal(document.getElementById('replaceModal')).show();
}
</script>
@endpush
