<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class PasarIndonesiaArticle extends Model
{
    use HasFactory;

    protected $table = 'pasar_indonesia_articles';

    protected $fillable = [
        'type',
        'image',
        'title_id',
        'title_en',
        'slug',
        'content_id',
        'content_en',
        'author_id',
        'source',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($item) {
            $item->slug = static::generateUniqueSlug($item->title_id, $item->type);
        });

        static::updating(function ($item) {
            if ($item->isDirty('title_id')) {
                $item->slug = static::generateUniqueSlug(
                    $item->title_id,
                    $item->type,
                    $item->id,
                    $item->created_at
                );
            }
        });
    }

    private static function generateUniqueSlug(?string $title, ?string $type, ?int $ignoreId = null, $date = null): string
    {
        $defaultTitle = $type === 'analisis'
            ? 'analisis-pasar-indonesia'
            : 'berita-pasar-indonesia';

        $titleSlug = Str::slug($title ?: $defaultTitle);

        if ($titleSlug === '') {
            $titleSlug = $defaultTitle;
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

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }
}
