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

if (! function_exists('media_url')) {
    /** Alamat gambar: foto bawaan (public/images/...) atau unggahan admin (storage). */
    function media_url(?string $path): ?string
    {
        if (! $path) {
            return null;
        }
        if (str_starts_with($path, 'http')) {
            return $path;
        }
        // asset() memakai alamat yang sedang dibuka di browser, jadi gambar tetap tampil
        // walau APP_URL di .env belum sesuai (misalnya http://localhost vs 127.0.0.1:8000).
        return str_starts_with($path, 'images/')
            ? asset($path)
            : asset('storage/' . ltrim($path, '/'));
    }
}
