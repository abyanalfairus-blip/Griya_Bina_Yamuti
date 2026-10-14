<?php

namespace App\Http\Controllers\Admin;

use App\Models\PaymentMethod;

class PaymentMethodController extends ResourceController
{
    protected string $model = PaymentMethod::class;
    protected string $route = 'payment-methods';
    protected string $title = 'Metode Pembayaran';
    protected string $singular = 'metode pembayaran';
    protected string $orderBy = 'sort_order';
    protected string $orderDir = 'asc';

    protected function columns(): array
    {
        return [
            ['name', 'Nama', 'text'],
            ['type', 'Jenis', 'badge'],
            ['account_number', 'No. rekening', 'text'],
            ['account_name', 'Atas nama', 'text'],
            ['is_active', 'Aktif', 'bool'],
        ];
    }

    protected function fields(): array
    {
        return [
            'name' => ['Nama bank / metode', 'text', 'required|string|max:100', null, 'Contoh: Bank Syariah Indonesia, QRIS Yayasan.'],
            'type' => ['Jenis', 'select', 'required|in:bank,qris,ewallet', PaymentMethod::TYPES],
            'account_number' => ['Nomor rekening / akun', 'text', 'nullable|string|max:60'],
            'account_name' => ['Atas nama', 'text', 'nullable|string|max:100'],
            'qr_image' => ['Gambar kode QR', 'image', 'nullable|image|max:2048'],
            'instructions' => ['Catatan untuk donatur', 'textarea', 'nullable|string|max:500'],
            'sort_order' => ['Urutan tampil', 'number', 'nullable|integer|min:0'],
            'is_active' => ['Aktif', 'checkbox', 'boolean'],
        ];
    }
}
