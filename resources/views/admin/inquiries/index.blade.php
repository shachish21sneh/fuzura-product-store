@extends('layouts.admin')

@section('title', 'Customer Support Inquiries')
@section('page_title', 'Support Tickets & Inquiries')

@section('content')
<div class="container-fluid px-0">

    <!-- KPI Metric Cards -->
    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-muted small fw-bold text-uppercase">Total Inquiries</div>
                        <div class="fs-3 fw-bold text-dark mt-1">{{ number_format($totalInquiries) }}</div>
                    </div>
                    <div class="stat-icon-wrapper bg-primary-subtle text-primary">
                        <i class="fas fa-headset"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-muted small fw-bold text-uppercase">New / Unread</div>
                        <div class="fs-3 fw-bold text-danger mt-1">{{ number_format($unreadCount) }}</div>
                    </div>
                    <div class="stat-icon-wrapper bg-danger-subtle text-danger">
                        <i class="fas fa-envelope"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-muted small fw-bold text-uppercase">Handled / Replied</div>
                        <div class="fs-3 fw-bold text-info mt-1">{{ number_format($totalInquiries - $unreadCount) }}</div>
                    </div>
                    <div class="stat-icon-wrapper bg-info-subtle text-info">
                        <i class="fas fa-reply"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-muted small fw-bold text-uppercase">Resolution Rate</div>
                        <div class="fs-3 fw-bold text-success mt-1">
                            {{ $totalInquiries > 0 ? round((($totalInquiries - $unreadCount) / $totalInquiries) * 100) : 100 }}%
                        </div>
                    </div>
                    <div class="stat-icon-wrapper bg-success-subtle text-success">
                        <i class="fas fa-check-double"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Header & Search -->
    <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-white">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
            <div>
                <h5 class="fw-bold mb-1 text-dark">Customer Support Inquiries</h5>
                <p class="text-muted small mb-0">Messages and inquiries submitted by visitors and customers through the public contact form.</p>
            </div>
        </div>

        <form action="{{ route('admin.inquiries.index') }}" method="GET" class="row g-3 mt-2 pt-3 border-top">
            <div class="col-md-7">
                <div class="input-group">
                    <span class="input-group-text bg-light text-muted border-end-0"><i class="fas fa-search"></i></span>
                    <input type="text" name="search" class="form-control border-start-0 ps-0" 
                           placeholder="Search Sender Name, Email, Phone, Subject, Content..." value="{{ request('search') }}">
                </div>
            </div>

            <div class="col-md-3">
                <select name="status" class="form-select">
                    <option value="">All Statuses</option>
                    <option value="unread" {{ request('status') === 'unread' ? 'selected' : '' }}>New / Unread</option>
                    <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                    <option value="replied" {{ request('status') === 'replied' ? 'selected' : '' }}>Replied</option>
                    <option value="closed" {{ request('status') === 'closed' ? 'selected' : '' }}>Resolved / Closed</option>
                </select>
            </div>

            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-secondary w-100"><i class="fas fa-filter me-1"></i> Filter</button>
                <a href="{{ route('admin.inquiries.index') }}" class="btn btn-light border" title="Reset"><i class="fas fa-rotate-left"></i></a>
            </div>
        </form>
    </div>

    <!-- Inquiries Table Card -->
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white">
        <div class="table-responsive">
            <table class="table table-custom mb-0 align-middle">
                <thead>
                    <tr>
                        <th>Ticket ID</th>
                        <th>Sender Details</th>
                        <th>Subject & Message Preview</th>
                        <th>Status</th>
                        <th>Submitted At</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($inquiries as $inq)
                        <tr class="{{ $inq->status === 'unread' ? 'bg-light bg-opacity-50 fw-semibold' : '' }}">
                            <td>
                                <span class="badge bg-light text-secondary border font-monospace">#{{ str_pad($inq->id, 5, '0', STR_PAD_LEFT) }}</span>
                            </td>
                            <td>
                                <div class="text-dark fw-bold">{{ $inq->name }}</div>
                                <div class="small text-muted"><i class="fas fa-envelope me-1 text-secondary small"></i> <a href="mailto:{{ $inq->email }}" class="text-decoration-none text-muted">{{ $inq->email }}</a></div>
                                @if ($inq->phone)
                                    <div class="small text-muted"><i class="fas fa-phone me-1 text-secondary small"></i> {{ $inq->phone }}</div>
                                @endif
                            </td>
                            <td>
                                <div class="text-dark fw-semibold text-truncate" style="max-width: 320px;">
                                    {{ $inq->subject }}
                                </div>
                                <div class="text-muted small text-truncate" style="max-width: 360px;">
                                    {{ $inq->message }}
                                </div>
                            </td>
                            <td>
                                @php $badge = $inq->status_badge; @endphp
                                <span class="badge {{ $badge['class'] }} px-2.5 py-1">
                                    <i class="{{ $badge['icon'] }} me-1"></i> {{ $badge['label'] }}
                                </span>
                            </td>
                            <td>
                                <div class="small text-dark">{{ $inq->created_at->format('M d, Y') }}</div>
                                <small class="text-muted">{{ $inq->created_at->format('h:i A') }}</small>
                            </td>
                            <td class="text-end">
                                <button type="button" class="btn btn-light btn-sm text-primary view-ticket-btn" 
                                        data-id="{{ $inq->id }}" title="View Ticket & Reply">
                                    <i class="fas fa-folder-open"></i>
                                </button>
                                <form action="{{ route('admin.inquiries.destroy', $inq->id) }}" method="POST" class="d-inline"
                                      onsubmit="return confirm('Delete inquiry ticket #{{ $inq->id }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-light btn-sm text-danger" title="Delete Ticket">
                                        <i class="fas fa-trash-can"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="fas fa-inbox fa-3x mb-3 text-secondary opacity-50"></i>
                                <h6 class="fw-bold">No Support Inquiries Found</h6>
                                <p class="small mb-0">Submitted messages from the contact page will automatically show up here.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($inquiries->hasPages())
            <div class="p-3 border-top d-flex justify-content-end">
                {{ $inquiries->links() }}
            </div>
        @endif
    </div>

