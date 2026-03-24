<?php

namespace App\Http\Controllers;

use App\Models\NewsmakerArticle;
use App\Models\NewsmakerMainCategory;
use App\Models\NewsmakerSubCategory;

class Newsmaker23Controller extends Controller
{
    public function index()
    {
        $categories = NewsmakerMainCategory::withCount(['subCategories', 'articles'])->latest()->get();

        return view('newsmaker23.main-category.index', compact('categories'));
    }
}
