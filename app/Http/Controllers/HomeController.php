<?php

namespace App\Http\Controllers;

use App\Models\Bundle;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('products')->get();

        $heroProducts = Product::where('status', 'ready')
            ->with('category')
            ->latest()
            ->take(10)
            ->get();

        return view('home', compact('categories', 'heroProducts'));
    }

    public function menu(Request $request)
    {
        $search = $request->query('q');
        $categorySlug = $request->query('category');

        $products = Product::query()
            ->where('status', 'ready')
            ->with('category')
            ->when($search, fn ($q) => $q->where('name', 'like', "%{$search}%"))
            ->when($categorySlug, fn ($q) => $q->whereHas('category', fn ($c) => $c->where('slug', $categorySlug)))
            ->latest()
            ->get();

        $categories = Category::withCount('products')->get();

        return view('menu', compact('products', 'categories', 'search', 'categorySlug'));
    }

    public function paket()
    {
        $bundles = Bundle::with('items.product')
            ->where('status', 'ready')
            ->latest()
            ->get();

        return view('paket', compact('bundles'));
    }

    public function show(Product $product)
    {
        abort_unless($product->isReady(), 404);

        $related = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('status', 'ready')
            ->take(4)
            ->get();

        return view('product-detail', compact('product', 'related'));
    }

    public function showBundle(Bundle $bundle)
    {
        abort_unless($bundle->isReady(), 404);

        $bundle->load('items.product');

        $related = Bundle::where('id', '!=', $bundle->id)
            ->where('status', 'ready')
            ->with('items.product')
            ->take(4)
            ->get();

        return view('bundle-detail', compact('bundle', 'related'));
    }
}