</div>

<!-- Modal: View Inquiry & Update Status -->
<div class="modal fade" id="ticketModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header border-0 pb-0">
                <div>
                    <h5 class="modal-title fw-bold" id="ticketModalTitle">Support Ticket Details</h5>
                    <small class="text-muted" id="ticketModalSubtitle"></small>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="ticketStatusForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <!-- Ticket Meta Details -->
                    <div class="row g-3 mb-4 p-3 bg-light rounded-3">
                        <div class="col-sm-6">
                            <span class="text-muted small d-block">Sender Name</span>
                            <strong class="text-dark" id="ticketSender"></strong>
                        </div>
                        <div class="col-sm-6">
                            <span class="text-muted small d-block">Sender Email</span>
                            <a href="#" id="ticketEmailLink" class="text-primary text-decoration-none fw-semibold"></a>
                        </div>
                        <div class="col-sm-6">
                            <span class="text-muted small d-block">Contact Phone</span>
                            <span class="text-dark" id="ticketPhone"></span>
                        </div>
                        <div class="col-sm-6">
                            <span class="text-muted small d-block">Received Date & Time</span>
                            <span class="text-dark" id="ticketDate"></span>
                        </div>
                    </div>

                    <!-- Subject & Full Message -->
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-bold text-uppercase">Subject</label>
                        <div class="p-2.5 bg-white border rounded-3 fw-semibold text-dark" id="ticketSubject"></div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label text-muted small fw-bold text-uppercase">Message Content</label>
                        <div class="p-3 bg-white border rounded-3 text-dark lh-base" id="ticketMessage" style="min-height: 100px; white-space: pre-wrap;"></div>
                    </div>

                    <!-- Status & Resolution Notes -->
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Ticket Status <span class="text-danger">*</span></label>
                            <select name="status" id="ticketStatusSelect" class="form-select" required>
                                <option value="unread">New / Unread</option>
                                <option value="in_progress">In Progress / Investigating</option>
                                <option value="replied">Replied to Customer</option>
                                <option value="closed">Resolved / Closed</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Quick Mail Action</label>
                            <a href="#" id="mailToBtn" target="_blank" class="btn btn-outline-primary w-100 d-flex align-items-center justify-content-center gap-2">
                                <i class="fas fa-envelope-open-text"></i>
                                <span>Open in Email Client</span>
                            </a>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold small">Admin Resolution Notes / Remarks</label>
                            <textarea name="admin_notes" id="ticketAdminNotes" rows="3" class="form-control" placeholder="Internal notes or summary of resolution..."></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary-custom px-4">Update Ticket Status</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const modalEl = document.getElementById('ticketModal');
    const modal = new bootstrap.Modal(modalEl);
    const form = document.getElementById('ticketStatusForm');

    document.querySelectorAll('.view-ticket-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            const ticketId = this.dataset.id;
            fetch(`/admin/inquiries/${ticketId}`)
                .then(res => res.json())
                .then(res => {
                    if (res.success) {
                        const inq = res.inquiry;
                        form.action = `/admin/inquiries/${inq.id}/status`;
                        document.getElementById('ticketModalTitle').textContent = `Inquiry Ticket #${String(inq.id).padStart(5, '0')}`;
                        document.getElementById('ticketModalSubtitle').textContent = `Status: ${inq.status.toUpperCase()}`;
                        document.getElementById('ticketSender').textContent = inq.name;
                        
                        const emailLink = document.getElementById('ticketEmailLink');
                        emailLink.textContent = inq.email;
                        emailLink.href = `mailto:${inq.email}?subject=Re: ${encodeURIComponent(inq.subject)}`;
                        
                        document.getElementById('ticketPhone').textContent = inq.phone || 'Not provided';
                        document.getElementById('ticketDate').textContent = inq.created_at ? new Date(inq.created_at).toLocaleString() : '—';
                        document.getElementById('ticketSubject').textContent = inq.subject;
                        document.getElementById('ticketMessage').textContent = inq.message;
                        document.getElementById('ticketStatusSelect').value = inq.status;
                        document.getElementById('ticketAdminNotes').value = inq.admin_notes || '';

                        const mailBtn = document.getElementById('mailToBtn');
                        mailBtn.href = `mailto:${inq.email}?subject=Re: ${encodeURIComponent(inq.subject)}&body=${encodeURIComponent('\n\n--- Original Inquiry ---\n' + inq.message)}`;

                        modal.show();
                    }
                })
                .catch(err => {
                    alert('Could not fetch ticket details.');
                });
        });
    });
});
</script>
@endpush
