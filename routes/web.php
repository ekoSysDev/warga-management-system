<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PemasukanController;

/*
|--------------------------------------------------------------------------
| Redirect Root
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('login'); // 🔥 langsung ke login
});


/*
|--------------------------------------------------------------------------
| Protected Routes (WAJIB LOGIN)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');
    Route::get('/pemasukan', [PemasukanController::class, 'index'])->name('pemasukan.index');
    Route::get('/pemasukan/create', [PemasukanController::class, 'create'])->name('pemasukan.create');
    Route::post('/pemasukan/store', [PemasukanController::class, 'store'])->name('pemasukan.store');
    Route::get('/pemasukan/edit/{id}', [PemasukanController::class, 'edit'])->name('pemasukan.edit');
    Route::post('/pemasukan/update/{id}', [PemasukanController::class, 'update'])->name('pemasukan.update');
    Route::get('/pemasukan/delete/{id}', [PemasukanController::class, 'delete'])->name('pemasukan.delete');
    Route::get('/warga/search', [PemasukanController::class, 'searchWarga']);
    
});


/*
|--------------------------------------------------------------------------
| Auth Routes (Login, Register, dll)
|--------------------------------------------------------------------------
*/

require __DIR__.'/auth.php';