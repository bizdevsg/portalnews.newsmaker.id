<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Berita;
use App\Models\Category;

class BeritaController extends Controller
{
    // Menampilkan berita berdasarkan kategori
    public function index($slug)
    {
        // Cari kategori berdasarkan slug
        $kategori = Category::where('slug', $slug)->firstOrFail();

        // Ambil berita berdasarkan category_id
        $beritas = Berita::where('category_id', $kategori->id)->latest()->get();

        return view('berita.index', compact('beritas', 'kategori'));
    }

    // Menampilkan form tambah berita
    public function create($slug)
    {
        // Cari kategori berdasarkan slug
        $kategori = Category::where('slug', $slug)->firstOrFail();

        return view('berita.create', compact('kategori'));
    }

    // Menyimpan berita baru
    public function store(Request $request, $slug)
    {
        // Validasi input
        $request->validate([
            'title' => 'required|max:100',
            'title_sg' => 'nullable|max:100',
            'title_rfb' => 'nullable|max:100',
            'title_kpf' => 'nullable|max:100',
            'title_ewf' => 'nullable|max:100',
            'title_bpf' => 'nullable|max:100',
            'content' => 'required',
            'image1' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'image2' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'image3' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'image4' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'image5' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'image6' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'category_id' => 'required|exists:categories,id',
        ]);

        // Ambil kategori berdasarkan slug
        $kategori = Category::where('slug', $slug)->firstOrFail();

        // Proses upload gambar
        $images = [];
        for ($i = 1; $i <= 6; $i++) {
            if ($request->hasFile("image$i")) {
                $namaFile = time() . '_' . $request->file("image$i")->getClientOriginalName();
                $request->file("image$i")->move(public_path('uploads'), $namaFile);
                $images["image$i"] = 'uploads/' . $namaFile;
            } else {
                $images["image$i"] = null;
            }
        }

        // Simpan berita ke database
        Berita::create([
            'title' => $request->title,
            'title_sg' => $request->title_sg,
            'title_rfb' => $request->title_rfb,
            'title_kpf' => $request->title_kpf,
            'title_ewf' => $request->title_ewf,
            'title_bpf' => $request->title_bpf,
            'content' => $request->content,
            'category_id' => $kategori->id,
            'image1' => $images['image1'],
            'image2' => $images['image2'],
            'image3' => $images['image3'],
            'image4' => $images['image4'],
            'image5' => $images['image5'],
            'image6' => $images['image6'],
        ]);

        return redirect()->route('berita.index', $slug)
            ->with('success', 'Berita berhasil ditambahkan!');
    }

    // Menampilkan detail berita
    public function show($slug, $id)
    {
        // Ambil kategori berdasarkan slug
        $kategori = Category::where('slug', $slug)->firstOrFail();

        // Ambil berita berdasarkan ID
        $berita = Berita::findOrFail($id);

        return view('berita.detail', compact('berita', 'kategori'));
    }

    // Menampilkan form edit berita
    public function edit($slug, $id)
    {
        // Ambil kategori berdasarkan slug
        $kategori = Category::where('slug', $slug)->firstOrFail();

        // Ambil berita berdasarkan ID
        $berita = Berita::findOrFail($id);

        return view('berita.edit', compact('berita', 'kategori'));
    }

    // Mengupdate berita
    public function update(Request $request, $slug, $id)
    {
        // Validasi input
        $request->validate([
            'title' => 'required|max:100',
            'title_sg' => 'nullable|max:100',
            'title_rfb' => 'nullable|max:100',
            'title_kpf' => 'nullable|max:100',
            'title_ewf' => 'nullable|max:100',
            'title_bpf' => 'nullable|max:100',
            'content' => 'required',
            'image1' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'image2' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'image3' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'image4' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'image5' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'image6' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Ambil berita berdasarkan ID
        $berita = Berita::findOrFail($id);

        // Proses upload gambar jika ada
        for ($i = 1; $i <= 6; $i++) {
            if ($request->hasFile("image$i")) {
                $namaFile = time() . '_' . $request->file("image$i")->getClientOriginalName();
                $request->file("image$i")->move(public_path('uploads'), $namaFile);
                $berita->{"image$i"} = 'uploads/' . $namaFile;
            }
        }

        // Update berita di database
        $berita->update([
            'title' => $request->title,
            'title_sg' => $request->title_sg,
            'title_rfb' => $request->title_rfb,
            'title_kpf' => $request->title_kpf,
            'title_ewf' => $request->title_ewf,
            'title_bpf' => $request->title_bpf,
            'content' => $request->content,
        ]);

        return redirect()->route('berita.index', $slug)
            ->with('success', 'Berita berhasil diperbarui!');
    }

    // Menghapus berita
    public function destroy($slug, $id)
    {
        // Ambil berita berdasarkan ID
        $berita = Berita::findOrFail($id);

        // Hapus file gambar dari server
        for ($i = 1; $i <= 6; $i++) {
            if ($berita->{"image$i"}) {
                $filePath = public_path($berita->{"image$i"});
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
            }
        }

        // Hapus berita dari database
        $berita->delete();

        return redirect()->route('berita.index', $slug)
            ->with('success', 'Berita berhasil dihapus!');
    }
}
