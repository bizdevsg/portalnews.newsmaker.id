@section('namePage', 'Kalender Ekonomi')

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
        'high' => [
            'label' => 'High',
            'badge' => 'bg-rose-50 text-rose-700 dark:bg-rose-500/10 dark:text-rose-200',
        ],
        'medium' => [
            'label' => 'Medium',
            'badge' => 'bg-amber-50 text-amber-700 dark:bg-amber-500/10 dark:text-amber-200',
        ],
        'low' => [
            'label' => 'Low',
            'badge' => 'bg-emerald-50 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-200',
        ],
    ];

    $defaultImpactMeta = [
        'label' => 'Unknown',
        'badge' => 'bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-200',
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

    $getImpactMeta = function ($impact) use ($impactMeta, $defaultImpactMeta) {
        $key = strtolower(trim((string) $impact));

        return $impactMeta[$key] ?? $defaultImpactMeta;
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
        ];

        if (!is_numeric($actualValue) || !is_numeric($previousValue)) {
            return $meta;
        }

        if ($actualValue > $previousValue) {
            return [
                'wrapper' => 'border-emerald-200 bg-emerald-50 dark:border-emerald-500/20 dark:bg-emerald-500/10',
                'text' => 'text-emerald-700 dark:text-emerald-200',
            ];
        }

        if ($actualValue < $previousValue) {
            return [
                'wrapper' => 'border-rose-200 bg-rose-50 dark:border-rose-500/20 dark:bg-rose-500/10',
                'text' => 'text-rose-700 dark:text-rose-200',
            ];
        }

        return $meta;
    };

    $formatCalendarDate = function ($date) {
        if (!filled($date)) {
            return '-';
        }

        return \Carbon\Carbon::parse($date)->format('d M Y');
    };

    $totalEvents = $stats['totalEvents'] ?? $calendars->total();
    $highImpactCount = $stats['highImpactCount'] ?? 0;
    $mediumImpactCount = $stats['mediumImpactCount'] ?? 0;
    $lowImpactCount = $stats['lowImpactCount'] ?? 0;
    $todayEventsCount = $stats['todayEventsCount'] ?? 0;
    $bankHolidayCount = $stats['bankHolidayCount'] ?? 0;
    $countryCount = $stats['countryCount'] ?? 0;
    $nextEvent = $stats['nextEvent'] ?? null;
    $latestEventDate = filled($stats['latestEventDate'] ?? null) ? $formatCalendarDate($stats['latestEventDate']) : '-';
@endphp

