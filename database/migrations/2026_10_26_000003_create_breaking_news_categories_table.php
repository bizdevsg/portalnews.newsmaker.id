<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Kategori milik Breaking News sendiri - mirip newsmaker_main_categories
     * secara konsep, tapi tabel terpisah sama sekali, tidak dipakai bareng
     * sistem kategori Newsmaker23 yang lain.
     */
    public function up(): void
    {
        Schema::create('breaking_news_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('slug', 120)->unique();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('breaking_news_categories');
    }
};
