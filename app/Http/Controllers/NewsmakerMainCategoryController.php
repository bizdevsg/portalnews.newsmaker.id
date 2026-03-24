<?php

namespace App\Http\Controllers;

use App\Models\NewsmakerMainCategory;
use Illuminate\Http\Request;

class NewsmakerMainCategoryController extends Controller
{
    public function index()
    {
        $categories = NewsmakerMainCategory::withCount(['subCategories', 'articles'])->latest()->get();

        return view('newsmaker23.main-category.index', compact('categories'));
    }

    public function create()
    {
        return view('newsmaker23.main-category.create');
    }

    public function show($id)
    {
        $category = NewsmakerMainCategory::withCount(['subCategories', 'articles'])->findOrFail($id);
        $subCategories = $category->subCategories()->withCount('articles')->latest()->get();

        return view('newsmaker23.main-category.show', compact('category', 'subCategories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:100',
        ]);

        NewsmakerMainCategory::create([
            'name' => $request->name,
        ]);

        return redirect()->route('newsmaker23.main-category.index')
            ->with('success', 'Main Category berhasil ditambahkan.');
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

        return redirect()->route('newsmaker23.main-category.index')
            ->with('success', 'Main Category berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $category = NewsmakerMainCategory::findOrFail($id);
        $category->delete();

        return redirect()->route('newsmaker23.main-category.index')
            ->with('success', 'Main Category berhasil dihapus.');
    }
}
