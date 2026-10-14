@extends('layouts.app')
@section('title', 'Kontak')
@section('content')
@include('partials.page-header', ['title' => 'Hubungi kami', 'subtitle' => 'Pertanyaan tentang donasi, penyaluran, atau kerja sama? Kami siap menjawab.'])

<section class="mx-auto grid max-w-6xl gap-10 px-4 pt-12 lg:grid-cols-12">
    <div class="lg:col-span-5">
        <h2 class="font-display text-2xl text-pine-900">Sekretariat</h2>
        <address class="mt-4 text-pine-800 not-italic leading-relaxed">{{ setting('address') }}</address>
        <ul class="mt-5 space-y-2 text-sm">
            <li>Telepon / WhatsApp: <a class="font-semibold text-pine-800 underline" href="https://wa.me/{{ setting('whatsapp') }}" target="_blank" rel="noopener">{{ setting('phone') }}</a></li>
            @if(setting('email'))<li>Email: <a class="font-semibold text-pine-800 underline" href="mailto:{{ setting('email') }}">{{ setting('email') }}</a></li>@endif
        </ul>
        @if(setting('maps_embed'))
            <iframe src="{{ setting('maps_embed') }}" class="mt-6 h-64 w-full rounded-2xl border-0" loading="lazy" referrerpolicy="no-referrer-when-downgrade" title="Lokasi sekretariat"></iframe>
        @else
            <a class="mt-6 inline-block rounded-full border border-pine-300 px-5 py-2.5 text-sm font-semibold text-pine-800 hover:bg-pine-50" target="_blank" rel="noopener" href="https://www.google.com/maps/search/?api=1&query={{ urlencode(setting('address')) }}">Buka lokasi di Google Maps</a>
        @endif
    </div>

    <form method="POST" action="{{ route('contact.store') }}" class="space-y-4 rounded-2xl border border-pine-100 bg-white p-6 lg:col-span-7">
        @csrf
        <h2 class="font-display text-2xl text-pine-900">Kirim pesan</h2>
        @if($errors->any())
            <div class="rounded-xl bg-red-50 p-3 text-sm text-red-800" role="alert">@foreach($errors->all() as $e)<p>{{ $e }}</p>@endforeach</div>
        @endif
        <div class="grid gap-4 sm:grid-cols-2">
            <label class="block text-sm font-semibold">Nama<input name="name" value="{{ old('name') }}" required class="mt-1.5 w-full rounded-xl border border-pine-200 px-4 py-3 font-normal"></label>
            <label class="block text-sm font-semibold">Email<input type="email" name="email" value="{{ old('email') }}" class="mt-1.5 w-full rounded-xl border border-pine-200 px-4 py-3 font-normal"></label>
            <label class="block text-sm font-semibold">Nomor WhatsApp<input name="phone" value="{{ old('phone') }}" class="mt-1.5 w-full rounded-xl border border-pine-200 px-4 py-3 font-normal"></label>
            <label class="block text-sm font-semibold">Subjek<input name="subject" value="{{ old('subject') }}" class="mt-1.5 w-full rounded-xl border border-pine-200 px-4 py-3 font-normal"></label>
        </div>
        <label class="block text-sm font-semibold">Pesan<textarea name="body" rows="5" required class="mt-1.5 w-full rounded-xl border border-pine-200 px-4 py-3 font-normal">{{ old('body') }}</textarea></label>
        <button class="rounded-full bg-pine-800 px-7 py-3 text-sm font-bold text-white hover:bg-pine-700">Kirim pesan</button>
    </form>
</section>
@endsection
