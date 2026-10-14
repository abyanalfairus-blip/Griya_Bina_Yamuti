@extends('layouts.app')
@section('title', 'Laporan')
@section('description', 'Wujud transparansi pengelolaan donasi ' . setting('site_name') . '. Lihat dan unduh laporan bulanan, tahunan, keuangan, serta penyaluran dana.')

@section('content')
@include('partials.page-header', ['title' => 'Laporan ' . setting('site_name'), 'subtitle' => 'Wujud transparansi pengelolaan amanah donatur. Pilih jenis laporan untuk melihat dan mengunduh dokumen yang Anda butuhkan.'])

@php
    $tabs = [
        'bulanan' => ['Laporan Bulanan', 'Ringkasan penghimpunan dan penyaluran donasi setiap bulan.', 'Bulanan'],
        'tahunan' => ['Laporan Tahunan', 'Rangkuman kegiatan dan capaian yayasan sepanjang tahun.', 'Tahunan'],
        'keuangan' => ['Laporan Keuangan', 'Laporan penerimaan dan penggunaan dana yayasan.', 'Keuangan'],
        'penyaluran' => ['Penyaluran Dana', 'Riwayat penyaluran dana ke program dan penerima manfaat.', 'Penyaluran'],
    ];
@endphp

<section class="mx-auto max-w-5xl px-4 pt-10"
         x-data="{ tab: '{{ $tab }}', pick(t) { this.tab = t; history.replaceState(null, '', '?jenis=' + t); } }">

    {{-- Ringkasan angka --}}
    <dl class="grid gap-4 sm:grid-cols-3">
        <div class="rounded-2xl border border-brand-100 bg-white p-5"><dt class="text-sm text-brand-600">Donasi diterima</dt><dd class="mt-1 font-display text-2xl text-brand-900">{{ rupiah($totalIn) }}</dd></div>
        <div class="rounded-2xl border border-brand-100 bg-white p-5"><dt class="text-sm text-brand-600">Dana tersalur</dt><dd class="mt-1 font-display text-2xl text-brand-900">{{ rupiah($totalOut) }}</dd></div>
        <div class="rounded-2xl border border-brand-100 bg-white p-5"><dt class="text-sm text-brand-600">Jumlah donatur</dt><dd class="mt-1 font-display text-2xl text-brand-900">{{ number_format($donorCount, 0, ',', '.') }}</dd></div>
    </dl>

    {{-- Pemilih jenis laporan --}}
    <div class="mt-10 grid grid-cols-2 gap-3 md:grid-cols-4" role="tablist" aria-label="Jenis laporan">
        @foreach($tabs as $key => [$label, $desc, $short])
            @php $count = $key === 'penyaluran' ? $distributions->count() : ($reports[$key] ?? collect())->count(); @endphp
            <button type="button" role="tab" @click="pick('{{ $key }}')" :aria-selected="tab === '{{ $key }}'"
                    :class="tab === '{{ $key }}' ? 'border-brand-700 bg-brand-800 text-white' : 'border-brand-100 bg-white text-brand-800 hover:border-brand-300'"
                    class="rounded-2xl border-2 p-4 text-left transition">
                <span class="block text-xs font-medium" :class="tab === '{{ $key }}' ? 'text-leaf-400' : 'text-brand-600'">{{ $short }}</span>
                <span class="mt-1 block font-display text-lg leading-snug">{{ $label }}</span>
                <span class="mt-1 block text-xs" :class="tab === '{{ $key }}' ? 'text-brand-200' : 'text-brand-600'">{{ $count }} {{ $key === 'penyaluran' ? 'kegiatan' : 'laporan' }}</span>
            </button>
        @endforeach
    </div>

    {{-- Daftar dokumen per jenis --}}
    @foreach(['bulanan', 'tahunan', 'keuangan'] as $type)
        @php $list = $reports[$type] ?? collect(); @endphp
        <div x-show="tab === '{{ $type }}'" @if($tab !== $type) x-cloak @endif role="tabpanel" class="mt-8">
            <div class="flex flex-wrap items-end justify-between gap-2">
                <div>
                    <h2 class="font-display text-2xl text-brand-900">{{ $tabs[$type][0] }}</h2>
                    <p class="mt-1 text-brand-700">{{ $tabs[$type][1] }}</p>
                </div>
                <p class="text-sm font-semibold text-brand-600">{{ $list->count() }} laporan</p>
            </div>

            @php
                $autoCount = $type === 'bulanan' ? collect($periods['months'])->sum(fn ($m) => count($m)) : count($periods['years']);
            @endphp

            @if($type === 'bulanan' && $autoCount > 0)
                <h3 class="mt-8 font-display text-xl text-brand-900">Rekap otomatis dari data donasi</h3>
                <p class="mt-1 text-sm text-brand-600">Dihitung langsung dari donasi yang sudah diterima dan penyaluran dana yang tercatat.</p>
                @foreach($periods['months'] as $year => $months)
                    <p class="mt-5 font-semibold text-brand-800">{{ $year }}</p>
                    <ul class="mt-2 grid gap-3 sm:grid-cols-2">
                        @foreach($months as $ym => $v)
                            <li>
                                <a href="{{ route('reports.month', ['year' => $year, 'month' => (int) substr($ym, 5)]) }}"
                                   class="group block rounded-2xl border border-brand-100 bg-white p-4 transition hover:border-brand-400">
                                    <span class="flex items-center justify-between gap-3">
                                        <span class="font-semibold text-brand-900">{{ \Illuminate\Support\Carbon::create((int) $year, (int) substr($ym, 5), 1)->translatedFormat('F Y') }}</span>
                                        <span class="text-sm font-semibold text-brand-700 group-hover:underline">Lihat rekap</span>
                                    </span>
                                    <span class="mt-2 grid grid-cols-2 gap-2 text-xs text-brand-600">
                                        <span>Diterima<br><strong class="text-sm text-brand-900">{{ rupiah($v['in']) }}</strong></span>
                                        <span>Tersalur<br><strong class="text-sm text-brand-900">{{ rupiah($v['out']) }}</strong></span>
                                    </span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                @endforeach
            @endif

            @if($type === 'tahunan' && $autoCount > 0)
                <h3 class="mt-8 font-display text-xl text-brand-900">Rekap otomatis dari data donasi</h3>
                <p class="mt-1 text-sm text-brand-600">Dihitung langsung dari donasi yang sudah diterima dan penyaluran dana yang tercatat.</p>
                <ul class="mt-4 grid gap-3 sm:grid-cols-2">
                    @foreach($periods['years'] as $year => $v)
                        <li>
                            <a href="{{ route('reports.year', ['year' => $year]) }}"
                               class="group block rounded-2xl border border-brand-100 bg-white p-4 transition hover:border-brand-400">
                                <span class="flex items-center justify-between gap-3">
                                    <span class="font-display text-xl text-brand-900">Tahun {{ $year }}</span>
                                    <span class="text-sm font-semibold text-brand-700 group-hover:underline">Lihat rekap</span>
                                </span>
                                <span class="mt-2 grid grid-cols-2 gap-2 text-xs text-brand-600">
                                    <span>Diterima<br><strong class="text-sm text-brand-900">{{ rupiah($v['in']) }}</strong></span>
                                    <span>Tersalur<br><strong class="text-sm text-brand-900">{{ rupiah($v['out']) }}</strong></span>
                                </span>
                            </a>
                        </li>
                    @endforeach
                </ul>
            @endif

            @if($list->isNotEmpty() && $autoCount > 0)
                <h3 class="mt-10 font-display text-xl text-brand-900">Dokumen laporan</h3>
            @endif

            @forelse($list->groupBy(fn ($r) => $r->period_date->format('Y')) as $year => $items)
                <h3 class="mt-8 font-display text-lg text-brand-800">{{ $year }}</h3>
                <ul class="mt-3 grid gap-3 sm:grid-cols-2">
                    @foreach($items as $r)
                        @if($r->link)
                        <li>
                            <a href="{{ $r->link }}" target="_blank" rel="noopener"
                               class="group flex h-full items-center gap-4 rounded-2xl border border-brand-100 bg-white p-4 transition hover:border-brand-400">
                                <span class="grid h-12 w-12 shrink-0 place-items-center rounded-xl bg-brand-50 text-brand-700 group-hover:bg-brand-800 group-hover:text-leaf-400" aria-hidden="true">
                                    <svg viewBox="0 0 24 24" class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M14 3H7a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V8z"/><path d="M14 3v5h5"/><path d="M9 13h6M9 17h4"/></svg>
                                </span>
                                <span class="min-w-0 flex-1">
                                    <span class="block font-semibold text-brand-900">{{ $r->title }}</span>
                                    <span class="block text-xs text-brand-600">{{ $r->period_label }}@if($r->description), {{ \Illuminate\Support\Str::limit($r->description, 60) }}@endif</span>
                                </span>
                                <span class="shrink-0 text-sm font-semibold text-brand-700">{{ $r->file ? 'Unduh PDF' : 'Buka' }}</span>
                            </a>
                        </li>
                        @endif
                    @endforeach
                </ul>
            @empty
                @if($autoCount === 0)
                    <p class="mt-8 rounded-2xl border border-dashed border-brand-200 p-12 text-center text-brand-600">Belum ada {{ strtolower($tabs[$type][0]) }} yang dipublikasikan.</p>
                @endif
            @endforelse
        </div>
    @endforeach

    {{-- Riwayat penyaluran dana --}}
    <div x-show="tab === 'penyaluran'" @if($tab !== 'penyaluran') x-cloak @endif role="tabpanel" class="mt-8">
        <h2 class="font-display text-2xl text-brand-900">Penyaluran Dana</h2>
        <p class="mt-1 text-brand-700">{{ $tabs['penyaluran'][1] }}</p>

        @forelse($distributions as $d)
            <article class="mt-4 flex flex-col gap-4 rounded-2xl border border-brand-100 bg-white p-5 sm:flex-row">
                @if($d->image)<x-cover :src="$d->image" :alt="$d->title" class="h-40 rounded-xl sm:w-52" />@endif
                <div class="flex-1">
                    <p class="text-xs font-medium text-brand-600">{{ $d->distributed_at->translatedFormat('d F Y') }}@if($d->program), {{ $d->program->title }}@endif</p>
                    <h3 class="mt-1 font-display text-xl text-brand-900">{{ $d->title }}</h3>
                    @if($d->description)<p class="mt-2 text-sm leading-relaxed text-brand-700">{{ $d->description }}</p>@endif
                </div>
                <p class="shrink-0 font-bold text-brand-800 sm:text-right">{{ rupiah($d->amount) }}</p>
            </article>
        @empty
            <p class="mt-8 rounded-2xl border border-dashed border-brand-200 p-12 text-center text-brand-600">Laporan penyaluran akan ditampilkan di sini setelah admin menambahkannya.</p>
        @endforelse
    </div>
</section>
@endsection
