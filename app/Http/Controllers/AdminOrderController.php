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
     * Accept an order (change status to finished, deduct stock).
     */
    public function accept(Request $request, $id)
    {
        $order = Order::with('orderItems.product')->findOrFail($id);

        if ($order->status !== 'process') {
            return back()->withErrors(['error' => 'Pesanan tidak valid untuk diterima.']);
        }

        // Deduct stock for each item in the order
        foreach ($order->orderItems as $item) {
            if ($item->product) {
                $item->product->decrement('stock', $item->quantity);
            }
        }

        $order->update(['status' => 'finished']);

        return redirect()->route('admin.orders.index')->with('success', 'Pesanan berhasil diterima dan stok diperbarui.');
    }

    /**
     * Reject an order (change status to rejected).
     * Stock is NOT returned because it was never deducted (deduction only happens on accept).
     */
    public function reject(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        if ($order->status !== 'process') {
            return back()->withErrors(['error' => 'Pesanan tidak valid untuk ditolak.']);
        }

        $order->update(['status' => 'rejected']);

        return redirect()->route('admin.orders.index')->with('success', 'Pesanan berhasil ditolak.');
    }
}
