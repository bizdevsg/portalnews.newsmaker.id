@section('namePage', 'Jadwal Live Newsmaker23')

<x-app-layout>
    <div class="w-full px-4 py-8 sm:px-6 lg:px-8">
        @if (session('success'))
            <div class="mb-6 rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm text-emerald-700 shadow-sm dark:border-emerald-500/20 dark:bg-emerald-500/10 dark:text-emerald-200">
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="mb-6 rounded-2xl border border-rose-200 bg-rose-50 px-5 py-4 text-sm text-rose-700 shadow-sm dark:border-rose-500/20 dark:bg-rose-500/10 dark:text-rose-200">
                {{ session('error') }}
            </div>
        @endif

        <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <div class="flex flex-wrap items-start justify-between gap-4">
                <div class="space-y-2">
                    <p class="text-sm font-semibold text-slate-500 dark:text-slate-400">Newsmaker23</p>
                    <h1 class="text-3xl font-bold text-slate-900 dark:text-slate-100">Jadwal Live</h1>
                    <p class="text-sm text-slate-500 dark:text-slate-400">
                        Kelola jadwal siaran live mingguan Newsmaker 23. Data ini otomatis tersedia lewat API untuk ditampilkan di frontend.
                    </p>
                </div>

                <div class="flex flex-wrap gap-3">
                    <a
                        href="{{ route('newsmaker23.index') }}"
                        class="inline-flex items-center justify-center rounded-xl border border-slate-300 px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-800">
                        Kembali
                    </a>
                    <a
                        href="{{ route('newsmaker23.live-schedule.create') }}"
                        class="inline-flex items-center justify-center rounded-xl bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-slate-800 dark:bg-slate-100 dark:text-slate-900 dark:hover:bg-white">
                        <i class="fa-solid fa-plus mr-2"></i>
                        Tambah
                    </a>
                </div>
            </div>
        </section>

        <section class="mt-6 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">
            @if (($liveSchedules ?? collect())->isEmpty())
                <div class="p-6">
                    <p class="text-sm font-semibold text-slate-900 dark:text-slate-100">Belum ada jadwal.</p>
                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Klik tombol Tambah untuk membuat jadwal live pertama.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full table-auto">
                        <thead class="bg-slate-50 dark:bg-slate-950/50">
                            <tr class="text-left text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">
                                <th class="px-6 py-4">Hari / Jam</th>
                                <th class="px-6 py-4">Judul</th>
                                <th class="px-6 py-4">Host</th>
                                <th class="px-6 py-4">Profile TikTok</th>
                                <th class="px-6 py-4">Status</th>
                                <th class="w-px px-6 py-4 text-right whitespace-nowrap">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                            @foreach ($liveSchedules as $schedule)
                                <tr class="align-top">
                                    <td class="px-6 py-4 text-sm whitespace-nowrap text-slate-700 dark:text-slate-200">
                                        <p class="font-semibold text-slate-900 dark:text-slate-100">{{ $schedule->day }}</p>
                                        <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">
                                            {{ \Illuminate\Support\Carbon::parse($schedule->start_time)->format('H:i') }}
                                            @if ($schedule->end_time)
                                                – {{ \Illuminate\Support\Carbon::parse($schedule->end_time)->format('H:i') }}
                                            @endif
                                            WIB
                                        </p>
                                    </td>
                                    <td class="px-6 py-4">
                                        <p class="text-sm font-semibold text-slate-900 dark:text-slate-100">{{ $schedule->title }}</p>
                                        @if (filled($schedule->description))
                                            <p class="mt-1 max-w-xs text-xs text-slate-500 dark:text-slate-400">
                                                {{ \Illuminate\Support\Str::limit($schedule->description, 90) }}
                                            </p>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm text-slate-700 dark:text-slate-200">
                                        {{ $schedule->host ?: '-' }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-slate-700 dark:text-slate-200">
                                        @if (filled($schedule->tiktok_profile_url))
                                            <a href="{{ $schedule->tiktok_profile_url }}" target="_blank" rel="noreferrer"
                                                class="inline-flex items-center gap-1.5 underline transition hover:text-slate-900 dark:hover:text-white">
                                                <i class="fa-brands fa-tiktok"></i>
                                                Profil TikTok
                                            </a>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        @if ($schedule->is_active)
                                            <span class="inline-flex items-center rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-300">
                                                Aktif
                                            </span>
                                        @else
                                            <span class="inline-flex items-center rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-600 dark:bg-slate-800 dark:text-slate-300">
                                                Nonaktif
                                            </span>
                                        @endif
                                    </td>
                                    <td class="w-px px-6 py-4 whitespace-nowrap">
                                        <div class="flex justify-end gap-2">
                                            <a href="{{ route('newsmaker23.live-schedule.edit', $schedule->id) }}"
                                                class="inline-flex items-center justify-center rounded-xl border border-slate-200 px-3 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-50 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-800">
                                                Edit
                                            </a>
                                            <form action="{{ route('newsmaker23.live-schedule.destroy', $schedule->id) }}" method="POST"
                                                onsubmit="return confirm('Yakin hapus jadwal live ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="inline-flex items-center justify-center rounded-xl bg-rose-600 px-3 py-2 text-sm font-semibold text-white transition hover:bg-rose-700">
                                                    Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </section>
    </div>
</x-app-layout>
