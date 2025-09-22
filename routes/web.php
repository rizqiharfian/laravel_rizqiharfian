<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\RumahSakitController;
use App\Http\Controllers\PasienController;

Auth::routes();

Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Logout
    Route::post('/logout', function () {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect()->route('login');
    })->name('logout');

    // Rumah Sakit
    Route::prefix('rumahsakit')->group(function () {
        Route::get('/', [RumahSakitController::class, 'index'])->name('rumahsakit.index');
        Route::post('/', [RumahSakitController::class, 'store'])->name('rumahsakit.store');
        Route::put('/{id}', [RumahSakitController::class, 'update'])->name('rumahsakit.update');
        Route::delete('/{id}', [RumahSakitController::class, 'destroyAjax'])->name('rumahsakit.destroy');
    });

    // Pasien
    Route::prefix('pasien')->group(function () {
        Route::get('/', [PasienController::class, 'index'])->name('pasien.index');
        Route::post('/', [PasienController::class, 'store'])->name('pasien.store');
        Route::put('/{id}', [PasienController::class, 'update'])->name('pasien.update');
        Route::delete('/{id}', [PasienController::class, 'destroyAjax'])->name('pasien.destroy');
        Route::get('/filter/{rsId}', [PasienController::class, 'filterByRumahSakit'])->name('pasien.filter');
    });
});

