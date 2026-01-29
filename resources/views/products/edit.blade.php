@extends('layouts.admin')

@section('title', 'Edit Product')

@section('content')
<div class="container-fluid">
    <div class="d-flex align-items-center mb-4">
        <a href="{{ route('products.index') }}" class="btn btn-light rounded-circle me-3">
            <i class="fas fa-arrow-left"></i>
        </a>
        <h2 class="fw-bold mb-0">Edit Product</h2>
    </div>

    <form action="{{ route('products.update', $product) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="row">
            <div class="col-lg-8">
                <!-- Basic Info -->
                <div class="stat-card mb-4">
                    <h5 class="fw-bold mb-4">General Information</h5>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Product Title</label>
                        <input type="text" name="title" id="title" class="form-control" value="{{ $product->title }}" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Product Code</label>
                            <input type="text" name="code" class="form-control" value="{{ $product->code }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Slug</label>
                            <input type="text" name="slug" id="slug" class="form-control" value="{{ $product->slug }}" required readonly>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Description</label>
                        <textarea name="description" class="form-control" rows="4">{{ $product->description }}</textarea>
                    </div>
                </div>

                <!-- Pricing & Stock Logic -->
                <div class="stat-card mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="fw-bold mb-0">Pricing & Variants</h5>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="have_variants" id="haveVariants" value="1" {{ $product->have_variants ? 'checked' : '' }}>
                            <label class="form-check-label fw-semibold" for="haveVariants">This product has variants</label>
                        </div>
                    </div>

                    <div id="singleProductPrice" style="{{ $product->have_variants ? 'display: none;' : '' }}">
                        <div class="row">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Base Price ($)</label>
                                <input type="number" step="0.01" name="price" class="form-control" value="{{ $product->price }}">
                            </div>
                        </div>
                    </div>

                    <div id="variantSection" style="{{ $product->have_variants ? '' : 'display: none;' }}">
                        <hr class="my-4 opacity-10">
                        <div id="variantList">
                            @foreach($product->variants as $variant)
                            <div class="variant-item bg-light p-3 rounded-4 mb-3 position-relative">
                                <button type="button" class="btn-close position-absolute top-0 end-0 m-2" style="font-size: 0.7rem;" onclick="this.parentElement.remove()"></button>
                                <div class="row g-3">
                                    <div class="col-md-5">
                                        <label class="small fw-bold text-muted mb-1">Variant Name</label>
                                        <input type="text" name="variant_titles[]" class="form-control form-control-sm" value="{{ $variant->title }}">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="small fw-bold text-muted mb-1">SKU</label>
                                        <input type="text" name="variant_skus[]" class="form-control form-control-sm" value="{{ $variant->sku }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="small fw-bold text-muted mb-1">Price Add-on ($)</label>
                                        <input type="number" step="0.01" name="variant_prices[]" class="form-control form-control-sm" value="{{ $variant->price }}">
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        <button type="button" class="btn btn-outline-primary btn-sm mt-3" onclick="addVariant()">
                            <i class="fas fa-plus me-1"></i> Add Variant Option
                        </button>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <!-- Status & Category -->
                <div class="stat-card mb-4">
                    <h5 class="fw-bold mb-4">Organization</h5>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Status</label>
                        <select name="status" class="form-select">
                            <option value="1" {{ $product->status ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ !$product->status ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Category</label>
                        <select name="category_id" class="form-select" required>
                            <option value="">Select Category</option>
                            @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ $product->category_id == $category->id ? 'selected' : '' }}>{{ $category->title }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Brand (Optional)</label>
                        <select name="brand_id" class="form-select">
                            <option value="">Select Brand</option>
                            @foreach($brands as $brand)
                            <option value="{{ $brand->id }}" {{ $product->brand_id == $brand->id ? 'selected' : '' }}>{{ $brand->title }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-primary btn-lg p-3">Update Product</button>
                </div>
            </div>
        </div>
    </form>
</div>

<template id="variantTemplate">
    <div class="variant-item bg-light p-3 rounded-4 mb-3 position-relative">
        <button type="button" class="btn-close position-absolute top-0 end-0 m-2" style="font-size: 0.7rem;" onclick="this.parentElement.remove()"></button>
        <div class="row g-3">
            <div class="col-md-5">
                <label class="small fw-bold text-muted mb-1">Variant Name</label>
                <input type="text" name="variant_titles[]" class="form-control form-control-sm" placeholder="Title">
            </div>
            <div class="col-md-4">
                <label class="small fw-bold text-muted mb-1">SKU</label>
                <input type="text" name="variant_skus[]" class="form-control form-control-sm" placeholder="SKU">
            </div>
            <div class="col-md-3">
                <label class="small fw-bold text-muted mb-1">Price Add-on ($)</label>
                <input type="number" step="0.01" name="variant_prices[]" class="form-control form-control-sm" placeholder="0.00">
            </div>
        </div>
    </div>
</template>
@endsection

@section('scripts')
<script>
    // Slug Generation
    document.getElementById('title').addEventListener('input', function() {
        const title = this.value;
        const slug = title.toLowerCase()
            .replace(/[^\w ]+/g, '')
            .replace(/ +/g, '-');
        document.getElementById('slug').value = slug;
    });

    // Toggle Variants
    document.getElementById('haveVariants').addEventListener('change', function() {
        if(this.checked) {
            document.getElementById('variantSection').style.display = 'block';
            document.getElementById('singleProductPrice').style.display = 'none';
        } else {
            document.getElementById('variantSection').style.display = 'none';
            document.getElementById('singleProductPrice').style.display = 'block';
        }
    });

    function addVariant() {
        const template = document.getElementById('variantTemplate');
        const clone = template.content.cloneNode(true);
        document.getElementById('variantList').appendChild(clone);
    }
</script>
@endsection
