<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\Schema;

class HomeController extends Controller
{
    public function index()
    {
        $categories = Category::with('products')->get();

        // Aman sebelum dan sesudah migration — cek dulu apakah kolom sale_type ada
        if (Schema::hasColumn('products', 'sale_type')) {
            $flashSales = Product::where('sale_type', 'flash_sale')->get();
            $lunarSales = Product::where('sale_type', 'lunar_day')->get();
        }
        else {
            // Fallback sebelum migration: pakai discount_price untuk kedua section
            $flashSales = Product::whereNotNull('discount_price')->get();
            $lunarSales = collect(); // kosong sampai migration dijalankan
        }

        $newReleases = Product::where('is_new', true)->paginate(10, ['*'], 'nr_page');
        $allProducts = Product::paginate(12, ['*'], 'page');

        return view('home.index', compact('categories', 'flashSales', 'lunarSales', 'newReleases', 'allProducts'));
    }
}
