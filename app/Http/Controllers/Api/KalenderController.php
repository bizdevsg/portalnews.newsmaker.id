<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\EconomicCalendar;
use Illuminate\Http\Request;

class KalenderController extends Controller
{
    /**
     * Tampilkan daftar berita dalam format JSON yang rapi.
     */
    public function index()
    {
        // Ambil semua data KalenderEkonomi
        $KalenderEkonomi = EconomicCalendar::all(); // Gantilah dengan query yang sesuai kebutuhan

        return response()->json([
            'status' => 'success',
            'data' => $KalenderEkonomi
        ], 200);
    }
}
