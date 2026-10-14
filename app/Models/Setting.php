<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    public $timestamps = false;
    protected $guarded = [];

    private static ?array $cache = null;

    /** Nilai bawaan agar situs tetap tampil walau tabel masih kosong. */
    public const DEFAULTS = [
        'site_name' => 'Griya Bina Yamuti',
        'tagline' => 'Mendidik dan membina generasi yatim dan dhuafa agar sejahtera dan berprestasi',
        'hero_title' => 'Masa depan anak yatim adalah tanggung jawab kita bersama',
        'hero_subtitle' => 'Setiap donasi Anda membantu anak-anak yatim dan dhuafa binaan Griya Bina Yamuti mendapat pendidikan, pembinaan akhlak, dan tempat tinggal yang layak.',
        'about_short' => 'Kami ada karena kepedulian terhadap anak-anak yatim piatu dan dhuafa. Masa depan mereka adalah hak untuk digapai, dan keceriaan tidak boleh direnggut hanya karena mereka tidak memiliki orang tua.',
        'about_history' => 'Griya Bina Yamuti adalah yayasan sosial di Kota Sukabumi yang membina anak-anak yatim, piatu, dan dhuafa melalui pembinaan akhlak dan tahfidz, beasiswa pendidikan, santunan, serta program pemberdayaan.',
        'vision' => 'Menjadi yayasan yang amanah dan peduli pada masa depan anak-anak yang lebih sejahtera dan berprestasi.',
        'mission' => "Membina akhlak dan keimanan anak yatim binaan melalui pengajian rutin dan tahfidz.\nMemberi beasiswa penunjang pendidikan setiap semester.\nMenyalurkan santunan dan program kebersamaan secara kreatif dan tepat sasaran.\nMemberdayakan keluarga dan anak binaan melalui kemitraan dan wakaf produktif.\nMengelola amanah donatur secara transparan dan akuntabel.",
        'address' => 'Jl. Benteng Kidul RT. 005 RW. 002, Kel. Dayeuhluhur, Kec. Warudoyong, Kota Sukabumi',
        'phone' => '+62 852-1638-8228',
        'whatsapp' => '6285216388228',
        'email' => '',
        'instagram' => 'https://www.instagram.com/griyabinayamuti._99/',
        'facebook' => 'https://www.facebook.com/GriyaBinaYamuti/',
        'youtube' => 'https://www.youtube.com/@GriyaBinayamuti3846',
        'maps_embed' => '',
        'gold_price' => '1500000',
        'fitrah_rate' => '45000',
    ];

    public static function all_(): array
    {
        if (self::$cache === null) {
            try {
                self::$cache = static::query()->pluck('value', 'key')->all();
            } catch (\Throwable $e) {
                self::$cache = [];
            }
        }
        return self::$cache;
    }

    public static function get(string $key, mixed $default = null): mixed
    {
        $value = self::all_()[$key] ?? null;
        if ($value !== null && $value !== '') {
            return $value;
        }
        return $default ?? (self::DEFAULTS[$key] ?? null);
    }

    public static function put(string $key, ?string $value): void
    {
        static::updateOrCreate(['key' => $key], ['value' => $value]);
        self::$cache = null;
    }
}
