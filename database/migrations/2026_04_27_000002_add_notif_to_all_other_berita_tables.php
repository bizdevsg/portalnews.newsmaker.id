<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('newsmaker_articles') && !Schema::hasColumn('newsmaker_articles', 'notif')) {
            Schema::table('newsmaker_articles', function (Blueprint $table) {
                $table->boolean('notif')->default(false)->after('title_en');
            });
        }

        if (Schema::hasTable('pasar_indonesia_articles') && !Schema::hasColumn('pasar_indonesia_articles', 'notif')) {
            Schema::table('pasar_indonesia_articles', function (Blueprint $table) {
                $table->boolean('notif')->default(false)->after('title_en');
            });
        }

        if (Schema::hasTable('pasar_indonesia_regulasi_institusi_articles') && !Schema::hasColumn('pasar_indonesia_regulasi_institusi_articles', 'notif')) {
            Schema::table('pasar_indonesia_regulasi_institusi_articles', function (Blueprint $table) {
                $table->boolean('notif')->default(false)->after('title_en');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('newsmaker_articles') && Schema::hasColumn('newsmaker_articles', 'notif')) {
            Schema::table('newsmaker_articles', function (Blueprint $table) {
                $table->dropColumn('notif');
            });
        }

        if (Schema::hasTable('pasar_indonesia_articles') && Schema::hasColumn('pasar_indonesia_articles', 'notif')) {
            Schema::table('pasar_indonesia_articles', function (Blueprint $table) {
                $table->dropColumn('notif');
            });
        }

        if (Schema::hasTable('pasar_indonesia_regulasi_institusi_articles') && Schema::hasColumn('pasar_indonesia_regulasi_institusi_articles', 'notif')) {
            Schema::table('pasar_indonesia_regulasi_institusi_articles', function (Blueprint $table) {
                $table->dropColumn('notif');
            });
        }
    }
};
