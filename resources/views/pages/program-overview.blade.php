@extends('layouts.app')
@section('title', 'Program')
@section('og_image', asset('images/program/santunan-yatim.jpg'))
@section('content')
@include('partials.page-header', ['title' => 'Program Griya Bina Yamuti', 'subtitle' => \App\Support\Pillars::headline()])

<section class="mx-auto max-w-3xl px-4 pt-12">
    @foreach(\App\Support\Pillars::intro() as $para)
        <p class="mb-4 text-lg leading-relaxed text-brand-800">{{ $para }}</p>
    @endforeach
</section>

{{-- Navigasi cepat --}}
<nav class="sticky top-16 z-20 mt-8 border-y border-brand-100 bg-paper/95 backdrop-blur" aria-label="Loncat ke program">
    <ul class="mx-auto flex max-w-6xl gap-2 overflow-x-auto px-4 py-3 text-sm">
        @foreach(\App\Support\Pillars::groups() as $g)
            @foreach($g['programs'] as $pr)
                <li class="shrink-0"><a href="#{{ $pr['slug'] }}" class="block rounded-full border border-brand-200 px-3 py-1.5 font-semibold text-brand-800 hover:bg-brand-100">{{ $pr['title'] }}</a></li>
            @endforeach
        @endforeach
    </ul>
</nav>

@foreach(\App\Support\Pillars::groups() as $g)
<section class="mx-auto mt-16 max-w-6xl px-4">
    <h2 class="font-display text-3xl text-brand-900 md:text-4xl">{{ $g['letter'] ? $g['letter'] . '. ' : '' }}{{ $g['title'] }}</h2>
    @if($g['note'])<p class="mt-3 max-w-2xl text-brand-700">{{ $g['note'] }}</p>@endif

    <div class="mt-10 space-y-14">
        @foreach($g['programs'] as $pr)
            <article id="{{ $pr['slug'] }}" class="grid scroll-mt-36 items-start gap-8 lg:grid-cols-12 lg:gap-12">
                <div class="lg:col-span-5 {{ $loop->odd ? '' : 'lg:order-2' }}">
                    @if(!empty($pr['img']))
                        <img src="{{ media_url($pr['img']) }}" alt="{{ $pr['alt'] ?? $pr['title'] }}" loading="lazy" class="aspect-[4/3] w-full rounded-3xl object-cover" style="object-position: {{ $pr['pos'] ?? 'center' }}">
                    @else
                        <div class="relative grid aspect-[4/3] place-items-center overflow-hidden rounded-3xl bg-brand-800">
                            <div class="star-pattern absolute inset-0"></div>
                            <x-icon :name="$pr['icon']" class="relative h-24 w-24 text-leaf-400" />
                        </div>
                    @endif
                </div>
                <div class="lg:col-span-7 {{ $loop->odd ? '' : 'lg:order-1' }}">
                    <p class="text-sm font-semibold uppercase tracking-wide text-brand-500">{{ $pr['no'] }}. {{ $pr['focus'] }}</p>
                    <h3 class="mt-1 font-display text-2xl text-brand-900 md:text-3xl">{{ $pr['title'] }}</h3>
                    @if($pr['tagline'])<p class="mt-2 text-lg font-semibold text-brand-700">{{ $pr['tagline'] }}</p>@endif
                    @if($pr['desc'])<p class="mt-3 leading-relaxed text-brand-800">{{ $pr['desc'] }}</p>@endif

                    @if(count($pr['list']))
                        <h4 class="mt-5 font-semibold text-brand-900">{{ $pr['list_title'] }}</h4>
                        <ul class="mt-2 space-y-2">
                            @foreach($pr['list'] as $item)
                                <li class="flex gap-3 text-brand-800"><span class="mt-2 h-1.5 w-1.5 shrink-0 rounded-full bg-leaf-500"></span><span class="leading-relaxed">{{ $item }}</span></li>
                            @endforeach
                        </ul>
                    @endif

                    <a href="{{ route('donation.create', ['type' => $pr['type']]) }}" class="mt-6 inline-block rounded-full bg-brand-800 px-6 py-3 text-sm font-bold text-white transition hover:bg-brand-700">Dukung {{ $pr['title'] }}</a>
                </div>
            </article>
        @endforeach
    </div>
</section>
@endforeach

<section class="mx-auto mt-20 max-w-6xl px-4">
    <div class="rounded-3xl bg-brand-800 p-8 text-center text-white md:p-12">
        <h2 class="font-display text-2xl md:text-3xl">Galang dana yang sedang berjalan</h2>
        <p class="mx-auto mt-3 max-w-xl text-brand-200">Lihat target dan perkembangan donasi untuk program yang sedang dibuka.</p>
        <a href="{{ route('programs.index') }}" class="mt-6 inline-block rounded-full bg-leaf-400 px-7 py-3 font-bold text-brand-950 hover:bg-leaf-300">Lihat program donasi</a>
    </div>
</section>

<section class="mx-auto mt-8 max-w-6xl px-4">
    <div class="rounded-3xl bg-brand-50 p-8 text-center md:p-12">
        <h2 class="font-display text-2xl text-brand-900 md:text-3xl">Setiap amanah dilaporkan secara terbuka</h2>
        <p class="mx-auto mt-3 max-w-xl text-brand-700">Pantau penghimpunan dan penyaluran dana lewat laporan bulanan dan tahunan.</p>
        <a href="{{ route('reports') }}" class="mt-6 inline-block rounded-full bg-leaf-400 px-7 py-3 font-bold text-brand-950 hover:bg-leaf-300">Buka laporan</a>
    </div>
</section>
@endsection
