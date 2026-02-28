<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminProductController extends Controller
{
    /**
     * Show the form for creating a new product.
     */
    public function create()
    {
        $categories = Category::all();
        return view('admin.products.create', compact('categories'));
    }

    /**
     * Store a newly created product in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $hasDiscount = $request->input('has_discount') === 'yes';

        // Base price
        $price = $request->input('price');
        $discountPrice = null;

        if ($hasDiscount) {
            $price = $request->input('initial_price');
            $discountPrice = $request->input('discount_price');
            if (!$price || !$discountPrice) {
                return back()->withErrors(['error' => 'Initial price and discount price are required if there is a discount.'])->withInput();
            }
        }
        else {
            if (!$price) {
                return back()->withErrors(['price' => 'Price is required.'])->withInput();
            }
        }

        $imagePath = 'images/placeholder-product.png';
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('products', 'public');
            $imagePath = 'storage/' . $path;
        }

        $product = Product::create([
            'name' => $request->name,
            'slug' => \Str::slug($request->name),
            'description' => $request->description,
            'price' => $price,
            'discount_price' => $discountPrice,
            'stock' => $request->stock,
            'image' => $imagePath,
            'is_new' => true, // By default newly added products are 'new arrivals'
        ]);

        if ($request->filled('category_id')) {
            $product->categories()->attach($request->category_id);
        }

        return redirect()->route('admin.dashboard')->with('success', 'Produk berhasil ditambahkan!');
    }
}
