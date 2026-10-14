@extends('admin.layout')
@section('title', 'Donasi masuk')
@section('heading', 'Donasi masuk')
@section('content')
<form method="GET" class="mb-5 flex flex-wrap gap-3">
    <input type="search" name="q" value="{{ request('q') }}" placeholder="Cari nama atau nomor donasi" class="w-full rounded-full border border-pine-200 bg-white px-5 py-2.5 text-sm sm:w-72">
    <select name="status" onchange="this.form.submit()" class="rounded-full border border-pine-200 bg-white px-5 py-2.5 text-sm">
        <option value="">Semua status</option>
        @foreach(\App\Models\Donation::STATUSES as $k => $v)<option value="{{ $k }}" @selected(request('status') === $k)>{{ $v }}</option>@endforeach
    </select>
    <button class="rounded-full bg-pine-800 px-5 py-2.5 text-sm font-bold text-white">Cari</button>
</form>

<div class="overflow-x-auto rounded-2xl border border-pine-100 bg-white">
    <table class="w-full text-left text-sm">
        <thead class="bg-pine-50 text-pine-700"><tr>
            <th class="px-4 py-3 font-semibold">Nomor</th><th class="px-4 py-3 font-semibold">Donatur</th><th class="px-4 py-3 font-semibold">Tujuan</th>
            <th class="px-4 py-3 font-semibold">Nominal</th><th class="px-4 py-3 font-semibold">Status</th><th class="px-4 py-3 font-semibold">Tanggal</th><th class="px-4 py-3"></th>
        </tr></thead>
        <tbody class="divide-y divide-pine-50">
            @forelse($donations as $d)
                @php $c = ['pending' => 'bg-marigold-100 text-marigold-600', 'review' => 'bg-blue-100 text-blue-800', 'paid' => 'bg-pine-100 text-pine-800', 'rejected' => 'bg-red-100 text-red-800'][$d->status] ?? ''; @endphp
                <tr>
                    <td class="px-4 py-3 font-mono text-xs">{{ $d->invoice }}</td>
                    <td class="px-4 py-3 font-semibold">{{ $d->donor_name }}@if($d->is_anonymous) <span class="text-xs font-normal text-pine-500">(anonim)</span>@endif</td>
                    <td class="max-w-[14rem] truncate px-4 py-3 text-pine-700">{{ $d->type_label }}: {{ $d->program?->title ?? 'Umum' }}</td>
                    <td class="px-4 py-3 font-semibold">{{ rupiah($d->total_transfer) }}</td>
                    <td class="px-4 py-3"><span class="rounded-full px-2.5 py-1 text-xs font-bold {{ $c }}">{{ $d->status_label }}</span></td>
                    <td class="px-4 py-3 text-pine-600">{{ $d->created_at->translatedFormat('d M Y') }}</td>
                    <td class="px-4 py-3 text-right"><a href="{{ route('admin.donations.show', $d) }}" class="font-semibold text-pine-700 hover:underline">Periksa</a></td>
                </tr>
            @empty
                <tr><td colspan="7" class="px-4 py-10 text-center text-pine-600">Tidak ada donasi.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mt-6">{{ $donations->links() }}</div>
@endsection
