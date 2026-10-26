<?php

namespace App\Http\Controllers;

use App\Models\NewsmakerArticle;
use App\Models\NewsmakerMainCategory;
use App\Models\NewsmakerSubCategory;
use Illuminate\Http\Request;

class NewsmakerArticleController extends Controller
{
    private const AUTHOR_INITIALS = ['MRV', 'ASD', 'YDS', 'ARL', 'CP', 'ALG', 'SRH', 'SNM'];

    public function index()
    {
        $articles = NewsmakerArticle::with(['mainCategory', 'authorUser'])->latest()->get();

        return view('newsmaker23.berita.index', compact('articles'));
    }

    public function create(Request $request)
    {
        $selectedMainId = $request->integer('main_category_id');

        if (!$selectedMainId) {
            return redirect()->route('newsmaker23.index')
                ->with('info', 'Masuk dulu ke kategori yang dituju untuk menambahkan berita.');
        }

        $selectedMainCategory = NewsmakerMainCategory::find($selectedMainId);

        if (!$selectedMainCategory) {
            return redirect()->route('newsmaker23.index')
                ->with('info', 'Kategori tujuan tidak ditemukan. Pilih kategori lain untuk menambahkan berita.');
        }

        return view('newsmaker23.berita.create', compact('selectedMainCategory'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'main_category_id' => 'required|exists:newsmaker_main_categories,id',
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'author' => 'required|string|in:' . implode(',', self::AUTHOR_INITIALS),
            'title_id' => 'required|max:150',
            'title_en' => 'required|max:150',
            'notif' => 'nullable|boolean',
            'content_id' => 'required',
            'content_en' => 'required',
            'source' => 'required|max:150',
        ]);

        $mainCategory = NewsmakerMainCategory::findOrFail($request->main_category_id);
        $subCategory = $mainCategory->subCategories()->first();

        if (!$subCategory) {
            $subCategory = NewsmakerSubCategory::create([
                'main_category_id' => $mainCategory->id,
                'name' => $mainCategory->name,
            ]);
        }

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
            'notif' => $request->boolean('notif'),
            'content_id' => $request->content_id,
            'content_en' => $request->content_en,
            'author' => strtoupper(trim((string) $request->author)),
            'author_initial' => strtoupper(trim((string) $request->author)),
            'author_id' => $request->user()?->id,
            'source' => $request->source,
        ]);

        return redirect()->route('newsmaker23.main-category.show', $mainCategory->slug)
            ->with('success', 'Berita Newsmaker 23 berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $article = NewsmakerArticle::with('authorUser')->findOrFail($id);
        $mainCategories = NewsmakerMainCategory::orderBy('name')->get();

        return view('newsmaker23.berita.edit', compact('article', 'mainCategories'));
    }

    public function show($id)
    {
        $article = NewsmakerArticle::with(['mainCategory', 'authorUser'])->findOrFail($id);

        return view('newsmaker23.berita.show', compact('article'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'main_category_id' => 'required|exists:newsmaker_main_categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'author' => 'required|string|in:' . implode(',', self::AUTHOR_INITIALS),
            'title_id' => 'required|max:150',
            'title_en' => 'required|max:150',
            'notif' => 'nullable|boolean',
            'content_id' => 'required',
            'content_en' => 'required',
            'source' => 'required|max:150',
        ]);

        $article = NewsmakerArticle::findOrFail($id);
        $mainCategory = NewsmakerMainCategory::findOrFail($request->main_category_id);
        $subCategory = $mainCategory->subCategories()->first();

        if (!$subCategory) {
            $subCategory = NewsmakerSubCategory::create([
                'main_category_id' => $mainCategory->id,
                'name' => $mainCategory->name,
            ]);
        }

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
            'notif' => $request->boolean('notif'),
            'content_id' => $request->content_id,
            'content_en' => $request->content_en,
            'author' => strtoupper(trim((string) $request->author)),
            'author_initial' => strtoupper(trim((string) $request->author)),
            'source' => $request->source,
        ];

        if ($updatedImage) {
            $data['image'] = $updatedImage;
        }

        $article->update($data);

        return redirect()->route('newsmaker23.main-category.show', $mainCategory->slug)
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

        return redirect()->back()
            ->with('success', 'Berita Newsmaker 23 berhasil dihapus.');
    }
}
