@php
    $waNumber = config('company.whatsapp');
    $waMessage = urlencode('Halo CV Adzra Engineering, saya ingin berkonsultasi mengenai kebutuhan mesin industri.');
    $waLink = "https://wa.me/{$waNumber}?text={$waMessage}";
@endphp

<section class="bg-paper-100 py-16 sm:py-20">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="anim anim-fade-up text-center">
            <p class="label-mono text-amber-600">Hubungi Kami</p>
            <h2 class="mt-3 font-display text-2xl font-bold text-graphite-900 sm:text-3xl">Siap Memulai Proyek Anda?</h2>
            <p class="mt-4 mx-auto max-w-2xl text-sm text-graphite-500 leading-relaxed sm:text-base">
                Konsultasikan kebutuhan mesin industri Anda secara gratis — tim kami siap membantu merancang solusi yang tepat untuk bisnis Anda.
            </p>
        </div>

        <div class="anim anim-fade-up anim-delay-1 mt-10 mx-auto max-w-4xl">
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                <div class="rounded border border-line-200 bg-white px-5 py-5">
                    <div class="flex items-start gap-3">
                        <span class="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded bg-steel-900">
                            <svg class="h-4 w-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        </span>
                        <div>
                            <p class="label-mono text-amber-600">Office</p>
                            <p class="mt-1.5 text-sm leading-relaxed text-graphite-500">{{ config('company.office') }}</p>
                        </div>
                    </div>
                </div>

                <div class="rounded border border-line-200 bg-white px-5 py-5">
                    <div class="flex items-start gap-3">
                        <span class="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded bg-steel-900">
                            <svg class="h-4 w-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><circle cx="12" cy="12" r="3"/></svg>
                        </span>
                        <div>
                            <p class="label-mono text-amber-600">Workshop</p>
                            <p class="mt-1.5 text-sm leading-relaxed text-graphite-500">{{ config('company.workshop') }}</p>
                        </div>
                    </div>
                </div>

                <div class="rounded border border-line-200 bg-white px-5 py-5">
                    <div class="flex items-start gap-3">
                        <span class="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded bg-steel-900">
                            <svg class="h-4 w-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        </span>
                        <div>
                            <p class="label-mono text-amber-600">Email</p>
                            <p class="mt-1.5 text-sm leading-relaxed text-graphite-500">{{ config('company.email') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-8 flex justify-center">
                <a href="{{ $waLink }}" target="_blank" rel="noopener"
                   class="inline-flex items-center gap-2 rounded bg-amber-600 px-6 py-3 font-mono text-sm font-semibold uppercase tracking-widest text-white transition hover:bg-amber-700">
                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.52.149-.174.198-.298.297-.497.1-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                    </svg>
                    Hubungi Kami via WhatsApp
                </a>
            </div>
        </div>
    </div>
</section>
