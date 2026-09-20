@extends('layouts.admin')

@section('title', 'Admin Dashboard')
@section('page_title', 'System Overview & Analytics')

@section('content')
<div class="container-fluid px-0">

    <!-- Top Summary Banner -->
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="metric-card h-100">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="metric-title">Catalog Units</div>
                        <div class="metric-value">{{ $totalProducts }}</div>
                    </div>
                    <div class="metric-icon-box bg-primary-subtle text-primary">
                        <i class="fas fa-boxes-stacked"></i>
                    </div>
                </div>
                <div class="mt-2 text-muted small">
                    <span class="text-success fw-bold"><i class="fas fa-check"></i> Master inventory</span>
                </div>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="metric-card h-100">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="metric-title">Units Sold</div>
                        <div class="metric-value">{{ $totalSoldProducts }}</div>
                    </div>
                    <div class="metric-icon-box bg-info-subtle text-info">
                        <i class="fas fa-cart-shopping"></i>
                    </div>
                </div>
                <div class="mt-2 text-muted small">
                    <span class="text-info fw-bold">Dealer invoiced</span>
                </div>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="metric-card h-100">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="metric-title">Warranties Active</div>
                        <div class="metric-value text-success">{{ $activeWarranty }}</div>
                    </div>
                    <div class="metric-icon-box bg-success-subtle text-success">
                        <i class="fas fa-shield-check"></i>
                    </div>
                </div>
                <div class="mt-2 text-muted small">
                    <span class="text-success fw-bold"><i class="fas fa-arrow-trend-up"></i> In warranty</span>
                </div>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="metric-card h-100">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="metric-title">Replacements</div>
                        <div class="metric-value text-primary">{{ $replacementProducts }}</div>
                    </div>
                    <div class="metric-icon-box bg-warning-subtle text-warning">
                        <i class="fas fa-repeat"></i>
                    </div>
                </div>
                <div class="mt-2 text-muted small">
                    <span class="text-muted">Total unit swaps</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Secondary Metrics Row -->
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="metric-card h-100">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="metric-title">Registered Units</div>
                        <div class="metric-value">{{ $totalRegistered }}</div>
                    </div>
                    <div class="metric-icon-box bg-primary-subtle text-primary">
                        <i class="fas fa-id-card"></i>
                    </div>
                </div>
                <div class="mt-2 text-muted small">Customer activated</div>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="metric-card h-100">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="metric-title">Pending Approvals</div>
                        <div class="metric-value text-warning">{{ $pendingRegistrations }}</div>
                    </div>
                    <div class="metric-icon-box bg-warning-subtle text-warning">
                        <i class="fas fa-hourglass-half"></i>
                    </div>
                </div>
                <div class="mt-2 text-muted small">Awaiting verification</div>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="metric-card h-100">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="metric-title">Expired Warranty</div>
                        <div class="metric-value text-danger">{{ $expiredWarranty }}</div>
                    </div>
                    <div class="metric-icon-box bg-danger-subtle text-danger">
                        <i class="fas fa-calendar-xmark"></i>
                    </div>
                </div>
                <div class="mt-2 text-muted small">Coverage elapsed</div>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="metric-card h-100">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="metric-title">Total Customers</div>
                        <div class="metric-value">{{ $totalCustomers }}</div>
                    </div>
                    <div class="metric-icon-box bg-secondary-subtle text-dark">
                        <i class="fas fa-users"></i>
                    </div>
                </div>
                <div class="mt-2 text-muted small">Enrolled accounts</div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row g-4 mb-4">
        <!-- Monthly Sales Bar Chart -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white h-100">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h6 class="fw-bold mb-0 text-dark">Monthly Sales & Registration Trends</h6>
                        <small class="text-muted">Trailing 6 Months Volume</small>
                    </div>
                    <span class="badge bg-light text-muted border">Realtime</span>
                </div>
                <div style="height: 280px;">
                    <canvas id="monthlyTrendsChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Warranty Status Doughnut Chart -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white h-100">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h6 class="fw-bold mb-0 text-dark">Warranty Health Ratio</h6>
                        <small class="text-muted">Breakdown of Registered Units</small>
                    </div>
                    <i class="fas fa-shield text-muted"></i>
                </div>
                <div style="height: 240px; position: relative;" class="d-flex align-items-center justify-content-center">
                    <canvas id="warrantyRatioChart"></canvas>
                </div>
                <div class="mt-3 pt-2 border-top d-flex justify-content-around text-center small">
                    <div>
                        <span class="d-block fw-bold text-success">{{ $activeWarranty }}</span>
                        <span class="text-muted" style="font-size: 0.75rem;">Active</span>
                    </div>
                    <div>
                        <span class="d-block fw-bold text-danger">{{ $expiredWarranty }}</span>
                        <span class="text-muted" style="font-size: 0.75rem;">Expired</span>
                    </div>
                    <div>
                        <span class="d-block fw-bold text-warning">{{ $pendingRegistrations }}</span>
                        <span class="text-muted" style="font-size: 0.75rem;">Pending</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Tables & Activity Row -->
    <div class="row g-4">
        <!-- Recent Sales Table -->
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white h-100">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h6 class="fw-bold mb-0 text-dark">Recent Sales Transactions</h6>
                        <small class="text-muted">Latest equipment sold by dealers</small>
                    </div>
                    <a href="{{ route('admin.sales.index') }}" class="btn btn-sm btn-outline-primary px-3">View All</a>
                </div>

                <div class="table-responsive">
                    <table class="table table-custom align-middle">
                        <thead>
                            <tr>
                                <th>Serial / Model</th>
                                <th>Dealer</th>
                                <th>Customer</th>
                                <th>Bill Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($recentSales as $sale)
                                <tr>
                                    <td>
                                        <span class="font-monospace fw-bold text-dark d-block">{{ $sale->product_serial_number }}</span>
                                        <small class="text-muted">{{ $sale->product_model }}</small>
                                    </td>
                                    <td>{{ $sale->dealer_name }}</td>
                                    <td>{{ $sale->customer_name }}</td>
                                    <td>{{ $sale->bill_date->format('d M, Y') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-4">No recent sales recorded.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Recent Activity Feed -->
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white h-100">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h6 class="fw-bold mb-0 text-dark">System Activity Audit</h6>
                        <small class="text-muted">Realtime operations log</small>
                    </div>
                    <i class="fas fa-list-check text-muted"></i>
                </div>

                <div class="d-flex flex-column gap-3 overflow-auto" style="max-height: 380px;">
                    @forelse ($recentActivities as $log)
                        <div class="d-flex align-items-start gap-3 p-2 rounded-3 bg-light">
                            <div class="p-2 rounded-circle bg-white text-primary shadow-sm mt-1">
                                <i class="fas fa-clock fs-6"></i>
                            </div>
                            <div class="flex-grow-1 min-w-0">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <span class="badge bg-secondary-subtle text-dark fw-semibold" style="font-size: 0.7rem;">
                                        {{ $log->action }}
                                    </span>
                                    <small class="text-muted" style="font-size: 0.7rem;">
                                        {{ $log->created_at->diffForHumans() }}
                                    </small>
                                </div>
                                <p class="small text-dark mb-0 text-truncate" title="{{ $log->description }}">
                                    {{ $log->description }}
                                </p>
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-muted py-4 small">No activity logs recorded.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Monthly Trends Chart (Sales vs Registrations)
        const ctxTrends = document.getElementById('monthlyTrendsChart').getContext('2d');
        new Chart(ctxTrends, {
            type: 'bar',
            data: {
                labels: {!! json_encode($monthLabels) !!},
                datasets: [
                    {
                        label: 'Units Sold',
                        data: {!! json_encode($monthlySales) !!},
                        backgroundColor: '#4f46e5',
                        borderRadius: 6,
                    },
                    {
                        label: 'Registrations',
                        data: {!! json_encode($monthlyRegistrations) !!},
                        backgroundColor: '#10b981',
                        borderRadius: 6,
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'top' }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { precision: 0 }
                    }
                }
            }
        });

        // Warranty Status Ratio Doughnut Chart
        const ctxRatio = document.getElementById('warrantyRatioChart').getContext('2d');
        new Chart(ctxRatio, {
            type: 'doughnut',
            data: {
                labels: ['Active', 'Expired', 'Pending'],
                datasets: [{
                    data: [{{ $activeWarranty }}, {{ $expiredWarranty }}, {{ $pendingRegistrations }}],
                    backgroundColor: ['#10b981', '#ef4444', '#f59e0b'],
                    borderWidth: 2,
                    borderColor: '#ffffff',
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                cutout: '70%'
            }
        });
    });
</script>
@endpush
