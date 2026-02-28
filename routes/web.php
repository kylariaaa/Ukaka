<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PaymentHistoryController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/', [HomeController::class , 'index'])->name('home');

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
