@section('namePage', 'TikTok')

@php
    $formatDate = function ($date) {
        if (!filled($date)) {
            return '-';
        }

        return \Carbon\Carbon::parse($date)->format('d M Y H:i');
    };
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
                        Hanya 1 video TikTok yang aktif di API. Upload video baru akan langsung menggantikan yang
                        lama - tidak ada riwayat upload.
                    </p>
                </div>

                <a href="{{ route('tiktok.edit') }}"
                    class="inline-flex items-center justify-center gap-2 rounded-xl bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-slate-800 dark:bg-slate-100 dark:text-slate-900 dark:hover:bg-white">
                    <i class="fa-solid fa-{{ $tiktok ? 'rotate' : 'upload' }}"></i>
                    {{ $tiktok ? 'Ganti Video' : 'Upload Video' }}
                </a>
            </div>
        </section>

        <section class="rounded-xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-950">
            @if (!$tiktok)
                <div class="p-6 text-center sm:p-10">
                    <div
                        class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-slate-100 text-slate-500 dark:bg-slate-800 dark:text-slate-300">
                        <i class="fa-brands fa-tiktok"></i>
                    </div>
                    <h3 class="mt-4 text-lg font-semibold text-slate-900 dark:text-white">Belum ada video TikTok</h3>
                    <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">
                        Upload video pertama supaya tersedia lewat API.
                    </p>
                </div>
            @else
                <div class="grid gap-6 p-5 lg:grid-cols-[minmax(0,420px)_1fr]">
                    <video controls class="w-full rounded-xl bg-black" preload="metadata">
                        <source src="{{ asset($tiktok->video) }}">
                    </video>

                    <div class="flex flex-col gap-4">
                        <div>
                            <p class="text-xs font-medium uppercase tracking-wide text-slate-400">Caption</p>
                            <p class="mt-1 text-base font-semibold text-slate-900 dark:text-white">
                                {{ $tiktok->title ?: '-' }}
                            </p>
                        </div>
                        <div>
                            <p class="text-xs font-medium uppercase tracking-wide text-slate-400">Terakhir diperbarui</p>
                            <p class="mt-1 text-sm text-slate-700 dark:text-slate-200">
                                {{ $formatDate($tiktok->updated_at) }}
                            </p>
                        </div>

                        <div class="mt-auto flex flex-wrap gap-3 pt-4">
                            <a href="{{ route('tiktok.edit') }}"
                                class="inline-flex items-center justify-center rounded-xl border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-50 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-900">
                                Ganti Video
                            </a>
                            <button type="button" onclick="openDeleteModal()"
                                class="inline-flex items-center justify-center rounded-xl bg-rose-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-rose-700">
                                Hapus
                            </button>
                        </div>
                    </div>
                </div>
            @endif
        </section>
    </div>

    <div id="deleteModal" data-modal
        class="fixed inset-0 z-50 hidden flex items-center justify-center bg-slate-950/50 px-4">
        <div
            class="w-full max-w-md rounded-2xl border border-slate-200 bg-white p-6 shadow-xl dark:border-slate-800 dark:bg-slate-950">
            <h2 class="text-lg font-semibold text-slate-900 dark:text-white">Hapus video TikTok?</h2>
            <p class="mt-2 text-sm leading-6 text-slate-500 dark:text-slate-400">
                Video akan dihapus permanen dan API TikTok akan kosong sampai ada video baru diunggah.
            </p>
            <div class="mt-6 flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
                <button type="button" onclick="closeDeleteModal()"
                    class="inline-flex items-center justify-center rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-900">
                    Batal
                </button>
                <form action="{{ route('tiktok.destroy') }}" method="POST">
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
        function openDeleteModal() {
            document.getElementById('deleteModal')?.classList.remove('hidden');
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
