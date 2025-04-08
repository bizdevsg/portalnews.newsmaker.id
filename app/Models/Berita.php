<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        'category_id',
    ];

    protected $appends = ['images'];

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
        ], fn($value) => !is_null($value))); // Pastikan hanya menyimpan data yang tidak null
    }
}
