<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Menambahkan foto kegiatan Bina Berdaya dan Wakaf Produktif ke database yang sudah ada:
 * - foto utama program (hanya jika program belum punya foto),
 * - slide baru di hero (hanya jika foto tersebut belum ada di slider).
 * Foto yang sudah diatur admin tidak ditimpa.
 */
return new class extends Migration
{
    private array $slides = [
            ['images/kegiatan/bina-berdaya-bunda-roti.jpg', 'center 45%', 'Produksi roti Bunda Berdaya · Bina Berdaya', 'Seorang ibu binaan memperlihatkan kotak berisi roti hasil produksinya', 'bina-berdaya'],
            ['images/kegiatan/bina-berdaya-anak-roti.jpg', 'center 35%', 'Belajar membuat roti · Bina Berdaya', 'Anak binaan bersarung tangan mengolah roti di dapur produksi', 'bina-berdaya'],
            ['images/kegiatan/wakaf-produktif-tanam.jpg', 'center 60%', 'Menggarap lahan produktif · Wakaf Produktif', 'Anak-anak binaan dan pembina menanam di lahan kebun', 'wakaf-produktif'],
            ['images/kegiatan/wakaf-produktif-tanam-jagung.jpg', 'center 40%', 'Menanam benih bersama · Wakaf Produktif', 'Anak binaan membuat lubang tanam dan menabur benih', 'wakaf-produktif'],
            ['images/kegiatan/rumah-belajar-bibit-jagung.jpg', 'center 55%', 'Persiapan bibit jagung di Rumah Belajar · Wakaf Produktif', 'Anak-anak binaan berdiskusi di Rumah Belajar YGBY dengan sekarung bibit jagung', 'wakaf-produktif'],
            ['images/kegiatan/wakaf-produktif-ternak-domba.jpg', 'center 70%', 'Merawat ternak domba · Wakaf Produktif', 'Anak-anak binaan merawat domba di halaman', 'wakaf-produktif'],
            ['images/kegiatan/wakaf-produktif-gembala.jpg', 'center 55%', 'Menggembala domba bersama · Wakaf Produktif', 'Anak-anak binaan menuntun domba di jalan kebun', 'wakaf-produktif'],
    ];

    public function up(): void
    {
        if (! Schema::hasTable('program_items') || ! Schema::hasTable('slides')) {
            return;
        }

        $now = now();
        foreach ([
            'bina-berdaya' => ['images/kegiatan/bina-berdaya-bunda-roti.jpg', 'center 45%', 'Ibu binaan Bunda Berdaya dengan hasil produksi roti'],
            'wakaf-produktif' => ['images/kegiatan/wakaf-produktif-tanam.jpg', 'center 60%', 'Anak-anak binaan menanam di lahan produktif'],
        ] as $slug => [$img, $pos, $alt]) {
            DB::table('program_items')->where('slug', $slug)->whereNull('image')
                ->update(['image' => $img, 'image_pos' => $pos, 'image_alt' => $alt, 'updated_at' => $now]);
        }

        $order = (int) DB::table('slides')->max('sort_order');
        foreach ($this->slides as [$img, $pos, $caption, $alt, $program]) {
            if (DB::table('slides')->where('image', $img)->exists()) {
                continue;
            }
            DB::table('slides')->insert([
                'image' => $img, 'image_pos' => $pos, 'caption' => $caption, 'alt' => $alt,
                'program_slug' => $program, 'sort_order' => ++$order, 'is_active' => true,
                'created_at' => $now, 'updated_at' => $now,
            ]);
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('slides')) {
            DB::table('slides')->whereIn('image', array_column($this->slides, 0))->delete();
        }
    }
};
