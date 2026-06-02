@section('namePage', 'Economic Calendar')

<x-app-layout :hideSidebar="true" :hideHeader="true">
    @php
        $periodLabels = [
            'today' => 'Today',
            'this-week' => 'This Week',
            'previous-week' => 'Previous Week',
            'next-week' => 'Next Week',
        ];

        $countryLabels = [
            'US' => 'United States',
            'USD' => 'United States',
            'CHN' => 'China',
            'CNH' => 'China',
            'EUR' => 'European Union',
            'EU' => 'European Union',
            'GBP' => 'United Kingdom',
            'JPY' => 'Japan',
            'JPN' => 'Japan',
            'AUD' => 'Australia',
            'CAD' => 'Canada',
            'CHF' => 'Switzerland',
            'NZD' => 'New Zealand',
            'IDR' => 'Indonesia',
            'IDN' => 'Indonesia',
        ];

        $formatDisplayDate = static function (?string $date): string {
            if (blank($date)) {
                return '-';
            }

            try {
                return \Carbon\Carbon::parse($date)->format('d-m-Y');
            } catch (\Throwable) {
                return (string) $date;
            }
        };

        $formatDividerDate = static function (?string $date): string {
            if (blank($date)) {
                return '-';
            }

            try {
                return \Carbon\Carbon::parse($date)->format('d M Y');
            } catch (\Throwable) {
                return (string) $date;
            }
        };

        $formatHistoryDate = static function (?string $date): string {
            if (blank($date)) {
                return '-';
            }

            try {
                return \Carbon\Carbon::parse($date)->format('Y-m-d');
            } catch (\Throwable) {
                return (string) $date;
            }
        };

        $formatDisplayTime = static function (?string $time): string {
            if (blank($time)) {
                return '-';
            }

            return str_replace(':', '.', (string) $time);
        };

        $formatCountry = static function (?string $country) use ($countryLabels): string {
            $normalized = strtoupper(trim((string) $country));

            return $countryLabels[$normalized] ?? ($normalized !== '' ? $normalized : '-');
        };

        $countryFlagCode = static function (?string $country): ?string {
            $normalized = strtoupper(trim((string) $country));

            return match ($normalized) {
                'US', 'USD' => 'US',
                'EUR' => 'EU',
                'JPY', 'JPN' => 'JP',
                'GBP' => 'GB',
                'AUD' => 'AU',
                'CAD' => 'CA',
                'CHF' => 'CH',
                'CHN', 'CNH' => 'CN',
                'IDR', 'IDN' => 'ID',
                'NZD' => 'NZ',
                default => $normalized !== '' ? $normalized : null,
            };
        };

        $countryFlagUrl = static function (?string $country) use ($countryFlagCode): ?string {
            $flagCode = $countryFlagCode($country);

            if ($flagCode === null) {
                return null;
            }

            return sprintf('https://flagcdn.com/w20/%s.png', strtolower($flagCode));
        };

        $impactStarClass = static function (?string $impact): string {
            return match ($impact) {
                'High' => 'text-red-900 dark:text-red-300 py-0.5 px-3 rounded-full bg-red-500/50',
                'Medium' => 'text-amber-900 dark:text-amber-300 py-0.5 px-3 rounded-full bg-amber-500/50',
                default => 'text-emerald-900 dark:text-emerald-300 py-0.5 px-3 rounded-full bg-emerald-500/50',
            };
        };

        $impactStarCount = static function (?string $impact): int {
            return match ($impact) {
                'High' => 3,
                'Medium' => 2,
                default => 1,
            };
        };

        $valueToneClass = static function (mixed $value): string {
            if ($value === null || $value === '' || $value === '-') {
                return 'text-slate-900 dark:text-white';
            }

            $normalized = str_replace([',', '%', ' '], '', (string) $value);

            if (is_numeric($normalized)) {
                $number = (float) $normalized;

                if ($number < 0) {
                    return 'text-red-600 dark:text-red-400';
                }

                if ($number > 0) {
                    return 'text-green-600 dark:text-green-400';
                }
            }

            return str_starts_with(trim((string) $value), '-')
                ? 'text-red-600 dark:text-red-400'
                : 'text-slate-900 dark:text-white';
        };

        $items = $paginatedItems->items();
        $activeItems = array_values($filteredItems ?? $groupedData[$activePeriod] ?? []);
        $activeDates = array_values(
            array_filter(
                array_map(static fn(array $item): ?string => $item['date'] ?? null, $activeItems),
                static fn(?string $date): bool => filled($date),
            ),
        );
        $activeStartDate = $activeDates[0] ?? null;
        $activeEndDate = $activeDates !== [] ? $activeDates[array_key_last($activeDates)] : null;
        $queryWithoutPage = request()->except(['page', 'period']);
    @endphp

    <div class="mx-auto w-full px-4 py-6 sm:px-6 lg:px-8 lg:py-8">
        <div x-data="{ selected: '' }" class="space-y-6">
            <div class="border-b border-slate-200 pb-6 dark:border-slate-700">
                <div class="flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
                    <div>
                        <h1 class="text-2xl font-bold tracking-tight text-slate-900 dark:text-white sm:text-3xl">
                            Kalender Ekonomi
                        </h1>
                        @if (!empty($meta['generated_at']))
                            @php
                                $lastUpdatedAt = \Carbon\Carbon::parse($meta['generated_at'])->timezone(
                                    config('app.timezone'),
                                );
                            @endphp
                            <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">
                                Update terakhir pukul {{ $lastUpdatedAt->format('H:i') }} WIB
                                <span
                                    class="text-slate-400 dark:text-slate-500">({{ $lastUpdatedAt->format('d M Y') }})</span>
                            </p>
                        @endif
                    </div>

                    <button type="button"
                        onclick="if (window.opener) { window.close(); } else { window.location.href = '{{ route('calendar.index') }}'; }"
                        class="inline-flex w-full items-center justify-center rounded-lg border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-50 dark:border-slate-600 dark:text-slate-200 dark:hover:bg-slate-800 sm:w-fit">
                        Tutup Preview
                    </button>
                </div>
            </div>

            @if (!$cacheAvailable)
                <div
                    class="rounded-2xl border border-dashed border-red-300 bg-red-50 px-6 py-10 text-sm text-red-700 dark:border-red-500/40 dark:bg-red-950/30 dark:text-red-200">
                    Cache kalender belum tersedia. Jalankan generate cache terlebih dahulu agar halaman preview bisa
                    menampilkan period.
                </div>
            @else
                <div class="flex flex-col gap-4 xl:flex-row xl:items-start xl:justify-between">
                    <div class="flex flex-wrap gap-2">
                        @foreach ($availablePeriods as $period)
                            <a href="{{ route('calendar.preview', array_merge($queryWithoutPage, ['period' => $period])) }}"
                                class="rounded-lg border px-4 py-2 text-sm font-semibold transition sm:px-5 sm:py-2.5 sm:text-base {{ $activePeriod === $period
                                    ? 'bg-blue-600 text-white border-blue-600'
                                    : 'bg-white text-blue-700 border-blue-600 hover:bg-blue-50 dark:bg-slate-900 dark:text-blue-300 dark:border-blue-500 dark:hover:bg-slate-800' }}">
                                {{ $periodLabels[$period] ?? $period }}
                            </a>
                        @endforeach
                    </div>

                    <form action="{{ route('calendar.preview') }}" method="GET"
                        class="w-full xl:max-w-xl xl:min-w-[420px]">
                        <input type="hidden" name="period" value="{{ $activePeriod }}">
                        <div class="flex flex-col gap-3 sm:flex-row">
                            <div class="grid min-w-0 flex-1 gap-3 sm:grid-cols-2">
                                <input type="text" name="q" id="preview-search" value="{{ $search ?? '' }}"
                                    class="block min-w-0 w-full rounded-lg border-slate-300 bg-white text-slate-900 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-slate-600 dark:bg-slate-950 dark:text-white"
                                    placeholder="Cari tanggal, impact, atau figures">

                                <select name="country" id="preview-country"
                                    class="block min-w-0 w-full rounded-lg border-slate-300 bg-white text-slate-900 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-slate-600 dark:bg-slate-950 dark:text-white">
                                    <option value="">Semua Negara</option>
                                    @foreach ($countryOptions as $countryOption)
                                        <option value="{{ $countryOption['value'] }}"
                                            {{ (($selectedCountry ?? null) === $countryOption['value']) ? 'selected' : '' }}>
                                            {{ $countryOption['label'] }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="flex gap-2 sm:items-end">
                                <button type="submit"
                                    class="w-full rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white transition hover:bg-slate-800 dark:bg-slate-100 dark:text-slate-900 dark:hover:bg-white sm:w-auto">
                                    Cari
                                </button>

                                @if (!empty($search) || !empty($selectedCountry))
                                    <a href="{{ route('calendar.preview', ['period' => $activePeriod]) }}"
                                        class="inline-flex w-full items-center justify-center rounded-lg border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 transition hover:bg-slate-50 dark:border-slate-600 dark:text-slate-200 dark:hover:bg-slate-800 sm:w-auto">
                                        Reset
                                    </a>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>

                <section class="space-y-4">
                    @if (empty($items))
                        <div
                            class="rounded-2xl border border-dashed border-slate-300 bg-white px-6 py-10 text-sm text-slate-500 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-400">
                            @if (!empty($search) || !empty($selectedCountry))
                                Tidak ada data yang cocok dengan filter ini untuk
                                {{ strtolower($periodLabels[$activePeriod] ?? $activePeriod) }}.
                            @else
                                Tidak ada data untuk {{ strtolower($periodLabels[$activePeriod] ?? $activePeriod) }}.
                            @endif
                        </div>
                    @else
                        <div
                            class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-900">
                            <div class="px-4 py-3 text-left sm:text-right">
                                @if ($activeStartDate && $activeEndDate)
                                    <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">
                                        @if ($activeStartDate === $activeEndDate)
                                            {{ $formatDividerDate($activeStartDate) }}
                                        @else
                                            {{ $formatDividerDate($activeStartDate) }} s/d
                                            {{ $formatDividerDate($activeEndDate) }}
                                        @endif
                                    </p>
                                @endif
                            </div>

                            @include('calendar.partials.preview-desktop-table')
                            @include('calendar.partials.preview-mobile-cards')

                        </div>

                        @if ($paginatedItems->hasPages())
                            <div
                                class="rounded-xl shadow border-t border-slate-200 bg-white px-4 py-4 dark:border-slate-700 dark:bg-slate-900">
                                <div
                                    class="hidden lg:flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                                    {{ $paginatedItems->onEachSide(1)->links('vendor.pagination.tailwind') }}
                                </div>

                                {{ $paginatedItems->links('vendor.pagination.mobile') }}
                            </div>
                        @endif
                    @endif
                </section>
            @endif
        </div>
    </div>
</x-app-layout>
