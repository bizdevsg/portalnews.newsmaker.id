<?php

namespace App\Http\Controllers;

use App\Models\Iklan;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;

class IklanController extends Controller
{
    public function index(): View
    {
        $tableReady = $this->tableExists();
        $supportsCustomHtml = $this->supportsCustomHtml();
        $iklans = $tableReady
            ? Iklan::query()
                ->orderBy('sort_order')
                ->latest()
                ->get()
            : collect();

        return view('iklan.index', compact('iklans', 'tableReady', 'supportsCustomHtml'));
    }

    public function create(): View|RedirectResponse
    {
        if (!$this->tableExists()) {
            return redirect()->route('iklan.index')
                ->with('error', 'Tabel iklan belum tersedia. Jalankan migrasi terlebih dahulu.');
        }

        return view('iklan.create', [
            'supportsCustomHtml' => $this->supportsCustomHtml(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        if (!$this->tableExists()) {
            return redirect()->route('iklan.index')
                ->with('error', 'Tabel iklan belum tersedia. Jalankan migrasi terlebih dahulu.');
        }

        $supportsCustomHtml = $this->supportsCustomHtml();
        $validated = $request->validate($this->rules($request, $supportsCustomHtml));

        Iklan::create($this->payload($validated, $request, $supportsCustomHtml));

        return redirect()->route('iklan.index')
            ->with('success', 'Iklan berhasil ditambahkan.');
    }

    public function edit(int $id): View|RedirectResponse
    {
        if (!$this->tableExists()) {
            return redirect()->route('iklan.index')
                ->with('error', 'Tabel iklan belum tersedia. Jalankan migrasi terlebih dahulu.');
        }

        $iklan = Iklan::findOrFail($id);

        return view('iklan.edit', compact('iklan') + [
            'supportsCustomHtml' => $this->supportsCustomHtml(),
        ]);
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        if (!$this->tableExists()) {
            return redirect()->route('iklan.index')
                ->with('error', 'Tabel iklan belum tersedia. Jalankan migrasi terlebih dahulu.');
        }

        $iklan = Iklan::findOrFail($id);
        $supportsCustomHtml = $this->supportsCustomHtml();
        $validated = $request->validate($this->rules($request, $supportsCustomHtml));

        $iklan->update($this->payload($validated, $request, $supportsCustomHtml, $iklan));

        return redirect()->route('iklan.index')
            ->with('success', 'Iklan berhasil diperbarui.');
    }

    public function destroy(int $id): RedirectResponse
    {
        if (!$this->tableExists()) {
            return redirect()->route('iklan.index')
                ->with('error', 'Tabel iklan belum tersedia. Jalankan migrasi terlebih dahulu.');
        }

        $iklan = Iklan::findOrFail($id);
        $this->deleteImage($iklan->image);
        $iklan->delete();

        return redirect()->route('iklan.index')
            ->with('success', 'Iklan berhasil dihapus.');
    }

    protected function rules(Request $request, bool $supportsCustomHtml): array
    {
        return [
            'title' => 'required|string|max:150',
            'design_mode' => [
                'required',
                Rule::in($supportsCustomHtml ? ['standard', 'html'] : ['standard']),
            ],
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'cta_label' => 'nullable|string|max:50',
            'cta_url' => 'nullable|string|max:255',
            'modal_html' => [
                'nullable',
                'string',
                Rule::requiredIf(
                    fn() => $supportsCustomHtml
                        && $request->input('design_mode') === 'html'
                        && !$request->hasFile('html_file')
                ),
            ],
            'html_file' => [
                'nullable',
                'file',
                'extensions:html,htm',
                'max:512',
                Rule::requiredIf(
                    fn() => $supportsCustomHtml
                        && $request->input('design_mode') === 'html'
                        && blank($request->input('modal_html'))
                ),
            ],
            'start_at' => 'nullable|date',
            'end_at' => 'nullable|date|after_or_equal:start_at',
            'is_active' => 'nullable|boolean',
            'sort_order' => 'nullable|integer|min:0|max:9999',
        ];
    }

    protected function payload(
        array $validated,
        Request $request,
        bool $supportsCustomHtml,
        ?Iklan $iklan = null
    ): array {
        $data = [
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'cta_label' => $validated['cta_label'] ?? null,
            'cta_url' => $validated['cta_url'] ?? null,
            'start_at' => $validated['start_at'] ?? null,
            'end_at' => $validated['end_at'] ?? null,
            'is_active' => $request->boolean('is_active'),
            'sort_order' => (int) ($validated['sort_order'] ?? 0),
        ];

        if ($supportsCustomHtml) {
            $data['modal_html'] = $this->resolveModalHtml($validated, $request);
        }

        if ($request->hasFile('image')) {
            $data['image'] = $this->storeImage($request, $iklan?->image);
        }

        return $data;
    }

    protected function resolveModalHtml(array $validated, Request $request): ?string
    {
        if (($validated['design_mode'] ?? 'standard') !== 'html') {
            return null;
        }

        $html = $request->hasFile('html_file')
            ? File::get($request->file('html_file')->getRealPath())
            : ($validated['modal_html'] ?? null);

        $html = trim((string) $html);

        return $html !== '' ? $html : null;
    }

    protected function storeImage(Request $request, ?string $existingImage = null): string
    {
        $uploadPath = public_path('uploads/iklans');
        if (!File::exists($uploadPath)) {
            File::makeDirectory($uploadPath, 0755, true);
        }

        $file = $request->file('image');
        $originalName = preg_replace('/[^A-Za-z0-9._-]/', '_', $file->getClientOriginalName());
        $fileName = time() . '_' . uniqid() . '_' . $originalName;

        $file->move($uploadPath, $fileName);
        $this->deleteImage($existingImage);

        return 'uploads/iklans/' . $fileName;
    }

    protected function deleteImage(?string $imagePath): void
    {
        if ($imagePath && File::exists(public_path($imagePath))) {
            File::delete(public_path($imagePath));
        }
    }

    protected function tableExists(): bool
    {
        return Schema::hasTable((new Iklan())->getTable());
    }

    protected function supportsCustomHtml(): bool
    {
        return $this->tableExists() && Schema::hasColumn((new Iklan())->getTable(), 'modal_html');
    }
}

