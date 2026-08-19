<section class="bg-paper-100 py-16 sm:py-20">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="anim anim-fade-up text-center">
            <p class="label-mono text-amber-600">Arah Perusahaan</p>
            <h2 class="mt-3 font-display text-2xl font-bold text-graphite-900 sm:text-3xl">Visi &amp; Misi</h2>
        </div>

        <div class="mt-10 space-y-6">
            <div class="anim anim-fade-up anim-delay-1 rounded border border-amber-600/20 bg-white p-6 sm:p-8">
                <div class="flex items-start gap-4">
                    <span class="mt-1 flex h-10 w-10 shrink-0 items-center justify-center rounded bg-amber-600 font-display text-lg font-bold text-white">V</span>
                    <div>
                        <h3 class="font-display text-lg font-bold text-graphite-900">Visi</h3>
                        <p class="mt-3 text-sm text-graphite-500 leading-relaxed sm:text-base">{{ $vision }}</p>
                    </div>
                </div>
            </div>

            <div class="anim anim-fade-up anim-delay-2 rounded border border-line-200 bg-white p-6 sm:p-8">
                <div class="flex items-start gap-4">
                    <span class="mt-1 flex h-10 w-10 shrink-0 items-center justify-center rounded bg-steel-900 font-display text-lg font-bold text-amber-600">M</span>
                    <div>
                        <h3 class="font-display text-lg font-bold text-graphite-900">Misi</h3>
                        <ol class="mt-3 space-y-3">
                            @foreach ($missions as $mission)
                                <li class="flex items-start gap-3">
                                    <span class="mt-1.5 flex h-1.5 w-1.5 shrink-0 rounded-full bg-amber-600"></span>
                                    <p class="text-sm text-graphite-500 leading-relaxed">{{ $mission }}</p>
                                </li>
                            @endforeach
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
