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
        Schema::create('economic_calendars', function (Blueprint $table) {
            $table->id();
            $table->text('sources');
            $table->text('measures')->nullable();
            $table->text('usual_effect')->nullable();
            $table->text('frequency')->nullable();
            $table->text('next_released')->nullable();
            $table->text('notes')->nullable();
            $table->text('why_trader_care')->nullable();
            $table->date('date')->nullable();
            $table->string('time')->nullable();
            $table->string('country');
            $table->enum('impact', ['Low', 'Medium', 'High']); // tambahkan opsinya
            $table->string('figures');
            $table->string('previous')->nullable();
            $table->string('forecast')->nullable();
            $table->string('actual')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('economic_calendars');
    }
};
