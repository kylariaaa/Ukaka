<?php

namespace App\Http\Controllers;

class PaymentHistoryController extends Controller
{
    public function index()
    {
        $orders = auth()->user()
            ->orders()
            ->with(['orderItems.product'])
            ->latest()
            ->get();

        return view('payment-history.index', compact('orders'));
    }
}
