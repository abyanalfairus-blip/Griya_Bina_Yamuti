<?php

namespace Database\Seeders;

use App\Models\Program;
use Illuminate\Database\Seeder;

/**
 * Tiga program unggulan di landing page (sepatu, rumah belajar, santunan yatim).
 * firstOrCreate: aman dijalankan ulang dan tidak menimpa suntingan admin.
 *
 *   php artisan db:seed --class=LandingProgramSeeder
 */
class LandingProgramSeeder extends Seeder
{
    public function run(): void
    {
        $landing = [
            [
                'slug' => 'penyaluran-sepatu',
                'title' => 'Penyaluran Sepatu untuk Anak Binaan',
                'category' => 'pendidikan',
                'excerpt' => 'Sepatu yang layak membuat anak berangkat sekolah dengan percaya diri. Bantu salurkan sepatu baru untuk anak yatim dan dhuafa binaan.',
                'description' => "Sepatu yang layak adalah bekal kecil yang berarti besar bagi anak yang setiap hari berjalan ke sekolah.\n\nMelalui program ini, donasi Anda dipakai untuk membeli sepatu baru dan menyalurkannya langsung kepada anak yatim dan dhuafa binaan Griya Bina Yamuti.\n\n## Donasi Anda dipakai untuk\n\n- Pembelian sepatu sekolah baru\n- Penyaluran langsung kepada anak binaan\n- Dokumentasi dan laporan penyaluran",
                'image' => 'images/program/sepatu.jpg',
                'target' => 10000000,
            ],
            [
                'slug' => 'rumah-belajar',
                'title' => 'Rumah Belajar Yamuti',
                'category' => 'pendidikan',
                'excerpt' => 'Tempat anak yatim dan dhuafa belajar, mengaji, dan tumbuh bersama lewat pembinaan akhlak, tahfidz, dan kegiatan Jumat Berkah.',
                'description' => "Rumah Belajar adalah ruang bagi anak-anak binaan untuk belajar, mengaji, dan bertumbuh bersama.\n\nKegiatannya mencakup pengajian rutin, pembinaan akhlak dan tahfidz, serta kegiatan kreatif yang membentuk pribadi **S.E.M.A.R.T**: Sidiq, Empati, Mandiri, Amanah, Religius, Terampil.\n\n## Donasi Anda dipakai untuk\n\n- Perlengkapan belajar dan mengaji\n- Konsumsi dan kebutuhan kegiatan Jumat Berkah\n- Operasional ruang belajar",
                'image' => 'images/program/rumah-belajar.jpg',
                'target' => 15000000,
            ],
            [
                'slug' => 'santunan-anak-yatim',
                'title' => 'Santunan Anak Yatim',
                'category' => 'yatim',
                'excerpt' => 'Santunan langsung berupa bingkisan dan bantuan tunai agar anak yatim binaan ikut merasakan kebahagiaan seperti anak-anak lainnya.',
                'description' => "Anak yatim berhak merasakan keceriaan dan kebahagiaan seperti anak-anak lainnya.\n\nSantunan ini disalurkan langsung kepada anak-anak yatim binaan, termasuk melalui kegiatan **GEREBEK** (Gerakan Edukasi Berbagi Kasih).\n\n## Donasi Anda dipakai untuk\n\n- Bingkisan dan bantuan tunai bagi anak yatim\n- Kegiatan kebersamaan dan edukasi\n- Dokumentasi dan laporan penyaluran",
                'image' => 'images/program/santunan-yatim.jpg',
                'target' => 20000000,
            ],
        ];
        foreach ($landing as $p) {
            Program::firstOrCreate(['slug' => $p['slug']], $p + ['is_active' => true]);
        }
    }
}
