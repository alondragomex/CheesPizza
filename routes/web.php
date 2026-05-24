<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\CustomerAuthController;
use App\Http\Controllers\AdminProductController;
use App\Http\Controllers\AdminOrderController;
use App\Http\Controllers\AdminAuthController;
use App\Http\Middleware\AdminAuthMiddleware;

// Rutas del Cliente: Ver menú, registro por teléfono y mandar pedidos
Route::get('/', [CustomerController::class, 'index'])->name('menu');
Route::post('/order', [CustomerController::class, 'storeOrder'])->name('order.store');
Route::post('/customer/register', [CustomerAuthController::class, 'register'])->name('customer.register');
Route::post('/customer/logout', [CustomerAuthController::class, 'logout'])->name('customer.logout');

// Rutas de Administración
Route::prefix('admin')->name('admin.')->group(function () {
    // Rutas públicas de Login
    Route::get('/login', [AdminAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('login.post');

    // Rutas protegidas mediante Middleware de Sesión
    Route::middleware(AdminAuthMiddleware::class)->group(function () {
        Route::get('/', [AdminOrderController::class, 'index'])->name('dashboard');
        Route::patch('/orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.status');
        Route::delete('/orders/{order}', [AdminOrderController::class, 'destroy'])->name('orders.destroy');
        Route::resource('products', AdminProductController::class)->except(['show']);
        Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');
        Route::post('/corte-caja', [AdminOrderController::class, 'corteCaja'])->name('corte-caja');

        // Endpoint AJAX rápido para validar nuevos pedidos en tiempo real
        Route::get('/check-new-orders', function () {
            $latestOrder = \App\Models\Order::orderBy('id', 'desc')->first();
            return response()->json([
                'latest_id' => $latestOrder ? $latestOrder->id : 0
            ]);
        })->name('check-new-orders');
    });
});

