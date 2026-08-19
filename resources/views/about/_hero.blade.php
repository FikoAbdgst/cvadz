<section class="hero-glow relative overflow-hidden pt-32 pb-16 text-white">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <p class="hero-anim hero-anim-delay-1 label-mono text-amber-600">Tentang Kami</p>
        <h1 class="hero-anim hero-anim-delay-2 mt-3 font-display text-4xl font-bold sm:text-5xl">{{ config('company.name') }}</h1>
        <p class="hero-anim hero-anim-delay-3 mt-1 font-display text-lg text-steel-400">Bandung</p>
        <p class="hero-anim hero-anim-delay-4 mt-4 max-w-3xl text-steel-400">
            Bengkel fabrikasi dan manufaktur mesin industri di Padalarang, Jawa Barat — melayani sektor agro, biomassa, tekstil, sawit, dan perikanan di seluruh Indonesia sejak {{ config('company.founded') }}.
        </p>
        <div class="hero-anim hero-anim-delay-5 mt-6 flex flex-wrap gap-3">
            <span class="inline-flex items-center gap-2 rounded border border-white/15 bg-white/5 px-4 py-2 font-mono text-xs font-semibold tracking-wider text-steel-400">
                <span class="h-1 w-1 rounded-full bg-amber-600"></span>
                Berdiri {{ config('company.founded') }}
            </span>
            <span class="inline-flex items-center gap-2 rounded border border-white/15 bg-white/5 px-4 py-2 font-mono text-xs font-semibold tracking-wider text-steel-400">
                <span class="h-1 w-1 rounded-full bg-amber-600"></span>
                Resmi Beroperasi {{ config('company.operational_since') }}
            </span>
            <span class="inline-flex items-center gap-2 rounded border border-white/15 bg-white/5 px-4 py-2 font-mono text-xs font-semibold tracking-wider text-steel-400">
                <span class="h-1 w-1 rounded-full bg-amber-600"></span>
                Basis Padalarang, Jawa Barat
            </span>
        </div>
    </div>
</section>
