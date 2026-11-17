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
        'day_until',
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

    /** Apakah jadwal ini pakai rentang hari (mis. Senin s/d Jumat), bukan cuma 1 hari. */
    public function isDayRange(): bool
    {
        return $this->day_until !== null && $this->day_until !== $this->day;
    }

    /** Label hari untuk ditampilkan: "Senin" saja, atau "Senin - Jumat" kalau pakai rentang. */
    public function dayRangeLabel(): string
    {
        return $this->isDayRange() ? "{$this->day} - {$this->day_until}" : (string) $this->day;
    }

    /** Semua hari yang tercakup dalam rentang (urut siklik Senin..Minggu), termasuk kalau cuma 1 hari. */
    public function daysCovered(): array
    {
        $startIndex = array_search($this->day, self::DAYS, true);

        if ($startIndex === false) {
            return [$this->day];
        }

        if (!$this->isDayRange()) {
            return [$this->day];
        }

        $endIndex = array_search($this->day_until, self::DAYS, true);

        if ($endIndex === false) {
            return [$this->day];
        }

        $days = [];
        $i = $startIndex;

        while (true) {
            $days[] = self::DAYS[$i];

            if ($i === $endIndex) {
                break;
            }

            $i = ($i + 1) % count(self::DAYS);

            // Safety net kalau ternyata muter lebih dari 1 putaran penuh (data tidak valid).
            if (count($days) > count(self::DAYS)) {
                break;
            }
        }

        return $days;
    }

    /** Apakah hari tertentu termasuk dalam rentang hari jadwal ini. */
    public function coversDay(string $day): bool
    {
        return in_array($day, $this->daysCovered(), true);
    }
}
