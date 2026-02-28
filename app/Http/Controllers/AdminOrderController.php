<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class AdminOrderController extends Controller
{
    /**
     * Display a listing of incoming orders (Items).
     */
    public function index()
    {
        // Fetch order items that belong to an order with status 'process'
        $orders = Order::with(['user', 'orderItems.product'])
            ->where('status', 'process')
            ->latest()
            ->paginate(15);

        return view('admin.orders.index', compact('orders'));
    }

    /**
     * Accept an order (change status to finished).
     */
    public function accept(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        if ($order->status !== 'process') {
            return back()->withErrors(['error' => 'Pesanan tidak valid untuk diterima.']);
        }

        $order->update(['status' => 'finished']);

        return redirect()->route('admin.orders.index')->with('success', 'Pesanan berhasil diterima dan diselesaikan.');
    }

    /**
     * Reject an order (change status to rejected, refund stock).
     */
    public function reject(Request $request, $id)
    {
        $order = Order::with('orderItems.product')->findOrFail($id);

        if ($order->status !== 'process') {
            return back()->withErrors(['error' => 'Pesanan tidak valid untuk ditolak.']);
        }

        // Refund stock for each item in the order
        foreach ($order->orderItems as $item) {
            if ($item->product) {
                // Return ordered quantity back to inventory
                $item->product->increment('stock', $item->quantity);
            }
        }

        $order->update(['status' => 'rejected']);

        return redirect()->route('admin.orders.index')->with('success', 'Pesanan berhasil ditolak dan stok dikembalikan.');
    }
}
