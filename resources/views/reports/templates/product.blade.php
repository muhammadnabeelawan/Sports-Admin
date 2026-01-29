<!DOCTYPE html>
<html>
<head>
    <title>Product Report - {{ $product->title }}</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; color: #333; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #3b82f6; padding-bottom: 10px; }
        .title { font-size: 24px; font-weight: bold; color: #3b82f6; margin-bottom: 5px; }
        .meta { margin-bottom: 20px; }
        .stats-table { width: 100%; margin-bottom: 30px; border-collapse: collapse; }
        .stats-table td { padding: 10px; border: 1px solid #eee; }
        .label { font-weight: bold; color: #666; font-size: 10px; text-transform: uppercase; }
        .value { font-size: 16px; font-weight: bold; display: block; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th { background-color: #f8fafc; color: #3b82f6; text-align: left; padding: 10px; border-bottom: 2px solid #eee; }
        td { padding: 10px; border-bottom: 1px solid #eee; }
        .text-success { color: #10b981; }
        .text-danger { color: #ef4444; }
        .text-info { color: #3b82f6; }
        .footer { position: fixed; bottom: 0; width: 100%; text-align: center; font-size: 10px; color: #aaa; }
    </style>
</head>
<body>
    <div class="header">
        <div class="title">Product Performance Report</div>
        <div>Generated on: {{ date('Y-m-d H:i') }}</div>
    </div>

    <div class="meta">
        <h3>{{ $product->title }}</h3>
        <p>Code: <strong>{{ $product->code }}</strong> | Category: {{ $product->category->title ?? 'N/A' }} | Brand: {{ $product->brand->title ?? 'N/A' }}</p>
    </div>

    <table class="stats-table">
        <tr>
            <td>
                <span class="label">Current Stock</span>
                <span class="value">{{ $product->stocks->sum('quantity') }}</span>
            </td>
            <td>
                <span class="label">Total Purchased</span>
                <span class="value text-success">{{ $product->purchaseItems->sum('quantity') }}</span>
            </td>
            <td>
                <span class="label">Total Sold</span>
                <span class="value text-info">{{ $product->orderItems->sum('quantity') }}</span>
            </td>
            <td>
                <span class="label">Net Balance</span>
                @php 
                    $cost = $product->purchaseItems->sum(fn($i) => $i->quantity * $i->cost);
                    $rev = $product->orderItems->sum(fn($i) => $i->quantity * $i->price);
                    $balance = $rev - $cost;
                @endphp
                <span class="value {{ $balance >= 0 ? 'text-success' : 'text-danger' }}">
                    {{ $balance >= 0 ? '+' : '' }}${{ number_format($balance, 2) }}
                </span>
            </td>
        </tr>
    </table>

    <h4>Transaction History</h4>
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Type</th>
                <th>Description</th>
                <th>Qty</th>
                <th>Price</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($history as $record)
            <tr>
                <td>{{ $record['date'] }}</td>
                <td><strong>{{ strtoupper($record['type']) }}</strong></td>
                <td>{{ $record['description'] }}</td>
                <td class="{{ $record['qty'] > 0 ? 'text-success' : 'text-danger' }}">
                    {{ $record['qty'] > 0 ? '+' : '' }}{{ $record['qty'] }}
                </td>
                <td>${{ number_format($record['price'], 2) }}</td>
                <td>${{ number_format($record['total'], 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        SportsHero Admin System - Confidential Report
    </div>
</body>
</html>
