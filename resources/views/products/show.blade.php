@extends('layouts.admin')

@section('title', 'Product History')

@section('content')
<div class="container-fluid">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div class="d-flex align-items-center">
            <a href="{{ route('products.index') }}" class="btn btn-light rounded-circle me-3">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div>
                <h2 class="fw-bold mb-0">{{ $product->title }}</h2>
                <div class="text-muted small">Code: {{ $product->code }} | Category: {{ $product->category->title ?? 'N/A' }}</div>
            </div>
        </div>
        <div class="d-flex align-items-center gap-3">
            <div class="d-flex align-items-center gap-2 bg-white px-3 py-2 rounded-pill shadow-sm border">
                <span class="small fw-bold text-muted">FILTER:</span>
                <input type="date" id="prod_from" class="form-control form-control-sm border-0" value="{{ date('Y-m-01') }}">
                <span class="text-muted">to</span>
                <input type="date" id="prod_to" class="form-control form-control-sm border-0" value="{{ date('Y-m-d') }}">
            </div>
            <div class="dropdown">
                <button class="btn btn-outline-primary dropdown-toggle rounded-pill" type="button" data-bs-toggle="dropdown">
                    <i class="fas fa-download me-2"></i> Export Report
                </button>
                <ul class="dropdown-menu shadow-lg border-0">
                    <li><a class="dropdown-item export-link" href="{{ route('reports.product', ['id' => $product->id, 'format' => 'csv']) }}">CSV Format</a></li>
                    <li><a class="dropdown-item export-link" href="{{ route('reports.product', ['id' => $product->id, 'format' => 'pdf']) }}">PDF Format</a></li>
                </ul>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-3">
            <div class="stat-card">
                <h6 class="text-muted small fw-bold">CURRENT STOCK</h6>
                <h3 class="fw-bold mb-0 text-primary">{{ $product->stocks->sum('quantity') }}</h3>
                <div class="text-muted small mt-1">Across {{ $product->stocks->count() }} stores</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <h6 class="text-muted small fw-bold">TOTAL PURCHASED</h6>
                <h3 class="fw-bold mb-0 text-success">{{ $product->purchaseItems->sum('quantity') }}</h3>
                <div class="text-muted small mt-1">Life-time Units</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <h6 class="text-muted small fw-bold">TOTAL SOLD (NET)</h6>
                <h3 class="fw-bold mb-0 text-info">{{ $product->orderItems->sum('quantity') - \App\Models\ReturnItem::where('product_id', $product->id)->sum('quantity') }}</h3>
                <div class="text-muted small mt-1">Revenue: ${{ number_format($totalRev, 2) }}</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <h6 class="text-muted small fw-bold">FIFO PROFIT</h6>
                @php 
                    $balance = $totalRev - $totalCogs;
                @endphp
                <h3 class="fw-bold mb-0 {{ $balance >= 0 ? 'text-success' : 'text-danger' }}">
                    {{ $balance >= 0 ? '+' : '' }}${{ number_format($balance, 2) }}
                </h3>
                <div class="text-muted small mt-1">COGS: ${{ number_format($totalCogs, 2) }}</div>
            </div>
        </div>
    </div>

    <div class="table-glass">
        <div class="p-4 border-bottom">
            <h5 class="fw-bold mb-0">Transaction History</h5>
        </div>
        <div class="table-responsive">
            <table class="table mb-0 align-middle">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">Date</th>
                        <th>Type</th>
                        <th>Reference</th>
                        <th>Entity</th>
                        <th>Variant</th>
                        <th>Qty</th>
                        <th>Price</th>
                        <th class="text-end pe-4">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($history as $record)
                    <tr>
                        <td class="ps-4">
                            <span class="text-muted small fw-bold">{{ $record['date'] }}</span>
                        </td>
                        <td>
                            @if(isset($record['is_return']))
                                <span class="badge bg-danger-soft text-danger px-3">RETURN</span>
                            @elseif($record['is_sale'])
                                <span class="badge bg-info-soft text-info px-3">SALE</span>
                            @else
                                <span class="badge bg-success-soft text-success px-3">PURCHASE</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ $record['link'] }}" class="fw-bold text-accent text-decoration-none">
                                {{ $record['ref'] }} <i class="fas fa-external-link-alt xsmall ms-1"></i>
                            </a>
                        </td>
                        <td>{{ $record['entity'] }}</td>
                        <td><span class="badge bg-light text-muted fw-normal">{{ $record['variant'] }}</span></td>
                        <td>
                            <span class="fw-bold {{ $record['qty'] > 0 ? 'text-success' : 'text-danger' }}">
                                {{ $record['qty'] > 0 ? '+' : '' }}{{ $record['qty'] }}
                            </span>
                        </td>
                        <td>${{ number_format($record['price'], 2) }}</td>
                        <td class="text-end pe-4 fw-bold">${{ number_format($record['total'], 2) }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-5">
                            <i class="fas fa-history fa-3x text-light mb-3"></i>
                            <p class="text-muted">No transaction history found for this product.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@section('scripts')
<script>
    const fromInput = document.getElementById('prod_from');
    const toInput = document.getElementById('prod_to');

    // Handle export clicks
    document.querySelectorAll('.export-link').forEach(link => {
        link.addEventListener('click', function(e) {
            const url = new URL(this.href);
            if (fromInput.value) url.searchParams.set('from_date', fromInput.value);
            if (toInput.value) url.searchParams.set('to_date', toInput.value);
            this.href = url.toString();
        });
    });

    // Handle table refresh on date change
    [fromInput, toInput].forEach(input => {
        input.addEventListener('change', () => {
            const url = new URL(window.location.href);
            url.searchParams.set('from_date', fromInput.value);
            url.searchParams.set('to_date', toInput.value);
            window.location.href = url.toString();
        });
    });

    // Set initial values if present in URL
    const params = new URLSearchParams(window.location.search);
    if (params.has('from_date')) fromInput.value = params.get('from_date');
    if (params.has('to_date')) toInput.value = params.get('to_date');
</script>
@endsection
@endsection
