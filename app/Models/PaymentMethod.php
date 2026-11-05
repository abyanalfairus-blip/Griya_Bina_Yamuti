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

    /** Logo bank (public/images/pembayaran) dikenali dari nama metode; null jika tidak ada. */
    public function getLogoAttribute(): ?string
    {
        $name = strtolower($this->name);
        $map = ['qris' => 'qris-logo', 'bsi' => 'bsi', 'syariah indonesia' => 'bsi', 'bri' => 'bri', 'rakyat indonesia' => 'bri', 'mandiri' => 'mandiri'];
        foreach ($map as $needle => $file) {
            if (str_contains($name, $needle)) {
                $path = 'images/pembayaran/' . $file . '.png';
                return file_exists(public_path($path)) ? $path : null;
            }
        }
        return null;
    }
}
