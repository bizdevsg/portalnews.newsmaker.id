<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NewsmakerVideoBriefing extends Model
{
    protected $fillable = [
        'title',
        'embed_code',
        'backup_video_url',
        'image',
    ];
}
