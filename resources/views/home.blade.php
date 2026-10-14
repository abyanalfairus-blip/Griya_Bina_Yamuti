@extends('layouts.app')
@section('title', setting('site_name') . ': ' . setting('tagline'))

@section('content')
{{-- HERO: panel donasi cepat adalah elemen utama --}}
<section class="relative overflow-hidden bg-pine-900 text-white">
    <div class="star-pattern absolute inset-0"></div>
    <div class="relative mx-auto grid max-w-6xl items-center gap-10 px-4 py-14 md:py-20 lg:grid-cols-12">
        <div class="lg:col-span-7">
            <h1 class="font-display text-4xl leading-[1.1] md:text-6xl">{{ setting('hero_title') }}</h1>
            <p class="mt-5 max-w-xl text-lg leading-relaxed text-pine-200">{{ setting('hero_subtitle') }}</p>
            <div class="mt-8 flex flex-wrap gap-3">
                <a href="{{ route('donation.create') }}" class="rounded-full bg-marigold-400 px-7 py-3.5 font-bold text-pine-950 transition hover:bg-marigold-300">Mulai berdonasi</a>
                <a href="{{ route('zakat') }}" class="rounded-full border border-pine-400 px-7 py-3.5 font-semibold text-white transition hover:bg-pine-800">Hitung zakat</a>
            </div>
        </div>

        @if($featured)
        <div class="lg:col-span-5" x-data="{ amount: 100000 }">
            <div class="rounded-3xl bg-white p-6 text-pine-950 shadow-2xl shadow-black/20">
                <p class="text-sm font-semibold text-pine-600">Program yang sedang membutuhkan</p>
                <h2 class="mt-1 font-display text-2xl leading-snug text-pine-900">{{ $featured->title }}</h2>
                <div class="mt-5">
                    <x-progress :value="$featured->progress" />
                    <div class="mt-2 flex justify-between text-sm">
                        <span class="font-bold">{{ rupiah($featured->collected) }}</span>
                        <span class="text-pine-600">target {{ rupiah($featured->target) }}</span>
                    </div>
                </div>
                <p class="mt-5 text-sm font-semibold">Pilih nominal donasi</p>
                <div class="mt-2 grid grid-cols-3 gap-2">
                    @foreach([25000, 50000, 100000, 250000, 500000, 1000000] as $n)
                        <button type="button" @click="amount={{ $n }}"
                                :class="amount==={{ $n }} ? 'border-pine-700 bg-pine-50 text-pine-800' : 'border-pine-100 text-pine-700 hover:border-pine-300'"
                                class="rounded-xl border-2 py-2.5 text-sm font-bold transition">{{ number_format($n/1000, 0, ',', '.') }}rb</button>
                    @endforeach
                </div>
                <a :href="'{{ route('donation.create') }}?program={{ $featured->id }}&amp;amount=' + amount"
                   class="mt-5 block rounded-full bg-pine-800 py-3.5 text-center font-bold text-white transition hover:bg-pine-700">
                    Donasi <span x-text="'Rp ' + amount.toLocaleString('id-ID')"></span>
                </a>
                <a href="{{ route('programs.show', $featured) }}" class="mt-3 block text-center text-sm font-medium text-pine-600 underline-offset-4 hover:underline">Baca selengkapnya tentang program ini</a>
            </div>
        </div>
        @endif
    </div>
</section>

{{-- Statistik --}}
<section class="border-b border-pine-100 bg-white">
    <dl class="mx-auto grid max-w-6xl grid-cols-2 gap-y-6 px-4 py-8 md:grid-cols-4">
        @foreach([
            ['Donasi terkumpul', rupiah($stats['collected'])],
            ['Dana tersalur', rupiah($stats['distributed'])],
            ['Donatur', number_format($stats['donors'], 0, ',', '.')],
            ['Program aktif', $stats['programs']],
        ] as [$label, $value])
            <div class="md:border-l md:border-pine-100 md:pl-6 md:first:border-0 md:first:pl-0">
                <dd class="font-display text-2xl text-pine-800 md:text-3xl">{{ $value }}</dd>
                <dt class="mt-1 text-sm text-pine-600">{{ $label }}</dt>
            </div>
        @endforeach
    </dl>
</section>

