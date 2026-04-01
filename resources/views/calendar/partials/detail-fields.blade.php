@php
    $detail = $detail ?? null;
@endphp

<div class="space-y-6">
    <div class="rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/40 p-4">
        <p class="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Category</p>
        <p class="mt-1 text-lg font-semibold text-gray-900 dark:text-white">{{ $category->figures }}</p>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
            {{ $category->country }} | {{ $category->impact }} Impact | {{ $category->sources }}
        </p>
    </div>

    <div class="grid gap-6 md:grid-cols-2">
        <div>
            <label for="date" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Tanggal</label>
            <input type="date" name="date" id="date"
                value="{{ old('date', $detail?->date ? \Carbon\Carbon::parse($detail->date)->format('Y-m-d') : null) }}"
                class="mt-2 block w-full rounded-lg border-gray-300 bg-white text-gray-900 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-900 dark:text-white"
                required>
            @error('date')
                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="time" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Waktu</label>
            <input type="text" name="time" id="time" value="{{ old('time', $detail?->time) }}"
                class="mt-2 block w-full rounded-lg border-gray-300 bg-white text-gray-900 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-900 dark:text-white"
                placeholder="Contoh: 08:30">
            @error('time')
                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div class="grid gap-6 md:grid-cols-3">
        <div>
            <label for="previous" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Previous</label>
            <input type="text" name="previous" id="previous" value="{{ old('previous', $detail?->previous) }}"
                class="mt-2 block w-full rounded-lg border-gray-300 bg-white text-gray-900 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-900 dark:text-white"
                placeholder="Contoh: 2.8%">
            @error('previous')
                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="forecast" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Forecast</label>
            <input type="text" name="forecast" id="forecast" value="{{ old('forecast', $detail?->forecast) }}"
                class="mt-2 block w-full rounded-lg border-gray-300 bg-white text-gray-900 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-900 dark:text-white"
                placeholder="Contoh: 3.0%">
            @error('forecast')
                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="actual" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Actual</label>
            <input type="text" name="actual" id="actual" value="{{ old('actual', $detail?->actual) }}"
                class="mt-2 block w-full rounded-lg border-gray-300 bg-white text-gray-900 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-900 dark:text-white"
                placeholder="Contoh: 3.1%">
            @error('actual')
                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
            @enderror
        </div>
    </div>
</div>
