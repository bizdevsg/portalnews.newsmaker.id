<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('newsmaker_live_schedules', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('day');
            $table->time('start_time');
            $table->time('end_time')->nullable();
            $table->string('host')->nullable();
            $table->string('platform')->nullable();
            $table->string('link_url')->nullable();
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();

            $table->index(['day', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('newsmaker_live_schedules');
    }
};
