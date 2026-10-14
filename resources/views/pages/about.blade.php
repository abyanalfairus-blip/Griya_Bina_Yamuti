@extends('layouts.app')
@section('title', 'Tentang kami')
@section('content')
@include('partials.page-header', ['title' => 'Tentang ' . setting('site_name'), 'subtitle' => setting('tagline')])

<section class="mx-auto grid max-w-6xl gap-12 px-4 pt-14 lg:grid-cols-12">
    <div class="lg:col-span-7">
        <h2 class="font-display text-3xl text-brand-900">Profil dan sejarah</h2>
        <p class="mt-4 text-lg leading-relaxed text-brand-800">{{ setting('about_short') }}</p>
        <p class="mt-4 leading-relaxed text-brand-800">{{ setting('about_history') }}</p>
    </div>
    <div class="space-y-6 lg:col-span-5">
        <div class="rounded-2xl bg-brand-800 p-6 text-white">
            <h2 class="font-display text-xl text-leaf-400">Visi</h2>
            <p class="mt-2 leading-relaxed">{{ setting('vision') }}</p>
        </div>
        <div class="rounded-2xl border border-brand-100 bg-white p-6">
            <h2 class="font-display text-xl text-brand-900">Misi</h2>
            <ul class="mt-3 space-y-2.5 text-sm leading-relaxed text-brand-800">
                @foreach(preg_split('/\r\n|\r|\n/', trim(setting('mission'))) as $line)
                    @if(trim($line) !== '')
                        <li class="flex gap-3"><span class="mt-2 h-1.5 w-1.5 shrink-0 rounded-full bg-leaf-500"></span><span>{{ $line }}</span></li>
                    @endif
                @endforeach
            </ul>
        </div>
    </div>
</section>

<section class="mx-auto mt-20 max-w-6xl px-4">
    <h2 class="font-display text-3xl text-brand-900">Susunan pengurus</h2>
    @if($team->isEmpty())
        <p class="mt-6 rounded-2xl border border-dashed border-brand-200 p-10 text-center text-brand-600">Data pengurus belum ditambahkan. Isi lewat panel admin pada menu Susunan Pengurus.</p>
    @else
        <div class="mt-8 grid grid-cols-2 gap-6 md:grid-cols-4">
            @foreach($team as $t)
                <div>
                    <x-cover :src="$t->photo" :alt="$t->name" class="aspect-square rounded-2xl" />
                    <p class="mt-3 font-semibold text-brand-900">{{ $t->name }}</p>
                    <p class="text-sm text-brand-600">{{ $t->position }}</p>
                </div>
            @endforeach
        </div>
    @endif
</section>

<section class="mx-auto mt-20 max-w-6xl px-4">
    <div class="rounded-3xl bg-brand-50 p-8 md:p-10">
        <h2 class="font-display text-2xl text-brand-900">Legalitas dan perizinan</h2>
        <p class="mt-2 max-w-2xl text-brand-700">Dokumen legalitas yayasan tersedia untuk donatur dan mitra. Hubungi sekretariat melalui halaman kontak untuk meminta salinannya.</p>
        <a href="{{ route('contact') }}" class="mt-5 inline-block rounded-full bg-brand-800 px-6 py-3 text-sm font-bold text-white hover:bg-brand-700">Hubungi sekretariat</a>
    </div>
</section>
@endsection
