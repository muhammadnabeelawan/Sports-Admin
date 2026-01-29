@extends('layouts.admin')

@section('title', 'Stock Management')

@section('content')
<div class="container-fluid">
    <div class="row align-items-center mb-4">
        <div class="col">
            <h2 class="fw-bold mb-0">Stock Inventory</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 small">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Stocks</li>
                </ol>
            </nav>
        </div>
        <div class="col-auto">
            <button type="button" class="btn btn-primary rounded-pill px-4 shadow-sm" data-bs-toggle="modal" data-bs-target="#updateStockModal">
                <i class="fas fa-plus me-2"></i> Add/Adjust Stock
            </button>
        </div>
    </div>

    <div class="table-glass p-3 mb-4 border-0">
        <form action="{{ route('stocks.index') }}" method="GET" class="row g-2">
            <div class="col-md-4">
                <div class="input-group input-group-sm bg-light rounded-pill px-2">
                    <span class="input-group-text bg-transparent border-0"><i class="fas fa-search text-muted"></i></span>
                    <input type="text" name="search" class="form-control bg-transparent border-0" placeholder="Search by name or code..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-3">
                <select name="category_id" class="form-select form-select-sm border-0 bg-light rounded-pill px-3">
                    <option value="">All Categories</option>
                    @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->title }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select form-select-sm border-0 bg-light rounded-pill px-3">
                    <option value="">All Status</option>
                    <option value="low" {{ request('status') == 'low' ? 'selected' : '' }}>Low Stock</option>
                    <option value="optimal" {{ request('status') == 'optimal' ? 'selected' : '' }}>Optimal</option>
                </select>
            </div>
            <div class="col-md-2">
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-dark btn-sm rounded-pill flex-fill">Filter</button>
                    <a href="{{ route('stocks.index') }}" class="btn btn-light btn-sm rounded-pill"><i class="fas fa-redo"></i></a>
                </div>
            </div>
        </form>
    </div>

    @if(session('success'))
    <div class="alert alert-success border-0 shadow-sm rounded-4 mb-4">
        {{ session('success') }}
    </div>
    @endif

    <div class="table-glass">
        <div class="table-responsive">
            <table class="table mb-0 align-middle">
                <thead>
                    <tr>
                        <th class="ps-4">Product</th>
                        <th>Variant</th>
                        <th>Store</th>
                        <th>Current Quantity</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($products as $product)
                        @if($product->have_variants && $product->variants->isNotEmpty())
                            @foreach($product->variants as $variant)
                                @php 
                                    $stock = $product->stocks->where('variant_id', $variant->id)->first();
                                @endphp
                                <tr>
                                    <td class="ps-4">
                                        <div class="fw-semibold">{{ $product->title }}</div>
                                        <div class="text-muted small">{{ $product->code }}</div>
                                    </td>
                                    <td><span class="badge bg-light text-dark fw-medium">{{ $variant->title }}</span></td>
                                    <td>{{ $stores->first()->title ?? 'Main' }}</td>
                                    <td><span class="fs-5 fw-bold {{ ($stock->quantity ?? 0) < 10 ? 'text-danger' : 'text-success' }}">{{ $stock->quantity ?? 0 }}</span></td>
                                    <td>
                                        @if(($stock->quantity ?? 0) < 10)
                                        <span class="badge bg-danger-soft text-danger">Low Stock</span>
                                        @else
                                        <span class="badge bg-success-soft text-success">Optimal</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            @php 
                                $stock = $product->stocks->where('variant_id', null)->first();
                            @endphp
                            <tr>
                                <td class="ps-4">
                                    <div class="fw-semibold">{{ $product->title }}</div>
                                    <div class="text-muted small">{{ $product->code }}</div>
                                </td>
                                <td><span class="text-muted small">Standard</span></td>
                                <td>{{ $stores->first()->title ?? 'Main' }}</td>
                                <td><span class="fs-5 fw-bold {{ ($stock->quantity ?? 0) < 10 ? 'text-danger' : 'text-success' }}">{{ $stock->quantity ?? 0 }}</span></td>
                                <td>
                                    @if(($stock->quantity ?? 0) < 10)
                                    <span class="badge bg-danger-soft text-danger">Low Stock</span>
                                    @else
                                    <span class="badge bg-success-soft text-success">Optimal</span>
                                    @endif
                                </td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Update Stock Modal -->
<div class="modal fade" id="updateStockModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow-lg">
            <div class="modal-header border-0 p-4">
                <h5 class="modal-title fw-bold">Adjust Inventory</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('stocks.update') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Select Store</label>
                        <select name="store_id" class="form-select" required>
                            @foreach($stores as $store)
                            <option value="{{ $store->id }}">{{ $store->title }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Product</label>
                        <select name="product_id" id="product_select" class="form-select" required onchange="updateVariantList()">
                            <option value="">Choose product...</option>
                            @foreach($products as $product)
                            <option value="{{ $product->id }}" data-has-variants="{{ $product->have_variants ? '1' : '0' }}">{{ $product->title }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3" id="variant_select_div" style="display: none;">
                        <label class="form-label fw-semibold">Variant</label>
                        <select name="variant_id" id="variant_select" class="form-select">
                            <!-- Dynamic Load -->
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Adjustment Quantity</label>
                        <input type="number" name="quantity" class="form-control" placeholder="e.g. 50 or -10" required>
                        <div class="form-text">Positive to add, negative to subtract.</div>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="button" class="btn btn-light rounded-3" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary rounded-3 px-4">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    const products = @json($products);

    function updateVariantList() {
        const productSelect = document.getElementById('product_select');
        const variantDiv = document.getElementById('variant_select_div');
        const variantSelect = document.getElementById('variant_select');
        
        const productId = productSelect.value;
        const product = products.find(p => p.id == productId);
        
        if (product && product.have_variants && product.variants.length > 0) {
            variantDiv.style.display = 'block';
            variantSelect.innerHTML = '<option value="">Select Variant</option>';
            product.variants.forEach(v => {
                variantSelect.innerHTML += `<option value="${v.id}">${v.title}</option>`;
            });
            variantSelect.required = true;
        } else {
            variantDiv.style.display = 'none';
            variantSelect.innerHTML = '';
            variantSelect.required = false;
        }
    }
</script>
@endsection
