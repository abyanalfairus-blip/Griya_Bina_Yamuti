<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    /** Grup => [kunci => [label, tipe]] */
    public static function groups(): array
    {
        return [
            'Identitas & Beranda' => [
                'site_name' => ['Nama yayasan', 'text'],
                'tagline' => ['Tagline', 'text'],
                'hero_title' => ['Judul utama beranda', 'text'],
                'hero_subtitle' => ['Kalimat pengantar beranda', 'textarea'],
            ],
            'Teks Halaman Program' => [
                'program_headline' => ['Judul deretan topik (tampil di atas beranda)', 'text'],
                'program_intro_1' => ['Paragraf pengantar 1', 'textarea'],
                'program_intro_2' => ['Paragraf pengantar 2', 'textarea'],
            ],
            'Tentang Kami' => [
                'about_short' => ['Ringkasan singkat (beranda)', 'textarea'],
                'about_history' => ['Profil & sejarah', 'textarea'],
                'vision' => ['Visi', 'textarea'],
                'mission' => ['Misi (satu poin per baris)', 'textarea'],
            ],
            'Kontak & Media Sosial' => [
                'address' => ['Alamat', 'textarea'],
                'phone' => ['Telepon', 'text'],
                'whatsapp' => ['Nomor WhatsApp (format 62xxxx)', 'text'],
                'email' => ['Email', 'text'],
                'instagram' => ['Tautan Instagram', 'text'],
                'facebook' => ['Tautan Facebook', 'text'],
                'youtube' => ['Tautan YouTube', 'text'],
                'maps_embed' => ['URL embed Google Maps', 'text'],
            ],
            'Kalkulator Zakat' => [
                'gold_price' => ['Harga emas per gram (Rp)', 'number'],
                'fitrah_rate' => ['Zakat fitrah per jiwa (Rp)', 'number'],
            ],
        ];
    }

    public function edit()
    {
        return view('admin.settings.edit', ['groups' => self::groups()]);
    }

    public function update(Request $request)
    {
        foreach (self::groups() as $fields) {
            foreach (array_keys($fields) as $key) {
                Setting::put($key, $request->input($key));
            }
        }

        return back()->with('success', 'Pengaturan disimpan.');
    }
}
