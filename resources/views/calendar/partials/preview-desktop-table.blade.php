<div class="hidden lg:block overflow-x-auto">
    <table class="min-w-full">
        <thead class="border-b border-slate-200 bg-slate-50/90 dark:border-slate-700 dark:bg-slate-800/60">
            <tr
                class="text-left text-[11px] font-semibold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-300">
                <th class="px-4 py-3">Date</th>
                <th class="px-4 py-3">Time</th>
                <th class="px-4 py-3">Country</th>
                <th class="px-4 py-3 text-center">Impact</th>
                <th class="px-4 py-3">Figures</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($items as $item)
                @php
                    $rowKey = (string) ($item['id'] ?? $loop->index);
                    $desktopRowKey = 'desktop-' . $rowKey;
                    $currentDate = $item['date'] ?? null;
                    $previousDate = $items[$loop->index - 1]['date'] ?? null;
                    $showDateDivider = !$loop->first && $currentDate !== $previousDate;
                    $displayCountry = $formatCountry($item['country'] ?? null);
                    $flagUrl = $countryFlagUrl($item['country'] ?? null);
                    $isHighImpact = ($item['impact'] ?? null) === 'High';
                    $rowBaseClass = $isHighImpact ? 'bg-rose-50/60 dark:bg-red-900/10' : '';
                    $rowHoverClass = $isHighImpact
                        ? 'hover:bg-rose-100/80 dark:hover:bg-rose-900/20'
                        : 'hover:bg-slate-50/70 dark:hover:bg-slate-800/50';
                    $rowSelectedClass = $isHighImpact
                        ? 'bg-rose-100 dark:bg-rose-900/25'
                        : 'bg-slate-50 dark:bg-slate-800/70';
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

                @if ($showDateDivider)
                    <tr class="bg-slate-50/80 dark:bg-slate-800/40">
                        <td colspan="5" class="px-4 py-4">
                            <div class="flex items-center gap-3 text-xs font-semibold text-blue-600 dark:text-blue-300">
                                <div class="h-px flex-1 bg-blue-200 dark:bg-blue-400/30"></div>
                                <span class="whitespace-nowrap">{{ $formatDividerDate($currentDate) }}</span>
                                <div class="h-px flex-1 bg-blue-200 dark:bg-blue-400/30"></div>
                            </div>
                        </td>
                    </tr>
                @endif

                <tr @click="selected = selected === '{{ $desktopRowKey }}' ? '' : '{{ $desktopRowKey }}'"
                    :class="selected === '{{ $desktopRowKey }}' ? '{{ $rowSelectedClass }}' : '{{ $rowHoverClass }}'"
                    class="cursor-pointer border-b border-slate-200 align-middle transition dark:border-slate-700 {{ $rowBaseClass }}">
                    <td class="px-4 py-4 text-[13px] font-medium text-slate-700 dark:text-slate-200 whitespace-nowrap">
                        {{ $formatDisplayDate($item['date'] ?? null) }}
                    </td>
                    <td class="px-4 py-4 text-[13px] font-medium text-slate-700 dark:text-slate-200 whitespace-nowrap">
                        {{ $formatDisplayTime($item['time'] ?? null) }}
                    </td>
                    <td class="px-4 py-4 whitespace-nowrap">
                        <span
                            class="inline-flex items-center gap-2 rounded-full bg-slate-100 px-2.5 py-1 text-[11px] font-semibold text-slate-700 dark:bg-slate-800 dark:text-slate-200">
                            @if ($flagUrl)
                                <img src="{{ $flagUrl }}" class="h-[14px] w-[18px] rounded-sm object-cover"
                                    alt="{{ $displayCountry }}" loading="lazy">
                            @endif
                            <span>{{ $displayCountry }}</span>
                        </span>
                    </td>
                    <td class="w-[110px] px-4 py-4 text-center">
                        <div class="flex min-h-[40px] items-center justify-center">
                            <span title="{{ $item['impact'] ?? 'Low' }}"
                                class="text-sm font-bold {{ $impactStarClass($item['impact'] ?? null) }}">
                                @for ($star = 0; $star < $impactStarCount($item['impact'] ?? null); $star++)
                                    &#9733;
                                @endfor
                            </span>
                        </div>
                    </td>
                    <td class="min-w-[420px] px-4 py-4">
                        <div class="text-[15px] font-bold text-slate-900 dark:text-white">
                            {{ $item['figures'] ?? '-' }}
                        </div>
                        <div class="mt-1 text-[12px] font-medium text-slate-500 dark:text-slate-300">
                            Previous:
                            <span>{{ $item['previous'] ?? '-' }}</span>
                            <span class="mx-1">|</span>
                            Forecast:
                            <span>{{ $item['forecast'] ?? '-' }}</span>
                            <span class="mx-1">|</span>
                            Actual:
                            <span class="{{ $valueToneClass($item['actual'] ?? null) }}">
                                {{ $item['actual'] ?? '-' }}
                            </span>
                        </div>
                    </td>
                </tr>

                <tr x-show="selected === '{{ $desktopRowKey }}'" x-cloak
                    class="border-b border-slate-200 dark:border-slate-700">
                    <td colspan="5" class="p-0">
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 bg-slate-50 p-3 dark:bg-slate-950/40">
                            <div
                                class="rounded-xl border border-blue-500 bg-blue-50 p-5 text-slate-900 shadow-sm dark:border-blue-400 dark:bg-blue-950/40 dark:text-slate-100">
                                <div class="space-y-5 text-sm leading-7">
                                    <div>
                                        <div class="text-xl font-bold">Sources</div>
                                        <div>{{ $item['sources'] ?? '-' }}</div>
                                    </div>
                                    <div>
                                        <div class="text-xl font-bold">Measures</div>
                                        <div>{{ $item['measures'] ?? '-' }}</div>
                                    </div>
                                    <div>
                                        <div class="text-xl font-bold">Usual Effect</div>
                                        <div>{{ $item['usual_effect'] ?? '-' }}</div>
                                    </div>
                                    <div>
                                        <div class="text-xl font-bold">Frequency</div>
                                        <div>{{ $item['frequency'] ?? '-' }}</div>
                                    </div>
                                    <div>
                                        <div class="text-xl font-bold">Next Released</div>
                                        <div>{{ $item['next_released'] ?? 'No Information' }}</div>
                                    </div>
                                    <div>
                                        <div class="text-xl font-bold">Notes</div>
                                        <div>{{ $item['notes'] ?? '-' }}</div>
                                    </div>
                                    <div>
                                        <div class="text-xl font-bold">Why Trader Care</div>
                                        <div>{{ $item['why_trader_care'] ?? '-' }}</div>
                                    </div>
                                    @if (!empty($item['isBankHoliday']))
                                        <div>
                                            <div class="text-xl font-bold">Bank Holiday</div>
                                            <div>{{ $item['bankHolidayNote'] ?? 'Ya' }}</div>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="rounded-xl bg-white p-3 shadow-sm dark:bg-slate-900">
                                <div class="overflow-x-auto">
                                    <table class="w-full min-w-[520px]">
                                        <thead class="border-b border-slate-200 dark:border-slate-700">
                                            <tr class="text-left text-lg font-bold text-slate-900 dark:text-white">
                                                <th class="px-4 py-3">History</th>
                                                <th class="px-4 py-3">Previous</th>
                                                <th class="px-4 py-3">Forecast</th>
                                                <th class="px-4 py-3">Actual</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($historyRows as $history)
                                                <tr
                                                    class="border-b border-slate-200 text-sm text-slate-900 dark:border-slate-700 dark:text-slate-100">
                                                    <td class="px-4 py-3 whitespace-nowrap">
                                                        {{ $formatHistoryDate($history['date'] ?? null) }}</td>
                                                    <td class="px-4 py-3 whitespace-nowrap">
                                                        {{ $history['previous'] ?? '-' }}</td>
                                                    <td class="px-4 py-3 whitespace-nowrap">
                                                        {{ $history['forecast'] ?? '-' }}</td>
                                                    <td
                                                        class="px-4 py-3 whitespace-nowrap {{ $valueToneClass($history['actual'] ?? null) }}">
                                                        {{ $history['actual'] ?? '-' }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
