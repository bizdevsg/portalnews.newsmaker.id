<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('newsmaker_articles') || Schema::hasColumn('newsmaker_articles', 'content_en')) {
            return;
        }

        Schema::table('newsmaker_articles', function (Blueprint $table) {
            $table->text('content_en')->after('content_id');
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('newsmaker_articles') || !Schema::hasColumn('newsmaker_articles', 'content_en')) {
            return;
        }

        Schema::table('newsmaker_articles', function (Blueprint $table) {
            $table->dropColumn('content_en');
        });
    }
};
