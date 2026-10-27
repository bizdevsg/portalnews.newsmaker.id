<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fed_rate_settings', function (Blueprint $table) {
            $table->id();
            $table->decimal('current_target_lower', 5, 2);
            $table->decimal('current_target_upper', 5, 2);
            $table->dateTime('source_updated_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fed_rate_settings');
    }
};
