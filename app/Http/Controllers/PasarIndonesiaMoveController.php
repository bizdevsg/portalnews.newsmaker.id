<?php

namespace App\Http\Controllers;

use App\Models\PasarIndonesiaArticle;
use App\Models\PasarIndonesiaCategory;
use App\Models\PasarIndonesiaRegulasiInstitusiArticle;
use App\Models\PasarIndonesiaRegulasiInstitusiCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class PasarIndonesiaMoveController extends Controller
{
    private const MODULES = ['berita', 'analisis', 'regulasi-institusi'];

    public function move(Request $request, string $from, $id)
    {
        abort_unless(in_array($from, self::MODULES, true), 404);

        $validated = $request->validate([
            'to_module' => ['required', 'string', Rule::in(self::MODULES)],
        ]);

        $to = $validated['to_module'];

        if ($to === $from) {
            return back()->withErrors(['to_module' => 'Modul tujuan harus berbeda dari modul asal.']);
        }

        // Author (inisial) selalu ikut dari artikel asal - tidak perlu dipilih ulang saat pindah.
        $extra = [];

        if ($to === 'berita') {
            $extra = $request->validate([
                'category_id' => 'required|exists:pasar_indonesia_categories,id',
            ]);
            $extra['category'] = PasarIndonesiaCategory::findOrFail($extra['category_id'])->slug;
        } elseif ($to === 'regulasi-institusi') {
            $extra = $request->validate([
                'category_id' => 'required|exists:pasar_indonesia_regulasi_institusi_categories,id',
            ]);
            $extra['category'] = PasarIndonesiaRegulasiInstitusiCategory::findOrFail($extra['category_id'])->slug;
        }

        $source = $this->resolveSource($from, $id);

        $targetId = DB::transaction(function () use ($from, $to, $source, $extra) {
            // Berita <-> Analisis berbagi tabel yang sama: cukup ganti kolom type.
            if (in_array($from, ['berita', 'analisis'], true) && in_array($to, ['berita', 'analisis'], true)) {
                $payload = ['type' => $to];
                $payload['category'] = $to === 'berita' ? $extra['category'] : null;

                $source->update($payload);

                return $source->id;
            }

            // Pindah lintas tabel (Regulasi & Institusi punya skema sendiri): copy row ke tabel tujuan, hapus yang lama.
            $common = [
                'image' => $source->image,
                'title_id' => $source->title_id,
                'title_en' => $source->title_en,
                'notif' => $source->notif,
                'content_id' => $source->content_id,
                'content_en' => $source->content_en,
                'author_id' => $source->author_id,
                'author_initial' => $source->author_initial,
                'source' => $source->source,
            ];

            $target = match ($to) {
                'berita' => PasarIndonesiaArticle::create(array_merge($common, [
                    'type' => 'berita',
                    'category' => $extra['category'],
                    'beranda_api' => false,
                ])),
                'analisis' => PasarIndonesiaArticle::create(array_merge($common, [
                    'type' => 'analisis',
                    'category' => null,
                    'beranda_api' => false,
                ])),
                'regulasi-institusi' => PasarIndonesiaRegulasiInstitusiArticle::create($common + [
                    'category' => $extra['category'],
                ]),
            };

            $source->delete();

            return $target->id;
        });

        $this->refreshCaches();

        return redirect()
            ->route($this->editRouteFor($to), $targetId)
            ->with('success', 'Artikel berhasil dipindahkan ke modul ' . $this->moduleLabel($to) . '.');
    }

    private function resolveSource(string $module, $id)
    {
        return match ($module) {
            'berita' => PasarIndonesiaArticle::where('type', 'berita')->findOrFail($id),
            'analisis' => PasarIndonesiaArticle::where('type', 'analisis')->findOrFail($id),
            'regulasi-institusi' => PasarIndonesiaRegulasiInstitusiArticle::findOrFail($id),
        };
    }

    private function editRouteFor(string $module): string
    {
        return match ($module) {
            'berita' => 'pasar-indonesia.berita.edit',
            'analisis' => 'pasar-indonesia.analisis.edit',
            'regulasi-institusi' => 'regulasi-institusi.edit',
        };
    }

    private function moduleLabel(string $module): string
    {
        return match ($module) {
            'berita' => 'Berita',
            'analisis' => 'Analisis',
            'regulasi-institusi' => 'Regulasi & Institusi',
        };
    }

    private function refreshCaches(): void
    {
        try {
            Artisan::call('pasar-indonesia:cache-json');
            Artisan::call('pasar-indonesia-regulasi:cache-json');
        } catch (\Throwable) {
            // Cache refresh is best-effort.
        }
    }
}
