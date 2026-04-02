<?php

namespace App\Http\Controllers;

use App\Models\PasarIndonesiaArticle;

class PasarIndonesiaController extends Controller
{
    public function index()
    {
        $beritaItems = PasarIndonesiaArticle::with('author')
            ->where('type', 'berita')
            ->latest()
            ->get();

        $analisisItems = PasarIndonesiaArticle::with('author')
            ->where('type', 'analisis')
            ->latest()
            ->get();

        $stats = [
            'berita' => $beritaItems->count(),
            'analisis' => $analisisItems->count(),
            'latest_berita' => optional($beritaItems->first())->title_id,
            'latest_analisis' => optional($analisisItems->first())->title_id,
        ];

        return view('pasar-indonesia.index', compact('stats', 'beritaItems', 'analisisItems'));
    }
}
