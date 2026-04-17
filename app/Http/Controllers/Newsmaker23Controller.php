<?php

namespace App\Http\Controllers;

use App\Models\NewsmakerMainCategory;

class Newsmaker23Controller extends Controller
{
    public function index()
    {
        $categories = NewsmakerMainCategory::withCount('articles')
            ->latest()
            ->get();

        return view('newsmaker23.main-category.index', compact('categories'));
    }
}
