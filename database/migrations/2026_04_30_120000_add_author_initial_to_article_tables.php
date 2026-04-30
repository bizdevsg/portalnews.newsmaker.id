<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('pasar_indonesia_articles') && !Schema::hasColumn('pasar_indonesia_articles', 'author_initial')) {
            Schema::table('pasar_indonesia_articles', function (Blueprint $table) {
                $table->string('author_initial', 10)->nullable()->after('author_id');
            });
        }

        if (Schema::hasTable('newsmaker_articles') && !Schema::hasColumn('newsmaker_articles', 'author_initial')) {
            Schema::table('newsmaker_articles', function (Blueprint $table) {
                $table->string('author_initial', 10)->nullable()->after('author_id');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('pasar_indonesia_articles') && Schema::hasColumn('pasar_indonesia_articles', 'author_initial')) {
            Schema::table('pasar_indonesia_articles', function (Blueprint $table) {
                $table->dropColumn('author_initial');
            });
        }

        if (Schema::hasTable('newsmaker_articles') && Schema::hasColumn('newsmaker_articles', 'author_initial')) {
            Schema::table('newsmaker_articles', function (Blueprint $table) {
                $table->dropColumn('author_initial');
            });
        }
    }
};

