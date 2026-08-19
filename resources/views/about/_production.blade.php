<section class="bg-white py-16 sm:py-20">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="grid items-center gap-8 sm:gap-10 lg:grid-cols-2">
            <div class="anim anim-fade-up">
                <p class="label-mono text-amber-600">02 — Produksi</p>
                <h2 class="mt-3 font-display text-2xl font-bold text-graphite-900 sm:text-3xl">Manufacturing &amp; Assembling</h2>
                <p class="mt-4 text-sm text-graphite-500 leading-relaxed sm:text-base">
                    Tim fabrikasi dan teknisi Adzra Engineering siap melayani Anda dan bisnis Anda:
                </p>
                <ul class="mt-6 space-y-3">
                    @foreach ($productionServices as $i => $service)
                        <li class="flex items-start gap-4 rounded border border-line-200/60 bg-paper-100 px-4 py-3 transition hover:border-steel-700/20">
                            <span class="mt-0.5 flex h-7 w-7 shrink-0 items-center justify-center rounded border border-amber-600/30 bg-amber-600/5 font-mono text-xs font-semibold text-amber-700">
                                {{ $i + 1 }}
                            </span>
                            <div>
                                <p class="font-display text-sm font-semibold text-graphite-900">{{ $service['label'] }}</p>
                                <p class="mt-0.5 text-xs text-graphite-500">{{ $service['description'] }}</p>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
            <div class="anim anim-fade-up anim-delay-1">
                <div class="relative overflow-hidden rounded border border-line-200 bg-paper-100">
                    <span class="plate-corner-bl"></span>
                    <span class="plate-corner-br"></span>
                    <img src="{{ asset('about/pekerja.jpeg') }}" alt="Tim Produksi CV Adzra Engineering"
                         class="w-full" loading="lazy">
                    <div class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-steel-900/60 to-transparent p-4">
                        <p class="label-mono text-amber-600">Tim Produksi</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
