<?php

use App\Http\Controllers\BeritaController;
use App\Http\Controllers\CategoryController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DataFeedController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EconomicCalendarController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Redirect root ke halaman login
Route::redirect('/', 'login');

Route::middleware(['auth:sanctum', 'verified'])->group(function () {

    // Route untuk mendapatkan data feed JSON
    Route::get('/json-data-feed', [DataFeedController::class, 'getDataFeed'])
        ->name('json_data_feed');

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Route untuk Berita (Kategori)
    Route::prefix('kategori')->group(function () {
        Route::get('/', [CategoryController::class, 'index'])->name('kategori.index');
        Route::post('/store', [CategoryController::class, 'store'])->name('kategori.store');
        Route::get('/tambah', [CategoryController::class, 'create'])->name('kategori.create');
        Route::put('/{id}/update', [CategoryController::class, 'update'])->name('kategori.update');
        Route::get('/{id}/edit', [CategoryController::class, 'edit'])->name('kategori.edit');
        Route::delete('/{id}/delete', [CategoryController::class, 'destroy'])->name('kategori.destroy');

        Route::get('/{slug}', [BeritaController::class, 'index'])->name('berita.index'); // Menampilkan berita berdasarkan kategori
        Route::get('/{slug}/tambah', [BeritaController::class, 'create'])->name('berita.create'); // Form tambah berita
        Route::post('/{slug}/store', [BeritaController::class, 'store'])->name('berita.store'); // Simpan berita baru
        Route::get('/{slug}/{id}', [BeritaController::class, 'show'])->name('berita.show'); // Lihat detail berita
        Route::get('/{slug}/{id}/edit', [BeritaController::class, 'edit'])->name('berita.edit'); // Form edit berita
        Route::put('/{slug}/{id}/update', [BeritaController::class, 'update'])->name('berita.update'); // Update berita
        Route::delete('/{slug}/{id}/hapus', [BeritaController::class, 'destroy'])->name('berita.destroy'); // Hapus berita
    });

    // Kalender Route
    Route::prefix('kalender')->group(function () {
        Route::get('/', [EconomicCalendarController::class, 'index'])->name('calendar.index');
        Route::post('/store', [EconomicCalendarController::class, 'store'])->name('calendar.store');
        Route::get('/tambah', [EconomicCalendarController::class, 'create'])->name('calendar.create');
        Route::put('/{id}/update', [EconomicCalendarController::class, 'update'])->name('calendar.update');
        Route::get('/{id}/edit', [EconomicCalendarController::class, 'edit'])->name('calendar.edit');
        Route::delete('/{id}/delete', [EconomicCalendarController::class, 'destroy'])->name('calendar.delete');
        Route::get('/{id}/show', [EconomicCalendarController::class, 'show'])->name('calendar.show');
    });

    // User Management Route
    Route::prefix('user')->middleware(['role:Superadmin'])->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('user.index');
        Route::post('/store', [UserController::class, 'store'])->name('user.store');
        Route::get('/tambah', [UserController::class, 'create'])->name('user.create');
        Route::put('/{id}/update', [UserController::class, 'update'])->name('user.update');
        Route::get('/{id}/edit', [UserController::class, 'edit'])->name('user.edit');
        Route::delete('/{id}/delete', [UserController::class, 'destroy'])->name('user.destroy');
    });

    // Fallback jika route tidak ditemukan
    Route::fallback(function () {
        return view('404');
    });
});
