@section('namePage', $calendar->figures)

@php
    $countryMap = [
        'US' => ['flag' => 'US', 'label' => 'United States'],
        'EUR' => ['flag' => 'EU', 'label' => 'Euro Area'],
        'JPY' => ['flag' => 'JP', 'label' => 'Japan'],
        'JPN' => ['flag' => 'JP', 'label' => 'Japan'],
        'GBP' => ['flag' => 'GB', 'label' => 'United Kingdom'],
        'AUD' => ['flag' => 'AU', 'label' => 'Australia'],
        'CAD' => ['flag' => 'CA', 'label' => 'Canada'],
        'CHF' => ['flag' => 'CH', 'label' => 'Switzerland'],
        'CHN' => ['flag' => 'CN', 'label' => 'China'],
        'HKD' => ['flag' => 'HK', 'label' => 'Hong Kong'],
        'IDN' => ['flag' => 'ID', 'label' => 'Indonesia'],
    ];

    $impactMeta = [
        'high' => ['label' => 'High', 'badge' => 'bg-rose-50 text-rose-700 dark:bg-rose-500/10 dark:text-rose-200'],
        'medium' => ['label' => 'Medium', 'badge' => 'bg-amber-50 text-amber-700 dark:bg-amber-500/10 dark:text-amber-200'],
        'low' => ['label' => 'Low', 'badge' => 'bg-emerald-50 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-200'],
    ];

    $getCountryMeta = function ($country) use ($countryMap) {
        $code = strtoupper(trim((string) $country));
        $meta = $countryMap[$code] ?? null;

        return [
            'code' => $code !== '' ? $code : '-',
            'flag' => $meta['flag'] ?? null,
            'label' => $meta['label'] ?? 'Market tidak dikenal',
        ];
    };

    $getImpactMeta = function ($impact) use ($impactMeta) {
        return $impactMeta[strtolower(trim((string) $impact))] ?? [
            'label' => 'Unknown',
            'badge' => 'bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-200',
        ];
    };

    $parseEconomicValue = function ($value) {
        if ($value === null) {
            return null;
        }

        $normalized = str_replace(',', '', trim((string) $value));

        if ($normalized === '') {
            return null;
        }

        if (preg_match('/(-?\d+(?:\.\d+)?)([KMB%]?)/i', $normalized, $matches)) {
            $number = (float) $matches[1];
            $suffix = strtoupper($matches[2]);

            return match ($suffix) {
                'K' => $number * 1000,
                'M' => $number * 1000000,
                'B' => $number * 1000000000,
                default => $number,
            };
        }

        return null;
    };

    $getActualTone = function ($actual, $previous) use ($parseEconomicValue) {
        $actualValue = $parseEconomicValue($actual);
        $previousValue = $parseEconomicValue($previous);

        $meta = [
            'wrapper' => 'border-slate-200 bg-slate-50 dark:border-slate-700 dark:bg-slate-900',
            'text' => 'text-slate-700 dark:text-slate-200',
            'label' => 'Tidak ada pembanding',
        ];

        if (!is_numeric($actualValue) || !is_numeric($previousValue)) {
            return $meta;
        }

        if ($actualValue > $previousValue) {
            return [
                'wrapper' => 'border-emerald-200 bg-emerald-50 dark:border-emerald-500/20 dark:bg-emerald-500/10',
                'text' => 'text-emerald-700 dark:text-emerald-200',
                'label' => 'Lebih baik dari previous',
            ];
        }

        if ($actualValue < $previousValue) {
            return [
                'wrapper' => 'border-rose-200 bg-rose-50 dark:border-rose-500/20 dark:bg-rose-500/10',
                'text' => 'text-rose-700 dark:text-rose-200',
                'label' => 'Lebih rendah dari previous',
            ];
        }

        return [
            'wrapper' => 'border-slate-200 bg-slate-50 dark:border-slate-700 dark:bg-slate-900',
            'text' => 'text-slate-700 dark:text-slate-200',
            'label' => 'Sama dengan previous',
        ];
    };

    $formatCalendarDate = function ($date) {
        if (!filled($date)) {
            return '-';
        }

        return \Carbon\Carbon::parse($date)->format('d M Y');
    };

    $countryMeta = $getCountryMeta($calendar->country);
    $impact = $getImpactMeta($calendar->impact);
    $actualTone = $getActualTone($calendar->actual, $calendar->previous);
    $traderCare = $calendar->why_traders_care ?? $calendar->why_trader_care ?? null;

    $detailItems = [
        ['label' => 'Tanggal', 'value' => $formatCalendarDate($calendar->date)],
        ['label' => 'Waktu', 'value' => $calendar->time ?: '-'],
        ['label' => 'Source', 'value' => $calendar->sources ?: '-'],
        ['label' => 'Measures', 'value' => $calendar->measures ?: '-'],
        ['label' => 'Usual Effect', 'value' => $calendar->usual_effect ?: '-'],
        ['label' => 'Frequency', 'value' => $calendar->frequency ?: '-'],
        ['label' => 'Next Released', 'value' => $calendar->next_released ?: '-'],
        ['label' => 'Negara', 'value' => $countryMeta['code'] . ' - ' . $countryMeta['label']],
    ];
