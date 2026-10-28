@section('namePage', 'Edit Jadwal Live Newsmaker23')

<x-app-layout>
    <div class="w-full px-4 py-8 sm:px-6 lg:px-8">
        @if (session('error'))
            <div class="mb-6 rounded-2xl border border-rose-200 bg-rose-50 px-5 py-4 text-sm text-rose-700 shadow-sm dark:border-rose-500/20 dark:bg-rose-500/10 dark:text-rose-200">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('newsmaker23.live-schedule.update', $liveSchedule->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <section class="overflow-hidden rounded-[32px] bg-gradient-to-br from-slate-950 via-slate-900 to-indigo-900 px-6 py-8 shadow-2xl ring-1 ring-white/10 sm:px-8 lg:px-10">
                <div class="flex flex-wrap items-start justify-between gap-6">
                    <div class="space-y-4">
                        <span class="inline-flex items-center rounded-full border border-white/10 bg-white/10 px-3 py-1 text-xs font-semibold uppercase tracking-[0.22em] text-indigo-100">
                            Form Jadwal Live
                        </span>
                        <div class="space-y-2">
                            <p class="text-xs font-semibold uppercase tracking-[0.32em] text-slate-300">Edit</p>
                            <h1 class="text-3xl font-bold tracking-tight text-white sm:text-4xl">Perbarui Jadwal Live</h1>
                            <p class="text-sm leading-6 text-indigo-100 sm:text-base">
                                Perbarui hari, jam, dan detail siaran. API akan otomatis ikut terbarui.
                            </p>
                        </div>
                    </div>

                    <div class="flex flex-wrap gap-3">
                        <a href="{{ route('newsmaker23.live-schedule.index') }}"
                            class="inline-flex items-center justify-center rounded-xl border border-white/15 bg-white/5 px-5 py-3 text-sm font-semibold text-white transition hover:bg-white/10">
                            Kembali
                        </a>
                        <button type="submit"
                            class="inline-flex items-center justify-center rounded-xl bg-white px-5 py-3 text-sm font-semibold text-slate-900 transition hover:bg-indigo-50">
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
                        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500 dark:text-slate-400">Jadwal</p>
                        <h2 class="mt-2 text-2xl font-bold text-slate-900 dark:text-slate-100">Hari &amp; waktu siaran</h2>
                        <p class="mt-2 text-sm leading-6 text-slate-500 dark:text-slate-400">
                            Hari dan jam mulai wajib diisi. Jadwal diurutkan otomatis dari Senin sampai Minggu.
                        </p>
                    </div>

                    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 xl:col-span-2">
                        <div>
                            <label for="day" class="mb-2 block text-sm font-semibold text-slate-900 dark:text-slate-100">Hari</label>
                            <select id="day" name="day"
                                class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-slate-900 outline-none transition dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100 @error('day') border-rose-500 @enderror"
                                required>
                                <option value="">Pilih hari</option>
                                @foreach (\App\Models\NewsmakerLiveSchedule::DAYS as $day)
                                    <option value="{{ $day }}" @selected(old('day', $liveSchedule->day) === $day)>{{ $day }}</option>
                                @endforeach
                            </select>
                            @error('day')
                                <p class="mt-2 text-sm font-medium text-rose-600 dark:text-rose-300">{{ $message }}</p>
                            @enderror
                        </div>

                        <div></div>

                        <div>
                            <label for="start_time" class="mb-2 block text-sm font-semibold text-slate-900 dark:text-slate-100">Jam mulai</label>
                            <input id="start_time" name="start_time" type="time"
                                value="{{ old('start_time', $liveSchedule->start_time ? \Illuminate\Support\Carbon::parse($liveSchedule->start_time)->format('H:i') : '') }}"
                                class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-slate-900 outline-none transition dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100 @error('start_time') border-rose-500 @enderror"
                                required>
                            @error('start_time')
                                <p class="mt-2 text-sm font-medium text-rose-600 dark:text-rose-300">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="end_time" class="mb-2 block text-sm font-semibold text-slate-900 dark:text-slate-100">Jam selesai (opsional)</label>
                            <input id="end_time" name="end_time" type="time"
                                value="{{ old('end_time', $liveSchedule->end_time ? \Illuminate\Support\Carbon::parse($liveSchedule->end_time)->format('H:i') : '') }}"
                                class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-slate-900 outline-none transition dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100 @error('end_time') border-rose-500 @enderror">
                            @error('end_time')
                                <p class="mt-2 text-sm font-medium text-rose-600 dark:text-rose-300">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </section>

            <section class="rounded-[28px] border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900 sm:p-8">
                <div class="grid grid-cols-1 gap-6 xl:grid-cols-3">
                    <div class="xl:col-span-1">
                        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500 dark:text-slate-400">Konten</p>
                        <h2 class="mt-2 text-2xl font-bold text-slate-900 dark:text-slate-100">Detail siaran</h2>
                        <p class="mt-2 text-sm leading-6 text-slate-500 dark:text-slate-400">
                            Judul wajib diisi. Field lain bersifat opsional, akan tetap dikirim ke API.
                        </p>
                    </div>

                    <div class="space-y-5 xl:col-span-2">
                        <div>
                            <label for="title" class="mb-2 block text-sm font-semibold text-slate-900 dark:text-slate-100">Judul</label>
                            <input id="title" name="title" type="text" value="{{ old('title', $liveSchedule->title) }}"
                                class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-slate-900 outline-none transition dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100 @error('title') border-rose-500 @enderror"
                                required>
                            @error('title')
                                <p class="mt-2 text-sm font-medium text-rose-600 dark:text-rose-300">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                            <div>
                                <label for="host" class="mb-2 block text-sm font-semibold text-slate-900 dark:text-slate-100">Host / Narator (opsional)</label>
                                <input id="host" name="host" type="text" value="{{ old('host', $liveSchedule->host) }}"
                                    class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-slate-900 outline-none transition dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100 @error('host') border-rose-500 @enderror">
                                @error('host')
                                    <p class="mt-2 text-sm font-medium text-rose-600 dark:text-rose-300">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="tiktok_profile_url" class="mb-2 block text-sm font-semibold text-slate-900 dark:text-slate-100">Link Profile TikTok (opsional)</label>
                                <input id="tiktok_profile_url" name="tiktok_profile_url" type="url" value="{{ old('tiktok_profile_url', $liveSchedule->tiktok_profile_url) }}"
                                    class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-slate-900 outline-none transition dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100 @error('tiktok_profile_url') border-rose-500 @enderror"
                                    placeholder="https://www.tiktok.com/@username">
                                @error('tiktok_profile_url')
                                    <p class="mt-2 text-sm font-medium text-rose-600 dark:text-rose-300">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label for="link_url" class="mb-2 block text-sm font-semibold text-slate-900 dark:text-slate-100">Link siaran (opsional)</label>
                            <input id="link_url" name="link_url" type="url" value="{{ old('link_url', $liveSchedule->link_url) }}"
                                class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-slate-900 outline-none transition dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100 @error('link_url') border-rose-500 @enderror"
                                placeholder="https://...">
                            @error('link_url')
                                <p class="mt-2 text-sm font-medium text-rose-600 dark:text-rose-300">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="description" class="mb-2 block text-sm font-semibold text-slate-900 dark:text-slate-100">Deskripsi (opsional)</label>
                            <textarea id="description" name="description" rows="4"
                                class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-slate-900 outline-none transition dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100 @error('description') border-rose-500 @enderror">{{ old('description', $liveSchedule->description) }}</textarea>
                            @error('description')
                                <p class="mt-2 text-sm font-medium text-rose-600 dark:text-rose-300">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="image" class="mb-2 block text-sm font-semibold text-slate-900 dark:text-slate-100">Gambar / Thumbnail (opsional)</label>
                            <input id="image" name="image" type="file" accept="image/*" data-preview="image-preview"
                                class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-slate-900 outline-none transition file:mr-4 file:rounded-xl file:border-0 file:bg-slate-900 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-white hover:file:bg-slate-800 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100 @error('image') border-rose-500 @enderror">
                            <img id="image-preview" @if ($liveSchedule->image) src="{{ asset($liveSchedule->image) }}" @endif
                                alt="{{ $liveSchedule->title }}"
                                class="mt-4 h-40 w-full rounded-2xl object-cover @unless ($liveSchedule->image) hidden @endunless">
                            @error('image')
                                <p class="mt-2 text-sm font-medium text-rose-600 dark:text-rose-300">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                            <div>
                                <label for="sort_order" class="mb-2 block text-sm font-semibold text-slate-900 dark:text-slate-100">Urutan dalam hari (opsional)</label>
                                <input id="sort_order" name="sort_order" type="number" min="0" value="{{ old('sort_order', $liveSchedule->sort_order) }}"
                                    class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-slate-900 outline-none transition dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100 @error('sort_order') border-rose-500 @enderror">
                                @error('sort_order')
                                    <p class="mt-2 text-sm font-medium text-rose-600 dark:text-rose-300">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="flex items-end pb-3">
                                <label for="is_active" class="inline-flex items-center gap-2 cursor-pointer">
                                    <input type="hidden" name="is_active" value="0">
                                    <input id="is_active" name="is_active" type="checkbox" value="1"
                                        class="rounded border-slate-300 text-blue-600 focus:ring-blue-500"
                                        {{ old('is_active', $liveSchedule->is_active) ? 'checked' : '' }}>
                                    <span class="text-sm font-semibold text-slate-900 dark:text-slate-100">Tampilkan di API (aktif)</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </form>
    </div>

    @include('partials.image-size-guard')
</x-app-layout>
