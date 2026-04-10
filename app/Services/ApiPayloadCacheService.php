<?php

namespace App\Services;

use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Storage;

class ApiPayloadCacheService
{
    public function getPayload(string $path): ?array
    {
        if (!Storage::disk('local')->exists($path)) {
            return null;
        }

        $json = Storage::disk('local')->get($path);
        if ($json === null || $json === '') {
            return null;
        }

        $payload = json_decode($json, true);

        return is_array($payload) ? $payload : null;
    }

    public function resolveGeneratedAt(string $path, ?array $payload = null): string
    {
        $payload ??= $this->getPayload($path);

        $payloadGeneratedAt = $payload['generated_at'] ?? $payload['meta']['generated_at'] ?? null;
        if (is_string($payloadGeneratedAt) && trim($payloadGeneratedAt) !== '') {
            try {
                return CarbonImmutable::parse($payloadGeneratedAt, config('app.timezone'))->toIso8601String();
            } catch (\Throwable) {
                // Fall through to file timestamp.
            }
        }

        if (Storage::disk('local')->exists($path)) {
            return CarbonImmutable::createFromTimestamp(
                Storage::disk('local')->lastModified($path),
                config('app.timezone')
            )->toIso8601String();
        }

        return now()->toIso8601String();
    }
}
