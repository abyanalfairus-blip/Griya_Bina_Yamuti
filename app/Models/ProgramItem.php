<?php

namespace App\Models;

use App\Models\Concerns\HasUniqueSlug;
use Illuminate\Database\Eloquent\Model;

/** Program yayasan yang tampil di beranda dan halaman Program (bukan galang dana). */
class ProgramItem extends Model
{
    use HasUniqueSlug;

    protected $guarded = [];

    protected $casts = ['is_active' => 'boolean'];

    public const GROUPS = [
        'utama' => 'Program utama',
        'gerakan' => 'Gerakan unggulan kebaikan',
        'wakaf' => 'Wakaf dan pengembangan aset produktif',
        'penguatan' => 'Penguatan',
    ];

    public const ICONS = [
        'book' => 'Buku (pendidikan)',
        'quran' => "Al-Qur'an",
        'hands' => 'Hati dan tangan (sosial)',
        'pulse' => 'Hati sehat (kesehatan)',
        'store' => 'Toko (usaha)',
        'lifebuoy' => 'Pelampung (kemanusiaan)',
        'sunrise' => 'Matahari terbit (subuh)',
        'gift' => 'Hadiah (berbagi)',
        'building' => 'Bangunan (sarana)',
        'sprout' => 'Tunas (wakaf produktif)',
        'shield' => 'Perisai (karakter)',
        'handshake' => 'Jabat tangan (kemitraan)',
        'heart' => 'Hati',
        'apps' => 'Lainnya',
    ];

    public function scopeActive($q)
    {
        return $q->where('is_active', true);
    }

    public function getGroupLabelAttribute(): string
    {
        return self::GROUPS[$this->group] ?? ucfirst($this->group);
    }

    /** Daftar butir program turunan (satu baris = satu butir). */
    public function getListAttribute(): array
    {
        return array_values(array_filter(array_map('trim', preg_split('/\R/', (string) $this->list_items))));
    }
}
