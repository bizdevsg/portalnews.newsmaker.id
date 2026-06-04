<?php

namespace App\Http\Controllers;

use App\Models\PasarIndonesiaArticle;
use App\Models\PasarIndonesiaCategory;
use Illuminate\Http\Request;

class PasarIndonesiaBeritaController extends Controller
{
    private const AUTHOR_INITIALS = ['MRV', 'ASD', 'YDS', 'ARL', 'CP', 'ALG', 'SRH', 'SNM'];

    public function create(Request $request)
    {
        $selectedCategoryId = $request->integer('category_id');

        if (!$selectedCategoryId) {
            return redirect()->route('pasar-indonesia.berita.index')
                ->with('info', 'Masuk dulu ke kategori yang dituju untuk menambahkan berita.');
        }

        $selectedCategory = PasarIndonesiaCategory::find($selectedCategoryId);

        if (!$selectedCategory) {
            return redirect()->route('pasar-indonesia.berita.index')
                ->with('info', 'Kategori tujuan tidak ditemukan. Pilih kategori lain untuk menambahkan berita.');
        }

        return view('pasar-indonesia.berita.create', compact('selectedCategory'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:pasar_indonesia_categories,id',
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'author' => 'required|string|in:' . implode(',', self::AUTHOR_INITIALS),
            'title_id' => 'required|max:150',
            'title_en' => 'required|max:150',
            'notif' => 'nullable|boolean',
            'beranda_api' => 'nullable|boolean',
            'content_id' => 'required',
            'content_en' => 'required',
            'source' => 'required|max:150',
        ]);

        $selectedCategory = PasarIndonesiaCategory::findOrFail($request->category_id);

        $uploadPath = public_path('uploads/pasar-indonesia/berita');
        if (!file_exists($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        $filename = time() . '_' . $request->file('image')->getClientOriginalName();
        $request->file('image')->move($uploadPath, $filename);

        PasarIndonesiaArticle::create([
            'type' => 'berita',
            'image' => 'uploads/pasar-indonesia/berita/' . $filename,
            'title_id' => $request->title_id,
            'title_en' => $request->title_en,
            'notif' => $request->boolean('notif'),
            'beranda_api' => $request->boolean('beranda_api'),
            'content_id' => $request->content_id,
            'content_en' => $request->content_en,
            'author_id' => $request->user()->id,
            'author_initial' => strtoupper(trim((string) $request->author)),
            'source' => $request->source,
            'category' => $selectedCategory->slug,
        ]);

        return redirect()->route('pasar-indonesia.berita.kategori.show', $selectedCategory->slug)
            ->with('success', 'Berita Pasar Indonesia berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $item = PasarIndonesiaArticle::query()
            ->with('author')
            ->where('type', 'berita')
            ->findOrFail($id);

        $categories = PasarIndonesiaCategory::query()->orderBy('name')->get();

        return view('pasar-indonesia.berita.edit', compact('item', 'categories'));
    }

    public function show($id)
    {
        $item = PasarIndonesiaArticle::query()
            ->with(['author', 'categoryItem'])
            ->where('type', 'berita')
            ->findOrFail($id);

        return view('pasar-indonesia.berita.show', compact('item'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'category_id' => 'required|exists:pasar_indonesia_categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'author' => 'required|string|in:' . implode(',', self::AUTHOR_INITIALS),
            'title_id' => 'required|max:150',
            'title_en' => 'required|max:150',
            'notif' => 'nullable|boolean',
            'beranda_api' => 'nullable|boolean',
            'content_id' => 'required',
            'content_en' => 'required',
            'source' => 'required|max:150',
        ]);

        $selectedCategory = PasarIndonesiaCategory::findOrFail($request->category_id);
        $item = PasarIndonesiaArticle::where('type', 'berita')->findOrFail($id);
        $updatedImage = $item->image;

        if ($request->hasFile('image')) {
            $uploadPath = public_path('uploads/pasar-indonesia/berita');
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }

            if ($item->image) {
                $oldPath = public_path($item->image);
                if (file_exists($oldPath)) {
                    unlink($oldPath);
                }
            }

            $filename = time() . '_' . $request->file('image')->getClientOriginalName();
            $request->file('image')->move($uploadPath, $filename);
            $updatedImage = 'uploads/pasar-indonesia/berita/' . $filename;
        }

        $item->update([
            'image' => $updatedImage,
            'title_id' => $request->title_id,
            'title_en' => $request->title_en,
            'notif' => $request->boolean('notif'),
            'beranda_api' => $request->boolean('beranda_api'),
            'content_id' => $request->content_id,
            'content_en' => $request->content_en,
            'author_initial' => strtoupper(trim((string) $request->author)),
            'source' => $request->source,
            'category' => $selectedCategory->slug,
        ]);

        return redirect()->route('pasar-indonesia.berita.kategori.show', $selectedCategory->slug)
            ->with('success', 'Berita Pasar Indonesia berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $item = PasarIndonesiaArticle::where('type', 'berita')->findOrFail($id);
        $categorySlug = $item->category;

        if ($item->image) {
            $oldPath = public_path($item->image);
            if (file_exists($oldPath)) {
                unlink($oldPath);
            }
        }

        $item->delete();

        if (
            is_string($categorySlug)
            && PasarIndonesiaCategory::query()->where('slug', $categorySlug)->exists()
        ) {
            return redirect()->route('pasar-indonesia.berita.kategori.show', $categorySlug)
                ->with('success', 'Berita Pasar Indonesia berhasil dihapus.');
        }

        return redirect()->route('pasar-indonesia.berita.index')
            ->with('success', 'Berita Pasar Indonesia berhasil dihapus.');
    }
}