<x-app-layout>
    <div class="mx-auto flex w-full flex-col gap-6 px-4 py-8 sm:px-6 lg:px-8">
        @if (session('success'))
            <div
                class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700 dark:border-emerald-500/20 dark:bg-emerald-500/10 dark:text-emerald-200">
                {{ session('success') }}
            </div>
        @endif

        <section
            class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-950">
            <div class="flex flex-col gap-4 md:flex-row md:items-start md:justify-between">
                <div class="min-w-0">
                    <h1 class="text-2xl font-semibold text-slate-900 dark:text-white sm:text-3xl">
                        Kalender Ekonomi
                    </h1>
                    <p class="mt-2 max-w-2xl text-sm text-slate-500 dark:text-slate-400">
                        Pantau event ekonomi berdasarkan tanggal, waktu, impact, dan hasil rilis dengan tampilan yang
                        lebih sederhana.
                    </p>

                    <div class="mt-4 flex flex-wrap gap-2 text-xs font-medium text-slate-600 dark:text-slate-300">
                        <span class="rounded-full border border-slate-200 px-3 py-1.5 dark:border-slate-700">
                            Update terakhir {{ $latestEventDate }}
                        </span>
                        @if ($nextEvent)
                            <span class="rounded-full border border-slate-200 px-3 py-1.5 dark:border-slate-700">
                                Agenda terdekat {{ $formatCalendarDate($nextEvent->date) }}
                                {{ $nextEvent->time ?: 'Tentative' }}
                            </span>
                        @endif
                    </div>
                </div>

                <a href="{{ route('calendar.create') }}"
                    class="inline-flex items-center justify-center gap-2 rounded-xl bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-slate-800 dark:bg-slate-100 dark:text-slate-900 dark:hover:bg-white">
                    <i class="fa-solid fa-plus"></i>
                    Tambah Kalender
                </a>
            </div>
        </section>

        <section class="grid gap-3 sm:grid-cols-2 xl:grid-cols-4">
            <div
                class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm dark:border-slate-800 dark:bg-slate-950">
                <p class="text-sm text-slate-500 dark:text-slate-400">Total Event</p>
                <p class="mt-2 text-2xl font-semibold text-slate-900 dark:text-white">{{ number_format($totalEvents) }}
                </p>
            </div>
            <div
                class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm dark:border-slate-800 dark:bg-slate-950">
                <p class="text-sm text-slate-500 dark:text-slate-400">Agenda Hari Ini</p>
                <p class="mt-2 text-2xl font-semibold text-slate-900 dark:text-white">
                    {{ number_format($todayEventsCount) }}</p>
            </div>
            <div
                class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm dark:border-slate-800 dark:bg-slate-950">
                <p class="text-sm text-slate-500 dark:text-slate-400">High Impact</p>
                <p class="mt-2 text-2xl font-semibold text-slate-900 dark:text-white">
                    {{ number_format($highImpactCount) }}</p>
            </div>
            <div
                class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm dark:border-slate-800 dark:bg-slate-950">
                <p class="text-sm text-slate-500 dark:text-slate-400">Negara Aktif</p>
                <p class="mt-2 text-2xl font-semibold text-slate-900 dark:text-white">{{ number_format($countryCount) }}
                </p>
            </div>
        </section>

        <section id="calendar-table"
            class="rounded-xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-950">
            <div
                class="flex flex-col gap-3 border-b border-slate-200 p-5 dark:border-slate-800 md:flex-row md:items-center md:justify-between">
                <div>
                    <h2 class="text-lg font-semibold text-slate-900 dark:text-white">Daftar Kalender</h2>
                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                        Data ditampilkan dalam format card di mobile dan tabel di layar besar.
                    </p>
                </div>

                <div class="flex flex-wrap gap-2 text-xs font-medium">
                    <span
                        class="rounded-full bg-rose-50 px-3 py-1.5 text-rose-700 dark:bg-rose-500/10 dark:text-rose-200">High
                        {{ $highImpactCount }}</span>
                    <span
                        class="rounded-full bg-amber-50 px-3 py-1.5 text-amber-700 dark:bg-amber-500/10 dark:text-amber-200">Medium
                        {{ $mediumImpactCount }}</span>
                    <span
                        class="rounded-full bg-emerald-50 px-3 py-1.5 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-200">Low
                        {{ $lowImpactCount }}</span>
                    <span
                        class="rounded-full bg-slate-100 px-3 py-1.5 text-slate-700 dark:bg-slate-800 dark:text-slate-200">Holiday
                        {{ $bankHolidayCount }}</span>
                </div>
            </div>

            @if ($calendars->total() === 0)
                <div class="p-6 text-center sm:p-10">
                    <div
                        class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-slate-100 text-slate-500 dark:bg-slate-800 dark:text-slate-300">
                        <i class="fa-solid fa-calendar-xmark"></i>
                    </div>
                    <h3 class="mt-4 text-lg font-semibold text-slate-900 dark:text-white">Belum ada data kalender</h3>
                    <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">
                        Tambahkan event pertama untuk mulai mengisi halaman ini.
                    </p>
                </div>
            @else
                <div class="grid gap-4 p-4 lg:hidden">
                    @foreach ($calendars as $calendar)
                        @php
                            $countryMeta = $getCountryMeta($calendar->country);
                            $impact = $getImpactMeta($calendar->impact);
                            $actualTone = $getActualTone($calendar->actual, $calendar->previous);
                        @endphp

                        <article class="rounded-2xl border border-slate-200 p-4 dark:border-slate-800">
                            <div class="flex items-start justify-between gap-3">
                                <div class="min-w-0">
                                    <h3 class="text-base font-semibold text-slate-900 dark:text-white">
                                        {{ $calendar->figures }}
                                    </h3>
                                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                                        {{ $calendar->sources ?: 'Sumber belum diisi' }}
                                    </p>
                                </div>

                                <span
                                    class="shrink-0 rounded-full px-3 py-1 text-xs font-semibold {{ $impact['badge'] }}">
                                    {{ $impact['label'] }}
                                </span>
                            </div>

                            <div class="mt-4 grid gap-3 sm:grid-cols-2">
                                <div>
                                    <p class="text-xs font-medium uppercase tracking-wide text-slate-400">Jadwal</p>
                                    <p class="mt-1 text-sm text-slate-700 dark:text-slate-200">
                                        {{ $formatCalendarDate($calendar->date) }} -
                                        {{ $calendar->time ?: 'Tentative' }}
                                    </p>
                                </div>

                                <div>
                                    <p class="text-xs font-medium uppercase tracking-wide text-slate-400">Negara</p>
                                    <div
                                        class="mt-1 flex items-center gap-2 text-sm text-slate-700 dark:text-slate-200">
                                        @if ($countryMeta['flag'])
                                            <img src="https://flagsapi.com/{{ $countryMeta['flag'] }}/shiny/24.png"
                                                alt="{{ $countryMeta['label'] }}" class="h-4 w-4 rounded-full">
                                        @endif
                                        <span>{{ $countryMeta['code'] }} - {{ $countryMeta['label'] }}</span>
                                    </div>
                                </div>
                            </div>

                            @if ($calendar->isBankHoliday)
                                <div class="mt-3 text-xs font-medium text-slate-500 dark:text-slate-400">
                                    Bank Holiday
                                </div>
                            @endif

                            <div class="mt-4 grid grid-cols-3 gap-2">
                                <div class="rounded-xl bg-slate-50 p-3 dark:bg-slate-900">
                                    <p class="text-[11px] uppercase tracking-wide text-slate-400">Previous</p>
                                    <p class="mt-1 text-sm font-semibold text-slate-900 dark:text-white">
                                        {{ $calendar->previous ?: '-' }}</p>
                                </div>
                                <div class="rounded-xl bg-slate-50 p-3 dark:bg-slate-900">
                                    <p class="text-[11px] uppercase tracking-wide text-slate-400">Forecast</p>
                                    <p class="mt-1 text-sm font-semibold text-slate-900 dark:text-white">
                                        {{ $calendar->forecast ?: '-' }}</p>
                                </div>
                                <div class="rounded-xl border p-3 {{ $actualTone['wrapper'] }}">
                                    <p class="text-[11px] uppercase tracking-wide text-slate-400">Actual</p>
                                    <p class="mt-1 text-sm font-semibold {{ $actualTone['text'] }}">
                                        {{ $calendar->actual ?: '-' }}</p>
                                </div>
                            </div>

                            <div class="mt-4 grid gap-2 sm:grid-cols-3">
                                <a href="{{ route('calendar.show', $calendar->id) }}"
                                    class="inline-flex items-center justify-center rounded-xl bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-slate-800 dark:bg-slate-100 dark:text-slate-900 dark:hover:bg-white">
                                    Detail
                                </a>
                                <a href="{{ route('calendar.edit', $calendar->id) }}"
                                    class="inline-flex items-center justify-center rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-900">
                                    Edit
                                </a>
                                <button type="button" onclick="openDeleteModal({{ $calendar->id }})"
                                    class="inline-flex items-center justify-center rounded-xl border border-rose-200 px-4 py-2.5 text-sm font-semibold text-rose-700 transition hover:bg-rose-50 dark:border-rose-500/20 dark:text-rose-200 dark:hover:bg-rose-500/10">
                                    Hapus
                                </button>
                            </div>
                        </article>
                    @endforeach
                </div>

                <div class="hidden lg:block">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-800">
                            <thead class="bg-slate-50 dark:bg-slate-900">
                                <tr>
                                    <th
                                        class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                                        Peristiwa</th>
                                    <th
                                        class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                                        Jadwal</th>
                                    <th
                                        class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                                        Negara</th>
                                    <th
                                        class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                                        Impact</th>
                                    <th
                                        class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                                        Previous</th>
                                    <th
                                        class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                                        Forecast</th>
                                    <th
                                        class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                                        Actual</th>
                                    <th
                                        class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                                        Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                                @foreach ($calendars as $calendar)
                                    @php
                                        $countryMeta = $getCountryMeta($calendar->country);
                                        $impact = $getImpactMeta($calendar->impact);
                                        $actualTone = $getActualTone($calendar->actual, $calendar->previous);
                                    @endphp

                                    <tr class="align-top">
                                        <td class="px-5 py-4">
                                            <p class="font-semibold text-slate-900 dark:text-white">
                                                {{ $calendar->figures }}</p>
                                            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                                                {{ $calendar->sources ?: 'Sumber belum diisi' }}</p>
                                        </td>
                                        <td class="px-5 py-4 text-sm text-slate-700 dark:text-slate-200">
                                            <div>{{ $formatCalendarDate($calendar->date) }}</div>
                                            <div class="mt-1 text-slate-500 dark:text-slate-400">
                                                {{ $calendar->time ?: 'Tentative' }}</div>
                                        </td>
                                        <td class="px-5 py-4 text-sm text-slate-700 dark:text-slate-200">
                                            <div class="flex items-center gap-2">
                                                @if ($countryMeta['flag'])
                                                    <img src="https://flagsapi.com/{{ $countryMeta['flag'] }}/shiny/24.png"
                                                        alt="{{ $countryMeta['label'] }}" class="h-4 w-4 rounded-full">
                                                @endif
                                                <span>{{ $countryMeta['code'] }}</span>
                                            </div>
                                            <div class="mt-1 text-slate-500 dark:text-slate-400">
                                                {{ $countryMeta['label'] }}</div>
                                        </td>
                                        <td class="px-5 py-4">
                                            <span
                                                class="rounded-full px-3 py-1 text-xs font-semibold {{ $impact['badge'] }}">
                                                {{ $impact['label'] }}
                                            </span>
                                            @if ($calendar->isBankHoliday)
                                                <div class="mt-2 text-xs text-slate-500 dark:text-slate-400">Bank
                                                    Holiday</div>
                                            @endif
                                        </td>
                                        <td class="px-5 py-4 text-sm font-medium text-slate-900 dark:text-white">
                                            {{ $calendar->previous ?: '-' }}</td>
                                        <td class="px-5 py-4 text-sm font-medium text-slate-900 dark:text-white">
                                            {{ $calendar->forecast ?: '-' }}</td>
                                        <td class="px-5 py-4 text-sm font-medium {{ $actualTone['text'] }}">
                                            {{ $calendar->actual ?: '-' }}</td>
                                        <td class="px-5 py-4">
                                            <div class="flex flex-wrap gap-2">
                                                <a href="{{ route('calendar.show', $calendar->id) }}"
                                                    class="inline-flex items-center justify-center rounded-lg bg-slate-900 px-3 py-2 text-xs font-semibold text-white transition hover:bg-slate-800 dark:bg-slate-100 dark:text-slate-900 dark:hover:bg-white">
                                                    Detail
                                                </a>
                                                <a href="{{ route('calendar.edit', $calendar->id) }}"
                                                    class="inline-flex items-center justify-center rounded-lg border border-slate-200 px-3 py-2 text-xs font-semibold text-slate-700 transition hover:bg-slate-50 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-900">
                                                    Edit
                                                </a>
                                                <button type="button" onclick="openDeleteModal({{ $calendar->id }})"
                                                    class="inline-flex items-center justify-center rounded-lg border border-rose-200 px-3 py-2 text-xs font-semibold text-rose-700 transition hover:bg-rose-50 dark:border-rose-500/20 dark:text-rose-200 dark:hover:bg-rose-500/10">
                                                    Hapus
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                @if ($calendars->hasPages())
                    <div
                        class="border-t border-slate-200 px-4 py-4 dark:border-slate-800 sm:px-5">
                        {{ $calendars->onEachSide(1)->links() }}
                    </div>
                @endif
            @endif
        </section>

        <div id="deleteModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-950/50 p-4">
            <div
                class="w-full max-w-md rounded-xl border border-slate-200 bg-white p-6 shadow-xl dark:border-slate-800 dark:bg-slate-950">
                <h2 class="text-lg font-semibold text-slate-900 dark:text-white">Konfirmasi Hapus</h2>
                <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">
                    Data yang dihapus tidak bisa dikembalikan.
                </p>

                <form id="deleteForm" method="POST" class="mt-6">
                    @csrf
                    @method('DELETE')

                    <div class="flex flex-col-reverse gap-2 sm:flex-row sm:justify-end">
                        <button type="button" onclick="closeDeleteModal()"
                            class="inline-flex items-center justify-center rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-900">
                            Batal
                        </button>
                        <button type="submit"
                            class="inline-flex items-center justify-center rounded-xl bg-rose-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-rose-700">
                            Hapus
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        const deleteForm = document.getElementById('deleteForm');
        const deleteModal = document.getElementById('deleteModal');

        function openDeleteModal(id) {
            deleteForm.action = `{{ url('/kalender') }}/${id}/delete`;
            deleteModal.classList.remove('hidden');
            deleteModal.classList.add('flex');
            document.body.classList.add('overflow-hidden');
        }

        function closeDeleteModal() {
            deleteModal.classList.add('hidden');
            deleteModal.classList.remove('flex');
            document.body.classList.remove('overflow-hidden');
        }

        if (deleteModal) {
            deleteModal.addEventListener('click', function(event) {
                if (event.target === deleteModal) {
                    closeDeleteModal();
                }
            });
        }

        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape' && deleteModal && !deleteModal.classList.contains('hidden')) {
                closeDeleteModal();
            }
        });
    </script>
</x-app-layout>
