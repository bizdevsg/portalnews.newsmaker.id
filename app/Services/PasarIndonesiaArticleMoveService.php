<?php

namespace App\Services;

use App\Models\PasarIndonesiaArticle;
use App\Models\PasarIndonesiaCategory;
use App\Models\PasarIndonesiaRegulasiInstitusiArticle;
use App\Models\PasarIndonesiaRegulasiInstitusiCategory;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class PasarIndonesiaArticleMoveService
{
    public const MODULES = ['berita', 'analisis', 'regulasi-institusi'];

    /**
     * Pindahkan satu artikel dari modul $from ke modul $to.
     * Mengembalikan id artikel di tabel tujuan (tetap sama untuk Berita<->Analisis, baru untuk lintas tabel).
     */
    public function move(string $from, string $to, $source, ?int $categoryId): int
    {
        $category = $this->resolveCategorySlug($to, $categoryId);

        $targetId = DB::transaction(function () use ($from, $to, $source, $category) {
            // Berita <-> Analisis berbagi tabel yang sama: cukup ganti kolom type.
            if (in_array($from, ['berita', 'analisis'], true) && in_array($to, ['berita', 'analisis'], true)) {
                $source->update([
                    'type' => $to,
                    'category' => $to === 'berita' ? $category : null,
                ]);

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
                    'category' => $category,
                    'beranda_api' => false,
                ])),
                'analisis' => PasarIndonesiaArticle::create(array_merge($common, [
                    'type' => 'analisis',
                    'category' => null,
                    'beranda_api' => false,
                ])),
                'regulasi-institusi' => PasarIndonesiaRegulasiInstitusiArticle::create($common + [
                    'category' => $category,
                ]),
            };

            $source->delete();

            return $target->id;
        });

        $this->refreshCaches();

        return $targetId;
    }

    public function editRouteFor(string $module): string
    {
        return match ($module) {
            'berita' => 'pasar-indonesia.berita.edit',
            'analisis' => 'pasar-indonesia.analisis.edit',
            'regulasi-institusi' => 'regulasi-institusi.edit',
        };
    }

    public function moduleLabel(string $module): string
    {
        return match ($module) {
            'berita' => 'Berita',
            'analisis' => 'Analisis',
            'regulasi-institusi' => 'Regulasi & Institusi',
        };
    }

    private function resolveCategorySlug(string $to, ?int $categoryId): ?string
    {
        if ($to === 'berita') {
            return PasarIndonesiaCategory::findOrFail($categoryId)->slug;
        }

        if ($to === 'regulasi-institusi') {
            return PasarIndonesiaRegulasiInstitusiCategory::findOrFail($categoryId)->slug;
        }

        return null;
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
