<?php

namespace App\Http\Controllers;

use App\Models\BreakingNewsCategory;
use App\Models\NewsmakerBreakingNews;
use Illuminate\Http\Request;

/**
 * Mirip NewsmakerMainCategoryController, tapi untuk kategori Breaking News
 * sendiri (BreakingNewsCategory) - sistemnya sama (grid kategori -> detail
 * kategori berisi daftar artikel -> Tambah Berita), datanya terpisah.
 */
class BreakingNewsCategoryController extends Controller
{
    public function index()
    {
        $categories = BreakingNewsCategory::withCount('breakingNews')->latest()->get();

        return view('newsmaker23.breaking-news.index', compact('categories'));
    }

    public function create()
    {
        return view('newsmaker23.breaking-news.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:100',
        ]);

        BreakingNewsCategory::create([
            'name' => $request->name,
        ]);

        return redirect()->route('newsmaker23.breaking-news.index')
            ->with('success', 'Kategori Breaking News berhasil ditambahkan.');
    }

    public function show($slug)
    {
        $category = BreakingNewsCategory::withCount('breakingNews')
            ->where('slug', $slug)
            ->firstOrFail();

        $items = $category->breakingNews()->with('authorUser')->latest()->get();

        return view('newsmaker23.breaking-news.show', compact('category', 'items'));
    }

    public function edit($id)
    {
        $category = BreakingNewsCategory::findOrFail($id);

        return view('newsmaker23.breaking-news.edit', compact('category'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|max:100',
        ]);

        $category = BreakingNewsCategory::findOrFail($id);
        $category->update([
            'name' => $request->name,
        ]);

        return redirect()->route('newsmaker23.breaking-news.index')
            ->with('success', 'Kategori Breaking News berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $category = BreakingNewsCategory::findOrFail($id);

        $items = NewsmakerBreakingNews::where('breaking_news_category_id', $category->id)->get();
        foreach ($items as $item) {
            if ($item->image) {
                $oldPath = public_path($item->image);
                if (file_exists($oldPath)) {
                    unlink($oldPath);
                }
            }
            $item->delete();
        }

        $category->delete();

        return redirect()->route('newsmaker23.breaking-news.index')
            ->with('success', 'Kategori Breaking News berhasil dihapus.');
    }
}
