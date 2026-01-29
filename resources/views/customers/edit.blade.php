@extends('layouts.admin')

@section('title', 'Manage Customer')

@section('content')
<div class="container-fluid">
    <div class="d-flex align-items-center mb-4">
        <a href="{{ route('customers.index') }}" class="btn btn-light rounded-circle me-3">
            <i class="fas fa-arrow-left"></i>
        </a>
        <h2 class="fw-bold mb-0">{{ isset($customer) ? 'Edit' : 'Add' }} Customer</h2>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="stat-card">
                <form action="{{ isset($customer) ? route('customers.update', $customer) : route('customers.store') }}" method="POST">
                    @csrf
                    @if(isset($customer)) @method('PUT') @endif

                    <div class="mb-3">
                        <label class="form-label fw-bold">Full Name</label>
                        <input type="text" name="name" class="form-control" value="{{ $customer->name ?? old('name') }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Phone Number</label>
                        <input type="text" name="phone" class="form-control" value="{{ $customer->phone ?? old('phone') }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Email (Optional)</label>
                        <input type="email" name="email" class="form-control" value="{{ $customer->email ?? old('email') }}">
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">Address</label>
                        <textarea name="address" class="form-control" rows="3">{{ $customer->address ?? old('address') }}</textarea>
                    </div>

                    <button type="submit" class="btn btn-primary rounded-pill w-100 py-3 fw-bold">
                        <i class="fas fa-save me-2"></i> {{ isset($customer) ? 'Update' : 'Register' }} Customer
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
