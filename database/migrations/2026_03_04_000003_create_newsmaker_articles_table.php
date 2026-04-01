<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('newsmaker_articles')) {
            return;
        }

        Schema::create('newsmaker_articles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('main_category_id')
                ->constrained('newsmaker_main_categories')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->foreignId('sub_category_id')
                ->constrained('newsmaker_sub_categories')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->string('image');
            $table->string('title_id', 150);
            $table->string('title_en', 150);
            $table->text('content_id');
            $table->string('author', 100);
            $table->string('source', 150);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('newsmaker_articles');
    }
};
