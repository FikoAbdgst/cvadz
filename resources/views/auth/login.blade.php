@extends('layouts.app')

@section('title', 'Masuk Admin — CV Adzra Engineering')

@section('content')
    <section class="relative overflow-hidden bg-steel-900 pt-32 pb-16 bg-[linear-gradient(to_right,rgba(255,255,255,.04)_1px,transparent_1px),linear-gradient(to_bottom,rgba(255,255,255,.04)_1px,transparent_1px)] bg-[size:32px_32px]">
        <div class="mx-auto max-w-md px-4 sm:px-6">
            <div class="plate rounded bg-white p-8">
                <span class="plate-corner-bl"></span>
                <span class="plate-corner-br"></span>
                <div class="text-center">
                    <span class="mx-auto flex h-14 w-14 items-center justify-center">
                        <img src="{{ asset('logo.png') }}" alt="Logo CV Adzra Engineering" class="h-full w-full object-contain">
                    </span>
                    <h1 class="mt-4 font-display text-2xl font-bold text-graphite-900">Masuk Admin</h1>
                    <p class="mt-1 font-mono text-xs uppercase tracking-widest text-graphite-500">Kelola produk, pemesanan, dan laporan penjualan</p>
                </div>

                @if ($errors->any())
                    <div class="mt-6 rounded border border-red-200 bg-red-50 p-3 text-sm text-red-600">
                        <ul class="list-inside list-disc space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('login.attempt') }}" class="mt-6 space-y-5">
                    @csrf

                    <div>
                        <label for="email" class="block font-mono text-xs font-semibold uppercase tracking-widest text-graphite-900">Email</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                               class="mt-1 block w-full rounded border border-line-200 px-3 py-2.5 text-sm text-graphite-900 placeholder-graphite-500 focus:border-steel-700 focus:outline-none focus:ring-2 focus:ring-steel-700/20">
                    </div>

                    <div>
                        <label for="password" class="block font-mono text-xs font-semibold uppercase tracking-widest text-graphite-900">Password</label>
                        <input type="password" id="password" name="password" required autocomplete="current-password"
                               class="mt-1 block w-full rounded border border-line-200 px-3 py-2.5 text-sm text-graphite-900 placeholder-graphite-500 focus:border-steel-700 focus:outline-none focus:ring-2 focus:ring-steel-700/20">
                    </div>

                    <div class="flex items-center justify-between">
                        <label class="flex items-center gap-2 font-mono text-xs uppercase tracking-widest text-graphite-500">
                            <input type="checkbox" name="remember" class="rounded border-line-200 text-steel-700 focus:ring-steel-700">
                            Ingat saya
                        </label>
                        <a href="{{ route('home') }}" class="font-mono text-xs font-semibold uppercase tracking-widest text-steel-700 hover:underline">Kembali ke beranda</a>
                    </div>

                    <button type="submit"
                            class="w-full rounded bg-amber-600 px-6 py-3 font-mono text-xs font-semibold uppercase tracking-widest text-white transition hover:bg-amber-700">
                        Masuk
                    </button>
                </form>
            </div>
        </div>
    </section>
@endsection
