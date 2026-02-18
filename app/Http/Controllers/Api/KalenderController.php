<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

class KalenderController extends Controller
{
    /**
     * Tampilkan daftar berita dalam format JSON yang rapi.
     */
    public function index(): JsonResponse
    {
        $payload = $this->getCachePayload('cache/kalender.json');
        if ($payload === null) {
            return response()->json(
                ['status' => 'error', 'message' => 'Cache kalender belum tersedia.'],
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
