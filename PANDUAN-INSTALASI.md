# Website Yayasan Griya Bina Yamuti (Laravel 12)

Paket ini berisi **kode aplikasi** (model, controller, view, migrasi, seeder) yang ditumpangkan ke
proyek Laravel 12 baru. Cara ini dipilih agar Anda mendapat struktur resmi Laravel terbaru dari Composer.

## Kebutuhan
- PHP 8.2 atau lebih baru (ekstensi: mbstring, xml, pdo_mysql / pdo_sqlite, fileinfo, gd)
- Composer 2
- MySQL/MariaDB (atau SQLite bawaan Laravel untuk percobaan)
- Tidak perlu Node.js: Tailwind CSS dan Alpine.js dimuat dari CDN.

## Langkah instalasi

1. Buat proyek Laravel 12 baru:
   ```bash
   composer create-project laravel/laravel:^12.0 griyabinayamuti
   ```
2. Salin **seluruh isi folder paket ini** (`app`, `database`, `resources`, `routes`) ke dalam folder
   `griyabinayamuti`, pilih **timpa** jika ditanya. File bawaan yang ikut tertimpa:
   `app/Models/User.php`, `app/Providers/AppServiceProvider.php`, `database/seeders/DatabaseSeeder.php`,
   `routes/web.php`.
3. Atur database di `.env` (contoh MySQL):
   ```env
   APP_NAME="Griya Bina Yamuti"
   APP_URL=http://localhost:8000
   APP_LOCALE=id

   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=griyabinayamuti
   DB_USERNAME=root
   DB_PASSWORD=

   # Akun admin awal (opsional, dibaca oleh seeder)
   ADMIN_EMAIL=admin@griyabinayamuti.or.id
   ADMIN_PASSWORD=isi-kata-sandi-kuat
   ```
4. Jalankan migrasi, isi data awal, dan tautkan penyimpanan unggahan:
   ```bash
   php artisan migrate --seed
   php artisan storage:link
   php artisan serve
   ```
5. Buka `http://localhost:8000` (situs) dan `http://localhost:8000/admin` (panel admin).

Untuk melihat tampilan dengan donatur contoh (hanya untuk demo, **jangan di produksi**):
```bash
php artisan db:seed --class=DemoDonationSeeder
```

## Yang wajib dilengkapi setelah instalasi
1. **Ganti kata sandi admin** (atau atur `ADMIN_PASSWORD` sebelum seeding).
2. **Menu Metode Pembayaran**: ganti data contoh dengan nomor rekening / QRIS resmi yayasan.
3. **Menu Pengaturan situs**: periksa alamat, WhatsApp, tautan media sosial, visi dan misi,
   harga emas per gram, dan nominal zakat fitrah (dipakai kalkulator zakat).
4. **Menu Susunan Pengurus, Program, Galeri**: ganti contoh dengan data dan foto asli.
5. Target dana pada program contoh hanyalah angka contoh. Sesuaikan dengan kebutuhan sebenarnya.

## Memperbarui proyek yang sudah berjalan (fitur Laporan dokumen)
Jika Anda sudah memasang versi sebelumnya, salin file baru/berubah dari zip (timpa), lalu jalankan:
```bash
php artisan migrate
php artisan optimize:clear
```
Migrasi `create_reports_table` menambah tabel `reports`. Isi laporan lewat **Admin > Laporan (dokumen)**:
pilih jenis (Bulanan / Tahunan / Keuangan), periode, lalu unggah PDF atau tempel tautan
(flipbook, Google Drive, dll.). Halaman publik: `/laporan`.

### Rekap laporan otomatis
Tab **Bulanan** dan **Tahunan** di `/laporan` otomatis memuat rekap dari data donasi berstatus *Diterima* dan
data *Laporan penyaluran*, tanpa input tambahan. Setiap rekap punya halaman detail
(`/laporan/bulanan/2026/8`, `/laporan/tahunan/2026`) dengan tombol **Cetak / simpan PDF**.
Dokumen PDF atau flipbook resmi tetap bisa diunggah lewat menu *Laporan (dokumen)* dan akan tampil di halaman yang sama.

## Landing page beranda (zakat, infak, sedekah, wakaf)
Beranda (`/`) kini berupa landing page: hero dengan foto kegiatan, **panel berbagi** (tab Zakat, Infak, Sedekah, Wakaf
dengan pilihan nominal, tersambung ke form donasi), tiga program unggulan (penyaluran sepatu, rumah belajar,
santunan anak yatim), program lainnya, tentang, cara berdonasi, kabar, donatur terbaru, dan tautan laporan.

