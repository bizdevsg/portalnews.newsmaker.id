<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\NewsmakerMainCategory;
use Illuminate\Http\Request;

class NewsmakerMainCategoryController extends Controller
{
    public function index(Request $request)
    {
        $categories = NewsmakerMainCategory::withCount(['subCategories', 'articles'])
            ->orderBy('name')
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->name,
                    'slug' => $item->slug,
                    'sub_category_count' => $item->sub_categories_count,
                    'article_count' => $item->articles_count,
                ];
            });

        return response()->json([
            'data' => $categories,
        ]);
    }

    public function show($id)
    {
        $category = NewsmakerMainCategory::with(['subCategories' => function ($query) {
                $query->withCount('articles')->orderBy('name');
            }])
            ->withCount(['subCategories', 'articles'])
            ->findOrFail($id);

        return response()->json([
            'data' => [
                'id' => $category->id,
                'name' => $category->name,
                'slug' => $category->slug,
                'sub_category_count' => $category->sub_categories_count,
                'article_count' => $category->articles_count,
                'sub_categories' => $category->subCategories->map(function ($sub) {
                    return [
                        'id' => $sub->id,
                        'main_category_id' => $sub->main_category_id,
                        'name' => $sub->name,
                        'slug' => $sub->slug,
                        'article_count' => $sub->articles_count,
                    ];
                }),
            ],
        ]);
    }
}