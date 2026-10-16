@extends('layouts.app')
@section('title', setting('site_name') . ': ' . setting('tagline'))
@section('og_image', asset('images/program/santunan-yatim.jpg'))



@section('content')
@php
    $flagshipView = [
        [
            'slug' => 'penyaluran-sepatu',
            'img' => 'images/program/sepatu.jpg',
            'alt' => 'Anak binaan berseragam sekolah memegang kotak sepatu dan amplop bantuan',
            'frame' => 'aspect-[3/4] max-w-sm',
            'pos' => 'center 18%',
            'title' => 'Penyaluran sepatu',
            'text' => 'Sepatu yang layak membuat anak berangkat sekolah dengan percaya diri. Donasi Anda dipakai membeli sepatu baru dan menyalurkannya langsung kepada anak yatim dan dhuafa binaan.',
        ],
        [
            'slug' => 'rumah-belajar',
            'img' => 'images/program/rumah-belajar.jpg',
            'alt' => 'Pembina berbicara di depan anak-anak yang duduk belajar pada kegiatan Jumat Berkah',
            'frame' => 'aspect-[16/9]',
            'pos' => 'center',
            'title' => 'Rumah belajar',
            'text' => 'Ruang bagi anak-anak binaan untuk belajar, mengaji, dan bertumbuh bersama lewat pengajian rutin, pembinaan akhlak, tahfidz, dan kegiatan Jumat Berkah.',
        ],
        [
            'slug' => 'santunan-anak-yatim',
            'img' => 'images/program/santunan-yatim.jpg',
            'alt' => 'Anak-anak yatim binaan memegang bingkisan dan amplop santunan bersama pendamping',
            'frame' => 'aspect-[4/3]',
            'pos' => 'center',
            'title' => 'Santunan anak yatim',
            'text' => 'Santunan langsung berupa bingkisan dan bantuan tunai, agar anak yatim ikut merasakan keceriaan seperti anak-anak lainnya. Disalurkan juga lewat GEREBEK, Gerakan Edukasi Berbagi Kasih.',
        ],
    ];

    $groups = \App\Support\Pillars::groups();
    $utama = collect($groups)->firstWhere('key', 'utama')['programs'] ?? [];
    $lainnya = array_values(array_filter($groups, fn ($g) => $g['key'] !== 'utama'));
    $utamaJs = [];
    foreach ($utama as $u) {
        $utamaJs[$u['slug']] = [
            'title' => $u['title'], 'tagline' => $u['tagline'], 'desc' => $u['desc'],
            'list_title' => $u['list_title'], 'list' => $u['list'],
            'img' => $u['img'] ? media_url($u['img']) : null, 'alt' => $u['alt'] ?? $u['title'], 'pos' => $u['pos'] ?? 'center',
            'url' => route('donation.create', ['type' => $u['type']]),
            'more' => route('programs.overview') . '#' . $u['slug'],
        ];
    }
