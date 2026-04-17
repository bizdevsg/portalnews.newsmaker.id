@section('namePage', 'Edit Video Briefing Newsmaker23')

<x-app-layout>
    <div class="w-full px-4 py-8 sm:px-6 lg:px-8">
        @if (session('error'))
            <div class="mb-6 rounded-2xl border border-rose-200 bg-rose-50 px-5 py-4 text-sm text-rose-700 shadow-sm dark:border-rose-500/20 dark:bg-rose-500/10 dark:text-rose-200">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('newsmaker23.video-briefing.update', $videoBriefing->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <section class="overflow-hidden rounded-[32px] bg-gradient-to-br from-slate-950 via-slate-900 to-blue-900 px-6 py-8 shadow-2xl ring-1 ring-white/10 sm:px-8 lg:px-10">
                <div class="flex flex-wrap items-start justify-between gap-6">
                    <div class="space-y-4">
                        <span class="inline-flex items-center rounded-full border border-white/10 bg-white/10 px-3 py-1 text-xs font-semibold uppercase tracking-[0.22em] text-blue-100">
                            Form Video Briefing
                        </span>
                        <div class="space-y-2">
                            <p class="text-xs font-semibold uppercase tracking-[0.32em] text-slate-300">Edit</p>
                            <h1 class="text-3xl font-bold tracking-tight text-white sm:text-4xl">Perbarui Video Briefing</h1>
                            <p class="text-sm leading-6 text-blue-100 sm:text-base">
                                Ubah judul, embed code, atau backup URL sesuai kebutuhan.
                            </p>
                        </div>
                    </div>

                    <div class="flex flex-wrap gap-3">
                        <a href="{{ route('newsmaker23.video-briefing.index') }}"
                            class="inline-flex items-center justify-center rounded-xl border border-white/15 bg-white/5 px-5 py-3 text-sm font-semibold text-white transition hover:bg-white/10">
                            Kembali
                        </a>
                        <button type="submit"
                            class="inline-flex items-center justify-center rounded-xl bg-white px-5 py-3 text-sm font-semibold text-slate-900 transition hover:bg-blue-50">
                            <i class="fa-solid fa-floppy-disk mr-2"></i>
                            Simpan Perubahan
                        </button>
                    </div>
                </div>
            </section>

            @if ($errors->any())
                <div class="rounded-2xl border border-rose-200 bg-rose-50 px-5 py-4 text-sm text-rose-700 shadow-sm dark:border-rose-500/20 dark:bg-rose-500/10 dark:text-rose-200">
                    <p class="font-semibold">Periksa kembali form sebelum menyimpan.</p>
                    <ul class="mt-2 list-disc space-y-1 pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <section class="rounded-[28px] border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900 sm:p-8">
                <div class="grid grid-cols-1 gap-6 xl:grid-cols-3">
                    <div class="xl:col-span-1">
                        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500 dark:text-slate-400">Konten</p>
                        <h2 class="mt-2 text-2xl font-bold text-slate-900 dark:text-slate-100">Informasi video</h2>
                        <p class="mt-2 text-sm leading-6 text-slate-500 dark:text-slate-400">
                            Embed code wajib diisi. Backup URL bersifat opsional.
                        </p>
                    </div>

                    <div class="space-y-5 xl:col-span-2">
                        <div>
                            <label for="title" class="mb-2 block text-sm font-semibold text-slate-900 dark:text-slate-100">Judul</label>
                            <input id="title" name="title" type="text" value="{{ old('title', $videoBriefing->title) }}"
                                class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-slate-900 outline-none transition dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100 @error('title') border-rose-500 @enderror"
                                required>
                            @error('title')
                                <p class="mt-2 text-sm font-medium text-rose-600 dark:text-rose-300">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="embed_code" class="mb-2 block text-sm font-semibold text-slate-900 dark:text-slate-100">Embed code</label>
                            <textarea id="embed_code" name="embed_code" rows="8"
                                class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-slate-900 outline-none transition dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100 @error('embed_code') border-rose-500 @enderror"
                                placeholder="Tempel embed code di sini..." required>{{ old('embed_code', $videoBriefing->embed_code) }}</textarea>
                            @error('embed_code')
                                <p class="mt-2 text-sm font-medium text-rose-600 dark:text-rose-300">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="backup_video_url" class="mb-2 block text-sm font-semibold text-slate-900 dark:text-slate-100">Backup video URL (opsional)</label>
                            <input id="backup_video_url" name="backup_video_url" type="url"
                                value="{{ old('backup_video_url', $videoBriefing->backup_video_url) }}"
                                class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-slate-900 outline-none transition dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100 @error('backup_video_url') border-rose-500 @enderror"
                                placeholder="https://...">
                            @error('backup_video_url')
                                <p class="mt-2 text-sm font-medium text-rose-600 dark:text-rose-300">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="image" class="mb-2 block text-sm font-semibold text-slate-900 dark:text-slate-100">Gambar (opsional)</label>
                            <input id="image" name="image" type="file" accept="image/*"
                                class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-slate-900 outline-none transition file:mr-4 file:rounded-xl file:border-0 file:bg-slate-900 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-white hover:file:bg-slate-800 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100 @error('image') border-rose-500 @enderror">
                            @error('image')
                                <p class="mt-2 text-sm font-medium text-rose-600 dark:text-rose-300">{{ $message }}</p>
                            @enderror

                            @if (filled($videoBriefing->image))
                                <div class="mt-4">
                                    <p class="text-sm font-semibold text-slate-900 dark:text-slate-100">Gambar saat ini</p>
                                    <img src="{{ asset($videoBriefing->image) }}" alt="{{ $videoBriefing->title }}"
                                        class="mt-2 h-28 w-auto rounded-2xl border border-slate-200 object-cover shadow-sm dark:border-slate-800">
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </section>
        </form>
    </div>
</x-app-layout>
