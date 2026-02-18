<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;

class KalenderController extends Controller
{
    /**
     * Tampilkan daftar berita dalam format JSON yang rapi.
     */
    public function index()
    {
        $path = 'cache/kalender.json';
        if (!Storage::disk('local')->exists($path)) {
            Artisan::call('kalender:cache-json');
        }

        if (!Storage::disk('local')->exists($path)) {
            return response()->json(
                [
                    'status' => 'error',
                    'message' => 'Cache kalender belum tersedia.'
                ],
                503
            );
        }

        $json = Storage::disk('local')->get($path);
        if (!is_string($json) || $json === '') {
            return response()->json(
                [
                    'status' => 'error',
                    'message' => 'Cache kalender tidak valid.'
                ],
                500
            );
        }

        return response($json, 200)->header('Content-Type', 'application/json');
    }
}
