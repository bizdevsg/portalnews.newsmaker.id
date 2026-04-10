@section('namePage', 'Iklan')

@php
    $formatDate = function ($date) {
        if (!filled($date)) {
            return '-';
        }

        return \Illuminate\Support\Carbon::parse($date)->format('d M Y H:i');
    };

    $totalItems = $iklans->count();
    $activeCount = $iklans->where('is_active', true)->count();
    $withImageCount = $iklans->filter(fn($item) => filled($item->image))->count();
    $customHtmlCount = $iklans->filter(fn($item) => filled($item->modal_html))->count();
    $scheduledCount = $iklans->filter(fn($item) => filled($item->start_at) || filled($item->end_at))->count();
    $deleteRouteTemplate = route('iklan.destroy', ['id' => '__ID__']);
    $formatHtmlSummary = function (?string $html) {
        if (!filled($html)) {
            return 'Markup modal akan dirender dari HTML kustom.';
        }

        $cleanHtml = preg_replace('/<(script|style)\b[^>]*>.*?<\/\1>/is', ' ', $html) ?? $html;
        $cleanText = trim(preg_replace('/\s+/', ' ', strip_tags($cleanHtml)) ?? '');

        return $cleanText !== '' ? \Illuminate\Support\Str::limit($cleanText, 100) : 'Markup modal akan dirender dari HTML kustom.';
    };
@endphp

