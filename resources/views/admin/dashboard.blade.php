@extends('admin.layout')
@section('title', 'Dasbor')
@section('heading', 'Dasbor')
@section('content')
@php $max = max(1, $months->max('value')); @endphp
<div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
    @foreach([
        ['Total donasi diterima', rupiah($stats['collected']), null],
        ['Donasi perlu ditindak', $stats['pending'], route('admin.donations.index', ['status' => 'review'])],
        ['Program aktif', $stats['programs'], route('admin.programs.index')],
        ['Pesan belum dibaca', $stats['messages'], route('admin.messages.index')],
    ] as [$label, $value, $link])
        <div class="rounded-2xl border border-pine-100 bg-white p-5">
            <p class="text-sm text-pine-600">{{ $label }}</p>
            <p class="mt-1 font-display text-3xl text-pine-900">{{ $value }}</p>
            @if($link)<a href="{{ $link }}" class="mt-2 inline-block text-xs font-semibold text-pine-700 underline">Buka</a>@endif
        </div>
    @endforeach
</div>

@if($stats['review'] > 0)
    <a href="{{ route('admin.donations.index', ['status' => 'review']) }}" class="mt-5 block rounded-xl bg-marigold-100 p-4 text-sm font-semibold text-pine-900">{{ $stats['review'] }} donasi sudah mengunggah bukti transfer dan menunggu verifikasi Anda.</a>
@endif

<div class="mt-6 grid gap-6 xl:grid-cols-5">
    <section class="rounded-2xl border border-pine-100 bg-white p-6 xl:col-span-2">
        <h2 class="font-display text-lg text-pine-900">Donasi diterima, 6 bulan terakhir</h2>
        <div class="mt-6 flex h-48 items-end gap-3">
            @foreach($months as $m)
                <div class="flex flex-1 flex-col items-center gap-2">
                    <div class="w-full rounded-t-lg bg-pine-600" style="height: {{ max(3, $m['value'] / $max * 100) }}%" title="{{ rupiah($m['value']) }}"></div>
                    <span class="text-xs text-pine-600">{{ $m['label'] }}</span>
                </div>
            @endforeach
        </div>
    </section>

    <section class="rounded-2xl border border-pine-100 bg-white p-6 xl:col-span-3">
        <h2 class="font-display text-lg text-pine-900">Donasi terbaru</h2>
        <div class="mt-4 overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="text-pine-600"><tr><th class="py-2 pr-4 font-medium">Donatur</th><th class="py-2 pr-4 font-medium">Program</th><th class="py-2 pr-4 font-medium">Nominal</th><th class="py-2 font-medium">Status</th></tr></thead>
                <tbody class="divide-y divide-pine-50">
                    @forelse($latest as $d)
                        <tr>
                            <td class="py-2.5 pr-4"><a href="{{ route('admin.donations.show', $d) }}" class="font-semibold text-pine-800 hover:underline">{{ $d->donor_name }}</a></td>
                            <td class="max-w-[12rem] truncate py-2.5 pr-4 text-pine-700">{{ $d->program?->title ?? 'Umum' }}</td>
                            <td class="py-2.5 pr-4 font-semibold">{{ rupiah($d->amount) }}</td>
                            <td class="py-2.5">{{ $d->status_label }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="py-6 text-center text-pine-600">Belum ada donasi.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
</div>
@endsection
