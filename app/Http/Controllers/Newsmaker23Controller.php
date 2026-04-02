<?php

namespace App\Http\Controllers;

use App\Models\NewsmakerArticle;
use App\Models\NewsmakerMainCategory;

class Newsmaker23Controller extends Controller
{
    public function index()
    {
        $stats = [
            'categories' => NewsmakerMainCategory::count(),
            'articles' => NewsmakerArticle::count(),
            'latest_category' => NewsmakerMainCategory::latest()->value('name'),
            'latest_article' => NewsmakerArticle::latest()->value('title_id'),
        ];

        $categories = NewsmakerMainCategory::withCount('articles')
            ->latest()
            ->take(6)
            ->get();

        $latestArticles = NewsmakerArticle::with(['mainCategory', 'authorUser'])
            ->latest()
            ->take(6)
            ->get();

        return view('newsmaker23.index', compact('stats', 'categories', 'latestArticles'));
    }
}
