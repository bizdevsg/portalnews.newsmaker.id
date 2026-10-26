<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\ApiPayloadCacheService;
use Illuminate\Http\JsonResponse;

class TiktokController extends Controller
{
    public function __construct(
        private readonly ApiPayloadCacheService $cacheService
    ) {
    }

    /**
     * Singleton: `data` berisi 1 objek video TikTok yang sedang aktif, atau
     * `null` kalau belum ada video yang diunggah (bukan error - itu status
     * valid). Hanya `503` kalau cache-nya sendiri belum pernah dibuat.
     */
    public function index(): JsonResponse
    {
        $payload = $this->cacheService->getPayload('cache/tiktok.json');
        if ($payload === null) {
            return response()->json(
                ['status' => 'error', 'message' => 'Cache TikTok belum tersedia.'],
                503,
                [],
                JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
            );
        }

        return response()->json(
            $payload,
            200,
            [],
            JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
        );
    }
}
