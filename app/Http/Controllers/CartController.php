<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    /**
     * Tampilkan isi keranjang belanja dari database.
     * Hanya bisa diakses user yang sudah login (via middleware 'auth' di routes).
     */
    public function index()
    {
        $cartItems = [];
        $total     = 0;

        /** @var \Illuminate\Database\Eloquent\Collection<int, CartItem> $dbItems */
        $dbItems = CartItem::where('user_id', Auth::id())
            ->with(['product.category'])
            ->get();

        foreach ($dbItems as $dbItem) {
            $product = $dbItem->product;

            if (!$product) {
                // Produk sudah dihapus dari katalog — hapus dari cart juga
                $dbItem->delete();
                continue;
            }

            $isCostume  = $product->isCostume();
            $rentalDays = $dbItem->rental_days ?? 1;

            if ($isCostume) {
                $activePrice = $product->discount_price ?? $product->price_per_day ?? $product->price;
                $subtotal    = $activePrice * $rentalDays;
            } else {
                $subtotal = $product->effective_price * $dbItem->quantity;
            }

            $cartItems[] = [
                'product'     => $product,
                'quantity'    => $dbItem->quantity,
                'rental_days' => $rentalDays,
                'subtotal'    => $subtotal,
                'is_costume'  => $isCostume,
            ];

            $total += $subtotal;
        }

        return view('cart.index', compact('cartItems', 'total'));
    }

    /**
     * Tambahkan produk ke keranjang (tersimpan di database).
     */
    public function add(Request $request)
    {
        $request->validate([
            'product_id'  => 'required|exists:products,id',
            'quantity'    => 'required|integer|min:1',
            'rental_days' => 'nullable|integer|min:1|max:7',
        ]);

        $productId  = $request->product_id;
        $quantity   = (int) $request->quantity;
        $rentalDays = (int) ($request->rental_days ?? 1);

        $existing = CartItem::where('user_id', Auth::id())
            ->where('product_id', $productId)
            ->first();

        if ($existing) {
            // Produk sudah ada → tambah quantity
            $existing->update([
                'quantity'    => $existing->quantity + $quantity,
                'rental_days' => $rentalDays,
            ]);
        } else {
            CartItem::create([
                'user_id'     => Auth::id(),
                'product_id'  => $productId,
                'quantity'    => $quantity,
                'rental_days' => $rentalDays,
            ]);
        }

        return redirect()->route('cart')->with('success', 'Produk berhasil ditambahkan ke keranjang!');
    }

    /**
     * Hapus produk dari keranjang.
     */
    public function remove(Request $request)
    {
        $request->validate([
            'product_id' => 'required',
        ]);

        CartItem::where('user_id', Auth::id())
            ->where('product_id', $request->product_id)
            ->delete();

        return redirect()->route('cart')->with('success', 'Produk dihapus dari keranjang.');
    }

    /**
     * Update kuantitas / rental days item di keranjang.
     */
    public function updateQuantity(Request $request)
    {
        $request->validate([
            'product_id'  => 'required|exists:products,id',
            'quantity'    => 'required|integer|min:1',
            'rental_days' => 'nullable|integer|min:1|max:7',
        ]);

        $updateData = ['quantity' => (int) $request->quantity];

        if ($request->filled('rental_days')) {
            $updateData['rental_days'] = (int) $request->rental_days;
        }

        CartItem::where('user_id', Auth::id())
            ->where('product_id', $request->product_id)
            ->update($updateData);

        return redirect()->route('cart');
    }
}
