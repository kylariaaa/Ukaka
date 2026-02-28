<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;

class HomeController extends Controller
{
    public function index()
    {
        $categories = Category::with('products')->get();
        $flashSales = Product::whereNotNull('discount_price')->get();
        $newReleases = Product::where('is_new', true)->paginate(10, ['*'], 'nr_page');
        $allProducts = Product::paginate(12, ['*'], 'page');

        return view('home.index', compact('categories', 'flashSales', 'newReleases', 'allProducts'));
    }
}
