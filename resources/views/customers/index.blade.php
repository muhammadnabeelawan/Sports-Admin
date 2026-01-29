@extends('layouts.admin')

@section('title', 'Customers')

@section('content')
<div class="container-fluid">
    <div class="row align-items-center mb-4">
        <div class="col">
            <h2 class="fw-bold mb-0">Customer Database</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 small">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Customers</li>
                </ol>
            </nav>
        </div>
        <div class="col-auto">
            <a href="{{ route('customers.create') }}" class="btn btn-primary rounded-pill px-4 shadow-sm">
                <i class="fas fa-plus me-2"></i> Add Customer
            </a>
        </div>
    </div>

    <div class="table-glass p-3 mb-4 border-0">
        <form action="{{ route('customers.index') }}" method="GET" class="row g-2 align-items-center">
            <div class="col-md-9">
                <div class="input-group input-group-sm bg-light rounded-pill px-2">
                    <span class="input-group-text bg-transparent border-0"><i class="fas fa-search text-muted"></i></span>
                    <input type="text" name="search" class="form-control bg-transparent border-0" placeholder="Search by name, phone or email..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-3">
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-dark btn-sm rounded-pill flex-fill">Search</button>
                    <a href="{{ route('customers.index') }}" class="btn btn-light btn-sm rounded-pill"><i class="fas fa-redo"></i></a>
                </div>
            </div>
        </form>
    </div>

    <div class="table-glass">
        <div class="table-responsive">
            <table class="table mb-0 align-middle">
                <thead>
                    <tr>
                        <th class="ps-4">Name</th>
                        <th>Contact</th>
                        <th>Address</th>
                        <th class="text-center">Total Orders</th>
                        <th class="text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($customers as $customer)
                    <tr>
                        <td class="ps-4">
                            <div class="fw-bold">{{ $customer->name }}</div>
                            <small class="text-muted">Joined: {{ $customer->created_at->format('M Y') }}</small>
                        </td>
                        <td>
                            <div><i class="fas fa-phone me-2 text-muted small"></i>{{ $customer->phone }}</div>
                            @if($customer->email)
                            <small class="text-muted"><i class="fas fa-envelope me-2 small"></i>{{ $customer->email }}</small>
                            @endif
                        </td>
                        <td><span class="text-muted small">{{ Str::limit($customer->address, 50) ?: 'N/A' }}</span></td>
                        <td class="text-center">
                            @php $orderCount = \App\Models\Order::where('customer_phone', $customer->phone)->count(); @endphp
                            <span class="badge bg-primary-soft text-primary rounded-pill px-3">{{ $orderCount }} Orders</span>
                        </td>
                        <td class="text-end pe-4">
                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('customers.show', $customer) }}" class="btn btn-light btn-sm rounded-pill" title="Purchase History">
                                    <i class="fas fa-history text-info"></i>
                                </a>
                                <a href="{{ route('customers.edit', $customer) }}" class="btn btn-light btn-sm rounded-pill">
                                    <i class="fas fa-edit text-primary"></i>
                                </a>
                                <form action="{{ route('customers.destroy', $customer) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this customer?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-light btn-sm rounded-pill"><i class="fas fa-trash text-danger"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
