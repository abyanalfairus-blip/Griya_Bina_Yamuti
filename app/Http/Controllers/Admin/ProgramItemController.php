<?php

namespace App\Http\Controllers\Admin;

use App\Models\ProgramItem;
use App\Models\Donation;

class ProgramItemController extends ResourceController
{
    protected string $model = ProgramItem::class;
    protected string $route = 'program-items';
    protected string $title = 'Program Yayasan';
    protected string $singular = 'program';
    protected string $orderBy = 'sort_order';
    protected string $orderDir = 'asc';

    protected function columns(): array
    {
        return [
            ['image', 'Foto', 'image'],
            ['title', 'Nama program', 'text'],
            ['group_label', 'Kelompok', 'badge'],
            ['focus', 'Fokus', 'text'],
            ['sort_order', 'Urutan', 'text'],
            ['is_active', 'Tampil', 'bool'],
        ];
    }

    protected function fields(): array
    {
        return [
            'title' => ['Nama program', 'text', 'required|string|max:150'],
            'group' => ['Kelompok', 'select', 'required|in:' . implode(',', array_keys(ProgramItem::GROUPS)), ProgramItem::GROUPS, 'Program utama tampil sebagai ikon pilihan di beranda.'],
            'icon' => ['Ikon', 'select', 'required|in:' . implode(',', array_keys(ProgramItem::ICONS)), ProgramItem::ICONS],
            'focus' => ['Fokus (satu frasa singkat)', 'text', 'nullable|string|max:150', null, 'Contoh: Pendidikan dan masa depan anak.'],
            'tagline' => ['Tagline', 'text', 'nullable|string|max:200'],
            'description' => ['Deskripsi', 'textarea', 'nullable|string|max:1500'],
            'list_title' => ['Judul daftar', 'text', 'nullable|string|max:100', null, 'Contoh: Program turunan, Fokus dukungan, Arah pengembangan.'],
            'list_items' => ['Isi daftar', 'textarea', 'nullable|string|max:3000', null, 'Satu butir per baris.'],
            'image' => ['Foto kegiatan', 'image', 'nullable|image|max:3072', null, 'Opsional. Tanpa foto, tampil ikon program.'],
            'image_alt' => ['Keterangan foto', 'text', 'nullable|string|max:200'],
            'donation_type' => ['Jenis donasi pada tombol "Dukung"', 'select', 'required|in:' . implode(',', array_keys(Donation::TYPES)), Donation::TYPES],
            'sort_order' => ['Urutan tampil', 'number', 'nullable|integer|min:0', null, 'Angka kecil tampil lebih dulu.'],
            'is_active' => ['Tampilkan di website', 'checkbox', 'boolean'],
        ];
    }
}
