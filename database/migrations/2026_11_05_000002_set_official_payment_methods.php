<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Rekening resmi Yayasan Griya Bina Yamuti: QRIS, BSI, BRI, dan Mandiri.
 * - Metode contoh bawaan ("ISI-DI-PANEL-ADMIN") dinonaktifkan.
 * - Metode yang sudah ada (nomor sama) tidak dibuat dua kali.
 */
return new class extends Migration
{
    private array $methods = [
        ['QRIS Yayasan Griya Bina Yamuti', 'qris', null, 'images/pembayaran/qris.jpg',
            'Pindai kode QRIS dengan aplikasi m-banking atau dompet digital (GoPay, OVO, DANA, ShopeePay, LinkAja). Masukkan nominal persis sesuai total donasi, lalu unggah bukti pembayaran.'],
        ['Bank Syariah Indonesia (BSI)', 'bank', '2217081947', null,
            'Transfer sesuai total donasi (termasuk kode unik) agar mudah diverifikasi, lalu unggah bukti transfer.'],
        ['Bank Rakyat Indonesia (BRI)', 'bank', '4411010976531', null,
            'Transfer sesuai total donasi (termasuk kode unik) agar mudah diverifikasi, lalu unggah bukti transfer.'],
        ['Bank Mandiri', 'bank', '1820017906470', null,
            'Transfer sesuai total donasi (termasuk kode unik) agar mudah diverifikasi, lalu unggah bukti transfer.'],
    ];

    public function up(): void
    {
        if (! Schema::hasTable('payment_methods')) {
            return;
        }
        $now = now();

        DB::table('payment_methods')->where('account_number', 'ISI-DI-PANEL-ADMIN')->update(['is_active' => false, 'updated_at' => $now]);

        foreach ($this->methods as $i => [$name, $type, $number, $qr, $info]) {
            $exists = $number
                ? DB::table('payment_methods')->where('account_number', $number)->exists()
                : DB::table('payment_methods')->where('type', 'qris')->where('qr_image', $qr)->exists();
            if ($exists) {
                continue;
            }
            DB::table('payment_methods')->insert([
                'name' => $name, 'type' => $type, 'account_number' => $number,
                'account_name' => 'Yayasan Griya Bina Yamuti', 'qr_image' => $qr, 'instructions' => $info,
                'is_active' => true, 'sort_order' => $i + 1, 'created_at' => $now, 'updated_at' => $now,
            ]);
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('payment_methods')) {
            DB::table('payment_methods')->whereIn('account_number', ['2217081947', '4411010976531', '1820017906470'])->delete();
            DB::table('payment_methods')->where('qr_image', 'images/pembayaran/qris.jpg')->delete();
        }
    }
};
