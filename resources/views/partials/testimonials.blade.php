@php
    $testimonials = [
        [
            'name' => 'Yusuf Hidayat',
            'role' => 'Kepala Produksi, PT Briket Nusantara',
            'quote' => 'Rotary dryer-nya jalan 24 jam tanpa masalah. Kapasitas produksi kami naik hampir dua kali lipat.',
        ],
        [
            'name' => 'Dedi Kurniawan',
            'role' => 'Pemilik, CV Sumber Kayu Makmur',
            'quote' => 'Mesin dibuat sesuai spek yang kami minta, bukan barang jadi dari katalog. Tim juga bantu setting awal.',
        ],
        [
            'name' => 'Ratna Sari Dewi',
            'role' => 'Manajer Operasional, PT Agro Pelet Sejahtera',
            'quote' => 'Layanan purna jualnya cepat. Ada kendala kecil sebulan setelah instalasi, teknisi langsung datang.',
        ],
        [
            'name' => 'Bambang Setiawan',
            'role' => 'Pemilik, CV Mitra Biomassa',
            'quote' => 'Fabrikasi rapi, sesuai jadwal. Komunikasi dengan tim lancar dari awal hingga mesin terpasang.',
        ],
        [
            'name' => 'Wulan Anggraini',
            'role' => 'Manager, UD Sekar Kayu',
            'quote' => 'Konsultasi awal sangat membantu menentukan spek mesin. Hasil akhirnya sesuai ekspektasi.',
        ],
        [
            'name' => 'Agus Prasetyo',
            'role' => 'Direktur, PT Energi Hijau Lestari',
            'quote' => 'Kualitas las dan material kokoh. Sudah setahun berjalan tanpa perbaikan besar.',
        ],
    ];
@endphp

<section class="bg-white py-16 sm:py-24">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <p class="anim anim-fade-up label-mono">Testimoni</p>
        <h2 class="anim anim-fade-up anim-delay-1 mt-3 font-display text-2xl font-bold text-graphite-900 sm:text-3xl">
            Dipercaya Tim Produksi di Berbagai Skala Usaha
        </h2>
    </div>

    <div class="relative mt-10">
        <div id="testimonial-track"
            class="no-scrollbar flex snap-x snap-mandatory gap-4 overflow-x-auto px-4 pb-4 sm:gap-6 sm:px-6 lg:px-8"
            style="scroll-behavior: smooth;">
            @foreach ($testimonials as $t)
                <article
                    class="w-[85vw] shrink-0 snap-start rounded border border-line-200 bg-paper-100 p-6 sm:w-[calc((100%-3rem)/3)] sm:p-8">
                    <span class="font-display text-4xl leading-none text-amber-600/40">&ldquo;</span>
                    <p class="mt-3 min-h-[4.5rem] text-sm leading-relaxed text-graphite-900 sm:text-base">
                        {{ $t['quote'] }}
                    </p>
                    <div class="mt-6 border-t border-line-200 pt-4">
                        <p class="font-display text-sm font-semibold text-graphite-900">{{ $t['name'] }}</p>
                        <p class="mt-0.5 font-mono text-[10px] uppercase tracking-widest text-graphite-500">{{ $t['role'] }}</p>
                    </div>
                </article>
            @endforeach
        </div>

        <div class="mt-6 flex items-center justify-center gap-3 px-4 sm:px-6 lg:px-8">
            <button id="testimonial-prev" type="button"
                class="flex h-9 w-9 items-center justify-center rounded border border-line-200 bg-white text-graphite-500 transition hover:border-steel-700 hover:text-steel-700 disabled:opacity-30 disabled:hover:border-line-200 disabled:hover:text-graphite-500">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                </svg>
            </button>
            <span id="testimonial-dots" class="flex items-center gap-1.5"></span>
            <button id="testimonial-next" type="button"
                class="flex h-9 w-9 items-center justify-center rounded border border-line-200 bg-white text-graphite-500 transition hover:border-steel-700 hover:text-steel-700 disabled:opacity-30 disabled:hover:border-line-200 disabled:hover:text-graphite-500">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                </svg>
            </button>
        </div>
    </div>
</section>

<style>
    .no-scrollbar {
        scrollbar-width: none;
        -ms-overflow-style: none;
    }
    .no-scrollbar::-webkit-scrollbar {
        display: none;
    }
</style>

<script>
(function () {
    const track = document.getElementById('testimonial-track');
    const prevBtn = document.getElementById('testimonial-prev');
    const nextBtn = document.getElementById('testimonial-next');
    const dotsWrap = document.getElementById('testimonial-dots');
    if (!track || !prevBtn || !nextBtn || !dotsWrap) return;

    const cards = track.querySelectorAll('article');
    const total = cards.length;
    let current = 0;
    let userScrolled = false;

    function getVisible() {
        return window.matchMedia('(min-width: 640px)').matches ? 3 : 1;
    }

    function getPositions() {
        return total - getVisible() + 1;
    }

    function buildDots() {
        dotsWrap.innerHTML = '';
        const positions = getPositions();
        for (let i = 0; i < positions; i++) {
            const dot = document.createElement('span');
            dot.className = 'h-1.5 rounded-full transition-all duration-200 ' +
                (i === current ? 'w-5 bg-steel-700' : 'w-1.5 bg-line-200');
            dotsWrap.appendChild(dot);
        }
    }

    function update() {
        const positions = getPositions();
        current = Math.max(0, Math.min(current, positions - 1));
        const dots = dotsWrap.querySelectorAll('span');
        dots.forEach((d, i) => {
            d.className = 'h-1.5 rounded-full transition-all duration-200 ' +
                (i === current ? 'w-5 bg-steel-700' : 'w-1.5 bg-line-200');
        });
        prevBtn.disabled = current === 0;
        nextBtn.disabled = current >= positions - 1;
    }

    function scrollTo(index) {
        const positions = getPositions();
        current = Math.max(0, Math.min(index, positions - 1));
        cards[current].scrollIntoView({ inline: 'start', block: 'nearest', behavior: 'smooth' });
        update();
    }

    prevBtn.addEventListener('click', () => { userScrolled = true; scrollTo(current - 1); });
    nextBtn.addEventListener('click', () => { userScrolled = true; scrollTo(current + 1); });

    let scrollTimer;
    track.addEventListener('scroll', () => {
        if (!userScrolled) { userScrolled = true; return; }
        clearTimeout(scrollTimer);
        scrollTimer = setTimeout(() => {
            const scrollLeft = track.scrollLeft;
            const gap = parseFloat(getComputedStyle(track).gap) || 16;
            const cardWidth = cards[0].offsetWidth + gap;
            const idx = Math.round(scrollLeft / cardWidth);
            const visible = getVisible();
            const maxIdx = total - visible;
            current = Math.max(0, Math.min(idx, maxIdx));
            update();
        }, 80);
    }, { passive: true });

    let resizeTimer;
    window.addEventListener('resize', () => {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(() => {
            buildDots();
            update();
        }, 150);
    });

    buildDots();
    update();
})();
</script>
