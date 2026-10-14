@extends('layouts.app')
@section('title', 'Laporan penyaluran')
@section('content')
@include('partials.page-header', ['title' => 'Laporan penyaluran', 'subtitle' => 'Amanah donatur kami catat dan laporkan secara terbuka.'])

<section class="mx-auto max-w-5xl px-4 pt-10">
    <dl class="grid gap-4 sm:grid-cols-3">
        <div class="rounded-2xl border border-pine-100 bg-white p-5"><dt class="text-sm text-pine-600">Donasi diterima</dt><dd class="mt-1 font-display text-2xl text-pine-900">{{ rupiah($totalIn) }}</dd></div>
        <div class="rounded-2xl border border-pine-100 bg-white p-5"><dt class="text-sm text-pine-600">Dana tersalur</dt><dd class="mt-1 font-display text-2xl text-pine-900">{{ rupiah($totalOut) }}</dd></div>
        <div class="rounded-2xl border border-pine-100 bg-white p-5"><dt class="text-sm text-pine-600">Jumlah donatur</dt><dd class="mt-1 font-display text-2xl text-pine-900">{{ number_format($donorCount, 0, ',', '.') }}</dd></div>
    </dl>

    <h2 class="mt-12 font-display text-2xl text-pine-900">Riwayat penyaluran</h2>
    @forelse($distributions as $d)
        <article class="mt-4 flex flex-col gap-4 rounded-2xl border border-pine-100 bg-white p-5 sm:flex-row">
            @if($d->image)<x-cover :src="$d->image" :alt="$d->title" class="h-40 rounded-xl sm:w-52" />@endif
            <div class="flex-1">
                <p class="text-xs font-medium text-pine-600">{{ $d->distributed_at->translatedFormat('d F Y') }}@if($d->program), {{ $d->program->title }}@endif</p>
                <h3 class="mt-1 font-display text-xl text-pine-900">{{ $d->title }}</h3>
                @if($d->description)<p class="mt-2 text-sm leading-relaxed text-pine-700">{{ $d->description }}</p>@endif
            </div>
            <p class="shrink-0 font-bold text-pine-800 sm:text-right">{{ rupiah($d->amount) }}</p>
        </article>
    @empty
        <p class="mt-6 rounded-2xl border border-dashed border-pine-200 p-12 text-center text-pine-600">Laporan penyaluran akan ditampilkan di sini setelah admin menambahkannya.</p>
    @endforelse
    <div class="mt-8">{{ $distributions->links() }}</div>
</section>
@endsection
