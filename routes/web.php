<?php

use App\Http\Controllers\BeritaController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EconomicCalendarController;
use App\Http\Controllers\EconomicCalendarDetailController;
use App\Http\Controllers\Newsmaker23Controller;
use App\Http\Controllers\NewsmakerArticleController;
use App\Http\Controllers\NewsmakerMainCategoryController;
use App\Http\Controllers\PasarIndonesiaAnalisisController;
use App\Http\Controllers\PasarIndonesiaBeritaController;
use App\Http\Controllers\PasarIndonesiaCategoryController;
use App\Http\Controllers\PasarIndonesiaController;
use App\Http\Controllers\PasarIndonesiaRegulasiInstitusiCategoryController;
use App\Http\Controllers\PasarIndonesiaRegulasiInstitusiController;
use App\Http\Controllers\PivotController;
use App\Http\Controllers\IklanController;
use App\Http\Controllers\PopupBannerController;
use App\Http\Controllers\TiktokController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

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
    Route::prefix('kalender')->name('calendar.')->group(function () {
        Route::get('/', [EconomicCalendarController::class, 'index'])->name('index');
        Route::get('/preview', [EconomicCalendarController::class, 'preview'])->name('preview');
        Route::get('/tambah', [EconomicCalendarController::class, 'create'])->name('create');
        Route::post('/store', [EconomicCalendarController::class, 'store'])->name('store');
        Route::get('/{calendarCategory}/show', [EconomicCalendarController::class, 'show'])->name('show');
        Route::get('/{calendarCategory}/edit', [EconomicCalendarController::class, 'edit'])->name('edit');
        Route::put('/{calendarCategory}/update', [EconomicCalendarController::class, 'update'])->name('update');
        Route::delete('/{calendarCategory}/delete', [EconomicCalendarController::class, 'destroy'])->name('delete');

        Route::get('/{calendarCategory}/detail/tambah', [EconomicCalendarDetailController::class, 'create'])
            ->name('detail.create');
        Route::post('/{calendarCategory}/detail/store', [EconomicCalendarDetailController::class, 'store'])
            ->name('detail.store');
        Route::get('/detail/{detail}/edit', [EconomicCalendarDetailController::class, 'edit'])
            ->name('detail.edit');
        Route::put('/detail/{detail}/update', [EconomicCalendarDetailController::class, 'update'])
            ->name('detail.update');
        Route::delete('/detail/{detail}/delete', [EconomicCalendarDetailController::class, 'destroy'])
            ->name('detail.delete');
    });

    // Pivot & Fibonacci
    Route::prefix('historical-data')->group(function () {
        Route::get('/', [PivotController::class, 'index'])->name('pivot.index');
        Route::post('/store', [PivotController::class, 'store'])->name('pivot.store');
        Route::get('/tambah', [PivotController::class, 'create'])->name('pivot.create');
        Route::put('/{id}/update', [PivotController::class, 'update'])->name('pivot.update');
        Route::get('/{id}/edit', [PivotController::class, 'edit'])->name('pivot.edit');
        Route::delete('/{id}/delete', [PivotController::class, 'destroy'])->name('pivot.destroy');
    });

    // TikTok
    Route::prefix('tiktok')->group(function () {
        Route::get('/', [TiktokController::class, 'index'])->name('tiktok.index');
        Route::post('/store', [TiktokController::class, 'store'])->name('tiktok.store');
        Route::get('/tambah', [TiktokController::class, 'create'])->name('tiktok.create');
        Route::put('/{id}/update', [TiktokController::class, 'update'])->name('tiktok.update');
        Route::get('/{id}/edit', [TiktokController::class, 'edit'])->name('tiktok.edit');
        Route::get('/{id}/show', [TiktokController::class, 'show'])->name('tiktok.show');
        Route::delete('/{id}/delete', [TiktokController::class, 'destroy'])->name('tiktok.destroy');
    });

    // Newsmaker 23
    Route::prefix('newsmaker23')->name('newsmaker23.')->group(function () {
        Route::get('/', [Newsmaker23Controller::class, 'index'])->name('index');

        Route::prefix('main-category')->name('main-category.')->group(function () {
            Route::get('/', [NewsmakerMainCategoryController::class, 'index'])->name('index');
            Route::get('/tambah', [NewsmakerMainCategoryController::class, 'create'])->name('create');
            Route::post('/store', [NewsmakerMainCategoryController::class, 'store'])->name('store');
            Route::get('/{slug}', [NewsmakerMainCategoryController::class, 'show'])->name('show');
            Route::get('/{id}/edit', [NewsmakerMainCategoryController::class, 'edit'])->name('edit');
            Route::put('/{id}/update', [NewsmakerMainCategoryController::class, 'update'])->name('update');
            Route::delete('/{id}/delete', [NewsmakerMainCategoryController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('berita')->name('berita.')->group(function () {
            Route::get('/', [NewsmakerArticleController::class, 'index'])->name('index');
            Route::get('/tambah', [NewsmakerArticleController::class, 'create'])->name('create');
            Route::post('/store', [NewsmakerArticleController::class, 'store'])->name('store');
            Route::get('/{id}/show', [NewsmakerArticleController::class, 'show'])->name('show');
            Route::get('/{id}/edit', [NewsmakerArticleController::class, 'edit'])->name('edit');
            Route::put('/{id}/update', [NewsmakerArticleController::class, 'update'])->name('update');
            Route::delete('/{id}/delete', [NewsmakerArticleController::class, 'destroy'])->name('destroy');
        });
    });

    // Pasar Indonesia
    Route::prefix('pasar-indonesia')->group(function () {
        Route::prefix('regulasi-institusi')->name('regulasi-institusi.')->group(function () {
            Route::get('/', [PasarIndonesiaRegulasiInstitusiCategoryController::class, 'index'])->name('index');

            Route::prefix('kategori')->name('kategori.')->group(function () {
                Route::get('/tambah', [PasarIndonesiaRegulasiInstitusiCategoryController::class, 'create'])->name('create');
                Route::post('/store', [PasarIndonesiaRegulasiInstitusiCategoryController::class, 'store'])->name('store');
                Route::get('/{slug}', [PasarIndonesiaRegulasiInstitusiCategoryController::class, 'show'])->name('show');
                Route::get('/{id}/edit', [PasarIndonesiaRegulasiInstitusiCategoryController::class, 'edit'])->name('edit');
                Route::put('/{id}/update', [PasarIndonesiaRegulasiInstitusiCategoryController::class, 'update'])->name('update');
                Route::delete('/{id}/delete', [PasarIndonesiaRegulasiInstitusiCategoryController::class, 'destroy'])->name('destroy');
            });

            Route::get('/tambah', [PasarIndonesiaRegulasiInstitusiController::class, 'create'])->name('create');
            Route::post('/store', [PasarIndonesiaRegulasiInstitusiController::class, 'store'])->name('store');
            Route::get('/{id}/show', [PasarIndonesiaRegulasiInstitusiController::class, 'show'])->name('show');
            Route::get('/{id}/edit', [PasarIndonesiaRegulasiInstitusiController::class, 'edit'])->name('edit');
            Route::put('/{id}/update', [PasarIndonesiaRegulasiInstitusiController::class, 'update'])->name('update');
            Route::delete('/{id}/delete', [PasarIndonesiaRegulasiInstitusiController::class, 'destroy'])->name('destroy');
        });

        Route::name('pasar-indonesia.')->group(function () {
            Route::get('/', [PasarIndonesiaController::class, 'index'])->name('index');

            Route::prefix('berita')->name('berita.')->group(function () {
                Route::get('/', [PasarIndonesiaCategoryController::class, 'index'])->name('index');

                Route::prefix('kategori')->name('kategori.')->group(function () {
                    Route::get('/tambah', [PasarIndonesiaCategoryController::class, 'create'])->name('create');
                    Route::post('/store', [PasarIndonesiaCategoryController::class, 'store'])->name('store');
                    Route::get('/{slug}', [PasarIndonesiaCategoryController::class, 'show'])->name('show');
                    Route::get('/{id}/edit', [PasarIndonesiaCategoryController::class, 'edit'])->name('edit');
                    Route::put('/{id}/update', [PasarIndonesiaCategoryController::class, 'update'])->name('update');
                    Route::delete('/{id}/delete', [PasarIndonesiaCategoryController::class, 'destroy'])->name('destroy');
                });
                Route::get('/tambah', [PasarIndonesiaBeritaController::class, 'create'])->name('create');
                Route::post('/store', [PasarIndonesiaBeritaController::class, 'store'])->name('store');
                Route::get('/{id}/show', [PasarIndonesiaBeritaController::class, 'show'])->name('show');
                Route::get('/{id}/edit', [PasarIndonesiaBeritaController::class, 'edit'])->name('edit');
                Route::put('/{id}/update', [PasarIndonesiaBeritaController::class, 'update'])->name('update');
                Route::delete('/{id}/delete', [PasarIndonesiaBeritaController::class, 'destroy'])->name('destroy');
            });

            Route::prefix('analisis')->name('analisis.')->group(function () {
                Route::get('/', [PasarIndonesiaAnalisisController::class, 'index'])->name('index');
                Route::get('/tambah', [PasarIndonesiaAnalisisController::class, 'create'])->name('create');
                Route::post('/store', [PasarIndonesiaAnalisisController::class, 'store'])->name('store');
                Route::get('/{id}/show', [PasarIndonesiaAnalisisController::class, 'show'])->name('show');
                Route::get('/{id}/edit', [PasarIndonesiaAnalisisController::class, 'edit'])->name('edit');
                Route::put('/{id}/update', [PasarIndonesiaAnalisisController::class, 'update'])->name('update');
                Route::delete('/{id}/delete', [PasarIndonesiaAnalisisController::class, 'destroy'])->name('destroy');
            });
        });
    });

    Route::prefix('popup-banner')->name('popup-banner.')->group(function () {
        Route::get('/', [PopupBannerController::class, 'index'])->name('index');
        Route::get('/tambah', [PopupBannerController::class, 'create'])->name('create');
        Route::post('/store', [PopupBannerController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [PopupBannerController::class, 'edit'])->name('edit');
        Route::put('/{id}/update', [PopupBannerController::class, 'update'])->name('update');
        Route::delete('/{id}/delete', [PopupBannerController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('iklan')->name('iklan.')->group(function () {
        Route::get('/', [IklanController::class, 'index'])->name('index');
        Route::get('/tambah', [IklanController::class, 'create'])->name('create');
        Route::post('/store', [IklanController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [IklanController::class, 'edit'])->name('edit');
        Route::put('/{id}/update', [IklanController::class, 'update'])->name('update');
        Route::delete('/{id}/delete', [IklanController::class, 'destroy'])->name('destroy');
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
