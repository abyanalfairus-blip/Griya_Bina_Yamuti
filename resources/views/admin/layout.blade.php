<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title>@yield('title', 'Panel Admin') | {{ setting('site_name') }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=Young+Serif&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = { theme: { extend: {
            fontFamily: { sans: ['"Plus Jakarta Sans"', 'system-ui', 'sans-serif'], display: ['"Young Serif"', 'Georgia', 'serif'] },
            colors: { pine: { 50:'#EFF7F3',100:'#DAEDE4',200:'#B6DAC9',300:'#86BFA7',400:'#54A084',500:'#328266',600:'#226750',700:'#1A5442',800:'#154236',900:'#0F2F27',950:'#0A211B' }, marigold: { 100:'#FCEFC4',400:'#F4C443',500:'#EDB01C',600:'#CC8F10' } }
        } } }
    </script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.9/dist/cdn.min.js"></script>
    <style>[x-cloak]{display:none!important} :focus-visible{outline:3px solid #F4C443;outline-offset:2px}</style>
</head>
<body class="bg-pine-50 font-sans text-pine-950 antialiased" x-data="{ menu:false }">
@php
    $menu = [
        ['Dasbor', 'admin.dashboard', 'admin.dashboard'],
        ['Donasi masuk', 'admin.donations.index', 'admin.donations.*'],
        ['Program', 'admin.programs.index', 'admin.programs.*'],
        ['Kabar & literasi', 'admin.posts.index', 'admin.posts.*'],
        ['Laporan penyaluran', 'admin.distributions.index', 'admin.distributions.*'],
        ['Metode pembayaran', 'admin.payment-methods.index', 'admin.payment-methods.*'],
        ['Galeri', 'admin.galleries.index', 'admin.galleries.*'],
        ['Susunan pengurus', 'admin.team.index', 'admin.team.*'],
        ['Pesan masuk', 'admin.messages.index', 'admin.messages.*'],
        ['Pengaturan situs', 'admin.settings.edit', 'admin.settings.*'],
    ];
@endphp
<div class="flex min-h-screen">
    <aside :class="menu ? 'translate-x-0' : '-translate-x-full'" class="fixed inset-y-0 left-0 z-30 w-64 transform bg-pine-950 p-4 text-pine-200 transition lg:static lg:translate-x-0">
        <a href="{{ route('admin.dashboard') }}" class="block px-3 py-4 font-display text-xl text-white">{{ setting('site_name') }}<span class="block font-sans text-xs font-medium text-pine-400">Panel admin</span></a>
        <nav class="mt-4 space-y-1" aria-label="Menu admin">
            @foreach($menu as [$label, $route, $pattern])
                <a href="{{ route($route) }}" class="block rounded-lg px-3 py-2.5 text-sm font-medium {{ request()->routeIs($pattern) ? 'bg-pine-800 text-marigold-400' : 'hover:bg-pine-900' }}">{{ $label }}</a>
            @endforeach
        </nav>
        <div class="mt-8 space-y-1 border-t border-pine-800 pt-4 text-sm">
            <a href="{{ route('home') }}" target="_blank" class="block rounded-lg px-3 py-2 hover:bg-pine-900">Lihat website</a>
            <form method="POST" action="{{ route('admin.logout') }}">@csrf<button class="w-full rounded-lg px-3 py-2 text-left hover:bg-pine-900">Keluar</button></form>
        </div>
    </aside>

    <div class="min-w-0 flex-1">
        <header class="flex items-center justify-between border-b border-pine-100 bg-white px-4 py-3 lg:px-8">
            <button @click="menu=!menu" class="rounded-lg border border-pine-200 px-3 py-1.5 text-sm font-semibold lg:hidden">Menu</button>
            <h1 class="font-display text-xl text-pine-900">@yield('heading')</h1>
            <p class="hidden text-sm text-pine-600 sm:block">{{ auth()->user()->name }}</p>
        </header>

        <main class="p-4 lg:p-8">
            @if(session('success'))<div class="mb-5 rounded-xl bg-pine-700 px-4 py-3 text-sm font-medium text-white" role="status">{{ session('success') }}</div>@endif
            @yield('content')
        </main>
    </div>
</div>
</body>
</html>
