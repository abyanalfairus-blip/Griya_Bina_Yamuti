@extends('admin.layout')
@section('title', 'Donasi masuk')
@section('heading', 'Donasi masuk')
@section('content')
<div class="mb-5 flex flex-col gap-3 rounded-2xl border border-leaf-300/60 bg-leaf-100/50 p-4 sm:flex-row sm:items-center sm:justify-between">
    <p class="text-sm text-brand-800"><span class="font-semibold">Ada donatur yang lapor lewat WhatsApp atau memberi tunai?</span> Catat di sini agar ikut terhitung di program dan laporan.</p>
    <a href="{{ route('admin.donations.create') }}" class="inline-flex shrink-0 items-center justify-center gap-2 rounded-full bg-leaf-600 px-5 py-2.5 text-sm font-bold text-white hover:bg-leaf-500">
        <svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"><path d="M12 5v14M5 12h14"/></svg>Catat donasi WA / manual
    </a>
</div>

<form method="GET" class="mb-5 flex flex-wrap gap-3">
    <input type="search" name="q" value="{{ request('q') }}" placeholder="Cari nama atau nomor donasi" class="w-full rounded-full border border-brand-200 bg-white px-5 py-2.5 text-sm sm:w-72">
    <select name="status" onchange="this.form.submit()" class="rounded-full border border-brand-200 bg-white px-5 py-2.5 text-sm">
        <option value="">Semua status</option>
        @foreach(\App\Models\Donation::STATUSES as $k => $v)<option value="{{ $k }}" @selected(request('status') === $k)>{{ $v }}</option>@endforeach
    </select>
    <button class="rounded-full bg-brand-800 px-5 py-2.5 text-sm font-bold text-white">Cari</button>
</form>

<div class="overflow-x-auto rounded-2xl border border-brand-100 bg-white">
    <table class="w-full text-left text-sm">
        <thead class="bg-brand-50 text-brand-700"><tr>
            <th class="px-4 py-3 font-semibold">Nomor</th><th class="px-4 py-3 font-semibold">Donatur</th><th class="px-4 py-3 font-semibold">Tujuan</th>
            <th class="px-4 py-3 font-semibold">Nominal</th><th class="px-4 py-3 font-semibold">Status</th><th class="px-4 py-3 font-semibold">Tanggal</th><th class="px-4 py-3"></th>
        </tr></thead>
        <tbody class="divide-y divide-brand-50">
            @forelse($donations as $d)
                @php $c = ['pending' => 'bg-leaf-100 text-leaf-600', 'review' => 'bg-blue-100 text-blue-800', 'paid' => 'bg-brand-100 text-brand-800', 'rejected' => 'bg-red-100 text-red-800'][$d->status] ?? ''; @endphp
                <tr>
                    <td class="px-4 py-3 font-mono text-xs">{{ $d->invoice }}</td>
                    <td class="px-4 py-3 font-semibold">{{ $d->donor_name }}@if($d->is_anonymous) <span class="text-xs font-normal text-brand-500">(anonim)</span>@endif
                        @if(($d->source ?? 'website') !== 'website')<span class="ml-1 rounded-full bg-leaf-100 px-2 py-0.5 text-[11px] font-bold text-leaf-600">{{ $d->source_label }}</span>@endif</td>
                    <td class="max-w-[14rem] truncate px-4 py-3 text-brand-700">{{ $d->type_label }}: {{ $d->program?->title ?? 'Umum' }}</td>
                    <td class="px-4 py-3 font-semibold">{{ rupiah($d->total_transfer) }}</td>
                    <td class="px-4 py-3"><span class="rounded-full px-2.5 py-1 text-xs font-bold {{ $c }}">{{ $d->status_label }}</span></td>
                    <td class="px-4 py-3 text-brand-600">{{ $d->created_at->translatedFormat('d M Y') }}</td>
                    <td class="px-4 py-3 text-right"><a href="{{ route('admin.donations.show', $d) }}" class="font-semibold text-brand-700 hover:underline">Periksa</a></td>
                </tr>
            @empty
                <tr><td colspan="7" class="px-4 py-10 text-center text-brand-600">Tidak ada donasi.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mt-6">{{ $donations->links() }}</div>
@endsection
