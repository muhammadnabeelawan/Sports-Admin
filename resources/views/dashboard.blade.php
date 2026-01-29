@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-3 mb-4">
            <div class="stat-card">
                <div class="stat-icon bg-primary-soft text-primary"><i class="fas fa-box"></i></div>
                <h3 class="fw-bold mb-1">{{ $stats['products_count'] }}</h3>
                <p class="text-muted mb-0">Total Products</p>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="stat-card">
                <div class="stat-icon bg-info-soft text-info"><i class="fas fa-shopping-cart"></i></div>
                <h3 class="fw-bold mb-1">{{ $stats['orders_count'] ?? 0 }}</h3>
                <p class="text-muted mb-0">Total Orders</p>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="stat-card">
                <div class="stat-icon bg-warning-soft text-warning"><i class="fas fa-warehouse"></i></div>
                <h3 class="fw-bold mb-1">{{ $stats['total_stock'] }}</h3>
                <p class="text-muted mb-0">Items in Stock</p>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="stat-card">
                <div class="stat-icon bg-success-soft text-success"><i class="fas fa-dollar-sign"></i></div>
                <h3 class="fw-bold mb-1">${{ number_format($stats['total_revenue'], 2) }}</h3>
                <p class="text-muted mb-0">Total Revenue</p>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row">
        <div class="col-lg-8 mb-4">
            <div class="stat-card h-100">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="fw-bold mb-0">Sales Analytics</h5>
                </div>
                <div style="height: 350px;">
                    <canvas id="salesChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-4 mb-4">
            <div class="stat-card h-100">
                <h5 class="fw-bold mb-4">Top Categories</h5>
                <div class="list-group list-group-flush">
                    @forelse($topCategories as $cat)
                    <div class="list-group-item px-0 border-0 bg-transparent mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="fw-bold text-accent">{{ $cat->title }}</span>
                            <span class="badge bg-primary-soft text-primary">{{ $cat->total_qty }} Sold</span>
                        </div>
                        <div class="progress" style="height: 6px;">
                            @php $maxQty = $topCategories->max('total_qty') ?: 1; @endphp
                            <div class="progress-bar" style="width: {{ ($cat->total_qty / $maxQty) * 100 }}%"></div>
                        </div>
                    </div>
                    @empty
                    <p class="text-muted text-center py-4">No data yet.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-7 mb-4">
            <div class="table-glass py-4">
                <div class="px-4 mb-4 d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0">Top Selling Products</h5>
                    <a href="{{ route('reports.index') }}" class="btn btn-sm btn-light rounded-pill px-3">View More</a>
                </div>
                <div class="table-responsive">
                    <table class="table mb-0 align-middle">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4">Product</th>
                                <th class="text-center">Sold</th>
                                <th class="text-end pe-4">Revenue</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($topProducts as $tp)
                            <tr>
                                <td class="ps-4">
                                    <div class="fw-bold">{{ $tp->product->title }}</div>
                                    <div class="text-muted small">{{ $tp->product->code }}</div>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-success-soft text-success rounded-pill px-3">{{ $tp->total_qty }} items</span>
                                </td>
                                <td class="text-end pe-4 fw-bold">${{ number_format($tp->total_qty * $tp->product->price, 2) }}</td>
                            </tr>
                            @empty
                            <tr><td colspan="3" class="text-center py-4 text-muted">No sales data.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-lg-5 mb-4">
            <div class="stat-card h-100">
                <h5 class="fw-bold mb-4">Live Stock Alerts</h5>
                <div class="list-group list-group-flush">
                    @php $lowStock = \App\Models\Stock::with(['product', 'store'])->where('quantity', '<', 10)->take(5)->get(); @endphp
                    @forelse($lowStock as $ls)
                    <div class="list-group-item px-0 border-0 mb-3 bg-transparent">
                        <div class="d-flex align-items-center">
                            <div class="bg-danger-soft text-danger p-3 rounded-4 me-3">
                                <i class="fas fa-exclamation-triangle"></i>
                            </div>
                            <div class="flex-fill">
                                <div class="fw-bold">{{ $ls->product->title }}</div>
                                <div class="text-muted small">{{ $ls->store->name }} &bull; <span class="text-danger fw-bold">{{ $ls->quantity }} left</span></div>
                            </div>
                            <a href="{{ route('stocks.index') }}" class="btn btn-sm btn-light rounded-pill">Manage</a>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-5">
                        <i class="fas fa-check-circle fa-3x text-success mb-3 opacity-25"></i>
                        <p class="text-muted italic">All stock levels are healthy.</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('salesChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: @json($chartData['labels']),
                datasets: [{
                    label: 'Revenue ($)',
                    data: @json($chartData['revenue']),
                    borderColor: '#3b82f6',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointRadius: 5,
                    pointHoverRadius: 8,
                    pointBackgroundColor: '#fff',
                    pointBorderColor: '#3b82f6',
                    pointBorderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                        padding: 12,
                        backgroundColor: 'rgba(255, 255, 255, 0.9)',
                        titleColor: '#1e293b',
                        bodyColor: '#64748b',
                        borderColor: '#e2e8f0',
                        borderWidth: 1,
                        displayColors: false,
                        callbacks: {
                            label: function(context) {
                                return '$ ' + context.parsed.y.toLocaleString();
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { drawBorder: false, color: 'rgba(0, 0, 0, 0.05)' },
                        ticks: { color: '#94a3b8' }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { color: '#94a3b8' }
                    }
                }
            }
        });
    });
</script>
@endsection
