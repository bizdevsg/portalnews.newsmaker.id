@section('namePage', 'Pop Up Banner')

@php
    $formatDate = function ($date) {
        if (!filled($date)) {
            return '-';
        }

        return \Illuminate\Support\Carbon::parse($date)->format('d M Y H:i');
    };

    $totalItems = $popupBanners->count();
    $activeCount = $popupBanners->where('is_active', true)->count();
    $withImageCount = $popupBanners->filter(fn($item) => filled($item->image))->count();
    $customHtmlCount = $popupBanners->filter(fn($item) => filled($item->modal_html))->count();
    $scheduledCount = $popupBanners->filter(fn($item) => filled($item->start_at) || filled($item->end_at))->count();
    $deleteRouteTemplate = route('popup-banner.destroy', ['id' => '__ID__']);
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
            <div
                class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700 dark:border-emerald-500/20 dark:bg-emerald-500/10 dark:text-emerald-200">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div
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
                        Pop Up Banner
                    </h1>
                    <p class="mt-2 max-w-2xl text-sm text-slate-500 dark:text-slate-400">
                        Kelola banner popup untuk promosi aplikasi, campaign, pengumuman, dan CTA di Newsmaker23.
                    </p>

                    <div class="mt-4 flex flex-wrap gap-2 text-xs font-medium text-slate-600 dark:text-slate-300">
                        <span class="rounded-full border border-slate-200 px-3 py-1.5 dark:border-slate-700">
                            Total banner {{ $totalItems }}
                        </span>
                        <span class="rounded-full border border-slate-200 px-3 py-1.5 dark:border-slate-700">
                            Aktif {{ $activeCount }}
                        </span>
                    </div>
                </div>

                @if ($tableReady)
                    <a href="{{ route('popup-banner.create') }}"
                        class="inline-flex items-center justify-center gap-2 rounded-xl bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-slate-800 dark:bg-slate-100 dark:text-slate-900 dark:hover:bg-white">
                        <i class="fa-solid fa-plus"></i>
                        Tambah Banner
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
                <h2 class="text-lg font-semibold text-amber-800 dark:text-amber-100">Tabel popup banner belum tersedia</h2>
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
                    <p class="text-sm text-slate-500 dark:text-slate-400">Banner Aktif</p>
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
                    <p class="text-sm text-slate-500 dark:text-slate-400">Pakai Jadwal</p>
                    <p class="mt-2 text-2xl font-semibold text-slate-900 dark:text-white">{{ number_format($scheduledCount) }}
                    </p>
                </div>
            </section>

            <section
                class="rounded-xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-950">
                <div
                    class="flex flex-col gap-3 border-b border-slate-200 p-5 dark:border-slate-800 md:flex-row md:items-center md:justify-between">
                    <div>
                        <h2 class="text-lg font-semibold text-slate-900 dark:text-white">Daftar Pop Up Banner</h2>
                        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                            Ringkasan status tayang, jadwal, CTA, aset gambar, dan desain HTML kustom.
                        </p>
                    </div>

                    <div class="flex flex-wrap gap-2 text-xs font-medium">
                        <span
                            class="rounded-full bg-slate-100 px-3 py-1.5 text-slate-700 dark:bg-slate-800 dark:text-slate-200">
                            Total {{ $totalItems }}
                        </span>
                        <span
                            class="rounded-full bg-emerald-50 px-3 py-1.5 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-200">
                            Aktif {{ $activeCount }}
                        </span>
                    </div>
                </div>

                @if ($totalItems === 0)
                    <div class="p-6 text-center sm:p-10">
                        <div
                            class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-slate-100 text-slate-500 dark:bg-slate-800 dark:text-slate-300">
                            <i class="fa-solid fa-rectangle-ad"></i>
                        </div>
                        <h3 class="mt-4 text-lg font-semibold text-slate-900 dark:text-white">Belum ada popup banner</h3>
                        <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">
                            Tambahkan banner pertama untuk mulai mengelola promo aplikasi, campaign, atau pengumuman.
                        </p>
                    </div>
                @else
                    <div class="grid gap-4 p-4 lg:hidden">
                        @foreach ($popupBanners as $popupBanner)
                            <article class="rounded-2xl border border-slate-200 p-4 dark:border-slate-800">
                                <div class="flex items-start justify-between gap-3">
                                    <div class="min-w-0">
                                        <h3 class="text-base font-semibold text-slate-900 dark:text-white">
                                            {{ $popupBanner->title }}
                                        </h3>
                                        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                                            Update {{ $formatDate($popupBanner->updated_at) }}
                                        </p>
                                    </div>
                                    <div class="flex flex-wrap justify-end gap-2">
                                        @if (filled($popupBanner->modal_html))
                                            <span
                                                class="rounded-full bg-sky-50 px-3 py-1 text-xs font-semibold text-sky-700 dark:bg-sky-500/10 dark:text-sky-200">
                                                HTML Kustom
                                            </span>
                                        @endif
                                        <span
                                            class="rounded-full px-3 py-1 text-xs font-semibold {{ $popupBanner->is_active
                                                ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-200'
                                                : 'bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-200' }}">
                                            {{ $popupBanner->is_active ? 'Aktif' : 'Nonaktif' }}
                                        </span>
                                    </div>
                                </div>

                                @if ($popupBanner->image)
                                    <img src="{{ asset($popupBanner->image) }}" alt="{{ $popupBanner->title }}"
                                        class="mt-4 h-40 w-full rounded-2xl object-cover">
                                @endif

                                <div class="mt-4 grid gap-3">
                                    <div class="rounded-xl bg-slate-50 p-4 dark:bg-slate-900">
                                        @if (filled($popupBanner->modal_html))
                                            <p class="text-xs font-medium uppercase tracking-wide text-slate-400">Desain</p>
                                            <p class="mt-2 text-sm font-semibold text-sky-700 dark:text-sky-200">
                                                Modal HTML kustom
                                            </p>
                                            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                                                {{ $formatHtmlSummary($popupBanner->modal_html) }}
                                            </p>
                                        @else
                                            <p class="text-xs font-medium uppercase tracking-wide text-slate-400">CTA</p>
                                            <p class="mt-2 text-sm text-slate-700 dark:text-slate-200">
                                                {{ $popupBanner->cta_label ?: '-' }}
                                            </p>
                                            <p class="mt-1 break-all text-sm text-slate-500 dark:text-slate-400">
                                                {{ $popupBanner->cta_url ?: '-' }}
                                            </p>
                                        @endif
                                    </div>

                                    <div class="rounded-xl bg-slate-50 p-4 dark:bg-slate-900">
                                        <p class="text-xs font-medium uppercase tracking-wide text-slate-400">Jadwal</p>
                                        <p class="mt-2 text-sm text-slate-700 dark:text-slate-200">
                                            Mulai: {{ $formatDate($popupBanner->start_at) }}
                                        </p>
                                        <p class="mt-1 text-sm text-slate-700 dark:text-slate-200">
                                            Selesai: {{ $formatDate($popupBanner->end_at) }}
                                        </p>
                                    </div>
                                </div>

                                <div class="mt-4 flex justify-end gap-3">
                                    <a href="{{ route('popup-banner.edit', $popupBanner->id) }}"
                                        class="inline-flex items-center justify-center rounded-xl border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-50 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-900">
                                        Edit
                                    </a>
                                    <button type="button"
                                        onclick="openDeleteModal({{ $popupBanner->id }}, @js($popupBanner->title))"
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
                                    <th class="px-5 py-4">Banner</th>
                                    <th class="px-5 py-4">CTA</th>
                                    <th class="px-5 py-4">Jadwal</th>
                                    <th class="px-5 py-4">Status</th>
                                    <th class="px-5 py-4 whitespace-nowrap">Urutan</th>
                                    <th class="w-px px-4 py-4 text-right whitespace-nowrap">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                                @foreach ($popupBanners as $popupBanner)
                                    <tr class="align-top">
                                        <td class="px-5 py-4">
                                            <div class="flex items-start gap-4">
                                                @if ($popupBanner->image)
                                                    <img src="{{ asset($popupBanner->image) }}" alt="{{ $popupBanner->title }}"
                                                        class="h-16 w-24 rounded-xl object-cover">
                                                @else
                                                    <div
                                                        class="flex h-16 w-24 items-center justify-center rounded-xl bg-slate-100 text-slate-400 dark:bg-slate-800 dark:text-slate-500">
                                                        <i class="fa-solid fa-image"></i>
                                                    </div>
                                                @endif

                                                <div class="min-w-0">
                                                    <p class="text-sm font-semibold text-slate-900 dark:text-white">
                                                        {{ $popupBanner->title }}</p>
                                                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                                                        {{ filled($popupBanner->modal_html) ? $formatHtmlSummary($popupBanner->modal_html) : \Illuminate\Support\Str::limit($popupBanner->description ?: '-', 80) }}
                                                    </p>
                                                    <p class="mt-1 text-xs text-slate-400">
                                                        Update {{ $formatDate($popupBanner->updated_at) }}
                                                    </p>
                                                    <div class="mt-2 flex flex-wrap gap-2">
                                                        @if (filled($popupBanner->modal_html))
                                                            <span
                                                                class="rounded-full bg-sky-50 px-2.5 py-1 text-[11px] font-semibold text-sky-700 dark:bg-sky-500/10 dark:text-sky-200">
                                                                HTML Kustom
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-5 py-4 text-sm text-slate-700 dark:text-slate-200">
                                            @if (filled($popupBanner->modal_html))
                                                <p class="font-semibold text-sky-700 dark:text-sky-200">Modal HTML kustom</p>
                                                <p class="mt-1 max-w-xs text-slate-500 dark:text-slate-400">
                                                    {{ $formatHtmlSummary($popupBanner->modal_html) }}
                                                </p>
                                            @else
                                                <p>{{ $popupBanner->cta_label ?: '-' }}</p>
                                                <p class="mt-1 max-w-xs break-all text-slate-500 dark:text-slate-400">
                                                    {{ $popupBanner->cta_url ?: '-' }}
                                                </p>
                                            @endif
                                        </td>
                                        <td class="px-5 py-4 text-sm text-slate-700 dark:text-slate-200">
                                            <p>Mulai: {{ $formatDate($popupBanner->start_at) }}</p>
                                            <p class="mt-1">Selesai: {{ $formatDate($popupBanner->end_at) }}</p>
                                        </td>
                                        <td class="px-5 py-4">
                                            <div class="flex flex-col items-start gap-2">
                                                <span
                                                    class="rounded-full px-3 py-1 text-xs font-semibold {{ $popupBanner->is_active
                                                        ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-200'
                                                        : 'bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-200' }}">
                                                    {{ $popupBanner->is_active ? 'Aktif' : 'Nonaktif' }}
                                                </span>
                                                @if (filled($popupBanner->modal_html))
                                                    <span
                                                        class="rounded-full bg-sky-50 px-3 py-1 text-xs font-semibold text-sky-700 dark:bg-sky-500/10 dark:text-sky-200">
                                                        HTML Kustom
                                                    </span>
                                                @endif
                                            </div>
                                        </td>
                                        <td
                                            class="px-5 py-4 text-sm font-semibold whitespace-nowrap text-slate-700 dark:text-slate-200">
                                            {{ $popupBanner->sort_order }}
                                        </td>
                                        <td class="w-px px-4 py-4 whitespace-nowrap">
                                            <div class="flex justify-end gap-2">
                                                <a href="{{ route('popup-banner.edit', $popupBanner->id) }}"
                                                    class="inline-flex items-center justify-center rounded-lg border border-slate-200 px-3 py-1.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-900">
                                                    Edit
                                                </a>
                                                <button type="button"
                                                    onclick="openDeleteModal({{ $popupBanner->id }}, @js($popupBanner->title))"
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
            <h2 class="text-lg font-semibold text-slate-900 dark:text-white">Hapus popup banner?</h2>
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
                deleteModalText.textContent = `Popup banner "${title}" akan dihapus permanen.`;
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
