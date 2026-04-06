<?php

namespace App\Http\Controllers;

use App\Models\PasarIndonesiaArticle;
use App\Models\PasarIndonesiaCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PasarIndonesiaCategoryController extends Controller
{
    public function index()
    {
        $categories = PasarIndonesiaCategory::query()
            ->withCount('articles')
            ->latest()
            ->get();

        return view('pasar-indonesia.berita.categories', compact('categories'));
    }

    public function create()
    {
        return view('pasar-indonesia.berita.category-create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100|unique:pasar_indonesia_categories,name',
        ]);

        PasarIndonesiaCategory::create([
            'name' => $request->name,
        ]);

        return redirect()->route('pasar-indonesia.berita.index')
            ->with('success', 'Kategori berita Pasar Indonesia berhasil ditambahkan.');
    }

    public function show(string $slug)
    {
        $category = PasarIndonesiaCategory::query()
            ->withCount('articles')
            ->where('slug', $slug)
            ->firstOrFail();

        $items = $category->articles()
            ->with(['author', 'categoryItem'])
            ->latest()
            ->get();

        return view('pasar-indonesia.berita.index', compact('category', 'items'));
    }

    public function edit(int $id)
    {
        $category = PasarIndonesiaCategory::findOrFail($id);

        return view('pasar-indonesia.berita.category-edit', compact('category'));
    }

    public function update(Request $request, int $id)
    {
        $request->validate([
            'name' => 'required|string|max:100|unique:pasar_indonesia_categories,name,' . $id,
        ]);

        $category = PasarIndonesiaCategory::findOrFail($id);
        $oldSlug = $category->slug;

        DB::transaction(function () use ($category, $request, $oldSlug): void {
            $category->update([
                'name' => $request->name,
            ]);

            if ($oldSlug !== $category->slug) {
                PasarIndonesiaArticle::query()
                    ->where('type', 'berita')
                    ->where('category', $oldSlug)
                    ->update(['category' => $category->slug]);
            }
        });

        return redirect()->route('pasar-indonesia.berita.index')
            ->with('success', 'Kategori berita Pasar Indonesia berhasil diperbarui.');
    }

    public function destroy(int $id)
    {
        $category = PasarIndonesiaCategory::findOrFail($id);
        $articles = $category->articles()->get();

        foreach ($articles as $article) {
            if ($article->image) {
                $oldPath = public_path($article->image);
                if (file_exists($oldPath)) {
                    unlink($oldPath);
                }
            }

            $article->delete();
        }

        $category->delete();

        return redirect()->route('pasar-indonesia.berita.index')
            ->with('success', 'Kategori berita Pasar Indonesia berhasil dihapus.');
    }
}
