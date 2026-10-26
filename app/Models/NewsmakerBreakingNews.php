<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * Dikelompokkan oleh BreakingNewsCategory miliknya sendiri - tidak
 * terhubung sama sekali ke NewsmakerMainCategory/NewsmakerArticle.
 */
class NewsmakerBreakingNews extends Model
{
    use HasFactory;

    protected $table = 'newsmaker_breaking_news';

    protected $fillable = [
        'breaking_news_category_id',
        'image',
        'title_id',
        'title_en',
        'slug',
        'content_id',
        'content_en',
        'author',
        'author_id',
        'author_initial',
        'source',
        'start_time',
        'end_time',
        'notif',
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
                $item->slug = static::generateUniqueSlug($item->title_id, $item->id, $item->created_at);
            }
        });
    }

    public function authorUser()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function breakingNewsCategory()
    {
        return $this->belongsTo(BreakingNewsCategory::class, 'breaking_news_category_id');
    }

    private static function generateUniqueSlug(?string $title, ?int $ignoreId = null, $date = null): string
    {
        $titleSlug = Str::slug($title ?: 'breaking-news');

        if ($titleSlug === '') {
            $titleSlug = 'breaking-news';
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
