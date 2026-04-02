<?php

namespace App\Http\Controllers;

use App\Models\PasarIndonesiaArticle;
use Illuminate\Http\Request;

class PasarIndonesiaBeritaController extends Controller
{
    public function index()
    {
        return redirect()->to(route('pasar-indonesia.index') . '#berita');
    }

    public function create()
    {
        return view('pasar-indonesia.berita.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'title_id' => 'required|max:150',
            'title_en' => 'required|max:150',
            'content_id' => 'required',
            'content_en' => 'required',
            'source' => 'required|max:150',
        ]);

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
            'content_id' => $request->content_id,
            'content_en' => $request->content_en,
            'author_id' => $request->user()->id,
            'source' => $request->source,
        ]);

        return redirect()->to(route('pasar-indonesia.index') . '#berita')
            ->with('success', 'Berita Pasar Indonesia berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $item = PasarIndonesiaArticle::where('type', 'berita')->findOrFail($id);

        return view('pasar-indonesia.berita.edit', compact('item'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'title_id' => 'required|max:150',
            'title_en' => 'required|max:150',
            'content_id' => 'required',
            'content_en' => 'required',
            'source' => 'required|max:150',
        ]);

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
            'content_id' => $request->content_id,
            'content_en' => $request->content_en,
            'source' => $request->source,
        ]);

        return redirect()->to(route('pasar-indonesia.index') . '#berita')
            ->with('success', 'Berita Pasar Indonesia berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $item = PasarIndonesiaArticle::where('type', 'berita')->findOrFail($id);

        if ($item->image) {
            $oldPath = public_path($item->image);
            if (file_exists($oldPath)) {
                unlink($oldPath);
            }
        }

        $item->delete();

        return redirect()->to(route('pasar-indonesia.index') . '#berita')
            ->with('success', 'Berita Pasar Indonesia berhasil dihapus.');
    }
}
