<?php

use App\Http\Controllers\Api\BeritaController;
use App\Http\Controllers\Api\KalenderController;
use App\Http\Controllers\Api\NewsmakerArticleController;
use App\Http\Controllers\Api\PasarIndonesiaArticleController;
use App\Http\Controllers\Api\PivotController;
use App\Http\Controllers\Api\PopupBannerController;
use App\Http\Controllers\Api\TiktokController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// 5 PT
Route::prefix('v1')->middleware('bearer')->group(
    function () {
        Route::get('/berita', [BeritaController::class, 'index']);
        ROute::get('/berita/{slug}', [BeritaController::class, 'show']);
    }
);

// Newsmaker 23
Route::prefix('v1')->middleware('bearer-newsmaker')->group(
    function () {
        Route::prefix('newsmaker')->group(
            function () {
                Route::get('/kategori', [NewsmakerArticleController::class, 'categories']);
                Route::get('/berita', [NewsmakerArticleController::class, 'index']);
                Route::get('/berita/{slug}', [NewsmakerArticleController::class, 'byCategory']);
                Route::get('/berita/show/{slug}', [NewsmakerArticleController::class, 'show'])
                    ->where('slug', '[A-Za-z0-9-]+');

                Route::get('/kalender-ekonomi', [KalenderController::class, 'index']);
                Route::get('/kalender-ekonomi/periode', [KalenderController::class, 'periods']);
                Route::get('/kalender-ekonomi/{period}', [KalenderController::class, 'index'])
                    ->where('period', '[A-Za-z_-]+');

                Route::get('/pivot-history', [PivotController::class, 'index']);

                Route::get('/tiktok', [TiktokController::class, 'index']);
                Route::get('/popup-banner', [PopupBannerController::class, 'index']);

                Route::prefix('pasar-indonesia')->group(
                    function () {
                        Route::get('/berita', [PasarIndonesiaArticleController::class, 'berita']);
                        Route::get('/berita/{slug}', [PasarIndonesiaArticleController::class, 'beritaShow'])
                            ->where('slug', '[A-Za-z0-9-]+');
                        Route::get('/analisis', [PasarIndonesiaArticleController::class, 'analisis']);
                        Route::get('/analisis/{slug}', [PasarIndonesiaArticleController::class, 'analisisShow'])
                            ->where('slug', '[A-Za-z0-9-]+');
                    }
                );
            }
        );
    }
);
