<?php

namespace App\Http\Controllers;

use App\Models\NewsmakerBreakingNews;
use Illuminate\Http\Request;

/**
 * Sistem CRUD berdiri sendiri untuk Breaking News - sengaja dibuat setara
 * dengan NewsmakerArticleController (judul/isi dua bahasa, gambar, author,
 * source, notif) tapi datanya di tabel sendiri (newsmaker_breaking_news),
 * tidak terhubung ke kategori/artikel Newsmaker23 sama sekali.
 */
class BreakingNewsController extends Controller
{
    private const AUTHOR_INITIALS = ['MRV', 'ASD', 'YDS', 'ARL', 'CP', 'ALG', 'SRH', 'SNM'];

    public function index()
    {
        $items = NewsmakerBreakingNews::with('authorUser')->latest()->get();

        return view('newsmaker23.breaking-news.index', compact('items'));
    }

    public function create()
    {
        return view('newsmaker23.breaking-news.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'author' => 'required|string|in:' . implode(',', self::AUTHOR_INITIALS),
            'title_id' => 'required|max:150',
            'title_en' => 'required|max:150',
            'notif' => 'nullable|boolean',
            'content_id' => 'required',
            'content_en' => 'required',
            'source' => 'required|max:150',
        ]);

        $uploadPath = public_path('uploads/newsmaker23/breaking-news');
        if (!file_exists($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        $namaFile = time() . '_' . $request->file('image')->getClientOriginalName();
        $request->file('image')->move($uploadPath, $namaFile);

        NewsmakerBreakingNews::create([
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

        return redirect()->route('newsmaker23.breaking-news.index')
            ->with('success', 'Breaking News berhasil ditambahkan.');
    }

    public function show($id)
    {
        $item = NewsmakerBreakingNews::with('authorUser')->findOrFail($id);

        return view('newsmaker23.breaking-news.show', compact('item'));
    }

    public function edit($id)
    {
        $item = NewsmakerBreakingNews::findOrFail($id);

        return view('newsmaker23.breaking-news.edit', compact('item'));
    }

    public function update(Request $request, $id)
    {
        $item = NewsmakerBreakingNews::findOrFail($id);

        $request->validate([
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'author' => 'required|string|in:' . implode(',', self::AUTHOR_INITIALS),
            'title_id' => 'required|max:150',
            'title_en' => 'required|max:150',
            'notif' => 'nullable|boolean',
            'content_id' => 'required',
            'content_en' => 'required',
            'source' => 'required|max:150',
        ]);

        $data = [
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

            $namaFile = time() . '_' . $request->file('image')->getClientOriginalName();
            $request->file('image')->move($uploadPath, $namaFile);

            if ($item->image && file_exists(public_path($item->image))) {
                unlink(public_path($item->image));
            }

            $data['image'] = 'uploads/newsmaker23/breaking-news/' . $namaFile;
        }

        $item->update($data);

        return redirect()->route('newsmaker23.breaking-news.index')
            ->with('success', 'Breaking News berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $item = NewsmakerBreakingNews::findOrFail($id);

        if ($item->image && file_exists(public_path($item->image))) {
            unlink(public_path($item->image));
        }

        $item->delete();

        return redirect()->route('newsmaker23.breaking-news.index')
            ->with('success', 'Breaking News berhasil dihapus.');
    }
}
