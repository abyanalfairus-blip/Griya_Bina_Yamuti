@extends('layouts.app')
@section('title', 'Kabar & literasi')
@section('content')
@include('partials.page-header', ['title' => 'Kabar dan literasi', 'subtitle' => 'Laporan kegiatan, cerita dari anak binaan, serta bacaan yang menenangkan hati.'])

<section class="mx-auto max-w-6xl px-4 pt-10">
    <div class="flex flex-wrap gap-2">
        <a href="{{ route('posts.index') }}" class="rounded-full px-4 py-2 text-sm font-semibold {{ !request('kategori') ? 'bg-pine-800 text-white' : 'bg-white text-pine-700 border border-pine-200 hover:bg-pine-50' }}">Semua</a>
        @foreach(\App\Models\Post::CATEGORIES as $k => $v)
            <a href="{{ route('posts.index', ['kategori' => $k]) }}" class="rounded-full px-4 py-2 text-sm font-semibold {{ request('kategori') === $k ? 'bg-pine-800 text-white' : 'bg-white text-pine-700 border border-pine-200 hover:bg-pine-50' }}">{{ $v }}</a>
        @endforeach
    </div>
    <form method="GET" class="mt-4">
        @if(request('kategori'))<input type="hidden" name="kategori" value="{{ request('kategori') }}">@endif
        <input type="search" name="q" value="{{ request('q') }}" placeholder="Cari tulisan" aria-label="Cari tulisan" class="w-full max-w-sm rounded-full border border-pine-200 bg-white px-5 py-3 text-sm">
    </form>

    @if($posts->isEmpty())
        <p class="mt-10 rounded-2xl border border-dashed border-pine-200 p-12 text-center text-pine-600">Belum ada tulisan pada kategori ini.</p>
    @else
        <div class="mt-8 grid gap-6 md:grid-cols-2 lg:grid-cols-3">
            @foreach($posts as $post) @include('partials.post-card', ['post' => $post]) @endforeach
        </div>
        <div class="mt-10">{{ $posts->links() }}</div>
    @endif
</section>
@endsection
