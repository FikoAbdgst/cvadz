<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Support\WhatsApp;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $featuredProducts = Product::with(['category', 'images'])
            ->where('is_featured', true)
            ->latest()
            ->take(6)
            ->get();

        $categories = Category::withCount('products')->orderBy('name')->get();

        return view('landing', [
            'featuredProducts' => $featuredProducts,
            'categories' => $categories,
            'whatsappLink' => WhatsApp::link('Halo, saya ingin berkonsultasi tentang mesin industri yang dijual CV Adzra Engineering.'),
        ]);
    }
}
