<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('pasar_indonesia_regulasi_institusi_articles')) {
            return;
        }

        Schema::create('pasar_indonesia_regulasi_institusi_articles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('author_id')
                ->constrained('users')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->string('image');
            $table->string('title_id', 150);
            $table->string('title_en', 150);
            $table->string('slug', 180)->unique();
            $table->text('content_id');
            $table->text('content_en');
            $table->string('source', 150);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pasar_indonesia_regulasi_institusi_articles');
    }
};
