<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class AboutController extends Controller
{
    public function index(): View
    {
        $leaders = [
            ['name' => 'Rohman', 'role' => 'Direktur Utama'],
            ['name' => 'Rohman', 'role' => 'Direktur Operasional'],
            ['name' => 'Mariam', 'role' => 'Direktur Finance'],
            ['name' => 'Mariam', 'role' => 'Direktur HRD & Marketing'],
        ];

        $productionServices = [
            ['label' => 'Custom', 'description' => 'Mesin dirancang sesuai kebutuhan spesifik proses produksi Anda.'],
            ['label' => 'Integration', 'description' => 'Integrasi mesin baru ke lini produksi yang sudah ada.'],
            ['label' => 'Servicing', 'description' => 'Perawatan dan perbaikan berkala oleh teknisi berpengalaman.'],
        ];

        $partners = [
            ['name' => 'Munch Machinery', 'country' => 'China'],
            ['name' => 'Simec', 'country' => 'China'],
            ['name' => 'Liyang Yugra Greace', 'country' => 'China'],
            ['name' => 'Zonstar Richi Machinery', 'country' => 'China'],
            ['name' => 'Huili Machine', 'country' => 'China'],
            ['name' => 'Indiamart', 'country' => 'Jerman'],
        ];

        $stats = [
            ['value' => '14', 'suffix' => 'THN', 'label' => 'Tahun Pengalaman'],
            ['value' => '30', 'suffix' => '+', 'label' => 'Pelanggan'],
            ['value' => '12', 'suffix' => '+', 'label' => 'Jenis Mesin'],
        ];

        $industries = [
            'Biomass',
            'Industri Agro',
            'Pertanian',
            'Industri Textile',
            'Industri Sawit',
            'Perikanan',
            'Manufacture',
            'Electric',
            'Konstruksi',
            'Boiler & Jasa',
        ];

        $vision = 'Menjadikan CV Adzra Engineering Bandung sebagai produsen mesin industri dan penyedia jasa perbaikan mesin terbesar di Asia, sekaligus menghadirkan solusi energi terbarukan dari biomassa untuk keseimbangan alam.';

        $missions = [
            'Menjalankan kegiatan perusahaan dengan standar etika tinggi, kejujuran, dan integritas.',
            'Memenuhi kebutuhan pelanggan dengan selalu menyediakan produk berkualitas tinggi dan terbaik.',
            'Mengelola unit usaha secara terintegrasi dengan mengedepankan prinsip sinergi antar unit usaha.',
            'Menerapkan standar profesionalisme tertinggi melalui inovasi berkelanjutan dan pengambilan keputusan yang matang dan konsisten.',
            'Bekerja dengan penuh tanggung jawab terhadap masyarakat dan lingkungan tempat usaha beroperasi.',
            'Memproduksi mesin industri agro, perikanan, tekstil, dan biomassa dengan kualitas tinggi dan ramah lingkungan.',
        ];

        return view('about.index', compact(
            'leaders', 'productionServices', 'partners',
            'stats', 'industries', 'vision', 'missions',
        ));
    }
}
