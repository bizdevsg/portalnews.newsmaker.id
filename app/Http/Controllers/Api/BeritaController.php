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
        // Ambil semua berita beserta nama kategorinya, tanpa mengambil kolom gambar secara langsung
        $beritas = Berita::select('id', 'title', 'slug', 'content', 'image1', 'image2', 'image3', 'image4', 'image5', 'category_id', 'created_at', 'updated_at')
            ->with(['category:id,name,slug'])
            ->get()
            ->transform(function ($berita) {
                return [
                    'id' => $berita->id,
                    'title' => $berita->title,
                    'slug' => $berita->slug, // Menambahkan slug
                    'content' => $berita->content,
                    'category_id' => $berita->category_id,
                    'kategori' => $berita->category,
                    'images' => $berita->images, // Mengambil dari accessor images
                    'created_at' => $berita->created_at,
                    'updated_at' => $berita->updated_at,
                ];
            });

        return response()->json([
            'status' => 'success',
            'data' => $beritas
        ], 200);
    }

    public function show($slug)
    {
        // Cari berita berdasarkan slug
        $berita = Berita::select('id', 'title', 'slug', 'content', 'image1', 'image2', 'image3', 'image4', 'image5', 'category_id', 'created_at', 'updated_at')
            ->with(['category:id,name'])
            ->where('slug', $slug)
            ->first();

        // Jika tidak ditemukan
        if (!$berita) {
            return response()->json([
                'status' => 'error',
                'message' => 'Berita tidak ditemukan.'
            ], 404);
        }

        // Format data
        $data = [
            'id' => $berita->id,
            'title' => $berita->title,
            'slug' => $berita->slug,
            'content' => $berita->content,
            'category_id' => $berita->category_id,
            'kategori' => $berita->category,
            'images' => $berita->images,
            'created_at' => $berita->created_at,
            'updated_at' => $berita->updated_at,
        ];

        return response()->json([
            'status' => 'success',
            'data' => $data
        ], 200);
    }
}
