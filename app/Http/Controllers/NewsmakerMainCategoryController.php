<?php

namespace App\Http\Controllers;

use App\Models\NewsmakerArticle;
use App\Models\NewsmakerMainCategory;
use Illuminate\Http\Request;

class NewsmakerMainCategoryController extends Controller
{
    public function index()
    {
        $categories = NewsmakerMainCategory::withCount('articles')->latest()->get();

        return view('newsmaker23.main-category.index', compact('categories'));
    }

    public function create()
    {
        return view('newsmaker23.main-category.create');
    }

    public function show($slug)
    {
        $category = NewsmakerMainCategory::withCount('articles')
            ->where('slug', $slug)
            ->firstOrFail();
        $articles = $category->articles()->with(['mainCategory', 'authorUser'])->latest()->get();

        return view('newsmaker23.main-category.show', compact('category', 'articles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:100',
        ]);

        NewsmakerMainCategory::create([
            'name' => $request->name,
        ]);

        return redirect()->route('newsmaker23.index')
            ->with('success', 'Kategori berita berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $category = NewsmakerMainCategory::findOrFail($id);

        return view('newsmaker23.main-category.edit', compact('category'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|max:100',
        ]);

        $category = NewsmakerMainCategory::findOrFail($id);
        $category->update([
            'name' => $request->name,
        ]);

        return redirect()->route('newsmaker23.index')
            ->with('success', 'Kategori berita berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $category = NewsmakerMainCategory::findOrFail($id);

        $articles = NewsmakerArticle::where('main_category_id', $category->id)->get();
        foreach ($articles as $article) {
            if ($article->image) {
                $oldPath = public_path($article->image);
                if (file_exists($oldPath)) {
                    unlink($oldPath);
                }
            }
            $article->delete();
        }

        $category->subCategories()->delete();
        $category->delete();

        return redirect()->route('newsmaker23.index')
            ->with('success', 'Kategori berita berhasil dihapus.');
    }
}
