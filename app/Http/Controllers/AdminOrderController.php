<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class AdminOrderController extends Controller
{
    /**
     * Tampilkan daftar pesanan aktif (process) beserta info SLA.
     */
    public function index()
    {
        $orders = Order::with(['user', 'orderItems.product'])
            ->where('status', 'process')
            // Sembunyikan pesanan yang SLA-nya sudah habis
            // (meski command belum dijalankan, admin tidak akan melihatnya)
            ->where(function ($q) {
                $q->whereNull('sla_deadline')
                  ->orWhere('sla_deadline', '>', now());
            })
            ->latest()
            ->paginate(15);

        return view('admin.orders.index', compact('orders'));
    }

    /**
     * Terima pesanan — ubah status ke 'finished'.
     *
     * CATATAN: Stok sudah dikurangi saat checkout (CheckoutController::store).
     * Di sini TIDAK melakukan pengurangan stok lagi untuk menghindari double-deduct.
     */
    public function accept(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        if ($order->status !== 'process') {
            return back()->withErrors(['error' => 'Pesanan tidak valid untuk diterima.']);
        }

        $order->update(['status' => 'finished']);

        return redirect()
            ->route('admin.orders.index')
            ->with('success', 'Pesanan berhasil diterima.');
    }

    /**
     * Tolak pesanan — ubah status ke 'rejected' dan KEMBALIKAN stok.
     *
     * Stok dikembalikan karena sudah dikurangi saat checkout.
     * Produk kostum (rental) tidak punya stok, jadi dilewati.
     */
    public function reject(Request $request, $id)
    {
        $order = Order::with('orderItems.product')->findOrFail($id);

        if ($order->status !== 'process') {
            return back()->withErrors(['error' => 'Pesanan tidak valid untuk ditolak.']);
        }

        // Kembalikan stok untuk produk biasa (bukan kostum/rental)
        foreach ($order->orderItems as $item) {
            if ($item->product && is_null($item->rental_days)) {
                $item->product->increment('stock', $item->quantity);
            }
        }

        $order->update(['status' => 'rejected']);

        return redirect()
            ->route('admin.orders.index')
            ->with('success', 'Pesanan ditolak dan stok produk telah dikembalikan.');
    }
}
