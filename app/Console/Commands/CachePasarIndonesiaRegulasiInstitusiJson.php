<?php

namespace App\Console\Commands;

use App\Models\PasarIndonesiaRegulasiInstitusiArticle;
use App\Models\PasarIndonesiaRegulasiInstitusiCategory;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CachePasarIndonesiaRegulasiInstitusiJson extends Command
{
    private const DEFAULT_CATEGORIES = [
        'umum' => 'Umum',
    ];

    protected $signature = 'pasar-indonesia-regulasi:cache-json {--path=cache/pasar-indonesia-regulasi-institusi.json : Storage path for the JSON file}';

    protected $description = 'Generate Pasar Indonesia Regulasi Institusi JSON cache for API responses.';

    public function handle(): int
    {
        $categories = $this->buildCategories();
        $categoryOptions = collect($categories)->pluck('name', 'slug')->toArray();

        $articles = PasarIndonesiaRegulasiInstitusiArticle::query()
            ->with(['author:id,name,email', 'categoryItem:id,name,slug'])
            ->latest()
            ->get()
            ->map(
                fn (PasarIndonesiaRegulasiInstitusiArticle $item) => $this->transformArticle($item, $categoryOptions)
            )
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

        $this->info('Pasar Indonesia Regulasi Institusi JSON cache saved to storage/app/'.$path);

        return self::SUCCESS;
    }

    private function buildCategories()
    {
        if (Schema::hasTable('pasar_indonesia_regulasi_institusi_categories')) {
            $categories = PasarIndonesiaRegulasiInstitusiCategory::query()
                ->withCount('articles')
                ->orderBy('name')
                ->get()
                ->map(fn (PasarIndonesiaRegulasiInstitusiCategory $category) => [
                    'id' => $category->id,
                    'name' => $category->name,
                    'slug' => $category->slug,
                    'articles_count' => $category->articles_count ?? 0,
                    'created_at' => optional($category->created_at)->toISOString(),
                    'updated_at' => optional($category->updated_at)->toISOString(),
                ])
                ->values();

            if ($categories->isNotEmpty()) {
                return $categories;
            }
        }

        $articlesCount = PasarIndonesiaRegulasiInstitusiArticle::query()
            ->selectRaw('category, COUNT(*) as aggregate')
            ->groupBy('category')
            ->pluck('aggregate', 'category');

        return collect(self::DEFAULT_CATEGORIES)
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

    private function transformArticle(PasarIndonesiaRegulasiInstitusiArticle $item, array $categoryOptions): array
    {
        return [
            'id' => $item->id,
            'type' => 'regulasi-institusi',
            'slug' => $item->slug,
            'image' => $item->image,
            'image_url' => $item->image ? asset($item->image) : null,
            'title_id' => $item->title_id,
            'title_en' => $item->title_en,
            'notif' => (bool) $item->notif,
            'content_id' => $item->content_id,
            'content_en' => $item->content_en,
            'category' => $item->category,
            'category_label' => $item->categoryItem?->name
                ?? $categoryOptions[$item->category]
                ?? ($item->category ? Str::of($item->category)->replace('-', ' ')->title()->toString() : null),
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