{{-- Program pilihan --}}
<section class="mx-auto max-w-6xl px-4 pt-20">
    <div class="flex flex-wrap items-end justify-between gap-4">
        <div>
            <h2 class="font-display text-3xl text-pine-900 md:text-4xl">Program yang bisa Anda dukung</h2>
            <p class="mt-2 max-w-xl text-pine-700">Pilih program yang paling dekat dengan hati Anda. Setiap rupiah tercatat dan dilaporkan.</p>
        </div>
        <a href="{{ route('programs.index') }}" class="rounded-full border border-pine-300 px-5 py-2.5 text-sm font-semibold text-pine-800 hover:bg-pine-100">Lihat semua program</a>
    </div>
    @if($programs->isEmpty())
        <p class="mt-10 rounded-2xl border border-dashed border-pine-200 p-10 text-center text-pine-600">Belum ada program aktif. Tambahkan program melalui panel admin.</p>
    @else
        <div class="mt-10 grid gap-6 md:grid-cols-2 lg:grid-cols-3">
            @foreach($programs as $program)
                @include('partials.program-card', ['program' => $program])
            @endforeach
        </div>
    @endif
</section>

{{-- Tentang & pilar pembinaan --}}
<section class="mx-auto mt-24 max-w-6xl px-4">
    <div class="grid gap-12 lg:grid-cols-12">
        <div class="lg:col-span-5">
            <h2 class="font-display text-3xl text-pine-900 md:text-4xl">Tentang {{ setting('site_name') }}</h2>
            <p class="mt-4 leading-relaxed text-pine-700">{{ setting('about_short') }}</p>
            <a href="{{ route('about') }}" class="mt-6 inline-block rounded-full bg-pine-800 px-6 py-3 text-sm font-bold text-white hover:bg-pine-700">Kenali yayasan kami</a>
        </div>
        <ul class="divide-y divide-pine-100 lg:col-span-7">
            @foreach([
                ['Bina Taqwa', 'Pembinaan akhlak, pengajian rutin, dan tahfidz untuk menumbuhkan kemandirian dan kreativitas anak yatim binaan.'],
                ['Bina Belajar', 'Beasiswa penunjang pendidikan yang diberikan setiap semester kepada anak binaan.'],
                ['S.E.M.A.R.T', 'Sidiq, Empati, Mandiri, Amanah, Religius, Terampil: sasaran pembinaan melalui berbagai kegiatan kreatif.'],
                ['Bina Sejahtera', 'Kemitraan dan pemberdayaan untuk meningkatkan taraf hidup dan kesejahteraan anak-anak.'],
                ['Santunan Kreatif', 'Santunan langsung dan GEREBEK (Gerakan Edukasi Berbagi Kasih) bagi seluruh yatim binaan.'],
                ['Wakaf Produktif', 'Wakaf uang, tanah, atau bangunan yang dikelola produktif demi manfaat berkelanjutan.'],
            ] as [$name, $desc])
                <li class="grid gap-1 py-4 sm:grid-cols-3 sm:gap-6">
                    <p class="font-display text-lg text-pine-800">{{ $name }}</p>
                    <p class="text-sm leading-relaxed text-pine-700 sm:col-span-2">{{ $desc }}</p>
                </li>
            @endforeach
        </ul>
    </div>
</section>

{{-- Cara berdonasi: urutan langkah yang sebenarnya --}}
<section class="mx-auto mt-24 max-w-6xl px-4">
    <div class="rounded-3xl bg-pine-50 p-8 md:p-12">
        <h2 class="font-display text-3xl text-pine-900">Berdonasi dalam tiga langkah</h2>
        <ol class="mt-8 grid gap-8 md:grid-cols-3">
            @foreach([
                ['Pilih program dan nominal', 'Tentukan program, jumlah donasi, dan isi nama Anda. Boleh tanpa menampilkan nama.'],
                ['Transfer sesuai nominal', 'Transfer ke rekening yayasan dengan tambahan kode unik agar donasi mudah kami kenali.'],
                ['Unggah bukti transfer', 'Kami memverifikasi donasi Anda, lalu dana tercatat dan dilaporkan di halaman laporan.'],
            ] as $i => [$h, $d])
                <li class="flex gap-4">
                    <span class="grid h-9 w-9 shrink-0 place-items-center rounded-full bg-pine-800 font-bold text-marigold-400">{{ $i + 1 }}</span>
                    <div>
                        <h3 class="font-semibold text-pine-900">{{ $h }}</h3>
                        <p class="mt-1 text-sm leading-relaxed text-pine-700">{{ $d }}</p>
                    </div>
                </li>
            @endforeach
        </ol>
    </div>
