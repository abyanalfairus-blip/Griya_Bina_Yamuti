@extends('layouts.app')
@section('title', 'Donasi ' . $donation->invoice)
@section('content')
@php
    $badge = ['pending' => 'bg-marigold-100 text-marigold-600', 'review' => 'bg-blue-100 text-blue-800', 'paid' => 'bg-pine-100 text-pine-800', 'rejected' => 'bg-red-100 text-red-800'][$donation->status] ?? 'bg-pine-100';
    $m = $donation->paymentMethod;
@endphp
<section class="mx-auto max-w-3xl px-4 pt-10">
    <div class="flex flex-wrap items-center justify-between gap-3">
        <div>
            <p class="text-sm text-pine-600">Nomor donasi</p>
            <h1 class="font-display text-3xl text-pine-900">{{ $donation->invoice }}</h1>
        </div>
        <span class="rounded-full px-4 py-1.5 text-sm font-bold {{ $badge }}">{{ $donation->status_label }}</span>
    </div>

    @if($donation->status === 'paid')
        <div class="mt-6 rounded-2xl bg-pine-800 p-6 text-white">
            <p class="font-display text-2xl">Jazakumullahu khairan, {{ $donation->display_name }}.</p>
            <p class="mt-2 text-pine-200">Donasi Anda sudah kami terima dan tercatat. Semoga Allah membalas dengan pahala yang terus mengalir dan melapangkan rezeki Anda.</p>
        </div>
    @endif

    <div class="mt-6 rounded-2xl border border-pine-100 bg-white p-6">
        <p class="text-sm text-pine-600">Total yang harus ditransfer</p>
        <p class="mt-1 font-display text-4xl text-pine-900">{{ rupiah($donation->total_transfer) }}</p>
        <p class="mt-2 text-sm text-pine-700">Donasi {{ rupiah($donation->amount) }} ditambah kode unik <strong>{{ $donation->unique_code }}</strong>. Mohon transfer sesuai angka tepat agar mudah diverifikasi.</p>
        <dl class="mt-5 grid gap-3 border-t border-pine-100 pt-5 text-sm sm:grid-cols-2">
            <div><dt class="text-pine-600">Jenis</dt><dd class="font-semibold">{{ $donation->type_label }}</dd></div>
            <div><dt class="text-pine-600">Program</dt><dd class="font-semibold">{{ $donation->program?->title ?? 'Umum' }}</dd></div>
            <div><dt class="text-pine-600">Atas nama</dt><dd class="font-semibold">{{ $donation->display_name }}</dd></div>
            <div><dt class="text-pine-600">Dibuat</dt><dd class="font-semibold">{{ $donation->created_at->translatedFormat('d F Y, H:i') }}</dd></div>
        </dl>
    </div>

    @if($m && $donation->status !== 'paid')
    <div class="mt-6 rounded-2xl border border-pine-100 bg-white p-6">
        <h2 class="font-display text-xl text-pine-900">Transfer ke</h2>
        <div class="mt-4 flex flex-col gap-5 sm:flex-row sm:items-center">
            @if($m->qr_image)
                <img src="{{ \Illuminate\Support\Facades\Storage::url($m->qr_image) }}" alt="Kode QR {{ $m->name }}" class="h-44 w-44 rounded-xl border border-pine-100 object-contain p-2">
            @endif
            <div x-data="{ copied:false }">
                <p class="font-semibold text-pine-800">{{ $m->name }}</p>
                @if($m->account_number)
                    <p class="mt-1 font-mono text-2xl font-bold tracking-wide text-pine-900" x-ref="acc">{{ $m->account_number }}</p>
                    <button type="button" @click="navigator.clipboard.writeText($refs.acc.innerText); copied=true; setTimeout(()=>copied=false,2000)" class="mt-2 rounded-full border border-pine-300 px-4 py-1.5 text-xs font-semibold text-pine-800 hover:bg-pine-50">
                        <span x-show="!copied">Salin nomor</span><span x-show="copied" x-cloak>Tersalin</span>
                    </button>
                @endif
                @if($m->account_name)<p class="mt-3 text-sm text-pine-700">a.n. {{ $m->account_name }}</p>@endif
                @if($m->instructions)<p class="mt-2 text-sm text-pine-600">{{ $m->instructions }}</p>@endif
            </div>
        </div>
    </div>
    @endif

    @if(in_array($donation->status, ['pending', 'review', 'rejected']))
    <form method="POST" action="{{ route('donation.proof', $donation) }}" enctype="multipart/form-data" class="mt-6 rounded-2xl border border-pine-100 bg-white p-6">
        @csrf
        <h2 class="font-display text-xl text-pine-900">{{ $donation->proof ? 'Ganti bukti transfer' : 'Unggah bukti transfer' }}</h2>
        @if($donation->status === 'rejected' && $donation->admin_note)
            <p class="mt-2 rounded-lg bg-red-50 p-3 text-sm text-red-800">Catatan admin: {{ $donation->admin_note }}</p>
        @endif
        @error('proof')<p class="mt-2 text-sm text-red-700" role="alert">{{ $message }}</p>@enderror
        @if($donation->proof)
            <img src="{{ \Illuminate\Support\Facades\Storage::url($donation->proof) }}" alt="Bukti transfer" class="mt-4 max-h-56 rounded-lg border border-pine-100">
        @endif
        <input type="file" name="proof" accept="image/*" required class="mt-4 block w-full text-sm file:mr-4 file:rounded-full file:border-0 file:bg-pine-100 file:px-4 file:py-2 file:font-semibold file:text-pine-800">
        <button class="mt-4 rounded-full bg-pine-800 px-6 py-3 text-sm font-bold text-white hover:bg-pine-700">Kirim bukti transfer</button>
    </form>
    @endif

    <p class="mt-6 text-center text-sm text-pine-600">Simpan alamat halaman ini untuk memantau status donasi Anda. Butuh bantuan? <a class="font-semibold text-pine-800 underline" href="https://wa.me/{{ setting('whatsapp') }}" target="_blank" rel="noopener">Hubungi kami via WhatsApp</a>.</p>
</section>
@endsection
