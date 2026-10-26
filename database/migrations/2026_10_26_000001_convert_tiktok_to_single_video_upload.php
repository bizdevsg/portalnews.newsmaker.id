<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Switches `tiktoks` from "many embed-code rows" to a single uploaded
     * video record. The old embed rows have no real video file behind them,
     * so they can't be migrated into the new model - cleared per product
     * decision (confirmed with the user before running this).
     */
    public function up(): void
    {
        if (!Schema::hasTable('tiktoks')) {
            return;
        }

        DB::table('tiktoks')->truncate();

        if (Schema::hasColumn('tiktoks', 'embed_code') || Schema::hasColumn('tiktoks', 'backup_video_url')) {
            Schema::table('tiktoks', function (Blueprint $table) {
                if (Schema::hasColumn('tiktoks', 'embed_code')) {
                    $table->dropColumn('embed_code');
                }
                if (Schema::hasColumn('tiktoks', 'backup_video_url')) {
                    $table->dropColumn('backup_video_url');
                }
            });
        }

        if (!Schema::hasColumn('tiktoks', 'video')) {
            Schema::table('tiktoks', function (Blueprint $table) {
                $table->string('video')->nullable()->after('title');
            });
        }

        Schema::table('tiktoks', function (Blueprint $table) {
            $table->string('title')->nullable()->change();
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('tiktoks')) {
            return;
        }

        DB::table('tiktoks')->truncate();

        if (Schema::hasColumn('tiktoks', 'video')) {
            Schema::table('tiktoks', function (Blueprint $table) {
                $table->dropColumn('video');
            });
        }

        if (!Schema::hasColumn('tiktoks', 'embed_code')) {
            Schema::table('tiktoks', function (Blueprint $table) {
                $table->longText('embed_code')->after('title');
            });
        }

        if (!Schema::hasColumn('tiktoks', 'backup_video_url')) {
            Schema::table('tiktoks', function (Blueprint $table) {
                $table->string('backup_video_url')->nullable()->after('embed_code');
            });
        }

        Schema::table('tiktoks', function (Blueprint $table) {
            $table->string('title')->nullable(false)->change();
        });
    }
};
