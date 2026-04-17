@section('namePage', 'Video Briefing Newsmaker23')

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
                    <h1 class="text-3xl font-bold text-slate-900 dark:text-slate-100">Video Briefing</h1>
                    <p class="text-sm text-slate-500 dark:text-slate-400">
                        Kelola daftar embed Video Briefing untuk modul Newsmaker 23.
                    </p>
                </div>

                <div class="flex flex-wrap gap-3">
                    <a
                        href="{{ route('newsmaker23.index') }}"
                        class="inline-flex items-center justify-center rounded-xl border border-slate-300 px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-800">
                        Kembali
                    </a>
                    <a
                        href="{{ route('newsmaker23.video-briefing.create') }}"
                        class="inline-flex items-center justify-center rounded-xl bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-slate-800 dark:bg-slate-100 dark:text-slate-900 dark:hover:bg-white">
                        <i class="fa-solid fa-plus mr-2"></i>
                        Tambah
                    </a>
                </div>
            </div>
        </section>

        <section class="mt-6 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">
            @if (($videoBriefings ?? collect())->isEmpty())
                <div class="p-6">
                    <p class="text-sm font-semibold text-slate-900 dark:text-slate-100">Belum ada data.</p>
                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Klik tombol Tambah untuk membuat Video Briefing pertama.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full table-auto">
                        <thead class="bg-slate-50 dark:bg-slate-950/50">
                            <tr class="text-left text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">
                                <th class="px-6 py-4">Gambar</th>
                                <th class="px-6 py-4">Judul</th>
                                <th class="px-6 py-4">Backup URL</th>
                                <th class="px-6 py-4">Dibuat</th>
                                <th class="w-px px-6 py-4 text-right whitespace-nowrap">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                            @foreach ($videoBriefings as $videoBriefing)
                                <tr class="align-top">
                                    <td class="px-6 py-4">
                                        @if (filled($videoBriefing->image))
                                            <img src="{{ asset($videoBriefing->image) }}" alt="{{ $videoBriefing->title }}"
                                                class="h-12 w-20 rounded-xl border border-slate-200 object-cover shadow-sm dark:border-slate-800">
                                        @else
                                            <div class="h-12 w-20 rounded-xl border border-dashed border-slate-300 bg-slate-50 dark:border-slate-700 dark:bg-slate-950/40"></div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <p class="text-sm font-semibold text-slate-900 dark:text-slate-100">{{ $videoBriefing->title }}</p>
                                        <p class="mt-2 max-w-xl text-xs text-slate-500 dark:text-slate-400">
                                            {{ \Illuminate\Support\Str::limit(preg_replace('/\\s+/', ' ', trim($videoBriefing->embed_code)), 120) }}
                                        </p>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-slate-700 dark:text-slate-200">
                                        @if (filled($videoBriefing->backup_video_url))
                                            <a href="{{ $videoBriefing->backup_video_url }}" target="_blank" rel="noreferrer"
                                                class="break-all underline transition hover:text-slate-900 dark:hover:text-white">
                                                {{ \Illuminate\Support\Str::limit($videoBriefing->backup_video_url, 45) }}
                                            </a>
                                        @else
                                            <span class="text-slate-500 dark:text-slate-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm whitespace-nowrap text-slate-700 dark:text-slate-200">
                                        {{ optional($videoBriefing->created_at)->format('d M Y H:i') }}
                                    </td>
                                    <td class="w-px px-6 py-4 whitespace-nowrap">
                                        <div class="flex justify-end gap-2">
                                            <a href="{{ route('newsmaker23.video-briefing.edit', $videoBriefing->id) }}"
                                                class="inline-flex items-center justify-center rounded-xl border border-slate-200 px-3 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-50 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-800">
                                                Edit
                                            </a>
                                            <form action="{{ route('newsmaker23.video-briefing.destroy', $videoBriefing->id) }}" method="POST"
                                                onsubmit="return confirm('Yakin hapus Video Briefing ini?')">
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
