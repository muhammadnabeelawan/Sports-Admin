<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\PurchaseOrder;
use App\Models\Order;
use App\Models\Stock;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Response;

class ReportController extends Controller
{
    public function index()
    {
        return view('reports.index');
    }

    public function productReport(Request $request, $id, $format)
    {
        $product = Product::with(['category', 'brand', 'variants', 'stocks.store'])->findOrFail($id);
        
        $queryPurchases = $product->purchaseItems()->with('purchaseOrder.supplier');
        $queryOrders = $product->orderItems()->with('order');

        if ($request->from_date) {
            $queryPurchases->whereHas('purchaseOrder', fn($q) => $q->where('received_date', '>=', $request->from_date));
            $queryOrders->whereHas('order', fn($q) => $q->where('created_at', '>=', $request->from_date . ' 00:00:00'));
        }
        if ($request->to_date) {
            $queryPurchases->whereHas('purchaseOrder', fn($q) => $q->where('received_date', '<=', $request->to_date));
            $queryOrders->whereHas('order', fn($q) => $q->where('created_at', '<=', $request->to_date . ' 23:59:59'));
        }

        $purchaseItems = $queryPurchases->get();
        $orderItems = $queryOrders->get();

        $history = collect();
        foreach($purchaseItems as $item) {
            $history->push([
                'date' => $item->purchaseOrder->received_date,
                'type' => 'Purchase',
                'description' => 'From ' . $item->purchaseOrder->supplier->name,
                'qty' => $item->quantity,
                'price' => $item->cost,
                'total' => $item->quantity * $item->cost
            ]);
        }
        foreach($orderItems as $item) {
            $history->push([
                'date' => $item->order->created_at->format('Y-m-d'),
                'type' => 'Sale',
                'description' => 'Customer Sale',
                'qty' => -$item->quantity,
                'price' => $item->price,
                'total' => $item->quantity * $item->price
            ]);
        }
        $history = $history->sortByDesc('date');

        if ($format === 'pdf') {
            $pdf = Pdf::loadView('reports.templates.product', compact('product', 'history'));
            return $pdf->download('Product_Report_' . $product->code . '.pdf');
        }

        // CSV
        $filename = "Product_Report_" . $product->code . ".csv";
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="'.$filename.'"');
        $handle = fopen('php://output', 'w');
        fputcsv($handle, ['Product Report', $product->title, 'Code: ' . $product->code]);
        fputcsv($handle, ['Date Range:', ($request->from_date ?? 'All') . ' to ' . ($request->to_date ?? 'All')]);
        fputcsv($handle, []);
        fputcsv($handle, ['Date', 'Type', 'Description', 'Quantity', 'Price', 'Total']);

        foreach ($history as $line) {
            fputcsv($handle, [$line['date'], $line['type'], $line['description'], $line['qty'], $line['price'], $line['total']]);
        }
        fclose($handle);
        return Response::make('', 200);
    }

    public function purchaseOrdersReport(Request $request, $format)
    {
        $query = PurchaseOrder::with('supplier');

        if ($request->from_date) {
            $query->where('received_date', '>=', $request->from_date);
        }
        if ($request->to_date) {
            $query->where('received_date', '<=', $request->to_date);
        }

        $purchases = $query->latest()->get();

        if ($format === 'pdf') {
            $pdf = Pdf::loadView('reports.templates.purchases', compact('purchases'));
            return $pdf->download('All_Purchases_Report.pdf');
        }

        $filename = "All_Purchases_Report.csv";
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="'.$filename.'"');
        $handle = fopen('php://output', 'w');
        fputcsv($handle, ['All Purchase Orders Report']);
        fputcsv($handle, ['Date Range:', ($request->from_date ?? 'All') . ' to ' . ($request->to_date ?? 'All')]);
        fputcsv($handle, []);
        fputcsv($handle, ['Date', 'PO Number', 'Supplier', 'Total Amount', 'Status']);

        foreach ($purchases as $p) {
            fputcsv($handle, [$p->received_date, $p->order_number, $p->supplier->name, $p->total_amount, $p->status]);
        }
        fclose($handle);
        return Response::make('', 200);
    }

    public function inventoryReport(Request $request, $format)
    {
        // Inventory is usually current state, but we can filter by products updated in a range if needed.
        // For standard inventory, we will just keep it as is or filter by creation date.
        $products = Product::with(['stocks.store', 'category'])->get();

        if ($format === 'pdf') {
            $pdf = Pdf::loadView('reports.templates.inventory', compact('products'));
            return $pdf->download('Inventory_Status_Report.pdf');
        }

        $filename = "Inventory_Status_Report.csv";
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="'.$filename.'"');
        $handle = fopen('php://output', 'w');
        fputcsv($handle, ['Inventory Status Report']);
        fputcsv($handle, []);
        fputcsv($handle, ['Category', 'Product', 'Code', 'Total Stock', 'Valuation']);

        foreach ($products as $p) {
            $totalStock = $p->stocks->sum('quantity');
            $valuation = $totalStock * $p->price;
            fputcsv($handle, [$p->category->title ?? 'N/A', $p->title, $p->code, $totalStock, $valuation]);
        }
        fclose($handle);
        return Response::make('', 200);
    }
}
