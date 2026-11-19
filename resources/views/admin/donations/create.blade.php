@extends('admin.layout')
@section('title', 'Catat donasi WA / manual')
@section('heading', 'Catat donasi WA / manual')
@section('content')
@php
    $cls = 'mt-1.5 w-full rounded-xl border border-brand-200 px-4 py-3 text-sm font-normal focus:border-brand-500 focus:outline-none';
    $sources = ['whatsapp' => ['Laporan WhatsApp', 'Donatur transfer lalu kirim bukti lewat WA'], 'transfer' => ['Transfer langsung', 'Dana masuk ke rekening tanpa laporan'], 'tunai' => ['Tunai di yayasan', 'Donatur menyerahkan uang secara langsung']];
@endphp

<div class="grid gap-6 lg:grid-cols-3">
<form method="POST" action="{{ route('admin.donations.store') }}" enctype="multipart/form-data"
      x-data="{ source: @js(old('source', 'whatsapp')) }"
      class="space-y-6 rounded-2xl border border-brand-100 bg-white p-6 lg:col-span-2">
    @csrf

    @if($errors->any())
        <div class="rounded-xl bg-red-50 p-4 text-sm text-red-800" role="alert">
            <p class="font-semibold">Data belum bisa disimpan:</p>
            <ul class="mt-1 list-inside list-disc">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    @endif

    {{-- 1. Sumber --}}
    <fieldset>
        <legend class="text-sm font-semibold">Dari mana donasi ini?</legend>
        <div class="mt-2 grid gap-3 sm:grid-cols-3">
            @foreach($sources as $k => [$label, $hint])
                <label class="cursor-pointer rounded-xl border p-3 transition" :class="source === '{{ $k }}' ? 'border-leaf-500 bg-leaf-100/60 ring-2 ring-leaf-400/40' : 'border-brand-200 hover:bg-brand-50'">
                    <input type="radio" name="source" value="{{ $k }}" x-model="source" class="sr-only">
                    <span class="block text-sm font-bold text-brand-900">{{ $label }}</span>
                    <span class="mt-0.5 block text-xs text-brand-600">{{ $hint }}</span>
                </label>
            @endforeach
        </div>
    </fieldset>

    {{-- 2. Donatur --}}
    <div class="grid gap-4 sm:grid-cols-2">
        <div>
            <label for="f-name" class="block text-sm font-semibold">Nama donatur</label>
            <input id="f-name" name="donor_name" value="{{ old('donor_name') }}" required class="{{ $cls }}" placeholder="Sesuai nama di WA / bukti transfer">
        </div>
        <div>
            <label for="f-phone" class="block text-sm font-semibold">Nomor WhatsApp <span class="font-normal text-brand-500">(opsional)</span></label>
            <input id="f-phone" name="donor_phone" value="{{ old('donor_phone') }}" class="{{ $cls }}" placeholder="08xxxxxxxxxx">
        </div>
    </div>
    <label class="flex items-center gap-3 text-sm font-semibold">
        <input type="checkbox" name="is_anonymous" value="1" @checked(old('is_anonymous')) class="h-5 w-5 rounded border-brand-300">
        Donatur ingin anonim (tampil sebagai "Hamba Allah" di website)
    </label>

    {{-- 3. Donasi --}}
    <div class="grid gap-4 sm:grid-cols-2">
        <div>
            <label for="f-type" class="block text-sm font-semibold">Jenis</label>
            <select id="f-type" name="type" class="{{ $cls }}">
                @foreach(\App\Models\Donation::TYPES as $k => $v)<option value="{{ $k }}" @selected(old('type', 'donasi') === $k)>{{ $v }}</option>@endforeach
            </select>
        </div>
        <div>
            <label for="f-program" class="block text-sm font-semibold">Untuk program</label>
            <select id="f-program" name="program_id" class="{{ $cls }}">
                <option value="">Umum (tidak untuk program tertentu)</option>
                @foreach($programs as $p)<option value="{{ $p->id }}" @selected((string) old('program_id') === (string) $p->id)>{{ $p->title }}</option>@endforeach
            </select>
        </div>
        <div>
            <label for="f-amount" class="block text-sm font-semibold">Nominal yang masuk (Rp)</label>
            <input id="f-amount" type="number" min="1" name="amount" value="{{ old('amount') }}" required class="{{ $cls }}" placeholder="Contoh: 250000">
            <p class="mt-1 text-xs text-brand-600">Tulis angka saja, tanpa titik.</p>
        </div>
        <div>
            <label for="f-date" class="block text-sm font-semibold">Tanggal dana diterima</label>
            <input id="f-date" type="date" name="paid_on" value="{{ old('paid_on', now()->format('Y-m-d')) }}" max="{{ now()->format('Y-m-d') }}" required class="{{ $cls }}">
        </div>
    </div>

    {{-- 4. Rekening dan bukti (tidak untuk tunai) --}}
    <div x-show="source !== 'tunai'" x-transition class="grid gap-4 sm:grid-cols-2">
        <div>
            <label for="f-method" class="block text-sm font-semibold">Masuk ke rekening</label>
            <select id="f-method" name="payment_method_id" class="{{ $cls }}">
                <option value="">(Tidak diketahui)</option>
                @foreach($methods as $m)<option value="{{ $m->id }}" @selected((string) old('payment_method_id') === (string) $m->id)>{{ $m->name }}{{ $m->account_number ? ' · ' . $m->account_number : '' }}{{ $m->is_active ? '' : ' (nonaktif)' }}</option>@endforeach
            </select>
        </div>
        <div>
            <label for="f-proof" class="block text-sm font-semibold">Bukti dari WA <span class="font-normal text-brand-500">(opsional)</span></label>
            <input id="f-proof" type="file" name="proof" accept="image/*" class="mt-2 block w-full text-sm file:mr-4 file:rounded-full file:border-0 file:bg-brand-100 file:px-4 file:py-2 file:font-semibold file:text-brand-800">
            <p class="mt-1 text-xs text-brand-600">Simpan foto bukti dari WhatsApp ke laptop, lalu pilih di sini.</p>
        </div>
    </div>

    <div class="grid gap-4 sm:grid-cols-2">
        <div>
            <label for="f-msg" class="block text-sm font-semibold">Doa / pesan donatur <span class="font-normal text-brand-500">(tampil di website)</span></label>
            <textarea id="f-msg" name="message" rows="3" class="{{ $cls }}">{{ old('message') }}</textarea>
        </div>
        <div>
            <label for="f-note" class="block text-sm font-semibold">Catatan internal <span class="font-normal text-brand-500">(hanya admin)</span></label>
            <textarea id="f-note" name="admin_note" rows="3" class="{{ $cls }}" placeholder="Contoh: dilaporkan ke WA admin, 19 Nov pukul 10.15">{{ old('admin_note') }}</textarea>
        </div>
    </div>

    {{-- 5. Konfirmasi --}}
    <label class="flex items-start gap-3 rounded-xl bg-brand-50 p-4 text-sm">
        <input type="checkbox" name="confirmed" value="1" @checked(old('confirmed', true)) class="mt-0.5 h-5 w-5 rounded border-brand-300">
        <span><span class="font-semibold text-brand-900">Dana sudah saya cek masuk ke rekening / sudah diterima</span>
            <span class="mt-0.5 block text-brand-600">Dicentang: status langsung <b>Diterima</b> dan progres program bertambah. Tidak dicentang: status <b>Menunggu verifikasi</b>.</span></span>
    </label>

    <div class="flex gap-3">
        <button class="rounded-full bg-brand-800 px-7 py-3 text-sm font-bold text-white hover:bg-brand-700">Simpan donasi</button>
        <a href="{{ route('admin.donations.index') }}" class="rounded-full border border-brand-200 px-7 py-3 text-sm font-semibold text-brand-700 hover:bg-brand-50">Batal</a>
    </div>
</form>

<aside class="h-fit space-y-3 rounded-2xl border border-brand-100 bg-white p-6 text-sm text-brand-700">
    <h2 class="font-display text-lg text-brand-900">Alur laporan lewat WhatsApp</h2>
    <ol class="list-inside list-decimal space-y-2">
        <li>Donatur mengirim bukti transfer ke WA yayasan.</li>
        <li>Cek mutasi rekening: pastikan nominal dan tanggalnya cocok.</li>
        <li>Isi formulir ini dan unggah foto buktinya.</li>
        <li>Centang "Dana sudah masuk", lalu simpan.</li>
        <li>Balas donatur dengan ucapan terima kasih dari halaman rincian donasi.</li>
    </ol>
    <p class="rounded-lg bg-leaf-100/60 p-3 text-xs text-brand-800">Kalau donatur sebelumnya sudah mengisi formulir di website, jangan catat dua kali: cari nomor donasinya di <a class="font-semibold underline" href="{{ route('admin.donations.index') }}">Donasi masuk</a>, lalu ubah statusnya menjadi Diterima.</p>
</aside>
</div>
@endsection
