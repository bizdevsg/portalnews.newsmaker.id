@section('namePage', 'TikTok')

@php
    $formatDate = function ($date) {
        if (!filled($date)) {
            return '-';
        }

        return \Carbon\Carbon::parse($date)->format('d M Y H:i');
    };

    $totalItems = $tiktoks->count();
    $backupCount = $tiktoks->filter(fn($item) => filled($item->backup_video_url))->count();
    $withoutBackupCount = $totalItems - $backupCount;
    $latestItem = $tiktoks->first();
    $deleteRouteTemplate = route('tiktok.destroy', ['id' => '__ID__']);
@endphp

<x-app-layout>
    <div class="mx-auto flex w-full flex-col gap-6 px-4 py-8 sm:px-6 lg:px-8">
        @if (session('success'))
            <div
                class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700 dark:border-emerald-500/20 dark:bg-emerald-500/10 dark:text-emerald-200">
                {{ session('success') }}
            </div>
        @endif

        <section
            class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-950">
            <div class="flex flex-col gap-4 md:flex-row md:items-start md:justify-between">
                <div class="min-w-0">
                    <h1 class="text-2xl font-semibold text-slate-900 dark:text-white sm:text-3xl">
                        TikTok
                    </h1>
                    <p class="mt-2 max-w-2xl text-sm text-slate-500 dark:text-slate-400">
                        Kelola embed TikTok dan video backup dengan tampilan yang lebih rapi, ringan, dan responsif.
                    </p>

                    <div class="mt-4 flex flex-wrap gap-2 text-xs font-medium text-slate-600 dark:text-slate-300">
                        <span class="rounded-full border border-slate-200 px-3 py-1.5 dark:border-slate-700">
                            Total embed {{ $totalItems }}
                        </span>
                        <span class="rounded-full border border-slate-200 px-3 py-1.5 dark:border-slate-700">
                            Update terakhir {{ $formatDate(optional($latestItem)->created_at) }}
                        </span>
                    </div>
                </div>

                <a href="{{ route('tiktok.create') }}"
                    class="inline-flex items-center justify-center gap-2 rounded-xl bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-slate-800 dark:bg-slate-100 dark:text-slate-900 dark:hover:bg-white">
                    <i class="fa-solid fa-plus"></i>
                    Tambah TikTok
                </a>
            </div>
        </section>

        <section class="grid gap-3 sm:grid-cols-2 xl:grid-cols-4">
            <div
                class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm dark:border-slate-800 dark:bg-slate-950">
                <p class="text-sm text-slate-500 dark:text-slate-400">Total Data</p>
                <p class="mt-2 text-2xl font-semibold text-slate-900 dark:text-white">{{ number_format($totalItems) }}
                </p>
            </div>
            <div
                class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm dark:border-slate-800 dark:bg-slate-950">
                <p class="text-sm text-slate-500 dark:text-slate-400">Backup Tersedia</p>
                <p class="mt-2 text-2xl font-semibold text-slate-900 dark:text-white">{{ number_format($backupCount) }}
                </p>
            </div>
            <div
                class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm dark:border-slate-800 dark:bg-slate-950">
                <p class="text-sm text-slate-500 dark:text-slate-400">Tanpa Backup</p>
                <p class="mt-2 text-2xl font-semibold text-slate-900 dark:text-white">
                    {{ number_format($withoutBackupCount) }}</p>
            </div>
            <div
                class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm dark:border-slate-800 dark:bg-slate-950">
                <p class="text-sm text-slate-500 dark:text-slate-400">Data Terbaru</p>
                <p class="mt-2 text-base font-semibold text-slate-900 dark:text-white">
                    {{ optional($latestItem)->title ?: '-' }}
                </p>
            </div>
        </section>

        <section class="rounded-xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-950">
            <div
                class="flex flex-col gap-3 border-b border-slate-200 p-5 dark:border-slate-800 md:flex-row md:items-center md:justify-between">
                <div>
                    <h2 class="text-lg font-semibold text-slate-900 dark:text-white">Daftar TikTok</h2>
                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                        Ringkasan embed, backup video, dan waktu pembuatan.
                    </p>
                </div>

                <div class="flex flex-wrap gap-2 text-xs font-medium">
                    <span
                        class="rounded-full bg-slate-100 px-3 py-1.5 text-slate-700 dark:bg-slate-800 dark:text-slate-200">
                        Total {{ $totalItems }}
                    </span>
                    <span
                        class="rounded-full bg-emerald-50 px-3 py-1.5 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-200">
                        Backup {{ $backupCount }}
                    </span>
                </div>
            </div>

            @if ($totalItems === 0)
                <div class="p-6 text-center sm:p-10">
                    <div
                        class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-slate-100 text-slate-500 dark:bg-slate-800 dark:text-slate-300">
                        <i class="fa-brands fa-tiktok"></i>
                    </div>
                    <h3 class="mt-4 text-lg font-semibold text-slate-900 dark:text-white">Belum ada data TikTok</h3>
                    <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">
                        Tambahkan embed pertama untuk mulai mengelola konten TikTok.
                    </p>
                </div>
            @else
                <div class="grid gap-4 p-4 lg:hidden">
                    @foreach ($tiktoks as $tiktok)
                        <article class="rounded-2xl border border-slate-200 p-4 dark:border-slate-800">
                            <div class="flex items-start justify-between gap-3">
                                <div class="min-w-0">
                                    <h3 class="text-base font-semibold text-slate-900 dark:text-white">
                                        {{ $tiktok->title }}
                                    </h3>
                                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                                        Dibuat {{ $formatDate($tiktok->created_at) }}
                                    </p>
                                </div>
                                <span
                                    class="rounded-full px-3 py-1 text-xs font-semibold {{ filled($tiktok->backup_video_url)
                                        ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-200'
                                        : 'bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-200' }}">
                                    {{ filled($tiktok->backup_video_url) ? 'Backup Ada' : 'Tanpa Backup' }}
                                </span>
                            </div>

                            <div class="mt-4 rounded-xl bg-slate-50 p-4 dark:bg-slate-900">
                                <p class="text-xs font-medium uppercase tracking-wide text-slate-400">Embed Code</p>
                                <p class="mt-2 break-all text-sm leading-6 text-slate-700 dark:text-slate-200">
                                    {{ \Illuminate\Support\Str::limit(preg_replace('/\s+/', ' ', trim($tiktok->embed_code)), 140) }}
                                </p>
                            </div>

                            <div class="mt-4 rounded-xl bg-slate-50 p-4 dark:bg-slate-900">
                                <p class="text-xs font-medium uppercase tracking-wide text-slate-400">Backup Video</p>
                                @if (filled($tiktok->backup_video_url))
                                    <a href="{{ $tiktok->backup_video_url }}" target="_blank" rel="noreferrer"
                                        class="mt-2 inline-flex break-all text-sm text-slate-700 underline transition hover:text-slate-900 dark:text-slate-200 dark:hover:text-white">
                                        {{ \Illuminate\Support\Str::limit($tiktok->backup_video_url, 60) }}
                                    </a>
                                @else
                                    <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">Belum ada backup video.
                                    </p>
                                @endif
                            </div>

                            <div class="mt-4 flex justify-end gap-3">
                                <a href="{{ route('tiktok.show', $tiktok->id) }}" target="_blank" rel="noreferrer"
                                    class="inline-flex items-center justify-center rounded-xl border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-50 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-900">
                                    Preview
                                </a>
                                <a href="{{ route('tiktok.edit', $tiktok->id) }}"
                                    class="inline-flex items-center justify-center rounded-xl border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-50 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-900">
                                    Edit
                                </a>
                                <button type="button"
                                    onclick="openDeleteModal({{ $tiktok->id }}, @js($tiktok->title))"
                                    class="inline-flex items-center justify-center rounded-xl bg-rose-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-rose-700">
                                    Hapus
                                </button>
                            </div>
                        </article>
                    @endforeach
                </div>

                <div class="hidden overflow-x-auto lg:block">
                    <table class="w-full table-auto">
                        <thead class="bg-slate-50 dark:bg-slate-900">
                            <tr
                                class="text-left text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">
                                <th class="px-5 py-4">Judul</th>
                                <th class="px-5 py-4">Backup Video</th>
                                <th class="px-5 py-4">Preview Kode</th>
                                <th class="px-5 py-4 whitespace-nowrap">Dibuat</th>
                                <th class="w-px px-4 py-4 text-right whitespace-nowrap">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                            @foreach ($tiktoks as $tiktok)
                                <tr class="align-top">
                                    <td class="px-5 py-4">
                                        <p class="text-sm font-semibold text-slate-900 dark:text-white">
                                            {{ $tiktok->title }}</p>
                                    </td>
                                    <td class="px-5 py-4 text-sm text-slate-700 dark:text-slate-200">
                                        @if (filled($tiktok->backup_video_url))
                                            <a href="{{ $tiktok->backup_video_url }}" target="_blank" rel="noreferrer"
                                                class="break-all underline transition hover:text-slate-900 dark:hover:text-white">
                                                {{ \Illuminate\Support\Str::limit($tiktok->backup_video_url, 45) }}
                                            </a>
                                        @else
                                            <span class="text-slate-500 dark:text-slate-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-5 py-4 text-sm text-slate-700 dark:text-slate-200">
                                        <div class="max-w-md break-all text-sm leading-6">
                                            {{ \Illuminate\Support\Str::limit(preg_replace('/\s+/', ' ', trim($tiktok->embed_code)), 120) }}
                                        </div>
                                    </td>
                                    <td class="px-5 py-4 text-sm whitespace-nowrap text-slate-700 dark:text-slate-200">
                                        {{ $formatDate($tiktok->created_at) }}
                                    </td>
                                    <td class="w-px px-4 py-4 whitespace-nowrap">
                                        <div class="flex justify-end gap-2">
                                            <a href="{{ route('tiktok.show', $tiktok->id) }}" rel="noreferrer"
                                                class="inline-flex items-center justify-center rounded-lg border border-slate-200 px-3 py-1.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-900">
                                                Preview
                                            </a>
                                            <a href="{{ route('tiktok.edit', $tiktok->id) }}"
                                                class="inline-flex items-center justify-center rounded-lg border border-slate-200 px-3 py-1.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-900">
                                                Edit
                                            </a>
                                            <button type="button"
                                                onclick="openDeleteModal({{ $tiktok->id }}, @js($tiktok->title))"
                                                class="inline-flex items-center justify-center rounded-lg bg-rose-600 px-3 py-1.5 text-sm font-semibold text-white transition hover:bg-rose-700">
                                                Hapus
                                            </button>
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

    <div id="deleteModal" data-modal
        class="fixed inset-0 z-50 hidden flex items-center justify-center bg-slate-950/50 px-4">
        <div
            class="w-full max-w-md rounded-2xl border border-slate-200 bg-white p-6 shadow-xl dark:border-slate-800 dark:bg-slate-950">
            <h2 class="text-lg font-semibold text-slate-900 dark:text-white">Hapus data TikTok?</h2>
            <p id="deleteModalText" class="mt-2 text-sm leading-6 text-slate-500 dark:text-slate-400">
                Data yang dipilih akan dihapus permanen.
            </p>
            <div class="mt-6 flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
                <button type="button" onclick="closeDeleteModal()"
                    class="inline-flex items-center justify-center rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-900">
                    Batal
                </button>
                <form id="deleteForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="inline-flex w-full items-center justify-center rounded-xl bg-rose-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-rose-700">
                        Ya, hapus
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        const deleteRouteTemplate = @json($deleteRouteTemplate);

        function openDeleteModal(id, title) {
            const deleteForm = document.getElementById('deleteForm');
            const deleteModal = document.getElementById('deleteModal');
            const deleteModalText = document.getElementById('deleteModalText');

            if (deleteForm) {
                deleteForm.action = deleteRouteTemplate.replace('__ID__', id);
            }

            if (deleteModalText) {
                deleteModalText.textContent = `Data "${title}" akan dihapus permanen.`;
            }

            deleteModal?.classList.remove('hidden');
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal')?.classList.add('hidden');
        }

        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeDeleteModal();
            }
        });

        document.getElementById('deleteModal')?.addEventListener('click', function(event) {
            if (event.target === event.currentTarget) {
                closeDeleteModal();
            }
        });
    </script>
</x-app-layout>
