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
            $table->boolean('isBankHoliday')->nullable()->default(false)->after('notes');
            $table->text('bankHolidayNote')->nullable()->after('isBankHoliday');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('economic_calendars', function (Blueprint $table) {
            $table->dropColumn('isBankHoliday');
            $table->dropColumn('bankHolidayNote');
        });
    }
};
