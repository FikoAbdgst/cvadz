@extends('layouts.app')

@section('title', $product->name . ' — CV Adzra Engineering')

@section('content')
    <section class="bg-steel-900 pt-20 pb-4 sm:pt-24 text-white">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <nav class="font-mono text-xs uppercase tracking-widest text-steel-400">
                <a href="{{ route('home') }}" class="transition hover:text-white">Beranda</a>
                <span class="mx-2">/</span>
                <a href="{{ route('products.index') }}" class="transition hover:text-white">Produk</a>
                <span class="mx-2">/</span>
                <span class="text-white">{{ $product->name }}</span>
            </nav>
        </div>
    </section>

    <section class="bg-paper-100 py-12">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="grid gap-10 lg:grid-cols-2">
                <div>
                    @php
                        $images = $product->images;
                        $active = $images->firstWhere('is_primary', true) ?? $images->first();
                    @endphp

                    <div class="relative aspect-[4/3] overflow-hidden rounded border border-line-200 bg-paper-100"
                        id="gallery-main">
                        @if ($active)
                            <img src="{{ asset('storage/' . $active->image_path) }}" alt="{{ $product->name }}"
                                class="img-load h-full w-full object-cover" onload="this.classList.add('loaded')">
                            <div class="skeleton absolute inset-0 rounded"></div>
                        @else
                            <div class="img-placeholder h-full w-full rounded">
                                <svg class="h-12 w-12 text-graphite-500/30" fill="none" stroke="currentColor"
                                    stroke-width="1.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909M3.75 21h16.5a1.5 1.5 0 001.5-1.5V5.25a1.5 1.5 0 00-1.5-1.5H3.75a1.5 1.5 0 00-1.5 1.5v14.25a1.5 1.5 0 001.5 1.5z" />
                                </svg>
                                <span class="font-mono text-xs uppercase tracking-widest text-graphite-500/50">Belum ada
                                    gambar</span>
                            </div>
                        @endif
                    </div>

                    @if ($images->count() > 1)
                        <div class="mt-4 grid grid-cols-5 gap-3" id="gallery-thumbs">
                            @foreach ($images as $image)
                                <button type="button" data-src="{{ asset('storage/' . $image->image_path) }}"
                                    class="aspect-[4/3] overflow-hidden rounded border border-line-200 bg-paper-100 transition hover:border-steel-700 {{ $image->is($active) ? 'border-steel-700 ring-2 ring-steel-700/30' : '' }}">
                                    <img src="{{ asset('storage/' . $image->image_path) }}" alt="{{ $product->name }}"
                                        class="h-full w-full object-cover">
                                </button>
                            @endforeach
                        </div>
                    @endif
                </div>

                <div>
                    <p class="label-mono">{{ $product->category?->name }}</p>
                    <h1 class="mt-2 font-display text-3xl font-bold text-graphite-900 sm:text-4xl">{{ $product->name }}
                    </h1>

                    <p class="mt-4 font-mono text-2xl font-semibold text-steel-700">
                        {{ $product->price ? 'Rp ' . number_format((float) $product->price, 0, ',', '.') : 'Harga: Hubungi kami' }}
                    </p>

                    @if ($product->warranty_months)
                        <div
                            class="mt-3 inline-flex items-center gap-2 rounded border border-line-200 bg-paper-100 px-3 py-2 font-mono text-xs font-semibold uppercase tracking-widest text-steel-700">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                    d="M9 12.75L11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 01-1.043 3.296 3.745 3.745 0 01-3.296 1.043A3.745 3.745 0 0112 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 01-3.296-1.043 3.745 3.745 0 01-1.043-3.296A3.745 3.745 0 013 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 011.043-3.296 3.746 3.746 0 013.296-1.043A3.746 3.746 0 0112 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 013.296 1.043 3.746 3.746 0 011.043 3.296A3.745 3.745 0 0121 12z" />
                            </svg>
                            Garansi {{ $product->warranty_months }} bulan
                        </div>
                    @endif

                    <div class="mt-6 border-t border-line-200 pt-6">
                        <h2 class="font-display text-lg font-semibold text-graphite-900">Deskripsi</h2>
                        <p class="mt-3 whitespace-pre-line leading-relaxed text-graphite-500">
                            {{ $product->description ?: 'Belum ada deskripsi untuk produk ini.' }}
                        </p>
                    </div>

                    <div class="mt-8 flex flex-col gap-4 sm:flex-row">
                        <a href="{{ $whatsappLink }}" target="_blank" rel="noopener noreferrer"
                            class="inline-flex items-center justify-center gap-2 rounded bg-amber-600 px-8 py-3.5 font-mono text-xs font-semibold uppercase tracking-widest text-white transition hover:bg-amber-700">
                            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z" />
                            </svg>
                            Pesan Sekarang
                        </a>
                        <a href="{{ route('products.index') }}"
                            class="inline-flex items-center justify-center rounded border border-line-200 px-8 py-3.5 font-mono text-xs font-semibold uppercase tracking-widest text-graphite-500 transition hover:border-steel-700 hover:text-steel-700">
                            Lihat Produk Lain
                        </a>
                    </div>
                </div>
            </div>

            @if ($product->specifications->isNotEmpty())
                <div class="mt-14">
                    <h2 class="font-display text-2xl font-bold text-graphite-900">Spesifikasi Teknis</h2>

                    @php
                        $hasMatrix = $product->specifications->contains(fn($s) => filled($s->model_name));
                    @endphp

                    @if ($hasMatrix)
                        @php
                            $matrixModels = $product->specifications
                                ->pluck('model_name')
                                ->filter()
                                ->unique()
                                ->values()
                                ->all();
                            $matrixAttrs = $product->specifications
                                ->pluck('spec_key')
                                ->filter()
                                ->unique()
                                ->values()
                                ->all();
                            $matrixValues = $product->specifications->keyBy(
                                fn($s) => $s->model_name . '|||' . $s->spec_key,
                            );
                        @endphp
                        <div class="plate mt-5 rounded">
                            <span class="plate-corner-bl"></span>
                            <span class="plate-corner-br"></span>
                            <div class="overflow-x-auto">
                                <table class="w-full border-collapse font-mono text-sm">
                                    <thead>
                                        <tr>
                                            <th
                                                class="border border-line-200 bg-paper-100 px-4 py-3 text-left font-semibold uppercase tracking-widest text-graphite-500">
                                                Model</th>
                                            @foreach ($matrixModels as $model)
                                                <th
                                                    class="border border-line-200 bg-paper-100 px-4 py-3 text-left font-semibold text-graphite-900">
                                                    {{ $model }}</th>
                                            @endforeach
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($matrixAttrs as $attr)
                                            <tr>
                                                <td
                                                    class="border border-line-200 px-4 py-3 font-semibold text-graphite-900">
                                                    {{ $attr }}</td>
                                                @foreach ($matrixModels as $model)
                                                    <td class="border border-line-200 px-4 py-3 text-graphite-500">
                                                        {{ $matrixValues[$model . '|||' . $attr]?->spec_value ?? '—' }}
                                                    </td>
                                                @endforeach
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @else
                        <div class="plate mt-5 rounded">
                            <span class="plate-corner-bl"></span>
                            <span class="plate-corner-br"></span>
                            <div class="overflow-x-auto">
                                <dl class="font-mono text-sm">
                                    @foreach ($product->specifications as $index => $spec)
                                        <div
                                            class="flex flex-col gap-1 px-5 py-3.5 sm:flex-row sm:gap-0 {{ $index % 2 ? '' : 'bg-paper-100' }}">
                                            <dt class="w-full font-semibold text-graphite-900 sm:w-1/2">
                                                {{ $spec->spec_key }}</dt>
                                            <dd class="text-graphite-500">{{ $spec->spec_value }}</dd>
                                        </div>
                                    @endforeach
                                </dl>
                            </div>
                        </div>
                    @endif
                </div>
            @endif

            @if ($product->videos->isNotEmpty())
                <div class="mt-14">
                    <h2 class="font-display text-2xl font-bold text-graphite-900">Video Produk</h2>
                    <div class="mt-5 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                        @foreach ($product->videos as $video)
                            <div class="plate rounded bg-white">
                                <span class="plate-corner-bl"></span>
                                <span class="plate-corner-br"></span>
                                <div class="aspect-video overflow-hidden bg-paper-100">
                                    @if (str_contains($video->video_url, 'youtube.com') || str_contains($video->video_url, 'youtu.be'))
                                        @php
                                            $videoId = str_contains($video->video_url, 'youtu.be')
                                                ? last(explode('/', parse_url($video->video_url, PHP_URL_PATH)))
                                                : substr(
                                                    parse_url($video->video_url, PHP_URL_QUERY) ?: '',
                                                    strpos(parse_url($video->video_url, PHP_URL_QUERY), 'v=') + 2,
                                                    11,
                                                );
                                        @endphp
                                        <iframe class="h-full w-full"
                                            src="https://www.youtube.com/embed/{{ $videoId }}"
                                            title="{{ $video->caption }}" frameborder="0" allowfullscreen></iframe>
                                    @else
                                        <video class="h-full w-full" controls src="{{ $video->video_url }}"></video>
                                    @endif
                                </div>
                                @if ($video->caption)
                                    <p class="px-4 py-3 font-mono text-xs uppercase tracking-widest text-graphite-500">
                                        {{ $video->caption }}</p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            @if ($relatedProducts->isNotEmpty())
                <div class="mt-14">
                    <h2 class="font-display text-2xl font-bold text-graphite-900">Produk Terkait</h2>
                    <div class="mt-5 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                        @foreach ($relatedProducts as $product)
                            <a href="{{ route('products.show', $product->slug) }}"
                                class="plate group block rounded transition hover:shadow-sm">
                                <span class="plate-corner-bl"></span>
                                <span class="plate-corner-br"></span>
                                <div class="relative aspect-[4/3] overflow-hidden bg-paper-100">
                                    @if ($product->primaryImage())
                                        <img src="{{ asset('storage/' . $product->primaryImage()->image_path) }}"
                                            alt="{{ $product->name }}"
                                            class="img-load h-full w-full object-cover transition duration-300 group-hover:scale-105"
                                            onload="this.classList.add('loaded')">
                                        <div class="skeleton absolute inset-0"></div>
                                    @else
                                        <div class="img-placeholder h-full w-full">
                                            <svg class="h-8 w-8 text-graphite-500/40" fill="none"
                                                stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909M3.75 21h16.5a1.5 1.5 0 001.5-1.5V5.25a1.5 1.5 0 00-1.5-1.5H3.75a1.5 1.5 0 00-1.5 1.5v14.25a1.5 1.5 0 001.5 1.5z" />
                                            </svg>
                                            <span
                                                class="font-mono text-[10px] uppercase tracking-widest text-graphite-500/60">{{ $product->category?->name ?? 'Produk' }}</span>
                                        </div>
                                    @endif
                                </div>
                                <div class="p-5">
                                    <p class="label-mono">{{ $product->category?->name }}</p>
                                    <h3
                                        class="mt-1 font-display text-lg font-semibold text-graphite-900 group-hover:text-steel-700">
                                        {{ $product->name }}</h3>
                                    <p class="mt-1 font-mono text-sm font-semibold text-steel-700">
                                        {{ $product->price ? 'Rp ' . number_format((float) $product->price, 0, ',', '.') : 'Hubungi kami' }}
                                    </p>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </section>
@endsection

@section('scripts')
    <script>
        const thumbs = document.querySelectorAll('#gallery-thumbs button');
        const main = document.querySelector('#gallery-main img');

        thumbs.forEach((thumb) => {
            thumb.addEventListener('click', () => {
                thumbs.forEach((t) => t.classList.remove('border-steel-700', 'ring-2',
                    'ring-steel-700/30'));
                thumb.classList.add('border-steel-700', 'ring-2', 'ring-steel-700/30');
                if (main) main.src = thumb.dataset.src;
            });
        });
    </script>
@endsection
