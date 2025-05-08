<?php

use App\Http\Controllers\Api\BeritaController;
use App\Http\Controllers\Api\KalenderController;
use Illuminate\Http\Request;
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

Route::get('/berita', [BeritaController::class, 'index']);
ROute::get('/berita/{slug}', [BeritaController::class, 'show']);

Route::get('/kalender-ekonomi', [KalenderController::class, 'index']);