</section>

{{-- Kabar terbaru --}}
@if($posts->isNotEmpty())
<section class="mx-auto mt-24 max-w-6xl px-4">
    <div class="flex flex-wrap items-end justify-between gap-4">
        <h2 class="font-display text-3xl text-pine-900 md:text-4xl">Kabar terbaru</h2>
        <a href="{{ route('posts.index') }}" class="rounded-full border border-pine-300 px-5 py-2.5 text-sm font-semibold text-pine-800 hover:bg-pine-100">Semua kabar</a>
    </div>
    <div class="mt-8 grid gap-6 md:grid-cols-3">
        @foreach($posts as $post) @include('partials.post-card', ['post' => $post]) @endforeach
    </div>
</section>
@endif

{{-- Donatur terbaru + galeri --}}
<section class="mx-auto mt-24 grid max-w-6xl gap-10 px-4 lg:grid-cols-12">
    <div class="lg:col-span-5">
        <h2 class="font-display text-3xl text-pine-900">Donatur terbaru</h2>
        <ul class="mt-6 divide-y divide-pine-100 rounded-2xl border border-pine-100 bg-white">
            @forelse($latestDonations as $d)
                <li class="flex items-start justify-between gap-4 p-4">
                    <div class="min-w-0">
                        <p class="font-semibold text-pine-900">{{ $d->display_name }}</p>
                        <p class="truncate text-xs text-pine-600">{{ $d->program?->title ?? $d->type_label }}</p>
                        @if($d->message)<p class="mt-1 text-sm italic text-pine-700">&ldquo;{{ \Illuminate\Support\Str::limit($d->message, 90) }}&rdquo;</p>@endif
                    </div>
                    <div class="shrink-0 text-right">
                        <p class="text-sm font-bold text-pine-800">{{ rupiah($d->amount) }}</p>
                        <p class="text-xs text-pine-500">{{ optional($d->paid_at)->diffForHumans() }}</p>
                    </div>
                </li>
            @empty
                <li class="p-6 text-center text-sm text-pine-600">Jadilah donatur pertama yang menitipkan kebaikan di sini.</li>
            @endforelse
        </ul>
    </div>
    <div class="lg:col-span-7">
        <h2 class="font-display text-3xl text-pine-900">Momen kebersamaan</h2>
        @if($galleries->isNotEmpty())
            <div class="mt-6 grid grid-cols-3 gap-3">
                @foreach($galleries as $g)
                    <x-cover :src="$g->image" :alt="$g->title" class="aspect-square rounded-xl" />
                @endforeach
            </div>
            <a href="{{ route('gallery') }}" class="mt-5 inline-block text-sm font-semibold text-pine-700 underline-offset-4 hover:underline">Buka galeri lengkap</a>
        @else
            <p class="mt-6 rounded-2xl border border-dashed border-pine-200 p-10 text-center text-pine-600">Foto kegiatan akan tampil di sini setelah diunggah lewat panel admin.</p>
        @endif
    </div>
</section>

{{-- Ajakan penutup --}}
<section class="mx-auto mt-24 max-w-6xl px-4">
    <div class="relative overflow-hidden rounded-3xl bg-pine-800 px-8 py-12 text-center text-white md:py-16">
        <div class="star-pattern absolute inset-0"></div>
        <div class="relative">
            <h2 class="mx-auto max-w-2xl font-display text-3xl md:text-4xl">Sedekah tidak akan mengurangi harta, tetapi membukakan pintu kebaikan</h2>
            <p class="mx-auto mt-4 max-w-xl text-pine-200">Mulai dari nominal yang Anda mampu. Kami menjaga amanah ini agar sampai kepada yang berhak.</p>
            <a href="{{ route('donation.create') }}" class="mt-8 inline-block rounded-full bg-marigold-400 px-8 py-3.5 font-bold text-pine-950 hover:bg-marigold-300">Donasi sekarang</a>
        </div>
    </div>
</section>
@endsection
