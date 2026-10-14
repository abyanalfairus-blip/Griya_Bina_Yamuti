<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Donation extends Model
{
    protected $guarded = [];

    protected $casts = [
        'is_anonymous' => 'boolean',
        'paid_at' => 'datetime',
    ];

    public const TYPES = [
        'donasi' => 'Donasi Program',
        'zakat' => 'Zakat',
        'infak' => 'Infak',
        'sedekah' => 'Sedekah',
        'wakaf' => 'Wakaf',
    ];

    public const STATUSES = [
        'pending' => 'Menunggu pembayaran',
        'review' => 'Menunggu verifikasi',
        'paid' => 'Diterima',
        'rejected' => 'Ditolak',
    ];

    public function program()
    {
        return $this->belongsTo(Program::class);
    }

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    public function getTotalTransferAttribute(): int
    {
        return $this->amount + $this->unique_code;
    }

    public function getDisplayNameAttribute(): string
    {
        return $this->is_anonymous ? 'Hamba Allah' : $this->donor_name;
    }

    public function getStatusLabelAttribute(): string
    {
        return self::STATUSES[$this->status] ?? $this->status;
    }

    public function getTypeLabelAttribute(): string
    {
        return self::TYPES[$this->type] ?? ucfirst($this->type);
    }

    public function scopePaid($q)
    {
        return $q->where('status', 'paid');
    }
}
