<?php

namespace App\Models\Concerns;

use Illuminate\Support\Str;

/** Membuat slug unik dari kolom "title" saat data baru dibuat. */
trait HasUniqueSlug
{
    protected static function bootHasUniqueSlug(): void
    {
        static::creating(function ($model) {
            if (! empty($model->slug)) {
                return;
            }
            $base = Str::slug($model->title) ?: Str::random(8);
            $slug = $base;
            $i = 2;
            while (static::where('slug', $slug)->exists()) {
                $slug = $base . '-' . $i++;
            }
            $model->slug = $slug;
        });
    }
}
