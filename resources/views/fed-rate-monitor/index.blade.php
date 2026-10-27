@section('namePage', 'Fed Rate Monitor')

<x-app-layout>
    <div class="w-full px-4 py-8 sm:px-6 lg:px-8">
        @if (session('success'))
            <div class="mb-6 rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm text-emerald-700 shadow-sm dark:border-emerald-500/20 dark:bg-emerald-500/10 dark:text-emerald-200">
                {{ session('success') }}
            </div>
        @endif

        <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <div class="flex flex-wrap items-start justify-between gap-4">
                <div class="space-y-2">
                    <p class="text-sm font-semibold text-slate-500 dark:text-slate-400">Newsmaker23</p>
                    <h1 class="text-3xl font-bold text-slate-900 dark:text-slate-100">Fed Rate Monitor</h1>
                    <p class="text-sm text-slate-500 dark:text-slate-400">
                        Daftar meeting FOMC beserta probabilitas target rate. Data ini otomatis tersedia lewat API untuk frontend.
                    </p>
                </div>

                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('fed-rate-monitor.settings.edit') }}"
                        class="inline-flex items-center justify-center rounded-xl border border-slate-300 px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-800">
                        Pengaturan
                    </a>
                    <a href="{{ route('fed-rate-monitor.create') }}"
                        class="inline-flex items-center justify-center rounded-xl bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-slate-800 dark:bg-slate-100 dark:text-slate-900 dark:hover:bg-white">
                        <i class="fa-solid fa-plus mr-2"></i>
                        Tambah Meeting
                    </a>
                </div>
            </div>
        </section>

        <section class="mt-6 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">
            @if ($meetings->isEmpty())
                <div class="p-6">
                    <p class="text-sm font-semibold text-slate-900 dark:text-slate-100">Belum ada meeting.</p>
                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Klik tombol Tambah Meeting untuk membuat data pertama.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full table-auto">
                        <thead class="bg-slate-50 dark:bg-slate-950/50">
                            <tr class="text-left text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">
                                <th class="px-6 py-4">Tanggal Meeting</th>
                                <th class="px-6 py-4">Jam (ET)</th>
                                <th class="px-6 py-4">Future Price</th>
                                <th class="px-6 py-4">Baris Probabilitas</th>
                                <th class="px-6 py-4">Status</th>
                                <th class="w-px px-6 py-4 text-right whitespace-nowrap">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                            @foreach ($meetings as $meeting)
                                <tr class="align-top">
                                    <td class="px-6 py-4 text-sm font-semibold whitespace-nowrap text-slate-900 dark:text-slate-100">
                                        {{ $meeting->meeting_date->translatedFormat('d F Y') }}
                                    </td>
                                    <td class="px-6 py-4 text-sm whitespace-nowrap text-slate-700 dark:text-slate-200">
                                        {{ \Illuminate\Support\Carbon::parse($meeting->meeting_time)->format('H:i') }} ET
                                    </td>
                                    <td class="px-6 py-4 text-sm text-slate-700 dark:text-slate-200">
                                        {{ $meeting->future_price !== null ? number_format($meeting->future_price, 3) : '-' }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-slate-700 dark:text-slate-200">
                                        {{ $meeting->rows_count }} baris
                                    </td>
                                    <td class="px-6 py-4">
                                        @if ($meeting->is_active)
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
                                            <a href="{{ route('fed-rate-monitor.edit', $meeting->id) }}"
                                                class="inline-flex items-center justify-center rounded-xl border border-slate-200 px-3 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-50 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-800">
                                                Edit
                                            </a>
                                            <form action="{{ route('fed-rate-monitor.destroy', $meeting->id) }}" method="POST"
                                                onsubmit="return confirm('Yakin hapus meeting ini?')">
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