@endphp
{{-- HERO --}}
<section class="relative overflow-hidden bg-brand-900 text-white">
    <div class="star-pattern absolute inset-0"></div>

    {{-- Hiasan latar --}}
    <div class="pointer-events-none absolute inset-0 overflow-hidden" aria-hidden="true">
        <div class="absolute -left-16 top-0 h-80 w-80 rounded-full bg-leaf-400/20 blur-3xl"></div>
        <div class="absolute bottom-0 left-[20%] h-72 w-72 rounded-full bg-brand-500/30 blur-3xl"></div>
        <svg viewBox="0 0 200 200" class="hero-spin absolute -left-40 top-1/2 hidden h-[34rem] w-[34rem] -translate-y-1/2 text-leaf-300/20 lg:block" fill="none" stroke="currentColor" stroke-width="0.8">
            <rect x="40" y="40" width="120" height="120"/><rect x="40" y="40" width="120" height="120" transform="rotate(45 100 100)"/>
            <circle cx="100" cy="100" r="62"/><circle cx="100" cy="100" r="40" stroke-dasharray="2 3"/><circle cx="100" cy="100" r="82" stroke-dasharray="1 4"/>
        </svg>
        <svg viewBox="0 0 64 64" class="hero-float absolute right-8 top-6 hidden h-14 w-14 text-leaf-300/80 lg:block" fill="currentColor"><path d="M44 8a26 26 0 1 0 12 40A22 22 0 0 1 44 8z"/><path d="M50 14l1.8 4.2 4.2 1.8-4.2 1.8L50 26l-1.8-4.2-4.2-1.8 4.2-1.8z" class="text-white"/></svg>
        <svg class="absolute bottom-24 left-[36%] hidden h-24 w-32 text-white/25 lg:block" viewBox="0 0 128 96"><defs><pattern id="hd" width="16" height="16" patternUnits="userSpaceOnUse"><circle cx="2" cy="2" r="1.6" fill="currentColor"/></pattern></defs><rect width="128" height="96" fill="url(#hd)"/></svg>
    </div>

    {{-- Tombol geser foto di tepi kiri dan kanan hero --}}
    <button type="button" onclick="window.dispatchEvent(new Event('hero-prev'))" aria-label="Foto sebelumnya"
            class="absolute left-3 top-1/2 z-20 hidden h-12 w-12 -translate-y-1/2 place-items-center rounded-full bg-white/10 text-white ring-1 ring-white/25 backdrop-blur transition hover:bg-leaf-400 hover:text-brand-950 lg:grid xl:left-5">
        <svg viewBox="0 0 24 24" class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
    </button>
    <button type="button" onclick="window.dispatchEvent(new Event('hero-next'))" aria-label="Foto berikutnya"
            class="absolute right-3 top-1/2 z-20 hidden h-12 w-12 -translate-y-1/2 place-items-center rounded-full bg-white/10 text-white ring-1 ring-white/25 backdrop-blur transition hover:bg-leaf-400 hover:text-brand-950 lg:grid xl:right-5">
        <svg viewBox="0 0 24 24" class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>
    </button>

    <div class="grid w-full items-center gap-10 px-5 pb-24 pt-10 md:pt-12 lg:min-h-[29rem] lg:grid-cols-12 lg:px-20 xl:px-24">
        <div class="relative z-10 max-w-2xl lg:col-span-7">
            @if(\App\Support\Pillars::headline())<p class="mb-4 text-xs font-semibold uppercase tracking-[0.14em] text-leaf-300 md:text-sm">{{ \App\Support\Pillars::headline() }}</p>@endif
            <h1 class="font-display text-3xl leading-[1.12] md:text-5xl">{{ setting('hero_title') }}</h1>
            <p class="mt-4 max-w-xl text-base leading-relaxed text-brand-200">{{ setting('hero_subtitle') }}</p>
            <div class="mt-8 flex flex-wrap gap-3">
                <a href="#program-utama" class="rounded-full bg-leaf-400 px-7 py-3.5 font-bold text-brand-950 transition hover:bg-leaf-300">Mulai berbagi</a>
                <a href="{{ route('reports') }}" class="rounded-full border border-brand-400 px-7 py-3.5 font-semibold text-white transition hover:bg-brand-800">Lihat laporan</a>
            </div>
            <div class="mt-8 flex flex-wrap gap-2.5">
                <span class="inline-flex items-center gap-2 rounded-full bg-white/10 px-3.5 py-2 text-sm font-semibold text-white ring-1 ring-white/20 backdrop-blur"><x-icon name="heart" class="h-4 w-4 text-leaf-300"/> Berbagi kebaikan</span>
                <span class="inline-flex items-center gap-2 rounded-full bg-white/10 px-3.5 py-2 text-sm font-semibold text-white ring-1 ring-white/20 backdrop-blur"><x-icon name="book" class="h-4 w-4 text-leaf-300"/> Pendidikan anak</span>
                <span class="inline-flex items-center gap-2 rounded-full bg-white/10 px-3.5 py-2 text-sm font-semibold text-white ring-1 ring-white/20 backdrop-blur"><x-icon name="hands" class="h-4 w-4 text-leaf-300"/> Peduli yatim &amp; dhuafa</span>
            </div>
        </div>

        {{-- Foto: di layar lebar memenuhi sisi kanan hero dan memudar ke arah teks --}}
        <div class="-mx-5 lg:absolute lg:inset-y-0 lg:right-0 lg:mx-0 lg:w-[60%]">
            @include('partials.hero-slider')
        </div>
    </div>
