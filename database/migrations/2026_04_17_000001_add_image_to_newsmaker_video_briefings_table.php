<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('newsmaker_video_briefings')) {
            return;
        }

        if (Schema::hasColumn('newsmaker_video_briefings', 'image')) {
            return;
        }

        Schema::table('newsmaker_video_briefings', function (Blueprint $table) {
            $table->string('image')->nullable()->after('backup_video_url');
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('newsmaker_video_briefings')) {
            return;
        }

        if (!Schema::hasColumn('newsmaker_video_briefings', 'image')) {
            return;
        }

        Schema::table('newsmaker_video_briefings', function (Blueprint $table) {
            $table->dropColumn('image');
        });
    }
};

