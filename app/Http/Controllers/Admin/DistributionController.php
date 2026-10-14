<?php

namespace App\Http\Controllers\Admin;

use App\Models\Distribution;
use App\Models\Program;

class DistributionController extends ResourceController
{
    protected string $model = Distribution::class;
    protected string $route = 'distributions';
    protected string $title = 'Laporan Penyaluran';
    protected string $singular = 'laporan penyaluran';
    protected string $orderBy = 'distributed_at';

    protected function columns(): array
    {
        return [
            ['image', 'Foto', 'image'],
            ['title', 'Kegiatan', 'text'],
            ['amount', 'Dana tersalur', 'money'],
            ['distributed_at', 'Tanggal', 'date'],
        ];
    }

    protected function fields(): array
    {
        return [
            'title' => ['Judul kegiatan penyaluran', 'text', 'required|string|max:200'],
            'program_id' => ['Program terkait', 'select', 'nullable|exists:programs,id', ['' => '(Tidak terkait program tertentu)'] + Program::orderBy('title')->pluck('title', 'id')->all()],
            'amount' => ['Dana tersalur (Rp)', 'number', 'required|integer|min:0'],
            'distributed_at' => ['Tanggal penyaluran', 'date', 'required|date'],
            'description' => ['Uraian', 'textarea', 'nullable|string|max:2000'],
            'image' => ['Foto dokumentasi', 'image', 'nullable|image|max:3072'],
        ];
    }
}
