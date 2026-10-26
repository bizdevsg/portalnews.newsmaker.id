<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('newsmaker_breaking_news') || Schema::hasColumn('newsmaker_breaking_news', 'breaking_news_category_id')) {
            return;
        }

        Schema::table('newsmaker_breaking_news', function (Blueprint $table) {
            $table->foreignId('breaking_news_category_id')
                ->after('id')
                ->constrained('breaking_news_categories')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('newsmaker_breaking_news') || !Schema::hasColumn('newsmaker_breaking_news', 'breaking_news_category_id')) {
            return;
        }

        Schema::table('newsmaker_breaking_news', function (Blueprint $table) {
            $table->dropForeign(['breaking_news_category_id']);
            $table->dropColumn('breaking_news_category_id');
        });
    }
};
