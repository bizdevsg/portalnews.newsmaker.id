<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Rentang jam relevan untuk tiap berita Breaking News (mis. 09:00-15:00)
     * - kapan berita itu masih "hangat"/relevan ditampilkan.
     */
    public function up(): void
    {
        if (!Schema::hasTable('newsmaker_breaking_news') || Schema::hasColumn('newsmaker_breaking_news', 'start_time')) {
            return;
        }

        Schema::table('newsmaker_breaking_news', function (Blueprint $table) {
            $table->time('start_time')->after('source');
            $table->time('end_time')->after('start_time');
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('newsmaker_breaking_news') || !Schema::hasColumn('newsmaker_breaking_news', 'start_time')) {
            return;
        }

        Schema::table('newsmaker_breaking_news', function (Blueprint $table) {
            $table->dropColumn(['start_time', 'end_time']);
        });
    }
};
