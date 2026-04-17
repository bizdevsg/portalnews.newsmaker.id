<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\ApiPayloadCacheService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class NewsmakerArticleController extends Controller
{
    private const PER_PAGE = 20;
    private const CACHE_PATH = 'cache/newsmaker.json';

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
        $payload = $this->cacheService->getPayload(self::CACHE_PATH);
        if ($payload === null) {
            return $this->cacheUnavailableResponse();
        }

        $articles = $this->paginateData(
            collect($payload['articles'] ?? [])->all(),
            $request
        );

        return response()->json(
            [
                'status' => 'success',
                'data' => $articles->getCollection()
                    ->map(fn ($article) => is_array($article) ? $this->withoutSubCategory($article) : $article)
                    ->values(),
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
        $payload = $this->cacheService->getPayload(self::CACHE_PATH);
        if ($payload === null) {
            return $this->cacheUnavailableResponse();
        }

        $categories = collect($payload['categories'] ?? []);
        $articles = collect($payload['articles'] ?? []);
        $category = $categories->firstWhere('slug', $slug);

        if ($category === null && ctype_digit($slug)) {
            $article = $articles->firstWhere('id', (int) $slug);

            if ($article !== null) {
                return response()->json(
                    [
                        'status' => 'success',
                        'data' => is_array($article) ? $this->withoutSubCategory($article) : $article,
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

        $articles = $this->paginateData(
            $articles
                ->filter(fn (array $article) => ($article['main_category']['slug'] ?? null) === $slug)
                ->values()
                ->all(),
            $request
        );

        return response()->json(
            [
                'status' => 'success',
                'category' => $category,
                'data' => $articles->getCollection()
                    ->map(fn ($article) => is_array($article) ? $this->withoutSubCategory($article) : $article)
                    ->values(),
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
        $payload = $this->cacheService->getPayload(self::CACHE_PATH);
        if ($payload === null) {
            return $this->cacheUnavailableResponse();
        }

        $article = collect($payload['articles'] ?? [])->firstWhere('slug', $slug);

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
                'data' => is_array($article) ? $this->withoutSubCategory($article) : $article,
            ],
            200,
            [],
            JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
        );
    }

    private function withoutSubCategory(array $article): array
    {
        unset($article['sub_category']);

        return $article;
    }

    private function paginateData(array $items, Request $request): LengthAwarePaginator
    {
        $page = max((int) $request->query('page', 1), 1);
        $items = array_values($items);

        return new LengthAwarePaginator(
            array_values(array_slice($items, ($page - 1) * self::PER_PAGE, self::PER_PAGE)),
            count($items),
            self::PER_PAGE,
            $page,
            [
                'path' => $request->url(),
                'query' => $request->query(),
            ]
        );
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
