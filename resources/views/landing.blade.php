@extends('layouts.app')

@section('title', 'CV Adzra Engineering — Fabrikasi Mesin Industri Bandung')

@section('content')
    <section class="relative overflow-hidden bg-steel-900 text-white">
        <div class="absolute inset-0 bg-[url('/rotari.jpeg')] bg-cover bg-center"></div>
        <div class="absolute inset-0 bg-black/40"></div>
        <div class="relative mx-auto max-w-7xl px-4 pt-32 pb-24 text-center sm:px-6 lg:px-8">
            <p class="label-mono text-amber-600">CV Adzra Engineering — Padalarang, Bandung Barat</p>
            <h1 class="mx-auto mt-4 max-w-3xl font-display text-4xl font-bold leading-tight sm:text-5xl">
                Fabrikasi Mesin Industri, Dibuat Sesuai Spesifikasi Anda
            </h1>
            <p class="mx-auto mt-6 max-w-2xl font-body text-base text-steel-400">
                Rotary dryer, mesin cetak wood pellet, mesin cetak pelet — dirancang dan difabrikasi langsung oleh tim kami
                di Bandung.
            </p>
            <div class="mt-10 flex flex-wrap justify-center gap-4">
                <a href="{{ route('products.index') }}"
                    class="rounded bg-amber-600 px-6 py-3 font-mono text-xs font-semibold uppercase tracking-widest text-white transition hover:bg-amber-700">
                    Lihat Produk →
                </a>
                <a href="{{ $whatsappLink }}" target="_blank" rel="noopener noreferrer"
                    class="rounded border border-white/25 px-6 py-3 font-mono text-xs font-semibold uppercase tracking-widest text-white transition hover:bg-white/5">
                    Konsultasi WhatsApp
                </a>
            </div>
        </div>
    </section>

    <section class="bg-paper-100 py-16">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="grid items-center gap-12 lg:grid-cols-2">
                <div>
                    <p class="label-mono">Profil Perusahaan</p>
                    <h2 class="mt-3 font-display text-3xl font-bold text-graphite-900">Tentang CV Adzra Engineering</h2>
                    <p class="mt-4 leading-relaxed text-graphite-500">
                        Kami adalah perusahaan fabrikasi mesin industri yang berdomisili di Bandung, Jawa Barat.
                        Berpengalaman dalam pembuatan rotary dryer, mesin cetak wood pellet, mesin cetak pelet, dan berbagai
                        mesin industri sesuai kebutuhan pelanggan.
                    </p>

                    <a href="{{ route('about') }}"
                        class="mt-8 inline-block font-mono text-xs font-semibold uppercase tracking-widest text-steel-700 transition hover:text-steel-900">
                        Selengkapnya →
                    </a>
                </div>
                <div class="plate rounded p-8">
                    <ul class="divide-y divide-line-200 border-y border-line-200">
                        <li class="flex items-baseline gap-4 py-3">
                            <span class="label-mono text-amber-600">01</span>
                            <span class="font-body text-graphite-900">Fabrikasi mesin custom sesuai spesifikasi</span>
                        </li>
                        <li class="flex items-baseline gap-4 py-3">
                            <span class="label-mono text-amber-600">02</span>
                            <span class="font-body text-graphite-900">Material berkualitas dengan pengerjaan presisi</span>
                        </li>
                        <li class="flex items-baseline gap-4 py-3">
                            <span class="label-mono text-amber-600">03</span>
                            <span class="font-body text-graphite-900">Konsultasi dan layanan purna jual</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <section class="bg-white py-16">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex items-end justify-between">
                <div>
                    <p class="label-mono">Katalog Mesin</p>
                    <h2 class="mt-3 font-display text-3xl font-bold text-graphite-900">Produk Unggulan</h2>
                    <p class="mt-2 text-graphite-500">Mesin terbaik dari CV Adzra Engineering</p>
                </div>
                <a href="{{ route('products.index') }}"
                    class="hidden font-mono text-xs font-semibold uppercase tracking-widest text-steel-700 hover:text-steel-900 sm:inline-block">
                    Semua Produk →
                </a>
            </div>

            @if ($featuredProducts->isEmpty())
                <p class="mt-8 rounded border border-line-200 bg-white p-8 text-center text-graphite-500">
                    Belum ada produk unggulan.
                </p>
            @else
                <div class="mt-8 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($featuredProducts as $product)
                        <a href="{{ route('products.show', $product->slug) }}"
                            class="plate group block rounded p-0 transition hover:shadow-sm">
                            <span class="plate-corner-bl"></span>
                            <span class="plate-corner-br"></span>
                            <div class="aspect-[4/3] overflow-hidden bg-paper-100">
                                @if ($product->primaryImage())
                                    <img src="{{ asset('storage/' . $product->primaryImage()->image_path) }}"
                                        alt="{{ $product->name }}"
                                        class="h-full w-full object-cover transition duration-300 group-hover:scale-105">
                                @else
                                    <div
                                        class="flex h-full items-center justify-center font-display text-sm font-semibold text-graphite-500">
                                        {{ $product->name }}</div>
                                @endif
                            </div>
                            <div class="p-5">
                                <p class="label-mono">{{ $product->category?->name }}</p>
                                <h3
                                    class="mt-1 font-display text-lg font-semibold text-graphite-900 group-hover:text-steel-700">
                                    {{ $product->name }}</h3>
                                <div class="mt-3 flex items-center justify-between border-t border-line-200 pt-3">
                                    <span class="font-mono text-sm font-semibold text-steel-700">
                                        {{ $product->price ? 'Rp ' . number_format((float) $product->price, 0, ',', '.') : 'Hubungi Kami' }}
                                    </span>
                                    <span class="label-mono text-amber-600">Detail →</span>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    <section class="bg-paper-100 py-16">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex items-end justify-between">
                <div>
                    <p class="label-mono">Layanan Kami</p>
                    <h2 class="mt-3 font-display text-3xl font-bold text-graphite-900">Layanan &amp; Jasa Teknis</h2>
                    <p class="mt-2 text-graphite-500">Dari mesin custom sampai instalasi — siap membantu produksi Anda</p>
                </div>
                <a href="{{ route('services.index') }}"
                    class="hidden font-mono text-xs font-semibold uppercase tracking-widest text-steel-700 hover:text-steel-900 sm:inline-block">
                    Semua Layanan →
                </a>
            </div>

            @if ($services->isEmpty())
                <p class="mt-8 rounded border border-line-200 bg-white p-8 text-center text-graphite-500">
                    Belum ada layanan yang tersedia.
                </p>
            @else
                <div class="mt-8 grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                    @foreach ($services as $service)
                        <div class="plate flex flex-col rounded bg-white p-6">
                            <span class="plate-corner-bl"></span>
                            <span class="plate-corner-br"></span>
                            <p class="label-mono text-amber-600">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</p>
                            <h3 class="mt-3 font-display text-lg font-semibold text-graphite-900">{{ $service->name }}</h3>
                            <p class="mt-2 flex-1 text-sm leading-relaxed text-graphite-500">{{ $service->description }}</p>
                            <a href="{{ \App\Support\WhatsApp::link($service->whatsappMessage()) }}" target="_blank" rel="noopener"
                                class="mt-5 inline-flex items-center justify-center gap-2 rounded bg-amber-600 px-4 py-2 font-mono text-xs font-semibold uppercase tracking-widest text-white transition hover:bg-amber-700">
                                <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                                </svg>
                                Pesan via WhatsApp
                            </a>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    <section
        class="relative overflow-hidden bg-steel-900 py-20 text-center text-white bg-[linear-gradient(to_right,rgba(255,255,255,.04)_1px,transparent_1px),linear-gradient(to_bottom,rgba(255,255,255,.04)_1px,transparent_1px)] bg-[size:32px_32px]">
        <div class="mx-auto max-w-3xl px-4 sm:px-6">
            <h2 class="font-display text-3xl font-bold">Butuh Mesin Sesuai Kebutuhan Anda?</h2>
            <p class="mt-4 text-steel-400">
                Konsultasikan kebutuhan fabrikasi mesin Anda langsung dengan tim kami melalui WhatsApp.
            </p>
            <a href="{{ $whatsappLink }}" target="_blank" rel="noopener noreferrer"
                class="mt-8 inline-flex items-center gap-2 rounded bg-amber-600 px-8 py-3 font-mono text-xs font-semibold uppercase tracking-widest text-white transition hover:bg-amber-700">
                <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                    <path
                        d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z" />
                </svg>
                Konsultasi Sekarang
            </a>
        </div>
    </section>
@endsection
