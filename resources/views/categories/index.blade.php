@extends('layouts.admin')

@section('title', 'Categories')

@section('content')
<div class="container-fluid">
    <div class="row align-items-center mb-4">
        <div class="col">
            <h2 class="fw-bold mb-0">Categories</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 small">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Categories</li>
                </ol>
            </nav>
        </div>
        <div class="col-auto">
            <a href="{{ route('categories.create') }}" class="btn btn-primary rounded-pill px-4 shadow-sm">
                <i class="fas fa-plus me-2"></i> Add New Category
            </a>
        </div>
    </div>

    <div class="table-glass p-3 mb-4 border-0">
        <form action="{{ route('categories.index') }}" method="GET" class="row g-2 align-items-center">
            <div class="col-md-9">
                <div class="input-group input-group-sm bg-light rounded-pill px-2">
                    <span class="input-group-text bg-transparent border-0"><i class="fas fa-search text-muted"></i></span>
                    <input type="text" name="search" class="form-control bg-transparent border-0" placeholder="Search categories..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-3">
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-dark btn-sm rounded-pill flex-fill">Search</button>
                    <a href="{{ route('categories.index') }}" class="btn btn-light btn-sm rounded-pill"><i class="fas fa-redo"></i></a>
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
                        <th class="ps-4">#</th>
                        <th>Icon/Image</th>
                        <th>Title</th>
                        <th>Slug</th>
                        <th>Products Count</th>
                        <th class="text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $category)
                    <tr>
                        <td class="ps-4 text-muted">{{ $loop->iteration }}</td>
                        <td>
                            @if($category->image)
                            <img src="{{ $category->image }}" class="rounded-3" width="40" height="40" style="object-fit: cover;">
                            @else
                            <div class="bg-light rounded-3 d-flex align-items-center justify-content-center" width="40" height="40">
                                <i class="fas fa-tag text-muted"></i>
                            </div>
                            @endif
                        </td>
                        <td><span class="fw-semibold">{{ $category->title }}</span></td>
                        <td><span class="badge bg-light text-muted fw-normal">{{ $category->slug }}</span></td>
                        <td>{{ $category->products_count ?? 0 }} items</td>
                        <td class="text-end pe-4">
                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('categories.edit', $category) }}" class="btn btn-light btn-sm rounded-pill">
                                    <i class="fas fa-edit text-primary"></i>
                                </a>
                                <form action="{{ route('categories.destroy', $category) }}" method="POST" onsubmit="return confirm('Delete this category?')">
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
                        <td colspan="6" class="text-center py-5">
                            <i class="fas fa-folder-open fa-3x text-light mb-3"></i>
                            <p class="text-muted">No categories found yet.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
