<!DOCTYPE html>
<html>
<head>
    <title>Inventory Status Report</title>
    <style>
        body { font-family: sans-serif; font-size: 11px; color: #333; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #3b82f6; padding-bottom: 10px; }
        .title { font-size: 20px; font-weight: bold; color: #3b82f6; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th { background-color: #f8fafc; color: #3b82f6; text-align: left; padding: 8px; border-bottom: 2px solid #eee; }
        td { padding: 8px; border-bottom: 1px solid #eee; }
        .text-right { text-align: right; }
        .footer { position: fixed; bottom: 0; width: 100%; text-align: center; font-size: 10px; color: #aaa; }
        .low-stock { color: #ef4444; font-weight: bold; }
    </style>
</head>
<body>
    <div class="header">
        <div class="title">Current Inventory Status Report</div>
        <div>Generated on: {{ date('Y-m-d H:i') }}</div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Category</th>
                <th>Product Name</th>
                <th>Product Code</th>
                <th class="text-right">Total Stock</th>
                <th class="text-right">Unit Price</th>
                <th class="text-right">Valuation</th>
            </tr>
        </thead>
        <tbody>
            @php 
                $grandStock = 0; 
                $grandValuation = 0;
            @endphp
            @foreach($products as $p)
            @php 
                $qty = $p->stocks->sum('quantity');
                $val = $qty * $p->price;
                $grandStock += $qty;
                $grandValuation += $val;
            @endphp
            <tr>
                <td>{{ $p->category->title ?? 'N/A' }}</td>
                <td>{{ $p->title }}</td>
                <td><strong>{{ $p->code }}</strong></td>
                <td class="text-right {{ $qty < 5 ? 'low-stock' : '' }}">{{ $qty }}</td>
                <td class="text-right">${{ number_format($p->price, 2) }}</td>
                <td class="text-right">${{ number_format($val, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr style="background: #f1f5f9; font-weight: bold;">
                <td colspan="3" class="text-right">TOTAL INVENTORY</td>
                <td class="text-right">{{ $grandStock }}</td>
                <td></td>
                <td class="text-right">${{ number_format($grandValuation, 2) }}</td>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        SportsHero Admin System - Confidential Inventory Report
    </div>
</body>
</html>
