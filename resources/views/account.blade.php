@extends('layouts.app')
@section('title', 'Akun saya')
@section('content')
@include('partials.page-header', ['title' => 'Halo, ' . \Illuminate\Support\Str::before(auth()->user()->name, ' '), 'subtitle' => auth()->user()->email])

<section class="mx-auto max-w-4xl px-4 pt-10">
    <div class="grid gap-4 sm:grid-cols-3">
        <div class="rounded-2xl border border-brand-100 bg-white p-5">
            <p class="text-sm text-brand-600">Total donasi diterima</p>
            <p class="mt-1 font-display text-2xl text-brand-800">{{ rupiah($totalPaid) }}</p>
        </div>
        <div class="rounded-2xl border border-brand-100 bg-white p-5">
            <p class="text-sm text-brand-600">Jumlah donasi</p>
            <p class="mt-1 font-display text-2xl text-brand-800">{{ $donations->count() }}</p>
        </div>
        <a href="{{ route('donation.create') }}" class="flex items-center justify-center gap-2 rounded-2xl bg-leaf-400 p-5 font-bold text-brand-950 transition hover:bg-leaf-300"><x-icon name="heart" />Berdonasi lagi</a>
    </div>

    <h2 class="mt-12 font-display text-2xl text-brand-900">Riwayat donasi</h2>
    <ul class="mt-4 divide-y divide-brand-100 overflow-hidden rounded-2xl border border-brand-100 bg-white">
        @forelse($donations as $d)
            <li>
                <a href="{{ route('donation.show', $d->invoice) }}" class="flex flex-wrap items-center justify-between gap-3 p-4 transition hover:bg-brand-50">
                    <div class="min-w-0">
                        <p class="font-semibold text-brand-900">{{ $d->program?->title ?? $d->type_label }}</p>
                        <p class="text-xs text-brand-600">{{ $d->invoice }} &middot; {{ $d->created_at->translatedFormat('d F Y') }}</p>
                    </div>
                    <div class="text-right">
                        <p class="font-bold text-brand-800">{{ rupiah($d->amount) }}</p>
                        <span class="mt-1 inline-block rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $d->status === 'paid' ? 'bg-leaf-100 text-leaf-600' : ($d->status === 'rejected' ? 'bg-red-100 text-red-700' : 'bg-brand-100 text-brand-700') }}">{{ \App\Models\Donation::STATUSES[$d->status] ?? $d->status }}</span>
                    </div>
                </a>
            </li>
        @empty
            <li class="p-8 text-center text-sm text-brand-600">Belum ada donasi yang tercatat di akun ini. Donasi yang Anda kirim saat masuk akan muncul di sini.</li>
        @endforelse
    </ul>

    <form method="POST" action="{{ route('logout') }}" class="mt-8">@csrf
        <button class="flex items-center gap-2 rounded-full border border-brand-300 px-5 py-2.5 text-sm font-semibold text-brand-800 hover:bg-brand-50"><x-icon name="log-out" class="h-[18px] w-[18px]" />Keluar</button>
    </form>
</section>
@endsection
