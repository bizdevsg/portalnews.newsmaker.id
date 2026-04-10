<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use App\Services\ApiPayloadCacheService;

class TiktokController extends Controller
{
    public function __construct(
        private readonly ApiPayloadCacheService $cacheService
    ) {
    }

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
