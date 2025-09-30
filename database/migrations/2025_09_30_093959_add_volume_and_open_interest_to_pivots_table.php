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
            $table->text('volume')->nullable();
            $table->text('open_interest')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pivots', function (Blueprint $table) {
            $table->dropColumn(['volume', 'open_interest']);
        });
    }
};
