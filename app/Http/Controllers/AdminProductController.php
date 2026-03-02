<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AdminProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->latest()->paginate(10);
        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'sale_type' => 'required|in:none,flash_sale,lunar_day',
            'price_per_day' => 'nullable|integer|min:0',
            'category_id' => 'nullable|exists:categories,id',
        ]);

        $hasDiscount = $request->input('has_discount') === 'yes';

        // price selalu diisi dari field Harga Produk
        $price = $request->input('price');
        $discountPrice = null;

        if ($hasDiscount) {
            $discountPrice = $request->input('discount_price');
            if (!$price && !$request->filled('price_per_day')) {
                return back()->withErrors(['price' => 'Harga produk wajib diisi sebagai patokan harga asli.'])->withInput();
            }
            if (!$discountPrice) {
                return back()->withErrors(['discount_price' => 'Harga diskon wajib diisi jika ada diskon.'])->withInput();
            }
        }
        else {
            if (!$price && !$request->filled('price_per_day')) {
                return back()->withErrors(['price' => 'Harga produk wajib diisi.'])->withInput();
            }
        }

        $imagePath = 'images/placeholder-product.png';
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('products', 'public');
            $imagePath = 'storage/' . $path;
        }

        // Untuk kategori costume/cosplay: price_per_day = harga asli produk
        $pricePerDay = $request->input('price_per_day');
        if ($pricePerDay && !$price) {
            $price = $pricePerDay;
        }

        $product = Product::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'price' => $price ?? 0,
            'discount_price' => $discountPrice,
            'stock' => $request->stock,
            'image' => $imagePath,
            'is_new' => true,
            'sale_type' => $request->sale_type,
            'price_per_day' => $request->price_per_day,
            'category_id' => $request->category_id,
        ]);

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil ditambahkan!');
    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'sale_type' => 'required|in:none,flash_sale,lunar_day',
            'price_per_day' => 'nullable|integer|min:0',
            'category_id' => 'nullable|exists:categories,id',
        ]);

        $hasDiscount = $request->input('has_discount') === 'yes';

        // price selalu diisi dari field Harga Produk
        $price = $request->input('price');
        $discountPrice = null;

        if ($hasDiscount) {
            $discountPrice = $request->input('discount_price');
            if (!$price && !$request->filled('price_per_day')) {
                return back()->withErrors(['price' => 'Harga produk wajib diisi sebagai patokan harga asli.'])->withInput();
            }
            if (!$discountPrice) {
                return back()->withErrors(['discount_price' => 'Harga diskon wajib diisi jika ada diskon.'])->withInput();
            }
        }
        else {
            if (!$price && !$request->filled('price_per_day')) {
                return back()->withErrors(['price' => 'Harga produk wajib diisi.'])->withInput();
            }
        }

        $imagePath = $product->image;
        if ($request->hasFile('image')) {
            if ($product->image && $product->image !== 'images/placeholder-product.png') {
                $oldPath = str_replace('storage/', '', $product->image);
                Storage::disk('public')->delete($oldPath);
            }
            $path = $request->file('image')->store('products', 'public');
            $imagePath = 'storage/' . $path;
        }

        // Untuk kategori costume/cosplay: price_per_day = harga asli produk
        $pricePerDay = $request->input('price_per_day');
        if ($pricePerDay && !$price) {
            $price = $pricePerDay;
        }

        $product->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'price' => $price ?? $product->price,
            'discount_price' => $discountPrice,
            'stock' => $request->stock,
            'image' => $imagePath,
            'sale_type' => $request->sale_type,
            'price_per_day' => $request->price_per_day,
            'category_id' => $request->category_id,
        ]);

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil diupdate!');
    }

    public function destroy(Product $product)
    {
        if ($product->image && $product->image !== 'images/placeholder-product.png') {
            $oldPath = str_replace('storage/', '', $product->image);
            Storage::disk('public')->delete($oldPath);
        }

        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil dihapus.');
    }
}
