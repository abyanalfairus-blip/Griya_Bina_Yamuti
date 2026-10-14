@extends('layouts.app')
@section('title', 'Laporan bulan ' . $from->translatedFormat('F Y'))
@push('head')
<style>@media print{header,footer,.no-print,a[aria-label="Chat WhatsApp"]{display:none!important}body{background:#fff!important}main{margin:0}}</style>
@endpush

@section('content')
@include('partials.page-header', ['title' => 'Laporan bulan ' . $from->translatedFormat('F Y'), 'subtitle' => 'Rekapitulasi penghimpunan dan penyaluran donasi ' . setting('site_name') . ' pada bulan ini.'])

<section class="mx-auto max-w-5xl px-4 pt-10">
    <div class="no-print mb-6 flex flex-wrap items-center justify-between gap-3">
        <a href="{{ route('reports', ['jenis' => 'bulanan']) }}" class="text-sm font-semibold text-brand-700 hover:underline">Kembali ke daftar laporan</a>
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('reports.month', ['year' => $prev->year, 'month' => $prev->month]) }}" class="rounded-full border border-brand-200 bg-white px-4 py-2 text-sm font-semibold text-brand-800 hover:bg-brand-50">{{ $prev->translatedFormat('F Y') }}</a>
            @if($next)<a href="{{ route('reports.month', ['year' => $next->year, 'month' => $next->month]) }}" class="rounded-full border border-brand-200 bg-white px-4 py-2 text-sm font-semibold text-brand-800 hover:bg-brand-50">{{ $next->translatedFormat('F Y') }}</a>@endif
            <a href="{{ route('reports.year', ['year' => $from->year]) }}" class="rounded-full border border-brand-200 bg-white px-4 py-2 text-sm font-semibold text-brand-800 hover:bg-brand-50">Rekap tahun {{ $from->year }}</a>
            <button type="button" onclick="window.print()" class="rounded-full bg-brand-800 px-5 py-2 text-sm font-bold text-white hover:bg-brand-700">Cetak / simpan PDF</button>
        </div>
    </div>

    @if($docs->isNotEmpty())
        <div class="no-print mb-6 rounded-2xl bg-brand-50 p-5">
            <p class="font-semibold text-brand-900">Dokumen laporan resmi bulan ini</p>
            <div class="mt-3 flex flex-wrap gap-2">
                @foreach($docs as $doc)
                    @if($doc->link)<a href="{{ $doc->link }}" target="_blank" rel="noopener" class="rounded-full bg-white px-4 py-2 text-sm font-semibold text-brand-800 ring-1 ring-brand-200 hover:bg-brand-100">{{ $doc->title }} ({{ $doc->file ? 'PDF' : 'tautan' }})</a>@endif
                @endforeach
            </div>
        </div>
    @endif

    @include('partials.report-summary', ['s' => $s])
</section>
@endsection
