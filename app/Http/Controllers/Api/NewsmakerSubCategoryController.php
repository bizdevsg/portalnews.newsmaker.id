<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\NewsmakerSubCategory;
use Illuminate\Http\Request;

class NewsmakerSubCategoryController extends Controller
{
    public function index(Request $request)
    {
        $query = NewsmakerSubCategory::with('mainCategory')->withCount('articles')->orderBy('name');

        if ($request->filled('main_category_id')) {
            $query->where('main_category_id', $request->query('main_category_id'));
        }

        $subCategories = $query->get()->map(function ($item) {
            return [
                'id' => $item->id,
                'main_category_id' => $item->main_category_id,
                'main_category' => $item->mainCategory ? [
                    'id' => $item->mainCategory->id,
                    'name' => $item->mainCategory->name,
                    'slug' => $item->mainCategory->slug,
                ] : null,
                'name' => $item->name,
                'slug' => $item->slug,
                'article_count' => $item->articles_count,
            ];
        });

        return response()->json([
            'data' => $subCategories,
        ]);
    }

    public function show($id)
    {
        $subCategory = NewsmakerSubCategory::with('mainCategory')->withCount('articles')->findOrFail($id);

        return response()->json([
            'data' => [
                'id' => $subCategory->id,
                'main_category_id' => $subCategory->main_category_id,
                'main_category' => $subCategory->mainCategory ? [
                    'id' => $subCategory->mainCategory->id,
                    'name' => $subCategory->mainCategory->name,
                    'slug' => $subCategory->mainCategory->slug,
                ] : null,
                'name' => $subCategory->name,
                'slug' => $subCategory->slug,
                'article_count' => $subCategory->articles_count,
            ],
        ]);
    }
}