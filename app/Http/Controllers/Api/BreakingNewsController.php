<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BreakingNewsCategory;
use App\Models\NewsmakerBreakingNews;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Mirip NewsmakerArticleController (API), tapi untuk Breaking News sendiri.
 * Query langsung ke DB tanpa cache JSON - breaking news perlu selalu
 * up to date, bukan ketunda sampai cache-nya di-refresh.
 */
class BreakingNewsController extends Controller
{
    private const PER_PAGE = 20;

    private const LIST_COLUMNS = [
        'id', 'slug', 'breaking_news_category_id', 'author_id',
        'image', 'title_id', 'title_en', 'notif', 'author', 'author_initial',
        'source', 'start_time', 'end_time', 'created_at', 'updated_at',
    ];

    public function categories(): JsonResponse
    {
        $categories = BreakingNewsCategory::withCount('breakingNews')
            ->orderBy('name')
            ->get()
            ->map(fn (BreakingNewsCategory $category) => [
                'id' => $category->id,
                'name' => $category->name,
                'slug' => $category->slug,
                'articles_count' => $category->breaking_news_count,
            ])
            ->values();

        return response()->json(
            [
                'status' => 'success',
                'data' => $categories,
            ],
            200,
            [],
            JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
        );
    }

    public function index(Request $request): JsonResponse
    {
        $items = NewsmakerBreakingNews::query()
            ->select(self::LIST_COLUMNS)
            ->with(['breakingNewsCategory:id,name,slug', 'authorUser:id,name'])
            ->latest()
            ->paginate(self::PER_PAGE, ['*'], 'page', $this->currentPage($request));

        return response()->json(
            [
                'status' => 'success',
                'data' => $items->getCollection()->map($this->mapListItem(...))->values(),
                'meta' => [
                    'pagination' => $this->buildPaginationMeta($items),
                ],
            ],
            200,
            [],
            JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
        );
    }

    public function byCategory(Request $request, string $slug): JsonResponse
    {
        $category = BreakingNewsCategory::query()->where('slug', $slug)->first();

        if ($category === null) {
            return response()->json(
                [
                    'status' => 'error',
                    'message' => 'Kategori Breaking News tidak ditemukan.',
                ],
                404,
                [],
                JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
            );
        }

        $items = NewsmakerBreakingNews::query()
            ->select(self::LIST_COLUMNS)
            ->where('breaking_news_category_id', $category->id)
            ->with(['breakingNewsCategory:id,name,slug', 'authorUser:id,name'])
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
                'data' => $items->getCollection()->map($this->mapListItem(...))->values(),
                'meta' => [
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
        $item = NewsmakerBreakingNews::query()
            ->with(['breakingNewsCategory:id,name,slug', 'authorUser:id,name'])
            ->where('slug', $slug)
            ->first();

        if ($item === null) {
            return response()->json(
                [
                    'status' => 'error',
                    'message' => 'Breaking News tidak ditemukan.',
                ],
                404,
                [],
                JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
            );
        }

        return response()->json(
            [
                'status' => 'success',
                'data' => $this->mapDetailItem($item),
            ],
            200,
            [],
            JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
        );
    }

    private function mapListItem(NewsmakerBreakingNews $item): array
    {
        return [
            'id' => $item->id,
            'slug' => $item->slug,
            'breaking_news_category_id' => $item->breaking_news_category_id,
            'author_id' => $item->author_id,
            'image' => $item->image,
            'image_url' => $item->image ? asset($item->image) : null,
            'title_id' => $item->title_id,
            'title_en' => $item->title_en,
            'notif' => (bool) $item->notif,
            'author' => $item->author_initial ?? $item->author ?? $item->authorUser?->name,
            'author_initial' => $item->author_initial,
            'author_user' => $item->authorUser ? [
                'id' => $item->authorUser->id,
                'name' => $item->authorUser->name,
            ] : null,
            'source' => $item->source,
            'start_time' => $item->start_time ? substr((string) $item->start_time, 0, 5) : null,
            'end_time' => $item->end_time ? substr((string) $item->end_time, 0, 5) : null,
            'breaking_news_category' => $item->breakingNewsCategory ? [
                'id' => $item->breakingNewsCategory->id,
                'name' => $item->breakingNewsCategory->name,
                'slug' => $item->breakingNewsCategory->slug,
            ] : null,
            'created_at' => optional($item->created_at)->toISOString(),
            'updated_at' => optional($item->updated_at)->toISOString(),
        ];
    }

    private function mapDetailItem(NewsmakerBreakingNews $item): array
    {
        return $this->mapListItem($item) + [
            'content_id' => $item->content_id,
            'content_en' => $item->content_en,
        ];
    }

    private function currentPage(Request $request): int
    {
        return max((int) $request->query('page', 1), 1);
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
