<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('pasar_indonesia_regulasi_institusi_categories')) {
            Schema::create('pasar_indonesia_regulasi_institusi_categories', function (Blueprint $table) {
                $table->id();
                $table->string('name', 100)->unique();
                $table->string('slug', 120)->unique();
                $table->timestamps();
            });
        }

        DB::table('pasar_indonesia_regulasi_institusi_categories')->updateOrInsert(
            ['slug' => 'umum'],
            [
                'name' => 'Umum',
                'updated_at' => now(),
                'created_at' => now(),
            ]
        );
    }

    public function down(): void
    {
        Schema::dropIfExists('pasar_indonesia_regulasi_institusi_categories');
    }
};
