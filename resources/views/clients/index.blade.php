@extends('layouts.app')

@section('title', 'Klien & Agen — CV Adzra Engineering')

@section('content')
    <section class="hero-glow relative overflow-hidden pt-32 pb-16 text-white">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <p class="hero-anim hero-anim-delay-1 label-mono text-amber-600">Dipercaya Oleh</p>
            <h1 class="hero-anim hero-anim-delay-2 mt-3 font-display text-4xl font-bold">Klien &amp; Agen</h1>
            <p class="hero-anim hero-anim-delay-3 mt-3 max-w-2xl text-steel-400">
                Daftar perusahaan yang telah mempercayakan kebutuhan mesin industri mereka kepada CV Adzra Engineering.
            </p>
            <div class="hero-anim hero-anim-delay-4 mt-6 flex flex-wrap gap-3">
                <span
                    class="inline-flex items-center gap-2 rounded border border-white/15 bg-white/5 px-4 py-2 font-mono text-xs font-semibold tracking-wider text-steel-400">
                    <span class="font-display text-base font-bold text-amber-600">{{ $stats['units'] }}</span> Unit Terkirim
                </span>
                <span
                    class="inline-flex items-center gap-2 rounded border border-white/15 bg-white/5 px-4 py-2 font-mono text-xs font-semibold tracking-wider text-steel-400">
                    <span class="font-display text-base font-bold text-amber-600">{{ $stats['cities'] }}</span> Kota Tujuan
                </span>
                <span
                    class="inline-flex items-center gap-2 rounded border border-white/15 bg-white/5 px-4 py-2 font-mono text-xs font-semibold tracking-wider text-steel-400">
                    <span class="font-display text-base font-bold text-amber-600">{{ $stats['provinces'] }}+</span> Provinsi
                </span>
            </div>
        </div>
    </section>

    <section class="bg-paper-100 py-12 sm:py-16">
        <div class="mx-auto max-w-7xl space-y-16 px-4 sm:px-6 lg:px-8">

            {{-- Kategori Mesin Terlaris --}}
            <div class="anim anim-fade-up">
                <div class="mb-6 flex items-end gap-3">
                    <p class="label-mono text-amber-600">Spesialisasi</p>
                    <span class="h-px flex-1 bg-line-200"></span>
                </div>
                <h2 class="font-display text-2xl font-bold text-graphite-900">Kategori Mesin Terlaris</h2>

                <div class="mt-6 grid gap-3 sm:grid-cols-2">
                    @php $maxCount = $categoryCounts->first(); @endphp
                    @foreach ($categoryCounts as $cat => $count)
                        @php $pct = round(($count / $maxCount) * 100); @endphp
                        <div
                            class="group rounded border border-line-200 bg-white px-5 py-4 transition hover:border-steel-700/30">
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-graphite-900">{{ $cat }}</span>
                                <span class="font-mono text-sm font-semibold text-steel-700">{{ $count }} unit</span>
                            </div>
                            <div class="mt-3 h-1.5 overflow-hidden rounded-full bg-line-200">
                                <div class="h-full rounded-full bg-amber-600 transition-all duration-500 group-hover:bg-steel-700"
                                    style="width: {{ $pct }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Tabel 1: Perusahaan yang Sudah Membeli Mesin --}}
            <div class="anim anim-fade-up">
                <div class="mb-6 flex items-end gap-3 mt-10">
                    <p class="label-mono text-amber-600">Daftar Klien</p>
                    <span class="h-px flex-1 bg-line-200"></span>
                </div>
                <h2 class="font-display text-2xl font-bold text-graphite-900">Perusahaan yang Sudah Membeli Mesin</h2>
                <p class="mt-2 text-sm text-graphite-500">{{ count($clients) }} perusahaan tercatat telah melakukan
                    pemesanan.</p>

                <div class="mt-6 overflow-hidden rounded border border-line-200 bg-white">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm">
                            <thead>
                                <tr
                                    class="border-b border-line-200 bg-paper-100 text-xs uppercase tracking-wider text-graphite-500">
                                    <th class="px-5 py-3 w-12 font-semibold">No</th>
                                    <th class="px-5 py-3 font-semibold">Perusahaan</th>
                                    <th class="px-5 py-3 font-semibold">Lokasi</th>
                                    <th class="px-5 py-3 font-semibold">Jenis Mesin</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($clients as $client)
                                    <tr
                                        class="border-b border-line-200/50 transition hover:bg-steel-900/[0.02] {{ $loop->even ? 'bg-paper-100/50' : '' }}">
                                        <td class="px-5 py-3 font-mono text-xs text-graphite-500">
                                            {{ str_pad($client['no'], 2, '0', STR_PAD_LEFT) }}</td>
                                        <td class="px-5 py-3 font-medium text-graphite-900">{{ $client['company'] }}</td>
                                        <td class="px-5 py-3">
                                            <span
                                                class="inline-flex items-center gap-1.5 rounded bg-paper-100 px-2 py-0.5 text-xs text-graphite-500">
                                                <svg class="h-3 w-3 shrink-0 text-graphite-500/60" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                                </svg>
                                                {{ $client['location'] }}
                                            </span>
                                        </td>
                                        <td class="px-5 py-3">
                                            <span
                                                class="inline-flex rounded border border-amber-600/20 bg-amber-600/5 px-2 py-0.5 font-mono text-xs font-medium text-amber-700">
                                                {{ $client['machine'] }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Tabel 2: Referensi Perusahaan --}}
            <div class="anim anim-fade-up">
                <div class="mb-6 flex items-end gap-3 mt-10">
                    <p class="label-mono text-amber-600">Mitra Referensi</p>
                    <span class="h-px flex-1 bg-line-200"></span>
                </div>
                <h2 class="font-display text-2xl font-bold text-graphite-900">Referensi Perusahaan yang Pernah Bekerja Sama
                </h2>

                <div class="mt-6 grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($references as $ref)
                        <div
                            class="plate group anim anim-fade-up anim-delay-{{ min($loop->iteration, 4) }} flex items-center gap-4 rounded bg-white px-5 py-4 transition hover:border-steel-700/30">
                            <span class="plate-corner-bl"></span>
                            <span class="plate-corner-br"></span>
                            <span
                                class="flex h-9 w-9 shrink-0 items-center justify-center rounded border border-line-200 bg-paper-100 font-mono text-xs font-semibold text-graphite-500">
                                {{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}
                            </span>
                            <span class="font-display text-sm font-semibold text-graphite-900">{{ $ref }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

        </div>
    </section>
@endsection
