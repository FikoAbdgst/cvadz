<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Support\WhatsApp;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(Request $request): View
    {
        $query = Product::query()->with(['category', 'images']);

        if ($search = $request->input('q')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($category = $request->input('category')) {
            $query->whereHas('category', fn ($q) => $q->where('slug', $category));
        }

        $products = $query->latest()->paginate(9)->withQueryString();

        $categories = Category::where('type', 'produk')->orderBy('name')->get();

        return view('products.index', [
            'products' => $products,
            'categories' => $categories,
            'activeCategory' => $request->input('category'),
            'search' => $request->input('q'),
        ]);
    }

    public function show(string $slug): View
    {
        $product = Product::with(['category', 'images', 'specifications', 'videos'])
            ->where('slug', $slug)
            ->firstOrFail();

        $relatedProducts = Product::with('images')
            ->where('category_id', $product->category_id)
            ->whereKeyNot($product->id)
            ->latest()
            ->take(3)
            ->get();

        return view('products.show', [
            'product' => $product,
            'relatedProducts' => $relatedProducts,
            'whatsappLink' => WhatsApp::link($product->whatsappMessage()),
        ]);
    }
}
