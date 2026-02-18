<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;

class BeritaController extends Controller
{
    private string $cachePath = 'cache/berita.json';

    public function index()
    {
        $json = $this->readCacheJson();
        if ($json === null) {
            return response()->json(
                [
                    'status' => 'error',
                    'message' => 'Cache berita belum tersedia.'
                ],
                503
            );
        }

        return response($json, 200)->header('Content-Type', 'application/json');
    }

    public function show($slug)
    {
        $payload = $this->readCacheDecoded();
        if ($payload === null || !isset($payload['data']) || !is_array($payload['data'])) {
            return response()->json(
                [
                    'status' => 'error',
                    'message' => 'Cache berita belum tersedia.'
                ],
                503
            );
        }

        $berita = collect($payload['data'])->firstWhere('slug', $slug);
        if (!$berita) {
            return response()->json(
                [
                    'status'  => 'error',
                    'message' => 'Berita tidak ditemukan.'
                ],
                404,
                [],
                JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
            );
        }

        return response()->json(
            [
                'status' => 'success',
                'data'   => $berita
            ],
            200,
            [],
            JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
        );
    }

    private function readCacheJson(): ?string
    {
        if (!Storage::disk('local')->exists($this->cachePath)) {
            Artisan::call('berita:cache-json');
        }

        if (!Storage::disk('local')->exists($this->cachePath)) {
            return null;
        }

        $json = Storage::disk('local')->get($this->cachePath);
        if (!is_string($json) || $json === '') {
            return null;
        }

        return $json;
    }

    private function readCacheDecoded(): ?array
    {
        $json = $this->readCacheJson();
        if ($json === null) {
            return null;
        }

        $decoded = json_decode($json, true);
        if (!is_array($decoded)) {
            return null;
        }

        return $decoded;
    }
}
