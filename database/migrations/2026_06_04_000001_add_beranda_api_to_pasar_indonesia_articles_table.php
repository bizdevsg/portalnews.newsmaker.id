<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('pasar_indonesia_articles') || Schema::hasColumn('pasar_indonesia_articles', 'beranda_api')) {
            return;
        }

        Schema::table('pasar_indonesia_articles', function (Blueprint $table) {
            $table->boolean('beranda_api')->default(false)->after('notif');
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('pasar_indonesia_articles') || !Schema::hasColumn('pasar_indonesia_articles', 'beranda_api')) {
            return;
        }

        Schema::table('pasar_indonesia_articles', function (Blueprint $table) {
            $table->dropColumn('beranda_api');
        });
    }
};
