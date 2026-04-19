<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\EmployeeController;
use App\Http\Controllers\Admin\TodoController;

// Main POS / Order Interface
Route::get('/', [OrderController::class, 'index'])->name('orders.index');
Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
Route::get('/queue', [OrderController::class, 'queue'])->name('orders.queue');
Route::post('/orders/{order}/complete', [OrderController::class, 'complete'])->name('orders.complete');
Route::post('/orders/{order}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');
Route::patch('/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.status');

// Admin Panel
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/sales', [DashboardController::class, 'sales'])->name('sales');
    Route::get('/stock', [DashboardController::class, 'stock'])->name('stock');
    Route::patch('/stock/{product}', [DashboardController::class, 'updateStock'])->name('stock.update');

    // Products
    Route::resource('products', ProductController::class);
    Route::post('/products/{id}/restore', [ProductController::class, 'restore'])->name('products.restore');

    // Employees
    Route::resource('employees', EmployeeController::class);
    Route::post('/employees/{id}/restore', [EmployeeController::class, 'restore'])->name('employees.restore');

    // Todos
    Route::resource('todos', TodoController::class);
    Route::patch('/todos/{todo}/status', [TodoController::class, 'updateStatus'])->name('todos.status');
});
