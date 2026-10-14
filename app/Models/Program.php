<?php

namespace App\Models;

use App\Models\Concerns\HasUniqueSlug;
use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    use HasUniqueSlug;

    protected $guarded = [];

    protected $casts = [
        'deadline' => 'date',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
    ];

    public const CATEGORIES = [
        'yatim' => 'Santunan Yatim',
        'pendidikan' => 'Pendidikan',
        'asrama' => 'Asrama & Pembangunan',
        'wakaf' => 'Wakaf Produktif',
        'kurban' => 'Kurban',
        'umum' => 'Lainnya',
    ];

    public function donations()
    {
        return $this->hasMany(Donation::class);
    }

    public function distributions()
    {
        return $this->hasMany(Distribution::class);
    }

    public function scopeActive($q)
    {
        return $q->where('is_active', true);
    }

    public function getCategoryLabelAttribute(): string
    {
        return self::CATEGORIES[$this->category] ?? ucfirst($this->category);
    }

    public function getProgressAttribute(): int
    {
        if ($this->target <= 0) {
            return 0;
        }
        return (int) min(100, floor($this->collected / $this->target * 100));
    }

    public function getDaysLeftAttribute(): ?int
    {
        if (! $this->deadline) {
            return null;
        }
        return max(0, (int) now()->startOfDay()->diffInDays($this->deadline->startOfDay(), false));
    }

    /** Hitung ulang dana terkumpul dari donasi berstatus "paid". */
    public function recalculate(): void
    {
        $this->update(['collected' => (int) $this->donations()->where('status', 'paid')->sum('amount')]);
    }
}
