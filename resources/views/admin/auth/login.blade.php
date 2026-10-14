<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><meta name="robots" content="noindex">
    <title>Masuk admin | {{ setting('site_name') }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700&family=Young+Serif&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>tailwind.config={theme:{extend:{fontFamily:{sans:['"Plus Jakarta Sans"','sans-serif'],display:['"Young Serif"','serif']},colors:{pine:{50:'#EFF7F3',100:'#DAEDE4',200:'#B6DAC9',700:'#1A5442',800:'#154236',900:'#0F2F27'},marigold:{400:'#F4C443',500:'#EDB01C'}}}}}</script>
</head>
<body class="grid min-h-screen place-items-center bg-pine-900 p-4 font-sans">
    <form method="POST" action="{{ route('admin.login') }}" class="w-full max-w-sm rounded-3xl bg-white p-8 shadow-2xl">
        @csrf
        <h1 class="font-display text-2xl text-pine-900">Masuk admin</h1>
        <p class="mt-1 text-sm text-pine-700">{{ setting('site_name') }}</p>
        @error('email')<p class="mt-4 rounded-lg bg-red-50 p-3 text-sm text-red-800" role="alert">{{ $message }}</p>@enderror
        <label class="mt-5 block text-sm font-semibold">Email
            <input type="email" name="email" value="{{ old('email') }}" required autofocus class="mt-1.5 w-full rounded-xl border border-pine-200 px-4 py-3 font-normal">
        </label>
        <label class="mt-4 block text-sm font-semibold">Kata sandi
            <input type="password" name="password" required class="mt-1.5 w-full rounded-xl border border-pine-200 px-4 py-3 font-normal">
        </label>
        <label class="mt-4 flex items-center gap-2 text-sm"><input type="checkbox" name="remember" value="1" class="h-4 w-4"> Ingat saya</label>
        <button class="mt-6 w-full rounded-full bg-marigold-400 py-3 font-bold text-pine-900 hover:bg-marigold-500">Masuk</button>
        <a href="{{ route('home') }}" class="mt-4 block text-center text-sm text-pine-700 underline">Kembali ke website</a>
    </form>
</body>
</html>
