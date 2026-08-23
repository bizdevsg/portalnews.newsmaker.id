<?php

namespace App\Console\Commands;

use App\Models\NewsmakerMainCategory;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class CacheNewsmakerJson extends Command
{
    protected $signature = 'newsmaker:cache-json {--path=cache/newsmaker.json : Storage path for the JSON file}';

    protected $description = 'Generate Newsmaker JSON cache for API responses.';

    /**
     * Articles are queried straight from the DB by the API controller
     * (paginated, content-less for list views) instead of being embedded
     * here — with 25k+ archived articles, dumping full bilingual HTML into
     * a single JSON blob made every API request decode 100MB+ of JSON.
     */
    public function handle(): int
    {
        $categories = NewsmakerMainCategory::query()
            ->withCount('articles')
            ->latest()
            ->get()
            ->map(fn (NewsmakerMainCategory $category) => [
                'id' => $category->id,
                'name' => $category->name,
                'slug' => $category->slug,
                'articles_count' => $category->articles_count ?? 0,
                'created_at' => optional($category->created_at)->toISOString(),
                'updated_at' => optional($category->updated_at)->toISOString(),
            ])
            ->values();

        $payload = [
            'status' => 'success',
            'categories' => $categories,
            'generated_at' => now()->toISOString(),
        ];

        $json = json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        if ($json === false) {
            $this->error('Failed to encode JSON.');

            return self::FAILURE;
        }

        $path = $this->option('path');
        $written = Storage::disk('local')->put($path, $json);

        if (! $written) {
            $this->error('Failed to write Newsmaker JSON cache to storage/app/'.$path.' (check file permissions/ownership).');

            return self::FAILURE;
        }

        $this->info('Newsmaker JSON cache saved to storage/app/'.$path);

        return self::SUCCESS;
    }
}
