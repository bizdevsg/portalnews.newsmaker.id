<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('newsmaker_articles') || Schema::hasColumn('newsmaker_articles', 'slug')) {
            return;
        }

        Schema::table('newsmaker_articles', function (Blueprint $table) {
            $table->string('slug', 180)->nullable()->after('title_en')->unique();
        });

        DB::table('newsmaker_articles')
            ->select('id', 'title_id', 'created_at')
            ->orderBy('id')
            ->get()
            ->each(function ($article) {
                DB::table('newsmaker_articles')
                    ->where('id', $article->id)
                    ->update([
                        'slug' => $this->generateUniqueSlug($article->title_id, $article->id, $article->created_at),
                    ]);
            });
    }

    public function down(): void
    {
        if (!Schema::hasTable('newsmaker_articles') || !Schema::hasColumn('newsmaker_articles', 'slug')) {
            return;
        }

        Schema::table('newsmaker_articles', function (Blueprint $table) {
            $table->dropUnique(['slug']);
            $table->dropColumn('slug');
        });
    }

    private function generateUniqueSlug(?string $title, int $ignoreId, $createdAt = null): string
    {
        $titleSlug = Str::slug($title ?: 'berita-newsmaker');

        if ($titleSlug === '') {
            $titleSlug = 'berita-newsmaker';
        }

        $datePrefix = Carbon::parse($createdAt ?: now())->format('dmY');
        $baseSlug = $datePrefix . '-' . $titleSlug;
        $slug = $baseSlug;
        $suffix = 1;

        while (
            DB::table('newsmaker_articles')
                ->where('slug', $slug)
                ->where('id', '!=', $ignoreId)
                ->exists()
        ) {
            $slug = $baseSlug . '-' . $suffix++;
        }

        return $slug;
    }
};
