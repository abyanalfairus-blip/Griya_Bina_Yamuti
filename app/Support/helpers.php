<?php

use App\Models\Setting;

if (! function_exists('setting')) {
    /** Ambil pengaturan situs (dengan nilai bawaan jika kosong). */
    function setting(string $key, mixed $default = null): mixed
    {
        return Setting::get($key, $default);
    }
}

if (! function_exists('rupiah')) {
    /** Format angka menjadi "Rp 1.250.000". */
    function rupiah(int|float|null $n): string
    {
        return 'Rp ' . number_format((float) ($n ?? 0), 0, ',', '.');
    }
}
