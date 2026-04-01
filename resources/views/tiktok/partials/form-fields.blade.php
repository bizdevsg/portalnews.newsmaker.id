<section class="grid gap-6 lg:grid-cols-[minmax(0,1.1fr)_minmax(320px,0.9fr)]">
    <div
        class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-950">
        <h2 class="text-lg font-semibold text-slate-900 dark:text-white">Informasi Utama</h2>
        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
            Isi judul dan kode embed TikTok yang akan ditampilkan di halaman preview.
        </p>

        <div class="mt-6 space-y-5">
            <div>
                <label for="title" class="text-sm font-semibold text-slate-700 dark:text-slate-200">Judul</label>
                <input id="title" name="title" type="text" value="{{ old('title', $tiktok?->title) }}"
                    class="mt-2 w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm outline-none transition focus:border-slate-500 focus:ring-2 focus:ring-slate-200 dark:border-slate-700 dark:bg-slate-900 dark:text-white dark:focus:border-slate-500 dark:focus:ring-slate-800"
                    placeholder="Contoh: TikTok Edukasi Harian" required>
                @error('title')
                    <p class="mt-2 text-sm text-rose-600 dark:text-rose-300">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="embed_code" class="text-sm font-semibold text-slate-700 dark:text-slate-200">Embed Code</label>
                <textarea id="embed_code" name="embed_code" rows="10"
                    class="mt-2 w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm outline-none transition focus:border-slate-500 focus:ring-2 focus:ring-slate-200 dark:border-slate-700 dark:bg-slate-900 dark:text-white dark:focus:border-slate-500 dark:focus:ring-slate-800"
                    placeholder="<blockquote>...</blockquote>" required>{{ old('embed_code', $tiktok?->embed_code) }}</textarea>
                @error('embed_code')
                    <p class="mt-2 text-sm text-rose-600 dark:text-rose-300">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="backup_video_url" class="text-sm font-semibold text-slate-700 dark:text-slate-200">Backup Video URL</label>
                <input id="backup_video_url" name="backup_video_url" type="text"
                    value="{{ old('backup_video_url', $tiktok?->backup_video_url) }}"
                    class="mt-2 w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 shadow-sm outline-none transition focus:border-slate-500 focus:ring-2 focus:ring-slate-200 dark:border-slate-700 dark:bg-slate-900 dark:text-white dark:focus:border-slate-500 dark:focus:ring-slate-800"
                    placeholder="https://...">
                @error('backup_video_url')
                    <p class="mt-2 text-sm text-rose-600 dark:text-rose-300">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>

    <div class="space-y-6">
        <section
            class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-950">
            <h2 class="text-lg font-semibold text-slate-900 dark:text-white">Ringkasan</h2>
            <dl class="mt-4 space-y-4 text-sm">
                <div>
                    <dt class="text-slate-500 dark:text-slate-400">Judul saat ini</dt>
                    <dd class="mt-1 font-medium text-slate-900 dark:text-white">
                        {{ old('title', $tiktok?->title) ?: '-' }}
                    </dd>
                </div>
                <div>
                    <dt class="text-slate-500 dark:text-slate-400">Backup video</dt>
                    <dd class="mt-1 break-all font-medium text-slate-900 dark:text-white">
                        {{ old('backup_video_url', $tiktok?->backup_video_url) ?: 'Belum diisi' }}
                    </dd>
                </div>
            </dl>
        </section>

        <section
            class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-950">
            <h2 class="text-lg font-semibold text-slate-900 dark:text-white">Preview Kode</h2>
            <div class="mt-4 rounded-xl bg-slate-50 p-4 dark:bg-slate-900">
                <pre class="whitespace-pre-wrap break-words text-xs leading-6 text-slate-700 dark:text-slate-200">{{ old('embed_code', $tiktok?->embed_code) ?: 'Embed code akan tampil di sini setelah diisi.' }}</pre>
            </div>
        </section>
    </div>
</section>
