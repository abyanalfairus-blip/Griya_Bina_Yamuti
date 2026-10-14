@extends('layouts.app')
@section('title', $program->title)
@section('description', $program->excerpt)
@if($program->image)@section('og_image', \Illuminate\Support\Facades\Storage::url($program->image))@endif

@section('content')
<div class="mx-auto max-w-6xl px-4 pt-8">
    <nav class="text-sm text-pine-600" aria-label="Jejak halaman">
        <a href="{{ route('programs.index') }}" class="hover:underline">Program</a> / <span>{{ $program->category_label }}</span>
    </nav>

    <div class="mt-5 grid gap-10 lg:grid-cols-12">
        <article class="lg:col-span-7">
            <x-cover :src="$program->image" :alt="$program->title" class="aspect-[16/9] rounded-2xl" />
            <h1 class="mt-6 font-display text-3xl leading-tight text-pine-900 md:text-4xl">{{ $program->title }}</h1>
            <div class="prose prose-pine mt-6 max-w-none prose-headings:font-display prose-headings:text-pine-900 prose-p:text-pine-800 prose-li:text-pine-800 prose-a:text-pine-700">
                {!! \Illuminate\Support\Str::markdown($program->description ?? '', ['html_input' => 'strip', 'allow_unsafe_links' => false]) !!}
            </div>

            @if($distributions->isNotEmpty())
                <h2 class="mt-12 font-display text-2xl text-pine-900">Penyaluran dana program ini</h2>
                <ul class="mt-4 divide-y divide-pine-100 rounded-2xl border border-pine-100 bg-white">
                    @foreach($distributions as $dist)
                        <li class="flex items-start justify-between gap-4 p-4">
                            <div>
                                <p class="font-semibold text-pine-900">{{ $dist->title }}</p>
                                <p class="text-xs text-pine-600">{{ $dist->distributed_at->translatedFormat('d F Y') }}</p>
                            </div>
                            <p class="shrink-0 font-bold text-pine-800">{{ rupiah($dist->amount) }}</p>
                        </li>
                    @endforeach
                </ul>
            @endif
        </article>

        <aside class="lg:col-span-5">
            <div class="sticky top-24 space-y-6">
                <form method="GET" action="{{ route('donation.create') }}" x-data="{ amount: 100000 }" class="rounded-2xl border border-pine-100 bg-white p-6">
                    <input type="hidden" name="program" value="{{ $program->id }}">
                    <input type="hidden" name="amount" :value="amount">
                    <p class="font-display text-3xl text-pine-900">{{ rupiah($program->collected) }}</p>
                    <p class="text-sm text-pine-600">terkumpul dari target {{ rupiah($program->target) }}</p>
                    <div class="mt-4"><x-progress :value="$program->progress" /></div>
                    <div class="mt-3 flex justify-between text-sm text-pine-700">
                        <span>{{ $program->progress }}% tercapai</span>
                        @if(!is_null($program->days_left))<span>{{ $program->days_left }} hari lagi</span>@endif
                    </div>

                    <p class="mt-6 text-sm font-semibold">Nominal donasi</p>
                    <div class="mt-2 grid grid-cols-3 gap-2">
                        @foreach([25000, 50000, 100000, 250000, 500000, 1000000] as $n)
                            <button type="button" @click="amount={{ $n }}"
                                    :class="amount==={{ $n }} ? 'border-pine-700 bg-pine-50 text-pine-800' : 'border-pine-100 text-pine-700 hover:border-pine-300'"
                                    class="rounded-xl border-2 py-2.5 text-sm font-bold transition">{{ number_format($n/1000, 0, ',', '.') }}rb</button>
                        @endforeach
                    </div>
                    <button class="mt-5 w-full rounded-full bg-marigold-400 py-3.5 font-bold text-pine-950 hover:bg-marigold-300">
                        Donasi <span x-text="'Rp ' + Number(amount).toLocaleString('id-ID')"></span>
                    </button>
                </form>

                <div class="rounded-2xl border border-pine-100 bg-white p-6">
                    <h2 class="font-display text-xl text-pine-900">Donatur ({{ $donations->count() }} terbaru)</h2>
                    <ul class="mt-4 space-y-4">
                        @forelse($donations as $d)
                            <li>
                                <div class="flex justify-between gap-3">
                                    <p class="font-semibold">{{ $d->display_name }}</p>
                                    <p class="font-bold text-pine-800">{{ rupiah($d->amount) }}</p>
                                </div>
                                @if($d->message)<p class="mt-1 text-sm italic text-pine-700">&ldquo;{{ $d->message }}&rdquo;</p>@endif
                                <p class="text-xs text-pine-500">{{ optional($d->paid_at)->diffForHumans() }}</p>
                            </li>
                        @empty
                            <li class="text-sm text-pine-600">Belum ada donatur. Jadilah yang pertama.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </aside>
    </div>

    @if($others->isNotEmpty())
        <section class="mt-20">
            <h2 class="font-display text-2xl text-pine-900">Program lainnya</h2>
            <div class="mt-6 grid gap-6 md:grid-cols-3">
                @foreach($others as $program) @include('partials.program-card', ['program' => $program]) @endforeach
            </div>
        </section>
    @endif
</div>
@endsection
