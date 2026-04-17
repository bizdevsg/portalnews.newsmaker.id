<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\NewsmakerVideoBriefing;
use App\Services\ApiPayloadCacheService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class NewsmakerVideoBriefingController extends Controller
{
    public function __construct(
        private readonly ApiPayloadCacheService $cacheService
    ) {
    }

    public function index(): JsonResponse
    {
        $path = 'cache/video-briefing.json';
        $payload = $this->cacheService->getPayload($path);

        if ($payload !== null) {
            return response()->json(
                $payload,
                200,
                [],
                JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
            );
        }

        if (!Schema::hasTable((new NewsmakerVideoBriefing())->getTable())) {
            return response()->json(
                [
                    'status' => 'error',
                    'message' => 'Tabel Video Briefing belum tersedia. Jalankan migrasi terlebih dahulu.',
                ],
                503,
                [],
                JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
            );
        }

        $items = NewsmakerVideoBriefing::query()
            ->orderByDesc('created_at')
            ->get()
            ->map(fn (NewsmakerVideoBriefing $videoBriefing) => $this->transformVideoBriefing($videoBriefing))
            ->values();

        $responsePayload = [
            'status' => 'success',
            'message' => 'Data Video Briefing berhasil diambil',
            'data' => $items,
            'generated_at' => now()->toISOString(),
        ];

        $json = json_encode($responsePayload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        if ($json !== false) {
            Storage::disk('local')->put($path, $json);
        }

        return response()->json(
            $responsePayload,
            200,
            [],
            JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
        );
    }

    private function transformVideoBriefing(NewsmakerVideoBriefing $videoBriefing): array
    {
        return [
            'id' => $videoBriefing->id,
            'title' => $videoBriefing->title,
            'embed_code' => $videoBriefing->embed_code,
            'backup_video_url' => $videoBriefing->backup_video_url,
            'image' => $videoBriefing->image,
            'image_url' => $videoBriefing->image ? asset($videoBriefing->image) : null,
            'created_at' => optional($videoBriefing->created_at)->toISOString(),
            'updated_at' => optional($videoBriefing->updated_at)->toISOString(),
        ];
    }
}
