@extends('layouts.app')

@section('title', 'CV Adzra Engineering — Fabrikasi Mesin Industri Bandung')

@section('content')
    {{-- HERO --}}
    <section
        class="relative flex min-h-screen flex-col overflow-hidden bg-steel-900 text-white bg-[linear-gradient(to_right,rgba(255,255,255,.035)_1px,transparent_1px),linear-gradient(to_bottom,rgba(255,255,255,.035)_1px,transparent_1px)] bg-[size:28px_28px]">
        <div class="mx-auto flex flex-1 items-center max-w-7xl px-4 pb-12 pt-24 sm:px-6 sm:pb-16 sm:pt-28 lg:px-8">
            <div class="grid gap-10 lg:grid-cols-2 lg:items-center lg:gap-16">
                <div>
                    <p
                        class="hero-anim hero-anim-delay-1 inline-flex items-center gap-2 rounded border border-white/15 px-2.5 py-1 font-mono text-[11px] uppercase tracking-widest text-amber-500">
                        <svg class="h-2.5 w-2.5" viewBox="0 0 10 10" fill="none" stroke="currentColor" stroke-width="1">
                            <path d="M5 0v10M0 5h10" />
                        </svg>
                        Padalarang, Bandung Barat
                    </p>
                    <h1 class="hero-anim hero-anim-delay-2 mt-5 font-display text-3xl font-bold leading-tight sm:text-5xl">
                        Fabrikasi Mesin Industri, Sesuai Spesifikasi Anda
                    </h1>
                    <p class="hero-anim hero-anim-delay-3 mt-4 max-w-md text-sm leading-relaxed text-steel-400 sm:text-base">
                        Rotary dryer dan mesin cetak pelet — dirancang serta dibuat langsung oleh tim kami di
                        Bandung.
                    </p>

                    <div class="hero-anim hero-anim-delay-4 mt-8 flex flex-col gap-3 sm:flex-row">
                        <a href="{{ route('products.index') }}"
                            class="rounded bg-amber-600 px-6 py-3.5 text-center font-mono text-xs font-semibold uppercase tracking-widest text-white transition hover:bg-amber-700 active:bg-amber-700 sm:py-3">
                            Lihat Produk →
                        </a>
                        <a href="{{ $whatsappLink }}" target="_blank" rel="noopener noreferrer"
                            class="rounded border border-white/20 px-6 py-3.5 text-center font-mono text-xs font-semibold uppercase tracking-widest text-white transition hover:bg-white/10 active:bg-white/10 sm:py-3">
                            Konsultasi WhatsApp
                        </a>
                    </div>
                </div>

                {{-- foto carousel ala lembar gambar teknik --}}
                <div class="hero-anim hero-anim-delay-3 relative">
                    <div id="hero-carousel"
                        class="relative aspect-[4/3] overflow-hidden rounded-lg ring-1 ring-white/10 sm:aspect-[16/10]">

                        @php
                            $heroSlides = [
                                [
                                    'src' => 'rotari.jpeg',
                                    'label' => 'Gbr. 01',
                                    'caption' => 'Rotary Dryer — Workshop Bandung',
                                ],
                                [
                                    'src' => 'WoodPelet.jpeg',
                                    'label' => 'Gbr. 02',
                                    'caption' => 'Mesin Wood Pelet — Produksi Lokal',
                                ],
                                [
                                    'src' => 'hammermil.jpeg',
                                    'label' => 'Gbr. 03',
                                    'caption' => 'Hammer Mill — Fabrikasi CV Adzra',
                                ],
                                [
                                    'src' => 'testbattery.jpeg',
                                    'label' => 'Gbr. 04',
                                    'caption' => 'Battery Test — Quality Control',
                                ],
                            ];
                        @endphp

                        @foreach ($heroSlides as $i => $slide)
                            <img src="{{ asset($slide['src']) }}" alt="{{ $slide['caption'] }}"
                                class="hero-slide absolute inset-0 h-full w-full object-cover transition-opacity duration-700 {{ $i === 0 ? 'opacity-100' : 'opacity-0' }}"
                                data-index="{{ $i }}">
                        @endforeach

                        {{-- crosshair registrasi --}}
                        <svg class="pointer-events-none absolute left-3 top-3 hidden h-4 w-4 text-white/50 sm:block"
                            viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1">
                            <path d="M8 0v6M8 10v6M0 8h6M10 8h16" />
                        </svg>
                        <svg class="pointer-events-none absolute right-3 top-3 hidden h-4 w-4 text-white/50 sm:block"
                            viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1">
                            <path d="M8 0v6M8 10v6M0 8h6M10 8h16" />
                        </svg>
                        <svg class="pointer-events-none absolute bottom-3 right-3 hidden h-4 w-4 text-white/50 sm:block"
                            viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1">
                            <path d="M8 0v6M8 10v6M0 8h6M10 8h16" />
                        </svg>

                        {{-- tag kop gambar — update per slide --}}
                        <div id="hero-tag"
                            class="absolute bottom-3 left-3 rounded border border-white/15 bg-steel-900/85 px-3 py-1.5 backdrop-blur transition-opacity duration-500">
                            <p id="hero-tag-label" class="font-mono text-[9px] uppercase tracking-widest text-amber-500">
                                {{ $heroSlides[0]['label'] }}</p>
                            <p id="hero-tag-caption"
                                class="mt-0.5 font-mono text-[10px] uppercase tracking-widest text-steel-300">
                                {{ $heroSlides[0]['caption'] }}</p>
                        </div>

                        {{-- dots --}}
                        <div class="absolute bottom-3 right-3 flex items-center gap-1.5 sm:bottom-auto sm:top-3 sm:right-3">
                            @foreach ($heroSlides as $i => $slide)
                                <button type="button" data-hero-dot="{{ $i }}"
                                    class="h-1.5 rounded-full transition-all duration-300 {{ $i === 0 ? 'w-5 bg-amber-500' : 'w-1.5 bg-white/40' }}"></button>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- strip statistik full-width --}}
        <div class="hero-anim hero-anim-delay-5 border-t border-white/10">
            <div class="mx-auto grid max-w-7xl grid-cols-2 divide-x divide-white/10 px-4 sm:px-6 lg:px-8">
                <div class="py-5 text-center sm:py-6 sm:text-left">
                    <p class="font-mono text-[10px] uppercase tracking-widest text-steel-400 sm:text-xs">Berdiri</p>
                    <p class="mt-1 font-display text-lg font-bold sm:text-2xl">2015</p>
                </div>

                <div class="py-5 text-center sm:py-6 sm:text-left">
                    <p class="font-mono text-[10px] uppercase tracking-widest text-steel-400 sm:text-xs">Basis</p>
                    <p class="mt-1 font-display text-lg font-bold sm:text-2xl">Bandung</p>
                </div>
            </div>
        </div>
    </section>

    @include('partials.partners-marquee')

    {{-- ABOUT --}}
    <section class="bg-paper-100 py-16 sm:py-24">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="grid items-start gap-10 lg:grid-cols-2 lg:gap-16">
                <div class="anim anim-fade-up">
                    <p class="label-mono">Profil Perusahaan</p>
                    <h2 class="mt-3 font-display text-2xl font-bold text-graphite-900 sm:text-3xl">
                        Tentang CV Adzra Engineering
                    </h2>
                    <p class="mt-4 max-w-md text-sm leading-relaxed text-graphite-500 sm:text-base">
                        Perusahaan fabrikasi mesin industri di Bandung — rotary dryer, mesin cetak pelet, dan mesin
                        custom lain sesuai kebutuhan produksi Anda.
                    </p>

                    <a href="{{ route('about') }}"
                        class="mt-6 inline-flex items-center gap-1 font-mono text-xs font-semibold uppercase tracking-widest text-steel-700 transition hover:text-steel-900">
                        Selengkapnya →
                    </a>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="plate anim anim-fade-up anim-delay-1 rounded p-5">
                        <span class="plate-corner-bl"></span>
                        <span class="plate-corner-br"></span>
                        <p class="font-display text-3xl font-bold text-steel-700">01</p>
                        <p class="mt-3 text-sm font-medium text-graphite-900">Fabrikasi mesin custom sesuai spesifikasi</p>
                    </div>
                    <div class="plate anim anim-fade-up anim-delay-2 rounded p-5">
                        <span class="plate-corner-bl"></span>
                        <span class="plate-corner-br"></span>
                        <p class="font-display text-3xl font-bold text-steel-700">02</p>
                        <p class="mt-3 text-sm font-medium text-graphite-900">Material berkualitas, pengerjaan presisi</p>
                    </div>
                    <div class="plate anim anim-fade-up anim-delay-3 rounded p-5">
                        <span class="plate-corner-bl"></span>
                        <span class="plate-corner-br"></span>
                        <p class="font-display text-3xl font-bold text-steel-700">03</p>
                        <p class="mt-3 text-sm font-medium text-graphite-900">Konsultasi dan layanan purna jual</p>
                    </div>
                    <div class="plate anim anim-fade-up anim-delay-4 rounded p-5">
                        <span class="plate-corner-bl"></span>
                        <span class="plate-corner-br"></span>
                        <p class="font-display text-3xl font-bold text-steel-700">04</p>
                        <p class="mt-3 text-sm font-medium text-graphite-900">Garansi resmi dan dukungan teknis</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- PRODUCTS --}}
    <section class="bg-white py-16 sm:py-24">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="anim anim-fade-up flex items-end justify-between gap-4">
                <div>
                    <p class="label-mono">Katalog Mesin</p>
                    <h2 class="mt-3 font-display text-2xl font-bold text-graphite-900 sm:text-3xl">Produk Unggulan</h2>
                </div>
                <a href="{{ route('products.index') }}"
                    class="hidden shrink-0 font-mono text-xs font-semibold uppercase tracking-widest text-steel-700 hover:text-steel-900 sm:inline-block">
                    Semua Produk →
                </a>
            </div>

            @if ($featuredProducts->isEmpty())
                <p class="mt-8 rounded border border-line-200 bg-white p-8 text-center text-graphite-500">
                    Belum ada produk unggulan.
                </p>
            @else
                <div class="mt-10 grid gap-6 sm:grid-cols-2 sm:gap-8 lg:grid-cols-3">
                    @foreach ($featuredProducts as $product)
                        <a href="{{ route('products.show', $product->slug) }}"
                            class="group anim anim-fade-up anim-delay-{{ $loop->iteration }} block overflow-hidden rounded border border-line-200 transition hover:shadow-md active:shadow-md">
                            <div class="relative aspect-[4/3] overflow-hidden bg-paper-100">
                                @if ($product->primaryImage())
                                    <img src="{{ asset('storage/' . $product->primaryImage()->image_path) }}"
                                        alt="{{ $product->name }}"
                                        class="img-load h-full w-full object-cover transition duration-300 group-hover:scale-105"
                                        onload="this.classList.add('loaded')">
                                    <div class="skeleton absolute inset-0"></div>
                                @else
                                    <div class="img-placeholder h-full w-full">
                                        <svg class="h-8 w-8 text-graphite-500/40" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909M3.75 21h16.5a1.5 1.5 0 001.5-1.5V5.25a1.5 1.5 0 00-1.5-1.5H3.75a1.5 1.5 0 00-1.5 1.5v14.25a1.5 1.5 0 001.5 1.5z"/>
                                        </svg>
                                        <span class="font-mono text-[10px] uppercase tracking-widest text-graphite-500/60">{{ $product->category?->name ?? 'Produk' }}</span>
                                    </div>
                                @endif
                            </div>
                            <div class="p-5">
                                <p class="label-mono">{{ $product->category?->name }}</p>
                                <h3
                                    class="mt-1 font-display text-base font-semibold text-graphite-900 group-hover:text-steel-700 sm:text-lg">
                                    {{ $product->name }}
                                </h3>
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

            <a href="{{ route('products.index') }}"
                class="mt-8 block rounded border border-line-200 py-3 text-center font-mono text-xs font-semibold uppercase tracking-widest text-steel-700 sm:hidden">
                Semua Produk →
            </a>
        </div>
    </section>

    {{-- SERVICES --}}
    <section class="bg-paper-100 py-16 sm:py-24">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="anim anim-fade-up flex items-end justify-between gap-4">
                <div>
                    <p class="label-mono">Layanan Kami</p>
                    <h2 class="mt-3 font-display text-2xl font-bold text-graphite-900 sm:text-3xl">Layanan &amp; Jasa
                        Teknis</h2>
                </div>
                <a href="{{ route('services.index') }}"
                    class="hidden shrink-0 font-mono text-xs font-semibold uppercase tracking-widest text-steel-700 hover:text-steel-900 sm:inline-block">
                    Semua Layanan →
                </a>
            </div>

            @if ($services->isEmpty())
                <p class="mt-8 rounded border border-line-200 bg-white p-8 text-center text-graphite-500">
                    Belum ada layanan yang tersedia.
                </p>
            @else
                <div class="mt-10">
                    @foreach ($services as $service)
                        <div class="group anim anim-fade-up anim-delay-{{ min($loop->iteration, 4) }} relative flex items-start gap-6 pb-10 last:pb-0">
                            {{-- garis penghubung vertikal --}}
                            @if (!$loop->last)
                                <div class="absolute left-5 top-12 bottom-0 w-px bg-line-200"></div>
                            @endif

                            {{-- nomor dalam lingkaran --}}
                            <span
                                class="relative z-10 flex h-10 w-10 shrink-0 items-center justify-center rounded-full border-2 border-line-200 bg-white font-mono text-sm font-semibold text-amber-600 transition-colors group-hover:border-amber-600">
                                {{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}
                            </span>

                            {{-- konten panel penuh --}}
                            <div
                                class="flex-1 rounded border border-line-200 bg-white px-5 py-4 transition-colors group-hover:border-steel-400/40">
                                <div class="flex flex-wrap items-baseline justify-between gap-x-4 gap-y-1">
                                    <div class="flex flex-wrap items-baseline gap-x-4 gap-y-1">
                                        <h3 class="font-display text-lg font-semibold text-graphite-900">
                                            {{ $service->name }}
                                        </h3>
                                        @if ($service->category)
                                            <span class="label-mono text-steel-700">{{ $service->category->name }}</span>
                                        @endif
                                    </div>
                                    <span class="font-mono text-sm font-semibold text-steel-700">
                                        {{ $service->price ? 'Rp ' . number_format((float) $service->price, 0, ',', '.') : 'Konsultasi gratis' }}
                                    </span>
                                </div>

                                <p class="mt-2 text-sm leading-relaxed text-graphite-500">
                                    {{ $service->description }}
                                </p>

                                <div class="mt-3 flex items-center gap-4 border-t border-line-200/60 pt-3">
                                    <a href="{{ \App\Support\WhatsApp::link($service->whatsappMessage()) }}"
                                        target="_blank" rel="noopener"
                                        class="inline-flex items-center gap-1.5 rounded bg-amber-600 px-3 py-1.5 font-mono text-[10px] font-semibold uppercase tracking-widest text-white transition hover:bg-amber-700">
                                        <svg class="h-3 w-3" fill="currentColor" viewBox="0 0 24 24">
                                            <path
                                                d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z" />
                                        </svg>
                                        Pesan via WhatsApp
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            <a href="{{ route('services.index') }}"
                class="mt-8 block rounded border border-line-200 py-3 text-center font-mono text-xs font-semibold uppercase tracking-widest text-steel-700 sm:hidden">
                Semua Layanan →
            </a>
        </div>
    </section>

    @include('partials.testimonials')

    {{-- CTA --}}
    <section class="bg-steel-900 py-16 text-center text-white sm:py-24">
        <div class="anim anim-scale-in mx-auto max-w-2xl px-4 sm:px-6">
            <h2 class="font-display text-2xl font-bold sm:text-3xl">Butuh Mesin Sesuai Kebutuhan Anda?</h2>
            <p class="mt-4 text-sm text-steel-400 sm:text-base">
                Konsultasikan kebutuhan fabrikasi mesin Anda langsung dengan tim kami.
            </p>
            <a href="{{ $whatsappLink }}" target="_blank" rel="noopener noreferrer"
                class="mt-8 inline-flex w-full items-center justify-center gap-2 rounded bg-amber-600 px-8 py-3.5 font-mono text-xs font-semibold uppercase tracking-widest text-white transition hover:bg-amber-700 active:bg-amber-700 sm:w-auto sm:py-3">
                <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                    <path
                        d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z" />
                </svg>
                Konsultasi Sekarang
            </a>
        </div>
    </section>

    <script>
        (function() {
            const slides = document.querySelectorAll('.hero-slide');
            const dots = document.querySelectorAll('[data-hero-dot]');
            const tagLabel = document.getElementById('hero-tag-label');
            const tagCaption = document.getElementById('hero-tag-caption');
            if (!slides.length || !dots.length) return;

            const data = [{
                    label: 'Gbr. 01',
                    caption: 'Rotary Dryer — Workshop Bandung'
                },
                {
                    label: 'Gbr. 02',
                    caption: 'Mesin Wood Pelet — Produksi Lokal'
                },
                {
                    label: 'Gbr. 03',
                    caption: 'Hammer Mill — Fabrikasi CV Adzra'
                },
                {
                    label: 'Gbr. 04',
                    caption: 'Battery Test — Quality Control'
                },
            ];
            let current = 0;
            let timer;

            function goTo(index) {
                current = index;
                slides.forEach((s, i) => {
                    s.style.opacity = i === current ? '1' : '0';
                });
                dots.forEach((d, i) => {
                    d.className = 'h-1.5 rounded-full transition-all duration-300 ' +
                        (i === current ? 'w-5 bg-amber-500' : 'w-1.5 bg-white/40');
                });
                tagLabel.textContent = data[current].label;
                tagCaption.textContent = data[current].caption;
            }

            function next() {
                goTo((current + 1) % slides.length);
            }

            dots.forEach((d, i) => {
                d.addEventListener('click', () => {
                    goTo(i);
                    resetTimer();
                });
            });

            function resetTimer() {
                clearInterval(timer);
                timer = setInterval(next, 4000);
            }

            resetTimer();
        })();
    </script>
@endsection
