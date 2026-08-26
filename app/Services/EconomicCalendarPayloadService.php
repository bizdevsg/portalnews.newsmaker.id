<?php

namespace App\Services;

use Carbon\CarbonImmutable;
use Carbon\CarbonInterface;
use Illuminate\Support\Facades\DB;

class EconomicCalendarPayloadService
{
    private const HISTORY_LIMIT = 5;

    private const LIST_COLUMNS = [
        'id', 'economic_calendar_category_id', 'date', 'time', 'country', 'impact', 'figures',
        'previous', 'forecast', 'actual', 'sources', 'measures', 'usual_effect', 'frequency',
        'next_released', 'notes', 'isBankHoliday', 'bankHolidayNote', 'why_trader_care',
        'created_at', 'updated_at',
    ];

    private const HISTORY_COLUMNS = [
        'id', 'date', 'time', 'previous', 'forecast', 'actual', 'isBankHoliday', 'bankHolidayNote',
    ];

    /**
     * Fetch rows straight from the database for the given period (or everything, if null).
     * Reads go straight to the source of truth instead of a pre-built cache file, so the API
     * is always consistent with the latest admin-panel edit — no rebuild delay.
     */
    public function fetchItems(?string $period): array
    {
        $query = DB::table('economic_calendars')->select(self::LIST_COLUMNS);

        if ($period !== null) {
            [$startDate, $endDate] = $this->resolveDateRange($period);
            $query->whereBetween('date', [$startDate->toDateString(), $endDate->toDateString()]);
        }

        return $query->orderBy('date')->orderBy('id')
            ->get()
            ->map(fn (object $row) => $this->normalizeRow((array) $row))
            ->all();
    }

    /**
     * Attach the last N prior releases per category. Called only on the page actually being
     * returned (not the whole table), so this stays cheap regardless of table size.
     */
    public function attachHistoryForItems(array $items): array
    {
        foreach ($items as $index => $item) {
            $categoryId = $item['economic_calendar_category_id'] ?? null;
            $date = $item['date'] ?? null;

            if (empty($categoryId) || empty($date)) {
                $items[$index]['history'] = [];

                continue;
            }

            $items[$index]['history'] = DB::table('economic_calendars')
                ->select(self::HISTORY_COLUMNS)
                ->where('economic_calendar_category_id', $categoryId)
                ->where('date', '<', $date)
                ->orderByDesc('date')
                ->orderByDesc('id')
                ->limit(self::HISTORY_LIMIT)
                ->get()
                ->map(fn (object $row) => $this->makeHistoryPayload((array) $row))
                ->values()
                ->all();
        }

        return $items;
    }

    public function availablePeriods(): array
    {
        return ['today', 'this-week', 'previous-week', 'next-week'];
    }

    public function buildMeta(?string $period): array
    {
        $meta = [
            'timezone' => config('app.timezone'),
            'generated_at' => now()->toIso8601String(),
            'available_periods' => $this->availablePeriods(),
        ];

        if ($period === null) {
            return $meta;
        }

        [$startDate, $endDate] = $this->resolveDateRange($period);

        $meta['period'] = $period;
        $meta['start_date'] = $startDate->toDateString();
        $meta['end_date'] = $endDate->toDateString();

        return $meta;
    }

    public function buildPeriodsMeta(): array
    {
        $meta = [
            'timezone' => config('app.timezone'),
            'generated_at' => now()->toIso8601String(),
            'periods' => [],
        ];

        foreach ($this->availablePeriods() as $period) {
            [$startDate, $endDate] = $this->resolveDateRange($period);

            $meta['periods'][$period] = [
                'start_date' => $startDate->toDateString(),
                'end_date' => $endDate->toDateString(),
            ];
        }

        return $meta;
    }

    public function normalizePeriod(?string $period): ?string
    {
        if ($period === null) {
            return null;
        }

        $normalized = strtolower(trim($period));
        $normalized = str_replace(['_', ' '], '-', $normalized);

        return match ($normalized) {
            'today' => 'today',
            'this-week', 'thisweek', 'current-week', 'currentweek' => 'this-week',
            'previous-week', 'previousweek', 'prev-week', 'prevweek', 'last-week', 'lastweek' => 'previous-week',
            'next-week', 'nextweek', 'upcoming-week', 'upcomingweek' => 'next-week',
            default => $normalized,
        };
    }

