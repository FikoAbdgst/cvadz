@extends('layouts.admin')

@section('title', 'Tambah Produk — CV Adzra Engineering')
@section('page', 'Tambah Produk')

@section('content')
    @if ($errors->any())
        <div class="mb-6 rounded-lg border border-red-200 bg-red-50 p-3 text-sm text-red-600">
            <ul class="list-inside list-disc space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @include('admin.products._form')
@endsection
