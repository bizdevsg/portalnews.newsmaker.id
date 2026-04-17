<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('newsmaker_video_briefings', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->longText('embed_code');
            $table->string('backup_video_url')->nullable();
            $table->string('image')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('newsmaker_video_briefings');
    }
};
