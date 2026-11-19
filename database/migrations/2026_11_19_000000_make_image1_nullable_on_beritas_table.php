<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Field SG (image1) sudah dihapus dari form, jadi kolomnya harus boleh kosong.
    public function up(): void
    {
        Schema::table('beritas', function (Blueprint $table) {
            $table->text('image1')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('beritas', function (Blueprint $table) {
            $table->text('image1')->nullable(false)->change();
        });
    }
};
