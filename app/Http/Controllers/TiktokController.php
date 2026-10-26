<?php

namespace App\Http\Controllers;

use App\Models\Tiktok;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

/**
 * TikTok sekarang singleton: cuma ada 1 video aktif, tidak ada riwayat
 * upload. Setiap submit form "update" memperlakukan data yang ada sebagai
 * edit - bukan menambah baris baru - supaya tabel `tiktoks` selalu berisi
 * maksimal 1 baris.
 */
class TiktokController extends Controller
{
    protected function rules(bool $videoRequired): array
    {
        return [
            'title' => 'nullable|string|max:255',
            'video' => ($videoRequired ? 'required' : 'nullable') . '|file|mimes:mp4,mov,webm|max:51200',
        ];
    }

    public function index()
    {
        $tiktok = Tiktok::current();

        return view('tiktok.index', compact('tiktok'));
    }

    public function edit()
    {
        $tiktok = Tiktok::current();

        return view('tiktok.edit', compact('tiktok'));
    }

    public function update(Request $request)
    {
        $tiktok = Tiktok::current();

        $validated = $request->validate($this->rules(videoRequired: $tiktok === null));

        $payload = [
            'title' => $validated['title'] ?? null,
        ];

        if ($request->hasFile('video')) {
            $payload['video'] = $this->storeVideo($request, $tiktok?->video);
        }

        if ($tiktok) {
            $tiktok->update($payload);
        } else {
            Tiktok::create($payload);
        }

        $this->refreshCache();

        return redirect()
            ->route('tiktok.index')
            ->with('success', 'Video TikTok berhasil diperbarui.');
    }

    public function destroy()
    {
        $tiktok = Tiktok::current();

        if ($tiktok) {
            $this->deleteVideo($tiktok->video);
            $tiktok->delete();
            $this->refreshCache();
        }

        return redirect()
            ->route('tiktok.index')
            ->with('success', 'Video TikTok berhasil dihapus.');
    }

    protected function refreshCache(): void
    {
        try {
            Artisan::call('tiktok:cache-json');
        } catch (\Throwable) {
            // Cache refresh is best-effort.
        }
    }

    protected function storeVideo(Request $request, ?string $existingVideo = null): string
    {
        $uploadPath = public_path('uploads/tiktok');
        if (!File::exists($uploadPath)) {
            File::makeDirectory($uploadPath, 0755, true);
        }

        $file = $request->file('video');
        $originalName = preg_replace('/[^A-Za-z0-9._-]/', '_', $file->getClientOriginalName());
        $fileName = time() . '_' . uniqid() . '_' . $originalName;

        $file->move($uploadPath, $fileName);
        $this->deleteVideo($existingVideo);

        return 'uploads/tiktok/' . $fileName;
    }

    protected function deleteVideo(?string $videoPath): void
    {
        if ($videoPath && File::exists(public_path($videoPath))) {
            File::delete(public_path($videoPath));
        }
    }
}
