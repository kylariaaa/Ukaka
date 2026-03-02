<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of products with search, filter, and sorting.
     */
    public function index(Request $request)
    {
        $query = Product::with('categories')->where('stock', '>', 0);

        // Search by name or description
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function (\Illuminate\Database\Eloquent\Builder $q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%")
                    ->orWhere('description', 'like', "%{$searchTerm}%");
            });
        }

        // Filter by category slug
        if ($request->filled('category')) {
            $query->whereHas('categories', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        // Sorting
        $sort = $request->get('sort', 'newest');
        switch ($sort) {
            case 'price_asc':
                // We order by price taking discount into account
                $query->orderByRaw('COALESCE(discount_price, price) ASC');
                break;
            case 'price_desc':
                $query->orderByRaw('COALESCE(discount_price, price) DESC');
                break;
            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('name', 'desc');
                break;
            case 'newest':
            default:
                $query->latest();
                break;
        }

        $products = $query->paginate(12)->withQueryString();
        $categories = Category::all();

        $pageTitle = 'Semua Produk';
        if ($request->filled('search')) {
            $pageTitle = 'Hasil Pencarian: "' . $request->search . '"';
        }
        elseif ($request->filled('category')) {
            $cat = Category::where('slug', $request->category)->first();
            $pageTitle = $cat ? 'Kategori: ' . $cat->name : 'Semua Produk';
        }

        return view('products.index', compact('products', 'categories', 'pageTitle'));
    }

    /**
     * Display the Flash Sale / Lunar Day products.
     */
    public function flashSale(Request $request)
    {
        $query = Product::with('categories')
            ->whereNotNull('discount_price')
            ->where('stock', '>', 0);

        // Sorting
        $sort = $request->get('sort', 'newest');
        switch ($sort) {
            case 'price_asc':
                $query->orderByRaw('COALESCE(discount_price, price) ASC');
                break;
            case 'price_desc':
                $query->orderByRaw('COALESCE(discount_price, price) DESC');
                break;
            case 'newest':
            default:
                $query->latest();
                break;
        }

        $products = $query->paginate(12)->withQueryString();
        $categories = Category::all();
        $pageTitle = 'Flash Sale - Special Lunar Day';

        return view('products.index', compact('products', 'categories', 'pageTitle'));
    }

    /**
     * Display New Arrivals.
     */
    public function newArrivals(Request $request)
    {
        $query = Product::with('categories')
            ->where('is_new', true)
            ->where('stock', '>', 0);

        // Sorting
        $sort = $request->get('sort', 'newest');
        switch ($sort) {
            case 'price_asc':
                $query->orderByRaw('COALESCE(discount_price, price) ASC');
                break;
            case 'price_desc':
                $query->orderByRaw('COALESCE(discount_price, price) DESC');
                break;
            case 'newest':
            default:
                $query->latest();
                break;
        }

        $products = $query->paginate(12)->withQueryString();
        $categories = Category::all();
        $pageTitle = 'New Arrivals 2025';

        return view('products.index', compact('products', 'categories', 'pageTitle'));
    }

    /**
     * Display the specified product.
     */
    public function show(Product $product)
    {
        return view('products.show', compact('product'));
    }

    /**
     * Display all products for a given category slug.
     */
    public function byCategory(Request $request, Category $category)
    {
        $query = Product::where('category_id', $category->id)
            ->where('stock', '>', 0);

        $sort = $request->get('sort', 'newest');
        switch ($sort) {
            case 'price_asc':
                $query->orderByRaw('COALESCE(discount_price, price) ASC');
                break;
            case 'price_desc':
                $query->orderByRaw('COALESCE(discount_price, price) DESC');
                break;
            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('name', 'desc');
                break;
            case 'newest':
            default:
                $query->latest();
                break;
        }

        $products = $query->paginate(12)->withQueryString();
        $categories = Category::all();
        $pageTitle = 'Kategori: ' . $category->name;

        return view('products.index', compact('products', 'categories', 'pageTitle'));
    }
}
