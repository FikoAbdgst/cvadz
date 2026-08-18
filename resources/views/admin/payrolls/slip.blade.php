@extends('layouts.admin')

@section('title', 'Slip Gaji — CV Adzra Engineering')
@section('page', 'Slip Gaji')

@section('content')
    <div class="mb-4 flex items-center gap-3 print:hidden">
        <a href="{{ route('admin.payrolls.slip-pdf', $payroll) }}"
            class="rounded-lg bg-steel-700 px-6 py-2.5 text-sm font-semibold text-white transition hover:bg-steel-900">
            Download PDF
        </a>
        <button type="button" onclick="window.print()"
            class="rounded-lg border border-line-200 px-6 py-2.5 text-sm font-medium text-graphite-500 transition hover:text-steel-700">
            Cetak
        </button>
        <a href="{{ route('admin.payrolls.index', ['period' => $payroll->period]) }}"
            class="rounded-lg border border-line-200 px-6 py-2.5 text-sm font-medium text-graphite-500 transition hover:text-steel-700">
            Kembali
        </a>
    </div>

    @include('admin.payrolls._slip-print')
@endsection
