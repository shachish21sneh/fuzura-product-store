@props(['timeline' => [], 'searchedSerial' => ''])

<div class="timeline-container py-2">
    @foreach ($timeline as $index => $node)
        @php
            $isSearched = ($node['serial_number'] === $searchedSerial);
            $isActive = $node['is_current_active'];
        @endphp
        <div class="timeline-node {{ $isActive ? 'active-unit' : '' }} {{ $isSearched ? 'searched-unit' : '' }}">
            <div class="timeline-marker">
                @if ($node['type'] === 'original')
                    <i class="fas fa-box-archive" style="font-size: 0.85rem;"></i>
                @elseif ($isActive)
                    <i class="fas fa-circle-check" style="font-size: 0.95rem;"></i>
                @else
                    <i class="fas fa-rotate" style="font-size: 0.85rem;"></i>
                @endif
            </div>

            <div class="timeline-card">
                <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-2 pb-2 border-bottom">
                    <div class="d-flex align-items-center gap-2 flex-wrap">
                        <span class="badge {{ $isActive ? 'bg-success' : ($node['type'] === 'original' ? 'bg-primary' : 'bg-secondary') }} px-2.5 py-1 font-mono" style="font-size: 0.72rem;">
                            {{ $node['title'] }}
                        </span>

                        @if ($isActive)
                            <span class="badge badge-soft-success fw-bold font-mono">
                                <i class="fas fa-bolt me-1"></i> Current Active Unit
                            </span>
                        @endif

                        @if ($isSearched)
                            <span class="badge badge-soft-primary fw-bold font-mono">
                                <i class="fas fa-crosshairs me-1"></i> Searched Serial
                            </span>
                        @endif
                    </div>

                    <div class="text-muted small font-mono" style="font-size: 0.78rem;">
                        <i class="far fa-calendar-check me-1 text-primary"></i>
                        {{ $node['date'] ? \Carbon\Carbon::parse($node['date'])->format('d M, Y') : 'Date N/A' }}
                    </div>
                </div>

                <div class="row g-3">
                    <div class="col-md-6 col-lg-4">
                        <small class="text-muted d-block text-uppercase fw-bold" style="font-size: 0.7rem; letter-spacing: 0.05em;">Unit Serial Number</small>
                        <div class="d-flex align-items-center gap-2 mt-0.5">
                            <span class="font-mono fw-extrabold fs-6 text-dark">{{ $node['serial_number'] }}</span>
                            <button type="button" class="btn btn-sm btn-light border py-0 px-1.5 text-muted" onclick="copyToClipboard('{{ $node['serial_number'] }}')" title="Copy Serial">
                                <i class="far fa-copy" style="font-size: 0.75rem;"></i>
                            </button>
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-4">
                        <small class="text-muted d-block text-uppercase fw-bold" style="font-size: 0.7rem; letter-spacing: 0.05em;">Hardware Model</small>
                        <span class="fw-bold text-dark fs-6">{{ $node['model_number'] }}</span>
                    </div>

                    <div class="col-md-6 col-lg-4">
                        <small class="text-muted d-block text-uppercase fw-bold" style="font-size: 0.7rem; letter-spacing: 0.05em;">Point of Sale / Dealer</small>
                        <span class="fw-semibold text-dark">{{ $node['dealer_name'] }}</span>
                    </div>

                    @if (!empty($node['customer_name']))
                        <div class="col-md-6 col-lg-4">
                            <small class="text-muted d-block text-uppercase fw-bold" style="font-size: 0.7rem; letter-spacing: 0.05em;">Registered Customer</small>
                            <span class="fw-semibold text-dark">{{ $node['customer_name'] }}</span>
                        </div>
                    @endif

                    @if ($node['type'] === 'replacement' && !empty($node['replaced_from']))
                        <div class="col-md-6 col-lg-4">
                            <small class="text-muted d-block text-uppercase fw-bold" style="font-size: 0.7rem; letter-spacing: 0.05em;">Replaced Defective Unit</small>
                            <span class="font-mono text-danger fw-semibold">{{ $node['replaced_from'] }}</span>
                        </div>
                    @endif

                    <div class="col-12">
                        <small class="text-muted d-block text-uppercase fw-bold" style="font-size: 0.7rem; letter-spacing: 0.05em;">Event Reason & Log Details</small>
                        <p class="mb-0 text-muted small fst-italic mt-0.5">
                            {{ $node['remarks'] ?? 'Normal retail transaction and inventory assignment.' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>
