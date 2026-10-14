<?php

namespace App\Http\Controllers\Admin;

use App\Models\Report;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\ValidationException;

class ReportController extends ResourceController
{
    protected string $model = Report::class;
    protected string $route = 'reports';
    protected string $title = 'Laporan';
    protected string $singular = 'laporan';
    protected string $orderBy = 'period_date';

    protected function columns(): array
    {
        return [
            ['title', 'Judul', 'text'],
            ['type_label', 'Jenis', 'text'],
            ['period_date', 'Periode', 'date'],
            ['is_published', 'Tampil', 'bool'],
        ];
    }

    protected function fields(): array
    {
        return [
            'title' => ['Judul laporan', 'text', 'required|string|max:200', null, 'Contoh: Laporan Agustus 2026'],
            'type' => ['Jenis laporan', 'select', 'required|in:' . implode(',', array_keys(Report::TYPES)), Report::TYPES],
            'period_date' => ['Periode', 'date', 'required|date', null, 'Untuk laporan bulanan, pilih tanggal mana pun pada bulan tersebut. Untuk tahunan, pilih tahunnya.'],
            'file' => ['Berkas PDF', 'file', 'nullable|file|mimes:pdf|max:20480', null, 'Opsional. Maksimal 20 MB.'],
            'url' => ['Atau tautan laporan', 'text', 'nullable|url|max:500', null, 'Opsional. Tempel tautan flipbook, Google Drive, atau dokumen online. Jika berkas PDF diisi, berkas yang dipakai.'],
            'description' => ['Keterangan singkat', 'textarea', 'nullable|string|max:300'],
            'is_published' => ['Tampilkan di website', 'checkbox', 'boolean'],
        ];
    }

    protected function beforeSave(array $data, ?Model $item): array
    {
        if (empty($data['url']) && empty($data['file'] ?? null) && empty($item?->file)) {
            throw ValidationException::withMessages(['url' => 'Isi tautan laporan atau unggah berkas PDF.']);
        }
        return $data;
    }
}
