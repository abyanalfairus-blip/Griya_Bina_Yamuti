<?php

namespace App\Http\Controllers\Admin;

use App\Models\ProgramItem;
use App\Models\Slide;

class SlideController extends ResourceController
{
    protected string $model = Slide::class;
    protected string $route = 'slides';
    protected string $title = 'Slider Foto Beranda';
    protected string $singular = 'foto slider';
    protected string $orderBy = 'sort_order';
    protected string $orderDir = 'asc';

    protected function columns(): array
    {
        return [
            ['image', 'Foto', 'image'],
            ['caption', 'Keterangan', 'text'],
            ['program_slug', 'Tautan ke program', 'text'],
            ['sort_order', 'Urutan', 'text'],
            ['is_active', 'Tampil', 'bool'],
        ];
    }

    protected function fields(): array
    {
        $programs = ['' => '(tanpa tautan, ke halaman Program)'] + ProgramItem::orderBy('sort_order')->pluck('title', 'slug')->all();

        return [
            'image' => ['Foto', 'image', 'nullable|image|max:4096', null, 'Wajib saat menambah. Foto landscape paling pas; foto potret akan terpotong di bagian tepi.'],
            'caption' => ['Keterangan di bawah foto', 'text', 'required|string|max:150', null, 'Contoh: Tahsin dan murojaah · Bina Taqwa.'],
            'alt' => ['Deskripsi foto (untuk pembaca layar)', 'text', 'nullable|string|max:200'],
            'program_slug' => ['Klik foto menuju program', 'select', 'nullable|string|max:150', $programs],
            'image_pos' => ['Bagian foto yang ditonjolkan', 'select', 'nullable|string|max:40', [
                'center' => 'Tengah', 'center 15%' => 'Atas', 'center 35%' => 'Agak atas', 'center 65%' => 'Agak bawah', 'center 85%' => 'Bawah',
            ]],
            'sort_order' => ['Urutan tampil', 'number', 'nullable|integer|min:0', null, 'Angka kecil tampil lebih dulu.'],
            'is_active' => ['Tampilkan di slider', 'checkbox', 'boolean'],
        ];
    }

    /** Foto wajib ada saat menambah slide baru. */
    public function store(\Illuminate\Http\Request $request)
    {
        $request->validate(['image' => 'required|image|max:4096'], ['image.required' => 'Pilih foto untuk slide ini.']);
        return parent::store($request);
    }
}
