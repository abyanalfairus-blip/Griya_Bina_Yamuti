<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/** Foto pada slider di beranda. */
class Slide extends Model
{
    protected $guarded = [];

    protected $casts = ['is_active' => 'boolean'];

    public function scopeActive($q)
    {
        return $q->where('is_active', true);
    }
}
