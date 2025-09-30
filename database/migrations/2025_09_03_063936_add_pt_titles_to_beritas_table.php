<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('beritas', function (Blueprint $table) {
            if (!Schema::hasColumn('beritas', 'title_sg')) {
                $table->string('title_sg')->after('title')->nullable();
            }
            if (!Schema::hasColumn('beritas', 'title_rfb')) {
                $table->string('title_rfb')->after('title_sg')->nullable();
            }
            if (!Schema::hasColumn('beritas', 'title_kpf')) {
                $table->string('title_kpf')->after('title_rfb')->nullable();
            }
            if (!Schema::hasColumn('beritas', 'title_ewf')) {
                $table->string('title_ewf')->after('title_kpf')->nullable();
            }
            if (!Schema::hasColumn('beritas', 'title_bpf')) {
                $table->string('title_bpf')->after('title_ewf')->nullable();
            }
            if (!Schema::hasColumn('beritas', 'title_backup')) {
                $table->string('title_backup')->after('title_bpf')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('beritas', function (Blueprint $table) {
            $table->dropColumn([
                'title_sg',
                'title_rfb',
                'title_kpf',
                'title_ewf',
                'title_bpf',
                'title_backup'
            ]);
        });
    }
};
