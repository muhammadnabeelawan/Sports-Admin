@extends('layouts.admin')

@section('title', 'Purchase Order Details')

@section('content')
<div class="container-fluid">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div class="d-flex align-items-center">
            <a href="{{ route('purchases.index') }}" class="btn btn-light rounded-circle me-3">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div>
                <h2 class="fw-bold mb-0">PO #{{ $purchase->order_number }}</h2>
                <p class="text-muted mb-0">Received on: {{ $purchase->received_date }}</p>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="table-glass mb-4">
                <div class="p-4 border-bottom">
                    <h5 class="fw-bold mb-0">Purchased Items</h5>
                </div>
                <div class="table-responsive">
                    <table class="table mb-0 align-middle">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4">Product</th>
                                <th class="text-center">Qty</th>
                                <th class="text-end">Unit Cost</th>
                                <th class="text-end pe-4">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($purchase->items as $item)
                            <tr>
                                <td class="ps-4">
                                    <div class="fw-bold text-dark">{{ $item->product->title ?? 'Unknown' }}</div>
                                    @if($item->variant)
                                        <div class="small text-muted">Variant: {{ $item->variant->title }}</div>
                                    @endif
                                </td>
                                <td class="text-center">{{ $item->quantity }}</td>
                                <td class="text-end">${{ number_format($item->cost, 2) }}</td>
                                <td class="text-end pe-4 fw-bold">${{ number_format($item->cost * $item->quantity, 2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="border-top">
                                <td colspan="3" class="text-end py-3"><strong>Total Investment</strong></td>
                                <td class="text-end pe-4 py-3"><h4 class="fw-bold mb-0 text-dark">${{ number_format($purchase->total_amount, 2) }}</h4></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="stat-card mb-4">
                <h6 class="text-muted small fw-bold mb-3 text-uppercase text-spacing-1">Supplier Information</h6>
                <div class="d-flex align-items-center mb-3">
                    <div class="bg-primary-soft rounded-circle p-3 me-3"><i class="fas fa-truck text-accent"></i></div>
                    <div>
                        <div class="fw-bold text-dark">{{ $purchase->supplier->name }}</div>
                        <div class="text-muted small">{{ $purchase->supplier->phone }}</div>
                    </div>
                </div>
                <div class="small text-muted mb-2"><i class="fas fa-envelope me-2"></i> {{ $purchase->supplier->email }}</div>
                <div class="small text-muted"><i class="fas fa-map-marker-alt me-2"></i> {{ $purchase->supplier->address }}</div>
            </div>

            <div class="stat-card">
                <h6 class="text-muted small fw-bold mb-3 text-uppercase text-spacing-1">Order Status</h6>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Status</span>
                    <span class="badge bg-success-soft text-success px-3">RECEIVED</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Total Amount</span>
                    <span class="fw-bold text-dark">${{ number_format($purchase->total_amount, 2) }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
