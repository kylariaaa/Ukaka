<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PaymentHistoryController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Public routes
Route::get('/', [HomeController::class , 'index'])->name('home');
Route::get('/products', [ProductController::class , 'index'])->name('products.index');
Route::get('/flash-sale', [ProductController::class , 'flashSale'])->name('products.flash-sale');
Route::get('/new-arrivals', [ProductController::class , 'newArrivals'])->name('products.new-arrivals');
Route::get('/products/{product:slug}', [ProductController::class , 'show'])->name('products.show');

// Auth routes (provided by Breeze)
require __DIR__ . '/auth.php';

// Auth-protected routes
Route::middleware('auth')->group(function () {
    // Fallback dashboard route that redirects based on role
    Route::get('/dashboard', function () {
            if (Auth::guard('admin')->check()) {
                return redirect()->route('admin.dashboard');
            }
            return redirect()->route('home');
        }
        )->name('dashboard');

        Route::get('/cart', [CartController::class , 'index'])->name('cart');
        Route::post('/cart/add', [CartController::class , 'add'])->name('cart.add');
        Route::post('/cart/remove', [CartController::class , 'remove'])->name('cart.remove');
        Route::post('/cart/update', [CartController::class , 'updateQuantity'])->name('cart.update');
        Route::get('/checkout', [CheckoutController::class , 'create'])->name('checkout.create');
        Route::post('/checkout', [CheckoutController::class , 'store'])->name('checkout.store');
        Route::get('/payment-history', [PaymentHistoryController::class , 'index'])->name('payment-history');
    });

// Admin public routes (no auth required)
Route::prefix('admin')->group(function () {
    Route::get('/login', [\App\Http\Controllers\Admin\Auth\AdminAuthController::class , 'showLoginForm'])->name('admin.login');
    Route::post('/login', [\App\Http\Controllers\Admin\Auth\AdminAuthController::class , 'login'])->name('admin.login.submit');
    Route::post('/logout', [\App\Http\Controllers\Admin\Auth\AdminAuthController::class , 'logout'])->name('admin.logout');
});

Route::prefix('admin')->middleware(['auth:admin'])->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\AdminController::class , 'index'])->name('admin.dashboard');

    // Products
    Route::resource('products', \App\Http\Controllers\AdminProductController::class)->names('admin.products')->except(['show']);

    // Orders
    Route::get('/orders', [\App\Http\Controllers\AdminOrderController::class , 'index'])->name('admin.orders.index');
    Route::patch('/orders/{id}/accept', [\App\Http\Controllers\AdminOrderController::class , 'accept'])->name('admin.orders.accept');
    Route::patch('/orders/{id}/reject', [\App\Http\Controllers\AdminOrderController::class , 'reject'])->name('admin.orders.reject');
});
