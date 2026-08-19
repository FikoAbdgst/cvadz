<footer class="border-t border-white/10 bg-steel-900 text-white">
    <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
        <div class="grid gap-10 md:grid-cols-3">
            <div>
                <div class="flex items-center gap-3">
                    <span class="flex h-9 w-9 items-center justify-center">
                        <img src="{{ asset('logo.png') }}" alt="Logo CV Adzra Engineering" class="h-full w-full object-contain">
                    </span>
                    <span class="font-display text-lg font-bold">{{ config('company.name') }}</span>
                </div>
                <p class="mt-4 text-sm leading-relaxed text-steel-400">
                    Fabrikasi dan jasa pembuatan mesin industri di Bandung — rotary dryer, mesin pelet, dan mesin industri lainnya.
                </p>
            </div>

            <div>
                <h3 class="label-mono text-steel-400">Kontak</h3>
                <ul class="mt-4 space-y-2 text-sm text-steel-400">
                    <li>Office: {{ config('company.office') }}</li>
                    <li>Workshop: {{ config('company.workshop') }}</li>
                    <li>Telepon: {{ config('company.phone') }}</li>
                    <li>Email: {{ config('company.email') }}</li>
                </ul>
            </div>

            <div>
                <h3 class="label-mono text-steel-400">Navigasi</h3>
                <ul class="mt-4 space-y-2 text-sm text-steel-400">
                    <li><a href="{{ route('home') }}" class="transition hover:text-white">Beranda</a></li>
                    <li><a href="{{ route('products.index') }}" class="transition hover:text-white">Produk &amp; Sparepart</a></li>
                    <li><a href="{{ route('services.index') }}" class="transition hover:text-white">Layanan</a></li>
                    <li><a href="{{ route('clients.index') }}" class="transition hover:text-white">Klien &amp; Agen</a></li>
                    <li><a href="{{ route('login') }}" class="transition hover:text-white">Area Admin</a></li>
                </ul>
            </div>
        </div>

        <div class="mt-10 border-t border-white/10 pt-6 text-center text-xs text-steel-400">
            &copy; {{ date('Y') }} {{ config('company.name') }} Bandung. Seluruh hak cipta dilindungi.
        </div>
    </div>
</footer>
