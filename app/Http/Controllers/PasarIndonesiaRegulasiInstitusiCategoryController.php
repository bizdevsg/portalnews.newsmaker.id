<?php

namespace App\Http\Controllers;

use App\Models\PasarIndonesiaRegulasiInstitusiArticle;
use App\Models\PasarIndonesiaRegulasiInstitusiCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PasarIndonesiaRegulasiInstitusiCategoryController extends Controller
{
    public function index()
    {
        $categories = PasarIndonesiaRegulasiInstitusiCategory::query()
            ->withCount('articles')
            ->latest()
            ->get();

        return view('pasar-indonesia.regulasi-institusi.categories', compact('categories'));
    }

    public function create()
    {
        return view('pasar-indonesia.regulasi-institusi.category-create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100|unique:pasar_indonesia_regulasi_institusi_categories,name',
        ]);

        PasarIndonesiaRegulasiInstitusiCategory::create([
            'name' => $request->name,
        ]);

        return redirect()->route('regulasi-institusi.index')
            ->with('success', 'Kategori Regulasi & Institusi berhasil ditambahkan.');
    }

    public function show(string $slug)
    {
        $category = PasarIndonesiaRegulasiInstitusiCategory::query()
            ->withCount('articles')
            ->where('slug', $slug)
            ->firstOrFail();

        $items = $category->articles()
            ->with('author')
            ->latest()
            ->get();

        return view('pasar-indonesia.regulasi-institusi.index', compact('category', 'items'));
    }

    public function edit(int $id)
    {
        $category = PasarIndonesiaRegulasiInstitusiCategory::findOrFail($id);

        return view('pasar-indonesia.regulasi-institusi.category-edit', compact('category'));
    }

    public function update(Request $request, int $id)
    {
        $request->validate([
            'name' => 'required|string|max:100|unique:pasar_indonesia_regulasi_institusi_categories,name,' . $id,
        ]);

        $category = PasarIndonesiaRegulasiInstitusiCategory::findOrFail($id);
        $oldSlug = $category->slug;

        DB::transaction(function () use ($category, $request, $oldSlug): void {
            $category->update([
                'name' => $request->name,
            ]);

            if ($oldSlug !== $category->slug) {
                PasarIndonesiaRegulasiInstitusiArticle::query()
                    ->where('category', $oldSlug)
                    ->update(['category' => $category->slug]);
            }
        });

        return redirect()->route('regulasi-institusi.index')
            ->with('success', 'Kategori Regulasi & Institusi berhasil diperbarui.');
    }

    public function destroy(int $id)
    {
        $category = PasarIndonesiaRegulasiInstitusiCategory::findOrFail($id);
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

        return redirect()->route('regulasi-institusi.index')
            ->with('success', 'Kategori Regulasi & Institusi berhasil dihapus.');
    }
}