<x-app-layout>
    <div class="mx-auto flex w-full flex-col gap-6 px-4 py-8 sm:px-6 lg:px-8">
        @if (session('success'))
            <div data-flash-alert
                class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700 dark:border-emerald-500/20 dark:bg-emerald-500/10 dark:text-emerald-200">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div data-flash-alert
                class="rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700 dark:border-rose-500/20 dark:bg-rose-500/10 dark:text-rose-200">
                {{ session('error') }}
            </div>
        @endif

        <section
            class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-950">
            <div class="flex flex-col gap-4 md:flex-row md:items-start md:justify-between">
                <div class="min-w-0">
                    <p class="text-sm font-semibold text-slate-500 dark:text-slate-400">Newsmaker23</p>
                    <h1 class="mt-2 text-2xl font-semibold text-slate-900 dark:text-white sm:text-3xl">
                        Iklan
                    </h1>
                    <p class="mt-2 max-w-2xl text-sm text-slate-500 dark:text-slate-400">
                        Kelola iklan untuk promosi aplikasi, campaign, pengumuman, dan CTA (format input mengikuti Popup Banner).
                    </p>

                    <div class="mt-4 flex flex-wrap gap-2 text-xs font-medium text-slate-600 dark:text-slate-300">
                        <span class="rounded-full border border-slate-200 px-3 py-1.5 dark:border-slate-700">
                            Total iklan {{ $totalItems }}
                        </span>
                        <span class="rounded-full border border-slate-200 px-3 py-1.5 dark:border-slate-700">
                            Aktif {{ $activeCount }}
                        </span>
                    </div>
                </div>

                @if ($tableReady)
                    <a href="{{ route('iklan.create') }}"
                        class="inline-flex items-center justify-center gap-2 rounded-xl bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-slate-800 dark:bg-slate-100 dark:text-slate-900 dark:hover:bg-white">
                        <i class="fa-solid fa-plus"></i>
                        Tambah Iklan
                    </a>
                @else
                    <span
                        class="inline-flex items-center justify-center rounded-xl border border-amber-200 bg-amber-50 px-4 py-2.5 text-sm font-semibold text-amber-700 dark:border-amber-500/20 dark:bg-amber-500/10 dark:text-amber-200">
                        Jalankan migrasi dulu
                    </span>
                @endif
            </div>
        </section>

        @if (!$tableReady)
            <section
                class="rounded-xl border border-amber-200 bg-amber-50 p-5 shadow-sm dark:border-amber-500/20 dark:bg-amber-500/10">
                <h2 class="text-lg font-semibold text-amber-800 dark:text-amber-100">Tabel iklan belum tersedia</h2>
                <p class="mt-2 text-sm leading-6 text-amber-700 dark:text-amber-200">
                    Jalankan migrasi supaya modul ini bisa dipakai untuk simpan, edit, dan hapus data.
                </p>
                <code
                    class="mt-4 inline-flex rounded-lg bg-white px-3 py-2 text-sm text-amber-900 shadow-sm dark:bg-slate-950 dark:text-amber-100">php artisan migrate</code>
            </section>
        @else
            <section class="grid gap-3 sm:grid-cols-2 xl:grid-cols-5">
                <div
                    class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm dark:border-slate-800 dark:bg-slate-950">
                    <p class="text-sm text-slate-500 dark:text-slate-400">Total Data</p>
                    <p class="mt-2 text-2xl font-semibold text-slate-900 dark:text-white">{{ number_format($totalItems) }}
                    </p>
                </div>
                <div
                    class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm dark:border-slate-800 dark:bg-slate-950">
                    <p class="text-sm text-slate-500 dark:text-slate-400">Iklan Aktif</p>
                    <p class="mt-2 text-2xl font-semibold text-slate-900 dark:text-white">{{ number_format($activeCount) }}
                    </p>
                </div>
                <div
                    class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm dark:border-slate-800 dark:bg-slate-950">
                    <p class="text-sm text-slate-500 dark:text-slate-400">Pakai Gambar</p>
                    <p class="mt-2 text-2xl font-semibold text-slate-900 dark:text-white">{{ number_format($withImageCount) }}
                    </p>
                </div>
                <div
                    class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm dark:border-slate-800 dark:bg-slate-950">
                    <p class="text-sm text-slate-500 dark:text-slate-400">HTML Kustom</p>
                    <p class="mt-2 text-2xl font-semibold text-slate-900 dark:text-white">{{ number_format($customHtmlCount) }}
                    </p>
                </div>
                <div
                    class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm dark:border-slate-800 dark:bg-slate-950">
                    <p class="text-sm text-slate-500 dark:text-slate-400">Terjadwal</p>
                    <p class="mt-2 text-2xl font-semibold text-slate-900 dark:text-white">{{ number_format($scheduledCount) }}
                    </p>
                </div>
            </section>

            <section
                class="rounded-xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-950">
                <div class="flex flex-col gap-4 border-b border-slate-200 px-5 py-4 dark:border-slate-800 md:flex-row md:items-center md:justify-between">
                    <div>
                        <h2 class="text-lg font-semibold text-slate-900 dark:text-white">Daftar Iklan</h2>
                        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                            Urutan tampil mengikuti kolom sort order.
                        </p>
                    </div>
                </div>

                @if ($iklans->isEmpty())
                    <div class="flex flex-col items-center gap-3 px-5 py-16 text-center">
                        <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-slate-100 text-slate-600 dark:bg-slate-900 dark:text-slate-200">
                            <i class="fa-solid fa-rectangle-ad text-xl"></i>
                        </div>
                        <h3 class="mt-4 text-lg font-semibold text-slate-900 dark:text-white">Belum ada iklan</h3>
                        <p class="max-w-md text-sm leading-6 text-slate-500 dark:text-slate-400">
                            Klik tombol tambah untuk mulai menambahkan iklan baru.
                        </p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-800">
                            <thead class="bg-slate-50 text-left text-xs font-semibold uppercase tracking-wide text-slate-500 dark:bg-slate-950 dark:text-slate-400">
                                <tr>
                                    <th class="px-5 py-3">Iklan</th>
                                    <th class="px-5 py-3">Konten</th>
                                    <th class="px-5 py-3">Jadwal</th>
                                    <th class="px-5 py-3">Status</th>
                                    <th class="px-5 py-3">Sort</th>
                                    <th class="px-4 py-3 text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200 bg-white dark:divide-slate-800 dark:bg-slate-950">
                                @foreach ($iklans as $iklan)
                                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-900/40">
                                        <td class="px-5 py-4 text-sm text-slate-700 dark:text-slate-200">
                                            <p class="font-semibold text-slate-900 dark:text-white">{{ $iklan->title }}</p>
                                            <p class="mt-1 max-w-xs text-slate-500 dark:text-slate-400">
                                                {{ $iklan->description ? \Illuminate\Support\Str::limit($iklan->description, 80) : '-' }}
                                            </p>
                                        </td>
                                        <td class="px-5 py-4 text-sm text-slate-700 dark:text-slate-200">
                                            @if (filled($iklan->image))
                                                <div class="flex items-center gap-3">
                                                    <img src="{{ asset($iklan->image) }}" alt="{{ $iklan->title }}"
                                                        class="h-12 w-20 rounded-xl object-cover">
                                                    <div>
                                                        <p class="text-xs text-slate-500 dark:text-slate-400">Gambar</p>
                                                        <p class="mt-1 text-sm font-semibold text-slate-900 dark:text-white">Tersimpan</p>
                                                    </div>
                                                </div>
                                            @else
                                                <p class="text-sm text-slate-500 dark:text-slate-400">Tanpa gambar</p>
                                            @endif

                                            <div class="mt-3 text-sm text-slate-700 dark:text-slate-200">
                                                @if (filled($iklan->modal_html))
                                                    <p class="font-semibold text-sky-700 dark:text-sky-200">Modal HTML kustom</p>
                                                    <p class="mt-1 max-w-xs text-slate-500 dark:text-slate-400">
                                                        {{ $formatHtmlSummary($iklan->modal_html) }}
                                                    </p>
                                                @else
                                                    <p>{{ $iklan->cta_label ?: '-' }}</p>
                                                    <p class="mt-1 max-w-xs break-all text-slate-500 dark:text-slate-400">
                                                        {{ $iklan->cta_url ?: '-' }}
                                                    </p>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-5 py-4 text-sm text-slate-700 dark:text-slate-200">
                                            <p>Mulai: {{ $formatDate($iklan->start_at) }}</p>
                                            <p class="mt-1">Selesai: {{ $formatDate($iklan->end_at) }}</p>
                                        </td>
                                        <td class="px-5 py-4">
                                            <div class="flex flex-col items-start gap-2">
                                                <span
                                                    class="rounded-full px-3 py-1 text-xs font-semibold {{ $iklan->is_active
                                                        ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-200'
                                                        : 'bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-200' }}">
                                                    {{ $iklan->is_active ? 'Aktif' : 'Nonaktif' }}
                                                </span>
                                                @if (filled($iklan->modal_html))
                                                    <span
                                                        class="rounded-full bg-sky-50 px-3 py-1 text-xs font-semibold text-sky-700 dark:bg-sky-500/10 dark:text-sky-200">
                                                        HTML Kustom
                                                    </span>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-5 py-4 text-sm font-semibold whitespace-nowrap text-slate-700 dark:text-slate-200">
                                            {{ $iklan->sort_order }}
                                        </td>
                                        <td class="w-px px-4 py-4 whitespace-nowrap">
                                            <div class="flex justify-end gap-2">
                                                <a href="{{ route('iklan.edit', $iklan->id) }}"
                                                    class="inline-flex items-center justify-center rounded-lg border border-slate-200 px-3 py-1.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-900">
                                                    Edit
                                                </a>
                                                <button type="button"
                                                    onclick="openDeleteModal({{ $iklan->id }}, @js($iklan->title))"
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
        @endif
    </div>

    <div id="deleteModal" data-modal
        class="fixed inset-0 z-50 hidden flex items-center justify-center bg-slate-950/50 px-4">
        <div
            class="w-full max-w-md rounded-2xl border border-slate-200 bg-white p-6 shadow-xl dark:border-slate-800 dark:bg-slate-950">
            <h2 class="text-lg font-semibold text-slate-900 dark:text-white">Hapus iklan?</h2>
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
                deleteModalText.textContent = `Iklan "${title}" akan dihapus permanen.`;
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

