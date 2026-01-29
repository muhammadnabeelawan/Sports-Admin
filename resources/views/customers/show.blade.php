@extends('layouts.admin')

@section('title', 'Customer History')

@section('content')
<div class="container-fluid">
    <div class="d-flex align-items-center mb-4">
        <a href="{{ route('customers.index') }}" class="btn btn-light rounded-circle me-3">
            <i class="fas fa-arrow-left"></i>
        </a>
        <h2 class="fw-bold mb-0">{{ $customer->name }} - Profile</h2>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="stat-card mb-4 text-center">
                <img src="https://ui-avatars.com/api/?name={{ urlencode($customer->name) }}&background=eff6ff&color=3b82f6&size=128" class="rounded-circle mb-3 shadow-sm">
                <h4 class="fw-bold mb-1">{{ $customer->name }}</h4>
                <p class="text-muted">{{ $customer->phone }}</p>
                <hr class="opacity-10 my-4">
                <div class="text-start">
                    <div class="mb-3">
                        <label class="text-muted small fw-bold text-uppercase">Email Address</label>
                        <div>{{ $customer->email ?: 'N/A' }}</div>
                    </div>
                    <div class="mb-3">
                        <label class="text-muted small fw-bold text-uppercase">Full Address</label>
                        <div>{{ $customer->address ?: 'N/A' }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="row g-3 mb-4">
                <div class="col-6">
                    <div class="stat-card bg-primary text-white text-center py-4">
                        <h2 class="fw-bold mb-0">{{ $orders->count() }}</h2>
                        <p class="mb-0 opacity-75">Total Transactions</p>
                    </div>
                </div>
                <div class="col-6">
                    <div class="stat-card bg-success text-white text-center py-4">
                        <h2 class="fw-bold mb-0">${{ number_format($orders->sum('total_amount'), 2) }}</h2>
                        <p class="mb-0 opacity-75">Lifetime Spending</p>
                    </div>
                </div>
            </div>

            <div class="table-glass py-4">
                <div class="px-4 mb-4">
                    <h5 class="fw-bold mb-0">Transaction History</h5>
                </div>
                <div class="table-responsive">
                    <table class="table mb-0 align-middle">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4">Order ID</th>
                                <th>Date</th>
                                <th class="text-center">Items</th>
                                <th class="text-end pe-4">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($orders as $order)
                            <tr>
                                <td class="ps-4 fw-bold text-primary">{{ $order->order_number }}</td>
                                <td>{{ $order->created_at->format('M d, Y') }}</td>
                                <td class="text-center">{{ $order->items_count ?? 0 }} items</td>
                                <td class="text-end pe-4 fw-bold">${{ number_format($order->total_amount, 2) }}</td>
                            </tr>
                            @empty
                            <tr><td colspan="4" class="text-center py-5 text-muted">No orders found for this customer.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
