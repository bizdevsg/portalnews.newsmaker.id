<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class PasarIndonesiaCategory extends Model
{
    use HasFactory;

    protected $table = 'pasar_indonesia_categories';

    protected $fillable = [
        'name',
        'slug',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function (PasarIndonesiaCategory $category) {
            $category->slug = static::generateUniqueSlug($category->name);
        });

        static::updating(function (PasarIndonesiaCategory $category) {
            if ($category->isDirty('name')) {
                $category->slug = static::generateUniqueSlug($category->name, $category->id);
            }
        });
    }

    private static function generateUniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $baseSlug = Str::slug($name);

        if ($baseSlug === '') {
            $baseSlug = 'kategori-pasar-indonesia';
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
        return $this->hasMany(PasarIndonesiaArticle::class, 'category', 'slug')
            ->where('type', 'berita');
    }
}
