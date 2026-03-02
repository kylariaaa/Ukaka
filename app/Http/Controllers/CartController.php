<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cart = session('cart', []);

        $cartItems = [];
        foreach ($cart as $productId => $item) {
            $product = Product::with('category')->find($productId);
            if ($product) {
                $isCostume = $product->isCostume();
                $rentalDays = $item['rental_days'] ?? 1;

                if ($isCostume) {
                    // Kostum: multiply active price × rental days
                    $activePrice = $product->discount_price ?? $product->price_per_day ?? $product->price;
                    $subtotal = $activePrice * $rentalDays;
                }
                else {
                    $subtotal = $product->effective_price * $item['quantity'];
                }

                $cartItems[] = [
                    'product' => $product,
                    'quantity' => $item['quantity'],
                    'rental_days' => $rentalDays,
                    'subtotal' => $subtotal,
                    'is_costume' => $isCostume,
                ];
            }
        }

        $total = array_sum(array_column($cartItems, 'subtotal'));

        return view('cart.index', compact('cartItems', 'total'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'rental_days' => 'nullable|integer|min:1|max:7',
        ]);

        $productId = $request->product_id;
        $quantity = $request->quantity;
        $rentalDays = $request->rental_days ?? 1;

        $cart = session('cart', []);

        if (isset($cart[$productId])) {
            $cart[$productId]['quantity'] += $quantity;
            $cart[$productId]['rental_days'] = $rentalDays;
        }
        else {
            $cart[$productId] = [
                'quantity' => $quantity,
                'rental_days' => $rentalDays,
            ];
        }

        session(['cart' => $cart]);

        return redirect()->route('cart')->with('success', 'Produk berhasil ditambahkan ke keranjang!');
    }

    public function remove(Request $request)
    {
        $request->validate([
            'product_id' => 'required',
        ]);

        $cart = session('cart', []);
        unset($cart[$request->product_id]);
        session(['cart' => $cart]);

        return redirect()->route('cart')->with('success', 'Produk dihapus dari keranjang.');
    }

    public function updateQuantity(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'rental_days' => 'nullable|integer|min:1|max:7',
        ]);

        $cart = session('cart', []);
        if (isset($cart[$request->product_id])) {
            $cart[$request->product_id]['quantity'] = $request->quantity;
            if ($request->filled('rental_days')) {
                $cart[$request->product_id]['rental_days'] = $request->rental_days;
            }
            session(['cart' => $cart]);
        }

        return redirect()->route('cart');
    }
}
