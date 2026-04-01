<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class NewsmakerMainCategory extends Model
{
    use HasFactory;

    protected $table = 'newsmaker_main_categories';

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

    public function subCategories()
    {
        return $this->hasMany(NewsmakerSubCategory::class, 'main_category_id');
    }

    public function articles()
    {
        return $this->hasMany(NewsmakerArticle::class, 'main_category_id');
    }
}
