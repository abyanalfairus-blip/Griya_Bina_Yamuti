@php
    // Dokumen legalitas yayasan. Berkas ada di public/images/legalitas (pratinjau) dan public/berkas (PDF).
    $docs = [
        ['slug' => 'sk-kemenkumham', 'judul' => 'SK Pengesahan Badan Hukum', 'instansi' => 'Kementerian Hukum dan HAM RI',
         'nomor' => 'AHU-0008632.AH.01.04.Tahun 2023', 'tanggal' => '31 Mei 2023', 'icon' => 'building',
         'ket' => 'Pengesahan pendirian badan hukum Yayasan Griya Bina Yamuti. Terdaftar pada Daftar Yayasan Nomor AHU-0012211.AH.01.12.Tahun 2023.'],
        ['slug' => 'lampiran-akta', 'judul' => 'Lampiran SK Akta Hukum', 'instansi' => 'Kementerian Hukum dan HAM RI',
         'nomor' => 'AHU-0008632.AH.01.04.Tahun 2023', 'tanggal' => '31 Mei 2023', 'icon' => 'users',
         'ket' => 'Memuat kekayaan awal, pendiri, dan susunan organ yayasan (pembina, pengurus, dan pengawas).'],
        ['slug' => 'penetapan-lks-dinsos', 'judul' => 'Penetapan Tercatat sebagai LKS', 'instansi' => 'Dinas Sosial Kota Sukabumi',
         'nomor' => 'Penetapan Dinsos Tahun 2025', 'tanggal' => '20 Juni 2025', 'icon' => 'shield',
         'ket' => 'Tercatat sebagai Lembaga Kesejahteraan Sosial (LKS). Berlaku sampai dengan 20 Juni 2028.'],
        ['slug' => 'akreditasi-kemensos', 'judul' => 'Sertifikat Akreditasi LKS', 'instansi' => 'Kementerian Sosial RI',
         'nomor' => '0867.SA-LKS.C/2024', 'tanggal' => '5 Juli 2024', 'icon' => 'handshake',
         'ket' => 'Akreditasi Lembaga Kesejahteraan Sosial dengan peringkat Cukup (C), masa berlaku 5 Juli 2024 sampai 5 Juli 2026.'],
        ['slug' => 'sk-upz-baznas', 'judul' => 'SK Unit Pengumpul Zakat (UPZ)', 'instansi' => 'BAZNAS Kota Sukabumi',
         'nomor' => '139/SK-UPZ/BAZNAS.KOSI/II/2026', 'tanggal' => '25 Februari 2026', 'icon' => 'wallet',
         'ket' => 'Pembentukan Unit Pengumpul Zakat Yayasan Griya Bina Yamuti untuk menghimpun dan menyalurkan zakat, infak, dan sedekah.'],
    ];
@endphp

