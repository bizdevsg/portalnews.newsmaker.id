<?php

namespace App\Http\Controllers;

use App\Models\PasarIndonesiaArticle;
use App\Models\PasarIndonesiaRegulasiInstitusiArticle;
use App\Services\PasarIndonesiaArticleMoveService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PasarIndonesiaMoveController extends Controller
{
    public function __construct(
        private readonly PasarIndonesiaArticleMoveService $moveService
    ) {
    }

    public function move(Request $request, string $from, $id)
    {
        abort_unless(in_array($from, PasarIndonesiaArticleMoveService::MODULES, true), 404);

        $validated = $request->validate([
            'to_module' => ['required', 'string', Rule::in(PasarIndonesiaArticleMoveService::MODULES)],
        ]);

        $to = $validated['to_module'];

        if ($to === $from) {
            return back()->withErrors(['to_module' => 'Modul tujuan harus berbeda dari modul asal.']);
        }

        $categoryId = null;

        if ($to === 'berita') {
            $categoryId = $request->validate(['category_id' => 'required|exists:pasar_indonesia_categories,id'])['category_id'];
        } elseif ($to === 'regulasi-institusi') {
            $categoryId = $request->validate(['category_id' => 'required|exists:pasar_indonesia_regulasi_institusi_categories,id'])['category_id'];
        }

        $source = $this->resolveSource($from, $id);
        $targetId = $this->moveService->move($from, $to, $source, $categoryId);

        return redirect()
            ->route($this->moveService->editRouteFor($to), $targetId)
            ->with('success', 'Artikel berhasil dipindahkan ke modul ' . $this->moveService->moduleLabel($to) . '.');
    }

    private function resolveSource(string $module, $id)
    {
        return match ($module) {
            'berita' => PasarIndonesiaArticle::where('type', 'berita')->findOrFail($id),
            'analisis' => PasarIndonesiaArticle::where('type', 'analisis')->findOrFail($id),
            'regulasi-institusi' => PasarIndonesiaRegulasiInstitusiArticle::findOrFail($id),
        };
    }
}
