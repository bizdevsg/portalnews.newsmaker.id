<?php

namespace App\Http\Controllers;

use App\Models\NewsmakerArticle;
use App\Models\NewsmakerMainCategory;
use App\Models\NewsmakerSubCategory;
use Illuminate\Http\Request;

class NewsmakerSubCategoryController extends Controller
{
    public function index()
    {
        $subCategories = NewsmakerSubCategory::with(['mainCategory'])
            ->withCount('articles')
            ->latest()
            ->get();

        return view('newsmaker23.sub-category.index', compact('subCategories'));
    }

    public function create(Request $request)
    {
        $mainCategories = NewsmakerMainCategory::orderBy('name')->get();
        $selectedMainId = $request->query('main_category_id');

        return view('newsmaker23.sub-category.create', compact('mainCategories', 'selectedMainId'));
    }

    public function show($id)
    {
        $subCategory = NewsmakerSubCategory::with('mainCategory')->findOrFail($id);
        $articles = $subCategory->articles()->with(['mainCategory', 'subCategory'])->latest()->get();

        return view('newsmaker23.sub-category.show', compact('subCategory', 'articles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'main_category_id' => 'required|exists:newsmaker_main_categories,id',
            'name' => 'required|max:100',
        ]);

        NewsmakerSubCategory::create([
            'main_category_id' => $request->main_category_id,
            'name' => $request->name,
        ]);

        return redirect()->route('newsmaker23.sub-category.index')
            ->with('success', 'Sub Category berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $subCategory = NewsmakerSubCategory::findOrFail($id);
        $mainCategories = NewsmakerMainCategory::orderBy('name')->get();

        return view('newsmaker23.sub-category.edit', compact('subCategory', 'mainCategories'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'main_category_id' => 'required|exists:newsmaker_main_categories,id',
            'name' => 'required|max:100',
        ]);

        $subCategory = NewsmakerSubCategory::findOrFail($id);
        $subCategory->update([
            'main_category_id' => $request->main_category_id,
            'name' => $request->name,
        ]);

        return redirect()->route('newsmaker23.sub-category.index')
            ->with('success', 'Sub Category berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $subCategory = NewsmakerSubCategory::findOrFail($id);

        $articles = NewsmakerArticle::where('sub_category_id', $subCategory->id)->get();
        foreach ($articles as $article) {
            if ($article->image) {
                $oldPath = public_path($article->image);
                if (file_exists($oldPath)) {
                    unlink($oldPath);
                }
            }
            $article->delete();
        }

        $subCategory->delete();

        return redirect()->route('newsmaker23.sub-category.index')
            ->with('success', 'Sub Category berhasil dihapus.');
    }
}
