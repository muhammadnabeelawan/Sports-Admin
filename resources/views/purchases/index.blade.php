@extends('layouts.admin')

@section('title', 'Purchase Orders')

@section('content')
<div class="container-fluid">
    <div class="row align-items-center mb-4">
        <div class="col">
            <h2 class="fw-bold mb-0">Purchase History</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 small">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Purchases</li>
                </ol>
            </nav>
        </div>
        <div class="col-auto">
            <a href="{{ route('purchases.create') }}" class="btn btn-primary rounded-pill px-4 shadow-sm">
                <i class="fas fa-plus me-2"></i> New Purchase Order
            </a>
        </div>
    </div>

    <div class="table-glass p-3 mb-4 border-0">
        <form action="{{ route('purchases.index') }}" method="GET" class="row g-2">
            <div class="col-md-5">
                <div class="input-group input-group-sm bg-light rounded-pill px-2">
                    <span class="input-group-text bg-transparent border-0"><i class="fas fa-search text-muted"></i></span>
                    <input type="text" name="search" class="form-control bg-transparent border-0" placeholder="Search PO number..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-4">
                <select name="supplier_id" class="form-select form-select-sm border-0 bg-light rounded-pill px-3">
                    <option value="">All Suppliers</option>
                    @foreach($suppliers as $sup)
                    <option value="{{ $sup->id }}" {{ request('supplier_id') == $sup->id ? 'selected' : '' }}>{{ $sup->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-dark btn-sm rounded-pill flex-fill">Filter</button>
                    <a href="{{ route('purchases.index') }}" class="btn btn-light btn-sm rounded-pill"><i class="fas fa-redo"></i></a>
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
                        <th class="ps-4">PO Number</th>
                        <th>Supplier</th>
                        <th>Amount</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th class="text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($purchases as $order)
                    <tr>
                        <td class="ps-4">
                            <span class="fw-bold text-accent">{{ $order->order_number }}</span>
                        </td>
                        <td>
                            <div class="fw-semibold">{{ $order->supplier->name }}</div>
                            <div class="text-muted small">{{ $order->supplier->email }}</div>
                        </td>
                        <td><span class="fw-bold">${{ number_format($order->total_amount, 2) }}</span></td>
                        <td><span class="text-muted small">{{ $order->received_date }}</span></td>
                        <td><span class="badge bg-success-soft text-success">Received</span></td>
                        <td class="text-end pe-4">
                            <a href="{{ route('purchases.show', $order) }}" class="btn btn-light btn-sm rounded-pill"><i class="fas fa-eye text-primary"></i> View</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5">
                            <i class="fas fa-file-invoice fa-3x text-light mb-3"></i>
                            <p class="text-muted">No purchase records found.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
