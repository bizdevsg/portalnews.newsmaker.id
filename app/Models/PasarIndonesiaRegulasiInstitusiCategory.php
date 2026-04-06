<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class PasarIndonesiaRegulasiInstitusiCategory extends Model
{
    use HasFactory;

    protected $table = 'pasar_indonesia_regulasi_institusi_categories';

    protected $fillable = [
        'name',
        'slug',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function (PasarIndonesiaRegulasiInstitusiCategory $category) {
            $category->slug = static::generateUniqueSlug($category->name);
        });

        static::updating(function (PasarIndonesiaRegulasiInstitusiCategory $category) {
            if ($category->isDirty('name')) {
                $category->slug = static::generateUniqueSlug($category->name, $category->id);
            }
        });
    }

    private static function generateUniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $baseSlug = Str::slug($name);

        if ($baseSlug === '') {
            $baseSlug = 'kategori-regulasi-institusi';
        }

        $slug = $baseSlug;
        $suffix = 1;

        while (
            static::query()
                ->where('slug', $slug)
                ->when($ignoreId, fn ($query) => $query->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $slug = $baseSlug . '-' . $suffix++;
        }

        return $slug;
    }

    public function articles()
    {
        return $this->hasMany(PasarIndonesiaRegulasiInstitusiArticle::class, 'category', 'slug');
    }
}
