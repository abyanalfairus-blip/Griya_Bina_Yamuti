<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', setting('site_name')) | {{ setting('site_name') }}</title>
    <meta name="description" content="@yield('description', setting('tagline'))">
    <meta property="og:title" content="@yield('title', setting('site_name'))">
    <meta property="og:description" content="@yield('description', setting('tagline'))">
    @hasSection('og_image')<meta property="og:image" content="@yield('og_image')">@endif
    <meta name="theme-color" content="#283A93">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=Young+Serif&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com?plugins=typography"></script>
    <script>
        /*
         * Palet warna mengikuti logo Griya Bina Yamu'ti:
         * - "pine"     -> BIRU logo (warna dominan: header, teks, footer)
         * - "marigold" -> HIJAU logo (aksen: tombol donasi, highlight, hover)
         * Nama token sengaja dipertahankan agar semua view lain yang
         * memakai kelas pine-* / marigold-* ikut berubah otomatis.
         */
        tailwind.config = {
            theme: { extend: {
                fontFamily: { sans: ['"Plus Jakarta Sans"', 'system-ui', 'sans-serif'], display: ['"Young Serif"', 'Georgia', 'serif'] },
                colors: {
                    pine: { 50:'#EEF1FB',100:'#DCE2F6',200:'#B9C4ED',300:'#8C9DE0',400:'#5E74CF',500:'#3D55BE',600:'#2E43A8',700:'#283A93',800:'#22307A',900:'#1A2560',950:'#111842' },
                    marigold: { 100:'#DDF3E3',300:'#7FD09A',400:'#34B05A',500:'#22964A',600:'#1A7A3C' },
                    paper: '#F5F7FC'
                }
            } }
        }
    </script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.9/dist/cdn.min.js"></script>
    <style>
        [x-cloak]{display:none!important}
        .star-pattern{background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='72' height='72' viewBox='0 0 72 72'%3E%3Cg fill='none' stroke='%2334B05A' stroke-opacity='.16' stroke-width='1'%3E%3Cpath d='M36 6l7 13 14-5-5 14 13 7-13 7 5 14-14-5-7 13-7-13-14 5 5-14-13-7 13-7-5-14 14 5z'/%3E%3C/g%3E%3C/svg%3E");}
        @media (prefers-reduced-motion: reduce){*{animation:none!important;transition:none!important;scroll-behavior:auto!important}}
        :focus-visible{outline:3px solid #34B05A;outline-offset:2px}
    </style>
    @stack('head')
</head>
<body class="bg-paper font-sans text-pine-950 antialiased">
@php
    $nav = [
        ['Program', route('programs.index'), 'programs.*'],
        ['Zakat', route('zakat'), 'zakat'],
        ['Kabar', route('posts.index'), 'posts.*'],
        ['Laporan', route('reports'), 'reports'],
        ['Tentang', route('about'), 'about'],
        ['Kontak', route('contact'), 'contact'],
    ];
@endphp
 
<a href="#konten" class="sr-only focus:not-sr-only focus:absolute focus:z-50 focus:bg-white focus:p-3">Lewati ke konten</a>
 
<header x-data="{ open:false }" class="sticky top-0 z-40 border-b border-pine-100 bg-paper/95 backdrop-blur">
    <div class="mx-auto flex h-16 max-w-6xl items-center justify-between px-4">
        <a href="{{ route('home') }}" class="flex items-center gap-3">
            <span class="grid h-10 w-10 place-items-center rounded-xl bg-pine-700 text-marigold-400" aria-hidden="true">
                <svg viewBox="0 0 24 24" class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 11.5 12 4l9 7.5"/><path d="M5.5 10v9.5h13V10"/><path d="M12 16.5s-2.5-1.6-2.5-3.2a1.4 1.4 0 0 1 2.5-.8 1.4 1.4 0 0 1 2.5.8c0 1.6-2.5 3.2-2.5 3.2z" fill="currentColor"/></svg>
            </span>
            <span class="font-display text-lg leading-none text-pine-800">{{ setting('site_name') }}</span>
        </a>
 
        <nav class="hidden items-center gap-1 lg:flex" aria-label="Utama">
            @foreach($nav as [$label, $url, $pattern])
                <a href="{{ $url }}" class="rounded-full px-3.5 py-2 text-sm font-medium transition hover:bg-pine-100 {{ request()->routeIs($pattern) ? 'bg-pine-100 text-pine-800' : 'text-pine-700' }}">{{ $label }}</a>
            @endforeach
            <a href="{{ route('donation.create') }}" class="ml-3 rounded-full bg-marigold-500 px-5 py-2.5 text-sm font-bold text-white transition hover:bg-marigold-600">Donasi sekarang</a>
        </nav>
 
        <button @click="open=!open" class="grid h-10 w-10 place-items-center rounded-lg text-pine-800 lg:hidden" :aria-expanded="open" aria-label="Buka menu">
            <svg x-show="!open" class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" d="M4 7h16M4 12h16M4 17h16"/></svg>
            <svg x-show="open" x-cloak class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" d="M6 6l12 12M18 6 6 18"/></svg>
        </button>
    </div>
 
    <div x-show="open" x-cloak x-transition class="border-t border-pine-100 bg-paper lg:hidden">
        <nav class="mx-auto flex max-w-6xl flex-col gap-1 px-4 py-3" aria-label="Seluler">
            @foreach($nav as [$label, $url, $pattern])
                <a href="{{ $url }}" class="rounded-lg px-3 py-2.5 font-medium {{ request()->routeIs($pattern) ? 'bg-pine-100 text-pine-800' : 'text-pine-700' }}">{{ $label }}</a>
            @endforeach
            <a href="{{ route('donation.create') }}" class="mt-2 rounded-full bg-marigold-500 px-5 py-3 text-center font-bold text-white transition hover:bg-marigold-600">Donasi sekarang</a>
        </nav>
    </div>
</header>
 
@if(session('success'))
    <div class="bg-marigold-600 text-white" role="status">
        <div class="mx-auto max-w-6xl px-4 py-3 text-sm font-medium">{{ session('success') }}</div>
    </div>
@endif
 
<main id="konten">
    @yield('content')
</main>
 
<footer class="mt-24 bg-pine-950 text-pine-200">
    <div class="mx-auto grid max-w-6xl gap-10 px-4 py-14 md:grid-cols-12">
        <div class="md:col-span-5">
            <p class="font-display text-2xl text-white">{{ setting('site_name') }}</p>
            <p class="mt-3 max-w-sm text-sm leading-relaxed text-pine-300">{{ setting('tagline') }}</p>
            <div class="mt-5 flex gap-3">
                @foreach(['instagram' => 'Instagram', 'facebook' => 'Facebook', 'youtube' => 'YouTube'] as $key => $label)
                    @if(setting($key))
                        <a href="{{ setting($key) }}" target="_blank" rel="noopener" class="rounded-full border border-pine-700 px-3.5 py-1.5 text-sm text-pine-100 transition hover:border-marigold-400 hover:text-marigold-400">{{ $label }}</a>
                    @endif
                @endforeach
            </div>
        </div>
        <div class="md:col-span-3">
            <p class="font-semibold text-white">Jelajahi</p>
            <ul class="mt-3 space-y-2 text-sm">
                @foreach($nav as [$label, $url])
                    <li><a href="{{ $url }}" class="hover:text-marigold-400">{{ $label }}</a></li>
                @endforeach
                <li><a href="{{ route('gallery') }}" class="hover:text-marigold-400">Galeri</a></li>
            </ul>
        </div>
        <div class="md:col-span-4">
            <p class="font-semibold text-white">Sekretariat</p>
            <address class="mt-3 text-sm not-italic leading-relaxed text-pine-300">
                {{ setting('address') }}
                <br>Telepon / WhatsApp: {{ setting('phone') }}
                @if(setting('email'))<br>Email: {{ setting('email') }}@endif
            </address>
        </div>
    </div>
    <div class="border-t border-pine-800">
        <div class="mx-auto flex max-w-6xl flex-col gap-2 px-4 py-5 text-xs text-pine-400 sm:flex-row sm:justify-between">
            <p>&copy; {{ date('Y') }} {{ setting('site_name') }}. Seluruh donasi disalurkan untuk anak yatim dan dhuafa.</p>
            <a href="{{ route('admin.login') }}" class="hover:text-pine-200">Masuk admin</a>
        </div>
    </div>
</footer>
 
@if(setting('whatsapp'))
    <a href="https://wa.me/{{ setting('whatsapp') }}?text={{ urlencode('Assalamualaikum, saya ingin bertanya tentang donasi di ' . setting('site_name')) }}" target="_blank" rel="noopener"
       class="fixed bottom-5 right-5 z-40 grid h-14 w-14 place-items-center rounded-full bg-[#25D366] text-white shadow-lg transition hover:scale-105" aria-label="Chat WhatsApp">
        <svg viewBox="0 0 24 24" class="h-7 w-7" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M21 11.5a8.4 8.4 0 0 1-12.4 7.3L3 20.5l1.7-5.4A8.5 8.5 0 1 1 21 11.5z"/><path d="M8.8 9.2c.3 2.200 2.700 4.600 5 5l1.300-1.300-1.800-1-.9.700c-.8-.4-1.600-1.200-2-2l.7-.9-1-1.800z" fill="currentColor" stroke="none"/></svg>
    </a>
@endif
@stack('scripts')
</body>
</html>