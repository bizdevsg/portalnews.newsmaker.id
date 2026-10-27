<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fed_rate_meeting_rows', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fed_rate_meeting_id')->constrained('fed_rate_meetings')->cascadeOnDelete();
            $table->decimal('target_lower', 5, 2);
            $table->decimal('target_upper', 5, 2);
            $table->decimal('current_probability', 5, 2);
            $table->decimal('previous_day_probability', 5, 2)->nullable();
            $table->decimal('previous_week_probability', 5, 2)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fed_rate_meeting_rows');
    }
};
