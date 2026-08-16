@extends('layouts.admin')

@section('title', 'Tambah Akun — CV Adzra Engineering')
@section('page', 'Tambah Akun')

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

        @include('admin.users._form')
    </div>
@endsection
