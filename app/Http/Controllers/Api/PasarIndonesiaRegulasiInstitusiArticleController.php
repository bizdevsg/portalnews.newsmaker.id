<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PasarIndonesiaRegulasiInstitusiArticle;
use App\Models\PasarIndonesiaRegulasiInstitusiCategory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class PasarIndonesiaRegulasiInstitusiArticleController extends Controller
{
    private const DEFAULT_PER_PAGE = 20;
    private const MAX_PER_PAGE = 100;
    private const DEFAULT_CATEGORIES = [
        'umum' => 'Umum',
    ];

    public function index(Request $request): JsonResponse
    {
        $perPage = $this->resolvePerPage($request->query('per_page'));
        $requestedCategory = $request->query('category');
        $allowedCategories = $this->categoryOptions();

        if (
            is_string($requestedCategory)
            && $requestedCategory !== ''
            && !array_key_exists($requestedCategory, $allowedCategories)
        ) {
            return response()->json(
                [
                    'status' => 'error',
                    'message' => 'Kategori Regulasi & Institusi tidak valid.',
                    'available_categories' => array_keys($allowedCategories),
                ],
                422,
                [],
                JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
            );
        }

        $items = PasarIndonesiaRegulasiInstitusiArticle::query()
            ->with(['author:id,name,email', 'categoryItem:id,name,slug'])
            ->when(
                is_string($requestedCategory) && $requestedCategory !== '',
                fn ($query) => $query->where('category', $requestedCategory)
            )
            ->latest()
            ->paginate($perPage)
            ->appends($request->query());

        return response()->json(
            [
                'status' => 'success',
                'type' => 'regulasi-institusi',
                'data' => $items->getCollection()
                    ->map(
                        fn (PasarIndonesiaRegulasiInstitusiArticle $item) => $this->transformArticle($item, $allowedCategories)
                    )
                    ->values(),
                'meta' => [
                    'filters' => [
                        'category' => $requestedCategory,
                    ],
                    'available_categories' => collect($allowedCategories)->map(
                        fn (string $label, string $value) => ['value' => $value, 'label' => $label]
                    )->values(),
                    'pagination' => $this->buildPaginationMeta($items),
                ],
            ],
            200,
            [],
            JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
        );
    }

    public function show(string $slug): JsonResponse
    {
        $item = PasarIndonesiaRegulasiInstitusiArticle::query()
            ->with(['author:id,name,email', 'categoryItem:id,name,slug'])
            ->where('slug', $slug)
            ->first();

        if ($item === null) {
            return response()->json(
                [
                    'status' => 'error',
                    'message' => 'Artikel Regulasi & Institusi tidak ditemukan.',
                ],
                404,
                [],
                JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
            );
        }

        return response()->json(
            [
                'status' => 'success',
                'type' => 'regulasi-institusi',
                'data' => $this->transformArticle($item, $this->categoryOptions()),
            ],
            200,
            [],
            JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
        );
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
            'content_id' => $item->content_id,
            'content_en' => $item->content_en,
            'category' => $item->category,
            'category_label' => $this->resolveCategoryLabel($item, $categoryOptions),
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

    private function resolveCategoryLabel(PasarIndonesiaRegulasiInstitusiArticle $item, array $categoryOptions): ?string
    {
        if ($item->category === null) {
            return null;
        }

        if ($item->relationLoaded('categoryItem') && $item->categoryItem) {
            return $item->categoryItem->name;
        }

        return $categoryOptions[$item->category]
            ?? Str::of($item->category)->replace('-', ' ')->title()->toString();
    }

    private function categoryOptions(): array
    {
        if (!Schema::hasTable('pasar_indonesia_regulasi_institusi_categories')) {
            return self::DEFAULT_CATEGORIES;
        }

        $categories = PasarIndonesiaRegulasiInstitusiCategory::query()
            ->orderBy('name')
            ->pluck('name', 'slug')
            ->toArray();

        return $categories !== [] ? $categories : self::DEFAULT_CATEGORIES;
    }

    private function buildPaginationMeta(LengthAwarePaginator $paginator): array
    {
        return [
            'current_page' => $paginator->currentPage(),
            'per_page' => $paginator->perPage(),
            'total' => $paginator->total(),
            'last_page' => $paginator->lastPage(),
            'from' => $paginator->firstItem(),
            'to' => $paginator->lastItem(),
            'has_more_pages' => $paginator->hasMorePages(),
            'prev_page_url' => $paginator->previousPageUrl(),
            'next_page_url' => $paginator->nextPageUrl(),
        ];
    }

    private function resolvePerPage(mixed $value): int
    {
        $perPage = filter_var($value, FILTER_VALIDATE_INT, [
            'options' => ['min_range' => 1, 'max_range' => self::MAX_PER_PAGE],
        ]);

        return $perPage !== false ? $perPage : self::DEFAULT_PER_PAGE;
    }
}
