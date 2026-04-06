<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Schema;

class PasarIndonesiaArticle extends Model
{
    use HasFactory;

    public const DEFAULT_BERITA_CATEGORIES = [
        'makro-ekonomi' => 'Makro Ekonomi',
        'saham' => 'Pasar Saham',
        'obligasi-sbn' => 'Obligasi & SBN',
        'rupiah-dan-valas' => 'Rupiah & Valas',
        'komoditas' => 'Komoditas',
        'kebijakan-regulasi' => 'Kebijakan & Regulasi',
        'korporasi-emiten' => 'Korporasi & Emiten',
        'investasi-strategi' => 'Investasi & Strategi',
    ];

    public const DEFAULT_BERITA_CATEGORY = 'makro-ekonomi';

    protected $table = 'pasar_indonesia_articles';

    protected $fillable = [
        'type',
        'category',
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

    public static function beritaCategoryOptions(): array
    {
        if (!Schema::hasTable('pasar_indonesia_categories')) {
            return self::DEFAULT_BERITA_CATEGORIES;
        }

        $categories = PasarIndonesiaCategory::query()
            ->orderBy('name')
            ->pluck('name', 'slug')
            ->toArray();

        return $categories !== [] ? $categories : self::DEFAULT_BERITA_CATEGORIES;
    }

    public function getCategoryLabelAttribute(): ?string
    {
        if ($this->category === null) {
            return null;
        }

        if ($this->relationLoaded('categoryItem') && $this->categoryItem) {
            return $this->categoryItem->name;
        }

        return self::DEFAULT_BERITA_CATEGORIES[$this->category]
            ?? Str::of($this->category)->replace('-', ' ')->title()->toString();
    }

    public function categoryItem()
    {
        return $this->belongsTo(PasarIndonesiaCategory::class, 'category', 'slug');
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }
}
