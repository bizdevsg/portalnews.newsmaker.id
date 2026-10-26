<section class="grid gap-6 lg:grid-cols-[minmax(0,1.1fr)_minmax(320px,0.9fr)]">
    <div
        class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-950">
        <h2 class="text-lg font-semibold text-slate-900 dark:text-white">Video TikTok</h2>
        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
            Upload file video TikTok (mp4/mov/webm, maks 50 MB). Mengunggah video baru akan menggantikan video yang
            sedang aktif sekarang.
        </p>

        <div class="mt-6 space-y-5">
            <div>
                <label for="video" class="text-sm font-semibold text-slate-700 dark:text-slate-200">
                    File video {{ $tiktok?->video ? '(opsional - biarkan kosong untuk tetap pakai video saat ini)' : '' }}
                </label>
                <input id="video" name="video" type="file" accept="video/mp4,video/quicktime,video/webm"
                    class="mt-2 w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm outline-none transition file:mr-4 file:rounded-lg file:border-0 file:bg-slate-900 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-white hover:file:bg-slate-800 focus:border-slate-500 focus:ring-2 focus:ring-slate-200 dark:border-slate-700 dark:bg-slate-900 dark:text-white dark:focus:border-slate-500 dark:focus:ring-slate-800"
                    @if (!$tiktok?->video) required @endif>
                @error('video')
                    <p class="mt-2 text-sm text-rose-600 dark:text-rose-300">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="title" class="text-sm font-semibold text-slate-700 dark:text-slate-200">Caption (opsional)</label>
                <input id="title" name="title" type="text" value="{{ old('title', $tiktok?->title) }}"
                    class="mt-2 w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm outline-none transition focus:border-slate-500 focus:ring-2 focus:ring-slate-200 dark:border-slate-700 dark:bg-slate-900 dark:text-white dark:focus:border-slate-500 dark:focus:ring-slate-800"
                    placeholder="Contoh: TikTok Edukasi Harian">
                @error('title')
                    <p class="mt-2 text-sm text-rose-600 dark:text-rose-300">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>

    <div class="space-y-6">
        <section
            class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-950">
            <h2 class="text-lg font-semibold text-slate-900 dark:text-white">Video saat ini</h2>
            @if ($tiktok?->video)
                <video controls class="mx-auto mt-4 max-h-[60vh] w-auto max-w-full rounded-xl bg-black" preload="metadata">
                    <source src="{{ asset($tiktok->video) }}">
                </video>
                <p class="mt-3 text-sm text-slate-500 dark:text-slate-400">
                    Caption: <span class="font-medium text-slate-900 dark:text-white">{{ $tiktok->title ?: '-' }}</span>
                </p>
            @else
                <p class="mt-4 text-sm text-slate-500 dark:text-slate-400">Belum ada video yang diunggah.</p>
            @endif
        </section>
    </div>
</section>
