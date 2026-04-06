<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('pasar_indonesia_categories')) {
            return;
        }

        $fallbackCategory = DB::table('pasar_indonesia_categories')
            ->where('slug', '!=', 'regulasi-institusi')
            ->orderByRaw("CASE WHEN slug = 'makro-ekonomi' THEN 0 ELSE 1 END")
            ->orderBy('id')
            ->value('slug');

        if (Schema::hasTable('pasar_indonesia_articles')) {
            DB::table('pasar_indonesia_articles')
                ->where('type', 'berita')
                ->where('category', 'regulasi-institusi')
                ->update([
                    'category' => $fallbackCategory,
                    'updated_at' => now(),
                ]);
        }

        DB::table('pasar_indonesia_categories')
            ->where('slug', 'regulasi-institusi')
            ->delete();
    }

    public function down(): void
    {
        if (!Schema::hasTable('pasar_indonesia_categories')) {
            return;
        }

        DB::table('pasar_indonesia_categories')->updateOrInsert(
            ['slug' => 'regulasi-institusi'],
            [
                'name' => 'Regulasi & Institusi',
                'updated_at' => now(),
                'created_at' => now(),
            ]
        );
    }
};
