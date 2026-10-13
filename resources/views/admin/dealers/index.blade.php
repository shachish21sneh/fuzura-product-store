@extends('layouts.admin')

@section('title', 'Authorized Dealers Master')
@section('page_title', 'Authorized Dealers Directory')

@section('content')
<div class="container-fluid px-0">

    <!-- KPI Metric Cards -->
    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-xl-4">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-muted small fw-bold text-uppercase">Total Dealers</div>
                        <div class="fs-3 fw-bold text-dark mt-1">{{ number_format($totalDealers) }}</div>
                    </div>
                    <div class="stat-icon-wrapper bg-primary-subtle text-primary">
                        <i class="fas fa-store"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-4">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-muted small fw-bold text-uppercase">Active Authorized</div>
                        <div class="fs-3 fw-bold text-success mt-1">{{ number_format($activeDealers) }}</div>
                    </div>
                    <div class="stat-icon-wrapper bg-success-subtle text-success">
                        <i class="fas fa-circle-check"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-4">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-muted small fw-bold text-uppercase">Inactive / On Hold</div>
                        <div class="fs-3 fw-bold text-secondary mt-1">{{ number_format($totalDealers - $activeDealers) }}</div>
                    </div>
                    <div class="stat-icon-wrapper bg-secondary-subtle text-secondary">
                        <i class="fas fa-pause-circle"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Header & Search Toolbar -->
    <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-white">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
            <div>
                <h5 class="fw-bold mb-1 text-dark">Authorized Dealer Network</h5>
                <p class="text-muted small mb-0">Manage authorized distributors, regional retail outlets, and assignable sales partners.</p>
            </div>
            <button class="btn btn-primary-custom" data-bs-toggle="modal" data-bs-target="#createDealerModal">
                <i class="fas fa-plus-circle me-1.5"></i> Register New Dealer
            </button>
        </div>

        <form action="{{ route('admin.dealers.index') }}" method="GET" class="row g-3 mt-2 pt-3 border-top">
            <div class="col-md-7">
                <div class="input-group">
                    <span class="input-group-text bg-light text-muted border-end-0"><i class="fas fa-search"></i></span>
                    <input type="text" name="search" class="form-control border-start-0 ps-0" 
                           placeholder="Search Dealer Name, Code, City, Contact Person..." value="{{ request('search') }}">
                </div>
            </div>

            <div class="col-md-3">
                <select name="status" class="form-select">
                    <option value="">All Statuses</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active Only</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive Only</option>
                </select>
            </div>

            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-secondary w-100"><i class="fas fa-filter me-1"></i> Filter</button>
                <a href="{{ route('admin.dealers.index') }}" class="btn btn-light border" title="Reset"><i class="fas fa-rotate-left"></i></a>
            </div>
        </form>
    </div>

    <!-- Dealers Table -->
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white">
        <div class="table-responsive">
            <table class="table table-custom mb-0 align-middle">
                <thead>
                    <tr>
                        <th>Dealer Name & Code</th>
                        <th>Contact Person</th>
                        <th>Phone & Email</th>
                        <th>Location</th>
                        <th>Associated Sales</th>
                        <th>Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($dealers as $d)
                        <tr>
                            <td>
                                <div class="fw-bold text-dark">{{ $d->name }}</div>
                                <small class="text-muted font-monospace">{{ $d->code ?? 'N/A' }}</small>
                            </td>
                            <td>
                                <div class="small fw-semibold text-dark">{{ $d->contact_person ?: '—' }}</div>
                            </td>
                            <td>
                                @if ($d->phone)
                                    <div class="small text-dark"><i class="fas fa-phone me-1 text-primary small"></i> {{ $d->phone }}</div>
                                @endif
                                @if ($d->email)
                                    <div class="small text-muted"><i class="fas fa-envelope me-1 text-secondary small"></i> {{ $d->email }}</div>
                                @endif
                                @if (!$d->phone && !$d->email)
                                    <span class="text-muted small">—</span>
                                @endif
                            </td>
                            <td>
                                <div class="small text-dark">{{ $d->city ?: ($d->state ?: '—') }}</div>
                                @if ($d->address)
                                    <small class="text-muted text-truncate d-inline-block" style="max-width: 200px;" title="{{ $d->address }}">
                                        {{ $d->address }}
                                    </small>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border px-2 py-1">
                                    <i class="fas fa-cart-shopping me-1 text-primary"></i> {{ $d->sales_count }} sales
                                </span>
                            </td>
                            <td>
                                <form action="{{ route('admin.dealers.toggle_status', $d->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @if ($d->is_active)
                                        <button type="submit" class="badge bg-success-subtle text-success border border-success border-opacity-25 px-2.5 py-1 text-decoration-none border-0" title="Click to Deactivate">
                                            <i class="fas fa-check-circle me-1"></i> Active
                                        </button>
                                    @else
                                        <button type="submit" class="badge bg-secondary-subtle text-secondary border border-secondary border-opacity-25 px-2.5 py-1 text-decoration-none border-0" title="Click to Activate">
                                            <i class="fas fa-ban me-1"></i> Inactive
                                        </button>
                                    @endif
                                </form>
                            </td>
                            <td class="text-end">
                                <button type="button" class="btn btn-light btn-sm text-primary edit-dealer-btn" 
                                        data-id="{{ $d->id }}" title="Edit Dealer Profile">
                                    <i class="fas fa-pen-to-square"></i>
                                </button>

                                @if ($d->sales_count == 0)
                                    <form action="{{ route('admin.dealers.destroy', $d->id) }}" method="POST" class="d-inline"
                                          onsubmit="return confirm('Are you sure you want to permanently delete dealer {{ addslashes($d->name) }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-light btn-sm text-danger" title="Delete Dealer">
                                            <i class="fas fa-trash-can"></i>
                                        </button>
                                    </form>
                                @else
                                    <button class="btn btn-light btn-sm text-muted" disabled title="Cannot delete dealer with existing sales records">
                                        <i class="fas fa-lock"></i>
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="fas fa-store-slash fa-3x mb-3 text-secondary opacity-50"></i>
                                <h6 class="fw-bold">No Authorized Dealers Found</h6>
                                <p class="small mb-3">Register your first dealer to streamline product sales assignments.</p>
                                <button class="btn btn-primary-custom btn-sm" data-bs-toggle="modal" data-bs-target="#createDealerModal">
                                    <i class="fas fa-plus me-1"></i> Add Dealer
                                </button>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($dealers->hasPages())
            <div class="p-3 border-top d-flex justify-content-end">
                {{ $dealers->links() }}
            </div>
        @endif
    </div>

