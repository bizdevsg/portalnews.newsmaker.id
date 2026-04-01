<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PopupBanner extends Model
{
    protected $fillable = [
        'title',
        'description',
        'image',
        'cta_label',
        'cta_url',
        'modal_html',
        'start_at',
        'end_at',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'start_at' => 'datetime',
        'end_at' => 'datetime',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];
}