</section>

{{-- PROGRAM UTAMA: pilih ikon, detail tampil di bawahnya --}}
@if(count($utama))
<section class="relative z-10 mx-auto -mt-16 max-w-5xl px-4">
<div id="program-utama" class="scroll-mt-24 rounded-3xl bg-white p-5 shadow-xl shadow-brand-950/5 ring-1 ring-brand-100 md:p-10"
     x-data="{ sel: '{{ $utama[0]['slug'] }}', items: @js($utamaJs) }">
    <h3 class="text-center font-display text-2xl text-brand-900 md:text-3xl">Program Utama</h3>
    <p class="mx-auto mt-2 max-w-xl text-center text-sm text-brand-600">Program utama YGBY. Pilih salah satu untuk melihat program turunannya.</p>

    <div class="mt-8 grid grid-cols-3 gap-3 sm:grid-cols-4 md:grid-cols-7 md:gap-4" role="tablist" aria-label="Program utama">
        @foreach($utama as $pr)
            <button type="button" role="tab" @click="sel = '{{ $pr['slug'] }}'" :aria-selected="sel === '{{ $pr['slug'] }}'"
                    class="group flex flex-col items-center gap-2.5 text-center">
                <span :class="sel === '{{ $pr['slug'] }}' ? 'bg-brand-700 text-white shadow-lg shadow-brand-700/30' : 'border border-brand-100 bg-white text-brand-600 group-hover:border-brand-300 group-hover:bg-brand-50'"
                      class="grid h-20 w-20 place-items-center rounded-2xl transition md:h-24 md:w-24">
                    <x-icon :name="$pr['icon']" class="h-9 w-9 md:h-10 md:w-10" />
                </span>
                <span :class="sel === '{{ $pr['slug'] }}' ? 'font-bold text-brand-700' : 'text-brand-800'" class="text-sm leading-snug">{{ $pr['title'] }}</span>
            </button>
        @endforeach
        <a href="{{ route('programs.overview') }}" class="group flex flex-col items-center gap-2.5 text-center">
            <span class="grid h-20 w-20 place-items-center rounded-2xl border border-brand-100 bg-white text-brand-600 transition group-hover:border-brand-300 group-hover:bg-brand-50 md:h-24 md:w-24"><x-icon name="apps" class="h-9 w-9 md:h-10 md:w-10" /></span>
            <span class="text-sm leading-snug text-brand-800">Lainnya</span>
        </a>
    </div>

    <div class="mt-10 grid items-start gap-8 border-t border-brand-100 pt-10 lg:grid-cols-12" aria-live="polite">
        <template x-if="items[sel].img">
            <div class="lg:col-span-5">
                <img :src="items[sel].img" :alt="items[sel].alt" class="aspect-[4/3] w-full rounded-2xl object-cover" :style="'object-position:' + items[sel].pos">
            </div>
        </template>
        <div :class="items[sel].img ? 'lg:col-span-7' : 'lg:col-span-12'">
            <h4 class="font-display text-2xl text-brand-900 md:text-3xl" x-text="items[sel].title"></h4>
            <p class="mt-2 text-lg font-semibold text-brand-600" x-text="items[sel].tagline"></p>
            <p class="mt-3 leading-relaxed text-brand-800" x-text="items[sel].desc"></p>
            <h5 class="mt-5 font-semibold text-brand-900" x-text="items[sel].list_title"></h5>
            <ul class="mt-2 space-y-2">
                <template x-for="item in items[sel].list" :key="item">
                    <li class="flex gap-3 text-brand-800"><span class="mt-2 h-1.5 w-1.5 shrink-0 rounded-full bg-leaf-500"></span><span class="leading-relaxed" x-text="item"></span></li>
                </template>
            </ul>
            <div class="mt-6 flex flex-wrap gap-3">
                <a :href="items[sel].url" class="rounded-full bg-leaf-400 px-6 py-3 text-sm font-bold text-brand-950 transition hover:bg-leaf-300">Dukung program ini</a>
                <a :href="items[sel].more" class="rounded-full border border-brand-300 px-6 py-3 text-sm font-semibold text-brand-800 transition hover:bg-brand-50">Lihat selengkapnya</a>
            </div>
        </div>
    </div>
