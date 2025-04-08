<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Berita;
use Illuminate\Http\Request;

class BeritaController extends Controller
{
    /**
     * Tampilkan daftar berita.
     */
    public function index()
    {
        // Ambil semua berita beserta nama kategorinya
        $beritas = Berita::with('kategori:id,name')->get();

        return response()->json([
            'status' => 'success',
            'data' => $beritas
        ], 200);
    }
}
