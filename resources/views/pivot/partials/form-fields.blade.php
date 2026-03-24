@php
    $categories = [
        'LGD Daily',
        'BCO Daily',
        'HSI Daily',
        'SNI Daily',
        'AUD/USD',
        'EUR/USD',
        'GBP/USD',
        'USD/CHF',
        'USD/JPY',
    ];

    $inputClass =
        'mt-1 w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-900 shadow-sm outline-none transition focus:border-slate-400 focus:ring-2 focus:ring-slate-200 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100 dark:focus:border-slate-500 dark:focus:ring-slate-800';
    $selectClass = $inputClass;
    $textareaClass = $inputClass;

    $pivotData = $pivot ?? null;
    $isHoliday = old('isBankHoliday', data_get($pivotData, 'isBankHoliday', false));
    $dateValue = filled(data_get($pivotData, 'tanggal'))
        ? \Carbon\Carbon::parse(data_get($pivotData, 'tanggal'))->format('Y-m-d')
        : '';
@endphp

<section class="rounded-xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-950">
    <div class="border-b border-slate-200 p-5 dark:border-slate-800">
        <h2 class="text-lg font-semibold text-slate-900 dark:text-white">Informasi Utama</h2>
        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
            Pilih tanggal dan kategori historical data yang akan disimpan.
        </p>
    </div>

    <div class="grid gap-4 p-5 md:grid-cols-2">
        <div>
            <label for="tanggal" class="text-sm font-medium text-slate-700 dark:text-slate-200">Tanggal</label>
            <input type="date" name="tanggal" id="tanggal" value="{{ old('tanggal', $dateValue) }}" required
                class="{{ $inputClass }}">
            @error('tanggal')
                <p class="mt-1 text-sm text-rose-600 dark:text-rose-300">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="category" class="text-sm font-medium text-slate-700 dark:text-slate-200">Kategori</label>
            <select name="category" id="category" required class="{{ $selectClass }}">
                <option value="">Pilih kategori</option>
                @foreach ($categories as $kategori)
                    <option value="{{ $kategori }}"
                        @selected(old('category', data_get($pivotData, 'category', request('category', 'LGD Daily'))) === $kategori)>
                        {{ $kategori }}
                    </option>
                @endforeach
            </select>
            @error('category')
                <p class="mt-1 text-sm text-rose-600 dark:text-rose-300">{{ $message }}</p>
            @enderror
        </div>
    </div>
</section>

<section class="rounded-xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-950">
    <div class="border-b border-slate-200 p-5 dark:border-slate-800">
        <h2 class="text-lg font-semibold text-slate-900 dark:text-white">Data OHLC</h2>
        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
            Isi nilai open, high, low, dan close. Field ini akan disembunyikan bila data ditandai sebagai bank holiday.
        </p>
    </div>

    <div class="p-5">
        <div id="ohlcFields" class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <div>
                <label for="open" class="text-sm font-medium text-slate-700 dark:text-slate-200">Open</label>
                <input type="text" name="open" id="open" data-ohlc-input
                    value="{{ old('open', data_get($pivotData, 'open', '')) }}"
                    @if (!$isHoliday) required @endif class="{{ $inputClass }}">
                @error('open')
                    <p class="mt-1 text-sm text-rose-600 dark:text-rose-300">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="high" class="text-sm font-medium text-slate-700 dark:text-slate-200">High</label>
                <input type="text" name="high" id="high" data-ohlc-input
                    value="{{ old('high', data_get($pivotData, 'high', '')) }}"
                    @if (!$isHoliday) required @endif class="{{ $inputClass }}">
                @error('high')
                    <p class="mt-1 text-sm text-rose-600 dark:text-rose-300">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="low" class="text-sm font-medium text-slate-700 dark:text-slate-200">Low</label>
                <input type="text" name="low" id="low" data-ohlc-input
                    value="{{ old('low', data_get($pivotData, 'low', '')) }}"
                    @if (!$isHoliday) required @endif class="{{ $inputClass }}">
                @error('low')
                    <p class="mt-1 text-sm text-rose-600 dark:text-rose-300">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="close" class="text-sm font-medium text-slate-700 dark:text-slate-200">Close</label>
                <input type="text" name="close" id="close" data-ohlc-input
                    value="{{ old('close', data_get($pivotData, 'close', '')) }}"
                    @if (!$isHoliday) required @endif class="{{ $inputClass }}">
                @error('close')
                    <p class="mt-1 text-sm text-rose-600 dark:text-rose-300">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>
</section>

<section class="rounded-xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-950">
    <div class="border-b border-slate-200 p-5 dark:border-slate-800">
        <h2 class="text-lg font-semibold text-slate-900 dark:text-white">Bank Holiday</h2>
        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
            Aktifkan bila tanggal tersebut tidak memiliki pergerakan data normal.
        </p>
    </div>

    <div class="grid gap-4 p-5 lg:grid-cols-[minmax(0,0.8fr)_minmax(0,1.2fr)]">
        <div class="rounded-xl border border-slate-200 p-4 dark:border-slate-800">
            <input type="hidden" name="isBankHoliday" value="0">

            <div class="flex items-start gap-3">
                <input type="checkbox" name="isBankHoliday" id="isBankHoliday" value="1"
                    class="mt-1 h-4 w-4 rounded border-slate-300 text-slate-900 focus:ring-slate-400"
                    @checked($isHoliday)>
                <div>
                    <label for="isBankHoliday" class="text-sm font-medium text-slate-700 dark:text-slate-200">Tandai sebagai bank holiday</label>
                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                        Saat aktif, field OHLC disembunyikan agar form lebih fokus ke catatan hari libur.
                    </p>
                </div>
            </div>
        </div>

        <div id="bankHolidayDescription" class="@if (!$isHoliday) hidden @endif">
            <label for="description" class="text-sm font-medium text-slate-700 dark:text-slate-200">Keterangan Bank Holiday</label>
            <textarea name="description" id="description" rows="5" placeholder="Tuliskan keterangan singkat bila diperlukan"
                class="{{ $textareaClass }}">{{ old('description', data_get($pivotData, 'description', '')) }}</textarea>
            @error('description')
                <p class="mt-1 text-sm text-rose-600 dark:text-rose-300">{{ $message }}</p>
            @enderror
        </div>
    </div>
</section>
