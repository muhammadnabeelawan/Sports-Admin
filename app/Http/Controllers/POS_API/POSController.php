<?php

namespace App\Http\Controllers\POS_API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class POSController extends Controller
{
    public function getCategories()
    {
        return response()->json([
            'success' => true,
            'data' => \App\Models\Category::all()
        ]);
    }

    public function getBrands()
    {
        return response()->json([
            'success' => true,
            'data' => \App\Models\Brand::all()
        ]);
    }

    public function createSale(\Illuminate\Http\Request $request)
    {
        $request->validate([
            'customer_id' => 'nullable|exists:customers,id',
            'store_id' => 'required|exists:stores,id',
            'items' => 'required|array',
            'total_amount' => 'required|numeric',
            'paid_amount' => 'nullable|numeric',
        ]);

        $paid_amount = $request->paid_amount ?? $request->total_amount;

        $order = \App\Models\Order::create([
            'customer_id' => $request->customer_id,
            'store_id' => $request->store_id,
            'order_number' => 'ORD-' . strtoupper(\Illuminate\Support\Str::random(10)),
            'total_amount' => $request->total_amount,
            'paid_amount' => $paid_amount,
            'status' => 'completed',
            'type' => 'pos',
        ]);

        foreach ($request->items as $item) {
            $qty = $item['quantity'] ?? $item['qty'] ?? 1;
            
            \App\Models\OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item['product_id'],
                'variant_id' => $item['variant_id'] ?? null,
                'quantity' => $qty,
                'price' => $item['price'],
            ]);

            // Update stock
            $stock = \App\Models\Stock::where('store_id', $request->store_id)
                ->where('product_id', $item['product_id'])
                ->where('variant_id', $item['variant_id'] ?? null)
                ->first();

            if ($stock) {
                $stock->decrement('quantity', $qty);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Sale created successfully',
            'order' => $order
        ]);
    }
    public function getTodayHistory()
    {
        $today = now()->startOfDay();
        $orders = \App\Models\Order::with(['items.product', 'items.variant', 'returns.items.product', 'returns.items.variant'])
            ->where('created_at', '>=', $today)
            ->where('type', 'pos')
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'orders' => $orders
        ]);
    }

    public function processReturn(\Illuminate\Http\Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'items' => 'required|array',
            'total_amount' => 'required|numeric',
        ]);

        $order = \App\Models\Order::find($request->order_id);

        $return = \App\Models\ReturnOrder::create([
            'return_number' => 'RET-' . strtoupper(\Illuminate\Support\Str::random(10)),
            'order_id' => $order->id,
            'store_id' => $order->store_id,
            'customer_id' => $order->customer_id,
            'total_return_amount' => $request->total_amount,
        ]);

        foreach ($request->items as $item) {
            \App\Models\ReturnItem::create([
                'return_order_id' => $return->id,
                'product_id' => $item['product_id'],
                'variant_id' => $item['variant_id'] ?? null,
                'quantity' => $item['qty'],
                'refund_price' => $item['price'],
            ]);

            // Restock items
            $stock = \App\Models\Stock::where('store_id', $order->store_id)
                ->where('product_id', $item['product_id'])
                ->where('variant_id', $item['variant_id'] ?? null)
                ->first();

            if ($stock) {
                $stock->increment('quantity', $item['qty']);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Return processed and stock updated',
            'return' => $return->load('items')
        ]);
    }
}
