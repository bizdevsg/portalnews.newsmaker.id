@php
    $inputClass =
        'mt-1 w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-900 shadow-sm outline-none transition focus:border-slate-400 focus:ring-2 focus:ring-slate-200 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100 dark:focus:border-slate-500 dark:focus:ring-slate-800';
    $textareaClass = $inputClass;

    $tiktokData = $tiktok ?? null;
    $embedPreviewValue = old('embed_code', data_get($tiktokData, 'embed_code', ''));
@endphp

<section class="rounded-xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-950">
    <div class="border-b border-slate-200 p-5 dark:border-slate-800">
        <h2 class="text-lg font-semibold text-slate-900 dark:text-white">Informasi Konten</h2>
        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
            Isi identitas dasar konten TikTok yang akan disimpan.
        </p>
    </div>

    <div class="grid gap-4 p-5 lg:grid-cols-2">
        <div>
            <label for="title" class="text-sm font-medium text-slate-700 dark:text-slate-200">Judul</label>
            <input type="text" name="title" id="title" value="{{ old('title', data_get($tiktokData, 'title', '')) }}"
                required placeholder="Masukkan judul TikTok" class="{{ $inputClass }}">
            @error('title')
                <p class="mt-1 text-sm text-rose-600 dark:text-rose-300">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="backup_video_url" class="text-sm font-medium text-slate-700 dark:text-slate-200">Backup Video URL</label>
            <input type="text" name="backup_video_url" id="backup_video_url"
                value="{{ old('backup_video_url', data_get($tiktokData, 'backup_video_url', '')) }}"
                placeholder="/storage/tiktok_backups/filename.mp4" class="{{ $inputClass }}">
            @error('backup_video_url')
                <p class="mt-1 text-sm text-rose-600 dark:text-rose-300">{{ $message }}</p>
            @enderror
        </div>
    </div>
</section>

<section class="rounded-xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-950">
    <div class="border-b border-slate-200 p-5 dark:border-slate-800">
        <h2 class="text-lg font-semibold text-slate-900 dark:text-white">Embed Code</h2>
        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
            Tempel embed code TikTok lengkap lalu cek preview sebelum data disimpan.
        </p>
    </div>

    <div class="grid gap-4 p-5 xl:grid-cols-[minmax(0,1.2fr)_minmax(320px,0.8fr)]">
        <div>
            <label for="embed_code" class="text-sm font-medium text-slate-700 dark:text-slate-200">Embed Code</label>
            <textarea name="embed_code" id="embed_code" rows="14" required placeholder="Tempel embed code TikTok di sini"
                class="{{ $textareaClass }}">{{ $embedPreviewValue }}</textarea>
            @error('embed_code')
                <p class="mt-1 text-sm text-rose-600 dark:text-rose-300">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex flex-col gap-4">
            <div class="rounded-xl border border-slate-200 p-4 dark:border-slate-800">
                <h3 class="text-sm font-semibold text-slate-900 dark:text-white">Preview</h3>
                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                    Preview akan diperbarui otomatis saat embed code diubah.
                </p>

                <div id="embedPreviewEmpty"
                    class="@if (filled($embedPreviewValue)) hidden @endif mt-4 rounded-xl border border-dashed border-slate-300 px-4 py-10 text-center text-sm text-slate-500 dark:border-slate-700 dark:text-slate-400">
                    Tempel embed code untuk melihat preview TikTok.
                </div>

                <iframe id="embedPreviewFrame"
                    class="@if (!filled($embedPreviewValue)) hidden @endif mt-4 h-[540px] w-full rounded-xl border border-slate-200 bg-slate-50 dark:border-slate-800 dark:bg-slate-900"
                    title="Preview TikTok"></iframe>
            </div>

            <div class="rounded-xl border border-slate-200 p-4 dark:border-slate-800">
                <h3 class="text-sm font-semibold text-slate-900 dark:text-white">Catatan Input</h3>
                <ul class="mt-3 space-y-3 text-sm leading-6 text-slate-500 dark:text-slate-400">
                    <li>Gunakan embed code penuh dari TikTok, bukan hanya URL halaman video.</li>
                    <li>Isi backup video bila ingin menyiapkan fallback selain embed utama.</li>
                    <li>Judul sebaiknya singkat dan mudah dicari saat data sudah banyak.</li>
                </ul>
            </div>
        </div>
    </div>
</section>
