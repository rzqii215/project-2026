<?php

use App\Http\Controllers\Admin\LaporanPrestasiController;
use App\Http\Controllers\Auth\AdminLoginController;
use App\Http\Controllers\Auth\MahasiswaLoginController;
use App\Http\Controllers\EPortfolioController;
use App\Http\Controllers\LandingPageController;
use Illuminate\Support\Facades\Route;
use Livewire\Livewire;

/*
|--------------------------------------------------------------------------
| Livewire Routes
|--------------------------------------------------------------------------
*/

Livewire::setUpdateRoute(function ($handle) {
    return Route::post(config('app.asset_prefix') . '/livewire/update', $handle);
});

Livewire::setScriptRoute(function ($handle) {
    return Route::get(config('app.asset_prefix') . '/livewire/livewire.js', $handle);
});

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [LandingPageController::class, 'index'])
    ->name('home');

Route::get('/portfolio', [LandingPageController::class, 'portfolio'])
    ->name('portfolio.index');

/*
|--------------------------------------------------------------------------
| Manual Auth Admin
|--------------------------------------------------------------------------
*/

Route::get('/admin/login', function () {
    return redirect('/login-admin');
});

Route::get('/login-admin', [AdminLoginController::class, 'show'])
    ->middleware('guest')
    ->name('login.admin');

Route::post('/login-admin', [AdminLoginController::class, 'login'])
    ->middleware('guest')
    ->name('login.admin.post');

Route::post('/logout-admin', [AdminLoginController::class, 'logout'])
    ->middleware('auth')
    ->name('logout.admin');

Route::get('/reset-admin-direct', [AdminLoginController::class, 'resetAdmin'])
    ->name('reset.admin.direct');

/*
|--------------------------------------------------------------------------
| Manual Auth Mahasiswa
|--------------------------------------------------------------------------
*/

Route::get('/mahasiswa/login', function () {
    return redirect('/login-mahasiswa');
});

Route::get('/mahasiswa/register', function () {
    return redirect('/register-mahasiswa');
});

Route::get('/login-mahasiswa', [MahasiswaLoginController::class, 'show'])
    ->middleware('guest')
    ->name('login');

Route::post('/login-mahasiswa', [MahasiswaLoginController::class, 'login'])
    ->middleware('guest')
    ->name('login.mahasiswa.post');

Route::get('/register-mahasiswa', [MahasiswaLoginController::class, 'showRegister'])
    ->middleware('guest')
    ->name('register.mahasiswa');

Route::post('/register-mahasiswa', [MahasiswaLoginController::class, 'register'])
    ->middleware('guest')
    ->name('register.mahasiswa.post');

Route::post('/logout-mahasiswa', [MahasiswaLoginController::class, 'logout'])
    ->middleware('auth')
    ->name('logout.mahasiswa');

/*
|--------------------------------------------------------------------------
| E-Portfolio Public
|--------------------------------------------------------------------------
*/

Route::get('/e-portfolio/{user}', [EPortfolioController::class, 'show'])
    ->name('eportfolio.show');

Route::get('/e-portfolio/{user}/cetak', [EPortfolioController::class, 'cetak'])
    ->name('eportfolio.print');

/*
|--------------------------------------------------------------------------
| Laporan Admin
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    Route::get('/laporan/prestasi/cetak', [LaporanPrestasiController::class, 'cetak'])
        ->name('laporan.prestasi.cetak');

    Route::get('/laporan/prestasi/export', [LaporanPrestasiController::class, 'export'])
        ->name('laporan.prestasi.export');
});