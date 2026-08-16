<?php

namespace Database\Seeders;

use App\Enums\OrderStatus;
use App\Enums\TransactionStatus;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DemoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'rotary-dryer' => ['Rotary Dryer', true],
            'wood-pellet' => ['Mesin Wood Pellet', true],
            'pelet' => ['Mesin Pelet', true],
            'lainnya' => ['Mesin Industri Lainnya', false],
        ];

        $categoryIds = [];

        foreach ($categories as $slug => [$name]) {
            $categoryIds[$slug] = Category::create(['name' => $name, 'slug' => $slug])->id;
        }

        $products = [
            [
                'category' => 'rotary-dryer',
                'name' => 'Rotary Dryer Kapasitas 1 Ton',
                'price' => 185000000,
                'featured' => true,
                'description' => 'Mesin pengering rotary dryer untuk mengeringkan bahan baku seperti kayu, sekam, ampas, dan material lainnya secara kontinyu. Kapasitas 1 ton per jam dengan sistem pemanas gas/bbm.',
                'specs' => [
                    ['Kapasitas', '1 ton/jam'],
                    ['Bahan Body', 'Plate SS400 / Stainless Steel'],
                    ['Sistem Pemanas', 'Gas / BBM'],
                    ['Motor Penggerak', '7.5 HP'],
                    ['Dimensi', '800 x 160 x 250 cm'],
                ],
            ],
            [
                'category' => 'rotary-dryer',
                'name' => 'Rotary Dryer Kapasitas 500 Kg',
                'price' => 125000000,
                'featured' => false,
                'description' => 'Rotary dryer skala menengah dengan kapasitas 500 kg per jam, cocok untuk usaha kecil menengah pengolahan bahan kering.',
                'specs' => [
                    ['Kapasitas', '500 kg/jam'],
                    ['Bahan Body', 'Plate SS400'],
                    ['Motor Penggerak', '5.5 HP'],
                    ['Dimensi', '650 x 140 x 220 cm'],
                ],
            ],
            [
                'category' => 'wood-pellet',
                'name' => 'Mesin Cetak Wood Pellet 200-300 Kg',
                'price' => 95000000,
                'featured' => true,
                'description' => 'Mesin pencetak wood pellet kapasitas 200–300 kg per jam. Ideal untuk produksi pelet kayu bahan bakar maupun pakan ternak.',
                'specs' => [
                    ['Kapasitas', '200–300 kg/jam'],
                    ['Diameter Pelet', '6–8 mm'],
                    ['Penggerak', 'Diesel / Electromotor'],
                    ['Dimensi', '120 x 70 x 160 cm'],
                ],
            ],
            [
                'category' => 'wood-pellet',
                'name' => 'Mesin Cetak Wood Pellet 1 Ton',
                'price' => 320000000,
                'featured' => false,
                'description' => 'Mesin cetak wood pellet kapasitas besar 1 ton per jam untuk industri produksi pelet skala pabrik.',
                'specs' => [
                    ['Kapasitas', '1 ton/jam'],
                    ['Diameter Pelet', '6–10 mm'],
                    ['Penggerak', '50 HP'],
                    ['Dimensi', '250 x 120 x 210 cm'],
                ],
            ],
            [
                'category' => 'pelet',
                'name' => 'Mesin Cetak Pelet Pakan Ternak',
                'price' => 65000000,
                'featured' => true,
                'description' => 'Mesin pencetak pelet pakan ternak kapasitas 100–150 kg per jam. Bisa untuk pakan ikan, ayam, dan ternak lainnya.',
                'specs' => [
                    ['Kapasitas', '100–150 kg/jam'],
                    ['Diameter Pelet', '2–6 mm'],
                    ['Penggerak', 'Diesel / Electromotor 15 HP'],
                    ['Dimensi', '90 x 60 x 130 cm'],
                ],
            ],
            [
                'category' => 'pelet',
                'name' => 'Mesin Pelet Apung',
                'price' => 78000000,
                'featured' => false,
                'description' => 'Mesin pelet apung untuk pakan ikan. Menghasilkan pelet yang mengapung di permukaan air sehingga pakan tidak tenggelam.',
                'specs' => [
                    ['Kapasitas', '150 kg/jam'],
                    ['Diameter Pelet', '3–6 mm'],
                    ['Sistem', 'Extruder apung'],
                    ['Penggerak', 'Electromotor 20 HP'],
                ],
            ],
            [
                'category' => 'lainnya',
                'name' => 'Hammer Mill Penghancur Bahan',
                'price' => 45000000,
                'featured' => false,
                'description' => 'Mesin hammer mill untuk menghancurkan bahan baku seperti kayu, sekam, dan limbah pertanian menjadi serbuk halus.',
                'specs' => [
                    ['Kapasitas', '500 kg/jam'],
                    ['Saringan', 'Diameter 2–10 mm'],
                    ['Penggerak', 'Electromotor 22 HP'],
                    ['Dimensi', '110 x 80 x 140 cm'],
                ],
            ],
            [
                'category' => 'lainnya',
                'name' => 'Mesin Conveyor Sabuk',
                'price' => 28000000,
                'featured' => false,
                'description' => 'Conveyor sabuk custom untuk memindahkan material dalam proses produksi. Panjang dan lebar dapat disesuaikan kebutuhan.',
                'specs' => [
                    ['Panjang', 'Custom (6–20 m)'],
                    ['Lebar Sabuk', '40–80 cm'],
                    ['Material Rangka', 'Besi / Stainless'],
                    ['Motor', '1.5–5 HP'],
                ],
            ],
        ];

        foreach ($products as $i => $productData) {
            $slug = Str::slug($productData['name']);
            $product = Product::create([
                'category_id' => $categoryIds[$productData['category']],
                'name' => $productData['name'],
                'slug' => $slug,
                'description' => $productData['description'],
                'price' => $productData['price'],
                'is_featured' => $productData['featured'],
            ]);

            $imagePath = 'products/'.$slug.'-1.svg';
            $this->makePlaceholder($imagePath, $productData['name']);

            $product->images()->create([
                'image_path' => $imagePath,
                'is_primary' => true,
            ]);

            $secondPath = 'products/'.$slug.'-2.svg';
            $this->makePlaceholder($secondPath, $productData['name']);

            $product->images()->create([
                'image_path' => $secondPath,
                'is_primary' => false,
            ]);

            foreach ($productData['specs'] as $spec) {
                $product->specifications()->create([
                    'spec_key' => $spec[0],
                    'spec_value' => $spec[1],
                ]);
            }
        }

        $customers = [
            ['Budi Santoso', '081234567890', 'budi@gmail.com', 'Jl. Merdeka No. 12, Bandung'],
            ['PT Agro Makmur', '082198765432', 'admin@agromakmur.co.id', 'Kawasan Industri Cikarang, Bekasi'],
            ['Siti Rahayu', '081398765432', null, 'Jl. Pahlawan No. 8, Yogyakarta'],
            ['CV Berkah Jaya', '085212345678', 'berkahjaya@yahoo.com', 'Jl. Raya Semarang Km 10, Semarang'],
            ['Andi Wijaya', '08123123123', 'andi.wijaya@outlook.com', 'Jl. Ahmad Yani No. 45, Surabaya'],
        ];

        $customerIds = [];

        foreach ($customers as $data) {
            $customerIds[] = Customer::create([
                'name' => $data[0],
                'phone' => $data[1],
                'email' => $data[2],
                'address' => $data[3],
            ])->id;
        }

        $allProducts = Product::all();
        $orderIds = [];

        for ($i = 0; $i < 8; $i++) {
            $order = Order::create([
                'customer_id' => $customerIds[$i % count($customerIds)],
                'product_id' => $allProducts[$i % $allProducts->count()]->id,
                'quantity' => rand(1, 3),
                'notes' => $i % 3 === 0 ? 'Mohon info estimasi waktu produksi.' : null,
                'status' => OrderStatus::cases()[array_rand(OrderStatus::cases())],
            ]);

            $orderIds[] = $order->id;
        }

        for ($i = 0; $i < 8; $i++) {
            $order = Order::find($orderIds[$i]);
            $paid = $i % 3 !== 0;

            Transaction::create([
                'order_id' => $order->id,
                'amount' => ($order->product->price ?? 50000000) * $order->quantity,
                'transaction_date' => now()->subDays(rand(0, 25))->toDateString(),
                'status' => $paid ? TransactionStatus::Lunas : TransactionStatus::BelumLunas,
            ]);
        }
    }

    private function makePlaceholder(string $path, string $title): void
    {
        $safeTitle = htmlspecialchars($title, ENT_QUOTES, 'UTF-8');

        $svg = <<<SVG
        <svg xmlns="http://www.w3.org/2000/svg" width="800" height="600" viewBox="0 0 800 600">
            <rect width="800" height="600" fill="#1E3A8A"/>
            <rect x="40" y="40" width="720" height="520" rx="24" fill="#F8FAFC"/>
            <circle cx="400" cy="250" r="130" fill="#1D4ED8" opacity="0.12"/>
            <g fill="#1D4ED8">
                <circle cx="400" cy="250" r="48" fill="#FFFFFF"/>
                <circle cx="400" cy="250" r="22" fill="#1D4ED8"/>
                <path d="M400 140 L412 182 L426 174 L416 214 L444 230 L442 246 L410 236 L410 264 L442 254 L444 270 L416 286 L426 326 L412 318 L400 360 L388 318 L374 326 L384 286 L356 270 L358 254 L390 264 L390 236 L358 246 L356 230 L384 214 L374 174 L388 182 Z"/>
            </g>
            <text x="400" y="470" text-anchor="middle" font-family="Poppins, Arial, sans-serif" font-size="34" font-weight="bold" fill="#1E293B">{$safeTitle}</text>
            <text x="400" y="505" text-anchor="middle" font-family="Arial, sans-serif" font-size="18" fill="#64748B">CV Adzra Engineering Bandung</text>
        </svg>
        SVG;

        Storage::disk('public')->put($path, $svg);
    }
}
