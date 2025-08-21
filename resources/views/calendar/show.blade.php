@section('namePage', $calendar->figures)

<x-app-layout>
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto flex flex-col gap-4 h-full">
        <div class="flex items-center gap-3">
            <a href="{{ route('calendar.index') }}"
                class="bg-gray-300 dark:bg-gray-700 text-black dark:text-white hover:bg-gray-200 dark:hover:bg-gray-600 hover:text-gray-800 dark:hover:text-gray-200 px-3 py-2 rounded-lg transition-all">
                <i class="fa-solid fa-xmark"></i>
            </a>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $calendar->figures ?? '-' }}</h1>
        </div>

        <div class="flex gap-4 flex-col lg:flex-row">
            <!-- Detail Keterangan -->
            <div
                class="w-full bg-white dark:bg-gray-800 p-4 rounded-lg shadow-md space-y-2 text-gray-900 dark:text-gray-100">
                <div>
                    <p><strong>Figures</strong></p>
                    <p>{{ $calendar->figures ?? '-' }}</p>
                </div>
                <div>
                    <p><strong>Sources</strong></p>
                    <p>{{ $calendar->sources ?? '-' }}</p>
                </div>
                <div>
                    <p><strong>Impact</strong></p>
                    <div
                        class="@if (strtolower($calendar->impact) === 'high') text-red-800 dark:text-red-400
                            @elseif(strtolower($calendar->impact) === 'medium') text-yellow-600 dark:text-yellow-400
                            @elseif(strtolower($calendar->impact) === 'low') text-green-600 dark:text-green-400 @endif">
                        @if (strtolower($calendar->impact) === 'high')
                            <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i
                                class="fa-solid fa-star"></i>
                        @elseif(strtolower($calendar->impact) === 'medium')
                            <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
                        @elseif(strtolower($calendar->impact) === 'low')
                            <i class="fa-solid fa-star"></i>
                        @endif
                    </div>
                </div>
                <div>
                    <p><strong>Country</strong></p>
                    <div class="flex items-center gap-2">
                        @php
                            $currencyToCountry = [
                                'US' => 'US',
                                'EUR' => 'EU',
                                'JPY' => 'JP',
                                'GBP' => 'GB',
                                'AUD' => 'AU',
                                'CAD' => 'CA',
                                'CHF' => 'CH',
                                'CHN' => 'CN',
                                'HKD' => 'HK',
                                'IDN' => 'ID',
                            ];
                            $currencyCode = strtoupper($calendar->country);
                            $countryCode = $currencyToCountry[$currencyCode] ?? 'unknown';
                        @endphp
                        @if ($countryCode !== 'unknown')
                            <img src="https://flagsapi.com/{{ $countryCode }}/shiny/24.png">
                        @endif
                        {{ $calendar->country ?? '-' }}
                    </div>
                </div>
                <div>
                    <p><strong>Date</strong></p>
                    <p>
                        @if ($calendar->date)
                            {{ \Carbon\Carbon::parse($calendar->date)->format('d M Y') }}
                        @else
                            -
                        @endif
                        {{ $calendar->time ?? '-' }}
                    </p>
                </div>
                <div>
                    <p><strong>Measures</strong></p>
                    <p>{{ $calendar->measures ?? '-' }}</p>
                </div>
                <div>
                    <p><strong>Usual Effect</strong></p>
                    <p>{{ $calendar->usual_effect ?? '-' }}</p>
                </div>
                <div>
                    <p><strong>Frequency</strong></p>
                    <p>{{ $calendar->frequency ?? '-' }}</p>
                </div>
                <div>
                    <p><strong>Next Released</strong></p>
                    <p>{{ $calendar->next_released ?? 'No Information' }}</p>
                </div>
                <div>
                    <p><strong>Notes</strong></p>
                    <p>{{ $calendar->notes ?? '-' }}</p>
                </div>
                <div>
                    <p><strong>Why Trader Care</strong></p>
                    <p>{{ $calendar->why_trader_care ?? '-' }}</p>
                </div>

                @php
                    if (!function_exists('parseEconomicValue')) {
                        function parseEconomicValue($value)
                        {
                            if (empty($value)) {
                                return null;
                            }
                            $value = (string) $value;
                            if (preg_match('/(-?\d+(\.\d+)?)([KMB%]?)/i', $value, $matches)) {
                                $number = floatval($matches[1]);
                                $suffix = strtoupper($matches[3]);
                                return match ($suffix) {
                                    'K' => $number * 1000,
                                    'M' => $number * 1000000,
                                    'B' => $number * 1000000000,
                                    default => $number,
                                };
                            }
                            return null;
                        }
                    }

                    $actual = parseEconomicValue($calendar->actual ?? null);
                    $previous = parseEconomicValue($calendar->previous ?? null);
                    $actualBg = null;

                    $actualBg = 'bg-gray-100 dark:bg-gray-700';
                    if (is_numeric($actual) && is_numeric($previous)) {
                        $actualBg =
                            $actual > $previous
                                ? 'bg-green-100 dark:bg-green-900'
                                : ($actual < $previous
                                    ? 'bg-red-100 dark:bg-red-900'
                                    : 'bg-gray-100 dark:bg-gray-700');
                } @endphp <div class="flex flex-col sm:flex-row items-stretch gap-3">
                    <div class="bg-gray-100 dark:bg-gray-700 flex items-center justify-between p-3 rounded-lg w-full">
                        <p><strong>Previous</strong></p>
                        <p class="text-2xl font-bold text-gray-600 dark:text-gray-300">{{ $calendar->previous ?? '-' }}
                        </p>
                    </div>
                    <div class="bg-gray-100 dark:bg-gray-700 flex items-center justify-between p-3 rounded-lg w-full">
                        <p><strong>Forecast</strong></p>
                        <p class="text-2xl font-bold text-gray-600 dark:text-gray-300">{{ $calendar->forecast ?? '-' }}
                        </p>
                    </div>
                    <div class="{{ $actualBg }} flex items-center justify-between p-3 rounded-lg w-full">
                        <p><strong>Actual</strong></p>
                        <p class="text-2xl font-bold text-gray-600 dark:text-gray-300">{{ $calendar->actual ?? '-' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Table History -->
        <div class="w-full rounded-lg shadow-md overflow-x-auto bg-white dark:bg-gray-800 md:p-4">
            <div class="rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700">
                <table class="min-w-full table-auto bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100">
                    <thead>
                        <tr class="bg-gray-100 dark:bg-gray-700">
                            <th class="border border-gray-200 dark:border-gray-600 px-4 py-2 text-center">History</th>
                            <th class="border border-gray-200 dark:border-gray-600 px-4 py-2 text-center">Previous</th>
                            <th class="border border-gray-200 dark:border-gray-600 px-4 py-2 text-center">Forecast</th>
                            <th class="border border-gray-200 dark:border-gray-600 px-4 py-2 text-center">Actual</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($histories->isNotEmpty())
                            @foreach ($histories as $history)
                                <tr>
                                    <td class="border border-gray-200 dark:border-gray-700 px-4 py-2 text-center">
                                        {{ \Carbon\Carbon::parse($history->date)->format('d M Y') }}
                                    </td>
                                    <td class="border border-gray-200 dark:border-gray-700 px-4 py-2 text-center">
                                        {{ $history->previous ?? '-' }}
                                    </td>
                                    <td class="border border-gray-200 dark:border-gray-700 px-4 py-2 text-center">
                                        {{ $history->forecast ?? '-' }}
                                    </td>
                                    <td class="border border-gray-200 dark:border-gray-700 px-4 py-2 text-center">
                                        {{ $history->actual ?? '-' }}
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td class="border border-gray-200 dark:border-gray-700 px-4 py-2 text-center"
                                    colspan="4">
                                    Belum ada data history.
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
