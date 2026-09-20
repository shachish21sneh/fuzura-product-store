@extends('layouts.customer')

@section('title', 'My Registered Products')
@section('page_title', 'My Registered Hardware Units')

@section('content')
<div class="container-fluid px-0">

    <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-white">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
            <div>
                <h5 class="fw-bold mb-1 text-dark">My Registered Products & Warranties</h5>
                <p class="text-muted small mb-0">View all active and historical hardware units registered under your account.</p>
            </div>
            <a href="{{ route('customer.products.register') }}" class="btn btn-primary-custom">
                <i class="fas fa-plus-circle me-1"></i> Register Another Unit
            </a>
        </div>
    </div>

    <!-- Products Table Card -->
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white">
        <div class="table-responsive">
            <table class="table table-custom mb-0 align-middle">
                <thead>
                    <tr>
                        <th>Serial Number</th>
                        <th>Model</th>
                        <th>Dealer Purchased</th>
                        <th>Purchase Date</th>
                        <th>Warranty Expiry</th>
                        <th>Coverage Status</th>
                        <th class="text-end">Documents</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($registrations as $reg)
                        @php
                            $isExp = $reg->isExpired();
                        @endphp
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="font-monospace fw-bold text-dark fs-6">{{ $reg->product_serial_number }}</span>
                                    <button type="button" class="btn btn-sm btn-light py-0 px-1 text-muted" onclick="copyToClipboard('{{ $reg->product_serial_number }}')">
                                        <i class="far fa-copy"></i>
                                    </button>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-primary-subtle text-primary fw-semibold">{{ $reg->product_model }}</span>
                            </td>
                            <td>
                                <div class="fw-medium text-dark">{{ $reg->dealer_name }}</div>
                            </td>
                            <td>{{ $reg->purchase_date->format('d M, Y') }}</td>
                            <td>
                                <div class="fw-semibold {{ $isExp ? 'text-danger' : 'text-success' }}">
                                    {{ $reg->warranty_end_date->format('d M, Y') }}
                                </div>
                                <small class="{{ $isExp ? 'text-danger' : 'text-muted' }}">
                                    {{ $isExp ? 'Coverage Ended' : $reg->daysRemaining() . ' Days Remaining' }}
                                </small>
                            </td>
                            <td>
                                @if ($reg->status === 'approved')
                                    @if ($isExp)
                                        <span class="badge badge-soft-danger fw-bold"><i class="fas fa-calendar-xmark me-1"></i> Expired</span>
                                    @else
                                        <span class="badge badge-soft-success fw-bold"><i class="fas fa-shield-check me-1"></i> Active</span>
                                    @endif
                                @elseif ($reg->status === 'pending')
                                    <span class="badge badge-soft-warning fw-bold"><i class="fas fa-clock me-1"></i> Verification Pending</span>
                                @else
                                    <span class="badge badge-soft-danger fw-bold"><i class="fas fa-ban me-1"></i> Rejected</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('customer.products.certificate', $reg->id) }}" class="btn btn-sm btn-outline-primary py-1 px-2" title="Print Certificate">
                                        <i class="fas fa-certificate text-warning me-1"></i> Certificate
                                    </a>
                                    @if ($reg->bill_path)
                                        <a href="{{ route('customer.products.view_bill', $reg->id) }}" target="_blank" class="btn btn-sm btn-light border py-1 px-2" title="View Uploaded Invoice">
                                            <i class="fas fa-file-invoice me-1"></i> Invoice
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="fas fa-boxes-stacked fs-1 mb-3 text-secondary opacity-50 d-block"></i>
                                <h5>No Registered Equipment Found</h5>
                                <p class="small mb-3">You haven't registered any equipment under this account yet.</p>
                                <a href="{{ route('customer.products.register') }}" class="btn btn-primary-custom btn-sm">
                                    Register Equipment
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($registrations->hasPages())
            <div class="card-footer bg-white py-3 border-top d-flex justify-content-between align-items-center">
                <small class="text-muted">Showing {{ $registrations->firstItem() }} to {{ $registrations->lastItem() }} of {{ $registrations->total() }} units</small>
                {{ $registrations->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
