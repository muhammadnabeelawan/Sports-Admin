<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;

class SaleController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['customer', 'items.product', 'items.variant', 'returns'])
            ->where('type', 'pos');

        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('order_number', 'like', '%' . $request->search . '%')
                  ->orWhereHas('customer', function($sq) use ($request) {
                      $sq->where('name', 'like', '%' . $request->search . '%')
                        ->orWhere('phone', 'like', '%' . $request->search . '%');
                  });
            });
        }

        if ($request->from_date) {
            $query->where('created_at', '>=', $request->from_date . ' 00:00:00');
        }
        if ($request->to_date) {
            $query->where('created_at', '<=', $request->to_date . ' 23:59:59');
        }

        $sales = $query->latest()->paginate(15);

        return view('sales.index', compact('sales'));
    }

    public function show(Order $sale)
    {
        $sale->load(['customer', 'items.product', 'items.variant', 'returns.items.product']);
        return view('sales.show', ['order' => $sale]);
    }
}
