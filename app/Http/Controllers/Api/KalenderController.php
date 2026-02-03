<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\EconomicCalendar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class KalenderController extends Controller
{
    /**
     * Tampilkan daftar berita dalam format JSON yang rapi.
     */
    public function index()
    {
        $cachedJson = $this->readCachedJson();
        if ($cachedJson !== null) {
            return response($cachedJson, 200)->header('Content-Type', 'application/json');
        }

        // Ambil semua data KalenderEkonomi
        $KalenderEkonomi = EconomicCalendar::all(); // Gantilah dengan query yang sesuai kebutuhan

        return response()->json([
            'status' => 'success',
            'data' => $KalenderEkonomi
        ], 200);
    }

    private function readCachedJson(): ?string
    {
        $path = 'cache/kalender.json';
        if (!Storage::disk('local')->exists($path)) {
            return null;
        }

        $json = Storage::disk('local')->get($path);
        return $json !== '' ? $json : null;
    }
}
