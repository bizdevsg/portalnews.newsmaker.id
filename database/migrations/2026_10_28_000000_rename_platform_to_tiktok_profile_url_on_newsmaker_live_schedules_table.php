<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('newsmaker_live_schedules', function (Blueprint $table) {
            $table->renameColumn('platform', 'tiktok_profile_url');
        });
    }

    public function down(): void
    {
        Schema::table('newsmaker_live_schedules', function (Blueprint $table) {
            $table->renameColumn('tiktok_profile_url', 'platform');
        });
    }
};
