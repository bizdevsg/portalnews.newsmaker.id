<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('pasar_indonesia_categories')) {
            Schema::create('pasar_indonesia_categories', function (Blueprint $table) {
                $table->id();
                $table->string('name', 100)->unique();
                $table->string('slug', 140)->unique();
                $table->timestamps();
            });
        }

        $defaults = [
            'makro-ekonomi' => 'Makro Ekonomi',
            'saham' => 'Pasar Saham',
            'obligasi-sbn' => 'Obligasi & SBN',
            'rupiah-dan-valas' => 'Rupiah & Valas',
            'komoditas' => 'Komoditas',
            'kebijakan-regulasi' => 'Kebijakan & Regulasi',
            'korporasi-emiten' => 'Korporasi & Emiten',
            'investasi-strategi' => 'Investasi & Strategi',
        ];

        foreach ($defaults as $slug => $name) {
            DB::table('pasar_indonesia_categories')->updateOrInsert(
                ['slug' => $slug],
                [
                    'name' => $name,
                    'updated_at' => now(),
                    'created_at' => now(),
                ]
            );
        }

        $existingSlugs = DB::table('pasar_indonesia_articles')
            ->where('type', 'berita')
            ->whereNotNull('category')
            ->distinct()
            ->pluck('category')
            ->filter(fn ($value) => is_string($value) && $value !== '')
            ->values();

        foreach ($existingSlugs as $slug) {
            DB::table('pasar_indonesia_categories')->updateOrInsert(
                ['slug' => $slug],
                [
                    'name' => Str::of($slug)->replace('-', ' ')->title()->toString(),
                    'updated_at' => now(),
                    'created_at' => now(),
                ]
            );
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('pasar_indonesia_categories');
    }
};
