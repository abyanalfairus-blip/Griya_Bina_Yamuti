<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    protected $guarded = [];
    protected $casts = ['is_active' => 'boolean'];

    public const TYPES = ['bank' => 'Transfer Bank', 'qris' => 'QRIS', 'ewallet' => 'Dompet Digital'];

    public function scopeActive($q)
    {
        return $q->where('is_active', true)->orderBy('sort_order')->orderBy('id');
    }
}
