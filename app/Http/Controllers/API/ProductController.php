<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function getProducts()
    {
        $products = \App\Models\Product::with(['category', 'brand', 'variants'])->get();
        return response()->json([
            'success' => true,
            'data' => $products
        ]);
    }

    public function getProductDetail($id)
    {
        $product = \App\Models\Product::with(['category', 'brand', 'variants'])->find($id);
        if (!$product) {
            return response()->json(['success' => false, 'message' => 'Product not found'], 404);
        }
        return response()->json(['success' => true, 'data' => $product]);
    }
}
