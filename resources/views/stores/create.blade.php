@extends('layouts.admin')

@section('title', 'Add Store')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="d-flex align-items-center mb-4">
                <a href="{{ route('stores.index') }}" class="btn btn-light rounded-circle me-3">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <h2 class="fw-bold mb-0">New Store</h2>
            </div>

            <div class="stat-card">
                <form action="{{ route('stores.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Store Title</label>
                        <input type="text" name="title" class="form-control" placeholder="e.g. Downtown Branch" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Location</label>
                        <input type="text" name="location" class="form-control" placeholder="e.g. 123 Main St, NY">
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">Contact Phone</label>
                        <input type="text" name="phone" class="form-control" placeholder="+1 234 567 890">
                    </div>

                    <div class="d-grid mt-5">
                        <button type="submit" class="btn btn-primary btn-lg">Create Store</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
