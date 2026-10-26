<?php

namespace App\Console\Commands;

use App\Models\Tiktok;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class CacheTiktokJson extends Command
{
    protected $signature = 'tiktok:cache-json {--path=cache/tiktok.json : Storage path for the JSON file}';

    protected $description = 'Generate TikTok JSON cache for API responses.';

    public function handle(): int
    {
        $tiktok = Tiktok::current();

        $payload = [
            'status' => 'success',
            'message' => $tiktok ? 'Data TikTok berhasil diambil' : 'Belum ada video TikTok yang diunggah.',
            'data' => $tiktok ? $this->transformTiktok($tiktok) : null,
            'generated_at' => now()->toISOString(),
        ];

        $json = json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        if ($json === false) {
            $this->error('Failed to encode JSON.');

            return self::FAILURE;
        }

        $path = $this->option('path');
        $written = Storage::disk('local')->put($path, $json);

        if (!$written) {
            $this->error('Failed to write TikTok JSON cache to storage/app/' . $path . ' (check file permissions/ownership).');

            return self::FAILURE;
        }

        $this->info('TikTok JSON cache saved to storage/app/' . $path);

        return self::SUCCESS;
    }

    private function transformTiktok(Tiktok $tiktok): array
    {
        return [
            'id' => $tiktok->id,
            'title' => $tiktok->title,
            'video' => $tiktok->video,
            'video_url' => $tiktok->video ? asset($tiktok->video) : null,
            'created_at' => optional($tiktok->created_at)->toISOString(),
            'updated_at' => optional($tiktok->updated_at)->toISOString(),
        ];
    }
}
