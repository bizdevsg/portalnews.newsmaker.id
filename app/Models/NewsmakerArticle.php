<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class NewsmakerArticle extends Model
{
    use HasFactory;

    protected $table = 'newsmaker_articles';

    protected $fillable = [
        'main_category_id',
        'sub_category_id',
        'image',
        'title_id',
        'title_en',
        'slug',
        'content_id',
        'content_en',
        'author',
        'source',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($article) {
            $article->slug = static::generateUniqueSlug($article->title_id);
        });

        static::updating(function ($article) {
            if ($article->isDirty('title_id')) {
                $article->slug = static::generateUniqueSlug(
                    $article->title_id,
                    $article->id,
                    $article->created_at
                );
            }
        });
    }

    public function mainCategory()
    {
        return $this->belongsTo(NewsmakerMainCategory::class, 'main_category_id');
    }

    public function subCategory()
    {
        return $this->belongsTo(NewsmakerSubCategory::class, 'sub_category_id');
    }

    private static function generateUniqueSlug(?string $title, ?int $ignoreId = null, $date = null): string
    {
        $titleSlug = Str::slug($title ?: 'berita-newsmaker');

        if ($titleSlug === '') {
            $titleSlug = 'berita-newsmaker';
        }

        $datePrefix = ($date ?? now())->format('dmY');
        $baseSlug = $datePrefix . '-' . $titleSlug;
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
}
