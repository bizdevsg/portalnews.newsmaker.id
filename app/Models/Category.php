<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Category extends Model
{
    use HasFactory;

    protected $table = 'categories'; // Nama tabel

    protected $fillable = ['name', 'slug']; // Kolom yang bisa diisi

    /**
     * Set slug secara otomatis berdasarkan name
     */
    public static function boot()
    {
        parent::boot();

        // Set slug saat membuat kategori baru
        static::creating(function ($category) {
            $category->slug = Str::slug($category->name);
        });

        // Perbarui slug jika nama berubah saat update
        static::updating(function ($category) {
            $category->slug = Str::slug($category->name);
        });
    }

    /**
     * Relasi ke model Berita
     */
    public function berita()
    {
        return $this->hasMany(Berita::class, 'category_id');
    }
}