</div>
</section>
@endif

{{-- PROGRAM UNGGULAN (dari menu Program donasi) --}}
@if($flagship->isNotEmpty())
<section class="mx-auto mt-24 max-w-6xl px-4">
    <h2 class="max-w-2xl font-display text-3xl text-brand-900 md:text-4xl">Kebaikan yang sampai ke tangan mereka</h2>
    <p class="mt-3 max-w-2xl text-brand-700">Program galang dana yang sedang berjalan. Pilih yang paling dekat dengan hati Anda.</p>

    <div class="mt-12 space-y-16 md:space-y-20">
        @foreach($flagshipView as $i => $f)
            @php $p = $flagship[$f['slug']] ?? null; @endphp
            @continue(! $p)
            <article class="grid items-center gap-8 lg:grid-cols-12 lg:gap-12">
                <div class="lg:col-span-6 {{ $i % 2 ? 'lg:order-2' : '' }}">
                    <img src="{{ media_url($p->image ?: $f['img']) }}" alt="{{ $f['alt'] }}" loading="lazy" class="w-full rounded-3xl object-cover {{ $f['frame'] }}" style="object-position: {{ $f['pos'] }}">
                </div>
                <div class="lg:col-span-6 {{ $i % 2 ? 'lg:order-1' : '' }}">
                    <h3 class="font-display text-2xl text-brand-900 md:text-3xl">{{ $p->title ?? $f['title'] }}</h3>
                    <p class="mt-3 leading-relaxed text-brand-700">{{ $p->excerpt ?: $f['text'] }}</p>

                    @if($p && $p->target > 0)
                        <div class="mt-6 max-w-md">
                            <x-progress :value="$p->progress" />
                            <div class="mt-2 flex justify-between text-sm">
                                <span class="font-bold text-brand-900">{{ rupiah($p->collected) }}</span>
                                <span class="text-brand-600">target {{ rupiah($p->target) }}</span>
                            </div>
                        </div>
                    @endif

                    <div class="mt-6 flex flex-wrap gap-3">
                        <a href="{{ route('donation.create', array_filter(['program' => $p?->id])) }}" class="rounded-full bg-brand-800 px-6 py-3 text-sm font-bold text-white transition hover:bg-brand-700">Donasi untuk program ini</a>
                        @if($p)<a href="{{ route('programs.show', $p) }}" class="rounded-full border border-brand-300 px-6 py-3 text-sm font-semibold text-brand-800 transition hover:bg-brand-100">Baca selengkapnya</a>@endif
                    </div>
                </div>
            </article>
        @endforeach
    </div>
</section>

@endif

{{-- PROGRAM LAINNYA --}}
@if($others->isNotEmpty())
<section class="mx-auto mt-24 max-w-6xl px-4">
    <div class="flex flex-wrap items-end justify-between gap-4">
        <h2 class="font-display text-3xl text-brand-900 md:text-4xl">Program lainnya</h2>
        <a href="{{ route('programs.index') }}" class="rounded-full border border-brand-300 px-5 py-2.5 text-sm font-semibold text-brand-800 hover:bg-brand-100">Lihat semua program</a>
    </div>
    <div class="mt-8 grid gap-6 md:grid-cols-3">
        @foreach($others as $program) @include('partials.program-card', ['program' => $program]) @endforeach
    </div>
</section>
@endif

