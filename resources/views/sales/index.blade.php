@extends('layouts.admin')

@section('title', 'POS Sales History')

@section('content')
<div class="container-fluid">
    <div class="row align-items-center mb-4">
        <div class="col">
            <h2 class="fw-bold mb-0">POS Transactions</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 small">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Sales</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="table-glass p-3 mb-4 border-0">
        <form action="{{ route('sales.index') }}" method="GET" class="row g-2">
            <div class="col-md-4">
                <div class="input-group input-group-sm bg-light rounded-pill px-2">
                    <span class="input-group-text bg-transparent border-0"><i class="fas fa-search text-muted"></i></span>
                    <input type="text" name="search" class="form-control bg-transparent border-0" placeholder="Search by Order # or Customer..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-3">
                <input type="date" name="from_date" class="form-control form-control-sm border-0 bg-light rounded-pill px-3" value="{{ request('from_date', date('Y-m-d')) }}">
            </div>
            <div class="col-md-3">
                <input type="date" name="to_date" class="form-control form-control-sm border-0 bg-light rounded-pill px-3" value="{{ request('to_date', date('Y-m-d')) }}">
            </div>
            <div class="col-md-2">
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-dark btn-sm rounded-pill flex-fill">Filter</button>
                    <a href="{{ route('sales.index') }}" class="btn btn-light btn-sm rounded-pill"><i class="fas fa-redo"></i></a>
                </div>
            </div>
        </form>
    </div>

    <div class="table-glass">
        <div class="table-responsive">
            <table class="table mb-0 align-middle text-nowrap">
                <thead>
                    <tr>
                        <th class="ps-4">Order #</th>
                        <th>Date & Time</th>
                        <th>Customer</th>
                        <th>Items</th>
                        <th>Total Amount</th>
                        <th>Status</th>
                        <th class="text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sales as $sale)
                    <tr>
                        <td class="ps-4"><span class="fw-bold text-dark">{{ $sale->order_number }}</span></td>
                        <td>
                            <div class="small fw-medium">{{ $sale->created_at->format('M d, Y') }}</div>
                            <div class="xsmall text-muted">{{ $sale->created_at->format('h:i A') }}</div>
                        </td>
                        <td>
                            @if($sale->customer)
                                <div class="fw-semibold">{{ $sale->customer->name }}</div>
                                <div class="text-muted small">{{ $sale->customer->phone }}</div>
                            @else
                                <span class="badge bg-light text-muted">Walk-in Customer</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-light text-dark fw-normal border">{{ $sale->items->count() }} Items</span>
                        </td>
                        <td>
                            <div class="fw-bold text-dark">${{ number_format($sale->total_amount, 2) }}</div>
                            @if($sale->returns->count() > 0)
                                <div class="text-danger xsmall">Refunded: ${{ number_format($sale->returns->sum('total_return_amount'), 2) }}</div>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-success-soft text-success">Completed</span>
                        </td>
                        <td class="text-end pe-4">
                            <a href="{{ route('sales.show', $sale) }}" class="btn btn-light btn-sm rounded-pill">
                                <i class="fas fa-eye text-primary"></i> Details
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">No sales found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-top">
            {{ $sales->links() }}
        </div>
    </div>
</div>
@endsection
