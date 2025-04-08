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

    protected $appends = ['images']; // Otomatis menambahkan "images" ke JSON

    /**
     * Relasi ke kategori (Setiap berita punya satu kategori).
     */
    public function kategori()
    {
        return $this->belongsTo(Category::class, 'category_id'); // Ubah Category ke Kategori
    }

    /**
     * Mendapatkan gambar dalam bentuk array.
     */
    public function getImagesAttribute()
    {
        return array_values(array_filter([
            $this->image1,
            $this->image2,
            $this->image3,
            $this->image4,
            $this->image5,
        ]));
    }
}
