<?php

namespace Database\Seeders;

use App\Models\Donation;
use App\Models\PaymentMethod;
use App\Models\Program;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

/**
 * DATA CONTOH untuk demo tampilan. Jangan dijalankan di website produksi.
 * php artisan db:seed --class=DemoDonationSeeder
 */
class DemoDonationSeeder extends Seeder
{
    public function run(): void
    {
        $method = PaymentMethod::first();
        $names = ['Siti Aisyah', 'Budi Santoso', 'Rahmat Hidayat', 'Nurul Fadilah', 'Dedi Kurniawan', 'Ani Rahayu'];

        foreach (Program::all() as $program) {
            foreach (range(1, 4) as $i) {
                Donation::create([
                    'invoice' => 'DEMO' . strtoupper(Str::random(8)),
                    'program_id' => $program->id,
                    'type' => 'donasi',
                    'donor_name' => $names[array_rand($names)],
                    'is_anonymous' => $i % 3 === 0,
                    'amount' => [50000, 100000, 250000, 500000][array_rand([0, 1, 2, 3])],
                    'unique_code' => random_int(11, 499),
                    'payment_method_id' => $method?->id,
                    'status' => 'paid',
                    'message' => $i === 1 ? 'Semoga menjadi amal jariyah dan bermanfaat bagi adik-adik.' : null,
                    'paid_at' => now()->subDays(random_int(0, 40)),
                ]);
            }
            $program->recalculate();
        }
    }
}
