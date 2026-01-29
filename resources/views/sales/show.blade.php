@extends('layouts.admin')

@section('title', 'Sale Details')

@section('content')
<div class="container-fluid">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div class="d-flex align-items-center">
            <a href="{{ route('sales.index') }}" class="btn btn-light rounded-circle me-3">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div>
                <h2 class="fw-bold mb-0">Order #{{ $order->order_number }}</h2>
                <p class="text-muted mb-0">{{ $order->created_at->format('F d, Y h:i A') }}</p>
            </div>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-light border rounded-pill px-4" onclick="window.print()"><i class="fas fa-print me-2"></i> Print Receipt</button>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="table-glass mb-4">
                <div class="p-4 border-bottom">
                    <h5 class="fw-bold mb-0">Order Items</h5>
                </div>
                <div class="table-responsive">
                    <table class="table mb-0 align-middle">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4">Product</th>
                                <th class="text-center">Qty</th>
                                <th class="text-end">Unit Price</th>
                                <th class="text-end pe-4">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->items as $item)
                            <tr>
                                <td class="ps-4">
                                    <div class="fw-bold text-dark">{{ $item->product->title ?? 'Unknown Product' }}</div>
                                    @if($item->variant)
                                        <div class="small text-muted">Variant: {{ $item->variant->title ?? 'N/A' }}</div>
                                    @endif
                                </td>
                                <td class="text-center">{{ $item->quantity }}</td>
                                <td class="text-end">${{ number_format($item->price, 2) }}</td>
                                <td class="text-end pe-4 fw-bold">${{ number_format($item->price * $item->quantity, 2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="border-top">
                                <td colspan="3" class="text-end py-3"><strong>Total Amount</strong></td>
                                <td class="text-end pe-4 py-3"><h4 class="fw-bold mb-0 text-dark">${{ number_format($order->total_amount, 2) }}</h4></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            @if($order->returns->count() > 0)
            <div class="table-glass border-danger">
                <div class="p-4 border-bottom bg-danger-soft">
                    <h5 class="fw-bold mb-0 text-danger">Return History</h5>
                </div>
                <div class="p-0">
                    @foreach($order->returns as $return)
                    <div class="p-4 border-bottom">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <span class="fw-bold">Return #{{ $return->return_number }}</span>
                                <div class="xsmall text-muted">{{ $return->created_at->format('M d, Y') }}</div>
                            </div>
                            <span class="fw-bold text-danger">Total Refund: ${{ number_format($return->total_return_amount, 2) }}</span>
                        </div>
                        <table class="table table-sm table-borderless mb-0 small">
                            @foreach($return->items as $ri)
                            <tr>
                                <td class="text-muted">{{ $ri->product->title ?? 'Deleted Product' }}</td>
                                <td class="text-center text-muted">x {{ $ri->quantity }}</td>
                                <td class="text-end text-muted">-${{ number_format($ri->refund_price * $ri->quantity, 2) }}</td>
                            </tr>
                            @endforeach
                        </table>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        <div class="col-lg-4">
            <div class="stat-card mb-4">
                <h6 class="text-muted small fw-bold mb-3 text-uppercase">Customer Information</h6>
                @if($order->customer)
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-light rounded-circle p-3 me-3"><i class="fas fa-user text-muted"></i></div>
                        <div>
                            <div class="fw-bold text-dark">{{ $order->customer->name }}</div>
                            <div class="text-muted small">{{ $order->customer->phone }}</div>
                        </div>
                    </div>
                    <div class="small text-muted mb-2"><i class="fas fa-envelope me-2"></i> {{ $order->customer->email }}</div>
                    <div class="small text-muted"><i class="fas fa-map-marker-alt me-2"></i> {{ $order->customer->address }}</div>
                @else
                    <div class="text-center py-3">
                        <i class="fas fa-walking fa-2x text-light mb-2"></i>
                        <p class="text-muted mb-0">Walk-in Customer</p>
                    </div>
                @endif
            </div>

            <div class="stat-card">
                <h6 class="text-muted small fw-bold mb-3 text-uppercase">Payment Summary</h6>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Status</span>
                    <span class="badge bg-success-soft text-success">Paid</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Total Order</span>
                    <span class="fw-bold text-dark">${{ number_format($order->total_amount, 2) }}</span>
                </div>
                @php $refunded = $order->returns->sum('total_return_amount'); @endphp
                @if($refunded > 0)
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Total Refunded</span>
                    <span class="fw-bold text-danger">-${{ number_format($refunded, 2) }}</span>
                </div>
                <div class="d-flex justify-content-between mt-3 pt-3 border-top">
                    <span class="fw-bold text-dark">Net Amount</span>
                    <span class="fw-bold text-primary h5 mb-0">${{ number_format($order->total_amount - $refunded, 2) }}</span>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
    @media print {
        .main-content { margin: 0; padding: 0; }
        .sidebar, .top-navbar, .btn-light, .btn-arrow-left, .btn-light.rounded-circle { display: none !important; }
        .container-fluid { width: 100%; }
        .stat-card, .table-glass { border: 1px solid #eee !important; box-shadow: none !important; }
    }
</style>
@endsection
