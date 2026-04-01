<div class="space-y-4 p-4 lg:hidden">
    @foreach ($items as $item)
        @php
            $rowKey = (string) ($item['id'] ?? $loop->index);
            $mobileRowKey = 'mobile-' . $rowKey;
            $currentDate = $item['date'] ?? null;
            $previousDate = $items[$loop->index - 1]['date'] ?? null;
            $showMobileDateDivider = $loop->first || $currentDate !== $previousDate;
            $displayCountry = $formatCountry($item['country'] ?? null);
            $flagUrl = $countryFlagUrl($item['country'] ?? null);
            $isHighImpact = ($item['impact'] ?? null) === 'High';
            $mobileCardBaseClass = $isHighImpact
                ? 'border-rose-200 bg-rose-50/60 dark:border-rose-800/50 dark:bg-rose-950/20'
                : 'border-slate-200 bg-white dark:border-slate-700 dark:bg-slate-950/50';
            $mobileSelectedClass = $isHighImpact
                ? 'ring-2 ring-rose-300 dark:ring-rose-700/60'
                : 'ring-2 ring-slate-300 dark:ring-slate-600';
            $historyRows = array_merge(
                [
                    [
                        'date' => $item['date'] ?? null,
                        'previous' => $item['previous'] ?? null,
                        'forecast' => $item['forecast'] ?? null,
                        'actual' => $item['actual'] ?? null,
                    ],
                ],
                is_array($item['history'] ?? null) ? $item['history'] : [],
            );
        @endphp

        @if ($showMobileDateDivider)
            <div class="flex items-center gap-3 pt-1">
                <div class="h-px flex-1 bg-blue-200 dark:bg-blue-700"></div>
                <span
                    class="whitespace-nowrap text-[11px] font-semibold uppercase tracking-[0.18em] text-blue-500 dark:text-blue-400">
                    {{ $formatDividerDate($currentDate) }}
                </span>
                <div class="h-px flex-1 bg-blue-200 dark:bg-blue-700"></div>
            </div>
        @endif

        <div :class="selected === '{{ $mobileRowKey }}' ? '{{ $mobileSelectedClass }}' : ''"
            class="overflow-hidden rounded-2xl border shadow-sm transition {{ $mobileCardBaseClass }}">
            <button type="button" @click="selected = selected === '{{ $mobileRowKey }}' ? '' : '{{ $mobileRowKey }}'"
                class="w-full p-4 text-left">
                <div class="flex items-start justify-between gap-3">
                    <div class="min-w-0 flex-1">
                        <div
                            class="flex flex-wrap items-center gap-2 text-[11px] font-semibold text-slate-500 dark:text-slate-400">
                            <span
                                class="rounded-full bg-slate-100 px-2.5 py-1 text-slate-700 dark:bg-slate-800 dark:text-slate-200">
                                {{ $formatDisplayDate($item['date'] ?? null) }}
                            </span>
                            <span
                                class="rounded-full bg-slate-100 px-2.5 py-1 text-slate-700 dark:bg-slate-800 dark:text-slate-200">
                                {{ $formatDisplayTime($item['time'] ?? null) }}
                            </span>
                            <span
                                class="inline-flex items-center gap-2 rounded-full bg-slate-100 px-2.5 py-1 text-slate-700 dark:bg-slate-800 dark:text-slate-200">
                                @if ($flagUrl)
                                    <img src="{{ $flagUrl }}" class="h-[14px] w-[18px] rounded-sm object-cover"
                                        alt="{{ $displayCountry }}" loading="lazy">
                                @endif
                                <span>{{ $displayCountry }}</span>
                            </span>
                        </div>

                        <div class="mt-3 text-sm font-bold text-slate-900 dark:text-white">
                            {{ $item['figures'] ?? '-' }}
                        </div>

                        <div class="mt-3 grid grid-cols-3 gap-2 text-[11px]">
                            <div class="rounded-xl bg-slate-100 px-3 py-2 dark:bg-slate-900/80">
                                <div class="font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">
                                    Prev</div>
                                <div class="mt-1 text-sm font-semibold text-slate-900 dark:text-white">
                                    {{ $item['previous'] ?? '-' }}</div>
                            </div>
                            <div class="rounded-xl bg-slate-100 px-3 py-2 dark:bg-slate-900/80">
                                <div class="font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">
                                    Fcst</div>
                                <div class="mt-1 text-sm font-semibold text-slate-900 dark:text-white">
                                    {{ $item['forecast'] ?? '-' }}</div>
                            </div>
                            <div class="rounded-xl bg-slate-100 px-3 py-2 dark:bg-slate-900/80">
                                <div class="font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">
                                    Actual</div>
                                <div class="mt-1 text-sm font-semibold {{ $valueToneClass($item['actual'] ?? null) }}">
                                    {{ $item['actual'] ?? '-' }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex shrink-0 flex-col items-end gap-3">
                        <span title="{{ $item['impact'] ?? 'Low' }}"
                            class="text-sm font-bold {{ $impactStarClass($item['impact'] ?? null) }}">
                            @for ($star = 0; $star < $impactStarCount($item['impact'] ?? null); $star++)
                                &#9733;
                            @endfor
                        </span>

                        <span
                            class="flex h-8 w-8 items-center justify-center rounded-full bg-slate-100 text-slate-500 dark:bg-slate-800 dark:text-slate-300">
                            <i class="fa-solid fa-chevron-down text-xs transition-transform"
                                :class="selected === '{{ $mobileRowKey }}' ? 'rotate-180' : ''"></i>
                        </span>
                    </div>
                </div>
            </button>

            <div x-show="selected === '{{ $mobileRowKey }}'" x-cloak
                class="border-t border-slate-200 bg-slate-50/80 p-4 dark:border-slate-800 dark:bg-slate-900/80">
                <div class="space-y-4">
                    <div
                        class="rounded-xl border border-slate-200 bg-white p-4 dark:border-slate-700 dark:bg-slate-900">
                        <div class="grid gap-4 sm:grid-cols-2">
                            <div>
                                <div
                                    class="text-[11px] font-semibold uppercase tracking-[0.18em] text-slate-400 dark:text-slate-500">
                                    Sources</div>
                                <div class="mt-1 text-sm text-slate-900 dark:text-white">{{ $item['sources'] ?? '-' }}
                                </div>
                            </div>
                            <div>
                                <div
                                    class="text-[11px] font-semibold uppercase tracking-[0.18em] text-slate-400 dark:text-slate-500">
                                    Measures</div>
                                <div class="mt-1 text-sm text-slate-900 dark:text-white">{{ $item['measures'] ?? '-' }}
                                </div>
                            </div>
                            <div>
                                <div
                                    class="text-[11px] font-semibold uppercase tracking-[0.18em] text-slate-400 dark:text-slate-500">
                                    Usual Effect</div>
                                <div class="mt-1 text-sm text-slate-900 dark:text-white">
                                    {{ $item['usual_effect'] ?? '-' }}</div>
                            </div>
                            <div>
                                <div
                                    class="text-[11px] font-semibold uppercase tracking-[0.18em] text-slate-400 dark:text-slate-500">
                                    Frequency</div>
                                <div class="mt-1 text-sm text-slate-900 dark:text-white">
                                    {{ $item['frequency'] ?? '-' }}</div>
                            </div>
                            <div>
                                <div
                                    class="text-[11px] font-semibold uppercase tracking-[0.18em] text-slate-400 dark:text-slate-500">
                                    Next Released</div>
                                <div class="mt-1 text-sm text-slate-900 dark:text-white">
                                    {{ $item['next_released'] ?? 'No Information' }}</div>
                            </div>
                            <div>
                                <div
                                    class="text-[11px] font-semibold uppercase tracking-[0.18em] text-slate-400 dark:text-slate-500">
                                    Bank Holiday</div>
                                <div class="mt-1 text-sm text-slate-900 dark:text-white">
                                    {{ !empty($item['isBankHoliday']) ? $item['bankHolidayNote'] ?? 'Ya' : '-' }}
                                </div>
                            </div>
                            <div class="sm:col-span-2">
                                <div
                                    class="text-[11px] font-semibold uppercase tracking-[0.18em] text-slate-400 dark:text-slate-500">
                                    Notes</div>
                                <div class="mt-1 text-sm text-slate-900 dark:text-white">{{ $item['notes'] ?? '-' }}
                                </div>
                            </div>
                            <div class="sm:col-span-2">
                                <div
                                    class="text-[11px] font-semibold uppercase tracking-[0.18em] text-slate-400 dark:text-slate-500">
                                    Why Trader Care</div>
                                <div class="mt-1 text-sm text-slate-900 dark:text-white">
                                    {{ $item['why_trader_care'] ?? '-' }}</div>
                            </div>
                        </div>
                    </div>

                    <div
                        class="rounded-xl border border-slate-200 bg-white p-4 dark:border-slate-700 dark:bg-slate-900">
                        <div class="mb-3 text-sm font-bold text-slate-900 dark:text-white">History</div>
                        <div class="space-y-2">
                            @foreach ($historyRows as $history)
                                <div class="rounded-xl bg-slate-50 px-3 py-3 dark:bg-slate-950/80">
                                    <div
                                        class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-400 dark:text-slate-500">
                                        {{ $formatHistoryDate($history['date'] ?? null) }}
                                    </div>
                                    <div class="mt-3 grid grid-cols-3 gap-2 text-[11px]">
                                        <div>
                                            <div
                                                class="font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">
                                                Prev</div>
                                            <div class="mt-1 text-sm text-slate-900 dark:text-white">
                                                {{ $history['previous'] ?? '-' }}</div>
                                        </div>
                                        <div>
                                            <div
                                                class="font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">
                                                Fcst</div>
                                            <div class="mt-1 text-sm text-slate-900 dark:text-white">
                                                {{ $history['forecast'] ?? '-' }}</div>
                                        </div>
                                        <div>
                                            <div
                                                class="font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">
                                                Actual</div>
                                            <div
                                                class="mt-1 text-sm {{ $valueToneClass($history['actual'] ?? null) }}">
                                                {{ $history['actual'] ?? '-' }}</div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>
