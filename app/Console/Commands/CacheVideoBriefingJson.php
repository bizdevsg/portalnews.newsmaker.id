<?php

namespace App\Console\Commands;

use App\Models\NewsmakerVideoBriefing;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class CacheVideoBriefingJson extends Command
{
    protected $signature = 'video-briefing:cache-json {--path=cache/video-briefing.json : Storage path for the JSON file}';

    protected $description = 'Generate Video Briefing JSON cache for API responses.';

    public function handle(): int
    {
        if (!Schema::hasTable((new NewsmakerVideoBriefing())->getTable())) {
            $this->error('Video Briefing table is not available.');

            return self::FAILURE;
        }

        $items = NewsmakerVideoBriefing::query()
            ->orderByDesc('created_at')
            ->get()
            ->map(fn (NewsmakerVideoBriefing $videoBriefing) => $this->transformVideoBriefing($videoBriefing))
            ->values();

        $payload = [
            'status' => 'success',
            'message' => 'Data Video Briefing berhasil diambil',
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

        $this->info('Video Briefing JSON cache saved to storage/app/' . $path);

        return self::SUCCESS;
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
