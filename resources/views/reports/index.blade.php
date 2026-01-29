@extends('layouts.admin')

@section('title', 'Reports Center')

@section('content')
<div class="container-fluid">
    <div class="mb-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold mb-0">Business Intelligence & Reports</h2>
                <p class="text-muted">Generate and download comprehensive business reports</p>
            </div>
            <div class="stat-card py-2 px-3 border border-primary-soft">
                <div class="row g-2 align-items-center">
                    <div class="col-auto"><span class="small fw-bold text-muted">DATE RANGE:</span></div>
                    <div class="col-auto">
                        <input type="date" id="report_from" class="form-control form-control-sm border-0 bg-light" value="{{ date('Y-m-01') }}">
                    </div>
                    <div class="col-auto"><span class="text-muted">to</span></div>
                    <div class="col-auto">
                        <input type="date" id="report_to" class="form-control form-control-sm border-0 bg-light" value="{{ date('Y-m-d') }}">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Inventory Report -->
        <div class="col-md-6 col-lg-4">
            <div class="stat-card h-100">
                <div class="d-flex align-items-center mb-3">
                    <div class="btn-light rounded-3 p-3 text-primary me-3">
                        <i class="fas fa-warehouse fa-2x"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-0">Inventory Status</h5>
                        <p class="text-muted small mb-0">Full stock levels & valuation</p>
                    </div>
                </div>
                <hr class="opacity-10 my-4">
                <div class="d-flex gap-2">
                    <a href="{{ route('reports.inventory', 'pdf') }}" class="btn btn-primary flex-fill px-4">
                        <i class="fas fa-file-pdf me-2"></i>PDF
                    </a>
                    <a href="{{ route('reports.inventory', 'csv') }}" class="btn btn-outline-primary flex-fill px-4">
                        <i class="fas fa-file-csv me-2"></i>CSV
                    </a>
                </div>
            </div>
        </div>

        <!-- Purchase Orders Report -->
        <div class="col-md-6 col-lg-4">
            <div class="stat-card h-100">
                <div class="d-flex align-items-center mb-3">
                    <div class="btn-light rounded-3 p-3 text-success me-3">
                        <i class="fas fa-truck-loading fa-2x"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-0">Purchase History</h5>
                        <p class="text-muted small mb-0">All stock procurement records</p>
                    </div>
                </div>
                <hr class="opacity-10 my-4">
                <div class="d-flex gap-2">
                    <a href="{{ route('reports.purchases', 'pdf') }}" class="btn btn-success flex-fill px-4">
                        <i class="fas fa-file-pdf me-2"></i>PDF
                    </a>
                    <a href="{{ route('reports.purchases', 'csv') }}" class="btn btn-outline-success flex-fill px-4">
                        <i class="fas fa-file-csv me-2"></i>CSV
                    </a>
                </div>
            </div>
        </div>

        <!-- Sales (coming soon or placeholder) -->
        <div class="col-md-6 col-lg-4">
            <div class="stat-card h-100 opacity-75">
                <div class="d-flex align-items-center mb-3">
                    <div class="btn-light rounded-3 p-3 text-info me-3">
                        <i class="fas fa-chart-line fa-2x"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-0">Sales Performance</h5>
                        <p class="text-muted small mb-0">Revenue & transaction metrics</p>
                    </div>
                </div>
                <hr class="opacity-10 my-4">
                <div class="alert alert-light small py-2 mb-0">
                    <i class="fas fa-info-circle me-1"></i> Under implementation
                </div>
            </div>
        </div>
    </div>

    <div class="mt-5">
        <h4 class="fw-bold mb-4">Product Specific Reports</h4>
        <div class="table-glass">
            <div class="p-4 bg-light border-bottom d-flex justify-content-between align-items-center">
                <span class="fw-bold">Select a product to view detailed transaction report</span>
                <a href="{{ route('products.index') }}" class="btn btn-sm btn-primary">View All Products</a>
            </div>
            <div class="p-4">
                <p class="text-muted mb-0">To generate a detailed history report for a specific product, go to the <strong>Products</strong> module and click the <i class="fas fa-eye text-info"></i> View History icon.</p>
            </div>
        </div>
    </div>
</div>
@section('scripts')
<script>
    document.querySelectorAll('a[href*="/reports/"]').forEach(link => {
        link.addEventListener('click', function(e) {
            const from = document.getElementById('report_from').value;
            const to = document.getElementById('report_to').value;
            const url = new URL(this.href);
            if (from) url.searchParams.set('from_date', from);
            if (to) url.searchParams.set('to_date', to);
            this.href = url.toString();
        });
    });
</script>
@endsection
@endsection
