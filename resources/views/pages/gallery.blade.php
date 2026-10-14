@extends('layouts.app')
@section('title', 'Galeri')
@section('content')
@include('partials.page-header', ['title' => 'Galeri kegiatan', 'subtitle' => 'Dokumentasi pembinaan, santunan, dan kebersamaan bersama anak-anak binaan.'])

<section class="mx-auto max-w-6xl px-4 pt-10" x-data="{ img: null, cap: '' }" @keydown.escape.window="img=null">
    @if($galleries->isEmpty())
        <p class="rounded-2xl border border-dashed border-brand-200 p-12 text-center text-brand-600">Belum ada foto. Unggah foto kegiatan lewat panel admin.</p>
    @else
        <div class="grid grid-cols-2 gap-4 md:grid-cols-3 lg:grid-cols-4">
            @foreach($galleries as $g)
                <button type="button" @click="img='{{ media_url($g->image) }}'; cap=@js($g->title . ($g->caption ? ': ' . $g->caption : ''))" class="group overflow-hidden rounded-xl">
                    <x-cover :src="$g->image" :alt="$g->title" class="aspect-square transition duration-500 group-hover:scale-105" />
                </button>
            @endforeach
        </div>
        <div class="mt-10">{{ $galleries->links() }}</div>

        <div x-show="img" x-cloak x-transition.opacity @click="img=null" class="fixed inset-0 z-50 grid place-items-center bg-black/80 p-4" role="dialog" aria-modal="true">
            <figure class="max-h-full max-w-4xl" @click.stop>
                <img :src="img" :alt="cap" class="max-h-[80vh] rounded-lg">
                <figcaption class="mt-3 text-center text-sm text-white" x-text="cap"></figcaption>
            </figure>
            <button type="button" @click="img=null" class="absolute right-4 top-4 rounded-full bg-white/20 px-4 py-2 text-sm font-semibold text-white">Tutup</button>
        </div>
    @endif
</section>
@endsection
