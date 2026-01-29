@extends('layouts.admin')

@section('title', 'Add Supplier')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="d-flex align-items-center mb-4">
                <a href="{{ route('suppliers.index') }}" class="btn btn-light rounded-circle me-3">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <h2 class="fw-bold mb-0">New Supplier</h2>
            </div>

            <div class="stat-card">
                <form action="{{ route('suppliers.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Supplier Name</label>
                        <input type="text" name="name" class="form-control" placeholder="e.g. Sports Global Ltd" required>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6 text-truncate">
                            <label class="form-label fw-semibold">Email</label>
                            <input type="email" name="email" class="form-control" placeholder="supplier@example.com">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Phone</label>
                            <input type="text" name="phone" class="form-control" placeholder="+123456789">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">Office Address</label>
                        <textarea name="address" class="form-control" rows="3" placeholder="Enter full address..."></textarea>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary btn-lg">Save Supplier</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
