<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\NewsmakerLiveSchedule;
use App\Services\ApiPayloadCacheService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class NewsmakerLiveScheduleController extends Controller
{
    private const CACHE_PATH = 'cache/live-schedule.json';

    public function __construct(
        private readonly ApiPayloadCacheService $cacheService
    ) {
    }

    public function index(Request $request): JsonResponse
    {
        $payload = $this->cacheService->getPayload(self::CACHE_PATH);

        if ($payload === null) {
            $payload = $this->buildAndCachePayload();
        }

        if ($payload === null) {
            return response()->json(
                [
                    'status' => 'error',
                    'message' => 'Tabel Jadwal Live belum tersedia. Jalankan migrasi terlebih dahulu.',
                ],
                503,
                [],
                JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
            );
        }

        $data = collect($payload['data'] ?? []);

        // Hanya tampilkan yang aktif ke publik, kecuali diminta eksplisit via ?all=1 (dipakai dashboard admin).
        if (!$request->boolean('all')) {
            $data = $data->filter(fn ($item) => (bool) ($item['is_active'] ?? false))->values();
        }

        if ($request->filled('day')) {
            $day = $request->string('day')->trim()->value();
            $data = $data->filter(fn ($item) => strcasecmp((string) ($item['day'] ?? ''), $day) === 0)->values();
        }

        return response()->json(
            [
                'status' => 'success',
                'message' => $payload['message'] ?? 'Data Jadwal Live berhasil diambil',
                'data' => $data->values(),
                'meta' => [
                    'total' => $data->count(),
                    'days_order' => NewsmakerLiveSchedule::DAYS,
                ],
                'generated_at' => $this->cacheService->resolveGeneratedAt(self::CACHE_PATH, $payload),
            ],
            200,
            [],
            JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
        );
    }

    private function buildAndCachePayload(): ?array
    {
        if (!Schema::hasTable((new NewsmakerLiveSchedule())->getTable())) {
            return null;
        }

        $days = implode(',', array_map(fn ($d) => "'$d'", NewsmakerLiveSchedule::DAYS));

        $items = NewsmakerLiveSchedule::query()
            ->orderByRaw("FIELD(day, {$days})")
            ->orderBy('sort_order')
            ->orderBy('start_time')
            ->get()
            ->map(fn (NewsmakerLiveSchedule $schedule) => $this->transformSchedule($schedule))
            ->values();

        $payload = [
            'status' => 'success',
            'message' => 'Data Jadwal Live berhasil diambil',
            'data' => $items,
            'generated_at' => now()->toISOString(),
        ];

        $json = json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        if ($json !== false) {
            Storage::disk('local')->put(self::CACHE_PATH, $json);
        }

        return json_decode($json ?: '', true) ?: $payload;
    }

    private function transformSchedule(NewsmakerLiveSchedule $schedule): array
    {
        return [
            'id' => $schedule->id,
            'title' => $schedule->title,
            'day' => $schedule->day,
            'start_time' => $schedule->start_time ? substr((string) $schedule->start_time, 0, 5) : null,
            'end_time' => $schedule->end_time ? substr((string) $schedule->end_time, 0, 5) : null,
            'host' => $schedule->host,
            'tiktok_profile_url' => $schedule->tiktok_profile_url,
            'link_url' => $schedule->link_url,
            'description' => $schedule->description,
            'image' => $schedule->image,
            'image_url' => $schedule->image ? asset($schedule->image) : null,
            'is_active' => (bool) $schedule->is_active,
            'sort_order' => $schedule->sort_order,
            'created_at' => optional($schedule->created_at)->toISOString(),
            'updated_at' => optional($schedule->updated_at)->toISOString(),
        ];
    }
}
