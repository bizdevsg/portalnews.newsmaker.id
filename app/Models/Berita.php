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
        'title_sg',
        'title_rfb',
        'title_kpf',
        'title_ewf',
        'title_bpf',
        'content',
        'image1',
        'image2',
        'image3',
        'image4',
        'image5',
        'image6',
        'category_id',
        'slug',
    ];
    
    protected $appends = ['images'];
    
    // Method untuk mendapatkan judul berdasarkan PT
    public function getTitleForPt($ptCode)
    {
        $ptTitles = [
            'sg' => $this->title_sg,
            'rfb' => $this->title_rfb,
            'kpf' => $this->title_kpf,
            'ewf' => $this->title_ewf,
            'bpf' => $this->title_bpf,
        ];
        
        return $ptTitles[strtolower($ptCode)] ?? $this->title;
    }

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
            $this->image6 ?: null,
        ], fn($value) => !is_null($value)));
    }
}
