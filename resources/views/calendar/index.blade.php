@section('namePage', 'Kalender Ekonomi')

<x-app-layout>
    <div x-data="{
        openPreviewPopup() {
            const popup = window.open(
                '{{ route('calendar.preview') }}',
                'calendar-preview',
                'popup=yes,width=1440,height=900,left=120,top=80,resizable=yes,scrollbars=yes'
            );
    
            if (popup) {
                popup.focus();
            } else {
                window.location.href = '{{ route('calendar.preview') }}';
            }
        }
    }" class="px-4 sm:px-6 lg:px-8 py-8 w-full mx-auto flex flex-col gap-6">
        <div class="flex items-center justify-between gap-4">
            <div>
                <p class="text-sm text-blue-600 dark:text-blue-400 font-semibold">Kalender Ekonomi</p>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white">Category Header</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    Setiap category menyimpan header, lalu update rutinnya cukup lewat data detail.
                </p>
            </div>

            <div class="space-y-2 flex flex-col ">
                <button type="button" @click="openPreviewPopup()"
                    class="w-full cursor-pointer text-center rounded-lg border border-slate-300 px-4 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50 dark:border-slate-600 dark:text-slate-200 dark:hover:bg-slate-700">
                    Preview
                </button>

                <a href="{{ route('calendar.create') }}"
                    class="w-full text-center rounded-lg bg-blue-600 px-4 py-2 text-xs text-nowrap font-semibold text-white hover:bg-blue-700">
                    Tambah
                </a>
            </div>
        </div>

        <form action="{{ route('calendar.index') }}" method="GET"
            class="rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-4 shadow-sm">
            <div class="grid gap-3 md:grid-cols-[minmax(0,1fr)_260px_auto]">
                <div class="min-w-0">
                    <label for="q" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Pencarian
                        Category</label>
                    <input type="text" name="q" id="q" value="{{ $search ?? '' }}"
                        class="mt-2 block w-full rounded-lg border-gray-300 bg-white text-gray-900 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-900 dark:text-white"
                        placeholder="Cari category, source, country, impact, atau measures">
                </div>

                <div class="min-w-0">
                    <label for="country" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Negara</label>
                    <select name="country" id="country"
                        class="mt-2 block w-full rounded-lg border-gray-300 bg-white text-gray-900 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-900 dark:text-white">
                        <option value="">Semua Negara</option>
                        @foreach ($countryOptions as $countryOption)
                            <option value="{{ $countryOption['value'] }}"
                                {{ (($selectedCountry ?? null) === $countryOption['value']) ? 'selected' : '' }}>
                                {{ $countryOption['label'] }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex items-end gap-2 md:justify-end">
                    <button type="submit"
                        class="rounded-lg bg-slate-800 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-900">
                        Cari
                    </button>
                    @if (!empty($search) || !empty($selectedCountry))
                        <a href="{{ route('calendar.index') }}"
                            class="rounded-lg border border-gray-300 dark:border-gray-600 px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700">
                            Reset
                        </a>
                    @endif
                </div>
            </div>
        </form>

        @php
            $currencyToCountry = [
                'US' => 'US',
                'EUR' => 'EU',
                'JPN' => 'JP',
                'GBP' => 'GB',
                'AUD' => 'AU',
                'CAD' => 'CA',
                'CHF' => 'CH',
                'CHN' => 'CN',
                'HKD' => 'HK',
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
        @endphp

        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
            @forelse ($categories as $category)
                @php
                    $currencyCode = strtoupper($category->country);
                    $countryCode = $currencyToCountry[$currencyCode] ?? $currencyCode;
                    $countryName = $countryToName[$countryCode] ?? $currencyCode;
                    $latestDetail = $category->latestDetail;
                    $impactClasses =
                        $category->impact === 'High'
                            ? 'bg-red-100 text-red-700 border-red-200'
                            : ($category->impact === 'Medium'
                                ? 'bg-yellow-100 text-yellow-700 border-yellow-200'
                                : 'bg-green-100 text-green-700 border-green-200');
                @endphp

                <div
                    class="rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 shadow-sm overflow-hidden">
                    <div class="p-5">
                        <div class="flex items-start justify-between gap-3">
                            <div class="flex items-center gap-2 min-w-0">
                                @if ($countryCode)
                                    @if ($countryCode === 'EU')
                                        <img src="https://flagcdn.com/w40/eu.png" class="h-4 w-5 rounded-sm"
                                            alt="{{ $currencyCode }}">
                                    @else
                                        <img src="https://flagsapi.com/{{ $countryCode }}/shiny/24.png"
                                            alt="{{ $currencyCode }}">
                                    @endif
                                @endif
                                <span class="text-xs font-semibold tracking-wide text-gray-500 dark:text-gray-400">
                                    {{ $countryName }}
                                </span>
                            </div>

                            <span
                                class="inline-flex rounded-full border px-2.5 py-1 text-[11px] font-semibold {{ $impactClasses }}">
                                {{ $category->impact }}
                            </span>
                        </div>

                        <div class="mt-2">
                            <h2 class="text-base font-bold text-gray-900 dark:text-white line-clamp-1">
                                {{ $category->figures }}
                            </h2>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400 truncate">
                                {{ $category->sources }}
                            </p>
                        </div>

                        <div class="mt-4 grid grid-cols-2 gap-3">
                            <div class="rounded-xl bg-gray-50 dark:bg-gray-900/50 px-3 py-2">
                                <p class="text-[11px] uppercase tracking-wide text-gray-500 dark:text-gray-400">Detail
                                </p>
                                <p class="mt-1 text-sm font-semibold text-gray-900 dark:text-white">
                                    {{ $category->details_count }}
                                </p>
                            </div>

                            <div class="rounded-xl bg-gray-50 dark:bg-gray-900/50 px-3 py-2">
                                <p class="text-[11px] uppercase tracking-wide text-gray-500 dark:text-gray-400">Rilis
                                </p>
                                <p class="mt-1 text-sm font-semibold text-gray-900 dark:text-white">
                                    {{ $latestDetail?->date ? \Carbon\Carbon::parse($latestDetail->date)->format('d M Y') : '-' }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="border-t border-gray-200 dark:border-gray-700 bg-gray-50/80 dark:bg-gray-900/40 p-3">
                        <div class="flex gap-2 w-full">
                            <a href="{{ route('calendar.show', $category) }}"
                                class="w-full rounded-lg bg-slate-700 px-3 py-2 text-center text-xs font-semibold text-white hover:bg-slate-800">
                                Detail
                            </a>
                            <a href="{{ route('calendar.edit', $category) }}"
                                class="w-full rounded-lg bg-amber-500 px-3 py-2 text-center text-xs font-semibold text-white hover:bg-amber-600">
                                Edit
                            </a>
                            <form action="{{ route('calendar.delete', $category) }}" method="POST"
                                onsubmit="return confirm('Hapus category dan semua data detailnya?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="w-full rounded-lg bg-red-600 px-3 py-2 text-xs font-semibold text-white hover:bg-red-700 cursor-pointer">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div
                    class="sm:col-span-2 xl:col-span-3 rounded-2xl border border-dashed border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 px-6 py-12 text-center text-sm text-gray-500 dark:text-gray-400">
                    @if (!empty($search) || !empty($selectedCountry))
                        Tidak ada category yang cocok dengan filter yang dipilih.
                    @else
                        Belum ada category kalender. Buat header terlebih dahulu, lalu tambahkan data detail.
                    @endif
                </div>
            @endforelse
        </div>

        @if ($categories->hasPages())
            <div
                class="rounded-xl shadow border-t border-slate-200 bg-white px-4 py-4 dark:border-slate-700 dark:bg-slate-900">
                <div class="hidden lg:flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                    {{ $categories->onEachSide(1)->links('vendor.pagination.tailwind') }}
                </div>

                {{ $categories->links('vendor.pagination.mobile') }}
            </div>
        @endif
    </div>
</x-app-layout>
