<!DOCTYPE html>
<html>
<head>
    <title>Purchase Orders Report</title>
    <style>
        body { font-family: sans-serif; font-size: 11px; color: #333; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #3b82f6; padding-bottom: 10px; }
        .title { font-size: 20px; font-weight: bold; color: #3b82f6; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th { background-color: #f8fafc; color: #3b82f6; text-align: left; padding: 8px; border-bottom: 2px solid #eee; }
        td { padding: 8px; border-bottom: 1px solid #eee; }
        .text-right { text-align: right; }
        .footer { position: fixed; bottom: 0; width: 100%; text-align: center; font-size: 10px; color: #aaa; }
    </style>
</head>
<body>
    <div class="header">
        <div class="title">All Purchase Orders Report</div>
        <div>Generated on: {{ date('Y-m-d H:i') }}</div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>PO Number</th>
                <th>Supplier</th>
                <th class="text-right">Total Amount</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @php $grandTotal = 0; @endphp
            @foreach($purchases as $p)
            <tr>
                <td>{{ $p->received_date }}</td>
                <td><strong>{{ $p->order_number }}</strong></td>
                <td>{{ $p->supplier->name }}</td>
                <td class="text-right">${{ number_format($p->total_amount, 2) }}</td>
                <td>{{ strtoupper($p->status) }}</td>
            </tr>
            @php $grandTotal += $p->total_amount; @endphp
            @endforeach
        </tbody>
        <tfoot>
            <tr style="background: #f1f5f9; font-weight: bold;">
                <td colspan="3" class="text-right">GRAND TOTAL</td>
                <td class="text-right">${{ number_format($grandTotal, 2) }}</td>
                <td></td>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        SportsHero Admin System - Confidential Management Report
    </div>
</body>
</html>
