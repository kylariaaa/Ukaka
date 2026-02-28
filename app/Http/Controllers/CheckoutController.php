<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'payment_method' => 'required|string|in:paypal,dana,bca,mandiri',
        ]);

        $cart = session('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart')->with('error', 'Keranjang belanja kosong!');
        }

        // Calculate total and build items
        $total = 0;
        $orderItems = [];

        foreach ($cart as $productId => $item) {
            $product = Product::find($productId);
            if (!$product)
                continue;

            $price = $product->effective_price;
            $qty = $item['quantity'];
            $subtotal = $price * $qty;
            $total += $subtotal;

            $orderItems[] = [
                'product_id' => $product->id,
                'quantity' => $qty,
                'price' => $price,
                'subtotal' => $subtotal,
            ];
        }

        // Create order
        $order = Order::create([
            'user_id' => auth()->id(),
            'order_code' => 'ORD-' . strtoupper(Str::random(8)) . '-' . time(),
            'total_price' => $total,
            'status' => 'process',
            'payment_method' => $request->payment_method,
        ]);

        // Create order items
        foreach ($orderItems as $item) {
            $order->orderItems()->create($item);
        }

        // Clear cart
        session()->forget('cart');

        return redirect()->route('payment-history')->with('success', 'Pesanan berhasil! Kode order: ' . $order->order_code);
    }
}