    public function resolveDateRange(string $period): array
    {
        $now = CarbonImmutable::now(config('app.timezone'));
        $period = $this->normalizePeriod($period) ?? '';

        return match ($period) {
            'today' => [
                $now->startOfDay(),
                $now->endOfDay(),
            ],
            'this-week' => [
                $now->startOfWeek(CarbonInterface::MONDAY)->startOfDay(),
                $now->endOfWeek(CarbonInterface::SUNDAY)->endOfDay(),
            ],
            'previous-week' => [
                $now->subWeek()->startOfWeek(CarbonInterface::MONDAY)->startOfDay(),
                $now->subWeek()->endOfWeek(CarbonInterface::SUNDAY)->endOfDay(),
            ],
            'next-week' => [
                $now->addWeek()->startOfWeek(CarbonInterface::MONDAY)->startOfDay(),
                $now->addWeek()->endOfWeek(CarbonInterface::SUNDAY)->endOfDay(),
            ],
            default => throw new \InvalidArgumentException("Unsupported period [$period]."),
        };
    }

    public function sortForApiByDateAndTime(array $data): array
    {
        usort($data, function (array $first, array $second): int {
            $dateComparison = strcmp((string) ($first['date'] ?? ''), (string) ($second['date'] ?? ''));
            if ($dateComparison !== 0) {
                return $dateComparison;
            }

            $eventComparison = $this->apiEventSortBucket($first) <=> $this->apiEventSortBucket($second);
            if ($eventComparison !== 0) {
                return $eventComparison;
            }

            [$firstRank, $firstTime] = $this->apiTimeSortKey($first['time'] ?? null);
            [$secondRank, $secondTime] = $this->apiTimeSortKey($second['time'] ?? null);

            $rankComparison = $firstRank <=> $secondRank;
            if ($rankComparison !== 0) {
                return $rankComparison;
            }

            $timeComparison = strcmp($firstTime, $secondTime);
            if ($timeComparison !== 0) {
                return $timeComparison;
            }

            return ($first['id'] ?? 0) <=> ($second['id'] ?? 0);
        });

        return $data;
    }

    private function normalizeRow(array $row): array
    {
        $row['isBankHoliday'] = (bool) ($row['isBankHoliday'] ?? false);

        return $row;
    }

    private function makeHistoryPayload(array $row): array
    {
        return [
            'id' => $row['id'] ?? null,
            'date' => $row['date'] ?? null,
            'time' => $row['time'] ?? null,
            'previous' => $row['previous'] ?? null,
            'forecast' => $row['forecast'] ?? null,
            'actual' => $row['actual'] ?? null,
            'isBankHoliday' => (bool) ($row['isBankHoliday'] ?? false),
            'bankHolidayNote' => $row['bankHolidayNote'] ?? null,
        ];
    }

    private function apiTimeSortKey(mixed $value): array
    {
        $time = trim((string) $value);

        if ($time === '') {
            return [3, '99:99'];
        }

        $normalizedTime = str_replace('.', ':', $time);

        if (preg_match('/^\d{1,2}:\d{2}$/', $normalizedTime) === 1) {
            [$hour, $minute] = explode(':', $normalizedTime, 2);

            return [1, sprintf('%02d:%02d', (int) $hour, (int) $minute)];
        }

        return match (strtoupper($time)) {
            'ALL DAY' => [0, '00:00'],
            'TENTATIVE' => [2, '99:98'],
            default => [2, strtolower($time)],
        };
    }

    private function apiEventSortBucket(array $item): int
    {
        if ($this->isBankHolidayEvent($item) || $this->isTentativeEvent($item)) {
            return 1;
        }

        return 0;
    }

    private function isBankHolidayEvent(array $item): bool
    {
        if (($item['isBankHoliday'] ?? false) === true) {
            return true;
        }

        $figures = strtoupper(trim((string) ($item['figures'] ?? '')));

        return $figures !== '' && str_contains($figures, 'BANK HOLIDAY');
    }

    private function isTentativeEvent(array $item): bool
    {
        return strtoupper(trim((string) ($item['time'] ?? ''))) === 'TENTATIVE';
    }
}
