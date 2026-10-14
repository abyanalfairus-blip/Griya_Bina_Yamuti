<?php

namespace App\Http\Controllers\Admin;

use App\Models\Gallery;

class GalleryController extends ResourceController
{
    protected string $model = Gallery::class;
    protected string $route = 'galleries';
    protected string $title = 'Galeri Foto';
    protected string $singular = 'foto';

    protected function columns(): array
    {
        return [['image', 'Foto', 'image'], ['title', 'Judul', 'text'], ['caption', 'Keterangan', 'text']];
    }

    protected function fields(): array
    {
        return [
            'title' => ['Judul foto', 'text', 'required|string|max:150'],
            'image' => ['Berkas foto', 'image', (request()->isMethod('post') ? 'required' : 'nullable') . '|image|max:4096', null, 'Wajib diisi saat menambah foto baru.'],
            'caption' => ['Keterangan', 'text', 'nullable|string|max:255'],
        ];
    }
}
