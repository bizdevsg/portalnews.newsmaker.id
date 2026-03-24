<?php

namespace App\Http\Controllers;

use App\Models\Tiktok;
use Illuminate\Http\Request;

class TiktokController extends Controller
{
    public function index()
    {
        $tiktoks = Tiktok::query()
            ->orderByDesc('created_at')
            ->get();

        return view('tiktok.index', compact('tiktoks'));
    }

    public function create()
    {
        return view('tiktok.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'embed_code' => 'required|string',
            'backup_video_url' => 'nullable|string|max:255',
        ]);

        Tiktok::create($validated);

        return redirect()->route('tiktok.index')->with('success', 'Data TikTok berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $tiktok = Tiktok::findOrFail($id);

        return view('tiktok.edit', compact('tiktok'));
    }

    public function show($id)
    {
        $tiktok = Tiktok::findOrFail($id);

        return view('tiktok.show', compact('tiktok'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'embed_code' => 'required|string',
            'backup_video_url' => 'nullable|string|max:255',
        ]);

        $tiktok = Tiktok::findOrFail($id);
        $tiktok->update($validated);

        return redirect()->route('tiktok.index')->with('success', 'Data TikTok berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $tiktok = Tiktok::findOrFail($id);
        $tiktok->delete();

        return redirect()->route('tiktok.index')->with('success', 'Data TikTok berhasil dihapus.');
    }
}
