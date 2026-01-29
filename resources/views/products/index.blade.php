@extends('layouts.admin')

@section('title', 'Products')

@section('content')
<div class="container-fluid">
    <div class="row align-items-center mb-4">
        <div class="col">
            <h2 class="fw-bold mb-0">Products Catalog</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 small">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Products</li>
                </ol>
            </nav>
        </div>
        <div class="col-auto">
            <a href="{{ route('products.create') }}" class="btn btn-primary rounded-pill px-4 shadow-sm">
                <i class="fas fa-plus me-2"></i> Add New Product
            </a>
        </div>
    </div>

    <div class="table-glass p-3 mb-4 border-0">
        <form action="{{ route('products.index') }}" method="GET" class="row g-2">
            <div class="col-md-5">
                <div class="input-group input-group-sm bg-light rounded-pill px-2">
                    <span class="input-group-text bg-transparent border-0"><i class="fas fa-search text-muted"></i></span>
                    <input type="text" name="search" class="form-control bg-transparent border-0" placeholder="Search products..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-4">
                <select name="category_id" class="form-select form-select-sm border-0 bg-light rounded-pill px-3">
                    <option value="">All Categories</option>
                    @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->title }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-dark btn-sm rounded-pill flex-fill">Apply Filters</button>
                    <a href="{{ route('products.index') }}" class="btn btn-light btn-sm rounded-pill"><i class="fas fa-redo"></i></a>
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
            <table class="table mb-0 align-middle text-nowrap">
                <thead>
                    <tr>
                        <th class="ps-4">Code</th>
                        <th>Product</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Variants</th>
                        <th>Stock</th>
                        <th>Status</th>
                        <th class="text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                    <tr>
                        <td class="ps-4"><span class="text-muted small fw-bold">{{ $product->code }}</span></td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="bg-light rounded-3 d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                    <i class="fas fa-box text-muted"></i>
                                </div>
                                <div>
                                    <div class="fw-semibold">{{ $product->title }}</div>
                                    <div class="text-muted small">{{ $product->slug }}</div>
                                </div>
                            </div>
                        </td>
                        <td><span class="badge bg-light text-primary fw-medium">{{ $product->category->title ?? 'N/A' }}</span></td>
                        <td><span class="fw-bold">${{ number_format($product->price, 2) }}</span></td>
                        <td>
                            @if($product->have_variants)
                            <span class="badge bg-info-soft text-info">{{ $product->variants_count }} variants</span>
                            @else
                            <span class="text-muted small">Single Product</span>
                            @endif
                        </td>
                        <td>
                            @php $totalStock = $product->stocks->sum('quantity'); @endphp
                            @if($totalStock <= 5)
                            <span class="text-danger fw-bold"><i class="fas fa-exclamation-triangle me-1"></i> {{ $totalStock }}</span>
                            @else
                            <span class="text-success fw-bold">{{ $totalStock }}</span>
                            @endif
                        </td>
                        <td>
                            @if($product->status)
                            <span class="badge bg-success-soft text-success">Active</span>
                            @else
                            <span class="badge bg-secondary-soft text-secondary">Inactive</span>
                            @endif
                        </td>
                        <td class="text-end pe-4">
                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('products.show', $product) }}" class="btn btn-light btn-sm rounded-pill" title="View History">
                                    <i class="fas fa-eye text-info"></i>
                                </a>
                                <a href="{{ route('products.edit', $product) }}" class="btn btn-light btn-sm rounded-pill">
                                    <i class="fas fa-edit text-primary"></i>
                                </a>
                                <form action="{{ route('products.destroy', $product) }}" method="POST" onsubmit="return confirm('Delete this product?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-light btn-sm rounded-pill">
                                        <i class="fas fa-trash text-danger"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-5">
                            <i class="fas fa-box-open fa-3x text-light mb-3"></i>
                            <p class="text-muted">No products found yet.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
