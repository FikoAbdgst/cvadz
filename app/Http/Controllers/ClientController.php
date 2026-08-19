<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class ClientController extends Controller
{
    public function index(): View
    {
        $clients = config('clients.clients');
        $references = config('clients.references');

        $cityProvince = [
            'Sentul' => 'Jawa Barat', 'Bogor' => 'Jawa Barat', 'Bandung' => 'Jawa Barat',
            'Sumedang' => 'Jawa Barat', 'Tasikmalaya' => 'Jawa Barat', 'Cianjur' => 'Jawa Barat',
            'Garut' => 'Jawa Barat', 'Cirebon' => 'Jawa Barat', 'Bekasi' => 'Jawa Barat',
            'Cikarang' => 'Jawa Barat',
            'Tangerang' => 'Banten',
            'Medan' => 'Sumatera Utara', 'Binjai' => 'Sumatera Utara',
            'Pacitan' => 'Jawa Timur', 'Gresik' => 'Jawa Timur', 'Surabaya' => 'Jawa Timur',
            'Sidoarjo' => 'Jawa Timur',
            'Jepara' => 'Jawa Tengah',
            'Makassar' => 'Sulawesi Selatan',
            'Lampung' => 'Lampung',
            'Papua' => 'Papua',
        ];

        $locations = collect($clients)->pluck('location')->map(fn ($loc) => array_map('trim', explode(',', $loc))[0]);
        $uniqueCities = $locations->unique()->count();
        $uniqueProvinces = $locations->map(fn ($city) => $cityProvince[$city] ?? $city)->unique()->count();

        $stats = [
            'units' => count($clients),
            'cities' => $uniqueCities,
            'provinces' => $uniqueProvinces,
        ];

        $categoryMap = [
            'Rotary Dryer' => fn ($m) => str_starts_with($m, 'Rotary Dryer'),
            'Hammer Mill' => fn ($m) => str_starts_with($m, 'Hammer Mill'),
            'Mesin Pelet' => fn ($m) => str_contains($m, 'Pelet') || str_contains($m, 'Woodpellet'),
            'Panel & Kelistrikan' => fn ($m) => str_contains($m, 'Panel') || str_contains($m, 'Instalasi Listrik') || str_contains($m, 'Sperpart Listrik'),
            'Boiler' => fn ($m) => str_starts_with($m, 'Boiler'),
        ];

        $categoryCounts = collect($categoryMap)->map(fn ($match) => collect($clients)->filter(fn ($c) => $match($c['machine']))->count())
            ->filter()
            ->sortByDesc(fn ($v) => $v);

        $otherCount = count($clients) - $categoryCounts->sum();
        if ($otherCount > 0) {
            $categoryCounts['Lainnya'] = $otherCount;
        }

        return view('clients.index', compact('clients', 'references', 'stats', 'categoryCounts'));
    }
}
