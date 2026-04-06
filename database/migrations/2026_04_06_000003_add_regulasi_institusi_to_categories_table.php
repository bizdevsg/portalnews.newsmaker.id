<?php

use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        // Regulasi & Institusi dipindahkan jadi modul terpisah
        // dan tidak lagi dibuat sebagai kategori Berita Pasar Indonesia.
    }

    public function down(): void
    {
        // No-op.
    }
};
