<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\ApiPayloadCacheService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class PasarIndonesiaArticleController extends Controller
{
    private const DEFAULT_PER_PAGE = 20;
    private const MAX_PER_PAGE = 100;
    private const CACHE_PATH = 'cache/pasar-indonesia.json';

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
                'type' => 'berita',
                'data' => collect($payload['categories'] ?? [])->values(),
            ],
            200,
            [],
            JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
        );
    }

    public function berita(Request $request): JsonResponse
    {
        return $this->listByType($request, 'berita');
    }

    public function beritaShow(string $slug): JsonResponse
    {
        return $this->showByType($slug, 'berita', 'Berita Pasar Indonesia tidak ditemukan.');
    }

    public function analisis(Request $request): JsonResponse
    {
        return $this->listByType($request, 'analisis');
    }

    public function analisisShow(string $slug): JsonResponse
    {
        return $this->showByType($slug, 'analisis', 'Analisis Pasar Indonesia tidak ditemukan.');
    }

    private function listByType(Request $request, string $type): JsonResponse
    {
        $payload = $this->cacheService->getPayload(self::CACHE_PATH);
        if ($payload === null) {
            return $this->cacheUnavailableResponse();
        }

        $perPage = $this->resolvePerPage($request->query('per_page'));
        $requestedCategory = $request->query('category');
        $categories = collect($payload['categories'] ?? []);
        $allowedCategories = $categories->pluck('name', 'slug')->toArray();
        $items = collect($payload[$type] ?? []);

        if ($type === 'berita' && is_string($requestedCategory) && $requestedCategory !== '' && !array_key_exists($requestedCategory, $allowedCategories)) {
            return response()->json(
                [
                    'status' => 'error',
                    'message' => 'Kategori berita Pasar Indonesia tidak valid.',
                    'available_categories' => array_keys($allowedCategories),
                ],
                422,
                [],
                JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
            );
        }

        if ($type === 'berita' && is_string($requestedCategory) && $requestedCategory !== '') {
            $items = $items
                ->filter(fn (array $item) => ($item['category'] ?? null) === $requestedCategory)
                ->values();
        }

        $items = $this->paginateData($items->all(), $request, $perPage);

        return response()->json(
            [
                'status' => 'success',
                'type' => $type,
                'data' => $items->getCollection()->values(),
                'meta' => [
                    'filters' => [
                        'category' => $type === 'berita' ? $requestedCategory : null,
                    ],
                    'available_categories' => $type === 'berita'
                        ? $categories->map(fn (array $category) => ['value' => $category['slug'], 'label' => $category['name']])->values()
                        : [],
                    'pagination' => $this->buildPaginationMeta($items),
                ],
            ],
            200,
            [],
            JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
        );
    }

    private function showByType(string $slug, string $type, string $notFoundMessage): JsonResponse
    {
        $payload = $this->cacheService->getPayload(self::CACHE_PATH);
        if ($payload === null) {
            return $this->cacheUnavailableResponse();
        }

        $item = collect($payload[$type] ?? [])->firstWhere('slug', $slug);

        if ($item === null) {
            return response()->json(
                [
                    'status' => 'error',
                    'message' => $notFoundMessage,
                ],
                404,
                [],
                JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
            );
        }

        return response()->json(
            [
                'status' => 'success',
                'type' => $type,
                'data' => $item,
            ],
            200,
            [],
            JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
        );
    }

    private function paginateData(array $items, Request $request, int $perPage): LengthAwarePaginator
    {
        $page = max((int) $request->query('page', 1), 1);
        $items = array_values($items);

        return new LengthAwarePaginator(
            array_values(array_slice($items, ($page - 1) * $perPage, $perPage)),
            count($items),
            $perPage,
            $page,
            [
                'path' => $request->url(),
                'query' => $request->query(),
            ]
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

    private function resolvePerPage(mixed $value): int
    {
        $perPage = filter_var($value, FILTER_VALIDATE_INT, [
            'options' => ['min_range' => 1, 'max_range' => self::MAX_PER_PAGE],
        ]);

        return $perPage !== false ? $perPage : self::DEFAULT_PER_PAGE;
    }

    private function cacheUnavailableResponse(): JsonResponse
    {
        return response()->json(
            ['status' => 'error', 'message' => 'Cache Pasar Indonesia belum tersedia.'],
            503,
            [],
            JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
        );
    }
}
