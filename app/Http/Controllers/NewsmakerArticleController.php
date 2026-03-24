<?php

namespace App\Http\Controllers;

use App\Models\NewsmakerArticle;
use App\Models\NewsmakerMainCategory;
use App\Models\NewsmakerSubCategory;
use Illuminate\Http\Request;

class NewsmakerArticleController extends Controller
{
    public function index()
    {
        $articles = NewsmakerArticle::with(['mainCategory', 'subCategory'])->latest()->get();

        return view('newsmaker23.berita.index', compact('articles'));
    }

    public function create(Request $request)
    {
        $mainCategories = NewsmakerMainCategory::orderBy('name')->get();
        $subCategories = NewsmakerSubCategory::with('mainCategory')->orderBy('name')->get();

        $selectedMainId = $request->query('main_category_id');
        $selectedSubId = $request->query('sub_category_id');

        return view('newsmaker23.berita.create', compact('mainCategories', 'subCategories', 'selectedMainId', 'selectedSubId'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'main_category_id' => 'required|exists:newsmaker_main_categories,id',
            'sub_category_id' => 'required|exists:newsmaker_sub_categories,id',
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'title_id' => 'required|max:150',
            'title_en' => 'required|max:150',
            'content_id' => 'required',
            'content_en' => 'required',
            'author' => 'required|max:100',
            'source' => 'required|max:150',
        ]);

        $subCategory = NewsmakerSubCategory::where('id', $request->sub_category_id)
            ->where('main_category_id', $request->main_category_id)
            ->firstOrFail();

        $uploadPath = public_path('uploads/newsmaker23');
        if (!file_exists($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        $namaFile = time() . '_' . $request->file('image')->getClientOriginalName();
        $request->file('image')->move($uploadPath, $namaFile);

        NewsmakerArticle::create([
            'main_category_id' => $request->main_category_id,
            'sub_category_id' => $subCategory->id,
            'image' => 'uploads/newsmaker23/' . $namaFile,
            'title_id' => $request->title_id,
            'title_en' => $request->title_en,
            'content_id' => $request->content_id,
            'content_en' => $request->content_en,
            'author' => $request->author,
            'source' => $request->source,
        ]);

        return redirect()->route('newsmaker23.berita.index')
            ->with('success', 'Berita Newsmaker 23 berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $article = NewsmakerArticle::findOrFail($id);
        $mainCategories = NewsmakerMainCategory::orderBy('name')->get();
        $subCategories = NewsmakerSubCategory::with('mainCategory')->orderBy('name')->get();

        return view('newsmaker23.berita.edit', compact('article', 'mainCategories', 'subCategories'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'main_category_id' => 'required|exists:newsmaker_main_categories,id',
            'sub_category_id' => 'required|exists:newsmaker_sub_categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'title_id' => 'required|max:150',
            'title_en' => 'required|max:150',
            'content_id' => 'required',
            'content_en' => 'required',
            'author' => 'required|max:100',
            'source' => 'required|max:150',
        ]);

        $article = NewsmakerArticle::findOrFail($id);

        $subCategory = NewsmakerSubCategory::where('id', $request->sub_category_id)
            ->where('main_category_id', $request->main_category_id)
            ->firstOrFail();

        $updatedImage = null;
        if ($request->hasFile('image')) {
            $uploadPath = public_path('uploads/newsmaker23');
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }

            if ($article->image) {
                $oldPath = public_path($article->image);
                if (file_exists($oldPath)) {
                    unlink($oldPath);
                }
            }

            $namaFile = time() . '_' . $request->file('image')->getClientOriginalName();
            $request->file('image')->move($uploadPath, $namaFile);
            $updatedImage = 'uploads/newsmaker23/' . $namaFile;
        }

        $data = [
            'main_category_id' => $request->main_category_id,
            'sub_category_id' => $subCategory->id,
            'title_id' => $request->title_id,
            'title_en' => $request->title_en,
            'content_id' => $request->content_id,
            'content_en' => $request->content_en,
            'author' => $request->author,
            'source' => $request->source,
        ];

        if ($updatedImage) {
            $data['image'] = $updatedImage;
        }

        $article->update($data);

        return redirect()->route('newsmaker23.berita.index')
            ->with('success', 'Berita Newsmaker 23 berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $article = NewsmakerArticle::findOrFail($id);

        if ($article->image) {
            $oldPath = public_path($article->image);
            if (file_exists($oldPath)) {
                unlink($oldPath);
            }
        }

        $article->delete();

        return redirect()->route('newsmaker23.berita.index')
            ->with('success', 'Berita Newsmaker 23 berhasil dihapus.');
    }
}
