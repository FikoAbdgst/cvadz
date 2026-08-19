<section class="bg-paper-100 py-16 sm:py-20">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="grid items-center gap-8 sm:gap-10 lg:grid-cols-2">
            <div class="anim anim-fade-up order-2 lg:order-1">
                <div class="relative overflow-hidden rounded border border-line-200 bg-white">
                    <span class="plate-corner-bl"></span>
                    <span class="plate-corner-br"></span>
                    <img src="{{ asset('about/kepemimpinan.jpeg') }}" alt="Tim Kepemimpinan CV Adzra Engineering"
                         class="w-full" loading="lazy">
                    <div class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-steel-900/60 to-transparent p-4">
                        <p class="label-mono text-amber-600">Tim Kepemimpinan</p>
                    </div>
                </div>
            </div>
            <div class="anim anim-fade-up anim-delay-1 order-1 lg:order-2">
                <p class="label-mono text-amber-600">01 — Kepemimpinan</p>
                <h2 class="mt-3 font-display text-2xl font-bold text-graphite-900 sm:text-3xl">Tim Kepemimpinan &amp; Rekayasa</h2>
                <p class="mt-4 text-sm text-graphite-500 leading-relaxed sm:text-base">
                    Tim inti yang mengawal setiap proyek mesin industri CV Adzra Engineering Bandung, dari perencanaan hingga serah terima ke klien:
                </p>
                <ul class="mt-6 space-y-3">
                    @foreach ($leaders as $leader)
                        <li class="flex items-center gap-3 rounded border border-line-200/60 bg-white px-4 py-3 transition hover:border-steel-700/20">
                            <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded bg-steel-900 font-mono text-xs font-semibold text-amber-600">
                                {{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}
                            </span>
                            <div>
                                <p class="font-display text-sm font-semibold text-graphite-900">{{ $leader['name'] }}</p>
                                <p class="text-xs text-graphite-500">{{ $leader['role'] }}</p>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</section>
