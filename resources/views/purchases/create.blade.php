@extends('layouts.admin')

@section('title', 'New Purchase')

@section('content')
<div class="container-fluid">
    <div class="d-flex align-items-center mb-4">
        <a href="{{ route('purchases.index') }}" class="btn btn-light rounded-circle me-3">
            <i class="fas fa-arrow-left"></i>
        </a>
        <h2 class="fw-bold mb-0">Record Stock Purchase</h2>
    </div>

    <form action="{{ route('purchases.store') }}" method="POST">
        @csrf
        <div class="row">
            <div class="col-lg-12">
                <div class="stat-card mb-4">
                    <div class="row g-4">
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Select Supplier</label>
                            <select name="supplier_id" class="form-select" required>
                                <option value="">Choose supplier...</option>
                                @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Receiving Store</label>
                            <select name="store_id" class="form-select" required>
                                @foreach($stores as $store)
                                <option value="{{ $store->id }}">{{ $store->title }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="stat-card">
                    <h5 class="fw-bold mb-4">Inventory Items</h5>
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead class="text-muted small fw-bold">
                                <tr>
                                    <th style="width: 40%">PRODUCT & VARIANT</th>
                                    <th>STK QTY</th>
                                    <th>UNIT COST ($)</th>
                                    <th>SUBTOTAL</th>
                                    <th style="width: 50px"></th>
                                </tr>
                            </thead>
                            <tbody id="purchaseTableBody">
                                <!-- Items will be added here -->
                            </tbody>
                        </table>
                    </div>
                    <button type="button" class="btn btn-outline-primary btn-sm mt-3" onclick="addRow()">
                        <i class="fas fa-plus me-1"></i> Add Item
                    </button>
                    
                    <hr class="my-4 opacity-10">
                    <div class="row justify-content-end">
                        <div class="col-md-4 text-end">
                            <h4 class="fw-bold">Total: <span id="grandTotal">$0.00</span></h4>
                        </div>
                    </div>

                    <div class="d-grid mt-4">
                        <button type="submit" class="btn btn-primary btn-lg">Complete Purchase & Update Stock</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<template id="rowTemplate">
    <tr>
        <td>
            <select name="product_id[]" class="form-select product-select" required onchange="updateRowVariants(this)">
                <option value="">Select Product...</option>
                @foreach($products as $product)
                <option value="{{ $product->id }}" data-has-variants="{{ $product->have_variants ? '1' : '0' }}">{{ $product->title }}</option>
                @endforeach
            </select>
            <div class="variant-select-wrapper mt-2" style="display: none;">
                <select name="variant_id[]" class="form-select form-select-sm variant-select">
                    <!-- Dynamic variants -->
                </select>
            </div>
        </td>
        <td><input type="number" name="quantity[]" class="form-control qty-input" min="1" value="1" required oninput="calculateRow(this)"></td>
        <td><input type="number" step="0.01" name="cost[]" class="form-control cost-input" placeholder="0.00" required oninput="calculateRow(this)"></td>
        <td><span class="fw-bold row-total">$0.00</span></td>
        <td><button type="button" class="btn btn-light btn-sm text-danger" onclick="this.closest('tr').remove(); updateGrandTotal();"><i class="fas fa-times"></i></button></td>
    </tr>
</template>
@endsection

@section('scripts')
<script>
    const products = @json($products);

    function addRow() {
        const template = document.getElementById('rowTemplate');
        const clone = template.content.cloneNode(true);
        document.getElementById('purchaseTableBody').appendChild(clone);
    }

    function updateRowVariants(select) {
        const row = select.closest('tr');
        const productId = select.value;
        const product = products.find(p => p.id == productId);
        const variantWrapper = row.querySelector('.variant-select-wrapper');
        const variantSelect = row.querySelector('.variant-select');

        if (product && product.have_variants && product.variants.length > 0) {
            variantWrapper.style.display = 'block';
            variantSelect.innerHTML = '<option value="">Standard Variant</option>';
            product.variants.forEach(v => {
                variantSelect.innerHTML += `<option value="${v.id}">${v.title}</option>`;
            });
            variantSelect.required = true;
        } else {
            variantWrapper.style.display = 'none';
            variantSelect.innerHTML = '';
            variantSelect.required = false;
        }
    }

    function calculateRow(input) {
        const row = input.closest('tr');
        const qty = row.querySelector('.qty-input').value || 0;
        const cost = row.querySelector('.cost-input').value || 0;
        const total = qty * cost;
        row.querySelector('.row-total').innerText = '$' + total.toFixed(2);
        updateGrandTotal();
    }

    function updateGrandTotal() {
        let grand = 0;
        document.querySelectorAll('.row-total').forEach(el => {
            grand += parseFloat(el.innerText.replace('$', ''));
        });
        document.getElementById('grandTotal').innerText = '$' + grand.toFixed(2);
    }

    // Add first row on load
    document.addEventListener('DOMContentLoaded', addRow);
</script>
@endsection
