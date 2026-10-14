@php $maxProgram = max(1, $s['byProgram']->max() ?? 1); @endphp
<dl class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
    <div class="rounded-2xl border border-brand-100 bg-white p-5"><dt class="text-sm text-brand-600">Donasi diterima</dt><dd class="mt-1 font-display text-2xl text-brand-900">{{ rupiah($s['in']) }}</dd></div>
    <div class="rounded-2xl border border-brand-100 bg-white p-5"><dt class="text-sm text-brand-600">Dana tersalur</dt><dd class="mt-1 font-display text-2xl text-brand-900">{{ rupiah($s['out']) }}</dd></div>
    <div class="rounded-2xl border border-brand-100 bg-white p-5"><dt class="text-sm text-brand-600">Selisih (diterima dikurangi tersalur)</dt><dd class="mt-1 font-display text-2xl {{ $s['balance'] < 0 ? 'text-red-700' : 'text-brand-900' }}">{{ rupiah($s['balance']) }}</dd></div>
    <div class="rounded-2xl border border-brand-100 bg-white p-5"><dt class="text-sm text-brand-600">Jumlah donasi diterima</dt><dd class="mt-1 font-display text-2xl text-brand-900">{{ number_format($s['count'], 0, ',', '.') }}</dd></div>
</dl>

<div class="mt-8 grid gap-6 lg:grid-cols-2">
    <section class="rounded-2xl border border-brand-100 bg-white p-6">
        <h2 class="font-display text-xl text-brand-900">Donasi per jenis</h2>
        <table class="mt-4 w-full text-left text-sm">
            <tbody class="divide-y divide-brand-50">
                @forelse($s['byType'] as $type => $amount)
                    <tr><td class="py-2.5 text-brand-800">{{ \App\Models\Donation::TYPES[$type] ?? ucfirst($type) }}</td><td class="py-2.5 text-right font-semibold">{{ rupiah($amount) }}</td></tr>
                @empty
                    <tr><td class="py-4 text-brand-600">Belum ada donasi pada periode ini.</td></tr>
                @endforelse
            </tbody>
        </table>
    </section>

    <section class="rounded-2xl border border-brand-100 bg-white p-6">
        <h2 class="font-display text-xl text-brand-900">Donasi per program</h2>
        <ul class="mt-4 space-y-4">
            @forelse($s['byProgram'] as $title => $amount)
                <li>
                    <div class="flex justify-between gap-3 text-sm"><span class="text-brand-800">{{ $title }}</span><span class="shrink-0 font-semibold">{{ rupiah($amount) }}</span></div>
                    <div class="mt-1.5 h-2 overflow-hidden rounded-full bg-brand-100"><div class="h-full rounded-full bg-brand-600" style="width: {{ max(3, $amount / $maxProgram * 100) }}%"></div></div>
                </li>
            @empty
                <li class="text-sm text-brand-600">Belum ada donasi pada periode ini.</li>
            @endforelse
        </ul>
    </section>
</div>

<section class="mt-8">
    <h2 class="font-display text-xl text-brand-900">Penyaluran dana</h2>
    @if($s['distributions']->isEmpty())
        <p class="mt-3 rounded-2xl border border-dashed border-brand-200 p-8 text-center text-sm text-brand-600">Belum ada penyaluran dana yang tercatat pada periode ini.</p>
    @else
        <ul class="mt-3 divide-y divide-brand-100 rounded-2xl border border-brand-100 bg-white">
            @foreach($s['distributions'] as $d)
                <li class="flex items-start justify-between gap-4 p-4">
                    <div>
                        <p class="font-semibold text-brand-900">{{ $d->title }}</p>
                        <p class="text-xs text-brand-600">{{ $d->distributed_at->translatedFormat('d F Y') }}@if($d->program), {{ $d->program->title }}@endif</p>
                        @if($d->description)<p class="mt-1 text-sm text-brand-700">{{ $d->description }}</p>@endif
                    </div>
                    <p class="shrink-0 font-bold text-brand-800">{{ rupiah($d->amount) }}</p>
                </li>
            @endforeach
        </ul>
    @endif
</section>

<p class="mt-6 rounded-xl bg-brand-50 p-4 text-xs leading-relaxed text-brand-700">Rekap ini dihitung otomatis dari donasi berstatus Diterima (berdasarkan tanggal verifikasi) dan penyaluran yang dicatat admin. Rekap ini bukan laporan keuangan yang diaudit.</p>
