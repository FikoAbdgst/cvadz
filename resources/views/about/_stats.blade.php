<section class="bg-steel-900 py-16 text-white sm:py-20">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="anim anim-fade-up text-center">
            <p class="label-mono text-amber-600">Fakta Adzra Engineering</p>
            <h2 class="mt-3 font-display text-2xl font-bold sm:text-3xl">Biarlah Angka yang Berbicara</h2>
        </div>

        <div class="mt-10 grid grid-cols-1 gap-4 sm:grid-cols-3 sm:gap-6">
            @foreach ($stats as $i => $stat)
                <div class="anim anim-fade-up anim-delay-{{ $i + 1 }} rounded border border-white/10 bg-white/5 px-6 py-8 text-center">
                    <p class="font-display text-4xl font-bold text-amber-600 sm:text-5xl">{{ $stat['value'] }}<span class="text-lg sm:text-xl">{{ $stat['suffix'] }}</span></p>
                    <p class="label-mono mt-2 text-steel-400">{{ $stat['label'] }}</p>
                </div>
            @endforeach
        </div>

        <div class="mt-12 grid gap-6 sm:gap-8 lg:grid-cols-2">
            <div class="anim anim-fade-up anim-delay-2 rounded border border-white/10 bg-white/5 p-6">
                <div class="flex items-start gap-3">
                    <span class="mt-1 flex h-2 w-2 shrink-0 rounded-full bg-amber-600"></span>
                    <div>
                        <h3 class="font-display text-lg font-semibold">Solusi Ramah Lingkungan dari Indonesia</h3>
                        <p class="mt-2 text-sm text-steel-400 leading-relaxed">
                            Adzra Engineering mendukung industri yang lebih ramah lingkungan dengan mengganti bahan bakar batu bara dengan wood pellet — bahan bakar padat dari limbah serbuk gergaji yang lebih awet dan rendah emisi.
                        </p>
                    </div>
                </div>
            </div>
            <div class="anim anim-fade-up anim-delay-3 rounded border border-white/10 bg-white/5 p-6">
                <div class="flex items-start gap-3">
                    <span class="mt-1 flex h-2 w-2 shrink-0 rounded-full bg-amber-600"></span>
                    <div>
                        <h3 class="font-display text-lg font-semibold">Ahli Mesin Siap Membantu Anda</h3>
                        <p class="mt-2 text-sm text-steel-400 leading-relaxed">
                            Tim teknisi dan jasa maintenance kami siap menjaga mesin tetap optimal di lokasi Anda, serta membantu modifikasi mesin sesuai kebutuhan dan spesifikasi bisnis Anda.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="anim anim-fade-up anim-delay-4 mt-10 border-t border-white/10 pt-8 text-center">
            <p class="text-sm text-steel-400 leading-relaxed sm:text-base">
                CV Adzra Engineering Bandung adalah partner mesin industri, fabrikasi, dan jasa perbaikan yang siap mengawal perjalanan bisnis Anda.
            </p>
            <p class="mt-2 text-sm text-steel-400 leading-relaxed sm:text-base">
                Solusi Mesin Industri untuk Kebutuhan Bisnis Anda — Biomass, Agro, Tekstil, Sawit, dan Perikanan.
            </p>
        </div>
    </div>
</section>