{{-- PROGRAM YGBY: program utama, gerakan, wakaf, penguatan --}}
<section class="mx-auto mt-24 max-w-6xl px-4">
    <div class="grid gap-8 lg:grid-cols-12">
        <div class="lg:col-span-5">
            <h2 class="font-display text-3xl text-brand-900 md:text-4xl">Tentang {{ setting('site_name') }}</h2>
            <a href="{{ route('about') }}" class="mt-6 inline-block rounded-full border border-brand-300 px-6 py-3 text-sm font-semibold text-brand-800 hover:bg-brand-100">Kenali yayasan kami</a>
        </div>
        <div class="space-y-4 lg:col-span-7">
            @foreach(\App\Support\Pillars::intro() as $para)
                <p class="leading-relaxed text-brand-800 {{ $loop->first ? 'text-lg' : '' }}">{{ $para }}</p>
            @endforeach
        </div>
    </div>

    @foreach($lainnya as $g)
        <div class="mt-14">
            <h3 class="font-display text-2xl text-brand-900 md:text-3xl">{{ $g['letter'] ? $g['letter'] . '. ' : '' }}{{ $g['title'] }}</h3>
            @if($g['note'])<p class="mt-2 max-w-2xl text-brand-700">{{ $g['note'] }}</p>@endif
            <div class="mt-6 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach($g['programs'] as $pr) @include('partials.pillar-card', ['pr' => $pr]) @endforeach
            </div>
        </div>
    @endforeach

    <div class="mt-10 text-center">
        <a href="{{ route('programs.overview') }}" class="inline-block rounded-full bg-brand-800 px-7 py-3 text-sm font-bold text-white hover:bg-brand-700">Lihat semua program turunan</a>
    </div>
</section>

{{-- CARA BERDONASI --}}
<section class="mx-auto mt-24 max-w-6xl px-4">
    <div class="rounded-3xl bg-brand-50 p-8 md:p-12">
        <h2 class="font-display text-3xl text-brand-900">Berbagi dalam tiga langkah</h2>
        <ol class="mt-8 grid gap-8 md:grid-cols-3">
            @foreach([
                ['Pilih jenis dan nominal', 'Tentukan zakat, infak, sedekah, atau wakaf, lalu isi nama Anda. Boleh tanpa menampilkan nama.'],
                ['Transfer sesuai nominal', 'Transfer ke rekening yayasan dengan tambahan kode unik agar donasi mudah kami kenali.'],
                ['Unggah bukti transfer', 'Kami memverifikasi donasi Anda, lalu dana tercatat dan dilaporkan di halaman laporan.'],
            ] as $i => [$h, $d])
                <li class="flex gap-4">
                    <span class="grid h-9 w-9 shrink-0 place-items-center rounded-full bg-brand-800 font-bold text-leaf-400">{{ $i + 1 }}</span>
                    <div>
                        <h3 class="font-semibold text-brand-900">{{ $h }}</h3>
                        <p class="mt-1 text-sm leading-relaxed text-brand-700">{{ $d }}</p>
                    </div>
                </li>
            @endforeach
        </ol>
    </div>
</section>

{{-- KABAR TERBARU --}}
@if($posts->isNotEmpty())
<section class="mx-auto mt-24 max-w-6xl px-4">
    <div class="flex flex-wrap items-end justify-between gap-4">
        <h2 class="font-display text-3xl text-brand-900 md:text-4xl">Kabar terbaru</h2>
        <a href="{{ route('posts.index') }}" class="rounded-full border border-brand-300 px-5 py-2.5 text-sm font-semibold text-brand-800 hover:bg-brand-100">Semua kabar</a>
    </div>
    <div class="mt-8 grid gap-6 md:grid-cols-3">
        @foreach($posts as $post) @include('partials.post-card', ['post' => $post]) @endforeach
    </div>
</section>
@endif

