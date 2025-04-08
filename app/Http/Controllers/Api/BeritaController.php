<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Berita;
use Illuminate\Http\Request;

class BeritaController extends Controller
{
    /**
     * Tampilkan daftar berita dalam format JSON yang rapi.
     */
    public function index()
    {
        // Ambil semua berita beserta nama kategorinya, tanpa field image1 - image5 secara langsung
        $beritas = Berita::select('id', 'title', 'content', 'image1', 'image2', 'image3', 'image4', 'image5', 'category_id', 'created_at', 'updated_at')
            ->with(['category:id,name'])
            ->get()
            ->map(function ($berita) {
                return [
                    'id' => $berita->id,
                    'title' => $berita->title,
                    'content' => $berita->content,
                    'category_id' => $berita->category_id,
                    'kategori' => $berita->category,
                    'images' => $berita->images, // Ambil dari accessor
                    'created_at' => $berita->created_at,
                    'updated_at' => $berita->updated_at,
                ];
            });

        return response()->json([
            'status' => 'success',
            'data' => $beritas
        ], 200);
    }
}
