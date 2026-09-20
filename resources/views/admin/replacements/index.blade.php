@extends('layouts.admin')

@section('title', 'Product Replacements')
@section('page_title', 'Product Replacement History & Chains')

@section('content')
<div class="container-fluid px-0">

    <!-- Header & Search -->
    <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-white">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
            <div>
                <h5 class="fw-bold mb-1 text-dark">Replacement Audit & Chain Tracker</h5>
                <p class="text-muted small mb-0">Multi-level product swap audit log with recursive timeline visualization.</p>
            </div>
            <a href="{{ route('admin.sales.index') }}" class="btn btn-outline-primary">
                <i class="fas fa-cart-shopping me-1"></i> Go to Sales to Initiate Swap
            </a>
        </div>

        <form action="{{ route('admin.replacements.index') }}" method="GET" class="row g-3 mt-2 pt-3 border-top">
            <div class="col-md-9">
                <div class="input-group">
                    <span class="input-group-text bg-light text-muted border-end-0"><i class="fas fa-search"></i></span>
                    <input type="text" name="search" class="form-control border-start-0 ps-0" 
                           placeholder="Search Old Serial, New Serial, Customer, Dealer..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-secondary w-100"><i class="fas fa-filter me-1"></i> Search</button>
                <a href="{{ route('admin.replacements.index') }}" class="btn btn-light border" title="Reset"><i class="fas fa-rotate-left"></i></a>
            </div>
        </form>
    </div>

    <!-- Table Card -->
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white">
        <div class="table-responsive">
            <table class="table table-custom mb-0 align-middle">
                <thead>
                    <tr>
                        <th>Replacement Date</th>
                        <th>Old Unit (Replaced)</th>
                        <th>New Unit (Issued)</th>
                        <th>Customer / Dealer</th>
                        <th>Remarks / Diagnostic</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($replacements as $rep)
                        <tr>
                            <td>
                                <span class="fw-bold text-dark">{{ $rep->replacement_date->format('d M, Y') }}</span>
                            </td>
                            <td>
                                <span class="font-monospace text-danger fw-bold">{{ $rep->old_serial_number }}</span>
                                <small class="text-muted d-block">{{ $rep->oldProduct?->model_number ?? 'Model N/A' }}</small>
                            </td>
                            <td>
                                <span class="font-monospace text-success fw-bold">{{ $rep->new_serial_number }}</span>
                                <small class="text-muted d-block">{{ $rep->newProduct?->model_number ?? 'Model N/A' }}</small>
                            </td>
                            <td>
                                <div class="fw-semibold text-dark">{{ $rep->customer_name }}</div>
                                <small class="text-muted">{{ $rep->dealer_name }}</small>
                            </td>
                            <td>
                                <p class="mb-0 small text-muted text-truncate" style="max-width: 250px;" title="{{ $rep->remarks }}">
                                    {{ $rep->remarks ?? 'No remarks provided.' }}
                                </p>
                            </td>
                            <td class="text-end">
                                <button type="button" class="btn btn-sm btn-primary-custom px-3 py-1" onclick="viewTimelineModal('{{ $rep->new_serial_number }}')">
                                    <i class="fas fa-sitemap me-1"></i> View Timeline
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="fas fa-repeat fs-1 mb-3 text-secondary opacity-50 d-block"></i>
                                <h5>No Replacements Registered</h5>
                                <p class="small">When products are replaced under warranty, their complete timeline appears here.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($replacements->hasPages())
            <div class="card-footer bg-white py-3 border-top d-flex justify-content-between align-items-center">
                <small class="text-muted">Showing {{ $replacements->firstItem() }} to {{ $replacements->lastItem() }} of {{ $replacements->total() }} replacements</small>
                {{ $replacements->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Timeline Modal -->
<div class="modal fade" id="timelineModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header bg-light">
                <h5 class="modal-title fw-bold"><i class="fas fa-sitemap text-primary me-2"></i> Replacement Chain Timeline</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4" id="timelineModalBody">
                <div class="text-center py-5"><i class="fas fa-spinner fa-spin fs-3 text-primary"></i></div>
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
function viewTimelineModal(serial) {
    new bootstrap.Modal(document.getElementById('timelineModal')).show();
    $('#timelineModalBody').html('<div class="text-center py-5"><i class="fas fa-spinner fa-spin fs-3 text-primary"></i></div>');

    $.get('/admin/replacements/timeline/' + encodeURIComponent(serial), function(res) {
        if (res.success && res.data.found) {
            const data = res.data;
            let nodesHtml = '';
            data.timeline.forEach(function(node) {
                const isActive = node.is_current_active;
                const isSearched = node.is_searched;

                nodesHtml += `
                    <div class="timeline-node ${isActive ? 'active-unit' : ''} ${isSearched ? 'searched-unit' : ''}">
                        <div class="timeline-marker">
                            <i class="fas ${isActive ? 'fa-check' : (node.type === 'original' ? 'fa-box' : 'fa-repeat')}"></i>
                        </div>
                        <div class="timeline-card mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="badge ${isActive ? 'bg-success' : (node.type === 'original' ? 'bg-primary' : 'bg-secondary')}">
                                    ${node.title} ${isActive ? '(Current Active Unit)' : ''}
                                </span>
                                <small class="text-muted">${node.date ? node.date.split('T')[0] : 'N/A'}</small>
                            </div>
                            <div class="row g-2 mt-1 small">
                                <div class="col-6">
                                    <span class="text-muted d-block">Serial:</span>
                                    <strong class="font-monospace text-dark">${node.serial_number}</strong>
                                </div>
                                <div class="col-6">
                                    <span class="text-muted d-block">Model:</span>
                                    <span class="fw-semibold text-secondary">${node.model_number}</span>
                                </div>
                                <div class="col-6">
                                    <span class="text-muted d-block">Dealer:</span>
                                    <span>${node.dealer_name}</span>
                                </div>
                                <div class="col-6">
                                    <span class="text-muted d-block">Customer:</span>
                                    <span>${node.customer_name || 'N/A'}</span>
                                </div>
                                <div class="col-12 pt-1 border-top">
                                    <span class="text-muted d-block">Remarks:</span>
                                    <span class="fst-italic text-muted">${node.remarks || 'None'}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            });

            const html = `
                <div class="p-3 bg-light rounded-3 mb-3 border">
                    <div class="row g-2 text-center small">
                        <div class="col-4">
                            <span class="text-muted d-block">Root Original Serial</span>
                            <strong class="font-monospace text-dark">${data.root_serial}</strong>
                        </div>
                        <div class="col-4">
                            <span class="text-muted d-block">Active Unit</span>
                            <strong class="font-monospace text-success">${data.active_serial}</strong>
                        </div>
                        <div class="col-4">
                            <span class="text-muted d-block">Warranty Status</span>
                            <span class="badge ${data.warranty_status === 'active' ? 'bg-success' : 'bg-danger'}">
                                ${data.warranty_status.toUpperCase()}
                            </span>
                        </div>
                    </div>
                </div>
                <div class="timeline-container ps-4">
                    ${nodesHtml}
                </div>
            `;
            $('#timelineModalBody').html(html);
        } else {
            $('#timelineModalBody').html('<div class="alert alert-danger">Failed to load timeline.</div>');
        }
    });
}
</script>
@endpush
