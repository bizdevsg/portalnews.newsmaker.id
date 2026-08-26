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
        Schema::table('economic_calendars', function (Blueprint $table) {
            $table->index('date');
            $table->index(['economic_calendar_category_id', 'date'], 'economic_calendars_category_date_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('economic_calendars', function (Blueprint $table) {
            $table->dropIndex(['date']);
            $table->dropIndex('economic_calendars_category_date_index');
        });
    }
};
