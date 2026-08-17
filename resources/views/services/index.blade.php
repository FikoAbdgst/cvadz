@extends('layouts.app')

@section('title', 'Katalog Layanan — CV Adzra Engineering')

@section('content')
    <section class="hero-glow relative overflow-hidden pt-32 pb-16 text-white">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <p class="hero-anim hero-anim-delay-1 label-mono text-amber-600">Layanan Kami</p>
            <h1 class="hero-anim hero-anim-delay-2 mt-3 font-display text-4xl font-bold">Katalog Layanan</h1>
            <p class="hero-anim hero-anim-delay-3 mt-3 max-w-2xl text-steel-400">
                Dari mesin custom hingga perawatan berkala — setiap layanan dikerjakan langsung oleh teknisi CV Adzra Engineering. Hubungi kami via WhatsApp untuk konsultasi gratis.
            </p>
        </div>
    </section>

    <section class="bg-paper-100 py-12">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            @if ($services->isEmpty())
                <div class="plate rounded p-12 text-center">
                    <span class="plate-corner-bl"></span>
                    <span class="plate-corner-br"></span>
                    <p class="font-display text-lg font-semibold text-graphite-900">Layanan belum tersedia</p>
                    <p class="mt-2 text-graphite-500">Silakan hubungi kami untuk informasi lebih lanjut.</p>
                </div>
            @else
                <div class="grid gap-6 sm:grid-cols-2">
                    @foreach ($services as $service)
                        <div class="plate group anim anim-fade-up anim-delay-{{ min($loop->iteration, 4) }} flex flex-col rounded bg-white p-6">
                            <span class="plate-corner-bl"></span>
                            <span class="plate-corner-br"></span>

                            <div class="flex items-start justify-between gap-4">
                                <span class="label-mono text-amber-600">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                                <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded border border-line-200 bg-paper-100 text-steel-700">
                                    @if (str_contains($service->slug, 'custom'))
                                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                            <circle cx="12" cy="12" r="3"/>
                                        </svg>
                                    @elseif (str_contains($service->slug, 'instalasi') || str_contains($service->slug, 'listrik'))
                                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                        </svg>
                                    @elseif (str_contains($service->slug, 'panel'))
                                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <rect x="3" y="3" width="18" height="18" rx="2" stroke-width="1.5"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 3v18M12 12h9M12 16h9M12 8h9M9 12H3"/>
                                        </svg>
                                    @else
                                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11.42 15.17L17.25 21A2.652 2.652 0 0021 17.25l-5.877-5.877M11.42 15.17l2.496-3.03c.317-.384.74-.626 1.208-.766M11.42 15.17l-4.655 5.653a2.548 2.548 0 11-3.586-3.586l6.837-5.63m5.108-.233c.55-.164 1.163-.188 1.743-.14a4.5 4.5 0 004.486-6.336l-3.276 3.277a3.004 3.004 0 01-2.25-2.25l3.276-3.276a4.5 4.5 0 00-6.336 4.486c.091 1.076-.071 2.264-.904 2.95l-.102.085"/>
                                        </svg>
                                    @endif
                                </span>
                            </div>

                            <h2 class="mt-4 font-display text-xl font-semibold text-graphite-900">{{ $service->name }}</h2>
                            @if ($service->category)
                                <p class="label-mono mt-2 text-steel-700">{{ $service->category->name }}</p>
                            @endif
                            <p class="mt-2 flex-1 text-sm leading-relaxed text-graphite-500">{{ $service->description }}</p>

                            <div class="mt-6 flex items-center justify-between gap-4 border-t border-line-200 pt-4">
                                <span class="font-mono text-sm font-semibold text-steel-700">
                                    {{ $service->price ? 'Rp '.number_format((float) $service->price, 0, ',', '.') : 'Konsultasi gratis' }}
                                </span>
                                <a href="{{ $whatsappLinks[$service->id] }}" target="_blank" rel="noopener"
                                   class="inline-flex items-center gap-2 rounded bg-amber-600 px-4 py-2 font-mono text-xs font-semibold uppercase tracking-widest text-white transition hover:bg-amber-700">
                                    <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.52.149-.174.198-.298.297-.497.1-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                                    </svg>
                                    Pesan via WhatsApp
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </section>
@endsection
