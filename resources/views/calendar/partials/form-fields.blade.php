@php
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

    $inputClass =
        'mt-1 w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-900 shadow-sm outline-none transition focus:border-slate-400 focus:ring-2 focus:ring-slate-200 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100 dark:focus:border-slate-500 dark:focus:ring-slate-800';
    $textareaClass =
        'mt-1 w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-900 shadow-sm outline-none transition focus:border-slate-400 focus:ring-2 focus:ring-slate-200 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100 dark:focus:border-slate-500 dark:focus:ring-slate-800';
    $selectClass =
        'mt-1 w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-900 shadow-sm outline-none transition focus:border-slate-400 focus:ring-2 focus:ring-slate-200 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100 dark:focus:border-slate-500 dark:focus:ring-slate-800';

    $calendarData = $calendar ?? null;
    $dateDefault = filled(data_get($calendarData, 'date'))
        ? \Carbon\Carbon::parse(data_get($calendarData, 'date'))->format('Y-m-d')
        : '';
    $traderCareValue = old(
        'why_trader_care',
        data_get($calendarData, 'why_trader_care', data_get($calendarData, 'why_traders_care', '')),
    );
@endphp

<section class="rounded-xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-950">
    <div class="border-b border-slate-200 p-5 dark:border-slate-800">
        <h2 class="text-lg font-semibold text-slate-900 dark:text-white">Informasi Utama</h2>
        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
            Isi data utama event ekonomi yang akan ditampilkan di daftar kalender.
        </p>
    </div>

    <div class="grid gap-4 p-5 md:grid-cols-2 xl:grid-cols-3">
        <div class="xl:col-span-3">
            <label for="figures" class="text-sm font-medium text-slate-700 dark:text-slate-200">Peristiwa</label>
            <input type="text" name="figures" id="figures" required
                value="{{ old('figures', data_get($calendarData, 'figures', '')) }}" class="{{ $inputClass }}">
            @error('figures')
                <p class="mt-1 text-sm text-rose-600 dark:text-rose-300">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="sources" class="text-sm font-medium text-slate-700 dark:text-slate-200">Sources</label>
            <input type="text" name="sources" id="sources" required
                value="{{ old('sources', data_get($calendarData, 'sources', '')) }}" class="{{ $inputClass }}">
            @error('sources')
                <p class="mt-1 text-sm text-rose-600 dark:text-rose-300">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="country" class="text-sm font-medium text-slate-700 dark:text-slate-200">Negara</label>
            <select name="country" id="country" required class="{{ $selectClass }}">
                <option value="">Pilih negara</option>
                @foreach ($countries as $country)
                    <option value="{{ $country['codeCountry'] }}"
                        @selected(old('country', data_get($calendarData, 'country', 'IDN')) == $country['codeCountry'])>
                        {{ $country['codeCountry'] }} - {{ $country['name'] }}
                    </option>
                @endforeach
            </select>
            @error('country')
                <p class="mt-1 text-sm text-rose-600 dark:text-rose-300">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="impact" class="text-sm font-medium text-slate-700 dark:text-slate-200">Impact</label>
            <select name="impact" id="impact" required class="{{ $selectClass }}">
                <option value="">Pilih impact</option>
                <option value="Low" @selected(old('impact', data_get($calendarData, 'impact', '')) === 'Low')>Low</option>
                <option value="Medium" @selected(old('impact', data_get($calendarData, 'impact', '')) === 'Medium')>Medium</option>
                <option value="High" @selected(old('impact', data_get($calendarData, 'impact', '')) === 'High')>High</option>
            </select>
            @error('impact')
                <p class="mt-1 text-sm text-rose-600 dark:text-rose-300">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="date" class="text-sm font-medium text-slate-700 dark:text-slate-200">Tanggal</label>
            <input type="date" name="date" id="date" value="{{ old('date', $dateDefault) }}"
                class="{{ $inputClass }}">
            @error('date')
                <p class="mt-1 text-sm text-rose-600 dark:text-rose-300">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="time" class="text-sm font-medium text-slate-700 dark:text-slate-200">Waktu</label>
            <input type="text" name="time" id="time" placeholder="Contoh: 13:00 / Tentative / 10th"
                value="{{ old('time', data_get($calendarData, 'time', '')) }}" class="{{ $inputClass }}">
            @error('time')
                <p class="mt-1 text-sm text-rose-600 dark:text-rose-300">{{ $message }}</p>
            @enderror
        </div>
    </div>
</section>

<section class="rounded-xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-950">
    <div class="border-b border-slate-200 p-5 dark:border-slate-800">
        <h2 class="text-lg font-semibold text-slate-900 dark:text-white">Angka Rilis</h2>
        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
            Nilai sebelumnya, estimasi, dan hasil aktual jika sudah tersedia.
        </p>
    </div>

    <div class="grid gap-4 p-5 md:grid-cols-3">
        <div>
            <label for="previous" class="text-sm font-medium text-slate-700 dark:text-slate-200">Previous</label>
            <input type="text" name="previous" id="previous"
                value="{{ old('previous', data_get($calendarData, 'previous', '')) }}" class="{{ $inputClass }}">
            @error('previous')
                <p class="mt-1 text-sm text-rose-600 dark:text-rose-300">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="forecast" class="text-sm font-medium text-slate-700 dark:text-slate-200">Forecast</label>
            <input type="text" name="forecast" id="forecast"
                value="{{ old('forecast', data_get($calendarData, 'forecast', '')) }}" class="{{ $inputClass }}">
            @error('forecast')
                <p class="mt-1 text-sm text-rose-600 dark:text-rose-300">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="actual" class="text-sm font-medium text-slate-700 dark:text-slate-200">Actual</label>
            <input type="text" name="actual" id="actual"
                value="{{ old('actual', data_get($calendarData, 'actual', '')) }}" class="{{ $inputClass }}">
            @error('actual')
                <p class="mt-1 text-sm text-rose-600 dark:text-rose-300">{{ $message }}</p>
            @enderror
        </div>
    </div>
