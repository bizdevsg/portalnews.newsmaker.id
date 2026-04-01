@section('namePage', $calendar->figures)

<x-app-layout>
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full mx-auto flex flex-col gap-6">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <div>
                <a href="{{ route('calendar.index') }}"
                    class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                    &larr; Kembali ke category
                </a>
                <p class="mt-3 text-sm text-blue-600 dark:text-blue-400 font-semibold">Market Header Information</p>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white">{{ $calendar->figures }}</h1>
            </div>

            <div class="flex flex-wrap gap-3">
                <a href="{{ route('calendar.edit', $calendar) }}"
                    class="inline-flex items-center rounded-lg bg-amber-500 px-4 py-2 text-sm font-semibold text-white hover:bg-amber-600">
                    Edit Header
                </a>
            </div>
        </div>

        @php
            $currencyToCountry = [
                'US' => 'US',
                'USD' => 'US',
                'EUR' => 'EU',
                'JPY' => 'JP',
                'JPN' => 'JP',
                'GBP' => 'GB',
                'AUD' => 'AU',
                'CAD' => 'CA',
                'CHF' => 'CH',
                'CHN' => 'CN',
                'CNH' => 'CN',
                'CNY' => 'CN',
                'HKD' => 'HK',
                'IDR' => 'ID',
                'IDN' => 'ID',
            ];

            $countryToName = [
                'US' => 'United States',
                'EU' => 'Eurozone',
                'JP' => 'Japan',
                'GB' => 'United Kingdom',
                'AU' => 'Australia',
                'CA' => 'Canada',
                'CH' => 'Switzerland',
                'CN' => 'China',
                'HK' => 'Hong Kong',
                'ID' => 'Indonesia',
            ];

            $currencyCode = strtoupper($calendar->country);
            $countryCode = $currencyToCountry[$currencyCode] ?? $currencyCode;
            $countryName = $countryToName[$countryCode] ?? $currencyCode;
            $countryFlagUrl = $countryCode ? sprintf('https://flagcdn.com/w40/%s.png', strtolower($countryCode)) : null;
        @endphp

        <div class="rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 shadow-sm">
            <div class="grid gap-6 p-6 lg:grid-cols-2">
                <div class="space-y-4">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                            Country</p>
                        <div class="mt-1 flex items-center gap-2 text-sm text-gray-900 dark:text-white">
                            @if ($countryFlagUrl)
                                <img src="{{ $countryFlagUrl }}" class="h-4 w-5 rounded-sm object-cover"
                                    alt="{{ $currencyCode }}" loading="lazy">
                            @endif
                            <span>{{ $countryName }}</span>
                        </div>
                    </div>

                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                            Description</p>
                        <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $calendar->figures }}</p>
                    </div>

                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Impact
                            Level</p>
                        <span
                            class="mt-1 inline-flex rounded-full px-3 py-1 text-xs font-semibold
                            @if ($calendar->impact === 'High') bg-red-100 text-red-700
                            @elseif ($calendar->impact === 'Medium') bg-yellow-100 text-yellow-700
                            @else bg-green-100 text-green-700 @endif">
                            {{ $calendar->impact }}
                        </span>
                    </div>

                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Bank
                            Holiday</p>
                        <p class="mt-1 text-sm text-gray-900 dark:text-white">
                            {{ $calendar->isBankHoliday ? 'Ya' : 'Tidak' }}
                            @if ($calendar->bankHolidayNote)
                                <span class="text-gray-500 dark:text-gray-400">-
                                    {{ $calendar->bankHolidayNote }}</span>
                            @endif
                        </p>
                    </div>
                </div>

                <div class="space-y-4">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                            Sources
                        </p>
                        <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $calendar->sources }}</p>
                    </div>

                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                            Measures</p>
                        <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $calendar->measures ?: '-' }}</p>
                    </div>

                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Usual
                            Effect</p>
                        <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $calendar->usual_effect ?: '-' }}</p>
                    </div>

                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                            Frequency</p>
                        <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $calendar->frequency ?: '-' }}</p>
                    </div>

                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Next
                            Released</p>
                        <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $calendar->next_released ?: '-' }}</p>
                    </div>

                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Notes
                        </p>
                        <p class="mt-1 text-sm leading-6 text-gray-900 dark:text-white">{{ $calendar->notes ?: '-' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Why
                            Trader Care</p>
                        <p class="mt-1 text-sm leading-6 text-gray-900 dark:text-white">
                            {{ $calendar->why_trader_care ?: '-' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div
            class="rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 shadow-sm overflow-hidden">
            <div
                class="flex items-center justify-between gap-4 border-b border-gray-200 dark:border-gray-700 px-6 py-5">
                <div>
                    <p class="text-sm text-blue-600 dark:text-blue-400 font-semibold">Market Details</p>
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white">Data Detail</h2>
                </div>
                <a href="{{ route('calendar.detail.create', $calendar) }}"
                    class="inline-flex items-center rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700">
                    Create Detail
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-900/60">
                        <tr class="text-left text-sm font-semibold text-gray-700 dark:text-gray-200">
                            <th class="px-6 py-4">Tanggal</th>
                            <th class="px-6 py-4">Waktu</th>
                            <th class="px-6 py-4">Previous</th>
                            <th class="px-6 py-4">Forecast</th>
                            <th class="px-6 py-4">Actual</th>
                            <th class="px-6 py-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody
                        class="divide-y divide-gray-200 dark:divide-gray-700 text-sm text-gray-700 dark:text-gray-200">
                        @forelse ($calendar->details as $detail)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-900/40">
                                <td class="px-6 py-4">
                                    {{ $detail->date ? \Carbon\Carbon::parse($detail->date)->format('d M Y') : '-' }}
                                </td>
                                <td class="px-6 py-4">{{ $detail->time ?: '-' }}</td>
                                <td class="px-6 py-4">{{ $detail->previous ?: '-' }}</td>
                                <td class="px-6 py-4">{{ $detail->forecast ?: '-' }}</td>
                                <td class="px-6 py-4 font-semibold">{{ $detail->actual ?: '-' }}</td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-wrap justify-center gap-2">
                                        <a href="{{ route('calendar.detail.edit', $detail) }}"
                                            class="rounded-lg bg-amber-500 px-3 py-2 text-xs font-semibold text-white hover:bg-amber-600">
                                            Edit
                                        </a>
                                        <form action="{{ route('calendar.detail.delete', $detail) }}" method="POST"
                                            onsubmit="return confirm('Hapus data detail ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="rounded-lg bg-red-600 px-3 py-2 text-xs font-semibold text-white hover:bg-red-700 cursor-pointer">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6"
                                    class="px-6 py-10 text-center text-sm text-gray-500 dark:text-gray-400">
                                    Belum ada data detail untuk category ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
