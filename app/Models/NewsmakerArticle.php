<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        'content_id',
        'content_en',
        'author',
        'source',
    ];

    public function mainCategory()
    {
        return $this->belongsTo(NewsmakerMainCategory::class, 'main_category_id');
    }

    public function subCategory()
    {
        return $this->belongsTo(NewsmakerSubCategory::class, 'sub_category_id');
    }
}
