<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Models\Supplier;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Stock;
use App\Models\Store;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PurchaseController extends Controller
{
    public function index(Request $request)
    {
        $query = PurchaseOrder::with('supplier');

        if ($request->search) {
            $query->where('order_number', 'like', '%' . $request->search . '%');
        }

        if ($request->supplier_id) {
            $query->where('supplier_id', $request->supplier_id);
        }

        $purchases = $query->latest()->get();
        $suppliers = \App\Models\Supplier::all();

        return view('purchases.index', compact('purchases', 'suppliers'));
    }

    public function create()
    {
        $suppliers = Supplier::all();
        $products = Product::with('variants')->get();
        $stores = Store::all();
        return view('purchases.create', compact('suppliers', 'products', 'stores'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'store_id' => 'required|exists:stores,id',
            'product_id' => 'required|array',
            'quantity' => 'required|array',
            'cost' => 'required|array',
        ]);

        DB::transaction(function () use ($request) {
            $purchase = PurchaseOrder::create([
                'supplier_id' => $request->supplier_id,
                'order_number' => 'PO-' . strtoupper(Str::random(6)),
                'status' => 'received',
                'received_date' => now(),
                'total_amount' => 0
            ]);

            $totalAmount = 0;
            foreach ($request->product_id as $key => $productId) {
                $variantId = $request->variant_id[$key] ?? null;
                $qty = $request->quantity[$key];
                $cost = $request->cost[$key];

                PurchaseOrderItem::create([
                    'purchase_order_id' => $purchase->id,
                    'product_id' => $productId,
                    'variant_id' => $variantId,
                    'quantity' => $qty,
                    'cost' => $cost,
                ]);

                // Update Stock safely
                $stock = Stock::firstOrNew([
                    'store_id' => $request->store_id,
                    'product_id' => $productId,
                    'variant_id' => $variantId
                ]);
                $stock->quantity = ($stock->quantity ?? 0) + $qty;
                $stock->save();

                $totalAmount += ($qty * $cost);
            }

            $purchase->update(['total_amount' => $totalAmount]);
        });

        return redirect()->route('purchases.index')->with('success', 'Purchase recorded and stock updated.');
    }
    public function show(PurchaseOrder $purchase)
    {
        $purchase->load(['supplier', 'items.product', 'items.variant']);
        return view('purchases.show', compact('purchase'));
    }
}
