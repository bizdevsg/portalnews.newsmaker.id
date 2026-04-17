<?php

namespace App\Http\Controllers;

use App\Models\NewsmakerVideoBriefing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;

class NewsmakerVideoBriefingController extends Controller
{
    protected function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'embed_code' => 'required|string',
            'backup_video_url' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ];
    }

    public function index()
    {
        $videoBriefings = NewsmakerVideoBriefing::query()
            ->orderByDesc('created_at')
            ->get();

        return view('newsmaker23.video-briefing.index', compact('videoBriefings'));
    }

    public function create()
    {
        return view('newsmaker23.video-briefing.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate($this->rules());

        $payload = [
            'title' => $validated['title'],
            'embed_code' => $validated['embed_code'],
            'backup_video_url' => $validated['backup_video_url'] ?? null,
        ];

        if ($request->hasFile('image')) {
            if (!Schema::hasColumn((new NewsmakerVideoBriefing())->getTable(), 'image')) {
                return redirect()
                    ->back()
                    ->withInput()
                    ->with('error', 'Kolom gambar Video Briefing belum tersedia. Jalankan migrasi terlebih dahulu.');
            }

            $payload['image'] = $this->storeImage($request);
        }

        NewsmakerVideoBriefing::create($payload);
        $this->refreshCache();

        return redirect()
            ->route('newsmaker23.video-briefing.index')
            ->with('success', 'Video Briefing berhasil ditambahkan.');
    }

    public function edit(int $id)
    {
        $videoBriefing = NewsmakerVideoBriefing::findOrFail($id);

        return view('newsmaker23.video-briefing.edit', compact('videoBriefing'));
    }

    public function update(Request $request, int $id)
    {
        $videoBriefing = NewsmakerVideoBriefing::findOrFail($id);
        $validated = $request->validate($this->rules());

        $payload = [
            'title' => $validated['title'],
            'embed_code' => $validated['embed_code'],
            'backup_video_url' => $validated['backup_video_url'] ?? null,
        ];

        if ($request->hasFile('image')) {
            if (!Schema::hasColumn((new NewsmakerVideoBriefing())->getTable(), 'image')) {
                return redirect()
                    ->back()
                    ->withInput()
                    ->with('error', 'Kolom gambar Video Briefing belum tersedia. Jalankan migrasi terlebih dahulu.');
            }

            $payload['image'] = $this->storeImage($request, $videoBriefing->image);
        }

        $videoBriefing->update($payload);
        $this->refreshCache();

        return redirect()
            ->route('newsmaker23.video-briefing.index')
            ->with('success', 'Video Briefing berhasil diperbarui.');
    }

    public function destroy(int $id)
    {
        $videoBriefing = NewsmakerVideoBriefing::findOrFail($id);
        $this->deleteImage($videoBriefing->image);
        $videoBriefing->delete();
        $this->refreshCache();

        return redirect()
            ->route('newsmaker23.video-briefing.index')
            ->with('success', 'Video Briefing berhasil dihapus.');
    }

    protected function refreshCache(): void
    {
        try {
            Artisan::call('video-briefing:cache-json');
        } catch (\Throwable) {
            // Cache refresh is best-effort.
        }
    }

    protected function storeImage(Request $request, ?string $existingImage = null): string
    {
        $uploadPath = public_path('uploads/newsmaker23/video-briefing');
        if (!File::exists($uploadPath)) {
            File::makeDirectory($uploadPath, 0755, true);
        }

        $file = $request->file('image');
        $originalName = preg_replace('/[^A-Za-z0-9._-]/', '_', $file->getClientOriginalName());
        $fileName = time() . '_' . uniqid() . '_' . $originalName;

        $file->move($uploadPath, $fileName);
        $this->deleteImage($existingImage);

        return 'uploads/newsmaker23/video-briefing/' . $fileName;
    }

    protected function deleteImage(?string $imagePath): void
    {
        if ($imagePath && File::exists(public_path($imagePath))) {
            File::delete(public_path($imagePath));
        }
    }
}
