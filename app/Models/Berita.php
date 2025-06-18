<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Berita extends Model
{
    use HasFactory;

    protected $table = 'beritas';

    protected $fillable = [
        'title',
        'content',
        'image1',
        'image2',
        'image3',
        'image4',
        'image5',
        'image6',
        'category_id',
        'slug', // tambahkan agar slug bisa diisi saat disimpan otomatis
    ];

    protected $appends = ['images'];

    /**
     * Boot method untuk membuat slug otomatis dari title.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($berita) {
            $datePrefix = now()->format('dmY'); // Format: 07052025
            $slugBase = $datePrefix . '-' . Str::slug($berita->title);
            $slug = $slugBase;
            $count = 1;

            while (static::where('slug', $slug)->exists()) {
                $slug = $slugBase . '-' . $count++;
            }

            $berita->slug = $slug;
        });

        static::updating(function ($berita) {
            if ($berita->isDirty('title')) {
                $datePrefix = now()->format('dmY');
                $slugBase = $datePrefix . '-' . Str::slug($berita->title);
                $slug = $slugBase;
                $count = 1;

                while (static::where('slug', $slug)->where('id', '!=', $berita->id)->exists()) {
                    $slug = $slugBase . '-' . $count++;
                }

                $berita->slug = $slug;
            }
        });
    }

    /**
     * Relasi ke kategori (Setiap berita punya satu kategori).
     */
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    /**
     * Mendapatkan gambar dalam bentuk array.
     */
    public function getImagesAttribute()
    {
        return array_values(array_filter([
            $this->image1 ?: null,
            $this->image2 ?: null,
            $this->image3 ?: null,
            $this->image4 ?: null,
            $this->image5 ?: null,
        ], fn($value) => !is_null($value)));
    }
}
