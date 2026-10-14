<?php

namespace App\Http\Controllers\Admin;

use App\Models\Program;

class ProgramController extends ResourceController
{
    protected string $model = Program::class;
    protected string $route = 'programs';
    protected string $title = 'Program Donasi';
    protected string $singular = 'program';

    protected function columns(): array
    {
        return [
            ['image', 'Gambar', 'image'],
            ['title', 'Judul', 'text'],
            ['category_label', 'Kategori', 'badge'],
            ['collected', 'Terkumpul', 'money'],
            ['target', 'Target', 'money'],
            ['is_active', 'Aktif', 'bool'],
        ];
    }

    protected function fields(): array
    {
        return [
            'title' => ['Judul program', 'text', 'required|string|max:200'],
            'category' => ['Kategori', 'select', 'required|in:' . implode(',', array_keys(Program::CATEGORIES)), Program::CATEGORIES],
            'excerpt' => ['Ringkasan singkat', 'textarea', 'nullable|string|max:300', null, 'Tampil di kartu program (maks. 300 karakter).'],
            'description' => ['Deskripsi lengkap', 'textarea-lg', 'nullable|string', null, 'Mendukung format Markdown: **tebal**, daftar dengan tanda -, judul dengan ##.'],
            'image' => ['Gambar sampul', 'image', 'nullable|image|max:3072'],
            'target' => ['Target dana (Rp)', 'number', 'required|integer|min:0'],
            'deadline' => ['Batas waktu', 'date', 'nullable|date', null, 'Kosongkan jika program tidak dibatasi waktu.'],
            'is_featured' => ['Jadikan program unggulan', 'checkbox', 'boolean'],
            'is_active' => ['Tampilkan di website', 'checkbox', 'boolean'],
        ];
    }
}
