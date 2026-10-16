@extends('layouts.app')
@section('title', 'Dokumen')
@section('content')
@include('partials.page-header', ['title' => 'Dokumen legalitas', 'subtitle' => 'Salinan izin dan pengesahan resmi ' . setting('site_name') . ' yang dapat diperiksa oleh donatur dan mitra.'])

@include('partials.legalitas')

<section class="mx-auto mt-16 max-w-6xl px-4">
    <div class="rounded-3xl bg-brand-50 p-8 text-center md:p-10">
        <h2 class="font-display text-2xl text-brand-900">Perlu dokumen lain?</h2>
        <p class="mx-auto mt-2 max-w-2xl text-brand-700">Untuk keperluan kemitraan atau verifikasi tambahan, hubungi sekretariat melalui halaman kontak.</p>
        <a href="{{ route('contact') }}" class="mt-5 inline-block rounded-full bg-brand-800 px-6 py-3 text-sm font-bold text-white hover:bg-brand-700">Hubungi sekretariat</a>
    </div>
</section>
@endsection
