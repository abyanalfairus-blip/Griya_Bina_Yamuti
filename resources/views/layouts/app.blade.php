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
    <meta name="theme-color" content="#153463">
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=Young+Serif&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com?plugins=typography"></script>
    <script>
        tailwind.config = {
            theme: { extend: {
                fontFamily: { sans: ['"Plus Jakarta Sans"', 'system-ui', 'sans-serif'], display: ['"Young Serif"', 'Georgia', 'serif'] },
                colors: {
                    brand: { 50:'#EEF4FC',100:'#D9E7F8',200:'#B3CFF1',300:'#82AEE5',400:'#4F86D3',500:'#2C66BB',600:'#1F5099',700:'#1A4179',800:'#153463',900:'#0F2547',950:'#091730' },
                    leaf: { 100:'#DCF5E4',300:'#7FD79A',400:'#3FBF69',500:'#25A455',600:'#1B8744' },
                    paper: '#F4F7FB'
                }
            } }
        }
    </script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.9/dist/cdn.min.js"></script>
    <style>
        [x-cloak]{display:none!important}
        .star-pattern{background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='72' height='72' viewBox='0 0 72 72'%3E%3Cg fill='none' stroke='%233FBF69' stroke-opacity='.16' stroke-width='1'%3E%3Cpath d='M36 6l7 13 14-5-5 14 13 7-13 7 5 14-14-5-7 13-7-13-14 5 5-14-13-7 13-7-5-14 14 5z'/%3E%3C/g%3E%3C/svg%3E");}
        @media (prefers-reduced-motion: reduce){*{animation:none!important;transition:none!important;scroll-behavior:auto!important}}
        :focus-visible{outline:3px solid #3FBF69;outline-offset:2px}
    </style>
    @stack('head')
</head>
<body class="bg-paper font-sans text-brand-950 antialiased">
@php
    // [label, url, pola route aktif, ikon]
    $nav = [
        ['Beranda', route('home'), 'home', 'home'],
        ['Program', route('programs.overview'), 'programs.*', 'grid'],
        ['Zakat', route('zakat'), 'zakat', 'calculator'],
        ['Kabar', route('posts.index'), 'posts.*', 'news'],
        ['Laporan', route('reports'), 'reports*', 'report'],
        ['Tentang', route('about'), 'about', 'info'],
        ['Kontak', route('contact'), 'contact', 'mail'],
    ];
    $hasLogo = file_exists(public_path('images/logo.png'));
@endphp

<a href="#konten" class="sr-only focus:not-sr-only focus:absolute focus:z-50 focus:bg-white focus:p-3">Lewati ke konten</a>

<header x-data="{ open:false, acct:false }" class="sticky top-0 z-40 border-b border-brand-100 bg-white/95 backdrop-blur">
    <div class="mx-auto flex h-16 max-w-7xl items-center justify-between gap-4 px-4">
        <a href="{{ route('home') }}" class="flex shrink-0 items-center gap-3">
            @if($hasLogo)
                <img src="{{ asset('images/logo.png') }}" alt="Logo {{ setting('site_name') }}" class="h-12 w-auto">
            @else
                <span class="grid h-10 w-10 place-items-center rounded-xl bg-gradient-to-br from-brand-700 to-brand-500 font-display text-lg text-white" aria-hidden="true">GB</span>
            @endif
            <span class="font-display text-lg leading-none text-brand-900">{{ setting('site_name') }}</span>
        </a>

        <nav class="hidden items-center gap-0.5 xl:flex" aria-label="Utama">
            @foreach($nav as [$label, $url, $pattern, $icon])
                <a href="{{ $url }}" @if(request()->routeIs($pattern)) aria-current="page" @endif
                   class="flex items-center gap-1.5 rounded-full px-3 py-2 text-sm font-medium transition hover:bg-brand-50 {{ request()->routeIs($pattern) ? 'bg-brand-100 text-brand-800' : 'text-brand-700' }}">
                    <x-icon :name="$icon" class="h-[18px] w-[18px]" />{{ $label }}
                </a>
            @endforeach
        </nav>

        <div class="hidden items-center gap-2 xl:flex">
            <a href="{{ route('donation.create') }}" class="flex items-center gap-1.5 rounded-full bg-leaf-400 px-4 py-2.5 text-sm font-bold text-brand-950 transition hover:bg-leaf-300"><x-icon name="heart" class="h-[18px] w-[18px]" />Donasi</a>
            @auth
                <div class="relative" @click.outside="acct = false">
                    <button type="button" @click="acct = !acct" :aria-expanded="acct" class="flex items-center gap-2 rounded-full border border-brand-200 py-1.5 pl-2 pr-3.5 text-sm font-semibold text-brand-800 hover:bg-brand-50">
                        <span class="grid h-7 w-7 place-items-center rounded-full bg-brand-700 text-xs font-bold text-white">{{ \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr(auth()->user()->name, 0, 1)) }}</span>
                        <span class="max-w-[7rem] truncate">{{ \Illuminate\Support\Str::before(auth()->user()->name, ' ') }}</span>
                    </button>
                    <div x-show="acct" x-cloak x-transition class="absolute right-0 mt-2 w-52 overflow-hidden rounded-2xl border border-brand-100 bg-white py-1 text-sm shadow-xl">
                        <a href="{{ route('account') }}" class="flex items-center gap-2.5 px-4 py-2.5 hover:bg-brand-50"><x-icon name="user" class="h-4 w-4" />Akun saya</a>
                        @if(auth()->user()->is_admin)
                            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2.5 px-4 py-2.5 hover:bg-brand-50"><x-icon name="dashboard" class="h-4 w-4" />Panel admin</a>
                        @endif
                        <form method="POST" action="{{ route('logout') }}">@csrf
                            <button class="flex w-full items-center gap-2.5 px-4 py-2.5 text-left hover:bg-brand-50"><x-icon name="log-out" class="h-4 w-4" />Keluar</button>
                        </form>
                    </div>
                </div>
            @else
                <a href="{{ route('login') }}" class="flex items-center gap-1.5 rounded-full border border-brand-300 px-4 py-2.5 text-sm font-semibold text-brand-800 transition hover:bg-brand-50"><x-icon name="user" class="h-[18px] w-[18px]" />Masuk</a>
                <a href="{{ route('register') }}" class="flex items-center gap-1.5 rounded-full bg-brand-700 px-4 py-2.5 text-sm font-bold text-white transition hover:bg-brand-600"><x-icon name="user-plus" class="h-[18px] w-[18px]" />Daftar</a>
            @endauth
        </div>

        <button @click="open=!open" class="grid h-10 w-10 place-items-center rounded-lg text-brand-800 xl:hidden" :aria-expanded="open" aria-label="Buka menu">
            <x-icon name="menu" class="h-6 w-6" x-show="!open" />
            <x-icon name="close" class="h-6 w-6" x-show="open" x-cloak />
        </button>
    </div>

    <div x-show="open" x-cloak x-transition class="border-t border-brand-100 bg-white xl:hidden">
        <nav class="mx-auto flex max-w-7xl flex-col gap-1 px-4 py-3" aria-label="Seluler">
            @foreach($nav as [$label, $url, $pattern, $icon])
                <a href="{{ $url }}" class="flex items-center gap-3 rounded-lg px-3 py-2.5 font-medium {{ request()->routeIs($pattern) ? 'bg-brand-100 text-brand-800' : 'text-brand-700' }}"><x-icon :name="$icon" />{{ $label }}</a>
            @endforeach
            <a href="{{ route('donation.create') }}" class="mt-2 flex items-center justify-center gap-2 rounded-full bg-leaf-400 px-5 py-3 text-center font-bold text-brand-950"><x-icon name="heart" />Donasi sekarang</a>
            @auth
                <a href="{{ route('account') }}" class="flex items-center gap-3 rounded-lg px-3 py-2.5 font-medium text-brand-700"><x-icon name="user" />Akun saya ({{ \Illuminate\Support\Str::before(auth()->user()->name, ' ') }})</a>
                @if(auth()->user()->is_admin)<a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 rounded-lg px-3 py-2.5 font-medium text-brand-700"><x-icon name="dashboard" />Panel admin</a>@endif
                <form method="POST" action="{{ route('logout') }}">@csrf<button class="flex w-full items-center gap-3 rounded-lg px-3 py-2.5 text-left font-medium text-brand-700"><x-icon name="log-out" />Keluar</button></form>
            @else
                <div class="mt-1 grid grid-cols-2 gap-2">
                    <a href="{{ route('login') }}" class="flex items-center justify-center gap-2 rounded-full border border-brand-300 px-4 py-2.5 font-semibold text-brand-800"><x-icon name="user" class="h-[18px] w-[18px]" />Masuk</a>
                    <a href="{{ route('register') }}" class="flex items-center justify-center gap-2 rounded-full bg-brand-700 px-4 py-2.5 font-bold text-white"><x-icon name="user-plus" class="h-[18px] w-[18px]" />Daftar</a>
                </div>
            @endauth
        </nav>
    </div>
</header>

@if(session('success'))
    <div class="bg-brand-700 text-white" role="status">
        <div class="mx-auto max-w-6xl px-4 py-3 text-sm font-medium">{{ session('success') }}</div>
    </div>
@endif

<main id="konten">
    @yield('content')
</main>

<footer class="mt-24 bg-brand-950 text-brand-200">
    <div class="mx-auto grid max-w-6xl gap-10 px-4 py-14 md:grid-cols-12">
        <div class="md:col-span-5">
            @if($hasLogo)
                <span class="mb-4 inline-block rounded-2xl bg-white p-3 shadow-lg shadow-black/20"><img src="{{ asset('images/logo.png') }}" alt="Logo {{ setting('site_name') }}" class="h-24 w-auto" loading="lazy"></span>
            @endif
            <p class="font-display text-2xl text-white">{{ setting('site_name') }}</p>
            <p class="mt-3 max-w-sm text-sm leading-relaxed text-brand-300">{{ setting('tagline') }}</p>
            <div class="mt-5 flex flex-wrap gap-3">
                @foreach([
                    ['instagram', 'Instagram', setting('instagram'), 'hover:bg-[#E1306C]'],
                    ['facebook', 'Facebook', setting('facebook'), 'hover:bg-[#1877F2]'],
                    ['youtube', 'YouTube', setting('youtube'), 'hover:bg-[#FF0000]'],
                    ['whatsapp', 'WhatsApp', setting('whatsapp') ? 'https://wa.me/' . setting('whatsapp') : null, 'hover:bg-[#25D366]'],
                ] as [$icon, $label, $url, $hover])
                    @if($url)
                        <a href="{{ $url }}" target="_blank" rel="noopener" aria-label="{{ $label }}" title="{{ $label }}"
                           class="grid h-11 w-11 place-items-center rounded-full border border-brand-700 text-brand-100 transition hover:border-transparent hover:text-white {{ $hover }}"><x-icon :name="$icon" class="h-5 w-5" /></a>
                    @endif
                @endforeach
            </div>
        </div>
        <div class="md:col-span-3">
            <p class="font-semibold text-white">Jelajahi</p>
            <ul class="mt-3 space-y-2 text-sm">
                @foreach($nav as [$label, $url])
                    <li><a href="{{ $url }}" class="hover:text-leaf-400">{{ $label }}</a></li>
                @endforeach
                <li><a href="{{ route('gallery') }}" class="hover:text-leaf-400">Galeri</a></li>
            </ul>
        </div>
        <div class="md:col-span-4">
            <p class="font-semibold text-white">Sekretariat</p>
            <address class="mt-3 text-sm not-italic leading-relaxed text-brand-300">
                {{ setting('address') }}
                <br>Telepon / WhatsApp: {{ setting('phone') }}
                @if(setting('email'))<br>Email: {{ setting('email') }}@endif
            </address>
            @php $mapSrc = setting('maps_embed') ?: 'https://www.google.com/maps?q=' . urlencode(setting('address')) . '&output=embed'; @endphp
            <iframe src="{{ $mapSrc }}" class="mt-4 h-44 w-full rounded-2xl border-0 bg-brand-900" loading="lazy" referrerpolicy="no-referrer-when-downgrade" title="Peta lokasi sekretariat"></iframe>
            <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode(setting('address')) }}" target="_blank" rel="noopener" class="mt-3 inline-flex items-center gap-1.5 text-sm font-semibold text-leaf-400 hover:text-leaf-300"><x-icon name="map-pin" class="h-4 w-4" />Buka di Google Maps</a>
        </div>
    </div>
    <div class="border-t border-brand-800">
        <div class="mx-auto max-w-6xl px-4 py-5 text-left text-xs text-brand-400">
            <p>&copy; {{ date('Y') }} {{ setting('site_name') }}. Seluruh donasi disalurkan untuk anak yatim dan dhuafa.</p>
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
