@extends('auth.shell', ['heading' => 'Masuk', 'sub' => 'Masuk untuk melihat riwayat donasi Anda.', 'panelTitle' => 'Selamat datang kembali', 'panelText' => 'Terima kasih sudah menjadi bagian dari kebaikan untuk anak-anak binaan kami.'])
@section('form')
<form method="POST" action="{{ route('login.attempt') }}" class="space-y-5" x-data="{ show: false }">
    @csrf
    <label class="block text-sm font-semibold text-brand-900">Email
        <input type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="email"
               class="mt-1.5 w-full rounded-xl border border-brand-200 px-4 py-3 font-normal focus:border-brand-500 focus:outline-none">
    </label>
    @error('email')<p class="-mt-3 text-sm text-red-600" role="alert">{{ $message }}</p>@enderror

    <label class="block text-sm font-semibold text-brand-900">Kata sandi
        <span class="relative mt-1.5 block">
            <input :type="show ? 'text' : 'password'" name="password" required autocomplete="current-password"
                   class="w-full rounded-xl border border-brand-200 py-3 pl-4 pr-20 font-normal focus:border-brand-500 focus:outline-none">
            <button type="button" @click="show = !show" class="absolute right-3 top-1/2 -translate-y-1/2 text-sm font-semibold text-brand-600" x-text="show ? 'Sembunyikan' : 'Lihat'">Lihat</button>
        </span>
    </label>
    @error('password')<p class="-mt-3 text-sm text-red-600" role="alert">{{ $message }}</p>@enderror

    <label class="flex items-center gap-2 text-sm text-brand-700">
        <input type="checkbox" name="remember" value="1" class="h-4 w-4 rounded border-brand-300 text-brand-700"> Ingat saya
    </label>

    <button class="flex w-full items-center justify-center gap-2 rounded-full bg-brand-700 px-6 py-3.5 font-bold text-white transition hover:bg-brand-600"><x-icon name="user" class="h-5 w-5" />Masuk</button>

    <p class="text-center text-sm text-brand-700">Belum punya akun? <a href="{{ route('register') }}" class="font-bold text-brand-800 underline underline-offset-4">Daftar sekarang</a></p>
</form>
@endsection
