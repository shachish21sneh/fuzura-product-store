@props(['timeline' => [], 'searchedSerial' => ''])

<div class="timeline-container py-3">
    @foreach ($timeline as $index => $node)
        @php
            $isSearched = ($node['serial_number'] === $searchedSerial);
            $isActive = $node['is_current_active'];
        @endphp
        <div class="timeline-node {{ $isActive ? 'active-unit' : '' }} {{ $isSearched ? 'searched-unit' : '' }}">
            <div class="timeline-marker">
                @if ($node['type'] === 'original')
                    <i class="fas fa-box"></i>
                @elseif ($isActive)
                    <i class="fas fa-check"></i>
                @else
                    <i class="fas fa-sync-alt"></i>
                @endif
            </div>

            <div class="timeline-card shadow-sm">
                <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-2">
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge {{ $isActive ? 'bg-success' : ($node['type'] === 'original' ? 'bg-primary' : 'bg-secondary') }} px-2 py-1">
                            {{ $node['title'] }}
                        </span>

                        @if ($isActive)
                            <span class="badge badge-soft-success fw-bold">
                                <i class="fas fa-shield-alt me-1"></i> Current Active Unit
                            </span>
                        @endif

                        @if ($isSearched)
                            <span class="badge badge-soft-primary fw-bold">
                                <i class="fas fa-search me-1"></i> Searched Serial
                            </span>
                        @endif
                    </div>

                    <div class="text-muted small">
                        <i class="far fa-calendar-alt me-1"></i>
                        {{ $node['date'] ? \Carbon\Carbon::parse($node['date'])->format('d M, Y') : 'Date N/A' }}
                    </div>
                </div>

                <div class="row g-3 mt-1">
                    <div class="col-md-6 col-lg-4">
                        <small class="text-muted d-block text-uppercase fw-semibold" style="font-size: 0.72rem;">Serial Number</small>
                        <div class="d-flex align-items-center gap-2">
                            <span class="font-monospace fw-bold fs-6 text-dark">{{ $node['serial_number'] }}</span>
                            <button type="button" class="btn btn-sm btn-light py-0 px-1 text-muted" onclick="copyToClipboard('{{ $node['serial_number'] }}')" title="Copy Serial">
                                <i class="far fa-copy"></i>
                            </button>
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-4">
                        <small class="text-muted d-block text-uppercase fw-semibold" style="font-size: 0.72rem;">Model</small>
                        <span class="fw-semibold text-secondary">{{ $node['model_number'] }}</span>
                    </div>

                    <div class="col-md-6 col-lg-4">
                        <small class="text-muted d-block text-uppercase fw-semibold" style="font-size: 0.72rem;">Dealer</small>
                        <span class="text-dark">{{ $node['dealer_name'] }}</span>
                    </div>

                    @if (!empty($node['customer_name']))
                        <div class="col-md-6 col-lg-4">
                            <small class="text-muted d-block text-uppercase fw-semibold" style="font-size: 0.72rem;">Customer</small>
                            <span class="text-dark">{{ $node['customer_name'] }}</span>
                        </div>
                    @endif

                    @if ($node['type'] === 'replacement' && !empty($node['replaced_from']))
                        <div class="col-md-6 col-lg-4">
                            <small class="text-muted d-block text-uppercase fw-semibold" style="font-size: 0.72rem;">Replaced From</small>
                            <span class="font-monospace text-muted">{{ $node['replaced_from'] }}</span>
                        </div>
                    @endif

                    <div class="col-12">
                        <small class="text-muted d-block text-uppercase fw-semibold" style="font-size: 0.72rem;">Remarks / Event</small>
                        <p class="mb-0 text-muted small fst-italic">
                            {{ $node['remarks'] ?? 'No additional remarks.' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>
