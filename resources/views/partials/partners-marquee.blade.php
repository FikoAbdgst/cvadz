@php
    $items = $partners ?? [];
    $loopItems = array_merge($items, $items);
    $duration = max(36, count($items) * 4);
@endphp

<section class="border-b border-line-200 bg-white py-8 sm:py-10">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <p class="text-center font-mono text-[11px] uppercase tracking-widest text-graphite-500 sm:text-left">
            Dipercaya Bermitra Dengan
        </p>
    </div>

    <div
        class="relative mt-5 overflow-hidden [mask-image:linear-gradient(to_right,transparent,black_8%,black_92%,transparent)]">
        <div class="marquee-track flex w-max items-center gap-8">
            @foreach ($loopItems as $partner)
                <span class="shrink-0 font-mono text-sm font-medium uppercase tracking-wide text-graphite-500/70">
                    {{ $partner }}
                </span>
            @endforeach
        </div>
    </div>
</section>

<style>
    .marquee-track {
        animation: marquee-scroll {{ $duration }}s linear infinite;
    }

    .marquee-track:hover {
        animation-play-state: paused;
    }

    @keyframes marquee-scroll {
        from {
            transform: translateX(0);
        }

        to {
            transform: translateX(-50%);
        }
    }

    @media (prefers-reduced-motion: reduce) {
        .marquee-track {
            animation: none;
        }
    }
</style>
