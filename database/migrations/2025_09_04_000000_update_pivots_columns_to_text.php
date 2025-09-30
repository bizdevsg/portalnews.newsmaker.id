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
            $table->text('open')->change();
            $table->text('high')->change();
            $table->text('low')->change();
            $table->text('close')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pivots', function (Blueprint $table) {
            $table->decimal('open', 10, 2)->change();
            $table->decimal('high', 10, 2)->change();
            $table->decimal('low', 10, 2)->change();
            $table->decimal('close', 10, 2)->change();
        });
    }
};
