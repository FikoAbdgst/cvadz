@extends('layouts.app')

@section('title', 'Katalog Produk — CV Adzra Engineering')

@section('content')
    <section class="hero-glow relative overflow-hidden pt-32 pb-16 text-white">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <p class="label-mono text-amber-600">Katalog Mesin &amp; Sparepart</p>
            <h1 class="mt-3 font-display text-4xl font-bold">Produk &amp; Sparepart</h1>
            <p class="mt-3 max-w-2xl text-steel-400">Jelajahi mesin industri dan sparepart yang kami sediakan. Hubungi kami via WhatsApp untuk info lebih lanjut.</p>

            <form method="GET" action="{{ route('products.index') }}" class="mt-8 flex flex-col gap-3 sm:flex-row">
                <input type="search" name="q" value="{{ $search }}" placeholder="Cari mesin..." autocomplete="off"
                       class="w-full rounded border-0 px-4 py-3 text-sm text-graphite-900 placeholder-graphite-500 focus:outline-none focus:ring-2 focus:ring-steel-400 sm:max-w-sm">
                <button type="submit" class="rounded bg-amber-600 px-6 py-3 font-mono text-xs font-semibold uppercase tracking-widest text-white transition hover:bg-amber-700">
                    Cari
                </button>
            </form>
        </div>
    </section>

    <section class="bg-paper-100 py-12">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex flex-wrap items-center gap-2">
                <a href="{{ route('products.index') }}"
                   class="rounded border px-4 py-1.5 font-mono text-xs font-semibold uppercase tracking-widest transition {{ blank($activeCategory) ? 'border-steel-700 bg-steel-700 text-white' : 'border-line-200 bg-white text-graphite-500 hover:border-steel-700 hover:text-steel-700' }}">
                    Semua
                </a>
                @foreach ($categories as $category)
                    <a href="{{ route('products.index', ['category' => $category->slug]) }}"
                       class="rounded border px-4 py-1.5 font-mono text-xs font-semibold uppercase tracking-widest transition {{ $activeCategory === $category->slug ? 'border-steel-700 bg-steel-700 text-white' : 'border-line-200 bg-white text-graphite-500 hover:border-steel-700 hover:text-steel-700' }}">
                        {{ $category->name }}
                    </a>
                @endforeach
            </div>

            @if ($products->isEmpty())
                <div class="plate rounded p-12 text-center">
                    <span class="plate-corner-bl"></span>
                    <span class="plate-corner-br"></span>
                    <p class="font-display text-lg font-semibold text-graphite-900">Produk tidak ditemukan</p>
                    <p class="mt-2 text-graphite-500">Coba kata kunci atau kategori lain.</p>
                </div>
            @else
                <div class="mt-8 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($products as $product)
                        <a href="{{ route('products.show', $product->slug) }}"
                           class="plate group block rounded transition hover:shadow-sm">
                            <span class="plate-corner-bl"></span>
                            <span class="plate-corner-br"></span>
                            <div class="relative aspect-[4/3] overflow-hidden bg-paper-100">
                                @if ($product->primaryImage())
                                    <img src="{{ asset('storage/'.$product->primaryImage()->image_path) }}" alt="{{ $product->name }}" class="h-full w-full object-cover transition duration-300 group-hover:scale-105">
                                @else
                                    <div class="flex h-full items-center justify-center font-display text-sm font-semibold text-graphite-500">{{ $product->name }}</div>
                                @endif
                                @if ($product->warranty_months)
                                    <span class="absolute left-3 top-3 rounded bg-steel-900/85 px-2 py-1 font-mono text-[10px] font-semibold uppercase tracking-widest text-white">
                                        Garansi {{ $product->warranty_months }} bln
                                    </span>
                                @endif
                            </div>
                            <div class="p-5">
                                <p class="label-mono">{{ $product->category?->name }}</p>
                                <h3 class="mt-1 font-display text-lg font-semibold text-graphite-900 group-hover:text-steel-700">{{ $product->name }}</h3>
                                <div class="mt-3 flex items-center justify-between border-t border-line-200 pt-3">
                                    <span class="font-mono text-sm font-semibold text-steel-700">
                                        {{ $product->price ? 'Rp '.number_format((float) $product->price, 0, ',', '.') : 'Hubungi kami' }}
                                    </span>
                                    <span class="label-mono text-amber-600">Tanya →</span>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>

                <div class="mt-10">
                    {{ $products->links() }}
                </div>
            @endif
        </div>
    </section>
@endsection
