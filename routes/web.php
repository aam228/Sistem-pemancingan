<?php
// routes/web.php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MejaController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\PesananMakananController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\AnalyticsController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Kolam (Meja)
    Route::resource('meja', MejaController::class);
    Route::post('/meja/{meja}/reset', [\App\Http\Controllers\MejaController::class, 'reset'])
    ->name('meja.reset');

    // Transaksi Pemancingan
    Route::prefix('transaksi')->controller(TransaksiController::class)->group(function () {
        Route::get('create/{meja_id}', 'create')->name('transaksi.create');
        Route::post('/', 'store')->name('transaksi.store');
        Route::get('{id}/selesai', 'selesaiForm')->name('transaksi.selesai.form');
        Route::put('{id}/selesai', 'selesaiProses')->name('transaksi.selesai.proses');
        Route::post('{id}/batal', 'batal')->name('transaksi.batal');
        Route::delete('{transaksi}', 'hapus')->name('transaksi.hapus');
        Route::get('histori', 'histori')->name('transaksi.histori');
        Route::get('laporan', 'laporan')->name('transaksi.laporan');
        Route::get('laporan/cetak', 'cetakLaporan')->name('transaksi.cetak');
    });

    // Pesanan Makanan/Minuman
    Route::get('pesanan/create/{transaksi_id}', [PesananMakananController::class, 'create'])->name('pesanan.create');
    Route::post('pesanan', [PesananMakananController::class, 'store'])->name('pesanan.store');
    Route::get('api/pesanan-makanan/{transaksi_id}', [PesananMakananController::class, 'getPesananByTransaksi'])->name('api.pesanan.byTransaksi');

    // Produk (Untuk makanan/minuman)
    Route::resource('produk', ProdukController::class);

    // Settings
    Route::prefix('settings')->controller(SettingsController::class)->group(function () {
        Route::get('/', 'index')->name('settings.index');
        Route::patch('/profile', 'updateProfile')->name('settings.updateProfile');
        Route::patch('/password', 'updatePassword')->name('settings.updatePassword');
        Route::patch('/theme', 'updateTheme')->name('settings.updateTheme');
        Route::patch('/profile-image', 'updateProfileImage')->name('settings.updateProfileImage'); 
    });

    Route::get('/analytics', [AnalyticsController::class, 'index'])->name('analytics.index');


});

require __DIR__.'/auth.php';