<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * Kategori milik Breaking News sendiri - terpisah total dari
 * NewsmakerMainCategory, meski konsepnya sama (name -> slug otomatis,
 * mengelompokkan artikel).
 */
class BreakingNewsCategory extends Model
{
    protected $table = 'breaking_news_categories';

    protected $fillable = ['name', 'slug'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($category) {
            $category->slug = Str::slug($category->name);
        });

        static::updating(function ($category) {
            $category->slug = Str::slug($category->name);
        });
    }

    public function breakingNews()
    {
        return $this->hasMany(NewsmakerBreakingNews::class, 'breaking_news_category_id');
    }
}
