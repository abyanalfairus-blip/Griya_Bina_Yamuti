{{-- Kerangka halaman masuk/daftar: panel merek di kiri, formulir di kanan. --}}
@extends('layouts.app')
@section('title', $heading)
@section('content')
<section class="mx-auto max-w-5xl px-4 py-10 md:py-16">
    <div class="grid overflow-hidden rounded-3xl bg-white shadow-xl shadow-brand-950/10 ring-1 ring-brand-100 md:grid-cols-2">
        <div class="relative hidden overflow-hidden bg-brand-800 p-10 text-white md:block">
            <div class="star-pattern absolute inset-0"></div>
            <div class="relative flex h-full flex-col justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.14em] text-leaf-300">{{ setting('site_name') }}</p>
                    <h2 class="mt-4 font-display text-3xl leading-tight">{{ $panelTitle }}</h2>
                    <p class="mt-4 leading-relaxed text-brand-200">{{ $panelText }}</p>
                </div>
                <ul class="mt-10 space-y-3 text-sm text-brand-100">
                    @foreach(['Riwayat donasi tersimpan rapi', 'Isi data donasi lebih cepat', 'Pantau status verifikasi donasi Anda'] as $point)
                        <li class="flex items-center gap-3"><span class="grid h-6 w-6 place-items-center rounded-full bg-leaf-400 text-brand-950"><svg viewBox="0 0 24 24" class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="m5 12 5 5 9-10"/></svg></span>{{ $point }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
        <div class="p-6 sm:p-10">
            <h1 class="font-display text-3xl text-brand-900">{{ $heading }}</h1>
            <p class="mt-2 text-sm text-brand-600">{{ $sub }}</p>
            <div class="mt-8">@yield('form')</div>
        </div>
    </div>
</section>
@endsection
