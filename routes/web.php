<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\RadioSettingsController;
use App\Http\Controllers\Admin\BannerController;
use App\Models\Setting;
use App\Models\Banner;

// Home (pública) — carrega URL do painel e 1 banner ativo aleatório
Route::get('/', function () {
    $radioUrl = optional(Setting::where('key','radio_stream_url')->first())->value
        ?? env('RADIO_STREAM_URL', 'https://stm10.conectastreaming.com:6890/stream');

    $banner = Banner::where('active', true)->inRandomOrder()->first();

    return view('radio', [
        'stream' => $radioUrl,
        'logo'   => asset('images/logo.png'),
        'banner' => $banner,
    ]);
});

// Auth (visitante)
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store'])
        ->middleware('throttle:5,1') // 5 tentativas/min
        ->name('login.store');
});

// Área logada
Route::middleware('auth')->group(function () {
    // Dashboard simples (ou troque por controller se preferir)
    Route::get('/dashboard', fn () => view('dashboard'))->name('dashboard');

    // Logout (POST)
    Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');

    // Rádio (editar URL do stream)
    Route::get('/admin/radio', [RadioSettingsController::class, 'edit'])
        ->name('admin.radio.edit');
    Route::put('/admin/radio', [RadioSettingsController::class, 'update'])
        ->name('admin.radio.update');

    // Banners 720x90
    Route::get('/admin/banners', [BannerController::class, 'index'])
        ->name('admin.banners.index');
    Route::post('/admin/banners', [BannerController::class, 'store'])
        ->name('admin.banners.store');
    Route::put('/admin/banners/{banner}/toggle', [BannerController::class, 'toggle'])
        ->name('admin.banners.toggle');     // <<< mantenha PUT
    Route::delete('/admin/banners/{banner}', [BannerController::class, 'destroy'])
        ->name('admin.banners.destroy');
});
