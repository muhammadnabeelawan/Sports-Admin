<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\ExpenseController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::get('/', function () {
        return redirect()->route('admin.dashboard');
    });

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

    // CRUDs
    Route::resource('categories', CategoryController::class);
    Route::resource('products', ProductController::class);
    Route::resource('brands', BrandController::class);
    Route::resource('suppliers', SupplierController::class);
    Route::resource('stores', StoreController::class);
    Route::resource('expenses', ExpenseController::class);
    Route::resource('customers', \App\Http\Controllers\CustomerController::class);
    Route::resource('purchases', \App\Http\Controllers\PurchaseController::class);
    Route::resource('sales', \App\Http\Controllers\SaleController::class);

    // Stock Management
    Route::get('/stocks', [ProductController::class, 'stockIndex'])->name('stocks.index');
    Route::post('/stocks/update', [ProductController::class, 'updateStock'])->name('stocks.update');

    // Profile
    Route::get('/profile', [AuthController::class, 'profile'])->name('admin.profile');
    Route::post('/profile', [AuthController::class, 'updateProfile'])->name('admin.profile.update');

    // Reports
    Route::get('/reports', [\App\Http\Controllers\ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/product/{id}/{format}', [\App\Http\Controllers\ReportController::class, 'productReport'])->name('reports.product');
    Route::get('/reports/purchases/{format}', [\App\Http\Controllers\ReportController::class, 'purchaseOrdersReport'])->name('reports.purchases');
    Route::get('/reports/inventory/{format}', [\App\Http\Controllers\ReportController::class, 'inventoryReport'])->name('reports.inventory');
});
