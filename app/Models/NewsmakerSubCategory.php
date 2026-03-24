<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class NewsmakerSubCategory extends Model
{
    use HasFactory;

    protected $table = 'newsmaker_sub_categories';

    protected $fillable = ['main_category_id', 'name', 'slug'];

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

    public function mainCategory()
    {
        return $this->belongsTo(NewsmakerMainCategory::class, 'main_category_id');
    }

    public function articles()
    {
        return $this->hasMany(NewsmakerArticle::class, 'sub_category_id');
    }
}
