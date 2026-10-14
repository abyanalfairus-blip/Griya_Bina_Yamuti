@extends('layouts.app')
@section('title', 'Form donasi')
@section('content')
@include('partials.page-header', ['title' => 'Form donasi', 'subtitle' => 'Isi data singkat di bawah ini. Instruksi pembayaran muncul setelah Anda mengirim form.'])

<section class="mx-auto max-w-3xl px-4 pt-10">
    @if($errors->any())
        <div class="mb-6 rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-800" role="alert">
            <p class="font-semibold">Periksa kembali isian Anda:</p>
            <ul class="mt-1 list-inside list-disc">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    @endif

    <form method="POST" action="{{ route('donation.store') }}" x-data="{ amount: {{ (int) old('amount', $amount ?: 100000) }} }" class="space-y-8">
        @csrf

        <fieldset class="rounded-2xl border border-brand-100 bg-white p-6">
            <legend class="px-2 font-display text-xl text-brand-900">Tujuan donasi</legend>
            <div class="grid gap-4 sm:grid-cols-2">
                <label class="block text-sm font-semibold">Jenis
                    <select name="type" class="mt-1.5 w-full rounded-xl border border-brand-200 px-4 py-3 font-normal">
                        @foreach(\App\Models\Donation::TYPES as $k => $v)
                            <option value="{{ $k }}" @selected(old('type', $type) === $k)>{{ $v }}</option>
                        @endforeach
                    </select>
                </label>
                <label class="block text-sm font-semibold">Program
                    <select name="program_id" class="mt-1.5 w-full rounded-xl border border-brand-200 px-4 py-3 font-normal">
                        <option value="">Umum (dikelola yayasan sesuai kebutuhan)</option>
                        @foreach($programs as $p)
                            <option value="{{ $p->id }}" @selected((int) old('program_id', $selectedProgram) === $p->id)>{{ $p->title }}</option>
                        @endforeach
                    </select>
                </label>
            </div>
        </fieldset>

        <fieldset class="rounded-2xl border border-brand-100 bg-white p-6">
            <legend class="px-2 font-display text-xl text-brand-900">Nominal</legend>
            <div class="grid grid-cols-3 gap-2 sm:grid-cols-6">
                @foreach([25000, 50000, 100000, 250000, 500000, 1000000] as $n)
                    <button type="button" @click="amount={{ $n }}"
                            :class="amount==={{ $n }} ? 'border-brand-700 bg-brand-50 text-brand-800' : 'border-brand-100 text-brand-700 hover:border-brand-300'"
                            class="rounded-xl border-2 py-2.5 text-sm font-bold transition">{{ number_format($n/1000, 0, ',', '.') }}rb</button>
                @endforeach
            </div>
            <label class="mt-4 block text-sm font-semibold">Atau tulis nominal lain (Rp)
                <input type="number" name="amount" x-model.number="amount" min="10000" step="1000" required
                       class="mt-1.5 w-full rounded-xl border border-brand-200 px-4 py-3 text-lg font-bold">
            </label>
            <p class="mt-2 text-xs text-brand-600">Minimal Rp 10.000. Kode unik akan ditambahkan pada langkah berikutnya.</p>
        </fieldset>

        <fieldset class="rounded-2xl border border-brand-100 bg-white p-6">
            <legend class="px-2 font-display text-xl text-brand-900">Data donatur</legend>
            <div class="grid gap-4 sm:grid-cols-2">
                <label class="block text-sm font-semibold sm:col-span-2">Nama lengkap
                    <input type="text" name="donor_name" value="{{ old('donor_name', auth()->user()?->name) }}" required class="mt-1.5 w-full rounded-xl border border-brand-200 px-4 py-3 font-normal">
                </label>
                <label class="block text-sm font-semibold">Nomor WhatsApp
                    <input type="tel" name="donor_phone" value="{{ old('donor_phone') }}" placeholder="08xxxxxxxxxx" class="mt-1.5 w-full rounded-xl border border-brand-200 px-4 py-3 font-normal">
                </label>
                <label class="block text-sm font-semibold">Email
                    <input type="email" name="donor_email" value="{{ old('donor_email', auth()->user()?->email) }}" class="mt-1.5 w-full rounded-xl border border-brand-200 px-4 py-3 font-normal">
                </label>
                <label class="block text-sm font-semibold sm:col-span-2">Doa atau pesan (opsional)
                    <textarea name="message" rows="3" maxlength="500" class="mt-1.5 w-full rounded-xl border border-brand-200 px-4 py-3 font-normal">{{ old('message') }}</textarea>
                </label>
                <label class="flex items-center gap-3 text-sm sm:col-span-2">
                    <input type="checkbox" name="is_anonymous" value="1" @checked(old('is_anonymous')) class="h-5 w-5 rounded border-brand-300 text-brand-700">
                    Sembunyikan nama saya (tampil sebagai &ldquo;Hamba Allah&rdquo;)
                </label>
            </div>
        </fieldset>

        <fieldset class="rounded-2xl border border-brand-100 bg-white p-6">
            <legend class="px-2 font-display text-xl text-brand-900">Metode pembayaran</legend>
            @forelse($methods as $m)
                <label class="mt-2 flex cursor-pointer items-center gap-4 rounded-xl border-2 border-brand-100 p-4 has-[:checked]:border-brand-600 has-[:checked]:bg-brand-50">
                    <input type="radio" name="payment_method_id" value="{{ $m->id }}" @checked((int) old('payment_method_id', $loop->first ? $m->id : 0) === $m->id) class="h-5 w-5 text-brand-700">
                    <span>
                        <span class="block font-semibold text-brand-900">{{ $m->name }}</span>
                        <span class="block text-xs text-brand-600">{{ \App\Models\PaymentMethod::TYPES[$m->type] ?? $m->type }}</span>
                    </span>
                </label>
            @empty
                <p class="text-sm text-red-700">Belum ada metode pembayaran aktif. Hubungi admin yayasan.</p>
            @endforelse
        </fieldset>

        <button class="w-full rounded-full bg-leaf-400 py-4 text-lg font-bold text-brand-950 hover:bg-leaf-300">Lanjut ke instruksi pembayaran</button>
    </form>
</section>
@endsection
