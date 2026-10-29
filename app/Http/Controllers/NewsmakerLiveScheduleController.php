<?php

namespace App\Http\Controllers;

use App\Models\NewsmakerLiveSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

class NewsmakerLiveScheduleController extends Controller
{
    protected function rules(): array
    {
        $days = implode(',', NewsmakerLiveSchedule::DAYS);

        return [
            'title' => 'required|string|max:255',
            'day' => 'required|string|in:' . $days,
            'start_time' => 'required|date_format:H:i',
            // Boleh lebih kecil dari start_time: itu berarti siaran nyebrang ke hari berikutnya (mis. 23:00 - 00:30).
            'end_time' => 'nullable|date_format:H:i',
            'host' => 'nullable|string|max:255',
            'tiktok_profile_url' => 'nullable|url|max:255',
            'link_url' => 'nullable|url|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'is_active' => 'nullable|boolean',
            'sort_order' => 'nullable|integer|min:0',
        ];
    }

    public function index()
    {
        $liveSchedules = NewsmakerLiveSchedule::query()
            ->orderByRaw('FIELD(day, ' . implode(',', array_map(fn ($d) => "'$d'", NewsmakerLiveSchedule::DAYS)) . ')')
            ->orderBy('sort_order')
            ->orderBy('start_time')
            ->get();

        return view('newsmaker23.live-schedule.index', compact('liveSchedules'));
    }

    public function create()
    {
        return view('newsmaker23.live-schedule.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate($this->rules());

        $payload = $this->payloadFromRequest($validated);

        if ($request->hasFile('image')) {
            $payload['image'] = $this->storeImage($request);
        }

        NewsmakerLiveSchedule::create($payload);
        $this->refreshCache();

        return redirect()
            ->route('newsmaker23.live-schedule.index')
            ->with('success', 'Jadwal Live berhasil ditambahkan.');
    }

    public function edit(int $id)
    {
        $liveSchedule = NewsmakerLiveSchedule::findOrFail($id);

        return view('newsmaker23.live-schedule.edit', compact('liveSchedule'));
    }

    public function update(Request $request, int $id)
    {
        $liveSchedule = NewsmakerLiveSchedule::findOrFail($id);
        $validated = $request->validate($this->rules());

        $payload = $this->payloadFromRequest($validated);

        if ($request->hasFile('image')) {
            $payload['image'] = $this->storeImage($request, $liveSchedule->image);
        }

        $liveSchedule->update($payload);
        $this->refreshCache();

        return redirect()
            ->route('newsmaker23.live-schedule.index')
            ->with('success', 'Jadwal Live berhasil diperbarui.');
    }

    public function destroy(int $id)
    {
        $liveSchedule = NewsmakerLiveSchedule::findOrFail($id);
        $this->deleteImage($liveSchedule->image);
        $liveSchedule->delete();
        $this->refreshCache();

        return redirect()
            ->route('newsmaker23.live-schedule.index')
            ->with('success', 'Jadwal Live berhasil dihapus.');
    }

    private function payloadFromRequest(array $validated): array
    {
        return [
            'title' => $validated['title'],
            'day' => $validated['day'],
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'] ?? null,
            'host' => $validated['host'] ?? null,
            'tiktok_profile_url' => $validated['tiktok_profile_url'] ?? null,
            'link_url' => $validated['link_url'] ?? null,
            'description' => $validated['description'] ?? null,
            'is_active' => array_key_exists('is_active', $validated) ? (bool) $validated['is_active'] : true,
            'sort_order' => $validated['sort_order'] ?? 0,
        ];
    }

    protected function refreshCache(): void
    {
        try {
            Artisan::call('live-schedule:cache-json');
        } catch (\Throwable) {
            // Cache refresh is best-effort.
        }
    }

    protected function storeImage(Request $request, ?string $existingImage = null): string
    {
        $uploadPath = public_path('uploads/newsmaker23/live-schedule');
        if (!File::exists($uploadPath)) {
            File::makeDirectory($uploadPath, 0755, true);
        }

        $file = $request->file('image');
        $originalName = preg_replace('/[^A-Za-z0-9._-]/', '_', $file->getClientOriginalName());
        $fileName = time() . '_' . uniqid() . '_' . $originalName;

        $file->move($uploadPath, $fileName);
        $this->deleteImage($existingImage);

        return 'uploads/newsmaker23/live-schedule/' . $fileName;
    }

    protected function deleteImage(?string $imagePath): void
    {
        if ($imagePath && File::exists(public_path($imagePath))) {
            File::delete(public_path($imagePath));
        }
    }
}
