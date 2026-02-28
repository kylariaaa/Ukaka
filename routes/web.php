<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PaymentHistoryController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/', [HomeController::class , 'index'])->name('home');
Route::get('/products', [ProductController::class , 'index'])->name('products.index');
Route::get('/flash-sale', [ProductController::class , 'flashSale'])->name('products.flash-sale');
Route::get('/new-arrivals', [ProductController::class , 'newArrivals'])->name('products.new-arrivals');

// Auth routes (provided by Breeze)
require __DIR__ . '/auth.php';

// Auth-protected routes
Route::middleware('auth')->group(function () {
    Route::get('/cart', [CartController::class , 'index'])->name('cart');
    Route::post('/cart/add', [CartController::class , 'add'])->name('cart.add');
    Route::post('/cart/remove', [CartController::class , 'remove'])->name('cart.remove');
    Route::post('/cart/update', [CartController::class , 'updateQuantity'])->name('cart.update');
    Route::post('/checkout', [CheckoutController::class , 'store'])->name('checkout.store');
    Route::get('/payment-history', [PaymentHistoryController::class , 'index'])->name('payment-history');
});

// Admin routes
Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\AdminController::class , 'index'])->name('admin.dashboard');
    Route::get('/products/create', [\App\Http\Controllers\AdminProductController::class , 'create'])->name('admin.products.create');
});
