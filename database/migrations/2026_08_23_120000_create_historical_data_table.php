<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('historical_data')) {
            return;
        }

        Schema::create('historical_data', function (Blueprint $table) {
            $table->increments('id');
            $table->string('symbol')->index();
            $table->string('date');
            $table->string('event')->nullable();
            $table->float('open')->nullable();
            $table->float('high')->nullable();
            $table->float('low')->nullable();
            $table->float('close')->nullable();
            $table->string('change')->nullable();
            $table->float('volume')->nullable();
            $table->float('openInterest')->nullable();
            $table->dateTime('createdAt');
            $table->dateTime('updatedAt');

            $table->unique(['symbol', 'date'], 'historical_data_symbol_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('historical_data');
    }
};
