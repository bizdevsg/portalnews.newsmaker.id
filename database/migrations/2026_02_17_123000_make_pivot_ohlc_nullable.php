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
            $table->text('open')->nullable()->change();
            $table->text('high')->nullable()->change();
            $table->text('low')->nullable()->change();
            $table->text('close')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pivots', function (Blueprint $table) {
            $table->text('open')->nullable(false)->change();
            $table->text('high')->nullable(false)->change();
            $table->text('low')->nullable(false)->change();
            $table->text('close')->nullable(false)->change();
        });
    }
};
