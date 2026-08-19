<section class="bg-white py-16 sm:py-20">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="anim anim-fade-up text-center">
            <p class="label-mono text-amber-600">Cakupan Industri</p>
            <h2 class="mt-3 font-display text-2xl font-bold text-graphite-900 sm:text-3xl">Sektor yang Kami Layani</h2>
            <p class="mt-4 mx-auto max-w-2xl text-sm text-graphite-500 leading-relaxed sm:text-base">
                Dari perancangan, fabrikasi, hingga jasa perbaikan — mesin kami bekerja di lini produksi berbagai sektor industri berikut.
            </p>
        </div>

        <div class="mt-10 grid grid-cols-2 gap-3 sm:grid-cols-3 sm:gap-4 lg:grid-cols-5">
            @foreach ($industries as $i => $industry)
                <div class="group anim anim-fade-up anim-delay-{{ min($i + 1, 5) }} rounded border border-line-200 bg-paper-100 px-4 py-5 text-center transition hover:border-steel-700/30 hover:bg-white sm:px-5 sm:py-6">
                    <p class="font-mono text-xs font-semibold text-amber-600">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</p>
                    <p class="mt-2 text-sm font-semibold text-graphite-900 sm:font-display">{{ $industry }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>
