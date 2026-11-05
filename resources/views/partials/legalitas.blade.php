@php
    // Dokumen legalitas yayasan (hanya redaksi; salinan dokumen tidak ditampilkan atau diunduh di situs).
    $docs = [
        ['judul' => 'SK Pengesahan Badan Hukum', 'instansi' => 'Kementerian Hukum dan HAM RI',
         'nomor' => 'AHU-0008632.AH.01.04.Tahun 2023', 'tanggal' => '31 Mei 2023', 'icon' => 'building',
         'ket' => 'Pengesahan pendirian badan hukum Yayasan Griya Bina Yamuti. Terdaftar pada Daftar Yayasan Nomor AHU-0012211.AH.01.12.Tahun 2023.'],
        ['judul' => 'Lampiran SK Akta Hukum', 'instansi' => 'Kementerian Hukum dan HAM RI',
         'nomor' => 'AHU-0008632.AH.01.04.Tahun 2023', 'tanggal' => '31 Mei 2023', 'icon' => 'users',
         'ket' => 'Memuat kekayaan awal, pendiri, dan susunan organ yayasan (pembina, pengurus, dan pengawas).'],
        ['judul' => 'Penetapan Tercatat sebagai LKS', 'instansi' => 'Dinas Sosial Kota Sukabumi',
         'nomor' => null, 'tanggal' => '20 Juni 2025', 'icon' => 'shield',
         'ket' => 'Tercatat sebagai Lembaga Kesejahteraan Sosial (LKS). Berlaku sampai dengan 20 Juni 2028.'],
        ['judul' => 'Sertifikat Akreditasi LKS', 'instansi' => 'Kementerian Sosial RI',
         'nomor' => '0867.SA-LKS.C/2024', 'tanggal' => '5 Juli 2024', 'icon' => 'handshake',
         'ket' => 'Akreditasi Lembaga Kesejahteraan Sosial dengan peringkat Cukup (C), masa berlaku 5 Juli 2024 sampai 5 Juli 2026.'],
        ['judul' => 'SK Unit Pengumpul Zakat (UPZ)', 'instansi' => 'BAZNAS Kota Sukabumi',
         'nomor' => '139/SK-UPZ/BAZNAS.KOSI/II/2026', 'tanggal' => '25 Februari 2026', 'icon' => 'wallet',
         'ket' => 'Pembentukan Unit Pengumpul Zakat Yayasan Griya Bina Yamuti untuk menghimpun dan menyalurkan zakat, infak, dan sedekah.'],
    ];
@endphp

<section class="mx-auto mt-14 max-w-6xl px-4 md:mt-20">
    <div class="mx-auto max-w-3xl text-center">
        <p class="text-xs font-semibold uppercase tracking-[0.14em] text-leaf-600">Legalitas dan perizinan</p>
        <h2 class="mt-3 font-display text-2xl italic leading-snug text-brand-900 md:text-4xl">Resmi, tercatat, dan dapat dipertanggungjawabkan.</h2>
        <p class="mt-4 font-semibold italic leading-relaxed text-brand-800 md:text-lg">Sejak disahkan sebagai badan hukum pada 2023, Yayasan Griya Bina Yamuti terus berupaya menjaga amanah donatur melalui kelengkapan izin dan pengawasan lembaga resmi.</p>
    </div>

    <div class="mx-auto mt-10 max-w-3xl space-y-5 text-justify leading-relaxed text-brand-800">
        <p>Yayasan Griya Bina Yamuti (YGBY) adalah lembaga sosial yang berkedudukan di Kota Sukabumi dan berfokus pada pelayanan anak yatim, dhuafa, dan anak terlantar. Yayasan ini telah memperoleh pengesahan sebagai badan hukum dari Kementerian Hukum dan Hak Asasi Manusia Republik Indonesia melalui Keputusan Nomor AHU-0008632.AH.01.04.Tahun 2023 tanggal 31 Mei 2023, berdasarkan Akta Notaris Nomor 95 tanggal 29 Mei 2023.</p>
        <p>Dalam penyelenggaraan kesejahteraan sosial, YGBY tercatat sebagai Lembaga Kesejahteraan Sosial (LKS) di Dinas Sosial Kota Sukabumi dan memiliki sertifikat akreditasi dari Kementerian Sosial Republik Indonesia. Untuk pengelolaan zakat, infak, dan sedekah, YGBY telah ditetapkan sebagai Unit Pengumpul Zakat (UPZ) oleh BAZNAS Kota Sukabumi sehingga penghimpunan dan penyaluran dana berjalan sesuai ketentuan perundang-undangan.</p>
        <p>Keterbukaan adalah bagian dari amanah: setiap donasi yang Anda titipkan kami kelola dengan jujur, tertib, dan kami laporkan secara berkala. Donatur maupun mitra yang memerlukan verifikasi dokumen dapat menghubungi sekretariat yayasan.</p>
    </div>

    <ol class="relative mx-auto mt-12 max-w-3xl space-y-4">
        @foreach($docs as $k => $d)
            <li class="flex gap-4 rounded-2xl bg-white p-5 ring-1 ring-brand-100 md:p-6">
                <span class="grid h-11 w-11 shrink-0 place-items-center rounded-full bg-leaf-100 text-leaf-600"><x-icon name="{{ $d['icon'] }}" class="h-5 w-5"/></span>
                <div class="min-w-0">
                    <p class="text-xs font-semibold uppercase tracking-wider text-brand-500">{{ $d['instansi'] }} · {{ $d['tanggal'] }}</p>
                    <h3 class="mt-1 font-display text-lg leading-snug text-brand-900">{{ $d['judul'] }}</h3>
                    @if($d['nomor'])<p class="mt-1 break-words text-sm font-semibold text-brand-700">Nomor {{ $d['nomor'] }}</p>@endif
                    <p class="mt-2 text-sm leading-relaxed text-brand-700">{{ $d['ket'] }}</p>
                </div>
            </li>
        @endforeach
    </ol>
</section>
