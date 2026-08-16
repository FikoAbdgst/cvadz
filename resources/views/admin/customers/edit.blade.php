@extends('layouts.admin')

@section('title', 'Edit Pelanggan — CV Adzra Engineering')
@section('page', 'Edit Pelanggan')

@section('content')
    <div class="mx-auto max-w-2xl rounded border border-line-200 bg-white p-6 shadow-sm sm:p-8">
        @if ($errors->any())
            <div class="mb-6 rounded-lg border border-red-200 bg-red-50 p-3 text-sm text-red-600">
                <ul class="list-inside list-disc space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.customers.update', $customer) }}" class="space-y-5">
            @csrf
            @method('PUT')

            <div>
                <label for="name" class="block text-sm font-medium text-graphite-900">Nama</label>
                <input type="text" id="name" name="name" value="{{ old('name', $customer->name) }}" required
                       class="mt-1 block w-full rounded-lg border border-line-200 px-3 py-2.5 text-sm focus:border-steel-700 focus:outline-none focus:ring-2 focus:ring-steel-700/20">
            </div>

            <div>
                <label for="phone" class="block text-sm font-medium text-graphite-900">Nomor Telepon / WhatsApp</label>
                <input type="text" id="phone" name="phone" value="{{ old('phone', $customer->phone) }}" required
                       class="mt-1 block w-full rounded-lg border border-line-200 px-3 py-2.5 text-sm focus:border-steel-700 focus:outline-none focus:ring-2 focus:ring-steel-700/20">
            </div>

            <div>
                <label for="email" class="block text-sm font-medium text-graphite-900">Email <span class="text-graphite-500">(opsional)</span></label>
                <input type="email" id="email" name="email" value="{{ old('email', $customer->email) }}"
                       class="mt-1 block w-full rounded-lg border border-line-200 px-3 py-2.5 text-sm focus:border-steel-700 focus:outline-none focus:ring-2 focus:ring-steel-700/20">
            </div>

            <div>
                <label for="address" class="block text-sm font-medium text-graphite-900">Alamat <span class="text-graphite-500">(opsional)</span></label>
                <input type="text" id="address" name="address" value="{{ old('address', $customer->address) }}"
                       class="mt-1 block w-full rounded-lg border border-line-200 px-3 py-2.5 text-sm focus:border-steel-700 focus:outline-none focus:ring-2 focus:ring-steel-700/20">
            </div>

            <div class="flex items-center gap-3">
                <button type="submit" class="rounded-lg bg-steel-700 px-6 py-2.5 text-sm font-semibold text-white transition hover:bg-steel-900">Simpan</button>
                <a href="{{ route('admin.sales.index', ['tab' => 'pelanggan']) }}" class="rounded-lg border border-line-200 px-6 py-2.5 text-sm font-medium text-graphite-500 transition hover:text-steel-700">Batal</a>
            </div>
        </form>
    </div>
@endsection
