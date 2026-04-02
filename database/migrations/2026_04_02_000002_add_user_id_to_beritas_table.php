<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('beritas') || Schema::hasColumn('beritas', 'user_id')) {
            return;
        }

        Schema::table('beritas', function (Blueprint $table) {
            $table->foreignId('user_id')
                ->nullable()
                ->after('category_id')
                ->constrained('users')
                ->cascadeOnUpdate()
                ->nullOnDelete();
        });

        $defaultUserId = DB::table('users')->orderBy('id')->value('id');

        if ($defaultUserId !== null) {
            DB::table('beritas')
                ->whereNull('user_id')
                ->update(['user_id' => $defaultUserId]);
        }
    }

    public function down(): void
    {
        if (!Schema::hasTable('beritas') || !Schema::hasColumn('beritas', 'user_id')) {
            return;
        }

        Schema::table('beritas', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
    }
};
