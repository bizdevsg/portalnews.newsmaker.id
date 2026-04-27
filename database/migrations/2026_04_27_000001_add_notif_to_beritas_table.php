<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('beritas') || Schema::hasColumn('beritas', 'notif')) {
            return;
        }

        Schema::table('beritas', function (Blueprint $table) {
            $table->boolean('notif')->default(false)->after('title_bpf');
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('beritas') || !Schema::hasColumn('beritas', 'notif')) {
            return;
        }

        Schema::table('beritas', function (Blueprint $table) {
            $table->dropColumn('notif');
        });
    }
};
