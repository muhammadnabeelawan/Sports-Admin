@extends('layouts.admin')

@section('title', 'Stores & Branches')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-0">Stores & Branches</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Stores</li>
                </ol>
            </nav>
        </div>
        <a href="{{ route('stores.create') }}" class="btn btn-primary d-flex align-items-center">
            <i class="fas fa-plus me-2"></i> Add New Store
        </a>
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
                        <th class="ps-4">Store Title</th>
                        <th>Location</th>
                        <th>Phone</th>
                        <th class="text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($stores as $store)
                    <tr>
                        <td class="ps-4">
                            <div class="fw-semibold">{{ $store->title }}</div>
                            <div class="text-muted small">ID: #ST-{{ $store->id }}</div>
                        </td>
                        <td><span class="text-muted">{{ $store->location ?? 'N/A' }}</span></td>
                        <td><span class="fw-medium">{{ $store->phone ?? 'N/A' }}</span></td>
                        <td class="text-end pe-4">
                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('stores.edit', $store) }}" class="btn btn-light btn-sm rounded-pill">
                                    <i class="fas fa-edit text-primary"></i>
                                </a>
                                <form action="{{ route('stores.destroy', $store) }}" method="POST" onsubmit="return confirm('Delete this store?')">
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
                            <i class="fas fa-store-slash fa-3x text-light mb-3"></i>
                            <p class="text-muted">No stores found yet.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
