@extends('layouts.app')
@section('title', 'Program donasi')
@section('content')
@include('partials.page-header', ['title' => 'Program donasi', 'subtitle' => 'Setiap program memiliki target dan laporan yang jelas. Pilih yang ingin Anda dukung.'])

<section class="mx-auto max-w-6xl px-4 pt-10">
    <form method="GET" class="flex flex-col gap-3 sm:flex-row">
        <input type="search" name="q" value="{{ request('q') }}" placeholder="Cari program" aria-label="Cari program"
               class="w-full rounded-full border border-brand-200 bg-white px-5 py-3 text-sm focus:border-brand-500 focus:outline-none sm:max-w-sm">
        <select name="kategori" onchange="this.form.submit()" aria-label="Kategori" class="rounded-full border border-brand-200 bg-white px-5 py-3 text-sm">
            <option value="">Semua kategori</option>
            @foreach(\App\Models\Program::CATEGORIES as $k => $v)
                <option value="{{ $k }}" @selected(request('kategori') === $k)>{{ $v }}</option>
            @endforeach
        </select>
        <button class="rounded-full bg-brand-800 px-6 py-3 text-sm font-bold text-white hover:bg-brand-700">Cari</button>
    </form>

    @if($programs->isEmpty())
        <div class="mt-10 rounded-2xl border border-dashed border-brand-200 p-12 text-center">
            <p class="font-semibold text-brand-800">Tidak ada program yang cocok.</p>
            <a href="{{ route('programs.index') }}" class="mt-2 inline-block text-sm text-brand-600 underline">Tampilkan semua program</a>
        </div>
    @else
        <div class="mt-8 grid gap-6 md:grid-cols-2 lg:grid-cols-3">
            @foreach($programs as $program) @include('partials.program-card', ['program' => $program]) @endforeach
        </div>
        <div class="mt-10">{{ $programs->links() }}</div>
    @endif
</section>
@endsection
