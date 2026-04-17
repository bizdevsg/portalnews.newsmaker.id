<?php

namespace App\Console\Commands;

use App\Models\NewsmakerArticle;
use App\Models\NewsmakerMainCategory;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class CacheNewsmakerJson extends Command
{
    protected $signature = 'newsmaker:cache-json {--path=cache/newsmaker.json : Storage path for the JSON file}';

    protected $description = 'Generate Newsmaker JSON cache for API responses.';

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

        $articles = NewsmakerArticle::query()
            ->with([
                'mainCategory:id,name,slug',
                'authorUser:id,name',
            ])
            ->latest()
            ->get()
            ->map(fn (NewsmakerArticle $article) => [
                'id' => $article->id,
                'slug' => $article->slug,
                'main_category_id' => $article->main_category_id,
                'sub_category_id' => $article->sub_category_id,
                'author_id' => $article->author_id,
                'image' => $article->image,
                'image_url' => $article->image ? asset($article->image) : null,
                'title_id' => $article->title_id,
                'title_en' => $article->title_en,
                'content_id' => $article->content_id,
                'content_en' => $article->content_en,
                'author' => $article->authorUser?->name ?? $article->author,
                'author_user' => $article->authorUser ? [
                    'id' => $article->authorUser->id,
                    'name' => $article->authorUser->name,
                ] : null,
                'source' => $article->source,
                'main_category' => $article->mainCategory ? [
                    'id' => $article->mainCategory->id,
                    'name' => $article->mainCategory->name,
                    'slug' => $article->mainCategory->slug,
                ] : null,
                'created_at' => optional($article->created_at)->toISOString(),
                'updated_at' => optional($article->updated_at)->toISOString(),
            ])
            ->values();

        $payload = [
            'status' => 'success',
            'categories' => $categories,
            'articles' => $articles,
            'generated_at' => now()->toISOString(),
        ];

        $json = json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        if ($json === false) {
            $this->error('Failed to encode JSON.');

            return self::FAILURE;
        }

        $path = $this->option('path');
        Storage::disk('local')->put($path, $json);

        $this->info('Newsmaker JSON cache saved to storage/app/'.$path);

        return self::SUCCESS;
    }
}
