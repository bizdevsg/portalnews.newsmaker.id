<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\ApiPayloadCacheService;
use Carbon\CarbonImmutable;
use Illuminate\Http\JsonResponse;

class IklanController extends Controller
{
    public function __construct(
        private readonly ApiPayloadCacheService $cacheService
    ) {
    }

    public function index(): JsonResponse
    {
        $path = 'cache/iklan.json';
        $payload = $this->cacheService->getPayload($path);

        if ($payload === null) {
            return response()->json(
                [
                    'status' => 'error',
                    'message' => 'Cache iklan belum tersedia.',
                ],
                503,
                [],
                JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
            );
        }

        $iklans = collect($payload['data'] ?? [])
            ->filter(fn (array $iklan) => $this->isCurrentlyVisible($iklan))
            ->values();

        return response()->json(
            [
                'status' => 'success',
                'data' => $iklans,
                'meta' => [
                    'total' => $iklans->count(),
                    'generated_at' => $this->cacheService->resolveGeneratedAt($path, $payload),
                ],
            ],
            200,
            [],
            JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
        );
    }

    private function isCurrentlyVisible(array $iklan): bool
    {
        if (($iklan['is_active'] ?? false) !== true) {
            return false;
        }

        $now = CarbonImmutable::now(config('app.timezone'));
        $startAt = $this->parseDateTime($iklan['start_at'] ?? null);
        $endAt = $this->parseDateTime($iklan['end_at'] ?? null);

        if ($startAt !== null && $startAt->greaterThan($now)) {
            return false;
        }

        if ($endAt !== null && $endAt->lessThan($now)) {
            return false;
        }

        return true;
    }

    private function parseDateTime(mixed $value): ?CarbonImmutable
    {
        if (!is_string($value) || trim($value) === '') {
            return null;
        }

        try {
            return CarbonImmutable::parse($value, config('app.timezone'));
        } catch (\Throwable) {
            return null;
        }
    }
}

