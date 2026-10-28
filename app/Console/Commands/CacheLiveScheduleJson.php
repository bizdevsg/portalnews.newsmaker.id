<?php

namespace App\Console\Commands;

use App\Models\NewsmakerLiveSchedule;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class CacheLiveScheduleJson extends Command
{
    protected $signature = 'live-schedule:cache-json {--path=cache/live-schedule.json : Storage path for the JSON file}';

    protected $description = 'Generate Jadwal Live Newsmaker JSON cache for API responses.';

    public function handle(): int
    {
        if (!Schema::hasTable((new NewsmakerLiveSchedule())->getTable())) {
            $this->error('Jadwal Live table is not available.');

            return self::FAILURE;
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
        if ($json === false) {
            $this->error('Failed to encode JSON.');

            return self::FAILURE;
        }

        $path = $this->option('path');
        Storage::disk('local')->put($path, $json);

        $this->info('Jadwal Live JSON cache saved to storage/app/' . $path);

        return self::SUCCESS;
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
