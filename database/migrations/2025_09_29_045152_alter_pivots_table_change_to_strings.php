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
        Schema::table('pivots', function (Blueprint $table) {
            $table->string('open', 20)->change();
            $table->string('high', 20)->change();
            $table->string('low', 20)->change();
            $table->string('close', 20)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pivots', function (Blueprint $table) {
            $table->decimal('open', 15, 8)->change();
            $table->decimal('high', 15, 8)->change();
            $table->decimal('low', 15, 8)->change();
            $table->decimal('close', 15, 8)->change();
        });
    }
};
