<?php

namespace App\Http\Controllers\Admin;

use App\Models\TeamMember;

class TeamMemberController extends ResourceController
{
    protected string $model = TeamMember::class;
    protected string $route = 'team';
    protected string $title = 'Susunan Pengurus';
    protected string $singular = 'pengurus';
    protected string $orderBy = 'sort_order';
    protected string $orderDir = 'asc';

    protected function columns(): array
    {
        return [['photo', 'Foto', 'image'], ['name', 'Nama', 'text'], ['position', 'Jabatan', 'text'], ['sort_order', 'Urutan', 'text']];
    }

    protected function fields(): array
    {
        return [
            'name' => ['Nama lengkap', 'text', 'required|string|max:120'],
            'position' => ['Jabatan', 'text', 'required|string|max:120'],
            'photo' => ['Foto', 'image', 'nullable|image|max:2048'],
            'sort_order' => ['Urutan tampil', 'number', 'nullable|integer|min:0', null, 'Angka kecil tampil lebih dulu.'],
        ];
    }
}
