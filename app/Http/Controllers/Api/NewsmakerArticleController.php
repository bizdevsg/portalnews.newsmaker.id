<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\NewsmakerArticle;
use Illuminate\Http\Request;

class NewsmakerArticleController extends Controller
{
    public function index(Request $request)
    {
        $query = NewsmakerArticle::with(['mainCategory', 'subCategory'])->orderByDesc('id');

        if ($request->filled('main_category_id')) {
            $query->where('main_category_id', $request->query('main_category_id'));
        }

        if ($request->filled('sub_category_id')) {
            $query->where('sub_category_id', $request->query('sub_category_id'));
        }

        $articles = $query->get()->map(function ($item) {
            return [
                'id' => $item->id,
                'main_category_id' => $item->main_category_id,
                'sub_category_id' => $item->sub_category_id,
                'main_category' => $item->mainCategory ? [
                    'id' => $item->mainCategory->id,
                    'name' => $item->mainCategory->name,
                    'slug' => $item->mainCategory->slug,
                ] : null,
                'sub_category' => $item->subCategory ? [
                    'id' => $item->subCategory->id,
                    'name' => $item->subCategory->name,
                    'slug' => $item->subCategory->slug,
                ] : null,
                'image' => $item->image ? url($item->image) : null,
                'title_id' => $item->title_id,
                'title_en' => $item->title_en,
                'content_id' => $item->content_id,
                'content_en' => $item->content_en,
                'author' => $item->author,
                'source' => $item->source,
                'created_at' => $item->created_at?->toISOString(),
                'updated_at' => $item->updated_at?->toISOString(),
            ];
        });

        return response()->json([
            'data' => $articles,
        ]);
    }

    public function show($id)
    {
        $item = NewsmakerArticle::with(['mainCategory', 'subCategory'])->findOrFail($id);

        return response()->json([
            'data' => [
                'id' => $item->id,
                'main_category_id' => $item->main_category_id,
                'sub_category_id' => $item->sub_category_id,
                'main_category' => $item->mainCategory ? [
                    'id' => $item->mainCategory->id,
                    'name' => $item->mainCategory->name,
                    'slug' => $item->mainCategory->slug,
                ] : null,
                'sub_category' => $item->subCategory ? [
                    'id' => $item->subCategory->id,
                    'name' => $item->subCategory->name,
                    'slug' => $item->subCategory->slug,
                ] : null,
                'image' => $item->image ? url($item->image) : null,
                'title_id' => $item->title_id,
                'title_en' => $item->title_en,
                'content_id' => $item->content_id,
                'content_en' => $item->content_en,
                'author' => $item->author,
                'source' => $item->source,
                'created_at' => $item->created_at?->toISOString(),
                'updated_at' => $item->updated_at?->toISOString(),
            ],
        ]);
    }
}