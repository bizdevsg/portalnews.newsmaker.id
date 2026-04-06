<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('pasar_indonesia_regulasi_institusi_articles', 'category')) {
            Schema::table('pasar_indonesia_regulasi_institusi_articles', function (Blueprint $table) {
                $table->string('category', 120)->nullable()->after('slug');
                $table->index('category', 'pasar_indonesia_regulasi_institusi_articles_category_index');
            });
        }

        DB::table('pasar_indonesia_regulasi_institusi_articles')
            ->whereNull('category')
            ->update(['category' => 'umum']);
    }

    public function down(): void
    {
        if (!Schema::hasColumn('pasar_indonesia_regulasi_institusi_articles', 'category')) {
            return;
        }

        Schema::table('pasar_indonesia_regulasi_institusi_articles', function (Blueprint $table) {
            $table->dropIndex('pasar_indonesia_regulasi_institusi_articles_category_index');
            $table->dropColumn('category');
        });
    }
};