<section class="mx-auto mt-20 max-w-6xl px-4" x-data="{ open: null, docs: @js(array_map(fn($d) => ['t' => $d['judul'], 'img' => asset('images/legalitas/'.$d['slug'].'.jpg'), 'pdf' => asset('berkas/'.$d['slug'].'.pdf')], $docs)) }" @keydown.escape.window="open = null">
    <div class="mx-auto max-w-3xl text-center">
        <p class="text-xs font-semibold uppercase tracking-[0.14em] text-leaf-600">Legalitas dan perizinan</p>
        <h2 class="mt-3 font-display text-3xl italic leading-snug text-brand-900 md:text-4xl">Resmi, tercatat, dan dapat dipertanggungjawabkan.</h2>
        <p class="mt-4 text-lg font-semibold italic leading-relaxed text-brand-800">Sejak disahkan sebagai badan hukum pada 2023, Yayasan Griya Bina Yamuti terus berupaya menjaga amanah donatur melalui kelengkapan izin dan pengawasan lembaga resmi.</p>
    </div>

    <div class="mx-auto mt-10 max-w-3xl space-y-5 text-justify leading-relaxed text-brand-800">
        <p>Yayasan Griya Bina Yamuti (YGBY) adalah lembaga sosial yang berkedudukan di Kota Sukabumi dan berfokus pada pelayanan anak yatim, dhuafa, dan anak terlantar. Yayasan ini telah memperoleh pengesahan sebagai badan hukum dari Kementerian Hukum dan Hak Asasi Manusia Republik Indonesia melalui Keputusan Nomor AHU-0008632.AH.01.04.Tahun 2023 tanggal 31 Mei 2023, berdasarkan Akta Notaris Nomor 95 tanggal 29 Mei 2023.</p>
        <p>Dalam penyelenggaraan kesejahteraan sosial, YGBY tercatat sebagai Lembaga Kesejahteraan Sosial (LKS) di Dinas Sosial Kota Sukabumi dan memiliki sertifikat akreditasi dari Kementerian Sosial Republik Indonesia. Untuk pengelolaan zakat, infak, dan sedekah, YGBY telah ditetapkan sebagai Unit Pengumpul Zakat (UPZ) oleh BAZNAS Kota Sukabumi sehingga penghimpunan dan penyaluran dana berjalan sesuai ketentuan perundang-undangan.</p>
        <p>Kami menyediakan salinan dokumen di bawah ini agar donatur dan mitra dapat memeriksanya langsung. Keterbukaan adalah bagian dari amanah: setiap donasi yang Anda titipkan kami kelola dengan jujur, tertib, dan kami laporkan secara berkala.</p>
    </div>

    <div class="mt-12 grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
        @foreach($docs as $k => $d)
            <article class="group flex flex-col overflow-hidden rounded-2xl bg-white ring-1 ring-brand-100 transition hover:-translate-y-1 hover:shadow-xl hover:shadow-brand-950/10">
                <button type="button" @click="open = {{ $k }}" class="relative block aspect-[4/3] overflow-hidden bg-brand-50 text-left" aria-label="Lihat {{ $d['judul'] }}">
                    <img src="{{ asset('images/legalitas/'.$d['slug'].'.jpg') }}" alt="{{ $d['judul'] }}" loading="lazy" class="h-full w-full object-cover object-top transition duration-500 group-hover:scale-105">
                    <span class="absolute inset-x-0 bottom-0 h-1/3 bg-gradient-to-t from-brand-950/50 to-transparent"></span>
                    <span class="absolute bottom-3 right-3 rounded-full bg-white/90 px-3 py-1 text-xs font-bold text-brand-900">Lihat dokumen</span>
                </button>
                <div class="flex flex-1 flex-col p-5">
                    <div class="flex items-center gap-2.5">
                        <span class="grid h-9 w-9 shrink-0 place-items-center rounded-full bg-leaf-100 text-leaf-600"><x-icon name="{{ $d['icon'] }}" class="h-5 w-5"/></span>
                        <h3 class="font-display text-lg leading-tight text-brand-900">{{ $d['judul'] }}</h3>
                    </div>
                    <p class="mt-3 text-sm leading-relaxed text-brand-700">{{ $d['ket'] }}</p>
                    <dl class="mt-4 space-y-1 border-t border-brand-100 pt-3 text-xs text-brand-600">
                        <div class="flex gap-2"><dt class="w-16 shrink-0 font-semibold">Penerbit</dt><dd>{{ $d['instansi'] }}</dd></div>
                        <div class="flex gap-2"><dt class="w-16 shrink-0 font-semibold">Nomor</dt><dd class="break-all">{{ $d['nomor'] }}</dd></div>
                        <div class="flex gap-2"><dt class="w-16 shrink-0 font-semibold">Tanggal</dt><dd>{{ $d['tanggal'] }}</dd></div>
                    </dl>
                    <div class="mt-auto flex gap-2 pt-4">
                        <button type="button" @click="open = {{ $k }}" class="flex-1 rounded-full bg-brand-800 px-4 py-2 text-sm font-bold text-white transition hover:bg-brand-700">Lihat</button>
                        <a href="{{ asset('berkas/'.$d['slug'].'.pdf') }}" download class="flex-1 rounded-full border border-brand-200 px-4 py-2 text-center text-sm font-semibold text-brand-800 transition hover:bg-brand-50">Unduh PDF</a>
                    </div>
                </div>
            </article>
        @endforeach
    </div>

    {{-- Tampilan dokumen layar penuh --}}
    <div x-show="open !== null" x-cloak x-transition.opacity class="fixed inset-0 z-[70] flex flex-col bg-brand-950/90 p-3 backdrop-blur-sm md:p-6" role="dialog" aria-modal="true" @click.self="open = null">
        <div class="mx-auto flex w-full max-w-4xl items-center justify-between gap-3 pb-3 text-white">
            <p class="truncate font-semibold" x-text="open !== null ? docs[open].t : ''"></p>
            <div class="flex shrink-0 items-center gap-2">
                <a :href="open !== null ? docs[open].pdf : '#'" download class="rounded-full bg-leaf-400 px-4 py-2 text-sm font-bold text-brand-950 hover:bg-leaf-300">Unduh PDF</a>
                <button type="button" @click="open = null" aria-label="Tutup" class="grid h-9 w-9 place-items-center rounded-full bg-white/15 hover:bg-white/30"><x-icon name="close" class="h-5 w-5"/></button>
            </div>
        </div>
        <div class="mx-auto min-h-0 w-full max-w-4xl flex-1 overflow-auto rounded-xl bg-white" @click.self="open = null">
            <img :src="open !== null ? docs[open].img : ''" :alt="open !== null ? docs[open].t : ''" class="mx-auto h-auto w-full">
        </div>
    </div>
</section>
