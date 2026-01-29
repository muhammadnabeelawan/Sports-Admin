@extends('layouts.admin')

@section('title', 'Suppliers')

@section('content')
<div class="container-fluid">
    <div class="row align-items-center mb-4">
        <div class="col">
            <h2 class="fw-bold mb-0">Suppliers</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 small">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Suppliers</li>
                </ol>
            </nav>
        </div>
        <div class="col-auto">
            <a href="{{ route('suppliers.create') }}" class="btn btn-primary rounded-pill px-4 shadow-sm">
                <i class="fas fa-plus me-2"></i> Add New Supplier
            </a>
        </div>
    </div>

    <div class="table-glass p-3 mb-4 border-0">
        <form action="{{ route('suppliers.index') }}" method="GET" class="row g-2 align-items-center">
            <div class="col-md-9">
                <div class="input-group input-group-sm bg-light rounded-pill px-2">
                    <span class="input-group-text bg-transparent border-0"><i class="fas fa-search text-muted"></i></span>
                    <input type="text" name="search" class="form-control bg-transparent border-0" placeholder="Search suppliers by name, email or phone..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-3">
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-dark btn-sm rounded-pill flex-fill">Search</button>
                    <a href="{{ route('suppliers.index') }}" class="btn btn-light btn-sm rounded-pill"><i class="fas fa-redo"></i></a>
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
                        <th class="ps-4">Supplier Name</th>
                        <th>Contact Info</th>
                        <th>Address</th>
                        <th class="text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($suppliers as $supplier)
                    <tr>
                        <td class="ps-4">
                            <div class="fw-semibold">{{ $supplier->name }}</div>
                            <div class="text-muted small">ID: #SUP-{{ $supplier->id }}</div>
                        </td>
                        <td>
                            <div class="small"><i class="fas fa-envelope me-2 text-muted"></i>{{ $supplier->email ?? 'N/A' }}</div>
                            <div class="small"><i class="fas fa-phone me-2 text-muted"></i>{{ $supplier->phone ?? 'N/A' }}</div>
                        </td>
                        <td><span class="text-muted small">{{ Str::limit($supplier->address, 50) }}</span></td>
                        <td class="text-end pe-4">
                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('suppliers.edit', $supplier) }}" class="btn btn-light btn-sm rounded-pill">
                                    <i class="fas fa-edit text-primary"></i>
                                </a>
                                <form action="{{ route('suppliers.destroy', $supplier) }}" method="POST" onsubmit="return confirm('Delete this supplier?')">
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
                        <td colspan="4" class="text-center py-5">
                            <i class="fas fa-truck-pickup fa-3x text-light mb-3"></i>
                            <p class="text-muted">No suppliers found yet.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
