@extends('layouts.app')
@section('title', $post->title)
@section('description', $post->excerpt ?: \Illuminate\Support\Str::limit(strip_tags($post->body), 150))
@if($post->image)@section('og_image', media_url($post->image))@endif

@section('content')
<article class="mx-auto max-w-3xl px-4 pt-10">
    <a href="{{ route('posts.index', ['kategori' => $post->category]) }}" class="text-sm font-semibold text-brand-600 hover:underline">{{ $post->category_label }}</a>
    <h1 class="mt-2 font-display text-3xl leading-tight text-brand-900 md:text-5xl">{{ $post->title }}</h1>
    <p class="mt-3 text-sm text-brand-600">{{ optional($post->published_at)->translatedFormat('d F Y') }}, {{ $post->read_minutes }} menit baca</p>

    @if($post->image)
        <x-cover :src="$post->image" :alt="$post->title" class="mt-8 aspect-[16/9] rounded-2xl" />
    @endif

    <div class="prose prose-lg prose-brand mt-8 max-w-none prose-headings:font-display prose-headings:text-brand-900 prose-p:text-brand-800 prose-li:text-brand-800">
        {!! \Illuminate\Support\Str::markdown($post->body, ['html_input' => 'strip', 'allow_unsafe_links' => false]) !!}
    </div>

    <div class="mt-10 flex flex-wrap items-center gap-3 border-t border-brand-100 pt-6">
        <span class="text-sm font-semibold text-brand-700">Bagikan:</span>
        <a target="_blank" rel="noopener" class="rounded-full border border-brand-200 px-4 py-1.5 text-sm hover:bg-brand-50" href="https://wa.me/?text={{ urlencode($post->title . ' ' . url()->current()) }}">WhatsApp</a>
        <a target="_blank" rel="noopener" class="rounded-full border border-brand-200 px-4 py-1.5 text-sm hover:bg-brand-50" href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}">Facebook</a>
    </div>

    <div class="mt-10 rounded-2xl bg-brand-50 p-6 text-center">
        <p class="font-display text-xl text-brand-900">Ikut menjadi bagian dari kebaikan ini</p>
        <a href="{{ route('donation.create') }}" class="mt-4 inline-block rounded-full bg-brand-800 px-6 py-3 text-sm font-bold text-white hover:bg-brand-700">Donasi sekarang</a>
    </div>
</article>

@if($related->isNotEmpty())
<section class="mx-auto mt-16 max-w-6xl px-4">
    <h2 class="font-display text-2xl text-brand-900">Tulisan terkait</h2>
    <div class="mt-6 grid gap-6 md:grid-cols-3">
        @foreach($related as $post) @include('partials.post-card', ['post' => $post]) @endforeach
    </div>
</section>
@endif
@endsection
