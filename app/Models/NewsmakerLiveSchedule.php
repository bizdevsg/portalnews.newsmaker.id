<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NewsmakerLiveSchedule extends Model
{
    protected $table = 'newsmaker_live_schedules';

    /** Urutan hari Senin-Minggu dipakai untuk sorting (index & query orderByRaw FIELD()). */
    public const DAYS = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];

    protected $fillable = [
        'title',
        'day',
        'start_time',
        'end_time',
        'host',
        'platform',
        'link_url',
        'description',
        'image',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];
}
