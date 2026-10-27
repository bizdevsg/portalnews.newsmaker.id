<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fed_rate_meetings', function (Blueprint $table) {
            $table->id();
            $table->date('meeting_date')->unique();
            $table->time('meeting_time')->default('14:00:00');
            $table->decimal('future_price', 8, 3)->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fed_rate_meetings');
    }
};
