<?php

namespace App\Http\Controllers;

use App\Models\BreakingNewsCategory;
use App\Models\NewsmakerBreakingNews;
use Illuminate\Http\Request;

/**
 * Mirip NewsmakerArticleController, tapi untuk artikel Breaking News
 * sendiri. Tambah artikel wajib lewat kategori Breaking News dulu
 * (breaking_news_category_id), sama seperti berita biasa yang wajib lewat
 * kategori utama - cuma datanya di tabel terpisah.
 */
class BreakingNewsController extends Controller
{
    private const AUTHOR_INITIALS = ['MRV', 'ASD', 'YDS', 'ARL', 'CP', 'ALG', 'SRH', 'SNM'];

    public function create(Request $request)
    {
        $selectedCategoryId = $request->integer('breaking_news_category_id');

        if (!$selectedCategoryId) {
            return redirect()->route('newsmaker23.breaking-news.index')
                ->with('info', 'Masuk dulu ke kategori yang dituju untuk menambahkan Breaking News.');
        }

        $selectedCategory = BreakingNewsCategory::find($selectedCategoryId);

        if (!$selectedCategory) {
            return redirect()->route('newsmaker23.breaking-news.index')
                ->with('info', 'Kategori tujuan tidak ditemukan. Pilih kategori lain untuk menambahkan Breaking News.');
        }

        return view('newsmaker23.breaking-news.article.create', compact('selectedCategory'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'breaking_news_category_id' => 'required|exists:breaking_news_categories,id',
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'author' => 'required|string|in:' . implode(',', self::AUTHOR_INITIALS),
            'title_id' => 'required|max:150',
            'title_en' => 'required|max:150',
            'notif' => 'nullable|boolean',
            'content_id' => 'required',
            'content_en' => 'required',
            'source' => 'required|max:150',
        ]);

        $category = BreakingNewsCategory::findOrFail($request->breaking_news_category_id);

        $uploadPath = public_path('uploads/newsmaker23/breaking-news');
        if (!file_exists($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        $namaFile = time() . '_' . $request->file('image')->getClientOriginalName();
        $request->file('image')->move($uploadPath, $namaFile);

        NewsmakerBreakingNews::create([
            'breaking_news_category_id' => $category->id,
            'image' => 'uploads/newsmaker23/breaking-news/' . $namaFile,
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

        return redirect()->route('newsmaker23.breaking-news.show', $category->slug)
            ->with('success', 'Breaking News berhasil ditambahkan.');
    }

    public function show($id)
    {
        $item = NewsmakerBreakingNews::with(['breakingNewsCategory', 'authorUser'])->findOrFail($id);

        return view('newsmaker23.breaking-news.article.show', compact('item'));
    }

    public function edit($id)
    {
        $item = NewsmakerBreakingNews::with('authorUser')->findOrFail($id);
        $categories = BreakingNewsCategory::orderBy('name')->get();

        return view('newsmaker23.breaking-news.article.edit', compact('item', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'breaking_news_category_id' => 'required|exists:breaking_news_categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'author' => 'required|string|in:' . implode(',', self::AUTHOR_INITIALS),
            'title_id' => 'required|max:150',
            'title_en' => 'required|max:150',
            'notif' => 'nullable|boolean',
            'content_id' => 'required',
            'content_en' => 'required',
            'source' => 'required|max:150',
        ]);

        $item = NewsmakerBreakingNews::findOrFail($id);
        $category = BreakingNewsCategory::findOrFail($request->breaking_news_category_id);

        $data = [
            'breaking_news_category_id' => $category->id,
            'title_id' => $request->title_id,
            'title_en' => $request->title_en,
            'notif' => $request->boolean('notif'),
            'content_id' => $request->content_id,
            'content_en' => $request->content_en,
            'author' => strtoupper(trim((string) $request->author)),
            'author_initial' => strtoupper(trim((string) $request->author)),
            'source' => $request->source,
        ];

        if ($request->hasFile('image')) {
            $uploadPath = public_path('uploads/newsmaker23/breaking-news');
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }

            if ($item->image) {
                $oldPath = public_path($item->image);
                if (file_exists($oldPath)) {
                    unlink($oldPath);
                }
            }

            $namaFile = time() . '_' . $request->file('image')->getClientOriginalName();
            $request->file('image')->move($uploadPath, $namaFile);
            $data['image'] = 'uploads/newsmaker23/breaking-news/' . $namaFile;
        }

        $item->update($data);

        return redirect()->route('newsmaker23.breaking-news.show', $category->slug)
            ->with('success', 'Breaking News berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $item = NewsmakerBreakingNews::findOrFail($id);

        if ($item->image) {
            $oldPath = public_path($item->image);
            if (file_exists($oldPath)) {
                unlink($oldPath);
            }
        }

        $item->delete();

        return redirect()->back()
            ->with('success', 'Breaking News berhasil dihapus.');
    }
}