</section>

<section class="rounded-xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-950">
    <div class="border-b border-slate-200 p-5 dark:border-slate-800">
        <h2 class="text-lg font-semibold text-slate-900 dark:text-white">Konteks Event</h2>
        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
            Informasi tambahan untuk membantu pembacaan data.
        </p>
    </div>

    <div class="grid gap-4 p-5 md:grid-cols-2">
        <div>
            <label for="measures" class="text-sm font-medium text-slate-700 dark:text-slate-200">Measures</label>
            <input type="text" name="measures" id="measures"
                value="{{ old('measures', data_get($calendarData, 'measures', '')) }}" class="{{ $inputClass }}"
                @if (!empty($isEdit)) required @endif>
            @error('measures')
                <p class="mt-1 text-sm text-rose-600 dark:text-rose-300">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="usual_effect" class="text-sm font-medium text-slate-700 dark:text-slate-200">Usual Effect</label>
            <input type="text" name="usual_effect" id="usual_effect"
                value="{{ old('usual_effect', data_get($calendarData, 'usual_effect', '')) }}" class="{{ $inputClass }}"
                @if (!empty($isEdit)) required @endif>
            @error('usual_effect')
                <p class="mt-1 text-sm text-rose-600 dark:text-rose-300">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="frequency" class="text-sm font-medium text-slate-700 dark:text-slate-200">Frequency</label>
            <input type="text" name="frequency" id="frequency"
                value="{{ old('frequency', data_get($calendarData, 'frequency', '')) }}" class="{{ $inputClass }}"
                @if (!empty($isEdit)) required @endif>
            @error('frequency')
                <p class="mt-1 text-sm text-rose-600 dark:text-rose-300">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="next_released" class="text-sm font-medium text-slate-700 dark:text-slate-200">Next Released</label>
            <input type="text" name="next_released" id="next_released"
                value="{{ old('next_released', data_get($calendarData, 'next_released', '')) }}" class="{{ $inputClass }}">
            @error('next_released')
                <p class="mt-1 text-sm text-rose-600 dark:text-rose-300">{{ $message }}</p>
            @enderror
        </div>
    </div>
</section>

<section class="rounded-xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-950">
    <div class="border-b border-slate-200 p-5 dark:border-slate-800">
        <h2 class="text-lg font-semibold text-slate-900 dark:text-white">Catatan</h2>
        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
            Tambahkan insight tambahan untuk kebutuhan internal dan pembaca.
        </p>
    </div>

    <div class="grid gap-4 p-5 lg:grid-cols-2">
        <div>
            <label for="notes" class="text-sm font-medium text-slate-700 dark:text-slate-200">Notes</label>
            <textarea name="notes" id="notes" rows="6" class="{{ $textareaClass }}">{{ old('notes', data_get($calendarData, 'notes', '')) }}</textarea>
            @error('notes')
                <p class="mt-1 text-sm text-rose-600 dark:text-rose-300">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="why_trader_care" class="text-sm font-medium text-slate-700 dark:text-slate-200">Why Traders Care</label>
            <textarea name="why_trader_care" id="why_trader_care" rows="6" class="{{ $textareaClass }}">{{ $traderCareValue }}</textarea>
            @error('why_trader_care')
                <p class="mt-1 text-sm text-rose-600 dark:text-rose-300">{{ $message }}</p>
            @enderror
        </div>

        <div class="rounded-xl border border-slate-200 p-4 dark:border-slate-800 lg:col-span-2">
            <div class="flex items-start gap-3">
                <input type="checkbox" name="isBankHoliday" id="isBankHoliday" value="1"
                    class="mt-1 h-4 w-4 rounded border-slate-300 text-slate-900 focus:ring-slate-400"
                    @checked(old('isBankHoliday', data_get($calendarData, 'isBankHoliday', false)))>
                <div>
                    <label for="isBankHoliday" class="text-sm font-medium text-slate-700 dark:text-slate-200">Bank Holiday</label>
                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                        Tandai event sebagai bank holiday bila memang tidak ada rilis data normal.
                    </p>
                </div>
            </div>

            <div class="mt-4">
                <label for="bankHolidayNote" class="text-sm font-medium text-slate-700 dark:text-slate-200">Catatan Bank Holiday</label>
                <textarea name="bankHolidayNote" id="bankHolidayNote" rows="5" placeholder="Tulis catatan tambahan jika perlu"
                    class="{{ $textareaClass }}">{{ old('bankHolidayNote', data_get($calendarData, 'bankHolidayNote', '')) }}</textarea>
                @error('bankHolidayNote')
                    <p class="mt-1 text-sm text-rose-600 dark:text-rose-300">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>
</section>
