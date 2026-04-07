<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\Schema;

class HomeController extends Controller
{
    public function index()
    {
        $categories = Category::with('productsDirect')->get();

        // Flash Sale: 4 newest products with any discount
        $flashSales = Product::whereNotNull('discount_price')
            ->where('stock', '>', 0)
            ->latest()
            ->limit(4)
            ->get();

        // Lunar Day Special: 4 most-discounted products (biggest % off)
        $lunarSales = Product::whereNotNull('discount_price')
            ->where('stock', '>', 0)
            ->whereRaw('discount_price < price')
            ->orderByRaw('(price - discount_price) / price DESC')
            ->limit(4)
            ->get();

        $newReleases = Product::where('is_new', true)->paginate(10, ['*'], 'nr_page');
        $allProducts = Product::paginate(12, ['*'], 'page');

        return view('home.index', compact('categories', 'flashSales', 'lunarSales', 'newReleases', 'allProducts'));
    }
}
