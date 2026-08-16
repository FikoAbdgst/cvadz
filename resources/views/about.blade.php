@extends('layouts.app')

@section('title', 'Tentang Kami — CV Adzra Engineering')

@section('content')
    <section class="hero-glow relative overflow-hidden pt-32 pb-16 text-white">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <p class="label-mono text-amber-600">Profil Perusahaan</p>
            <h1 class="mt-3 font-display text-4xl font-bold">Tentang Kami</h1>
            <p class="mt-3 max-w-2xl text-steel-400">Perusahaan fabrikasi mesin industri yang berpusat di Bandung, Jawa Barat.</p>
        </div>
    </section>

    <section class="bg-white py-16">
        <div class="mx-auto max-w-3xl space-y-8 px-4 text-graphite-500 sm:px-6">
            <div>
                <h2 class="font-display text-2xl font-bold text-graphite-900">Profil Perusahaan</h2>
                <p class="mt-4 leading-relaxed">
                    CV Adzra Engineering adalah perusahaan yang bergerak di bidang jasa dan fabrikasi mesin industri. Kami menyediakan layanan pembuatan mesin seperti rotary dryer, mesin cetak wood pellet, mesin cetak pelet, dan berbagai mesin industri lainnya.
                </p>
                <p class="mt-4 leading-relaxed">
                    Dengan pengalaman dan tenaga kerja yang terampil, kami berkomitmen menghasilkan mesin yang presisi, tahan lama, dan sesuai kebutuhan pelanggan.
                </p>
            </div>

            <div>
                <h2 class="font-display text-2xl font-bold text-graphite-900">Layanan Kami</h2>
                <ul class="mt-4 grid gap-4 sm:grid-cols-2">
                    <li class="plate rounded bg-white p-5">
                        <span class="plate-corner-bl"></span>
                        <span class="plate-corner-br"></span>
                        <p class="label-mono text-amber-600">01</p>
                        <h3 class="mt-2 font-display font-semibold text-graphite-900">Fabrikasi Mesin</h3>
                        <p class="mt-2 text-sm">Pembuatan mesin industri baru sesuai desain dan spesifikasi.</p>
                    </li>
                    <li class="plate rounded bg-white p-5">
                        <span class="plate-corner-bl"></span>
                        <span class="plate-corner-br"></span>
                        <p class="label-mono text-amber-600">02</p>
                        <h3 class="mt-2 font-display font-semibold text-graphite-900">Mesin Custom</h3>
                        <p class="mt-2 text-sm">Mesin dibuat khusus mengikuti kebutuhan proses produksi Anda.</p>
                    </li>
                    <li class="plate rounded bg-white p-5">
                        <span class="plate-corner-bl"></span>
                        <span class="plate-corner-br"></span>
                        <p class="label-mono text-amber-600">03</p>
                        <h3 class="mt-2 font-display font-semibold text-graphite-900">Konsultasi</h3>
                        <p class="mt-2 text-sm">Konsultasi gratis untuk memilih atau merancang mesin yang tepat.</p>
                    </li>
                    <li class="plate rounded bg-white p-5">
                        <span class="plate-corner-bl"></span>
                        <span class="plate-corner-br"></span>
                        <p class="label-mono text-amber-600">04</p>
                        <h3 class="mt-2 font-display font-semibold text-graphite-900">Layanan Purna Jual</h3>
                        <p class="mt-2 text-sm">Dukungan teknis dan perawatan setelah mesin terpasang.</p>
                    </li>
                </ul>
            </div>
        </div>
    </section>
@endsection