{{-- DONATUR TERBARU & TRANSPARANSI --}}
<section class="mx-auto mt-24 grid max-w-6xl gap-10 px-4 lg:grid-cols-12">
    <div class="lg:col-span-7">
        <h2 class="font-display text-3xl text-brand-900">Donatur terbaru</h2>
        <ul class="mt-6 divide-y divide-brand-100 rounded-2xl border border-brand-100 bg-white">
            @forelse($latestDonations as $d)
                <li class="flex items-start justify-between gap-4 p-4">
                    <div class="min-w-0">
                        <p class="font-semibold text-brand-900">{{ $d->display_name }}</p>
                        <p class="truncate text-xs text-brand-600">{{ $d->program?->title ?? $d->type_label }}</p>
                        @if($d->message)<p class="mt-1 text-sm italic text-brand-700">&ldquo;{{ \Illuminate\Support\Str::limit($d->message, 90) }}&rdquo;</p>@endif
                    </div>
                    <div class="shrink-0 text-right">
                        <p class="text-sm font-bold text-brand-800">{{ rupiah($d->amount) }}</p>
                        <p class="text-xs text-brand-500">{{ optional($d->paid_at)->diffForHumans() }}</p>
                    </div>
                </li>
            @empty
                <li class="p-6 text-center text-sm text-brand-600">Jadilah donatur pertama yang menitipkan kebaikan di sini.</li>
            @endforelse
        </ul>
    </div>
    <div class="lg:col-span-5">
        <div class="rounded-3xl bg-brand-800 p-8 text-white">
            <h2 class="font-display text-2xl">Amanah Anda kami laporkan terbuka</h2>
            <p class="mt-3 leading-relaxed text-brand-200">Setiap donasi yang diterima dan setiap dana yang disalurkan bisa Anda lihat dalam laporan bulanan dan tahunan.</p>
            <a href="{{ route('reports') }}" class="mt-6 inline-block rounded-full bg-leaf-400 px-6 py-3 text-sm font-bold text-brand-950 hover:bg-leaf-300">Buka laporan</a>
        </div>
    </div>
</section>

{{-- PENUTUP --}}
<section class="mx-auto mt-24 max-w-6xl px-4">
    <div class="relative overflow-hidden rounded-3xl bg-brand-900 px-8 py-12 text-center text-white md:py-16">
        <div class="star-pattern absolute inset-0"></div>
        <div class="relative">
            <h2 class="mx-auto max-w-2xl font-display text-3xl md:text-4xl">Sedekah tidak akan mengurangi harta, tetapi membukakan pintu kebaikan</h2>
            <p class="mx-auto mt-4 max-w-xl text-brand-200">Mulai dari nominal yang Anda mampu. Kami menjaga amanah ini agar sampai kepada yang berhak.</p>
            <a href="#program-utama" class="mt-8 inline-block rounded-full bg-leaf-400 px-8 py-3.5 font-bold text-brand-950 hover:bg-leaf-300">Mulai berbagi</a>
        </div>
    </div>
</section>

{{-- STATISTIK: ikon di atas, angka besar, label di bawah --}}
<section class="mx-auto mt-20 max-w-6xl px-4">
    <h2 class="text-center font-display text-3xl text-brand-900 md:text-4xl">Mengapa berdonasi di {{ setting('site_name') }}?</h2>
    <dl class="mt-12 grid grid-cols-2 gap-x-4 gap-y-12 text-center lg:grid-cols-4">
        @foreach([
            ['Donasi terkumpul', rupiah($stats['collected']), $stats['collected'], 'Rp ', 'wallet'],
            ['Dana tersalur', rupiah($stats['distributed']), $stats['distributed'], 'Rp ', 'gift'],
            ['Jumlah donatur', number_format($stats['donors'], 0, ',', '.'), $stats['donors'], '', 'users'],
            ['Program aktif', $stats['programs'], $stats['programs'], '', 'sprout'],
        ] as [$label, $value, $raw, $prefix, $icon])
            <div class="flex flex-col items-center">
                <x-icon :name="$icon" class="h-14 w-14 text-brand-600 [stroke-width:1.4]" />
                <dd class="mt-4 text-3xl font-extrabold tracking-tight text-brand-700 md:text-4xl" data-count="{{ (int) $raw }}" data-prefix="{{ $prefix }}">{{ $value }}</dd>
                <dt class="mt-1 text-brand-900">{{ $label }}</dt>
            </div>
        @endforeach
    </dl>
</section>
@endsection
