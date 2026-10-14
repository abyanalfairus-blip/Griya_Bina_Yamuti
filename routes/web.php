<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\DonationController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProgramController;
use App\Http\Middleware\EnsureAdmin;
use Illuminate\Support\Facades\Route;

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
Route::get('/laporan', [PageController::class, 'reports'])->name('reports');
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
        Route::resource('posts', Admin\PostController::class)->except('show');
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
