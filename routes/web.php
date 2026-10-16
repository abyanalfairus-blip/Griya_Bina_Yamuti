<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\Admin;
use App\Http\Controllers\Auth;
use App\Http\Controllers\DonationController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProgramController;
use App\Http\Middleware\EnsureAdmin;
use Illuminate\Support\Facades\Route;

/* ---------- Berkas unggahan ----------
   Cadangan jika `php artisan storage:link` belum dijalankan. Bila symlink sudah ada,
   server web melayani berkasnya langsung dan rute ini tidak terpakai. */
Route::get('/storage/{path}', function (string $path) {
    abort_if(str_contains($path, '..'), 404);
    $disk = \Illuminate\Support\Facades\Storage::disk('public');
    abort_unless($disk->exists($path), 404);
    return response()->file($disk->path($path));
})->where('path', '.*')->name('storage.fallback');

/* ---------- Situs publik ---------- */
Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/program', [ProgramController::class, 'index'])->name('programs.index');
Route::get('/program/{program:slug}', [ProgramController::class, 'show'])->name('programs.show');

Route::get('/donasi', [DonationController::class, 'create'])->name('donation.create');
Route::post('/donasi', [DonationController::class, 'store'])->name('donation.store');
Route::get('/donasi/{donation:invoice}', [DonationController::class, 'show'])->name('donation.show');
Route::post('/donasi/{donation:invoice}/bukti', [DonationController::class, 'uploadProof'])->name('donation.proof');

Route::get('/kabar', [PostController::class, 'index'])->name('posts.index');
Route::get('/kabar/{post:slug}', [PostController::class, 'show'])->name('posts.show');

Route::get('/zakat', [PageController::class, 'zakat'])->name('zakat');
Route::get('/tentang', [PageController::class, 'about'])->name('about');
Route::get('/legalitas', [PageController::class, 'documents'])->name('documents');
// Akun donatur
Route::get('/masuk', [Auth\AuthController::class, 'showLogin'])->name('login');
Route::post('/masuk', [Auth\AuthController::class, 'login'])->middleware('throttle:10,1')->name('login.attempt');
Route::get('/daftar', [Auth\AuthController::class, 'showRegister'])->name('register');
Route::post('/daftar', [Auth\AuthController::class, 'register'])->middleware('throttle:10,1')->name('register.store');
Route::post('/keluar', [Auth\AuthController::class, 'logout'])->name('logout');
Route::get('/akun', [AccountController::class, 'index'])->middleware('auth')->name('account');

Route::get('/program-kami', [PageController::class, 'programOverview'])->name('programs.overview');
Route::get('/laporan', [PageController::class, 'reports'])->name('reports');
Route::get('/laporan/bulanan/{year}/{month}', [PageController::class, 'reportMonth'])
    ->where(['year' => '20\d\d', 'month' => '0?[1-9]|1[0-2]'])->name('reports.month');
Route::get('/laporan/tahunan/{year}', [PageController::class, 'reportYear'])
    ->where(['year' => '20\d\d'])->name('reports.year');
Route::get('/galeri', [PageController::class, 'gallery'])->name('gallery');
Route::get('/kontak', [PageController::class, 'contact'])->name('contact');
Route::post('/kontak', [PageController::class, 'contactStore'])->name('contact.store')->middleware('throttle:5,1');

/* ---------- Panel admin ---------- */
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('login', [Admin\AuthController::class, 'showLogin'])->name('login');
    Route::post('login', [Admin\AuthController::class, 'login'])->middleware('throttle:10,1');
    Route::post('logout', [Admin\AuthController::class, 'logout'])->name('logout');

    Route::middleware(EnsureAdmin::class)->group(function () {
        Route::get('/', [Admin\DashboardController::class, 'index'])->name('dashboard');

        Route::resource('donations', Admin\DonationController::class)->only(['index', 'show', 'update', 'destroy']);

        Route::resource('programs', Admin\ProgramController::class)->except('show');
        Route::resource('program-items', Admin\ProgramItemController::class)->except('show')
            ->parameters(['program-items' => 'id']);
        Route::resource('slides', Admin\SlideController::class)->except('show')
            ->parameters(['slides' => 'id']);
        Route::resource('posts', Admin\PostController::class)->except('show');
        Route::resource('reports', Admin\ReportController::class)->except('show');
        Route::resource('distributions', Admin\DistributionController::class)->except('show');
        Route::resource('payment-methods', Admin\PaymentMethodController::class)->except('show')
            ->parameters(['payment-methods' => 'id']);
        Route::resource('galleries', Admin\GalleryController::class)->except('show');
        Route::resource('team', Admin\TeamMemberController::class)->except('show')
            ->parameters(['team' => 'id']);
        Route::resource('messages', Admin\MessageController::class)->only(['index', 'destroy']);

        Route::get('settings', [Admin\SettingController::class, 'edit'])->name('settings.edit');
        Route::put('settings', [Admin\SettingController::class, 'update'])->name('settings.update');
    });
});
