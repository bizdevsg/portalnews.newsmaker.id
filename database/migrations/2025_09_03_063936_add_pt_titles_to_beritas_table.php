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
            $table->string('title_sg')->after('title')->nullable();
            $table->string('title_rfb')->after('title_sg')->nullable();
            $table->string('title_kpf')->after('title_rfb')->nullable();
            $table->string('title_ewf')->after('title_kpf')->nullable();
            $table->string('title_bpf')->after('title_ewf')->nullable();
            $table->string('title_backup')->after('title_bpf')->nullable();
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
