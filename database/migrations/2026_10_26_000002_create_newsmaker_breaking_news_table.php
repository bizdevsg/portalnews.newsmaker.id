<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Breaking News berdiri sendiri - bukan kategori, tidak terhubung ke
     * newsmaker_main_categories/newsmaker_articles sama sekali. Strukturnya
     * sengaja dibuat mirip newsmaker_articles (judul/isi dua bahasa, gambar,
     * author, source, notif) supaya sistem tambah/edit beritanya setara,
     * tapi datanya benar-benar terpisah.
     */
    public function up(): void
    {
        Schema::create('newsmaker_breaking_news', function (Blueprint $table) {
            $table->id();
            $table->string('image');
            $table->string('title_id', 150);
            $table->string('title_en', 150);
            $table->string('slug', 180)->unique();
            $table->text('content_id');
            $table->text('content_en');
            $table->string('author', 100)->nullable();
            $table->foreignId('author_id')
                ->nullable()
                ->constrained('users')
                ->cascadeOnUpdate()
                ->nullOnDelete();
            $table->string('author_initial', 20)->nullable();
            $table->string('source', 150)->nullable();
            $table->boolean('notif')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('newsmaker_breaking_news');
    }
};
