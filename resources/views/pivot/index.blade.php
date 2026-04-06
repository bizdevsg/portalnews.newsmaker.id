@section('namePage', 'Historical Data')

@php
    $categories = ['LGD Daily', 'BCO Daily', 'HSI Daily', 'SNI Daily', 'AUD/USD', 'EUR/USD', 'GBP/USD', 'USD/CHF', 'USD/JPY'];
    $selectedCategory = $category ?? request('category', 'LGD Daily');
    $totalRows = $pivots->count();
    $holidayCount = $pivots->where('isBankHoliday', true)->count();
    $marketCount = $totalRows - $holidayCount;
    $latestDate = $pivots->first()?->tanggal;
    $oldestDate = $pivots->last()?->tanggal;
@endphp

<x-app-layout>
    <div class="w-full px-4 py-8 sm:px-6 lg:px-8">
        <section
            class="relative overflow-hidden rounded-[28px] border border-slate-200 bg-gradient-to-br from-slate-950 via-slate-900 to-blue-900 px-6 py-7 text-white shadow-sm dark:border-slate-800">
            <div class="absolute -right-16 top-0 h-44 w-44 rounded-full bg-cyan-400/20 blur-3xl"></div>
            <div class="absolute bottom-0 left-0 h-40 w-40 rounded-full bg-blue-500/20 blur-3xl"></div>

            <div class="relative flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
                <div class="max-w-3xl">
                    <p class="text-sm font-semibold uppercase tracking-[0.22em] text-sky-200/80">Market Archive</p>
                    <h1 class="mt-3 text-3xl font-bold tracking-tight text-white md:text-4xl">Historical Data</h1>
                    <p class="mt-3 max-w-2xl text-sm leading-6 text-slate-200">
                        Ringkas pergerakan historis untuk setiap instrumen dalam satu tampilan yang lebih rapi, cepat dibaca,
                        dan nyaman dipakai saat memilah data lama.
                    </p>
                </div>

                <div class="flex flex-col gap-3 sm:flex-row">
                    <div class="rounded-2xl border border-white/15 bg-white/10 px-4 py-3 backdrop-blur-sm">
                        <p class="text-[11px] font-semibold uppercase tracking-[0.18em] text-sky-100/75">Kategori Aktif</p>
                        <p class="mt-1 text-lg font-semibold text-white">{{ $selectedCategory }}</p>
                    </div>

                    <a href="{{ route('pivot.create') }}"
                        class="inline-flex items-center justify-center rounded-2xl bg-white px-5 py-3 text-sm font-semibold text-slate-900 transition hover:bg-slate-100">
                        <i class="fa-solid fa-plus mr-2 text-xs"></i>
                        Tambah History
                    </a>
                </div>
            </div>
        </section>

        @if (session('success'))
            <div id="successAlert"
                class="mt-6 flex items-start justify-between gap-4 rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-emerald-700 shadow-sm dark:border-emerald-500/20 dark:bg-emerald-500/10 dark:text-emerald-200">
                <div class="flex items-start gap-3">
                    <i class="fa-solid fa-circle-check mt-0.5"></i>
                    <p class="text-sm font-medium leading-6">{{ session('success') }}</p>
                </div>
                <button type="button" id="closeSuccessAlert"
                    class="inline-flex h-9 w-9 items-center justify-center rounded-full transition hover:bg-emerald-100 dark:hover:bg-emerald-500/10">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
        @endif

        <section class="mt-6 grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-4">
            <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <p class="text-sm text-slate-500 dark:text-slate-400">Total Entry</p>
                <p class="mt-2 text-3xl font-bold text-slate-900 dark:text-slate-100">{{ number_format($totalRows) }}</p>
                <p class="mt-2 text-xs text-slate-500 dark:text-slate-400">Semua record pada kategori aktif.</p>
            </article>

            <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <p class="text-sm text-slate-500 dark:text-slate-400">Trading Day</p>
                <p class="mt-2 text-3xl font-bold text-slate-900 dark:text-slate-100">{{ number_format($marketCount) }}</p>
                <p class="mt-2 text-xs text-slate-500 dark:text-slate-400">Baris data dengan nilai open, high, low, close.</p>
            </article>

            <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <p class="text-sm text-slate-500 dark:text-slate-400">Bank Holiday</p>
                <p class="mt-2 text-3xl font-bold text-slate-900 dark:text-slate-100">{{ number_format($holidayCount) }}</p>
                <p class="mt-2 text-xs text-slate-500 dark:text-slate-400">Hari libur yang menimpa instrumen terpilih.</p>
            </article>

            <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <p class="text-sm text-slate-500 dark:text-slate-400">Rentang Data</p>
                <p class="mt-2 text-lg font-bold text-slate-900 dark:text-slate-100">
                    @if ($latestDate && $oldestDate)
                        {{ \Carbon\Carbon::parse($oldestDate)->translatedFormat('d M Y') }}
                        <span class="mx-1 text-slate-400">-</span>
                        {{ \Carbon\Carbon::parse($latestDate)->translatedFormat('d M Y') }}
                    @else
                        Belum tersedia
                    @endif
                </p>
                <p class="mt-2 text-xs text-slate-500 dark:text-slate-400">Tanggal paling awal sampai paling baru.</p>
            </article>
        </section>

        <section class="mt-6 rounded-[28px] border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <div class="border-b border-slate-200 px-5 py-5 dark:border-slate-800">
                <div class="flex flex-col gap-4 xl:flex-row xl:items-center xl:justify-between">
                    <div>
                        <p class="text-sm font-semibold text-blue-600 dark:text-blue-400">Instrument Filter</p>
                        <h2 class="mt-1 text-xl font-bold text-slate-900 dark:text-slate-100">Pilih kategori histori</h2>
                        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                            Ganti kategori untuk melihat histori instrumen lain tanpa meninggalkan halaman ini.
                        </p>
                    </div>

                    <div class="rounded-2xl bg-slate-50 px-4 py-3 text-sm text-slate-600 dark:bg-slate-800 dark:text-slate-300">
                        Menampilkan
                        <span class="font-semibold text-slate-900 dark:text-slate-100">{{ number_format($totalRows) }}</span>
                        data untuk
                        <span class="font-semibold text-slate-900 dark:text-slate-100">{{ $selectedCategory }}</span>
                    </div>
                </div>

                <div class="mt-5 flex gap-2 overflow-x-auto pb-1">
                    @foreach ($categories as $cat)
                        @php
                            $isActiveCategory = $selectedCategory === $cat;
                        @endphp
                        <button type="button" data-category="{{ $cat }}"
                            class="category-button shrink-0 rounded-full border px-4 py-2 text-sm font-semibold transition {{ $isActiveCategory
                                ? 'border-blue-600 bg-blue-600 text-white shadow-sm shadow-blue-600/30'
                                : 'border-slate-200 bg-white text-slate-600 hover:border-slate-300 hover:bg-slate-50 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-300 dark:hover:border-slate-600 dark:hover:bg-slate-800' }}">
                            {{ $cat }}
                        </button>
                    @endforeach
                </div>
            </div>

            @if ($pivots->isEmpty())
                <div class="px-6 py-14 text-center">
                    <img src="{{ asset('assets/hand-drawn-no-data-concept.png') }}" alt="No data"
                        class="mx-auto h-40 w-auto object-contain opacity-90">
                    <h3 class="mt-5 text-lg font-bold text-slate-900 dark:text-slate-100">Belum ada historical data</h3>
                    <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">
                        Kategori <span class="font-semibold text-slate-700 dark:text-slate-200">{{ $selectedCategory }}</span>
                        belum memiliki data. Tambahkan entry baru untuk mulai mengisi histori.
                    </p>
                    <div class="mt-6">
                        <a href="{{ route('pivot.create') }}"
                            class="inline-flex items-center justify-center rounded-2xl bg-slate-900 px-5 py-3 text-sm font-semibold text-white transition hover:bg-slate-800 dark:bg-slate-100 dark:text-slate-900 dark:hover:bg-white">
                            Tambah Data Pertama
                        </a>
                    </div>
                </div>
            @else
                <div class="grid gap-4 p-4 lg:hidden">
                    @foreach ($pivots as $pivot)
                        <article
                            class="overflow-hidden rounded-2xl border {{ $pivot->isBankHoliday ? 'border-amber-200 bg-amber-50/60 dark:border-amber-500/20 dark:bg-amber-500/10' : 'border-slate-200 bg-white dark:border-slate-800 dark:bg-slate-900' }}">
                            <div class="p-4">
                                <div class="flex items-start justify-between gap-3">
                                    <div>
                                        <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">
                                            {{ $selectedCategory }}
                                        </p>
                                        <h3 class="mt-1 text-base font-bold text-slate-900 dark:text-slate-100">
                                            {{ \Carbon\Carbon::parse($pivot->tanggal)->translatedFormat('d F Y') }}
                                        </h3>
                                    </div>

                                    @if ($pivot->isBankHoliday)
                                        <span
                                            class="inline-flex rounded-full border border-amber-300 bg-amber-100 px-3 py-1 text-[11px] font-semibold text-amber-800 dark:border-amber-500/20 dark:bg-amber-500/10 dark:text-amber-200">
                                            Bank Holiday
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex rounded-full border border-emerald-200 bg-emerald-50 px-3 py-1 text-[11px] font-semibold text-emerald-700 dark:border-emerald-500/20 dark:bg-emerald-500/10 dark:text-emerald-200">
                                            Trading Day
                                        </span>
                                    @endif
                                </div>

                                @if ($pivot->isBankHoliday)
                                    <div
                                        class="mt-4 rounded-2xl border border-amber-200 bg-white/70 px-4 py-3 text-sm text-amber-900 dark:border-amber-500/20 dark:bg-slate-950/40 dark:text-amber-100">
                                        {{ $pivot->description ?: 'Bank Holiday' }}
                                    </div>
                                @else
                                    <div class="mt-4 grid grid-cols-2 gap-3">
                                        <div class="rounded-2xl bg-slate-50 px-3 py-3 dark:bg-slate-800">
                                            <p class="text-[11px] uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">Open</p>
                                            <p class="mt-1 font-mono text-sm font-semibold text-slate-900 dark:text-slate-100">{{ $pivot->open }}</p>
                                        </div>
                                        <div class="rounded-2xl bg-slate-50 px-3 py-3 dark:bg-slate-800">
                                            <p class="text-[11px] uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">High</p>
                                            <p class="mt-1 font-mono text-sm font-semibold text-slate-900 dark:text-slate-100">{{ $pivot->high }}</p>
                                        </div>
                                        <div class="rounded-2xl bg-slate-50 px-3 py-3 dark:bg-slate-800">
                                            <p class="text-[11px] uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">Low</p>
                                            <p class="mt-1 font-mono text-sm font-semibold text-slate-900 dark:text-slate-100">{{ $pivot->low }}</p>
                                        </div>
                                        <div class="rounded-2xl bg-slate-50 px-3 py-3 dark:bg-slate-800">
                                            <p class="text-[11px] uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">Close</p>
                                            <p class="mt-1 font-mono text-sm font-semibold text-slate-900 dark:text-slate-100">{{ $pivot->close }}</p>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <div class="grid grid-cols-2 gap-3 border-t border-slate-200 bg-slate-50/80 p-4 dark:border-slate-800 dark:bg-slate-950/50">
                                <a href="{{ route('pivot.edit', $pivot->id) }}"
                                    class="inline-flex items-center justify-center rounded-xl border border-slate-300 px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-100 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-800">
                                    Edit
                                </a>
                                <button type="button"
                                    class="inline-flex items-center justify-center rounded-xl bg-rose-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-rose-700"
                                    onclick="openDeleteModal({{ $pivot->id }}, '{{ addslashes(\Carbon\Carbon::parse($pivot->tanggal)->translatedFormat('d F Y')) }}')">
                                    Hapus
                                </button>
                            </div>
                        </article>
                    @endforeach
                </div>

                <div class="hidden lg:block">
                    <div class="overflow-x-auto">
                        <table class="min-w-full border-collapse">
                            <thead class="bg-slate-50 dark:bg-slate-950/80">
                                <tr class="text-left">
                                    <th
                                        class="whitespace-nowrap px-6 py-4 text-xs font-semibold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">
                                        Tanggal
                                    </th>
                                    <th
                                        class="whitespace-nowrap px-6 py-4 text-center text-xs font-semibold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">
                                        Open
                                    </th>
                                    <th
                                        class="whitespace-nowrap px-6 py-4 text-center text-xs font-semibold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">
                                        High
                                    </th>
                                    <th
                                        class="whitespace-nowrap px-6 py-4 text-center text-xs font-semibold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">
                                        Low
                                    </th>
                                    <th
                                        class="whitespace-nowrap px-6 py-4 text-center text-xs font-semibold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">
                                        Close
                                    </th>
                                    <th
                                        class="whitespace-nowrap px-6 py-4 text-center text-xs font-semibold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">
                                        Status
                                    </th>
                                    <th
                                        class="whitespace-nowrap px-6 py-4 text-center text-xs font-semibold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">
                                        Aksi
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                                @foreach ($pivots as $pivot)
                                    <tr
                                        class="transition hover:bg-slate-50 dark:hover:bg-slate-800/60 {{ $pivot->isBankHoliday ? 'bg-amber-50/60 dark:bg-amber-500/5' : 'bg-white dark:bg-slate-900' }}">
                                        <td class="px-6 py-4">
                                            <div class="flex flex-col">
                                                <span class="font-semibold text-slate-900 dark:text-slate-100">
                                                    {{ \Carbon\Carbon::parse($pivot->tanggal)->translatedFormat('d F Y') }}
                                                </span>
                                                <span class="mt-1 text-xs text-slate-500 dark:text-slate-400">
                                                    {{ $pivot->category }}
                                                </span>
                                            </div>
                                        </td>

                                        @if ($pivot->isBankHoliday)
                                            <td colspan="4" class="px-6 py-4 text-center">
                                                <div
                                                    class="inline-flex max-w-2xl items-center rounded-full border border-amber-300 bg-amber-100 px-4 py-2 text-sm font-medium text-amber-900 dark:border-amber-500/20 dark:bg-amber-500/10 dark:text-amber-100">
                                                    {{ $pivot->description ?: 'Bank Holiday' }}
                                                </div>
                                            </td>
                                        @else
                                            <td class="px-6 py-4 text-center font-mono text-sm font-semibold text-slate-700 dark:text-slate-200">
                                                {{ $pivot->open }}
                                            </td>
                                            <td class="px-6 py-4 text-center font-mono text-sm font-semibold text-slate-700 dark:text-slate-200">
                                                {{ $pivot->high }}
                                            </td>
                                            <td class="px-6 py-4 text-center font-mono text-sm font-semibold text-slate-700 dark:text-slate-200">
                                                {{ $pivot->low }}
                                            </td>
                                            <td class="px-6 py-4 text-center font-mono text-sm font-semibold text-slate-700 dark:text-slate-200">
                                                {{ $pivot->close }}
                                            </td>
                                        @endif

                                        <td class="px-6 py-4 text-center">
                                            @if ($pivot->isBankHoliday)
                                                <span
                                                    class="inline-flex rounded-full border border-amber-300 bg-amber-100 px-3 py-1 text-[11px] font-semibold text-amber-800 dark:border-amber-500/20 dark:bg-amber-500/10 dark:text-amber-200">
                                                    Bank Holiday
                                                </span>
                                            @else
                                                <span
                                                    class="inline-flex rounded-full border border-emerald-200 bg-emerald-50 px-3 py-1 text-[11px] font-semibold text-emerald-700 dark:border-emerald-500/20 dark:bg-emerald-500/10 dark:text-emerald-200">
                                                    Trading Day
                                                </span>
                                            @endif
                                        </td>

                                        <td class="px-6 py-4">
                                            <div class="flex items-center justify-center gap-2">
                                                <a href="{{ route('pivot.edit', $pivot->id) }}"
                                                    class="inline-flex items-center justify-center rounded-xl border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-100 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-800">
                                                    Edit
                                                </a>
                                                <button type="button"
                                                    class="inline-flex items-center justify-center rounded-xl bg-rose-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-rose-700"
                                                    onclick="openDeleteModal({{ $pivot->id }}, '{{ addslashes(\Carbon\Carbon::parse($pivot->tanggal)->translatedFormat('d F Y')) }}')">
                                                    Hapus
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </section>
    </div>

    <div id="deleteModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-950/55 px-4" aria-hidden="true">
        <div class="w-full rounded-3xl border border-slate-200 bg-white p-6 shadow-xl dark:border-slate-800 dark:bg-slate-900 md:w-[30rem]">
            <div class="flex items-start gap-4">
                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-rose-100 text-rose-700 dark:bg-rose-500/10 dark:text-rose-300">
                    <i class="fa-solid fa-trash"></i>
                </div>

                <div class="min-w-0">
                    <h3 class="text-lg font-bold text-slate-900 dark:text-slate-100">Hapus Historical Data</h3>
                    <p class="mt-2 text-sm leading-6 text-slate-500 dark:text-slate-400">
                        Entry pada tanggal <span id="deleteDateLabel" class="font-semibold text-slate-900 dark:text-slate-100"></span>
                        akan dihapus permanen.
                    </p>
                </div>
            </div>

            <div class="mt-6 flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
                <button type="button" id="cancelDeleteButton"
                    class="inline-flex items-center justify-center rounded-2xl border border-slate-300 px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-800">
                    Batal
                </button>
                <form id="deleteForm" action="" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" id="confirmDeleteButton"
                        class="inline-flex w-full items-center justify-center rounded-2xl bg-rose-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-rose-700 sm:w-auto">
                        Ya, Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const deleteModal = document.getElementById('deleteModal');
            const deleteForm = document.getElementById('deleteForm');
            const deleteDateLabel = document.getElementById('deleteDateLabel');
            const cancelDeleteButton = document.getElementById('cancelDeleteButton');
            const confirmDeleteButton = document.getElementById('confirmDeleteButton');
            const successAlert = document.getElementById('successAlert');
            const closeSuccessAlert = document.getElementById('closeSuccessAlert');
            const deleteActionTemplate = @json(route('pivot.destroy', ['id' => '__ID__']));

            document.querySelectorAll('.category-button').forEach((button) => {
                button.addEventListener('click', () => {
                    const category = button.dataset.category;
                    const url = new URL(window.location.href);
                    url.searchParams.set('category', category);
                    window.location.href = url.toString();
                });
            });

            window.openDeleteModal = (id, dateLabel) => {
                if (!deleteModal || !deleteForm) {
                    return;
                }

                deleteForm.action = deleteActionTemplate.replace('__ID__', id);
                deleteDateLabel.textContent = dateLabel || '';
                confirmDeleteButton.disabled = false;
                confirmDeleteButton.textContent = 'Ya, Hapus';
                deleteModal.classList.remove('hidden');
                deleteModal.classList.add('flex');
                document.body.classList.add('overflow-hidden');
            };

            window.closeDeleteModal = () => {
                deleteModal?.classList.add('hidden');
                deleteModal?.classList.remove('flex');
                document.body.classList.remove('overflow-hidden');
            };

            cancelDeleteButton?.addEventListener('click', window.closeDeleteModal);

            deleteModal?.addEventListener('click', (event) => {
                if (event.target === deleteModal) {
                    window.closeDeleteModal();
                }
            });

            document.addEventListener('keydown', (event) => {
                if (event.key === 'Escape') {
                    window.closeDeleteModal();
                }
            });

            deleteForm?.addEventListener('submit', () => {
                confirmDeleteButton.disabled = true;
                confirmDeleteButton.textContent = 'Menghapus...';
            });

            closeSuccessAlert?.addEventListener('click', () => {
                if (!successAlert) {
                    return;
                }

                successAlert.style.opacity = '0';
                setTimeout(() => successAlert.remove(), 300);
            });
        });
    </script>
</x-app-layout>
