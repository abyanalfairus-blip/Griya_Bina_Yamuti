@extends('layouts.app')
@section('title', 'Laporan tahun ' . $year)
@push('head')
<style>@media print{header,footer,.no-print,a[aria-label="Chat WhatsApp"]{display:none!important}body{background:#fff!important}main{margin:0}}</style>
@endpush

@section('content')
@include('partials.page-header', ['title' => 'Laporan tahun ' . $year, 'subtitle' => 'Rekapitulasi penghimpunan dan penyaluran donasi ' . setting('site_name') . ' sepanjang tahun ' . $year . '.'])

<section class="mx-auto max-w-5xl px-4 pt-10">
    <div class="no-print mb-6 flex flex-wrap items-center justify-between gap-3">
        <a href="{{ route('reports', ['jenis' => 'tahunan']) }}" class="text-sm font-semibold text-brand-700 hover:underline">Kembali ke daftar laporan</a>
        <div class="flex flex-wrap gap-2">
            @if($year > 2020)<a href="{{ route('reports.year', ['year' => $year - 1]) }}" class="rounded-full border border-brand-200 bg-white px-4 py-2 text-sm font-semibold text-brand-800 hover:bg-brand-50">Tahun {{ $year - 1 }}</a>@endif
            @if($year < now()->year)<a href="{{ route('reports.year', ['year' => $year + 1]) }}" class="rounded-full border border-brand-200 bg-white px-4 py-2 text-sm font-semibold text-brand-800 hover:bg-brand-50">Tahun {{ $year + 1 }}</a>@endif
            <button type="button" onclick="window.print()" class="rounded-full bg-brand-800 px-5 py-2 text-sm font-bold text-white hover:bg-brand-700">Cetak / simpan PDF</button>
        </div>
    </div>

    @if($docs->isNotEmpty())
        <div class="no-print mb-6 rounded-2xl bg-brand-50 p-5">
            <p class="font-semibold text-brand-900">Dokumen laporan tahunan resmi</p>
            <div class="mt-3 flex flex-wrap gap-2">
                @foreach($docs as $doc)
                    @if($doc->link)<a href="{{ $doc->link }}" target="_blank" rel="noopener" class="rounded-full bg-white px-4 py-2 text-sm font-semibold text-brand-800 ring-1 ring-brand-200 hover:bg-brand-100">{{ $doc->title }} ({{ $doc->file ? 'PDF' : 'tautan' }})</a>@endif
                @endforeach
            </div>
        </div>
    @endif

    @include('partials.report-summary', ['s' => $s])

    <section class="mt-8">
        <h2 class="font-display text-xl text-brand-900">Rincian per bulan</h2>
        <div class="mt-3 overflow-x-auto rounded-2xl border border-brand-100 bg-white">
            <table class="w-full text-left text-sm">
                <thead class="bg-brand-50 text-brand-700"><tr><th class="px-4 py-3 font-semibold">Bulan</th><th class="px-4 py-3 text-right font-semibold">Diterima</th><th class="px-4 py-3 text-right font-semibold">Tersalur</th><th class="px-4 py-3 text-right font-semibold">Selisih</th></tr></thead>
                <tbody class="divide-y divide-brand-50">
                    @foreach($rows as $r)
                        <tr>
                            <td class="px-4 py-2.5"><a class="font-semibold text-brand-800 hover:underline" href="{{ route('reports.month', ['year' => $year, 'month' => $r['month']]) }}">{{ $r['label'] }}</a></td>
                            <td class="px-4 py-2.5 text-right">{{ rupiah($r['in']) }}</td>
                            <td class="px-4 py-2.5 text-right">{{ rupiah($r['out']) }}</td>
                            <td class="px-4 py-2.5 text-right {{ $r['in'] - $r['out'] < 0 ? 'text-red-700' : '' }}">{{ rupiah($r['in'] - $r['out']) }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot class="bg-brand-50 font-bold text-brand-900"><tr><td class="px-4 py-3">Total</td><td class="px-4 py-3 text-right">{{ rupiah($s['in']) }}</td><td class="px-4 py-3 text-right">{{ rupiah($s['out']) }}</td><td class="px-4 py-3 text-right">{{ rupiah($s['balance']) }}</td></tr></tfoot>
            </table>
        </div>
    </section>
</section>
@endsection
