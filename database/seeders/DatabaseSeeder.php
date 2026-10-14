<?php

namespace Database\Seeders;

use App\Models\PaymentMethod;
use App\Models\Post;
use App\Models\Program;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Akun admin awal, hanya dibuat jika belum ada (kata sandi yang sudah diganti tidak ditimpa).
        User::firstOrCreate(
            ['email' => env('ADMIN_EMAIL', 'admin@griyabinayamuti.or.id')],
            ['name' => 'Admin Yayasan', 'password' => env('ADMIN_PASSWORD', 'admin123'), 'is_admin' => true]
        );

        if (Program::count() === 0) {
            $programs = [
                [
                    'title' => 'Lanjutkan Pembangunan Asrama Yatim & Dhuafa',
                    'category' => 'asrama',
                    'excerpt' => 'Pembangunan asrama sempat tertunda lebih dari 2 tahun karena keterbatasan dana. Kini dilanjutkan sedikit demi sedikit.',
                    'description' => "Alhamdulillah, pembangunan ruang Asrama Yatim & Dhuafa Griya Bina Yamuti kembali dilanjutkan setelah sempat tertunda lebih dari 2 tahun karena keterbatasan dana.\n\n## Progres yang sudah berjalan\n\n- Pemasangan plafon ruang asrama\n- Penyelesaian 3 kamar mandi\n- Finishing area teras\n\n## Kebutuhan yang masih diperlukan\n\n- 6 unit kasur\n- 3 unit lemari\n- 14 dus keramik\n- 3 pail cat\n- Biaya upah tukang hingga pekerjaan selesai\n\nSekecil apa pun dukungan Anda akan menjadi bagian dari hadirnya tempat tinggal yang nyaman bagi anak-anak yatim dan dhuafa.",
                    'target' => 25000000, 'is_featured' => true,
                ],
                [
                    'title' => 'Santunan Kreatif untuk Yatim Binaan',
                    'category' => 'yatim',
                    'excerpt' => 'Santunan langsung dan kegiatan GEREBEK (Gerakan Edukasi Berbagi Kasih) untuk seluruh yatim binaan.',
                    'description' => "Santunan Kreatif adalah kegiatan karitas kepada seluruh yatim binaan Yamuti, melalui santunan langsung dan kegiatan **GEREBEK** (Gerakan Edukasi Berbagi Kasih).\n\nDonasi Anda dipakai untuk kebutuhan sehari-hari anak binaan, perlengkapan sekolah, dan kegiatan kebersamaan.",
                    'target' => 15000000,
                ],
                [
                    'title' => 'Beasiswa Bina Belajar per Semester',
                    'category' => 'pendidikan',
                    'excerpt' => 'Beasiswa penunjang pendidikan anak binaan Yamuti yang diberikan setiap semester.',
                    'description' => "Melalui program **Bina Belajar**, yayasan memberikan beasiswa penunjang pendidikan kepada anak-anak binaan setiap semester, agar mereka tetap bersekolah dan berprestasi.",
                    'target' => 20000000,
                ],
                [
                    'title' => 'Jumat Berkah: Doa dan Pembinaan Akhlak',
                    'category' => 'yatim',
                    'excerpt' => 'Kegiatan pekanan doa bersama dan pembinaan akhlak serta tahfidz anak yatim binaan di sekretariat.',
                    'description' => "Setiap pekan, anak-anak yatim binaan berkumpul di sekretariat untuk doa bersama, pembinaan akhlak, dan tahfidz bersama para pembina Yamuti.\n\nDukungan Anda membantu konsumsi, perlengkapan belajar, dan kebutuhan kegiatan.",
                    'target' => 5000000,
                ],
                [
                    'title' => 'Wakaf Produktif Griya Bina Yamuti',
                    'category' => 'wakaf',
                    'excerpt' => 'Wakaf uang, tanah, atau bangunan yang dikelola produktif agar manfaatnya berkelanjutan.',
                    'description' => "Wakaf Produktif adalah program donasi berupa uang, tanah, atau bangunan yang dikelola secara produktif. Pokok wakaf dijaga, dan hanya manfaatnya yang diambil hingga menghasilkan surplus berkelanjutan untuk kesejahteraan anak-anak binaan.",
                    'target' => 100000000,
                ],
            ];
            foreach ($programs as $p) {
                Program::create($p + ['is_active' => true]);
            }
        }

        // Tiga program unggulan landing page (aman dijalankan ulang).
        $this->call(LandingProgramSeeder::class);

        if (PaymentMethod::count() === 0) {
            PaymentMethod::create([
                'name' => 'Transfer Bank (isi nama bank resmi yayasan)',
                'type' => 'bank',
                'account_number' => 'ISI-DI-PANEL-ADMIN',
                'account_name' => 'Yayasan Griya Bina Yamuti',
                'instructions' => 'Lengkapi nomor rekening resmi di menu Metode Pembayaran pada panel admin.',
                'sort_order' => 1,
            ]);
        }

        if (Post::count() === 0) {
            Post::create([
                'title' => 'Progres Pembangunan Asrama Yatim & Dhuafa',
                'category' => 'kegiatan',
                'excerpt' => 'Pembangunan asrama kembali berjalan: plafon terpasang, tiga kamar mandi selesai, dan teras memasuki tahap akhir.',
                'body' => "Setelah sempat tertunda lebih dari dua tahun, pembangunan ruang asrama kembali dilanjutkan.\n\n## Yang sudah selesai\n\n- Pemasangan plafon\n- Tiga kamar mandi\n- Finishing area teras\n\nKami masih membutuhkan kasur, lemari, keramik, cat, dan upah tukang. Terima kasih atas dukungan para donatur.",
                'published_at' => now(),
            ]);
            Post::create([
                'title' => 'Dua Fungsi Utama Zakat Fitrah',
                'category' => 'literasi',
                'excerpt' => 'Zakat fitrah menyempurnakan ibadah puasa dan memastikan fakir miskin turut merayakan hari raya.',
                'body' => "Zakat fitrah memiliki dua fungsi utama:\n\n- **Menyucikan** orang yang berpuasa dari perbuatan sia-sia dan perkataan kotor.\n- **Memberi makan** orang miskin agar mereka turut bergembira di hari raya.\n\nTunaikan sebelum salat Id, atau sejak awal Ramadan agar penyalurannya tepat waktu.",
                'published_at' => now()->subDays(3),
            ]);
        }
    }
}
