<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PasarIndonesiaArticle;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class PasarIndonesiaArticleController extends Controller
{
    private const DEFAULT_PER_PAGE = 20;
    private const MAX_PER_PAGE = 100;

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
        $perPage = $this->resolvePerPage($request->query('per_page'));

        $items = PasarIndonesiaArticle::query()
            ->with('author:id,name,email')
            ->where('type', $type)
            ->latest()
            ->paginate($perPage)
            ->appends($request->query());

        return response()->json(
            [
                'status' => 'success',
                'type' => $type,
                'data' => $items->getCollection()
                    ->map(fn (PasarIndonesiaArticle $item) => $this->transformArticle($item))
                    ->values(),
                'meta' => [
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
        $item = PasarIndonesiaArticle::query()
            ->with('author:id,name,email')
            ->where('type', $type)
            ->where('slug', $slug)
            ->first();

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
                'data' => $this->transformArticle($item),
            ],
            200,
            [],
            JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
        );
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
