<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Product;
use App\Models\Category;
use App\Models\Order;
use App\Models\Stock;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'products_count' => Product::count(),
            'categories_count' => Category::count(),
            'orders_count' => Order::count(),
            'total_stock' => Stock::sum('quantity'),
            'total_revenue' => Order::sum('total_amount'),
        ];

        // Top Selling Products
        $topProducts = \App\Models\OrderItem::select('product_id', \DB::raw('SUM(quantity) as total_qty'))
            ->groupBy('product_id')
            ->with('product')
            ->orderByDesc('total_qty')
            ->take(5)
            ->get();

        // Top Categories
        $topCategories = \App\Models\OrderItem::join('products', 'order_items.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->select('categories.title', \DB::raw('SUM(order_items.quantity) as total_qty'))
            ->groupBy('categories.id', 'categories.title')
            ->orderByDesc('total_qty')
            ->take(5)
            ->get();

        // Monthly Revenue Chart Data
        $monthlyRevenue = Order::select(
                \DB::raw('MONTH(created_at) as month'),
                \DB::raw('SUM(total_amount) as total')
            )
            ->whereYear('created_at', date('Y'))
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month')
            ->toArray();

        $chartData = [
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            'revenue' => array_map(fn($m) => $monthlyRevenue[$m] ?? 0, range(1, 12)),
        ];

        return view('dashboard', compact('stats', 'chartData', 'topProducts', 'topCategories'));
    }
}
