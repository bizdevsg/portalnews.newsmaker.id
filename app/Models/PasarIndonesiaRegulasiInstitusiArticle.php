<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class PasarIndonesiaRegulasiInstitusiArticle extends Model
{
    use HasFactory;

    protected $table = 'pasar_indonesia_regulasi_institusi_articles';

    protected $fillable = [
        'category',
        'image',
        'title_id',
        'title_en',
        'notif',
        'slug',
        'content_id',
        'content_en',
        'author_id',
        'source',
    ];

    protected $casts = [
        'notif' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($item) {
            $item->slug = static::generateUniqueSlug($item->title_id);
        });

        static::updating(function ($item) {
            if ($item->isDirty('title_id')) {
                $item->slug = static::generateUniqueSlug(
                    $item->title_id,
                    $item->id,
                    $item->created_at
                );
            }
        });
    }

    private static function generateUniqueSlug(?string $title, ?int $ignoreId = null, $date = null): string
    {
        $defaultTitle = 'regulasi-institusi-pasar-indonesia';
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

    public function categoryItem()
    {
        return $this->belongsTo(PasarIndonesiaRegulasiInstitusiCategory::class, 'category', 'slug');
    }
}
