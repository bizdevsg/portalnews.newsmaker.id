<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FedRateMeeting;
use App\Models\FedRateSetting;
use App\Observers\FedRateMonitorCacheObserver;
use Carbon\CarbonImmutable;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;

/**
 * Data diinput manual oleh admin (disalin dari investing.com/CME FedWatch).
 * Respons di-cache 5 menit karena filter "meeting_date >= hari ini" harus
 * dihitung ulang tiap kali, tapi FedRateMonitorCacheObserver membersihkan
 * cache ini setiap kali admin menyimpan perubahan, jadi tetap langsung update.
 */
class FedRateMonitorController extends Controller
{
    private const CACHE_KEY = FedRateMonitorCacheObserver::CACHE_KEY;
    private const CACHE_TTL_SECONDS = 300;
    private const MAX_MEETINGS = 8;
    private const NY_TIMEZONE = 'America/New_York';

    public function index(): JsonResponse
    {
        $payload = Cache::remember(self::CACHE_KEY, self::CACHE_TTL_SECONDS, fn () => $this->buildPayload());

        return response()->json(
            $payload,
            200,
            [],
            JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
        );
    }

    private function buildPayload(): array
    {
        $setting = FedRateSetting::current();

        $todayNy = CarbonImmutable::now(self::NY_TIMEZONE)->toDateString();

        $meetings = FedRateMeeting::query()
            ->where('is_active', true)
            ->whereDate('meeting_date', '>=', $todayNy)
            ->with('rows')
            ->orderBy('meeting_date')
            ->limit(self::MAX_MEETINGS)
            ->get();

        if ($setting === null && $meetings->isEmpty()) {
            return [
                'status' => 'success',
                'message' => 'Belum ada data Fed Rate Monitor.',
                'data' => null,
                'generated_at' => now()->toISOString(),
            ];
        }

        return [
            'status' => 'success',
            'message' => 'Data Fed Rate Monitor berhasil diambil',
            'data' => [
                'current_target' => $setting ? [
                    'lower' => $setting->current_target_lower,
                    'upper' => $setting->current_target_upper,
                ] : null,
                'updated_at' => optional($setting?->source_updated_at)->toISOString(),
                'meetings' => $meetings->map($this->mapMeeting(...))->values(),
            ],
            'generated_at' => now()->toISOString(),
        ];
    }

    private function mapMeeting(FedRateMeeting $meeting): array
    {
        return [
            'id' => $meeting->id,
            'meeting_date' => $meeting->meeting_date->format('Y-m-d'),
            'meeting_time' => substr((string) $meeting->meeting_time, 0, 5),
            'decision_at' => $this->decisionAt($meeting)->toISOString(),
            'future_price' => $meeting->future_price,
            'rows' => $meeting->rows->map(fn ($row) => [
                'target_lower' => $row->target_lower,
                'target_upper' => $row->target_upper,
                'current_probability' => $row->current_probability,
                'previous_day_probability' => $row->previous_day_probability,
                'previous_week_probability' => $row->previous_week_probability,
            ])->values(),
        ];
    }

    private function decisionAt(FedRateMeeting $meeting): CarbonImmutable
    {
        $dateTime = $meeting->meeting_date->format('Y-m-d') . ' ' . substr((string) $meeting->meeting_time, 0, 8);

        return CarbonImmutable::createFromFormat('Y-m-d H:i:s', $dateTime, self::NY_TIMEZONE)->utc();
    }
}
