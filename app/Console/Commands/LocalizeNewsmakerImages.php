<?php

namespace App\Console\Commands;

use App\Models\NewsmakerArticle;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class LocalizeNewsmakerImages extends Command
{
    protected $signature = 'newsmaker:localize-images
        {--source= : Only process images whose URL starts with this prefix (default: https://www.newsmaker.id/)}
        {--limit= : Only process this many distinct images (for testing)}
        {--timeout=15 : Per-request timeout in seconds}
        {--retries=2 : Retry attempts per image}';

    protected $description = 'Download externally hotlinked newsmaker_articles images and re-host them under public/uploads/newsmaker23/archive.';

    private const TARGET_DIR = 'newsmaker23/archive';

    public function handle(): int
    {
        $sourcePrefix = $this->option('source') ?: 'https://www.newsmaker.id/';
        $timeout = (int) $this->option('timeout');
        $retries = (int) $this->option('retries');
        $limit = $this->option('limit') ? (int) $this->option('limit') : null;

        $disk = Storage::disk('public');
        $disk->makeDirectory(self::TARGET_DIR);

        $urls = NewsmakerArticle::query()
            ->where('image', 'like', $sourcePrefix.'%')
            ->distinct()
            ->pluck('image');

        if ($limit) {
            $urls = $urls->take($limit);
        }

        $total = $urls->count();
        $this->info("Found {$total} distinct external image URL(s) to process.");

        $ok = 0;
        $failed = 0;
        $bar = $this->output->createProgressBar($total);

        foreach ($urls as $url) {
            $localPath = $this->downloadOne($url, $disk, $timeout, $retries);

            if ($localPath === null) {
                $failed++;
                $bar->advance();
                continue;
            }

            NewsmakerArticle::query()
                ->where('image', $url)
                ->update(['image' => $localPath]);

            $ok++;
            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);
        $this->info("Done. Localized: {$ok}. Failed/unreachable: {$failed}.");

        if ($failed > 0) {
            $this->warn("{$failed} image(s) could not be downloaded (source unreachable or 404) — those articles keep their original external URL untouched.");
        }

        return self::SUCCESS;
    }

    private function downloadOne(string $url, $disk, int $timeout, int $retries): ?string
    {
        $filename = $this->safeFilename($url);
        $relativePath = self::TARGET_DIR.'/'.$filename;

        if ($disk->exists($relativePath)) {
            return 'uploads/'.$relativePath;
        }

        for ($attempt = 1; $attempt <= $retries + 1; $attempt++) {
            try {
                $response = Http::timeout($timeout)
                    ->withHeaders(['User-Agent' => 'Mozilla/5.0 (compatible; NewsmakerImageArchiver/1.0)'])
                    ->get($url);

                if ($response->successful() && strlen($response->body()) > 0) {
                    $disk->put($relativePath, $response->body());

                    return 'uploads/'.$relativePath;
                }
            } catch (\Throwable $e) {
                // swallow and retry; final failure just leaves the original URL in place
            }
        }

        return null;
    }

    private function safeFilename(string $url): string
    {
        $path = parse_url($url, PHP_URL_PATH) ?: $url;
        $ext = pathinfo($path, PATHINFO_EXTENSION);
        $ext = $ext && strlen($ext) <= 5 ? '.'.preg_replace('/[^a-zA-Z0-9]/', '', $ext) : '.jpg';

        return substr(hash('sha1', $url), 0, 24).$ext;
    }
}
