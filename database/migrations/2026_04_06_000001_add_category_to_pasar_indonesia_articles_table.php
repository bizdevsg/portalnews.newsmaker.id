<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('pasar_indonesia_articles', 'category')) {
            Schema::table('pasar_indonesia_articles', function (Blueprint $table) {
                $table->string('category', 50)->nullable()->after('type');
                $table->index(['type', 'category'], 'pasar_indonesia_type_category_index');
            });
        }

        DB::table('pasar_indonesia_articles')
            ->where('type', 'berita')
            ->whereNull('category')
            ->update(['category' => 'makro-ekonomi']);
    }

    public function down(): void
    {
        if (!Schema::hasColumn('pasar_indonesia_articles', 'category')) {
            return;
        }

        Schema::table('pasar_indonesia_articles', function (Blueprint $table) {
            $table->dropIndex('pasar_indonesia_type_category_index');
            $table->dropColumn('category');
        });
    }
};
