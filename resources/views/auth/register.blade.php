@extends('auth.shell', ['heading' => 'Daftar akun', 'sub' => 'Buat akun donatur agar riwayat donasi Anda tersimpan.', 'panelTitle' => 'Mulai berbagi kebaikan', 'panelText' => 'Akun gratis dan hanya butuh satu menit. Donasi tanpa akun tetap bisa dilakukan kapan saja.'])
@section('form')
<form method="POST" action="{{ route('register.store') }}" class="space-y-5" x-data="{ show: false }">
    @csrf
    <label class="block text-sm font-semibold text-brand-900">Nama lengkap
        <input type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name"
               class="mt-1.5 w-full rounded-xl border border-brand-200 px-4 py-3 font-normal focus:border-brand-500 focus:outline-none">
    </label>
    @error('name')<p class="-mt-3 text-sm text-red-600" role="alert">{{ $message }}</p>@enderror

    <label class="block text-sm font-semibold text-brand-900">Email
        <input type="email" name="email" value="{{ old('email') }}" required autocomplete="email"
               class="mt-1.5 w-full rounded-xl border border-brand-200 px-4 py-3 font-normal focus:border-brand-500 focus:outline-none">
    </label>
    @error('email')<p class="-mt-3 text-sm text-red-600" role="alert">{{ $message }}</p>@enderror

    <label class="block text-sm font-semibold text-brand-900">Kata sandi
        <span class="relative mt-1.5 block">
            <input :type="show ? 'text' : 'password'" name="password" required minlength="8" autocomplete="new-password"
                   class="w-full rounded-xl border border-brand-200 py-3 pl-4 pr-20 font-normal focus:border-brand-500 focus:outline-none">
            <button type="button" @click="show = !show" class="absolute right-3 top-1/2 -translate-y-1/2 text-sm font-semibold text-brand-600" x-text="show ? 'Sembunyikan' : 'Lihat'">Lihat</button>
        </span>
        <span class="mt-1 block text-xs font-normal text-brand-500">Minimal 8 karakter.</span>
    </label>
    @error('password')<p class="-mt-3 text-sm text-red-600" role="alert">{{ $message }}</p>@enderror

    <label class="block text-sm font-semibold text-brand-900">Ulangi kata sandi
        <input :type="show ? 'text' : 'password'" name="password_confirmation" required autocomplete="new-password"
               class="mt-1.5 w-full rounded-xl border border-brand-200 px-4 py-3 font-normal focus:border-brand-500 focus:outline-none">
    </label>

    <button class="flex w-full items-center justify-center gap-2 rounded-full bg-leaf-400 px-6 py-3.5 font-bold text-brand-950 transition hover:bg-leaf-300"><x-icon name="user-plus" class="h-5 w-5" />Buat akun</button>

    <p class="text-center text-sm text-brand-700">Sudah punya akun? <a href="{{ route('login') }}" class="font-bold text-brand-800 underline underline-offset-4">Masuk</a></p>
</form>
@endsection
