@section('namePage', 'Pengaturan Fed Rate Monitor')

<x-app-layout>
    <div class="w-full px-4 py-8 sm:px-6 lg:px-8">
        @if (session('success'))
            <div class="mb-6 rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm text-emerald-700 shadow-sm dark:border-emerald-500/20 dark:bg-emerald-500/10 dark:text-emerald-200">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('fed-rate-monitor.settings.update') }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <section class="overflow-hidden rounded-[32px] bg-gradient-to-br from-slate-950 via-slate-900 to-emerald-900 px-6 py-8 shadow-2xl ring-1 ring-white/10 sm:px-8 lg:px-10">
                <div class="flex flex-wrap items-start justify-between gap-6">
                    <div class="space-y-4">
                        <span class="inline-flex items-center rounded-full border border-white/10 bg-white/10 px-3 py-1 text-xs font-semibold uppercase tracking-[0.22em] text-emerald-100">
                            Fed Rate Monitor
                        </span>
                        <div class="space-y-2">
                            <p class="text-xs font-semibold uppercase tracking-[0.32em] text-slate-300">Pengaturan</p>
                            <h1 class="text-3xl font-bold tracking-tight text-white sm:text-4xl">Current Target Rate</h1>
                            <p class="text-sm leading-6 text-emerald-100 sm:text-base">
                                Target fed rate saat ini, disalin dari investing.com/central-banks/fed-rate-monitor atau CME FedWatch.
                            </p>
                        </div>
                    </div>

                    <div class="flex flex-wrap gap-3">
                        <a href="{{ route('fed-rate-monitor.index') }}"
                            class="inline-flex items-center justify-center rounded-xl border border-white/15 bg-white/5 px-5 py-3 text-sm font-semibold text-white transition hover:bg-white/10">
                            Daftar Meeting
                        </a>
                        <button type="submit"
                            class="inline-flex items-center justify-center rounded-xl bg-white px-5 py-3 text-sm font-semibold text-slate-900 transition hover:bg-emerald-50">
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
                <div class="grid grid-cols-1 gap-5 sm:grid-cols-3">
                    <div>
                        <label for="current_target_lower" class="mb-2 block text-sm font-semibold text-slate-900 dark:text-slate-100">Target bawah (%)</label>
                        <input id="current_target_lower" name="current_target_lower" type="number" step="0.01" min="0" max="100"
                            value="{{ old('current_target_lower', $setting?->current_target_lower) }}"
                            class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-slate-900 outline-none transition dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100 @error('current_target_lower') border-rose-500 @enderror"
                            placeholder="3.75" required>
                        @error('current_target_lower')
                            <p class="mt-2 text-sm font-medium text-rose-600 dark:text-rose-300">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="current_target_upper" class="mb-2 block text-sm font-semibold text-slate-900 dark:text-slate-100">Target atas (%)</label>
                        <input id="current_target_upper" name="current_target_upper" type="number" step="0.01" min="0" max="100"
                            value="{{ old('current_target_upper', $setting?->current_target_upper) }}"
                            class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-slate-900 outline-none transition dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100 @error('current_target_upper') border-rose-500 @enderror"
                            placeholder="4.00" required>
                        @error('current_target_upper')
                            <p class="mt-2 text-sm font-medium text-rose-600 dark:text-rose-300">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="source_updated_at" class="mb-2 block text-sm font-semibold text-slate-900 dark:text-slate-100">Updated at (waktu ET)</label>
                        <input id="source_updated_at" name="source_updated_at" type="datetime-local"
                            value="{{ old('source_updated_at', optional($setting?->source_updated_at)->timezone('America/New_York')->format('Y-m-d\TH:i')) }}"
                            class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-slate-900 outline-none transition dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100 @error('source_updated_at') border-rose-500 @enderror">
                        <p class="mt-2 text-xs text-slate-500 dark:text-slate-400">Jam "Updated" yang tertera di sumber data, disimpan sebagai UTC.</p>
                        @error('source_updated_at')
                            <p class="mt-2 text-sm font-medium text-rose-600 dark:text-rose-300">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </section>
        </form>
    </div>
</x-app-layout>
