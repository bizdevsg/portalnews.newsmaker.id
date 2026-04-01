@php
    $calendar = $calendar ?? null;
    $countries = [
        ['codeCountry' => 'AUD', 'name' => 'Australia'],
        ['codeCountry' => 'CAD', 'name' => 'Canada'],
        ['codeCountry' => 'CHF', 'name' => 'Switzerland'],
        ['codeCountry' => 'CHN', 'name' => 'China'],
        ['codeCountry' => 'EUR', 'name' => 'European Union'],
        ['codeCountry' => 'GBP', 'name' => 'United Kingdom'],
        ['codeCountry' => 'IDN', 'name' => 'Indonesia'],
        ['codeCountry' => 'JPN', 'name' => 'Japan'],
        ['codeCountry' => 'US', 'name' => 'United States'],
    ];
@endphp

<div class="space-y-6">
    <div class="grid gap-6 lg:grid-cols-3">
        <div class="lg:col-span-2">
            <label for="figures" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Category / Description</label>
            <input type="text" name="figures" id="figures" value="{{ old('figures', $calendar?->figures) }}"
                class="mt-2 block w-full rounded-lg border-gray-300 bg-white text-gray-900 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-900 dark:text-white"
                placeholder="Contoh: CPI y/y (CHN)" required>
            @error('figures')
                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="impact" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Impact Level</label>
            <select name="impact" id="impact"
                class="mt-2 block w-full rounded-lg border-gray-300 bg-white text-gray-900 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-900 dark:text-white"
                required>
                <option value="">Pilih impact</option>
                @foreach (['Low', 'Medium', 'High'] as $impact)
                    <option value="{{ $impact }}" {{ old('impact', $calendar?->impact) === $impact ? 'selected' : '' }}>
                        {{ $impact }}
                    </option>
                @endforeach
            </select>
            @error('impact')
                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="country" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Country</label>
            <select name="country" id="country"
                class="mt-2 block w-full rounded-lg border-gray-300 bg-white text-gray-900 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-900 dark:text-white"
                required>
                <option value="">Pilih country</option>
                @foreach ($countries as $country)
                    <option value="{{ $country['codeCountry'] }}"
                        {{ old('country', $calendar?->country) === $country['codeCountry'] ? 'selected' : '' }}>
                        {{ $country['codeCountry'] }} - {{ $country['name'] }}
                    </option>
                @endforeach
            </select>
            @error('country')
                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div class="rounded-2xl border border-gray-200 bg-gray-50/70 p-5 dark:border-gray-700 dark:bg-gray-900/40">
        <div class="mb-5">
            <p class="text-xs font-semibold uppercase tracking-wide text-blue-600 dark:text-blue-400">Header Card</p>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                Urutan input di bawah mengikuti urutan tampilan header pada preview kalender ekonomi.
            </p>
        </div>

        <div class="space-y-5">
            <div>
                <label for="sources" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Sources</label>
                <input type="text" name="sources" id="sources" value="{{ old('sources', $calendar?->sources) }}"
                    class="mt-2 block w-full rounded-lg border-gray-300 bg-white text-gray-900 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-900 dark:text-white"
                    placeholder="Contoh: Biro Statistik Nasional China" required>
                @error('sources')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="measures" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Measures</label>
                <textarea name="measures" id="measures" rows="3"
                    class="mt-2 block w-full rounded-lg border-gray-300 bg-white text-gray-900 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-900 dark:text-white"
                    placeholder="Apa yang diukur oleh kalender ini?">{{ old('measures', $calendar?->measures) }}</textarea>
                @error('measures')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="usual_effect" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Usual Effect</label>
                <textarea name="usual_effect" id="usual_effect" rows="3"
                    class="mt-2 block w-full rounded-lg border-gray-300 bg-white text-gray-900 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-900 dark:text-white"
                    placeholder="Contoh: Aktual > Perkiraan = Positif">{{ old('usual_effect', $calendar?->usual_effect) }}</textarea>
                @error('usual_effect')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="frequency" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Frequency</label>
                <input type="text" name="frequency" id="frequency" value="{{ old('frequency', $calendar?->frequency) }}"
                    class="mt-2 block w-full rounded-lg border-gray-300 bg-white text-gray-900 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-900 dark:text-white"
                    placeholder="Contoh: Rilis bulanan">
                @error('frequency')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="next_released" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Next Released</label>
                <input type="text" name="next_released" id="next_released"
                    value="{{ old('next_released', $calendar?->next_released) }}"
                    class="mt-2 block w-full rounded-lg border-gray-300 bg-white text-gray-900 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-900 dark:text-white"
                    placeholder="Contoh: 11 Apr 2026">
                @error('next_released')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="notes" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Notes</label>
                <textarea name="notes" id="notes" rows="4"
                    class="mt-2 block w-full rounded-lg border-gray-300 bg-white text-gray-900 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-900 dark:text-white"
                    placeholder="Catatan tambahan">{{ old('notes', $calendar?->notes) }}</textarea>
                @error('notes')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="why_trader_care" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Why Trader Care</label>
                <textarea name="why_trader_care" id="why_trader_care" rows="5"
                    class="mt-2 block w-full rounded-lg border-gray-300 bg-white text-gray-900 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-900 dark:text-white"
                    placeholder="Mengapa data ini penting untuk market?">{{ old('why_trader_care', $calendar?->why_trader_care) }}</textarea>
                @error('why_trader_care')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>

    <div class="rounded-xl border border-gray-200 p-4 dark:border-gray-700">
        <label class="flex items-center gap-3 text-sm font-medium text-gray-700 dark:text-gray-200">
            <input type="checkbox" name="isBankHoliday" value="1"
                class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                @checked(old('isBankHoliday', $calendar?->isBankHoliday))>
            Tandai sebagai bank holiday
        </label>
        <textarea name="bankHolidayNote" id="bankHolidayNote" rows="3"
            class="mt-3 block w-full rounded-lg border-gray-300 bg-white text-gray-900 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-900 dark:text-white"
            placeholder="Catatan bank holiday (opsional)">{{ old('bankHolidayNote', $calendar?->bankHolidayNote) }}</textarea>
        @error('bankHolidayNote')
            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
        @enderror
    </div>
</div>
