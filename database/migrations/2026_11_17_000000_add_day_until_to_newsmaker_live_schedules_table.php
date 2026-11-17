<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('newsmaker_live_schedules', function (Blueprint $table) {
            $table->string('day_until')->nullable()->after('day');
        });
    }

    public function down(): void
    {
        Schema::table('newsmaker_live_schedules', function (Blueprint $table) {
            $table->dropColumn('day_until');
        });
    }
};
