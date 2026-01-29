<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\ProductVariant;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['category', 'stocks'])->withCount('variants');

        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('code', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->category_id) {
            $query->where('category_id', $request->category_id);
        }

        $products = $query->latest()->get();
        $categories = Category::all();

        return view('products.index', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = Category::all();
        $brands = Brand::all();
        return view('products.create', compact('categories', 'brands'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'code' => 'required|string|unique:products,code',
            'slug' => 'required|string|unique:products,slug',
            'category_id' => 'required|exists:categories,id',
            'price' => 'nullable|numeric',
        ]);

        $product = Product::create($request->only([
            'title', 'code', 'slug', 'description', 
            'category_id', 'brand_id', 'price', 
            'have_variants', 'status'
        ]));

        if ($request->have_variants && $request->has('variant_titles')) {
            foreach ($request->variant_titles as $key => $title) {
                if ($title) {
                    ProductVariant::create([
                        'product_id' => $product->id,
                        'title' => $title,
                        'sku' => $request->variant_skus[$key] ?? $product->code . '-' . Str::random(4),
                        'price' => $request->variant_prices[$key] ?? 0,
                    ]);
                }
            }
        }

        return redirect()->route('products.index')->with('success', 'Product published successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Product $product)
    {
        $product->load(['category', 'brand', 'variants', 'stocks.store']);
        
        // 1. Fetch all raw data for FIFO calculation
        $allPurchases = $product->purchaseItems()->with('purchaseOrder')
            ->join('purchase_orders', 'purchase_order_items.purchase_order_id', '=', 'purchase_orders.id')
            ->orderBy('purchase_orders.received_date', 'asc')
            ->select('purchase_order_items.*')
            ->get();

        $allOrders = $product->orderItems()->with('order')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->orderBy('orders.created_at', 'asc')
            ->select('order_items.*')
            ->get();

        // Include Returns in calculation
        $allReturns = \App\Models\ReturnItem::where('product_id', $product->id)
            ->join('returns', 'return_items.return_order_id', '=', 'returns.id')
            ->orderBy('returns.created_at', 'asc')
            ->select('return_items.*')
            ->get();

        // 2. FIFO Profit Engine
        $batches = [];
        foreach ($allPurchases as $p) {
            $batches[] = (object)[
                'id' => $p->id,
                'qty' => $p->quantity,
                'remaining' => $p->quantity,
                'cost' => $p->cost
            ];
        }

        $totalCogs = 0;
        $totalRev = 0;

        // Process Sales
        foreach ($allOrders as $sale) {
            $totalRev += ($sale->quantity * $sale->price);
            $needed = $sale->quantity;
            
            foreach ($batches as &$batch) {
                if ($needed <= 0) break;
                if ($batch->remaining > 0) {
                    $take = min($needed, $batch->remaining);
                    $totalCogs += ($take * $batch->cost);
                    $batch->remaining -= $take;
                    $needed -= $take;
                }
            }
        }

        // Process Returns (Reverse FIFO: put back into batches)
        foreach ($allReturns as $ret) {
            $totalRev -= ($ret->quantity * $ret->refund_price);
            $restoring = $ret->quantity;
            // Return logic: restore to the most recently used batch (LIFO restore)
            for ($i = count($batches) - 1; $i >= 0; $i--) {
                if ($restoring <= 0) break;
                $capacity = $batches[$i]->qty - $batches[$i]->remaining;
                if ($capacity > 0) {
                    $restore = min($restoring, $capacity);
                    $totalCogs -= ($restore * $batches[$i]->cost);
                    $batches[$i]->remaining += $restore;
                    $restoring -= $restore;
                }
            }
        }

        // 3. Prepare History with Links
        $history = collect();

        foreach($allPurchases as $item) {
            $history->push([
                'date' => $item->purchaseOrder->received_date,
                'type' => 'Purchase',
                'ref' => $item->purchaseOrder->order_number,
                'link' => route('purchases.show', $item->purchaseOrder->id),
                'entity' => $item->purchaseOrder->supplier->name ?? 'Supplier',
                'qty' => $item->quantity,
                'price' => $item->cost,
                'variant' => $item->variant->title ?? 'N/A',
                'total' => $item->quantity * $item->cost,
                'is_sale' => false
            ]);
        }

        foreach($allOrders as $item) {
            $history->push([
                'date' => $item->order->created_at->format('Y-m-d'),
                'type' => 'Sale',
                'ref' => $item->order->order_number,
                'link' => route('sales.show', $item->order->id),
                'entity' => $item->order->customer->name ?? 'Walk-in',
                'qty' => -$item->quantity,
                'price' => $item->price,
                'variant' => $item->variant->title ?? 'N/A',
                'total' => $item->quantity * $item->price,
                'is_sale' => true
            ]);
        }

        foreach($allReturns as $item) {
            $item->load('returnOrder');
            $history->push([
                'date' => $item->returnOrder->created_at->format('Y-m-d'),
                'type' => 'Return',
                'ref' => $item->returnOrder->return_number,
                'link' => route('sales.show', $item->returnOrder->order_id), // Link to original sale
                'entity' => 'Customer',
                'qty' => $item->quantity,
                'price' => $item->refund_price,
                'variant' => $item->variant->title ?? 'N/A',
                'total' => -($item->quantity * $item->refund_price),
                'is_sale' => true,
                'is_return' => true
            ]);
        }

        $history = $history->sortByDesc('date');

        return view('products.show', compact('product', 'history', 'totalCogs', 'totalRev'));
    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        $brands = Brand::all();
        $product->load('variants');
        return view('products.edit', compact('product', 'categories', 'brands'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'code' => 'required|string|unique:products,code,' . $product->id,
            'slug' => 'required|string|unique:products,slug,' . $product->id,
            'category_id' => 'required|exists:categories,id',
            'price' => 'nullable|numeric',
        ]);

        $product->update($request->only([
            'title', 'code', 'slug', 'description', 
            'category_id', 'brand_id', 'price', 
            'have_variants', 'status'
        ]));

        // Simple variant sync: delete old and recreate
        // In a real app, you might want to match IDs to prevent data loss or duplicate SKUs
        if ($request->have_variants && $request->has('variant_titles')) {
            $product->variants()->delete();
            foreach ($request->variant_titles as $key => $title) {
                if ($title) {
                    ProductVariant::create([
                        'product_id' => $product->id,
                        'title' => $title,
                        'sku' => $request->variant_skus[$key] ?? $product->code . '-' . Str::random(4),
                        'price' => $request->variant_prices[$key] ?? 0,
                    ]);
                }
            }
        }

        return redirect()->route('products.index')->with('success', 'Product updated successfully.');
    }

    public function stockIndex(Request $request)
    {
        $query = Product::with(['variants', 'stocks']);

        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('code', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->category_id) {
            $query->where('category_id', $request->category_id);
        }

        $products = $query->get();
        
        if ($request->status) {
            $products = $products->filter(function($product) use ($request) {
                $totalStock = $product->stocks->sum('quantity');
                if ($request->status === 'low') return $totalStock < 10;
                if ($request->status === 'optimal') return $totalStock >= 10;
                return true;
            });
        }

        $stores = \App\Models\Store::all();
        if ($stores->isEmpty()) {
            \App\Models\Store::create(['title' => 'Main Branch', 'location' => 'Default', 'phone' => '000']);
            $stores = \App\Models\Store::all();
        }
        
        $categories = Category::all();

        return view('stocks.index', compact('products', 'stores', 'categories'));
    }

    public function updateStock(Request $request)
    {
        $request->validate([
            'store_id' => 'required|exists:stores,id',
            'product_id' => 'required|exists:products,id',
            'variant_id' => 'nullable|exists:product_variants,id',
            'quantity' => 'required|integer',
        ]);

        $stock = \App\Models\Stock::firstOrNew([
            'store_id' => $request->store_id,
            'product_id' => $request->product_id,
            'variant_id' => $request->variant_id,
        ]);
        $stock->quantity = ($stock->quantity ?? 0) + $request->quantity;
        $stock->save();

        return back()->with('success', 'Stock updated successfully.');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('products.index')->with('success', 'Product deleted successfully.');
    }
}
