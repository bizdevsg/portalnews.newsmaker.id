<?php

namespace App\Http\Controllers;

use App\Models\PasarIndonesiaRegulasiInstitusiArticle;
use App\Models\PasarIndonesiaRegulasiInstitusiCategory;
use Illuminate\Http\Request;

class PasarIndonesiaRegulasiInstitusiController extends Controller
{
    public function create(Request $request)
    {
        $selectedCategoryId = $request->integer('category_id');

        if (!$selectedCategoryId) {
            return redirect()->route('regulasi-institusi.index')
                ->with('info', 'Masuk dulu ke kategori yang dituju untuk menambahkan berita.');
        }

        $selectedCategory = PasarIndonesiaRegulasiInstitusiCategory::find($selectedCategoryId);

        if (!$selectedCategory) {
            return redirect()->route('regulasi-institusi.index')
                ->with('info', 'Kategori tujuan tidak ditemukan. Pilih kategori lain untuk menambahkan berita.');
        }

        return view('pasar-indonesia.regulasi-institusi.create', compact('selectedCategory'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:pasar_indonesia_regulasi_institusi_categories,id',
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'title_id' => 'required|max:150',
            'title_en' => 'required|max:150',
            'content_id' => 'required',
            'content_en' => 'required',
            'source' => 'required|max:150',
        ]);

        $selectedCategory = PasarIndonesiaRegulasiInstitusiCategory::findOrFail($request->category_id);

        $uploadPath = public_path('uploads/pasar-indonesia/regulasi-institusi');
        if (!file_exists($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        $filename = time() . '_' . $request->file('image')->getClientOriginalName();
        $request->file('image')->move($uploadPath, $filename);

        PasarIndonesiaRegulasiInstitusiArticle::create([
            'category' => $selectedCategory->slug,
            'image' => 'uploads/pasar-indonesia/regulasi-institusi/' . $filename,
            'title_id' => $request->title_id,
            'title_en' => $request->title_en,
            'content_id' => $request->content_id,
            'content_en' => $request->content_en,
            'author_id' => $request->user()->id,
            'source' => $request->source,
        ]);

        return redirect()->route('regulasi-institusi.kategori.show', $selectedCategory->slug)
            ->with('success', 'Berita Regulasi & Institusi berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $item = PasarIndonesiaRegulasiInstitusiArticle::query()
            ->with(['author', 'categoryItem'])
            ->findOrFail($id);

        $categories = PasarIndonesiaRegulasiInstitusiCategory::query()->orderBy('name')->get();

        return view('pasar-indonesia.regulasi-institusi.edit', compact('item', 'categories'));
    }

    public function show($id)
    {
        $item = PasarIndonesiaRegulasiInstitusiArticle::query()
            ->with(['author', 'categoryItem'])
            ->findOrFail($id);

        return view('pasar-indonesia.regulasi-institusi.show', compact('item'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'category_id' => 'required|exists:pasar_indonesia_regulasi_institusi_categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'title_id' => 'required|max:150',
            'title_en' => 'required|max:150',
            'content_id' => 'required',
            'content_en' => 'required',
            'source' => 'required|max:150',
        ]);

        $selectedCategory = PasarIndonesiaRegulasiInstitusiCategory::findOrFail($request->category_id);
        $item = PasarIndonesiaRegulasiInstitusiArticle::findOrFail($id);
        $updatedImage = $item->image;

        if ($request->hasFile('image')) {
            $uploadPath = public_path('uploads/pasar-indonesia/regulasi-institusi');
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
            $updatedImage = 'uploads/pasar-indonesia/regulasi-institusi/' . $filename;
        }

        $item->update([
            'category' => $selectedCategory->slug,
            'image' => $updatedImage,
            'title_id' => $request->title_id,
            'title_en' => $request->title_en,
            'content_id' => $request->content_id,
            'content_en' => $request->content_en,
            'source' => $request->source,
        ]);

        return redirect()->route('regulasi-institusi.kategori.show', $selectedCategory->slug)
            ->with('success', 'Berita Regulasi & Institusi berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $item = PasarIndonesiaRegulasiInstitusiArticle::findOrFail($id);
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
            && PasarIndonesiaRegulasiInstitusiCategory::query()->where('slug', $categorySlug)->exists()
        ) {
            return redirect()->route('regulasi-institusi.kategori.show', $categorySlug)
                ->with('success', 'Berita Regulasi & Institusi berhasil dihapus.');
        }

        return redirect()->route('regulasi-institusi.index')
            ->with('success', 'Berita Regulasi & Institusi berhasil dihapus.');
    }
}
