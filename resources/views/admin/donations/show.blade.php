@extends('admin.layout')
@section('title', 'Donasi ' . $donation->invoice)
@section('heading', 'Donasi ' . $donation->invoice)
@section('content')
<div class="grid gap-6 lg:grid-cols-2">
    <section class="rounded-2xl border border-pine-100 bg-white p-6">
        <h2 class="font-display text-lg text-pine-900">Rincian</h2>
        <dl class="mt-4 grid grid-cols-3 gap-y-3 text-sm">
            @foreach([
                'Donatur' => $donation->donor_name . ($donation->is_anonymous ? ' (ingin anonim)' : ''),
                'WhatsApp' => $donation->donor_phone ?: '-',
                'Email' => $donation->donor_email ?: '-',
                'Jenis' => $donation->type_label,
                'Program' => $donation->program?->title ?? 'Umum',
                'Nominal' => rupiah($donation->amount),
                'Kode unik' => $donation->unique_code,
                'Total transfer' => rupiah($donation->total_transfer),
                'Metode' => $donation->paymentMethod?->name ?? '-',
                'Dibuat' => $donation->created_at->translatedFormat('d F Y, H:i'),
                'Pesan' => $donation->message ?: '-',
            ] as $k => $v)
                <dt class="text-pine-600">{{ $k }}</dt><dd class="col-span-2 font-semibold">{{ $v }}</dd>
            @endforeach
        </dl>
        @if($donation->donor_phone)
            <a target="_blank" rel="noopener" href="https://wa.me/{{ preg_replace('/^0/', '62', preg_replace('/\D/', '', $donation->donor_phone)) }}?text={{ urlencode('Assalamualaikum ' . $donation->donor_name . ', terima kasih atas donasi ' . $donation->invoice . ' di ' . setting('site_name') . '.') }}" class="mt-5 inline-block rounded-full border border-pine-300 px-5 py-2 text-sm font-semibold text-pine-800 hover:bg-pine-50">Hubungi via WhatsApp</a>
        @endif
    </section>

    <section class="space-y-6">
        <div class="rounded-2xl border border-pine-100 bg-white p-6">
            <h2 class="font-display text-lg text-pine-900">Bukti transfer</h2>
            @if($donation->proof)
                <a href="{{ \Illuminate\Support\Facades\Storage::url($donation->proof) }}" target="_blank"><img src="{{ \Illuminate\Support\Facades\Storage::url($donation->proof) }}" alt="Bukti transfer" class="mt-4 max-h-96 rounded-lg border border-pine-100"></a>
            @else
                <p class="mt-3 text-sm text-pine-600">Donatur belum mengunggah bukti transfer.</p>
            @endif
        </div>

        <form method="POST" action="{{ route('admin.donations.update', $donation) }}" class="rounded-2xl border border-pine-100 bg-white p-6">
            @csrf @method('PUT')
            <h2 class="font-display text-lg text-pine-900">Verifikasi</h2>
            <label class="mt-4 block text-sm font-semibold">Status
                <select name="status" class="mt-1.5 w-full rounded-xl border border-pine-200 px-4 py-3 font-normal">
                    @foreach(\App\Models\Donation::STATUSES as $k => $v)<option value="{{ $k }}" @selected($donation->status === $k)>{{ $v }}</option>@endforeach
                </select>
            </label>
            <label class="mt-4 block text-sm font-semibold">Catatan untuk donatur (tampil jika ditolak)
                <textarea name="admin_note" rows="3" class="mt-1.5 w-full rounded-xl border border-pine-200 px-4 py-3 font-normal">{{ old('admin_note', $donation->admin_note) }}</textarea>
            </label>
            <p class="mt-3 text-xs text-pine-600">Dana program otomatis dihitung ulang dari donasi berstatus Diterima.</p>
            <button class="mt-4 rounded-full bg-pine-800 px-6 py-3 text-sm font-bold text-white hover:bg-pine-700">Simpan status</button>
        </form>

        <form method="POST" action="{{ route('admin.donations.destroy', $donation) }}" onsubmit="return confirm('Hapus donasi ini secara permanen?')">
            @csrf @method('DELETE')
            <button class="text-sm font-semibold text-red-700 hover:underline">Hapus donasi ini</button>
        </form>
    </section>
</div>
@endsection
