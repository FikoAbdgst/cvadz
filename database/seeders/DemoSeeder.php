<?php

namespace Database\Seeders;

use App\Enums\OrderStatus;
use App\Enums\TransactionStatus;
use App\Models\Attendance;
use App\Models\Cashbook;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use App\Models\Service;
use App\Models\Supplier;
use App\Models\Transaction;
use App\Models\Worker;
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
            'sparepart' => ['Sparepart', false],
            'lainnya' => ['Mesin Industri Lainnya', false],
        ];

        $categoryIds = [];

        foreach ($categories as $slug => [$name]) {
            $categoryIds[$slug] = Category::create(['name' => $name, 'slug' => $slug])->id;
        }

        $layananCategories = [
            'fabrikasi' => 'Fabrikasi & Custom Mesin',
            'perbaikan' => 'Perbaikan & Perawatan',
            'elektrikal' => 'Elektrikal & Panel',
        ];

        foreach ($layananCategories as $slug => $name) {
            $categoryIds[$slug] = Category::create(['name' => $name, 'slug' => $slug, 'type' => 'layanan'])->id;
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
            [
                'category' => 'sparepart',
                'name' => 'Motor Listrik 1.5 HP',
                'price' => 1850000,
                'featured' => false,
                'warranty' => 6,
                'description' => 'Motor listrik penggerak pengganti untuk berbagai mesin industri. Tersedia varian 1.5 HP hingga 50 HP.',
                'specs' => [
                    ['Daya', '1.5 HP / 1.1 kW'],
                    ['Sumber', '380V 3 Phase'],
                    ['Kecepatan', '1500 rpm'],
                    ['Garansi', '6 bulan'],
                ],
            ],
            [
                'category' => 'sparepart',
                'name' => 'Roller & Bearing Rotary Dryer',
                'price' => 2500000,
                'featured' => false,
                'warranty' => 3,
                'description' => 'Sparepart roller dan bearing untuk rotary dryer — pengganti suku cadang yang aus agar mesin kembali lancar.',
                'specs' => [
                    ['Jenis', 'Roller + Pillow Block Bearing'],
                    ['Material', 'Cast Iron / Baja'],
                    ['Garansi', '3 bulan'],
                ],
            ],
            [
                'category' => 'sparepart',
                'name' => 'Plat Baja SS400 & Stainless',
                'price' => 750000,
                'featured' => false,
                'description' => 'Plat baja SS400 dan stainless steel untuk kebutuhan fabrikasi dan perbaikan mesin. Harga per lembar sesuai ukuran.',
                'specs' => [
                    ['Material', 'SS400 / SUS 304'],
                    ['Ketebalan', '2–20 mm'],
                    ['Satuan', 'Per lembar / potong'],
                ],
            ],
            [
                'category' => 'sparepart',
                'name' => 'Die & Roller Mesin Pelet',
                'price' => 3500000,
                'featured' => false,
                'warranty' => 3,
                'description' => 'Die (cetakan) dan roller pengganti untuk mesin pelet. Diameter lubang 2–10 mm sesuai kebutuhan produksi.',
                'specs' => [
                    ['Jenis', 'Die + Roller set'],
                    ['Diameter Lubang', '2–10 mm'],
                    ['Material', 'Baja krom tahan aus'],
                    ['Garansi', '3 bulan'],
                ],
            ],
        ];

        $stockValues = [2, 4, 6, 1, 5, 3, 0, 4, 12, 8, 30, 5];

        foreach ($products as $i => $productData) {
            $slug = Str::slug($productData['name']);
            $product = Product::create([
                'category_id' => $categoryIds[$productData['category']],
                'name' => $productData['name'],
                'slug' => $slug,
                'description' => $productData['description'],
                'price' => $productData['price'],
                'is_featured' => $productData['featured'],
                'warranty_months' => $productData['warranty'] ?? 12,
                'stock' => $productData['stock'] ?? $stockValues[$i % count($stockValues)],
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
            $product = $allProducts[$i % $allProducts->count()];
            $quantity = rand(1, 3);
            $status = OrderStatus::cases()[array_rand(OrderStatus::cases())];
            $hasWarranty = in_array($status, [OrderStatus::Selesai, OrderStatus::Diproses], true);

            $order = Order::create([
                'customer_id' => $customerIds[$i % count($customerIds)],
                'product_id' => $product->id,
                'quantity' => $quantity,
                'notes' => $i % 3 === 0 ? 'Mohon info estimasi waktu produksi.' : null,
                'status' => $status,
                'total' => $product->price * $quantity,
                'warranty_end_date' => $hasWarranty
                    ? ($i % 4 === 3
                        ? now()->subMonths(2)->toDateString()
                        : now()->addMonths($product->warranty_months ?? 12)->toDateString())
                    : null,
            ]);

            $orderIds[] = $order->id;
        }

        $services = [
            [
                'name' => 'Custom Mesin',
                'category' => 'fabrikasi',
                'description' => 'Rancang dan fabrikasi mesin industri sesuai kebutuhan proses produksi Anda — mulai dari konsep, gambar desain, hingga jadi.',
                'price' => null,
                'featured' => true,
            ],
            [
                'name' => 'Service & Perawatan Mesin',
                'category' => 'perbaikan',
                'description' => 'Perbaikan dan perawatan berkala mesin rotary dryer, wood pellet, dan mesin industri lainnya agar tetap prima.',
                'price' => null,
                'featured' => true,
            ],
            [
                'name' => 'Instalasi Listrik',
                'category' => 'elektrikal',
                'description' => 'Instalasi panel dan jaringan listrik untuk mesin industri, termasuk setting inverter dan motor penggerak.',
                'price' => null,
                'featured' => false,
            ],
            [
                'name' => 'Panel Kontrol',
                'category' => 'elektrikal',
                'description' => 'Pembuatan panel kontrol manual maupun otomatis (PLC) untuk otomasi proses produksi mesin Anda.',
                'price' => null,
                'featured' => false,
            ],
        ];

        foreach ($services as $serviceData) {
            Service::create([
                'name' => $serviceData['name'],
                'slug' => Str::slug($serviceData['name']),
                'description' => $serviceData['description'],
                'price' => $serviceData['price'],
                'is_featured' => $serviceData['featured'],
                'category_id' => $categoryIds[$serviceData['category']],
            ]);
        }

        $serviceOrders = [
            ['customer' => 1, 'service' => 0, 'status' => OrderStatus::Diproses, 'total' => 15000000, 'notes' => 'Estimasi fabrikasi body rotary dryer 1 ton.'],
            ['customer' => 3, 'service' => 1, 'status' => OrderStatus::Selesai, 'total' => 8500000, 'notes' => null],
        ];

        $allServices = Service::all();

        foreach ($serviceOrders as $data) {
            $order = Order::create([
                'customer_id' => $customerIds[$data['customer'] % count($customerIds)],
                'service_id' => $allServices[$data['service']]->id,
                'quantity' => 1,
                'notes' => $data['notes'],
                'status' => $data['status'],
                'total' => $data['total'],
                'warranty_end_date' => null,
            ]);

            $orderIds[] = $order->id;
        }

        for ($i = 0; $i < count($orderIds); $i++) {
            $order = Order::find($orderIds[$i]);
            $paid = $i % 3 !== 0;

            Transaction::create([
                'order_id' => $order->id,
                'amount' => $order->total ?? 50000000,
                'transaction_date' => now()->subDays(rand(0, 25))->toDateString(),
                'status' => $paid ? TransactionStatus::Lunas : TransactionStatus::BelumLunas,
            ]);
        }

        $suppliers = [
            ['PT Baja Utama Steel', 'Bpk. Hendra', '021-5551001', 'sales@bajautama.co.id', 'Jl. Raya Bekasi Km 22, Jakarta Timur'],
            ['CV Elektrik Nusantara', 'Ibu Sari', '022-7304555', 'info@elektriknusantara.com', 'Jl. Soekarno-Hatta No. 335, Bandung'],
        ];

        foreach ($suppliers as $data) {
            Supplier::create([
                'name' => $data[0],
                'contact_name' => $data[1],
                'phone' => $data[2],
                'email' => $data[3],
                'address' => $data[4],
            ]);
        }

        $workers = [
            ['Dedi Kurniawan', 'Kepala Bengkel', '081222111333', 150000],
            ['Rudi Hartono', 'Teknisi Fabrikasi', '081333222444', 120000],
            ['Agus Salim', 'Tukang Las', '081444333555', 110000],
        ];

        $workerIds = [];

        foreach ($workers as $data) {
            $workerIds[] = Worker::create([
                'name' => $data[0],
                'position' => $data[1],
                'phone' => $data[2],
                'salary' => $data[3],
            ])->id;
        }

        for ($day = 0; $day < 5; $day++) {
            foreach ($workerIds as $workerId) {
                $absent = $workerId === $workerIds[2] && $day === 2;

                if ($absent) {
                    continue;
                }

                Attendance::create([
                    'worker_id' => $workerId,
                    'date' => now()->subDays($day)->toDateString(),
                    'check_in' => '08:00:00',
                    'check_out' => $day % 2 === 0 ? '16:30:00' : null,
                ]);
            }
        }

        $cashbookEntries = [
            ['pemasukan', 85000000, 'DP pemesanan Rotary Dryer', 3],
            ['pengeluaran', 12000000, 'Pembelian plat baja SS400 ke PT Baja Utama Steel', 2],
            ['pemasukan', 5000000, 'Pembayaran service mesin wood pellet', 1],
            ['pengeluaran', 3500000, 'Gaji tukang las mingguan', 0],
        ];

        foreach ($cashbookEntries as $data) {
            Cashbook::create([
                'type' => $data[0],
                'amount' => $data[1],
                'description' => $data[2],
                'transaction_date' => now()->subDays($data[3])->toDateString(),
                'user_id' => 1,
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
