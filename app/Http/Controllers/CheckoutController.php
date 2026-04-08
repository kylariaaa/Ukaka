<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    /**
     * Tampilkan halaman checkout dengan total dan form alamat.
     */
    public function create()
    {
        $cart = $this->getCartFromDb();

        if (empty($cart)) {
            return redirect()->route('cart')->with('error', 'Keranjang belanja kosong!');
        }

        $total = $this->calculateTotal($cart);

        return view('checkout.create', compact('total'));
    }

    /**
     * Proses checkout: validasi stok, buat order, kurangi stok, kosongkan cart.
     * Dibungkus DB::transaction + lockForUpdate untuk mencegah race condition.
     */
    public function store(Request $request)
    {
        $request->validate([
            'payment_method' => 'required|string|in:paypal,dana,bca,mandiri',
            'address'        => 'required|string|min:10',
        ]);

        $cart = $this->getCartFromDb();

        if (empty($cart)) {
            return redirect()->route('cart')->with('error', 'Keranjang belanja kosong!');
        }

        try {
            $order = DB::transaction(function () use ($cart, $request) {
                $total      = 0;
                $orderItems = [];

                // ── Pass 1: Validasi & lock stok (cegah race condition) ──────────
                foreach ($cart as $productId => $item) {
                    /** @var Product $product */
                    $product = Product::with('category')->lockForUpdate()->find($productId);

                    if (!$product) {
                        throw new \Exception("Produk tidak ditemukan (ID: {$productId}).");
                    }

                    $isCostume  = $product->isCostume();
                    $rentalDays = $item['rental_days'] ?? 1;

                    if ($isCostume) {
                        $price    = $product->discount_price ?? $product->price_per_day ?? $product->price;
                        $qty      = 1;
                        $subtotal = $price * $rentalDays;
                    } else {
                        $qty = $item['quantity'];

                        if ($product->stock < $qty) {
                            throw new \Exception(
                                "Stok produk \"{$product->name}\" tidak mencukupi. " .
                                "Tersedia: {$product->stock}, diminta: {$qty}."
                            );
                        }

                        $price      = $product->effective_price;
                        $subtotal   = $price * $qty;
                        $rentalDays = null;
                    }

                    $total += $subtotal;

                    $orderItems[] = [
                        'product'     => $product,
                        'product_id'  => $product->id,
                        'quantity'    => $qty,
                        'price'       => $price,
                        'subtotal'    => $subtotal,
                        'rental_days' => $rentalDays,
                        'is_costume'  => $isCostume,
                    ];
                }

                // ── Buat Order ────────────────────────────────────────────────────
                $order = Order::create([
                    'user_id'        => Auth::id(),
                    'order_code'     => 'ORD-' . strtoupper(Str::random(8)) . '-' . time(),
                    'total_price'    => $total,
                    'status'         => 'process',
                    'payment_method' => $request->payment_method,
                    'address'        => $request->address,
                    'sla_deadline'   => now()->addSeconds(20),
                ]);

                // ── Buat Order Items & Kurangi Stok ──────────────────────────────
                foreach ($orderItems as $item) {
                    OrderItem::create([
                        'order_id'    => $order->id,
                        'product_id'  => $item['product_id'],
                        'quantity'    => $item['quantity'],
                        'price'       => $item['price'],
                        'subtotal'    => $item['subtotal'],
                        'rental_days' => $item['rental_days'],
                    ]);

                    if (!$item['is_costume']) {
                        Product::where('id', $item['product_id'])
                            ->decrement('stock', $item['quantity']);
                    }
                }

                // ── Kosongkan Cart dari DB ────────────────────────────────────────
                CartItem::where('user_id', Auth::id())->delete();

                return $order;
            });

            return redirect()
                ->route('payment-history')
                ->with('success', 'Pesanan berhasil! Kode order: ' . $order->order_code);

        } catch (\Exception $e) {
            return redirect()
                ->route('cart')
                ->with('error', $e->getMessage());
        }
    }

    // ─────────────────────────────────────────────────────────────────────────

    /**
     * Ambil cart user yang sedang login dari database.
     * Return format: [ product_id => ['quantity', 'rental_days'], ... ]
     */
    private function getCartFromDb(): array
    {
        $items = CartItem::where('user_id', Auth::id())->get();
        $cart  = [];

        foreach ($items as $item) {
            $cart[$item->product_id] = [
                'quantity'    => $item->quantity,
                'rental_days' => $item->rental_days,
            ];
        }

        return $cart;
    }

    /**
     * Hitung grand total dari array cart (digunakan di create()).
     */
    private function calculateTotal(array $cart): int
    {
        $total = 0;

        foreach ($cart as $productId => $item) {
            $product = Product::with('category')->find($productId);
            if (!$product) {
                continue;
            }

            if ($product->isCostume()) {
                $rentalDays  = $item['rental_days'] ?? 1;
                $activePrice = $product->discount_price ?? $product->price_per_day ?? $product->price;
                $total      += $activePrice * $rentalDays;
            } else {
                $total += $product->effective_price * $item['quantity'];
            }
        }

        return $total;
    }
}
