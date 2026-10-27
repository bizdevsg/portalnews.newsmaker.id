@section('namePage', 'Tambah Meeting FOMC')

<x-app-layout>
    <div class="w-full px-4 py-8 sm:px-6 lg:px-8">
        <form action="{{ route('fed-rate-monitor.store') }}" method="POST" class="space-y-6" id="fedRateForm">
            @csrf

            <section class="overflow-hidden rounded-[32px] bg-gradient-to-br from-slate-950 via-slate-900 to-emerald-900 px-6 py-8 shadow-2xl ring-1 ring-white/10 sm:px-8 lg:px-10">
                <div class="flex flex-wrap items-start justify-between gap-6">
                    <div class="space-y-4">
                        <span class="inline-flex items-center rounded-full border border-white/10 bg-white/10 px-3 py-1 text-xs font-semibold uppercase tracking-[0.22em] text-emerald-100">
                            Form Meeting FOMC
                        </span>
                        <div class="space-y-2">
                            <p class="text-xs font-semibold uppercase tracking-[0.32em] text-slate-300">Tambah</p>
                            <h1 class="text-3xl font-bold tracking-tight text-white sm:text-4xl">Buat Meeting Baru</h1>
                            <p class="text-sm leading-6 text-emerald-100 sm:text-base">
                                Tanggal, jam keputusan, future price, dan tabel probabilitas target rate.
                            </p>
                        </div>
                    </div>

                    <div class="flex flex-wrap gap-3">
                        <a href="{{ route('fed-rate-monitor.index') }}"
                            class="inline-flex items-center justify-center rounded-xl border border-white/15 bg-white/5 px-5 py-3 text-sm font-semibold text-white transition hover:bg-white/10">
                            Kembali
                        </a>
                        <button type="submit"
                            class="inline-flex items-center justify-center rounded-xl bg-white px-5 py-3 text-sm font-semibold text-slate-900 transition hover:bg-emerald-50">
                            <i class="fa-solid fa-plus mr-2"></i>
                            Simpan
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
                        <h2 class="mt-2 text-2xl font-bold text-slate-900 dark:text-slate-100">Tanggal &amp; jam keputusan</h2>
                        <p class="mt-2 text-sm leading-6 text-slate-500 dark:text-slate-400">
                            Tanggal keputusan FOMC (hari ke-2 meeting). Jam dalam waktu New York, default 14:00.
                        </p>
                    </div>

                    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 xl:col-span-2">
                        <div>
                            <label for="meeting_date" class="mb-2 block text-sm font-semibold text-slate-900 dark:text-slate-100">Tanggal meeting</label>
                            <input id="meeting_date" name="meeting_date" type="date" value="{{ old('meeting_date') }}"
                                class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-slate-900 outline-none transition dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100 @error('meeting_date') border-rose-500 @enderror"
                                required>
                            @error('meeting_date')
                                <p class="mt-2 text-sm font-medium text-rose-600 dark:text-rose-300">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="meeting_time" class="mb-2 block text-sm font-semibold text-slate-900 dark:text-slate-100">Jam keputusan (ET)</label>
                            <input id="meeting_time" name="meeting_time" type="time" value="{{ old('meeting_time', '14:00') }}"
                                class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-slate-900 outline-none transition dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100 @error('meeting_time') border-rose-500 @enderror"
                                required>
                            @error('meeting_time')
                                <p class="mt-2 text-sm font-medium text-rose-600 dark:text-rose-300">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="future_price" class="mb-2 block text-sm font-semibold text-slate-900 dark:text-slate-100">Future price (opsional)</label>
                            <input id="future_price" name="future_price" type="number" step="0.001" value="{{ old('future_price') }}"
                                class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-slate-900 outline-none transition dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100 @error('future_price') border-rose-500 @enderror"
                                placeholder="96.118">
                            @error('future_price')
                                <p class="mt-2 text-sm font-medium text-rose-600 dark:text-rose-300">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="sort_order" class="mb-2 block text-sm font-semibold text-slate-900 dark:text-slate-100">Urutan (opsional)</label>
                            <input id="sort_order" name="sort_order" type="number" min="0" value="{{ old('sort_order', 0) }}"
                                class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-slate-900 outline-none transition dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100 @error('sort_order') border-rose-500 @enderror">
                            @error('sort_order')
                                <p class="mt-2 text-sm font-medium text-rose-600 dark:text-rose-300">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-end pb-3 sm:col-span-2">
                            <label for="is_active" class="inline-flex items-center gap-2 cursor-pointer">
                                <input type="hidden" name="is_active" value="0">
                                <input id="is_active" name="is_active" type="checkbox" value="1"
                                    class="rounded border-slate-300 text-emerald-600 focus:ring-emerald-500"
                                    {{ old('is_active', true) ? 'checked' : '' }}>
                                <span class="text-sm font-semibold text-slate-900 dark:text-slate-100">Tampilkan di API (aktif)</span>
                            </label>
                        </div>
                    </div>
                </div>
            </section>

            <section class="rounded-[28px] border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900 sm:p-8">
                <div class="space-y-2">
                    <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500 dark:text-slate-400">Paste Cepat</p>
                    <h2 class="text-2xl font-bold text-slate-900 dark:text-slate-100">Tempel tabel dari investing.com</h2>
                    <p class="text-sm leading-6 text-slate-500 dark:text-slate-400">
                        Satu baris = satu rentang target rate. Contoh: <code class="rounded bg-slate-100 px-1.5 py-0.5 dark:bg-slate-800">3.75 - 4.00  89.7%  92.0%  83.9%</code>
                        (lower, upper, current %, previous day %, previous week %). Tanda % dan spasi/tab bebas, boleh diabaikan.
                    </p>
                </div>

                <textarea id="pasteInput" rows="5" placeholder="3.75 - 4.00  89.7%  92.0%  83.9%&#10;4.00 - 4.25  10.3%  8.0%   16.1%"
                    class="mt-4 w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 font-mono text-sm text-slate-900 outline-none transition dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100"></textarea>

                <div class="mt-4 flex flex-wrap gap-3">
                    <button type="button" id="parsePasteButton"
                        class="inline-flex items-center justify-center rounded-xl bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-slate-800 dark:bg-slate-100 dark:text-slate-900 dark:hover:bg-white">
                        <i class="fa-solid fa-wand-magic-sparkles mr-2"></i>
                        Parse &amp; Isi Baris
                    </button>
                    <p id="parseFeedback" class="hidden text-sm font-medium"></p>
                </div>
            </section>

            <section class="rounded-[28px] border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900 sm:p-8">
                <div class="flex flex-wrap items-start justify-between gap-4">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500 dark:text-slate-400">Probabilitas</p>
                        <h2 class="mt-2 text-2xl font-bold text-slate-900 dark:text-slate-100">Tabel target rate</h2>
                    </div>
                    <button type="button" id="addRowButton"
                        class="inline-flex items-center justify-center rounded-xl border border-slate-300 px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-800">
                        <i class="fa-solid fa-plus mr-2"></i>
                        Tambah Baris
                    </button>
                </div>

                <p id="probabilityWarning" class="mt-4 hidden rounded-2xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-700 dark:border-amber-500/20 dark:bg-amber-500/10 dark:text-amber-200"></p>

                <div id="rowsContainer" class="mt-4 space-y-4"></div>

                <template id="rowTemplate">
                    <article class="js-row rounded-2xl border border-slate-200 bg-slate-50 p-4 dark:border-slate-800 dark:bg-slate-950">
                        <div class="grid grid-cols-2 gap-3 sm:grid-cols-5">
                            <div>
                                <label class="mb-1 block text-xs font-semibold text-slate-500 dark:text-slate-400">Lower (%)</label>
                                <input type="number" step="0.01" min="0" max="100" class="js-target-lower w-full rounded-xl border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 outline-none dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100" required>
                            </div>
                            <div>
                                <label class="mb-1 block text-xs font-semibold text-slate-500 dark:text-slate-400">Upper (%)</label>
                                <input type="number" step="0.01" min="0" max="100" class="js-target-upper w-full rounded-xl border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 outline-none dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100" required>
                            </div>
                            <div>
                                <label class="mb-1 block text-xs font-semibold text-slate-500 dark:text-slate-400">Current %</label>
                                <input type="number" step="0.01" min="0" max="100" class="js-current w-full rounded-xl border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 outline-none dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100" required>
                            </div>
                            <div>
                                <label class="mb-1 block text-xs font-semibold text-slate-500 dark:text-slate-400">Prev Day %</label>
                                <input type="number" step="0.01" min="0" max="100" class="js-prev-day w-full rounded-xl border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 outline-none dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100">
                            </div>
                            <div class="flex items-end gap-2">
                                <div class="flex-1">
                                    <label class="mb-1 block text-xs font-semibold text-slate-500 dark:text-slate-400">Prev Week %</label>
                                    <input type="number" step="0.01" min="0" max="100" class="js-prev-week w-full rounded-xl border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 outline-none dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100">
                                </div>
                                <button type="button" class="js-remove-row inline-flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-rose-600 text-white transition hover:bg-rose-700">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </article>
                </template>
            </section>
        </form>
    </div>

    <script type="application/json" id="initialRowsData">[]</script>
    @include('fed-rate-monitor.partials.repeater-script')
</x-app-layout>
