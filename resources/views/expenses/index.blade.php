@extends('layouts.admin')

@section('title', 'Expenses')

@section('content')
<div class="container-fluid">
    <div class="row align-items-center mb-4">
        <div class="col">
            <h2 class="fw-bold mb-0">Business Expenses</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 small">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Expenses</li>
                </ol>
            </nav>
        </div>
        <div class="col-auto">
            <a href="{{ route('expenses.create') }}" class="btn btn-primary rounded-pill px-4 shadow-sm">
                <i class="fas fa-plus me-2"></i> Record Expense
            </a>
        </div>
    </div>

    <div class="table-glass p-3 mb-4 border-0">
        <form action="{{ route('expenses.index') }}" method="GET" class="row g-2 align-items-center">
            <div class="col-md-3">
                <div class="input-group input-group-sm bg-light rounded-pill px-2">
                    <span class="input-group-text bg-transparent border-0"><i class="fas fa-search text-muted"></i></span>
                    <input type="text" name="search" class="form-control bg-transparent border-0" placeholder="Search expenses..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-2">
                <input type="date" name="from_date" class="form-control form-control-sm border-0 bg-light rounded-pill px-3" value="{{ request('from_date') }}">
            </div>
            <div class="col-md-2">
                <input type="date" name="to_date" class="form-control form-control-sm border-0 bg-light rounded-pill px-3" value="{{ request('to_date') }}">
            </div>
            <div class="col-md-3">
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-dark btn-sm rounded-pill flex-fill">Filter</button>
                    <a href="{{ route('expenses.index') }}" class="btn btn-light btn-sm rounded-pill"><i class="fas fa-redo"></i></a>
                </div>
            </div>
            <div class="col-md-2 text-end">
                <div class="fw-bold text-danger">Total: ${{ number_format($expenses->sum('amount'), 2) }}</div>
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
            <table class="table mb-0 align-middle">
                <thead>
                    <tr>
                        <th class="ps-4">Date</th>
                        <th>Title</th>
                        <th>Category</th>
                        <th>Amount</th>
                        <th class="text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($expenses as $expense)
                    <tr>
                        <td class="ps-4 text-muted small">{{ date('M d, Y', strtotime($expense->date)) }}</td>
                        <td><span class="fw-semibold">{{ $expense->title }}</span></td>
                        <td><span class="badge bg-light text-muted fw-normal">{{ $expense->category }}</span></td>
                        <td><span class="fw-bold text-danger">-${{ number_format($expense->amount, 2) }}</span></td>
                        <td class="text-end pe-4">
                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('expenses.edit', $expense) }}" class="btn btn-light btn-sm rounded-pill">
                                    <i class="fas fa-edit text-primary"></i>
                                </a>
                                <form action="{{ route('expenses.destroy', $expense) }}" method="POST" onsubmit="return confirm('Delete this expense record?')">
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
                        <td colspan="5" class="text-center py-5">
                            <i class="fas fa-file-invoice-dollar fa-3x text-light mb-3"></i>
                            <p class="text-muted">No expense records found.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
