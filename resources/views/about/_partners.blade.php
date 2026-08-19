<section class="bg-white py-16 sm:py-20">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="anim anim-fade-up">
            <p class="label-mono text-amber-600">04 — Kolaborasi</p>
            <h2 class="mt-3 font-display text-2xl font-bold text-graphite-900 sm:text-3xl">Mitra Manufaktur Internasional</h2>
            <p class="mt-4 max-w-2xl text-sm text-graphite-500 leading-relaxed sm:text-base">
                Adzra Engineering hadir dengan dukungan teknologi dari mitra manufaktur di:
            </p>
        </div>

        <div class="mt-8 grid grid-cols-1 gap-3 sm:grid-cols-2 sm:gap-4 lg:grid-cols-3">
            @foreach ($partners as $i => $partner)
                <div class="group anim anim-fade-up anim-delay-{{ min($i + 1, 4) }} flex items-center gap-4 rounded border border-line-200 bg-paper-100 px-5 py-4 transition hover:border-steel-700/30 hover:bg-white">
                    <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded bg-steel-900 font-mono text-xs font-semibold text-amber-600">
                        {{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}
                    </span>
                    <div class="min-w-0">
                        <p class="truncate font-display text-sm font-semibold text-graphite-900">{{ $partner['name'] }}</p>
                        <p class="label-mono text-steel-400">{{ $partner['country'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