Foto bawaan ada di `public/images/program/` (sepatu.jpg, rumah-belajar.jpg, santunan-yatim.jpg). Agar tiga program
unggulan muncul di database (dengan progress dana dan halaman detail), jalankan sekali:
```bash
php artisan db:seed --class=LandingProgramSeeder
```
Aman dijalankan ulang dan tidak menimpa suntingan admin. Teks, target dana, dan foto program bisa diubah di
**Admin > Program** (slug: `penyaluran-sepatu`, `rumah-belajar`, `santunan-anak-yatim`; jangan diubah agar tetap
tampil di landing page).

## Fitur
**Publik:** beranda dengan donasi cepat, daftar dan detail program (progress dana, donatur, penyaluran),
form donasi dengan kode unik, halaman instruksi bayar + unggah bukti transfer, kalkulator zakat
(penghasilan, maal, fitrah), kabar dan literasi (kategori, pencarian), laporan penyaluran,
galeri dengan lightbox, tentang kami (visi, misi, pengurus), kontak dengan pesan masuk, tombol WhatsApp.

**Admin (`/admin`):** dasbor ringkasan, verifikasi donasi (terima, tolak, catatan, hitung ulang dana
program otomatis), CRUD program, kabar, laporan penyaluran, metode pembayaran, galeri, pengurus,
kotak pesan, dan pengaturan situs. Isi program dan tulisan memakai format Markdown.

## Alur donasi
`Form donasi` → status **Menunggu pembayaran** → donatur transfer + unggah bukti → **Menunggu verifikasi**
→ admin memeriksa → **Diterima** (dana program bertambah) atau **Ditolak** (dengan catatan).

## Catatan produksi
- Set `APP_ENV=production`, `APP_DEBUG=false`, dan jalankan `php artisan config:cache route:cache view:cache`.
- Tailwind dimuat dari CDN agar mudah dijalankan. Untuk performa terbaik di produksi, pindahkan ke
  build Vite/Tailwind bawaan Laravel.
- Pembayaran saat ini manual (transfer + bukti). Tahap lanjutan yang disarankan: integrasi Midtrans
  atau Xendit dan notifikasi WhatsApp otomatis.
- Berkas unggahan disimpan di `storage/app/public` (diakses lewat `public/storage`).
- Migrasi konten dari Blogger: salin judul dan isi artikel lama ke menu Kabar & literasi (format Markdown).

## Struktur singkat
```
app/Http/Controllers          Controller publik
app/Http/Controllers/Admin    Controller admin (ResourceController = CRUD generik)
app/Http/Middleware           EnsureAdmin
app/Models                    Program, Donation, Post, Setting, dll.
app/Support/helpers.php       setting() dan rupiah()
database/migrations           Semua tabel
database/seeders              Admin + data awal, DemoDonationSeeder
resources/views               Layout, halaman publik, panel admin
routes/web.php                Semua rute
```

## Slider foto dan halaman Program

Tidak ada migrasi baru. Cukup timpa file lalu jalankan `php artisan optimize:clear`.

- **Slider foto** ada di hero beranda (`resources/views/partials/hero-slider.blade.php`). Otomatis berganti tiap 5 detik, berhenti saat disentuh atau disorot kursor, bisa digeser di ponsel, dan bisa dikendalikan keyboard (panah kiri/kanan).
- **Semua teks dan foto program** ada di satu file: `app/Support/Pillars.php`.
  - `slides()` mengatur foto slider dan keterangannya.
  - `groups()` mengatur 12 program (A. Enam program utama, B. Gerakan unggulan, C. Wakaf, Penguatan), lengkap dengan tagline, deskripsi, dan program turunan.
  - Untuk memasang foto pada program yang belum punya, isi `'img' => 'images/kegiatan/nama-file.jpg'`.
- Foto kegiatan ada di `public/images/kegiatan/` dan `public/images/program/`.
- Halaman lengkap: `/program-kami` (menu "Program" di navigasi). Daftar donasi berjalan tetap di `/program`.
- Bina Kewiraan dan Bina Kolaborasi baru berisi judul dan fokus, uraiannya menunggu teks dari yayasan.

## Tema biru-hijau, menu berikon, dan akun donatur

**Wajib setelah menimpa file:** ada migrasi baru (kolom `user_id` pada tabel donasi), jadi jalankan:

```
php artisan migrate
php artisan optimize:clear
```

Teman satu tim cukup `git pull`, lalu menjalankan dua perintah yang sama.

