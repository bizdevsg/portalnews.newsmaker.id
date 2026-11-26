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

    private const COUNTRY_VARIANTS = [
        'US' => ['US', 'USD'],
        'EU' => ['EU', 'EUR'],
        'JP' => ['JP', 'JPY', 'JPN'],
        'GB' => ['GB', 'GBP'],
        'AU' => ['AU', 'AUD'],
        'CA' => ['CA', 'CAD'],
        'CH' => ['CH', 'CHF'],
        'CN' => ['CN', 'CHN', 'CNH'],
        'ID' => ['ID', 'IDN', 'IDR'],
        'NZ' => ['NZ', 'NZD'],
    ];

    /**
     * Codes exposed by the API, matching the filter buttons in the app design.
     */
    private const API_COUNTRY_CODES = [
        'US' => 'USD',
        'EU' => 'EUR',
        'JP' => 'JPN',
        'GB' => 'GBP',
        'AU' => 'AUD',
        'CA' => 'CAD',
        'CH' => 'CHF',
        'CN' => 'CHN',
        'ID' => 'IDR',
        'NZ' => 'NZD',
    ];

    /**
     * Turn any stored or requested spelling into the code the API uses (US => USD).
     */
    public static function apiCountryCode(mixed $country): ?string
    {
        $canonical = self::normalizeCountry($country);

        return self::API_COUNTRY_CODES[$canonical] ?? $canonical;
    }

    /**
     * Turn a country or currency code into its canonical country code (USD => US).
     */
    public static function normalizeCountry(mixed $country): ?string
    {
        $normalized = strtoupper(trim((string) $country));

        if ($normalized === '') {
            return null;
        }

        foreach (self::COUNTRY_VARIANTS as $canonical => $variants) {
            if (in_array($normalized, $variants, true)) {
                return $canonical;
            }
        }

        return $normalized;
    }

    /**
     * Every spelling that may be stored in the database for a canonical country code.
     *
     * @return array<int, string>
     */
    public static function countryVariants(string $canonical): array
    {
        return self::COUNTRY_VARIANTS[$canonical] ?? [$canonical];
    }

    /**
     * Fetch rows straight from the database for the given period (or everything, if null).
     * Reads go straight to the source of truth instead of a pre-built cache file, so the API
     * is always consistent with the latest admin-panel edit — no rebuild delay.
     *
     * Optional filters: impact (list), country (list of codes), date_from, date_to.
     */
    public function fetchItems(?string $period, array $filters = []): array
    {
        $query = DB::table('economic_calendars')->select(self::LIST_COLUMNS);

        if ($period !== null) {
            [$startDate, $endDate] = $this->resolveDateRange($period);
            $query->whereBetween('date', [$startDate->toDateString(), $endDate->toDateString()]);
        }

        if (! empty($filters['impact'])) {
            $query->whereIn('impact', $filters['impact']);
        }

        if (! empty($filters['country'])) {
            $query->whereIn('country', array_merge(
                ...array_map(
                    fn (string $code) => self::countryVariants(self::normalizeCountry($code)),
                    $filters['country']
                )
            ));
        }

        if (! empty($filters['date_from'])) {
            $query->where('date', '>=', $filters['date_from']);
        }

        if (! empty($filters['date_to'])) {
            $query->where('date', '<=', $filters['date_to']);
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
        $candidates = $this->fetchHistoryCandidates($items);

        return array_map(function (array $item) use ($candidates): array {
            $categoryId = $item['economic_calendar_category_id'] ?? null;
            $date = $item['date'] ?? null;
            $item['history'] = [];

            if (empty($categoryId) || empty($date)) {
                return $item;
            }

            foreach ($candidates[$categoryId] ?? [] as $row) {
                if ((string) $row['date'] >= (string) $date) {
                    continue;
                }

                $item['history'][] = $this->makeHistoryPayload($row);

                if (count($item['history']) === self::HISTORY_LIMIT) {
                    break;
                }
            }

            return $item;
        }, $items);
    }

    /**
     * Load the possible history rows for all items in one query, grouped by category
     * and ordered newest first, so the query count does not grow with the page size.
     */
    private function fetchHistoryCandidates(array $items): array
    {
        $withHistory = array_filter(
            $items,
            fn (array $item) => ! empty($item['economic_calendar_category_id']) && ! empty($item['date'])
        );

        if ($withHistory === []) {
            return [];
        }

        $rows = DB::table('economic_calendars')
            ->select(array_merge(self::HISTORY_COLUMNS, ['economic_calendar_category_id']))
            ->whereIn(
                'economic_calendar_category_id',
                array_values(array_unique(array_column($withHistory, 'economic_calendar_category_id')))
            )
            ->where('date', '<', max(array_column($withHistory, 'date')))
            ->orderByDesc('date')
            ->orderByDesc('id')
            ->get();

        $grouped = [];

        foreach ($rows as $row) {
            $grouped[$row->economic_calendar_category_id][] = (array) $row;
        }

        return $grouped;
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
        // Same code as the country filter, whatever spelling the admin typed.
        $row['country'] = self::apiCountryCode($row['country'] ?? null);

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
