<?php

namespace App\Http\Controllers;

use App\Models\NewsmakerMainCategory;

/**
 * Halaman tersendiri untuk Breaking News - bukan bagian dari grid "Kategori"
 * (lihat Newsmaker23Controller::HIDDEN_CATEGORY_SLUGS), tapi tetap pakai
 * sistem berita yang sama: artikel disimpan sebagai NewsmakerArticle dengan
 * main_category_id mengarah ke kategori "Breaking News" di bawah ini.
 */
class BreakingNewsController extends Controller
{
    public const SLUG = 'breaking-news';

    public function index()
    {
        $category = NewsmakerMainCategory::withCount('articles')
            ->where('slug', self::SLUG)
            ->first();

        if (!$category) {
            $category = NewsmakerMainCategory::create(['name' => 'Breaking News']);
            $category->loadCount('articles');
        }

        $articles = $category->articles()->with(['mainCategory', 'authorUser'])->latest()->get();

        return view('newsmaker23.breaking-news.index', compact('category', 'articles'));
    }
}
