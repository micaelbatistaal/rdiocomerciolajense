<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\LoginController;

// Route::get('/', function () {
//     return view('welcome');
// });



Route::get('/', function () {
    return view('radio', [
        'stream' => env('RADIO_STREAM_URL', 'https://stm10.conectastreaming.com:6890/stream'),
        'logo'   => asset('images/logo.png'), 
    ]);
});




Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store'])
        ->middleware('throttle:5,1') // 5 tentativas/min
        ->name('login.store');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', fn () => view('dashboard'))->name('dashboard');
    Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');
});



use App\Http\Controllers\Admin\RadioSettingsController;
use App\Http\Controllers\Admin\BannerController;

Route::get('/admin/radio', [RadioSettingsController::class, 'edit'])->name('admin.radio.edit');
Route::put('/admin/radio', [RadioSettingsController::class, 'update'])->name('admin.radio.update');

Route::get('/admin/banners', [BannerController::class, 'index'])->name('admin.banners.index');
Route::post('/admin/banners', [BannerController::class, 'store'])->name('admin.banners.store');
Route::post('/admin/banners/{banner}/toggle', [BannerController::class, 'toggle'])->name('admin.banners.toggle');
Route::delete('/admin/banners/{banner}', [BannerController::class, 'destroy'])->name('admin.banners.destroy');



Route::middleware('auth')->group(function () {
    // ... sua rota do dashboard já existente

    // Rádio
    Route::put('/admin/radio', [RadioSettingsController::class, 'update'])
        ->name('admin.radio.update');

    // Banners
    Route::post('/admin/banners', [BannerController::class, 'store'])
        ->name('admin.banners.store');

    Route::put('/admin/banners/{banner}/toggle', [BannerController::class, 'toggle'])
        ->name('admin.banners.toggle');

    Route::delete('/admin/banners/{banner}', [BannerController::class, 'destroy'])
        ->name('admin.banners.destroy');
});