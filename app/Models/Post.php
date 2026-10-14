<?php

namespace App\Models;

use App\Models\Concerns\HasUniqueSlug;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasUniqueSlug;

    protected $guarded = [];

    protected $casts = [
        'is_published' => 'boolean',
        'published_at' => 'datetime',
    ];

    public const CATEGORIES = [
        'kegiatan' => 'Kegiatan',
        'berita' => 'Berita',
        'literasi' => 'Literasi',
        'dongeng' => 'Dongeng',
        'kajian' => 'Kajian',
    ];

    public function scopePublished($q)
    {
        return $q->where('is_published', true)->where(fn ($w) => $w->whereNull('published_at')->orWhere('published_at', '<=', now()));
    }

    public function getCategoryLabelAttribute(): string
    {
        return self::CATEGORIES[$this->category] ?? ucfirst($this->category);
    }

    public function getReadMinutesAttribute(): int
    {
        return max(1, (int) ceil(str_word_count(strip_tags($this->body)) / 200));
    }
}
