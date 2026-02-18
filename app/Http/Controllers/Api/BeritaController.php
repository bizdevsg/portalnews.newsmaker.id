<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

class BeritaController extends Controller
{
    public function index(): JsonResponse
    {
        return $this->respondFromCache('cache/berita.json');
    }

    public function show(string $slug): JsonResponse
    {
        $payload = $this->getCachePayload('cache/berita.json');
        if ($payload === null) {
            return response()->json(
                ['status' => 'error', 'message' => 'Cache berita belum tersedia.'],
                503
            );
        }

        $items = $payload['data'] ?? [];
        $item = collect($items)->firstWhere('slug', $slug);

        if (!$item) {
            return response()->json(
                ['status' => 'error', 'message' => 'Berita tidak ditemukan.'],
                404,
                [],
                JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
            );
        }

        return response()->json(
            ['status' => 'success', 'data' => $item],
            200,
            [],
            JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
        );
    }

    private function respondFromCache(string $path): JsonResponse
    {
        $payload = $this->getCachePayload($path);

        if ($payload === null) {
            return response()->json(
                ['status' => 'error', 'message' => 'Cache API belum tersedia.'],
                503
            );
        }

        return response()->json(
            $payload,
            200,
            [],
            JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
        );
    }

    private function getCachePayload(string $path): ?array
    {
        if (!Storage::disk('local')->exists($path)) {
            return null;
        }

        $json = Storage::disk('local')->get($path);
        if ($json === null || $json === '') {
            return null;
        }

        $payload = json_decode($json, true);
        if (!is_array($payload)) {
            return null;
        }

        return $payload;
    }
}
