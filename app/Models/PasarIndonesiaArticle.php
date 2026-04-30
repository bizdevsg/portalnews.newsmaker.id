<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class PasarIndonesiaArticle extends Model
{
    use HasFactory;

    public const MAIN_BERITA_CATEGORIES = [
        'komoditas' => 'Komoditas',
        'pasar-saham' => 'Pasar Saham',
    ];

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
        'notif',
        'slug',
        'content_id',
        'content_en',
        'author_id',
        'author_initial',
        'source',
    ];

    protected $casts = [
        'notif' => 'boolean',
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
        $baseSlug = $datePrefix.'-'.$titleSlug;
        $slug = $baseSlug;
        $suffix = 1;

        while (
            static::query()
                ->where('slug', $slug)
                ->when($ignoreId, fn ($query) => $query->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $slug = $baseSlug.'-'.$suffix++;
        }

        return $slug;
    }

    public static function beritaCategoryOptions(): array
    {
        if (! Schema::hasTable('pasar_indonesia_categories')) {
            return self::DEFAULT_BERITA_CATEGORIES;
        }

        $categories = PasarIndonesiaCategory::query()
            ->orderBy('name')
            ->pluck('name', 'slug')
            ->toArray();

        return $categories !== [] ? $categories : self::DEFAULT_BERITA_CATEGORIES;
    }

    public static function mainBeritaCategoryOptions(): array
    {
        return self::MAIN_BERITA_CATEGORIES;
    }

    public static function resolveBeritaMainCategory(?string $subcategorySlug, ?string $subcategoryLabel = null): ?array
    {
        if ($subcategorySlug === null && $subcategoryLabel === null) {
            return null;
        }

        $slug = Str::of((string) $subcategorySlug)
            ->lower()
            ->replace('_', '-')
            ->toString();
        $label = Str::of((string) $subcategoryLabel)
            ->lower()
            ->ascii()
            ->toString();

        $commoditySubcategories = [
            'komodita',
            'komoditas',
            'gold',
            'silver',
            'oil',
        ];

        $stockMarketSubcategories = [
            'makro-ekonomi',
            'makro ekonomi',
            'pasar-saham',
            'pasar saham',
            'saham',
            'obligasi-sbn',
            'obligasi & sbn',
            'rupiah-dan-valas',
            'rupiah & valas',
            'korporasi-emiten',
            'korporasi & emiten',
            'investasi-strategi',
            'investasi & strategi',
        ];

        if (in_array($slug, $commoditySubcategories, true) || in_array($label, $commoditySubcategories, true)) {
            return [
                'slug' => 'komoditas',
                'name' => self::MAIN_BERITA_CATEGORIES['komoditas'],
            ];
        }

        if (in_array($slug, $stockMarketSubcategories, true) || in_array($label, $stockMarketSubcategories, true)) {
            return [
                'slug' => 'pasar-saham',
                'name' => self::MAIN_BERITA_CATEGORIES['pasar-saham'],
            ];
        }

        return [
            'slug' => 'pasar-saham',
            'name' => self::MAIN_BERITA_CATEGORIES['pasar-saham'],
        ];
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
