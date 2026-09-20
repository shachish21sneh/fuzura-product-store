@extends('layouts.admin')

@section('title', 'Customer Directory')
@section('page_title', 'Customer Account Directory')

@section('content')
<div class="container-fluid px-0">

    <!-- Header & Search -->
    <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-white">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
            <div>
                <h5 class="fw-bold mb-1 text-dark">Customer Directory</h5>
                <p class="text-muted small mb-0">View registered customer profiles, enrolled warranties, and manage account statuses.</p>
            </div>
        </div>

        <form action="{{ route('admin.customers.index') }}" method="GET" class="row g-3 mt-2 pt-3 border-top">
            <div class="col-md-7">
                <div class="input-group">
                    <span class="input-group-text bg-light text-muted border-end-0"><i class="fas fa-search"></i></span>
                    <input type="text" name="search" class="form-control border-start-0 ps-0" 
                           placeholder="Search Customer Name, Email, Mobile..." value="{{ request('search') }}">
                </div>
            </div>

            <div class="col-md-3">
                <select name="status" class="form-select">
                    <option value="">All Statuses</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>

            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-secondary w-100"><i class="fas fa-filter me-1"></i> Filter</button>
                <a href="{{ route('admin.customers.index') }}" class="btn btn-light border" title="Reset"><i class="fas fa-rotate-left"></i></a>
            </div>
        </form>
    </div>

    <!-- Customers Table Card -->
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white">
        <div class="table-responsive">
            <table class="table table-custom mb-0 align-middle">
                <thead>
                    <tr>
                        <th>Customer Name</th>
                        <th>Contact Email</th>
                        <th>Mobile Number</th>
                        <th>Registered Warranties</th>
                        <th>Status</th>
                        <th>Joined On</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($customers as $c)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="rounded-circle bg-primary-subtle text-primary fw-bold d-flex align-items-center justify-content-center" style="width: 38px; height: 38px;">
                                        {{ strtoupper(substr($c->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark">{{ $c->name }}</div>
                                        <small class="text-muted d-block text-truncate" style="max-width: 200px;">{{ $c->address ?? 'Address unlisted' }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $c->email }}</td>
                            <td><span class="font-monospace text-dark">{{ $c->mobile }}</span></td>
                            <td>
                                <span class="badge bg-primary-subtle text-primary fw-bold px-2 py-1">
                                    {{ $c->warranty_registrations_count }} Unit(s)
                                </span>
                            </td>
                            <td>
                                <span class="badge {{ $c->status === 'active' ? 'badge-soft-success' : 'badge-soft-danger' }} fw-bold" id="status-badge-{{ $c->id }}">
                                    {{ ucfirst($c->status) }}
                                </span>
                            </td>
                            <td>{{ $c->created_at->format('d M, Y') }}</td>
                            <td class="text-end">
                                <button type="button" class="btn btn-sm btn-outline-secondary px-2 py-1" onclick="viewCustomerDetails({{ $c->id }})">
                                    <i class="fas fa-eye me-1"></i> Products
                                </button>
                                <button type="button" class="btn btn-sm btn-light border px-2 py-1 ms-1" onclick="toggleCustomerStatus({{ $c->id }})">
                                    <i class="fas fa-power-off text-warning"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="fas fa-users fs-1 mb-3 text-secondary opacity-50 d-block"></i>
                                <h5>No Customers Found</h5>
                                <p class="small">Customers will appear here after signing up.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($customers->hasPages())
            <div class="card-footer bg-white py-3 border-top d-flex justify-content-between align-items-center">
                <small class="text-muted">Showing {{ $customers->firstItem() }} to {{ $customers->lastItem() }} of {{ $customers->total() }} customers</small>
                {{ $customers->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Customer Details Modal -->
<div class="modal fade" id="customerModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header bg-light">
                <h5 class="modal-title fw-bold"><i class="fas fa-user-circle text-primary me-2"></i> Customer Profile & Warranties</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4" id="customerModalBody">
                <div class="text-center py-4"><i class="fas fa-spinner fa-spin fs-3 text-primary"></i></div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function toggleCustomerStatus(id) {
    $.post('/admin/customers/' + id + '/toggle-status', function(res) {
        if (res.success) {
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: res.message,
                showConfirmButton: false,
                timer: 2000
            });
            const badge = $('#status-badge-' + id);
            badge.text(res.status.charAt(0).toUpperCase() + res.status.slice(1));
            if (res.status === 'active') {
                badge.removeClass('badge-soft-danger').addClass('badge-soft-success');
            } else {
                badge.removeClass('badge-soft-success').addClass('badge-soft-danger');
            }
        }
    });
}

function viewCustomerDetails(id) {
    new bootstrap.Modal(document.getElementById('customerModal')).show();
    $.get('/admin/customers/' + id, function(res) {
        if (res.success) {
            const c = res.customer;
            let rows = '';
            if (c.warranty_registrations && c.warranty_registrations.length > 0) {
                c.warranty_registrations.forEach(function(w) {
                    rows += `
                        <tr>
                            <td class="font-monospace fw-bold">${w.product_serial_number}</td>
                            <td>${w.product_model}</td>
                            <td>${w.dealer_name}</td>
                            <td>${w.warranty_end_date ? w.warranty_end_date.split('T')[0] : 'N/A'}</td>
                            <td><span class="badge ${w.status === 'approved' ? 'bg-success' : 'bg-warning'}">${w.status}</span></td>
                        </tr>
                    `;
                });
            } else {
                rows = '<tr><td colspan="5" class="text-center text-muted py-3">No products registered yet.</td></tr>';
            }

            const html = `
                <div class="d-flex align-items-center gap-3 p-3 bg-light rounded-3 mb-4 border">
                    <div class="rounded-circle bg-primary text-white fw-bold d-flex align-items-center justify-content-center fs-4" style="width: 50px; height: 50px;">
                        ${c.name.charAt(0).toUpperCase()}
                    </div>
                    <div>
                        <h5 class="mb-0 fw-bold text-dark">${c.name}</h5>
                        <small class="text-muted">${c.email} | ${c.mobile}</small>
                        <div class="small text-muted mt-1">${c.address || 'Address unlisted'}</div>
                    </div>
                </div>

                <h6 class="fw-bold mb-2">Registered Hardware Products</h6>
                <div class="table-responsive border rounded-3">
                    <table class="table table-sm mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Serial Number</th>
                                <th>Model</th>
                                <th>Dealer</th>
                                <th>Warranty Expiry</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>${rows}</tbody>
                    </table>
                </div>
            `;
            $('#customerModalBody').html(html);
        }
    });
}
</script>
@endpush
