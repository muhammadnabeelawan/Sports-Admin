<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\POS_API\POSController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/products', [ProductController::class, 'getProducts']);
Route::get('/products/{id}', [ProductController::class, 'getProductDetail']);
Route::get('/pos/categories', [POSController::class, 'getCategories']);
Route::get('/pos/brands', [POSController::class, 'getBrands']);
Route::post('/pos/sales', [POSController::class, 'createSale']);
Route::post('/pos/returns', [POSController::class, 'processReturn']);
Route::get('/pos/history', [POSController::class, 'getTodayHistory']);
Route::get('/pos/customers', function() {
    return response()->json(['success' => true, 'data' => \App\Models\Customer::latest()->get()]);
});
