@extends('layouts.admin')

@section('title', 'Warranty Registrations')
@section('page_title', 'Warranty Claims & Registrations')

@section('content')
<div class="container-fluid px-0">

    <!-- Header & Filters -->
    <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-white">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
            <div>
                <h5 class="fw-bold mb-1 text-dark">Customer Warranty Registrations</h5>
                <p class="text-muted small mb-0">Verify customer invoice submissions, calculate warranty periods, and approve or reject claims.</p>
            </div>
        </div>

        <form action="{{ route('admin.warranties.index') }}" method="GET" class="row g-3 mt-2 pt-3 border-top">
            <div class="col-md-6">
                <div class="input-group">
                    <span class="input-group-text bg-light text-muted border-end-0"><i class="fas fa-search"></i></span>
                    <input type="text" name="search" class="form-control border-start-0 ps-0" 
                           placeholder="Search Serial, Customer, Email, Mobile, Dealer..." value="{{ request('search') }}">
                </div>
            </div>

            <div class="col-md-3">
                <select name="status" class="form-select">
                    <option value="">All Statuses</option>
                    <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
            </div>

            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-secondary w-100"><i class="fas fa-filter me-1"></i> Filter</button>
                <a href="{{ route('admin.warranties.index') }}" class="btn btn-light border" title="Reset"><i class="fas fa-rotate-left"></i></a>
            </div>
        </form>
    </div>

    <!-- Warranties Table Card -->
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white">
        <div class="table-responsive">
            <table class="table table-custom mb-0 align-middle">
                <thead>
                    <tr>
                        <th>Product Serial</th>
                        <th>Customer Details</th>
                        <th>Purchase Date</th>
                        <th>Warranty Expiry</th>
                        <th>Status</th>
                        <th>Invoice Bill</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($warranties as $w)
                        @php
                            $isExp = $w->isExpired();
                        @endphp
                        <tr>
                            <td>
                                <span class="font-monospace fw-bold text-dark d-block">{{ $w->product_serial_number }}</span>
                                <small class="text-muted">{{ $w->product_model }}</small>
                            </td>
                            <td>
                                <div class="fw-semibold text-dark">{{ $w->customer_name }}</div>
                                <small class="text-muted d-block">{{ $w->customer_email }}</small>
                                <small class="text-muted"><i class="fas fa-phone-alt me-1" style="font-size: 0.7rem;"></i>{{ $w->customer_mobile }}</small>
                            </td>
                            <td>
                                <div>{{ $w->purchase_date->format('d M, Y') }}</div>
                                <small class="text-muted">{{ $w->dealer_name }}</small>
                            </td>
                            <td>
                                <div class="fw-semibold {{ $isExp ? 'text-danger' : 'text-success' }}">
                                    {{ $w->warranty_end_date->format('d M, Y') }}
                                </div>
                                <small class="{{ $isExp ? 'text-danger' : 'text-muted' }}">
                                    {{ $isExp ? 'Expired' : $w->daysRemaining() . ' days left' }}
                                </small>
                            </td>
                            <td>
                                @if ($w->status === 'approved')
                                    <span class="badge badge-soft-success fw-bold"><i class="fas fa-check-circle me-1"></i> Approved</span>
                                @elseif ($w->status === 'pending')
                                    <span class="badge badge-soft-warning fw-bold"><i class="fas fa-clock me-1"></i> Pending</span>
                                @else
                                    <span class="badge badge-soft-danger fw-bold"><i class="fas fa-ban me-1"></i> Rejected</span>
                                @endif
                            </td>
                            <td>
                                @if ($w->bill_path)
                                    <a href="{{ route('admin.warranties.download_bill', $w->id) }}" class="btn btn-sm btn-outline-secondary py-1 px-2" title="Download Invoice">
                                        <i class="fas fa-file-arrow-down me-1"></i> Bill File
                                    </a>
                                @else
                                    <span class="text-muted small">No file</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <button type="button" class="btn btn-sm btn-primary-custom px-3 py-1" 
                                        onclick="openStatusModal({{ $w->id }}, '{{ $w->status }}', '{{ addslashes($w->admin_remarks ?? '') }}')">
                                    <i class="fas fa-sliders me-1"></i> Update
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="fas fa-certificate fs-1 mb-3 text-secondary opacity-50 d-block"></i>
                                <h5>No Warranty Registrations</h5>
                                <p class="small">Customer warranty registrations will appear here for review.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($warranties->hasPages())
            <div class="card-footer bg-white py-3 border-top d-flex justify-content-between align-items-center">
                <small class="text-muted">Showing {{ $warranties->firstItem() }} to {{ $warranties->lastItem() }} of {{ $warranties->total() }} registrations</small>
                {{ $warranties->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Status Update Modal -->
<div class="modal fade" id="statusModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header bg-light">
                <h5 class="modal-title fw-bold"><i class="fas fa-sliders text-primary me-2"></i> Update Warranty Status</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="statusForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Registration Decision <span class="text-danger">*</span></label>
                        <select name="status" id="warrantyStatusSelect" class="form-select" required>
                            <option value="approved">Approved (Active Coverage)</option>
                            <option value="pending">Pending (Needs Further Verification)</option>
                            <option value="rejected">Rejected (Invalid Invoice / Fake Serial)</option>
                        </select>
                    </div>

                    <div class="mb-2">
                        <label class="form-label fw-semibold small">Admin Remarks / Notes</label>
                        <textarea name="admin_remarks" id="adminRemarksInput" rows="3" class="form-control" placeholder="Enter notes visible on customer claim records..."></textarea>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary-custom">Update Decision</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function openStatusModal(id, currentStatus, remarks) {
    $('#statusForm').attr('action', '/admin/warranties/' + id + '/status');
    $('#warrantyStatusSelect').val(currentStatus);
    $('#adminRemarksInput').val(remarks || '');
    new bootstrap.Modal(document.getElementById('statusModal')).show();
}
</script>
@endpush
