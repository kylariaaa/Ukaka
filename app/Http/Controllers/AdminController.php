<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Display the Admin Dashboard.
     */
    public function index()
    {
        // For now, assume orders table has a string 'status' column. 
        // We'll just aggregate these values.

        // Ensure Orders table exists to query against it.
        $completedOrdersCount = Order::where('status', 'finished')->count();
        $enteredOrdersCount = Order::where('status', 'process')->count();

        $totalStock = Product::sum('stock');

        return view('admin.dashboard', compact('completedOrdersCount', 'enteredOrdersCount', 'totalStock'));
    }
}
