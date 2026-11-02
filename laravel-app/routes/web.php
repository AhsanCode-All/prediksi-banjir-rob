<?php

use App\Http\Controllers\Admin\ImporController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\KelurahanController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DataHarianController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\KualitasDataController;
use App\Http\Controllers\PrediksiController;
use App\Http\Controllers\PetaController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth')->prefix('admin')->group(function () {

    Route::get('/impor', function () {
        return view('admin.impor.index');
    })->name('admin.impor.index');

    Route::post('/impor', [ImporController::class, 'store'])
        ->name('admin.impor.store');

    Route::get('/data', [DataHarianController::class, 'index'])
        ->name('admin.data.index');

    Route::get('/riwayat-impor', [ImporController::class, 'index'])
    ->name('admin.impor.riwayat');

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('admin.dashboard');

    Route::get('/kelurahan', [KelurahanController::class, 'index'])
        ->name('admin.kelurahan.index');

    Route::get('/kelurahan/{id}', [KelurahanController::class, 'show'])
        ->name('admin.kelurahan.show');
    
    Route::get('/dashboard/grafik-harian', [DashboardController::class, 'grafikHarian'])
        ->name('admin.dashboard.grafik');

    Route::get('/kualitas-data', [KualitasDataController::class, 'index'])
        ->name('admin.kualitas-data.index');

    Route::post('/prediksi', [PrediksiController::class, 'proses'])
        ->name('admin.prediksi.proses');

    Route::get('/peta', [PetaController::class, 'admin'])
        ->name('admin.peta.index');
});

// Halaman login
Route::get('/admin/login', [AuthController::class, 'showLogin'])
    ->name('admin.login');

// Proses login
Route::post('/admin/login', [AuthController::class, 'login'])
    ->name('admin.login.process');

// Logout
Route::post('/admin/logout', [AuthController::class, 'logout'])
    ->name('admin.logout');

// Halaman peta masyarakat
Route::get('/peta', [PetaController::class, 'index'])
    ->name('peta.index');

// API data peta
Route::get('/api/peta-prediksi', [PetaController::class, 'data'])
    ->name('peta.data');