@endphp

<x-app-layout>
    <div class="mx-auto flex w-full flex-col gap-6 px-4 py-8 sm:px-6 lg:px-8">
        <section class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-950">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                <div class="min-w-0">
                    <a href="{{ route('calendar.index') }}"
                        class="inline-flex items-center gap-2 text-sm font-medium text-slate-500 transition hover:text-slate-900 dark:text-slate-400 dark:hover:text-white">
                        <i class="fa-solid fa-arrow-left"></i>
                        Kembali ke kalender
                    </a>

                    <h1 class="mt-3 text-2xl font-semibold text-slate-900 dark:text-white sm:text-3xl">
                        {{ $calendar->figures ?: '-' }}
                    </h1>

                    <div class="mt-4 flex flex-wrap gap-2 text-xs font-medium">
                        <span class="rounded-full px-3 py-1.5 {{ $impact['badge'] }}">
                            Impact {{ $impact['label'] }}
                        </span>
                        <span class="rounded-full border border-slate-200 px-3 py-1.5 text-slate-600 dark:border-slate-700 dark:text-slate-300">
                            {{ $formatCalendarDate($calendar->date) }} {{ $calendar->time ?: '' }}
                        </span>
                        <span class="rounded-full border border-slate-200 px-3 py-1.5 text-slate-600 dark:border-slate-700 dark:text-slate-300">
                            {{ $countryMeta['code'] }} - {{ $countryMeta['label'] }}
                        </span>
                        @if ($calendar->isBankHoliday)
                            <span class="rounded-full border border-slate-200 px-3 py-1.5 text-slate-600 dark:border-slate-700 dark:text-slate-300">
                                Bank Holiday
                            </span>
                        @endif
                    </div>
                </div>

                <div class="flex flex-col gap-2 sm:flex-row">
                    <a href="{{ route('calendar.edit', $calendar->id) }}"
                        class="inline-flex items-center justify-center gap-2 rounded-xl bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-slate-800 dark:bg-slate-100 dark:text-slate-900 dark:hover:bg-white">
                        <i class="fa-solid fa-pen-to-square"></i>
                        Edit
                    </a>
                    <a href="{{ route('calendar.index') }}"
                        class="inline-flex items-center justify-center rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-900">
                        Tutup
                    </a>
                </div>
            </div>
        </section>

        <section class="grid gap-3 sm:grid-cols-3">
            <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm dark:border-slate-800 dark:bg-slate-950">
                <p class="text-sm text-slate-500 dark:text-slate-400">Previous</p>
                <p class="mt-2 text-2xl font-semibold text-slate-900 dark:text-white">{{ $calendar->previous ?: '-' }}</p>
            </div>
            <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm dark:border-slate-800 dark:bg-slate-950">
                <p class="text-sm text-slate-500 dark:text-slate-400">Forecast</p>
                <p class="mt-2 text-2xl font-semibold text-slate-900 dark:text-white">{{ $calendar->forecast ?: '-' }}</p>
            </div>
            <div class="rounded-xl border p-4 shadow-sm {{ $actualTone['wrapper'] }}">
                <p class="text-sm text-slate-500 dark:text-slate-400">Actual</p>
                <p class="mt-2 text-2xl font-semibold {{ $actualTone['text'] }}">{{ $calendar->actual ?: '-' }}</p>
                <p class="mt-2 text-xs font-medium {{ $actualTone['text'] }}">{{ $actualTone['label'] }}</p>
            </div>
        </section>

        <section class="grid gap-6 xl:grid-cols-[minmax(0,1.35fr)_minmax(0,0.9fr)]">
            <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-950">
                <h2 class="text-lg font-semibold text-slate-900 dark:text-white">Informasi Detail</h2>

                <div class="mt-5 grid gap-4 sm:grid-cols-2">
                    @foreach ($detailItems as $item)
                        <div class="rounded-xl bg-slate-50 p-4 dark:bg-slate-900">
                            <p class="text-xs font-medium uppercase tracking-wide text-slate-400">{{ $item['label'] }}</p>
                            <p class="mt-2 text-sm text-slate-800 dark:text-slate-200">{{ $item['value'] }}</p>
                        </div>
                    @endforeach
                </div>

                <div class="mt-5 grid gap-4">
                    <div class="rounded-xl bg-slate-50 p-4 dark:bg-slate-900">
                        <p class="text-xs font-medium uppercase tracking-wide text-slate-400">Notes</p>
                        <p class="mt-2 text-sm leading-6 text-slate-700 dark:text-slate-200">{{ $calendar->notes ?: '-' }}</p>
                    </div>

                    <div class="rounded-xl bg-slate-50 p-4 dark:bg-slate-900">
                        <p class="text-xs font-medium uppercase tracking-wide text-slate-400">Why Traders Care</p>
                        <p class="mt-2 text-sm leading-6 text-slate-700 dark:text-slate-200">{{ $traderCare ?: '-' }}</p>
                    </div>
                </div>
            </div>

            <div class="flex flex-col gap-6">
                <section class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-950">
                    <h2 class="text-lg font-semibold text-slate-900 dark:text-white">Ringkasan Event</h2>

                    <div class="mt-5 space-y-4">
                        <div class="flex items-start gap-3">
                            <div class="flex h-10 w-10 items-center justify-center rounded-full bg-slate-100 dark:bg-slate-800">
                                @if ($countryMeta['flag'])
                                    <img src="https://flagsapi.com/{{ $countryMeta['flag'] }}/shiny/24.png"
                                        alt="{{ $countryMeta['label'] }}" class="h-5 w-5 rounded-full">
                                @else
                                    <i class="fa-solid fa-earth-asia text-slate-500 dark:text-slate-300"></i>
                                @endif
                            </div>
                            <div>
                                <p class="text-xs font-medium uppercase tracking-wide text-slate-400">Market</p>
                                <p class="mt-1 text-sm text-slate-800 dark:text-slate-200">{{ $countryMeta['label'] }}</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3">
                            <div class="flex h-10 w-10 items-center justify-center rounded-full bg-slate-100 dark:bg-slate-800">
                                <i class="fa-regular fa-calendar text-slate-500 dark:text-slate-300"></i>
                            </div>
                            <div>
                                <p class="text-xs font-medium uppercase tracking-wide text-slate-400">Schedule</p>
                                <p class="mt-1 text-sm text-slate-800 dark:text-slate-200">
                                    {{ $formatCalendarDate($calendar->date) }} {{ $calendar->time ?: '-' }}
                                </p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3">
                            <div class="flex h-10 w-10 items-center justify-center rounded-full bg-slate-100 dark:bg-slate-800">
                                <i class="fa-solid fa-chart-simple text-slate-500 dark:text-slate-300"></i>
                            </div>
                            <div>
                                <p class="text-xs font-medium uppercase tracking-wide text-slate-400">Impact</p>
                                <span class="mt-1 inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $impact['badge'] }}">
                                    {{ $impact['label'] }}
                                </span>
                            </div>
                        </div>

                        @if ($calendar->isBankHoliday || filled($calendar->bankHolidayNote))
                            <div class="rounded-xl border border-slate-200 p-4 dark:border-slate-800">
                                <p class="text-xs font-medium uppercase tracking-wide text-slate-400">Bank Holiday</p>
                                <p class="mt-2 text-sm leading-6 text-slate-700 dark:text-slate-200">
                                    {{ $calendar->bankHolidayNote ?: 'Event ini ditandai sebagai bank holiday.' }}
                                </p>
                            </div>
                        @endif
                    </div>
                </section>

                <section class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-950">
                    <h2 class="text-lg font-semibold text-slate-900 dark:text-white">Snapshot Angka</h2>

                    <div class="mt-5 space-y-3">
                        <div class="flex items-center justify-between rounded-xl bg-slate-50 px-4 py-3 dark:bg-slate-900">
                            <span class="text-sm text-slate-500 dark:text-slate-400">Previous</span>
                            <span class="text-sm font-semibold text-slate-900 dark:text-white">{{ $calendar->previous ?: '-' }}</span>
                        </div>
                        <div class="flex items-center justify-between rounded-xl bg-slate-50 px-4 py-3 dark:bg-slate-900">
                            <span class="text-sm text-slate-500 dark:text-slate-400">Forecast</span>
                            <span class="text-sm font-semibold text-slate-900 dark:text-white">{{ $calendar->forecast ?: '-' }}</span>
                        </div>
                        <div class="flex items-center justify-between rounded-xl border px-4 py-3 {{ $actualTone['wrapper'] }}">
                            <span class="text-sm text-slate-500 dark:text-slate-400">Actual</span>
                            <span class="text-sm font-semibold {{ $actualTone['text'] }}">{{ $calendar->actual ?: '-' }}</span>
                        </div>
                    </div>
                </section>
            </div>
        </section>

        <section class="rounded-xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-950">
            <div class="border-b border-slate-200 p-5 dark:border-slate-800">
                <h2 class="text-lg font-semibold text-slate-900 dark:text-white">History</h2>
                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                    Riwayat rilis sebelumnya untuk event yang sama.
                </p>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-800">
                    <thead class="bg-slate-50 dark:bg-slate-900">
                        <tr>
                            <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Tanggal</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Previous</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Forecast</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Actual</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                        @forelse ($histories as $history)
                            @php
                                $historyTone = $getActualTone($history->actual, $history->previous);
                            @endphp
                            <tr>
                                <td class="px-5 py-4 text-sm text-slate-700 dark:text-slate-200">
                                    {{ $formatCalendarDate($history->date) }}
                                </td>
                                <td class="px-5 py-4 text-sm font-medium text-slate-900 dark:text-white">{{ $history->previous ?: '-' }}</td>
                                <td class="px-5 py-4 text-sm font-medium text-slate-900 dark:text-white">{{ $history->forecast ?: '-' }}</td>
                                <td class="px-5 py-4 text-sm font-medium {{ $historyTone['text'] }}">{{ $history->actual ?: '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-5 py-8 text-center text-sm text-slate-500 dark:text-slate-400">
                                    Belum ada data history.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </div>
</x-app-layout>
