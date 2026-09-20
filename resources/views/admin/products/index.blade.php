@extends('layouts.admin')

@section('title', 'Product Master')
@section('page_title', 'Product Master Directory')

@section('content')
<div class="container-fluid px-0">

    <!-- Header & Action Row -->
    <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-white">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
            <div>
                <h5 class="fw-bold mb-1 text-dark">Product Master Catalog</h5>
                <p class="text-muted small mb-0">Manage hardware models, serial numbers, warranty parameters, and spare parts.</p>
            </div>
            <button type="button" class="btn btn-primary-custom" data-bs-toggle="modal" data-bs-target="#addProductModal">
                <i class="fas fa-plus me-1"></i> Add New Product
            </button>
        </div>

        <!-- Filter & Search Bar -->
        <form action="{{ route('admin.products.index') }}" method="GET" class="row g-3 mt-2 pt-3 border-top">
            <div class="col-md-4">
                <div class="input-group">
                    <span class="input-group-text bg-light text-muted border-end-0"><i class="fas fa-search"></i></span>
                    <input type="text" name="search" class="form-control border-start-0 ps-0" 
                           placeholder="Search Serial, Model, Vendor..." value="{{ request('search') }}">
                </div>
            </div>

            <div class="col-md-3">
                <select name="model_number" class="form-select">
                    <option value="">All Models</option>
                    @foreach ($models as $m)
                        <option value="{{ $m }}" {{ request('model_number') === $m ? 'selected' : '' }}>{{ $m }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3">
                <select name="status" class="form-select">
                    <option value="">All Statuses</option>
                    <option value="available" {{ request('status') === 'available' ? 'selected' : '' }}>Available (In Stock)</option>
                    <option value="sold" {{ request('status') === 'sold' ? 'selected' : '' }}>Sold (With Customer)</option>
                    <option value="replaced" {{ request('status') === 'replaced' ? 'selected' : '' }}>Replaced</option>
                    <option value="scrapped" {{ request('status') === 'scrapped' ? 'selected' : '' }}>Scrapped</option>
                </select>
            </div>

            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-secondary w-100"><i class="fas fa-filter me-1"></i> Filter</button>
                <a href="{{ route('admin.products.index') }}" class="btn btn-light border" title="Reset"><i class="fas fa-rotate-left"></i></a>
            </div>
        </form>
    </div>

    <!-- Products DataTable Card -->
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white">
        <div class="table-responsive">
            <table class="table table-custom mb-0 align-middle">
                <thead>
                    <tr>
                        <th>Serial Number</th>
                        <th>Model Number</th>
                        <th>Mfg Date</th>
                        <th>Warranty Period</th>
                        <th>Status</th>
                        <th>Vendor / Spares</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($products as $product)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="font-monospace fw-bold text-dark">{{ $product->serial_number }}</span>
                                    <button type="button" class="btn btn-sm btn-light py-0 px-1 text-muted" onclick="copyToClipboard('{{ $product->serial_number }}')">
                                        <i class="far fa-copy"></i>
                                    </button>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-primary-subtle text-primary fw-semibold">{{ $product->model_number }}</span>
                            </td>
                            <td>{{ $product->manufacturing_date ? $product->manufacturing_date->format('d M, Y') : 'N/A' }}</td>
                            <td>
                                <span class="fw-semibold text-dark">{{ $product->warranty_period_years }} Year(s)</span>
                            </td>
                            <td>
                                @if ($product->status === 'available')
                                    <span class="badge badge-soft-success fw-bold"><i class="fas fa-check-circle me-1"></i> Available</span>
                                @elseif ($product->status === 'sold')
                                    <span class="badge badge-soft-primary fw-bold"><i class="fas fa-cart-shopping me-1"></i> Sold</span>
                                @elseif ($product->status === 'replaced')
                                    <span class="badge badge-soft-warning fw-bold"><i class="fas fa-repeat me-1"></i> Replaced</span>
                                @else
                                    <span class="badge badge-soft-secondary fw-bold">{{ ucfirst($product->status) }}</span>
                                @endif
                            </td>
                            <td>
                                <div class="text-truncate" style="max-width: 180px;" title="{{ $product->spare_details }}">
                                    <span class="fw-medium text-dark">{{ $product->spare_vendor ?? 'Direct MFG' }}</span>
                                    <small class="text-muted d-block text-truncate">{{ $product->spare_details ?? 'Standard' }}</small>
                                </div>
                            </td>
                            <td class="text-end">
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-light rounded-circle border" type="button" data-bs-toggle="dropdown">
                                        <i class="fas fa-ellipsis-vertical"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                                        <li>
                                            <a class="dropdown-item" href="javascript:void(0)" onclick="viewProductDetails({{ $product->id }})">
                                                <i class="fas fa-eye me-2 text-info"></i> View Details
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="javascript:void(0)" onclick="editProduct({{ $product->id }})">
                                                <i class="fas fa-edit me-2 text-primary"></i> Edit Product
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="{{ route('admin.search.index', ['serial_number' => $product->serial_number]) }}">
                                                <i class="fas fa-magnifying-glass me-2 text-success"></i> Deep Search
                                            </a>
                                        </li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" id="delete-product-{{ $product->id }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="dropdown-item text-danger" onclick="confirmDelete('delete-product-{{ $product->id }}', 'Delete product {{ $product->serial_number }}?')">
                                                    <i class="fas fa-trash me-2"></i> Delete Product
                                                </button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="fas fa-boxes-stacked fs-1 mb-3 text-secondary opacity-50 d-block"></i>
                                <h5>No Products Found</h5>
                                <p class="small mb-3">No products match the selected criteria.</p>
                                <button type="button" class="btn btn-primary-custom btn-sm" data-bs-toggle="modal" data-bs-target="#addProductModal">
                                    Add First Product
                                </button>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($products->hasPages())
            <div class="card-footer bg-white py-3 border-top d-flex justify-content-between align-items-center">
                <small class="text-muted">Showing {{ $products->firstItem() }} to {{ $products->lastItem() }} of {{ $products->total() }} units</small>
                {{ $products->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Add Product Modal -->
<div class="modal fade" id="addProductModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header bg-light">
                <h5 class="modal-title fw-bold"><i class="fas fa-plus-circle text-primary me-2"></i> Add New Product</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.products.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Model Number <span class="text-danger">*</span></label>
                            <input type="text" name="model_number" class="form-control" placeholder="e.g. FZ-PRO-500" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Serial Number <span class="text-danger">*</span></label>
                            <input type="text" name="serial_number" class="form-control font-monospace" placeholder="e.g. FZ-SN-9099" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Manufacturing Date <span class="text-danger">*</span></label>
                            <input type="date" name="manufacturing_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Warranty Period (Years) <span class="text-danger">*</span></label>
                            <input type="number" name="warranty_period_years" class="form-control" min="1" max="10" value="2" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold small">Initial Status <span class="text-danger">*</span></label>
                            <select name="status" class="form-select" required>
                                <option value="available" selected>Available (In Stock)</option>
                                <option value="sold">Sold</option>
                                <option value="replaced">Replaced</option>
                                <option value="scrapped">Scrapped</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold small">Spare Vendor (Optional)</label>
                            <input type="text" name="spare_vendor" class="form-control" placeholder="e.g. Precision Components Ltd">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold small">Spare Details (Optional)</label>
                            <textarea name="spare_details" rows="2" class="form-control" placeholder="List key components, part numbers..."></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary-custom">Save Product</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Product Modal -->
<div class="modal fade" id="editProductModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header bg-light">
                <h5 class="modal-title fw-bold"><i class="fas fa-edit text-primary me-2"></i> Edit Product</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editProductForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Model Number <span class="text-danger">*</span></label>
                            <input type="text" name="model_number" id="editModelNumber" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Serial Number <span class="text-danger">*</span></label>
                            <input type="text" name="serial_number" id="editSerialNumber" class="form-control font-monospace" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Manufacturing Date <span class="text-danger">*</span></label>
                            <input type="date" name="manufacturing_date" id="editManufacturingDate" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Warranty Period (Years) <span class="text-danger">*</span></label>
                            <input type="number" name="warranty_period_years" id="editWarrantyPeriod" class="form-control" min="1" max="10" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold small">Status <span class="text-danger">*</span></label>
                            <select name="status" id="editStatus" class="form-select" required>
                                <option value="available">Available (In Stock)</option>
                                <option value="sold">Sold</option>
                                <option value="replaced">Replaced</option>
                                <option value="scrapped">Scrapped</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold small">Spare Vendor</label>
                            <input type="text" name="spare_vendor" id="editSpareVendor" class="form-control">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold small">Spare Details</label>
                            <textarea name="spare_details" id="editSpareDetails" rows="2" class="form-control"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary-custom">Update Product</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- View Product Modal -->
<div class="modal fade" id="viewProductModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header bg-light">
                <h5 class="modal-title fw-bold"><i class="fas fa-circle-info text-info me-2"></i> Product Information</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4" id="viewProductDetailsBody">
                <div class="text-center py-4 text-muted"><i class="fas fa-spinner fa-spin fs-4"></i> Loading...</div>
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
function editProduct(id) {
    $.get('/admin/products/' + id, function(res) {
        if (res.success) {
            const p = res.product;
            $('#editProductForm').attr('action', '/admin/products/' + id);
            $('#editModelNumber').val(p.model_number);
            $('#editSerialNumber').val(p.serial_number);
            $('#editManufacturingDate').val(p.manufacturing_date ? p.manufacturing_date.split('T')[0] : '');
            $('#editWarrantyPeriod').val(p.warranty_period_years);
            $('#editStatus').val(p.status);
            $('#editSpareVendor').val(p.spare_vendor);
            $('#editSpareDetails').val(p.spare_details);
            new bootstrap.Modal(document.getElementById('editProductModal')).show();
        }
    });
}

function viewProductDetails(id) {
    new bootstrap.Modal(document.getElementById('viewProductModal')).show();
    $.get('/admin/products/' + id, function(res) {
        if (res.success) {
            const p = res.product;
            let html = `
                <div class="row g-3">
                    <div class="col-6">
                        <small class="text-muted d-block text-uppercase fw-semibold" style="font-size: 0.72rem;">Serial Number</small>
                        <strong class="font-monospace fs-6 text-dark">${p.serial_number}</strong>
                    </div>
                    <div class="col-6">
                        <small class="text-muted d-block text-uppercase fw-semibold" style="font-size: 0.72rem;">Model Number</small>
                        <span class="badge bg-primary-subtle text-primary">${p.model_number}</span>
                    </div>
                    <div class="col-6">
                        <small class="text-muted d-block text-uppercase fw-semibold" style="font-size: 0.72rem;">Manufacturing Date</small>
                        <span class="text-dark">${p.manufacturing_date ? p.manufacturing_date.split('T')[0] : 'N/A'}</span>
                    </div>
                    <div class="col-6">
                        <small class="text-muted d-block text-uppercase fw-semibold" style="font-size: 0.72rem;">Warranty Period</small>
                        <span class="text-dark">${p.warranty_period_years} Year(s)</span>
                    </div>
                    <div class="col-6">
                        <small class="text-muted d-block text-uppercase fw-semibold" style="font-size: 0.72rem;">Current Status</small>
                        <span class="badge bg-secondary">${p.status.toUpperCase()}</span>
                    </div>
                    <div class="col-6">
                        <small class="text-muted d-block text-uppercase fw-semibold" style="font-size: 0.72rem;">Spare Vendor</small>
                        <span class="text-dark">${p.spare_vendor || 'None specified'}</span>
                    </div>
                    <div class="col-12 pt-2 border-top">
                        <small class="text-muted d-block text-uppercase fw-semibold" style="font-size: 0.72rem;">Spare Components</small>
                        <p class="small text-muted mb-0">${p.spare_details || 'No specific spare details recorded.'}</p>
                    </div>
                </div>
            `;
            $('#viewProductDetailsBody').html(html);
        }
    });
}
</script>
@endpush
