<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    /**
     * Show the checkout page with total and address form.
     */
    public function create()
    {
        $cart = session('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart')->with('error', 'Keranjang belanja kosong!');
        }

        $total = 0;
        foreach ($cart as $productId => $item) {
            $product = Product::with('category')->find($productId);
            if ($product) {
                if ($product->isCostume()) {
                    $rentalDays = $item['rental_days'] ?? 1;
                    $activePrice = $product->discount_price ?? $product->price_per_day ?? $product->price;
                    $total += $activePrice * $rentalDays;
                }
                else {
                    $total += $product->effective_price * $item['quantity'];
                }
            }
        }

        return view('checkout.create', compact('total'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'payment_method' => 'required|string|in:paypal,dana,bca,mandiri',
            'address' => 'required|string|min:10',
        ]);

        $cart = session('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart')->with('error', 'Keranjang belanja kosong!');
        }

        $total = 0;
        $orderItems = [];

        foreach ($cart as $productId => $item) {
            $product = Product::with('category')->find($productId);
            if (!$product)
                continue;

            $isCostume = $product->isCostume();
            $rentalDays = $item['rental_days'] ?? 1;

            if ($isCostume) {
                $price = $product->discount_price ?? $product->price_per_day ?? $product->price;
                $qty = 1; // Kostum selalu qty 1, dihitung dari hari
                $subtotal = $price * $rentalDays;
            }
            else {
                $price = $product->effective_price;
                $qty = $item['quantity'];
                $subtotal = $price * $qty;
                $rentalDays = null;
            }

            $total += $subtotal;

            $orderItems[] = [
                'product_id' => $product->id,
                'quantity' => $qty,
                'price' => $price,
                'subtotal' => $subtotal,
                'rental_days' => $rentalDays,
            ];
        }

        // Create order
        $order = Order::create([
            'user_id' => auth()->id(),
            'order_code' => 'ORD-' . strtoupper(Str::random(8)) . '-' . time(),
            'total_price' => $total,
            'status' => 'process',
            'payment_method' => $request->payment_method,
            'address' => $request->address,
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
