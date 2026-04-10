<?php

namespace App\Console\Commands;

use App\Models\PasarIndonesiaArticle;
use App\Models\PasarIndonesiaCategory;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class CachePasarIndonesiaJson extends Command
{
    protected $signature = 'pasar-indonesia:cache-json {--path=cache/pasar-indonesia.json : Storage path for the JSON file}';

    protected $description = 'Generate Pasar Indonesia JSON cache for API responses.';

    public function handle(): int
    {
        $categories = $this->buildCategories();

        $berita = PasarIndonesiaArticle::query()
            ->with(['author:id,name,email', 'categoryItem:id,name,slug'])
            ->where('type', 'berita')
            ->latest()
            ->get()
            ->map(fn (PasarIndonesiaArticle $item) => $this->transformArticle($item))
            ->values();

        $analisis = PasarIndonesiaArticle::query()
            ->with(['author:id,name,email'])
            ->where('type', 'analisis')
            ->latest()
            ->get()
            ->map(fn (PasarIndonesiaArticle $item) => $this->transformArticle($item))
            ->values();

        $payload = [
            'status' => 'success',
            'categories' => $categories,
            'berita' => $berita,
            'analisis' => $analisis,
            'generated_at' => now()->toISOString(),
        ];

        $json = json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        if ($json === false) {
            $this->error('Failed to encode JSON.');

            return self::FAILURE;
        }

        $path = $this->option('path');
        Storage::disk('local')->put($path, $json);

        $this->info('Pasar Indonesia JSON cache saved to storage/app/'.$path);

        return self::SUCCESS;
    }

    private function buildCategories()
    {
        if (Schema::hasTable('pasar_indonesia_categories')) {
            return PasarIndonesiaCategory::query()
                ->withCount('articles')
                ->orderBy('name')
                ->get()
                ->map(fn (PasarIndonesiaCategory $category) => [
                    'id' => $category->id,
                    'name' => $category->name,
                    'slug' => $category->slug,
                    'articles_count' => $category->articles_count ?? 0,
                    'created_at' => optional($category->created_at)->toISOString(),
                    'updated_at' => optional($category->updated_at)->toISOString(),
                ])
                ->values();
        }

        $articlesCount = PasarIndonesiaArticle::query()
            ->where('type', 'berita')
            ->selectRaw('category, COUNT(*) as aggregate')
            ->groupBy('category')
            ->pluck('aggregate', 'category');

        return collect(PasarIndonesiaArticle::beritaCategoryOptions())
            ->map(fn (string $name, string $slug) => [
                'id' => null,
                'name' => $name,
                'slug' => $slug,
                'articles_count' => (int) ($articlesCount[$slug] ?? 0),
                'created_at' => null,
                'updated_at' => null,
            ])
            ->values();
    }

    private function transformArticle(PasarIndonesiaArticle $item): array
    {
        return [
            'id' => $item->id,
            'type' => $item->type,
            'slug' => $item->slug,
            'image' => $item->image,
            'image_url' => $item->image ? asset($item->image) : null,
            'title_id' => $item->title_id,
            'title_en' => $item->title_en,
            'content_id' => $item->content_id,
            'content_en' => $item->content_en,
            'category' => $item->category,
            'category_label' => $item->category_label,
            'source' => $item->source,
            'author' => $item->author ? [
                'id' => $item->author->id,
                'name' => $item->author->name,
                'email' => $item->author->email,
            ] : null,
            'created_at' => optional($item->created_at)->toISOString(),
            'updated_at' => optional($item->updated_at)->toISOString(),
        ];
    }
}