- **Warna**: token `brand` (biru) dan `leaf` (hijau) didefinisikan di `resources/views/layouts/app.blade.php` (dan versi admin di `admin/layout.blade.php`). Untuk menyesuaikan dengan logo asli, cukup ubah nilai heksadesimalnya di dua tempat itu.
- **Logo**: file logo ada di `public/images/logo.png` (latar transparan, resolusi tinggi) dan ikon tab di `public/favicon.png`. Ganti file itu untuk memperbarui logo; tampil otomatis di menu dan footer.
- **Ikon**: semua ikon ada di `resources/views/components/icon.blade.php`. Pakai dengan `<x-icon name="home" />`.
- **Akun donatur**: `/daftar`, `/masuk`, `/akun`. Donasi yang dikirim saat sudah masuk tercatat di riwayat akun. Akun yang dibuat dari halaman daftar selalu bukan admin. Admin tetap masuk lewat `/masuk` atau `/admin/login`, lalu diarahkan ke panel admin.
- **Program Utama** di beranda (tepat di bawah hero): pilih ikon untuk melihat program turunan. Isinya dari `app/Support/Pillars.php`.

## Program yang bisa dikelola dari panel admin

Ada migrasi baru. Setelah menimpa file, jalankan:

```
php artisan migrate
php artisan optimize:clear
```

Migrasi ini otomatis mengisi program awal (tanpa Bina Kewiraan), jadi beranda langsung terisi.

Menu panel admin:

| Menu | Mengatur |
|---|---|
| **Program yayasan** | Program utama, gerakan unggulan, wakaf, dan penguatan: nama, ikon, tagline, deskripsi, daftar program turunan, foto, urutan, tampil/sembunyi. Bisa tambah, ubah, dan hapus. |
| **Slider foto** | Foto slider di beranda: foto, keterangan, program tujuan saat diklik, urutan. Bisa tambah, ubah, dan hapus. |
| **Program donasi** | Program galang dana dengan target (tiga program unggulan di beranda juga dari sini). |
| **Pengaturan situs** | Bagian "Teks Halaman Program": judul deretan topik dan dua paragraf pengantar. |

Catatan: file `app/Support/Pillars.php` kini hanya membaca data dari database. Isi awalnya ada di `database/seeders/ProgramItemSeeder.php`.

## Foto unggahan tidak tampil?

Foto yang diunggah lewat panel admin disimpan di `storage/app/public`. Agar bisa dibuka dari browser, jalankan sekali:

```
php artisan storage:link
```

Sebagai cadangan, proyek ini punya rute `/storage/...` yang melayani foto walau perintah itu belum dijalankan, dan alamat gambar selalu mengikuti alamat yang sedang dibuka (tidak bergantung pada `APP_URL`).

## Animasi saat scroll

Konten tampil bertahap ketika masuk layar (muncul naik/geser dengan jeda berurutan), angka statistik di beranda menghitung naik, dan peta di footer baru dimuat saat mendekati layar. Semua diatur oleh `public/js/reveal.js` dan CSS kecil di `layouts/app.blade.php`; tidak ada yang perlu ditandai manual.

- Pengguna yang mengaktifkan "kurangi gerakan" di sistemnya langsung melihat semua konten tanpa animasi.
- Untuk mengecualikan suatu bagian dari animasi, beri atribut `data-no-reveal` pada elemennya.
- Untuk menganimasikan satu blok utuh (tidak dipecah per bagian), beri atribut `data-reveal-whole`.

## Foto hero dengan gradasi
Foto slider kini memenuhi sisi kanan hero dan memudar halus ke warna latar (tanpa kotak). File yang berubah:
`resources/views/partials/hero-slider.blade.php`, `resources/views/home.blade.php`, `resources/views/layouts/app.blade.php`.
Setelah menimpa file: `php artisan optimize:clear`, lalu Ctrl+F5.

Hero juga memiliki hiasan (bintang segi delapan, bulan sabit, titik, lencana ikon melayang) di `home.blade.php`; animasi CSS di `layouts/app.blade.php`.

## Legalitas dan perizinan (halaman Tentang Kami)
Lima dokumen (SK Kemenkumham, lampiran akta, penetapan LKS Dinsos, akreditasi Kemensos, SK UPZ BAZNAS) tampil di halaman **Dokumen** (`/legalitas`, menu "Dokumen") lewat `resources/views/partials/legalitas.blade.php`. Pratinjau di `public/images/legalitas/`, PDF di `public/berkas/`. Untuk menambah dokumen, tambahkan satu baris pada array `$docs` di partial itu dan taruh berkasnya di dua folder tersebut. Perhatikan masa berlaku akreditasi (sampai 5 Juli 2026) dan perbarui bila sudah ada sertifikat baru.

Catatan: folder PDF bernama `public/berkas` (bukan `public/dokumen`) agar tidak bentrok dengan nama rute; folder lama `public/dokumen` boleh dihapus. Folder yang namanya sama dengan rute membuat server menampilkan "Not Found". Jika sebelumnya sudah menyalin folder `public/dokumen`, hapus folder itu.
