<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Support\WhatsApp;
use Illuminate\View\View;

class ServiceController extends Controller
{
    public function index(): View
    {
        $services = Service::with('category')->orderBy('is_featured', 'desc')->orderBy('name')->get();

        return view('services.index', [
            'services' => $services,
            'whatsappLinks' => $services->mapWithKeys(fn (Service $service) => [
                $service->id => WhatsApp::link($service->whatsappMessage()),
            ]),
        ]);
    }
}
