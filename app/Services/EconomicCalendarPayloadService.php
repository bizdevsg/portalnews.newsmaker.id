<?php

namespace App\Services;

use Carbon\CarbonImmutable;
use Carbon\CarbonInterface;
use Illuminate\Support\Facades\Storage;

class EconomicCalendarPayloadService
{
    private const HISTORY_LIMIT = 5;

    public function getCachePayload(string $path = 'cache/kalender.json'): ?array
    {
        if (!Storage::disk('local')->exists($path)) {
            return null;
        }

        $json = Storage::disk('local')->get($path);
        if ($json === null || $json === '') {
            return null;
        }

        $payload = json_decode($json, true);
        if (!is_array($payload)) {
            return null;
        }

        return $payload;
    }

    public function getPreparedData(string $path = 'cache/kalender.json'): ?array
    {
        $payload = $this->getCachePayload($path);
        if ($payload === null) {
            return null;
        }

        return $this->attachHistory(
            $this->sortCalendarData(
                $this->normalizeCalendarData($payload['data'] ?? [])
            )
        );
    }

    public function availablePeriods(): array
    {
        return ['today', 'this-week', 'previous-week', 'next-week'];
    }

    public function buildMeta(?string $period): array
    {
        $meta = [
            'timezone' => config('app.timezone'),
            'generated_at' => $this->resolveGeneratedAt(),
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
            'generated_at' => $this->resolveGeneratedAt(),
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

    public function filterByPeriod(array $data, string $period): array
    {
        [$startDate, $endDate] = $this->resolveDateRange($period);

        return $this->filterByRange($data, $startDate, $endDate);
    }

    public function filterByRange(array $data, CarbonImmutable $startDate, CarbonImmutable $endDate): array
    {
        return array_values(array_filter($data, function (array $item) use ($startDate, $endDate): bool {
            if (empty($item['date'])) {
                return false;
            }

            try {
                $itemDate = CarbonImmutable::parse((string) $item['date'], config('app.timezone'));
            } catch (\Throwable) {
                return false;
            }

            return $itemDate->betweenIncluded($startDate, $endDate);
        }));
    }

    public function groupByPeriods(array $data): array
    {
        $groupedData = [];

        foreach ($this->availablePeriods() as $period) {
            $groupedData[$period] = array_values($this->filterByPeriod($data, $period));
        }

        return $groupedData;
    }

    private function resolveGeneratedAt(string $path = 'cache/kalender.json'): string
    {
        $payload = $this->getCachePayload($path);

        $payloadGeneratedAt = $payload['generated_at'] ?? $payload['meta']['generated_at'] ?? null;
        if (is_string($payloadGeneratedAt) && trim($payloadGeneratedAt) !== '') {
            try {
                return CarbonImmutable::parse($payloadGeneratedAt, config('app.timezone'))->toIso8601String();
            } catch (\Throwable) {
                // Fallback to file timestamp below.
            }
        }

        if (Storage::disk('local')->exists($path)) {
            return CarbonImmutable::createFromTimestamp(
                Storage::disk('local')->lastModified($path),
                config('app.timezone')
            )->toIso8601String();
        }

        return now()->toIso8601String();
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

    private function sortCalendarData(array $data): array
    {
        usort($data, function (array $first, array $second): int {
            $dateComparison = strcmp((string) ($first['date'] ?? ''), (string) ($second['date'] ?? ''));
            if ($dateComparison !== 0) {
                return $dateComparison;
            }

            $timeComparison = strcmp((string) ($first['time'] ?? ''), (string) ($second['time'] ?? ''));
            if ($timeComparison !== 0) {
                return $timeComparison;
            }

            return ($first['id'] ?? 0) <=> ($second['id'] ?? 0);
        });

        return $data;
    }

    private function normalizeCalendarData(array $data): array
    {
        return array_map(function ($item): array {
            if (!is_array($item)) {
                return [];
            }

            if (array_key_exists('date', $item)) {
                $item['date'] = $this->normalizeDateValue($item['date']);
            }

            return $item;
        }, $data);
    }

    private function normalizeDateValue(mixed $value): ?string
    {
        if ($value === null || $value === '') {
            return null;
        }

        $stringValue = (string) $value;

        if (preg_match('/^\d{4}-\d{2}-\d{2}/', $stringValue, $matches) === 1) {
            return $matches[0];
        }

        try {
            return CarbonImmutable::parse($stringValue)->toDateString();
        } catch (\Throwable) {
            return $stringValue;
        }
    }

    private function attachHistory(array $data): array
    {
        $groupedHistory = [];

        foreach ($data as $index => $item) {
            $groupKey = $this->historyGroupKey($item);
            $currentDate = (string) ($item['date'] ?? '');
            $previousItems = $groupedHistory[$groupKey] ?? [];

            $historyItems = array_slice(array_values(array_reverse(array_filter($previousItems, function (array $historyItem) use ($currentDate): bool {
                $historyDate = (string) ($historyItem['date'] ?? '');

                return $currentDate !== '' && $historyDate !== '' && $historyDate < $currentDate;
            }))), 0, self::HISTORY_LIMIT);

            $data[$index]['history'] = array_values(array_map(
                fn (array $historyItem): array => $historyItem['payload'],
                $historyItems
            ));

            $groupedHistory[$groupKey][] = [
                'date' => $item['date'] ?? null,
                'payload' => $this->makeHistoryPayload($item),
            ];
        }

        return $data;
    }

    private function historyGroupKey(array $item): string
    {
        if (!empty($item['economic_calendar_category_id'])) {
            return 'category:' . $item['economic_calendar_category_id'];
        }

        return implode('|', [
            strtoupper((string) ($item['country'] ?? '')),
            strtolower((string) ($item['impact'] ?? '')),
            trim((string) ($item['figures'] ?? '')),
        ]);
    }

    private function makeHistoryPayload(array $item): array
    {
        return [
            'id' => $item['id'] ?? null,
            'date' => $item['date'] ?? null,
            'time' => $item['time'] ?? null,
            'previous' => $item['previous'] ?? null,
            'forecast' => $item['forecast'] ?? null,
            'actual' => $item['actual'] ?? null,
            'isBankHoliday' => $item['isBankHoliday'] ?? false,
            'bankHolidayNote' => $item['bankHolidayNote'] ?? null,
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
