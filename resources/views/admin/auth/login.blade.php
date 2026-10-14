<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><meta name="robots" content="noindex">
    <title>Masuk admin | {{ setting('site_name') }}</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700&family=Young+Serif&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>tailwind.config={theme:{extend:{fontFamily:{sans:['"Plus Jakarta Sans"','sans-serif'],display:['"Young Serif"','serif']},colors:{brand: { 50:'#EEF4FC',100:'#D9E7F8',200:'#B3CFF1',300:'#82AEE5',400:'#4F86D3',500:'#2C66BB',600:'#1F5099',700:'#1A4179',800:'#153463',900:'#0F2547',950:'#091730' },leaf: { 100:'#DCF5E4',300:'#7FD79A',400:'#3FBF69',500:'#25A455',600:'#1B8744' }}}}}</script>
</head>
<body class="grid min-h-screen place-items-center bg-brand-900 p-4 font-sans">
    <form method="POST" action="{{ route('admin.login') }}" class="w-full max-w-sm rounded-3xl bg-white p-8 shadow-2xl">
        @csrf
        <h1 class="font-display text-2xl text-brand-900">Masuk admin</h1>
        <p class="mt-1 text-sm text-brand-700">{{ setting('site_name') }}</p>
        @error('email')<p class="mt-4 rounded-lg bg-red-50 p-3 text-sm text-red-800" role="alert">{{ $message }}</p>@enderror
        <label class="mt-5 block text-sm font-semibold">Email
            <input type="email" name="email" value="{{ old('email') }}" required autofocus class="mt-1.5 w-full rounded-xl border border-brand-200 px-4 py-3 font-normal">
        </label>
        <label class="mt-4 block text-sm font-semibold">Kata sandi
            <input type="password" name="password" required class="mt-1.5 w-full rounded-xl border border-brand-200 px-4 py-3 font-normal">
        </label>
        <label class="mt-4 flex items-center gap-2 text-sm"><input type="checkbox" name="remember" value="1" class="h-4 w-4"> Ingat saya</label>
        <button class="mt-6 w-full rounded-full bg-leaf-400 py-3 font-bold text-brand-900 hover:bg-leaf-500">Masuk</button>
        <a href="{{ route('home') }}" class="mt-4 block text-center text-sm text-brand-700 underline">Kembali ke website</a>
    </form>
</body>
</html>
