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
        'tiktok_profile_url',
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

    /** Jam selesai <= jam mulai berarti siaran nyebrang ke hari berikutnya (mis. 23:00 - 00:30). */
    public function crossesMidnight(): bool
    {
        return $this->end_time !== null && $this->start_time !== null && $this->end_time <= $this->start_time;
    }

    /** Hari berakhirnya siaran: sama dengan `day` kalau tidak nyebrang tengah malam, else hari berikutnya dalam siklus DAYS. */
    public function endDay(): ?string
    {
        if ($this->end_time === null) {
            return null;
        }

        if (!$this->crossesMidnight()) {
            return $this->day;
        }

        $index = array_search($this->day, self::DAYS, true);

        return $index === false ? $this->day : self::DAYS[($index + 1) % count(self::DAYS)];
    }
}
