@extends('layouts.admin')

@section('title', 'Edit Expense')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="d-flex align-items-center mb-4">
                <a href="{{ route('expenses.index') }}" class="btn btn-light rounded-circle me-3">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <h2 class="fw-bold mb-0">Edit Expense</h2>
            </div>

            <div class="stat-card">
                <form action="{{ route('expenses.update', $expense) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Expense Title</label>
                        <input type="text" name="title" class="form-control" value="{{ $expense->title }}" required>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Amount ($)</label>
                            <input type="number" step="0.01" name="amount" class="form-control" value="{{ $expense->amount }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Category</label>
                            <select name="category" class="form-select" required>
                                <option value="Utilities" {{ $expense->category == 'Utilities' ? 'selected' : '' }}>Utilities</option>
                                <option value="Rent" {{ $expense->category == 'Rent' ? 'selected' : '' }}>Rent</option>
                                <option value="Salaries" {{ $expense->category == 'Salaries' ? 'selected' : '' }}>Salaries</option>
                                <option value="Marketing" {{ $expense->category == 'Marketing' ? 'selected' : '' }}>Marketing</option>
                                <option value="Maintenance" {{ $expense->category == 'Maintenance' ? 'selected' : '' }}>Maintenance</option>
                                <option value="Other" {{ $expense->category == 'Other' ? 'selected' : '' }}>Other</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Date</label>
                        <input type="date" name="date" class="form-control" value="{{ $expense->date }}" required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">Description (Optional)</label>
                        <textarea name="description" class="form-control" rows="3">{{ $expense->description }}</textarea>
                    </div>

                    <div class="d-grid mt-5">
                        <button type="submit" class="btn btn-primary btn-lg">Update Expense</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