</div>

<!-- Modal: Create Dealer -->
<div class="modal fade" id="createDealerModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold">Register Authorized Dealer</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.dealers.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Dealer / Store Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" placeholder="e.g. Apex Electronics Tech" required>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label fw-semibold small">Dealer Code</label>
                            <input type="text" name="code" class="form-control" placeholder="e.g. DLR-001">
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-semibold small">Contact Person</label>
                            <input type="text" name="contact_person" class="form-control" placeholder="e.g. John Miller">
                        </div>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label fw-semibold small">Phone Number</label>
                            <input type="text" name="phone" class="form-control" placeholder="e.g. +91 98765 43210">
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-semibold small">Email Address</label>
                            <input type="email" name="email" class="form-control" placeholder="e.g. dealer@example.com">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Full Address / Store Location</label>
                        <textarea name="address" rows="2" class="form-control" placeholder="Store #, Building, Street, Area..."></textarea>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label fw-semibold small">City</label>
                            <input type="text" name="city" class="form-control" placeholder="e.g. New Delhi">
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-semibold small">State</label>
                            <input type="text" name="state" class="form-control" placeholder="e.g. Delhi">
                        </div>
                    </div>

                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="is_active" value="1" id="dealerActiveSwitch" checked>
                        <label class="form-check-label fw-semibold small" for="dealerActiveSwitch">Active Dealer Status</label>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary-custom px-4">Register Dealer</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal: Edit Dealer -->
<div class="modal fade" id="editDealerModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold">Edit Dealer Profile</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editDealerForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Dealer / Store Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="edit_name" class="form-control" required>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label fw-semibold small">Dealer Code</label>
                            <input type="text" name="code" id="edit_code" class="form-control">
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-semibold small">Contact Person</label>
                            <input type="text" name="contact_person" id="edit_contact_person" class="form-control">
                        </div>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label fw-semibold small">Phone Number</label>
                            <input type="text" name="phone" id="edit_phone" class="form-control">
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-semibold small">Email Address</label>
                            <input type="email" name="email" id="edit_email" class="form-control">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Full Address / Store Location</label>
                        <textarea name="address" id="edit_address" rows="2" class="form-control"></textarea>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label fw-semibold small">City</label>
                            <input type="text" name="city" id="edit_city" class="form-control">
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-semibold small">State</label>
                            <input type="text" name="state" id="edit_state" class="form-control">
                        </div>
                    </div>

                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="is_active" value="1" id="edit_is_active">
                        <label class="form-check-label fw-semibold small" for="edit_is_active">Active Dealer Status</label>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary-custom px-4">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const editModal = new bootstrap.Modal(document.getElementById('editDealerModal'));
    const editForm = document.getElementById('editDealerForm');

    document.querySelectorAll('.edit-dealer-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            const dealerId = this.dataset.id;
            fetch(`/admin/dealers/${dealerId}`)
                .then(res => res.json())
                .then(res => {
                    if (res.success) {
                        const d = res.dealer;
                        editForm.action = `/admin/dealers/${d.id}`;
                        document.getElementById('edit_name').value = d.name || '';
                        document.getElementById('edit_code').value = d.code || '';
                        document.getElementById('edit_contact_person').value = d.contact_person || '';
                        document.getElementById('edit_phone').value = d.phone || '';
                        document.getElementById('edit_email').value = d.email || '';
                        document.getElementById('edit_address').value = d.address || '';
                        document.getElementById('edit_city').value = d.city || '';
                        document.getElementById('edit_state').value = d.state || '';
                        document.getElementById('edit_is_active').checked = !!d.is_active;

                        editModal.show();
                    }
                })
                .catch(err => {
                    alert('Could not load dealer details.');
                });
        });
    });
});
</script>
@endpush
