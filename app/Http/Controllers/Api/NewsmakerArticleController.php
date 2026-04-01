<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\NewsmakerArticle;
use App\Models\NewsmakerMainCategory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class NewsmakerArticleController extends Controller
{
    private const PER_PAGE = 20;

    public function categories(): JsonResponse
    {
        $categories = NewsmakerMainCategory::query()
            ->withCount('articles')
            ->latest()
            ->get();

        return response()->json(
            [
                'status' => 'success',
                'data' => $categories->map(
                    fn (NewsmakerMainCategory $category) => $this->transformCategory($category)
                )->values(),
            ],
            200,
            [],
            JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
        );
    }

    public function index(Request $request): JsonResponse
    {
        $articles = NewsmakerArticle::query()
            ->with([
                'mainCategory:id,name,slug',
                'subCategory:id,main_category_id,name,slug',
            ])
            ->latest()
            ->paginate(self::PER_PAGE)
            ->appends($request->query());

        return response()->json(
            [
                'status' => 'success',
                'data' => $articles->getCollection()
                    ->map(fn (NewsmakerArticle $article) => $this->transformArticle($article))
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
        $category = NewsmakerMainCategory::query()
            ->withCount('articles')
            ->where('slug', $slug)
            ->first();

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
            ->with([
                'mainCategory:id,name,slug',
                'subCategory:id,main_category_id,name,slug',
            ])
            ->where('main_category_id', $category->id)
            ->latest()
            ->paginate(self::PER_PAGE)
            ->appends($request->query());

        return response()->json(
            [
                'status' => 'success',
                'category' => $this->transformCategory($category),
                'data' => $articles->getCollection()
                    ->map(fn (NewsmakerArticle $article) => $this->transformArticle($article))
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
        $article = NewsmakerArticle::query()
            ->with([
                'mainCategory:id,name,slug',
                'subCategory:id,main_category_id,name,slug',
            ])
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
                'data' => $this->transformArticle($article),
            ],
            200,
            [],
            JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
        );
    }

    private function transformCategory(NewsmakerMainCategory $category): array
    {
        return [
            'id' => $category->id,
            'name' => $category->name,
            'slug' => $category->slug,
            'articles_count' => $category->articles_count ?? 0,
            'created_at' => optional($category->created_at)->toISOString(),
            'updated_at' => optional($category->updated_at)->toISOString(),
        ];
    }

    private function transformArticle(NewsmakerArticle $article): array
    {
        return [
            'id' => $article->id,
            'slug' => $article->slug,
            'main_category_id' => $article->main_category_id,
            'sub_category_id' => $article->sub_category_id,
            'image' => $article->image,
            'image_url' => $article->image ? asset($article->image) : null,
            'title_id' => $article->title_id,
            'title_en' => $article->title_en,
            'content_id' => $article->content_id,
            'content_en' => $article->content_en,
            'author' => $article->author,
            'source' => $article->source,
            'main_category' => $article->mainCategory ? [
                'id' => $article->mainCategory->id,
                'name' => $article->mainCategory->name,
                'slug' => $article->mainCategory->slug,
            ] : null,
            'sub_category' => $article->subCategory ? [
                'id' => $article->subCategory->id,
                'main_category_id' => $article->subCategory->main_category_id,
                'name' => $article->subCategory->name,
                'slug' => $article->subCategory->slug,
            ] : null,
            'created_at' => optional($article->created_at)->toISOString(),
            'updated_at' => optional($article->updated_at)->toISOString(),
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
}
