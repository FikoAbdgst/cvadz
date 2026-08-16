<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Service;
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

        $categories = Category::withCount('products')->where('type', 'produk')->orderBy('name')->get();

        $services = Service::orderBy('is_featured', 'desc')->orderBy('name')->take(4)->get();

        return view('landing', [
            'featuredProducts' => $featuredProducts,
            'categories' => $categories,
            'services' => $services,
            'whatsappLink' => WhatsApp::link('Halo, saya ingin berkonsultasi tentang mesin industri yang dijual CV Adzra Engineering.'),
        ]);
    }
}
