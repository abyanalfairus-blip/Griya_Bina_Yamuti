<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Report extends Model
{
    protected $guarded = [];

    protected $casts = [
        'period_date' => 'date',
        'is_published' => 'boolean',
    ];

    public const TYPES = [
        'bulanan' => 'Laporan Bulanan',
        'tahunan' => 'Laporan Tahunan',
        'keuangan' => 'Laporan Keuangan',
    ];

    public function scopePublished($q)
    {
        return $q->where('is_published', true);
    }

    public function getTypeLabelAttribute(): string
    {
        return self::TYPES[$this->type] ?? ucfirst($this->type);
    }

    public function getPeriodLabelAttribute(): string
    {
        return $this->type === 'tahunan'
            ? $this->period_date->format('Y')
            : $this->period_date->translatedFormat('F Y');
    }

    /** Alamat untuk membuka laporan: berkas unggahan lebih diutamakan daripada tautan luar. */
    public function getLinkAttribute(): ?string
    {
        return $this->file ? Storage::url($this->file) : $this->url;
    }
}
