<?php

namespace App\Http\Controllers;

use App\Models\NewsmakerMainCategory;

class Newsmaker23Controller extends Controller
{
    /**
     * Kategori yang punya halaman khusus sendiri (lihat BreakingNewsController
     * dkk) - disembunyikan dari grid "Kategori" ini supaya tidak kecampur,
     * tapi barisnya di `newsmaker_main_categories` tetap ada karena artikel
     * tetap butuh main_category_id ke sana.
     */
    public const HIDDEN_CATEGORY_SLUGS = [
        BreakingNewsController::SLUG,
    ];

    /**
     * Kategori yang ditonjolkan di halaman Newsmaker23: tampil paling atas
     * sesuai urutan di bawah ini dan judulnya diberi warna merah pada view.
     * Kategori lain yang tidak ada di daftar ini tetap ditampilkan (tidak
     * dihapus), diletakkan setelah daftar ini tanpa pewarnaan.
     */
    public const FEATURED_CATEGORY_SLUGS = [
        'gold',
        'oil',
        'hang-seng',
        'nikkei',
        'market-update',
        'global-economy',
        'fiscal-monetary',
        'us-dollar',
        'audusd',
        'eurusd',
        'gbpusd',
        'usdchf',
        'usdjpy',
        'market-analysis',
        'analysis-opinion',
        'market-academy',
        'gold-corner',
    ];

    public function index()
    {
        $featuredOrder = self::FEATURED_CATEGORY_SLUGS;
        $rank = array_flip($featuredOrder);

        $categories = NewsmakerMainCategory::withCount('articles')
            ->latest()
            ->get()
            ->reject(fn (NewsmakerMainCategory $category) => in_array($category->slug, self::HIDDEN_CATEGORY_SLUGS, true))
            ->sortBy(fn (NewsmakerMainCategory $category) => $rank[$category->slug] ?? PHP_INT_MAX)
            ->values();

        return view('newsmaker23.main-category.index', compact('categories', 'featuredOrder'));
    }
}
