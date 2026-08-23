<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\NewsmakerArticle;
use App\Models\NewsmakerMainCategory;
use App\Services\ApiPayloadCacheService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NewsmakerArticleController extends Controller
{
    private const PER_PAGE = 20;
    private const CACHE_PATH = 'cache/newsmaker.json';

    private const LIST_COLUMNS = [
        'id', 'slug', 'main_category_id', 'sub_category_id', 'author_id',
        'image', 'title_id', 'title_en', 'notif', 'author', 'author_initial',
        'source', 'created_at', 'updated_at',
    ];

    public function __construct(
        private readonly ApiPayloadCacheService $cacheService
    ) {
    }

    public function categories(): JsonResponse
    {
        $payload = $this->cacheService->getPayload(self::CACHE_PATH);
        if ($payload === null) {
            return $this->cacheUnavailableResponse();
        }

        return response()->json(
            [
                'status' => 'success',
                'data' => collect($payload['categories'] ?? [])->values(),
            ],
            200,
            [],
            JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
        );
    }

    public function index(Request $request): JsonResponse
    {
        $articles = NewsmakerArticle::query()
            ->select(self::LIST_COLUMNS)
            ->with(['mainCategory:id,name,slug', 'authorUser:id,name'])
            ->latest()
            ->paginate(self::PER_PAGE, ['*'], 'page', $this->currentPage($request));

        return response()->json(
            [
                'status' => 'success',
                'data' => $articles->getCollection()->map($this->mapListItem(...))->values(),
                'meta' => [
                    'pagination' => $this->buildPaginationMeta($articles),
                ],
            ],
            200,
            [],
            JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
        );
    }

    public function byCategory(Request $request, string $slug): JsonResponse
    {
        $category = NewsmakerMainCategory::query()->where('slug', $slug)->first();

        if ($category === null && ctype_digit($slug)) {
            $article = NewsmakerArticle::query()
                ->with(['mainCategory:id,name,slug', 'authorUser:id,name'])
                ->find((int) $slug);

            if ($article !== null) {
                return response()->json(
                    [
                        'status' => 'success',
                        'data' => $this->mapDetailItem($article),
                    ],
                    200,
                    [],
                    JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
                );
            }
        }

        if ($category === null) {
            return response()->json(
                [
                    'status' => 'error',
                    'message' => 'Kategori Newsmaker 23 tidak ditemukan.',
                ],
                404,
                [],
                JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
            );
        }

        $articles = NewsmakerArticle::query()
            ->select(self::LIST_COLUMNS)
            ->where('main_category_id', $category->id)
            ->with(['mainCategory:id,name,slug', 'authorUser:id,name'])
            ->latest()
            ->paginate(self::PER_PAGE, ['*'], 'page', $this->currentPage($request));

        return response()->json(
            [
                'status' => 'success',
                'category' => [
                    'id' => $category->id,
                    'name' => $category->name,
                    'slug' => $category->slug,
                ],
                'data' => $articles->getCollection()->map($this->mapListItem(...))->values(),
                'meta' => [
                    'pagination' => $this->buildPaginationMeta($articles),
                ],
            ],
            200,
            [],
            JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
        );
    }

    public function show(string $slug): JsonResponse
    {
        $article = NewsmakerArticle::query()
            ->with(['mainCategory:id,name,slug', 'authorUser:id,name'])
            ->where('slug', $slug)
            ->first();

        if ($article === null) {
            return response()->json(
                [
                    'status' => 'error',
                    'message' => 'Berita Newsmaker 23 tidak ditemukan.',
                ],
                404,
                [],
                JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
            );
        }

        return response()->json(
            [
                'status' => 'success',
                'data' => $this->mapDetailItem($article),
            ],
            200,
            [],
            JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
        );
    }

    /** List views (index/byCategory) omit content_id/content_en — that's what made the old JSON-cache approach balloon to 100MB+ once the archive was imported. */
    private function mapListItem(NewsmakerArticle $article): array
    {
        return [
            'id' => $article->id,
            'slug' => $article->slug,
            'main_category_id' => $article->main_category_id,
            'sub_category_id' => $article->sub_category_id,
            'author_id' => $article->author_id,
            'image' => $article->image,
            'image_url' => $article->image ? asset($article->image) : null,
            'title_id' => $article->title_id,
            'title_en' => $article->title_en,
            'notif' => (bool) $article->notif,
            'author' => $article->author_initial ?? $article->author ?? $article->authorUser?->name,
            'author_initial' => $article->author_initial,
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
        ];
    }

    private function mapDetailItem(NewsmakerArticle $article): array
    {
        return $this->mapListItem($article) + [
            'content_id' => $article->content_id,
            'content_en' => $article->content_en,
        ];
    }

    private function currentPage(Request $request): int
    {
        return max((int) $request->query('page', 1), 1);
    }

    private function cacheUnavailableResponse(): JsonResponse
    {
        return response()->json(
            ['status' => 'error', 'message' => 'Cache Newsmaker belum tersedia.'],
            503,
            [],
            JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
        );
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
}
