<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;

class PivotController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $path = 'cache/pivot.json';
        if (!Storage::disk('local')->exists($path)) {
            Artisan::call('pivot:cache-json');
        }

        if (!Storage::disk('local')->exists($path)) {
            return response()->json(
                [
                    'status' => 'error',
                    'message' => 'Cache pivot belum tersedia.'
                ],
                503
            );
        }

        $json = Storage::disk('local')->get($path);
        if (!is_string($json) || $json === '') {
            return response()->json(
                [
                    'status' => 'error',
                    'message' => 'Cache pivot tidak valid.'
                ],
                500
            );
        }

        return response($json, 200)->header('Content-Type', 'application/json');
    }
}
