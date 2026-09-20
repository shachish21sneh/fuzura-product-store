@extends('layouts.admin')

@section('title', 'Reports & Export')
@section('page_title', 'Operational Reports & Analytics')

@section('content')
<div class="container-fluid px-0">

    <!-- Header & Filter Form -->
    <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-white">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
            <div>
                <h5 class="fw-bold mb-1 text-dark">System Reports & Exports</h5>
                <p class="text-muted small mb-0">Generate filtered operational reports for sales, warranty registrations, and replacement histories.</p>
            </div>
            <div>
                <a href="{{ route('admin.reports.export_csv', ['type' => $type, 'from_date' => $fromDate, 'to_date' => $toDate]) }}" class="btn btn-success px-3">
                    <i class="fas fa-file-csv me-1"></i> Export to CSV
                </a>
            </div>
        </div>

        <form action="{{ route('admin.reports.index') }}" method="GET" class="row g-3 mt-2 pt-3 border-top">
            <div class="col-md-4">
                <label class="form-label small fw-semibold">Report Category</label>
                <select name="type" class="form-select">
                    <option value="sales" {{ $type === 'sales' ? 'selected' : '' }}>Product Sales Report</option>
                    <option value="warranties" {{ $type === 'warranties' ? 'selected' : '' }}>Warranty Claims & Registrations</option>
                    <option value="replacements" {{ $type === 'replacements' ? 'selected' : '' }}>Product Replacements History</option>
                </select>
            </div>

            <div class="col-md-3">
                <label class="form-label small fw-semibold">From Date</label>
                <input type="date" name="from_date" class="form-control" value="{{ $fromDate }}">
            </div>

            <div class="col-md-3">
                <label class="form-label small fw-semibold">To Date</label>
                <input type="date" name="to_date" class="form-control" value="{{ $toDate }}">
            </div>

            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-primary-custom w-100"><i class="fas fa-filter me-1"></i> Generate</button>
            </div>
        </form>
    </div>

    <!-- Report Results Table -->
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white">
        <div class="card-header bg-light py-3 d-flex justify-content-between align-items-center">
            <span class="fw-bold text-dark text-uppercase small">
                Report Type: {{ ucfirst($type) }} ({{ $data->total() }} Records Found)
            </span>
            <span class="text-muted small">Period: {{ $fromDate }} to {{ $toDate }}</span>
        </div>

        <div class="table-responsive">
            <table class="table table-custom mb-0 align-middle">
                @if ($type === 'sales')
                    <thead>
                        <tr>
                            <th>Bill Date</th>
                            <th>Serial Number</th>
                            <th>Model</th>
                            <th>Dealer</th>
                            <th>Customer</th>
                            <th>Mobile</th>
                            <th>Invoice #</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($data as $r)
                            <tr>
                                <td>{{ $r->bill_date->format('d M, Y') }}</td>
                                <td class="font-monospace fw-bold text-dark">{{ $r->product_serial_number }}</td>
                                <td><span class="badge bg-primary-subtle text-primary">{{ $r->product_model }}</span></td>
                                <td>{{ $r->dealer_name }}</td>
                                <td>{{ $r->customer_name }}</td>
                                <td>{{ $r->customer_mobile }}</td>
                                <td><span class="font-monospace text-muted">{{ $r->invoice_number ?? 'N/A' }}</span></td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="text-center py-4 text-muted">No sales records found for this period.</td></tr>
                        @endforelse
                    </tbody>
                @elseif ($type === 'warranties')
                    <thead>
                        <tr>
                            <th>Registered Date</th>
                            <th>Serial Number</th>
                            <th>Model</th>
                            <th>Customer Name</th>
                            <th>Customer Email</th>
                            <th>Warranty Expiry</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($data as $r)
                            <tr>
                                <td>{{ $r->created_at->format('d M, Y') }}</td>
                                <td class="font-monospace fw-bold text-dark">{{ $r->product_serial_number }}</td>
                                <td><span class="badge bg-primary-subtle text-primary">{{ $r->product_model }}</span></td>
                                <td>{{ $r->customer_name }}</td>
                                <td>{{ $r->customer_email }}</td>
                                <td>{{ $r->warranty_end_date->format('d M, Y') }}</td>
                                <td>
                                    <span class="badge {{ $r->status === 'approved' ? 'bg-success' : 'bg-warning' }}">
                                        {{ ucfirst($r->status) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="text-center py-4 text-muted">No warranty records found for this period.</td></tr>
                        @endforelse
                    </tbody>
                @elseif ($type === 'replacements')
                    <thead>
                        <tr>
                            <th>Replacement Date</th>
                            <th>Old Serial</th>
                            <th>New Serial</th>
                            <th>Customer</th>
                            <th>Dealer</th>
                            <th>Remarks</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($data as $r)
                            <tr>
                                <td>{{ $r->replacement_date->format('d M, Y') }}</td>
                                <td class="font-monospace text-danger fw-bold">{{ $r->old_serial_number }}</td>
                                <td class="font-monospace text-success fw-bold">{{ $r->new_serial_number }}</td>
                                <td>{{ $r->customer_name }}</td>
                                <td>{{ $r->dealer_name }}</td>
                                <td><span class="small text-muted">{{ $r->remarks }}</span></td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="text-center py-4 text-muted">No replacement records found for this period.</td></tr>
                        @endforelse
                    </tbody>
                @endif
            </table>
        </div>

        @if ($data->hasPages())
            <div class="card-footer bg-white py-3 border-top d-flex justify-content-between align-items-center">
                <small class="text-muted">Showing page {{ $data->currentPage() }} of {{ $data->lastPage() }}</small>
                {{ $data->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
