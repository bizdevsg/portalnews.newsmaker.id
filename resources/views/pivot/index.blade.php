@section('namePage', 'Pivot & Fibonnaci')

@php
    $categories = [
        'LGD Daily',
        'BCO Daily',
        'HSI Daily',
        'SNI Daily',
        'AUD/USD',
        'EUR/USD',
        'GBP/USD',
        'USD/CHF',
        'USD/JPY',
    ];

    $selectedCategory = request('category', $category ?? 'LGD Daily');

    $formatPivotDate = function ($date) {
        if (!filled($date)) {
            return '-';
        }

        return \Carbon\Carbon::parse($date)->translatedFormat('d M Y');
    };

    $totalRows = $pivots->count();
    $holidayCount = $pivots->where('isBankHoliday', true)->count();
    $normalCount = $totalRows - $holidayCount;
    $latestPivot = $pivots->first();
    $oldestPivot = $pivots->last();
    $deleteRouteTemplate = route('pivot.destroy', ['id' => '__ID__']);
@endphp

<x-app-layout>
    <div class="mx-auto flex w-full flex-col gap-6 px-4 py-8 sm:px-6 lg:px-8">
        @if (session('success'))
            <div
                class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700 dark:border-emerald-500/20 dark:bg-emerald-500/10 dark:text-emerald-200">
                {{ session('success') }}
            </div>
        @endif

<<<<<<< Updated upstream
        <!-- Card -->
        <div class="bg-white dark:bg-gray-800 mt-5 rounded shadow-lg">
            <!-- Kategori -->
            @php
                // Hapus "Semua"
                $categories = [
                    'LGD Daily',
                    'LSI',
                    'HSI Daily',
                    'SNI Daily',
                    'AUD/USD',
                    'EUR/USD',
                    'GBP/USD',
                    'USD/CHF',
                    'USD/JPY',
                ];
=======
        <section
            class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-950">
            <div class="flex flex-col gap-4 md:flex-row md:items-start md:justify-between">
                <div class="min-w-0">
                    <h1 class="text-2xl font-semibold text-slate-900 dark:text-white sm:text-3xl">
                        Historical Data
                    </h1>
                    <p class="mt-2 max-w-2xl text-sm text-slate-500 dark:text-slate-400">
                        Kelola data pivot berdasarkan kategori dengan tampilan yang lebih sederhana dan lebih stabil di
                        mobile maupun desktop.
                    </p>
>>>>>>> Stashed changes

                    <div class="mt-4 flex flex-wrap gap-2 text-xs font-medium text-slate-600 dark:text-slate-300">
                        <span class="rounded-full border border-slate-200 px-3 py-1.5 dark:border-slate-700">
                            Kategori aktif {{ $selectedCategory }}
                        </span>
                        <span class="rounded-full border border-slate-200 px-3 py-1.5 dark:border-slate-700">
                            Data terbaru {{ $formatPivotDate(optional($latestPivot)->tanggal) }}
                        </span>
                        <span class="rounded-full border border-slate-200 px-3 py-1.5 dark:border-slate-700">
                            Data terlama {{ $formatPivotDate(optional($oldestPivot)->tanggal) }}
                        </span>
                    </div>
                </div>

                <a href="{{ route('pivot.create', ['category' => $selectedCategory]) }}"
                    class="inline-flex items-center justify-center gap-2 rounded-xl bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-slate-800 dark:bg-slate-100 dark:text-slate-900 dark:hover:bg-white">
                    <i class="fa-solid fa-plus"></i>
                    Tambah History
                </a>
            </div>
        </section>

        <section class="grid gap-3 sm:grid-cols-2 xl:grid-cols-4">
            <div
                class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm dark:border-slate-800 dark:bg-slate-950">
                <p class="text-sm text-slate-500 dark:text-slate-400">Total Data</p>
                <p class="mt-2 text-2xl font-semibold text-slate-900 dark:text-white">{{ number_format($totalRows) }}
                </p>
            </div>
            <div
                class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm dark:border-slate-800 dark:bg-slate-950">
                <p class="text-sm text-slate-500 dark:text-slate-400">Data Normal</p>
                <p class="mt-2 text-2xl font-semibold text-slate-900 dark:text-white">{{ number_format($normalCount) }}
                </p>
            </div>
            <div
                class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm dark:border-slate-800 dark:bg-slate-950">
                <p class="text-sm text-slate-500 dark:text-slate-400">Bank Holiday</p>
                <p class="mt-2 text-2xl font-semibold text-slate-900 dark:text-white">{{ number_format($holidayCount) }}
                </p>
            </div>
            <div
                class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm dark:border-slate-800 dark:bg-slate-950">
                <p class="text-sm text-slate-500 dark:text-slate-400">Kategori Tersedia</p>
                <p class="mt-2 text-2xl font-semibold text-slate-900 dark:text-white">{{ count($categories) }}</p>
            </div>
        </section>

        <section class="rounded-xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-950">
            <div class="border-b border-slate-200 p-5 dark:border-slate-800">
                <h2 class="text-lg font-semibold text-slate-900 dark:text-white">Filter Kategori</h2>
                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                    Pilih kategori untuk melihat data historical yang sesuai.
                </p>
            </div>

            <div class="flex gap-2 overflow-x-auto p-5">
                @foreach ($categories as $cat)
                    <a href="{{ route('pivot.index', ['category' => $cat]) }}"
                        class="inline-flex shrink-0 items-center rounded-full px-3 py-1.5 text-sm font-medium transition {{ $selectedCategory === $cat
                            ? 'bg-slate-900 text-white dark:bg-slate-100 dark:text-slate-900'
                            : 'border border-slate-200 text-slate-700 hover:bg-slate-50 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-900' }}">
                        {{ $cat }}
                    </a>
                @endforeach
            </div>
        </section>

<<<<<<< Updated upstream
            <!-- Table -->
            <div class="p-4 overflow-x-auto">
                <table class="min-w-full table-auto border-collapse rounded-lg overflow-hidden">
                    <thead class="bg-gray-200 dark:bg-gray-600">
                        <tr>
                            <th
                                class="px-4 py-2 text-center text-xs font-medium text-gray-600 dark:text-gray-300 uppercase">
                                Tanggal</th>
                            <th
                                class="px-4 py-2 text-center text-xs font-medium text-gray-600 dark:text-gray-300 uppercase">
                                Open</th>
                            <th
                                class="px-4 py-2 text-center text-xs font-medium text-gray-600 dark:text-gray-300 uppercase">
                                High</th>
                            <th
                                class="px-4 py-2 text-center text-xs font-medium text-gray-600 dark:text-gray-300 uppercase">
                                Low</th>
                            <th
                                class="px-4 py-2 text-center text-xs font-medium text-gray-600 dark:text-gray-300 uppercase">
                                Close</th>
                            @if($selectedCategory === 'HSI Daily' || $selectedCategory === 'SNI Daily')
                            <th
                                class="px-4 py-2 text-center text-xs font-medium text-gray-600 dark:text-gray-300 uppercase">
                                Chg.</th>
                            <th
                                class="px-4 py-2 text-center text-xs font-medium text-gray-600 dark:text-gray-300 uppercase">
                                Volume</th>
                            @if($selectedCategory === 'HSI Daily')
                            <th
                                class="px-4 py-2 text-center text-xs font-medium text-gray-600 dark:text-gray-300 uppercase">
                                Open Interest</th>
                            @endif
                            @endif
                            <th
                                class="px-4 py-2 text-center text-xs font-medium text-gray-600 dark:text-gray-300 uppercase">
                                Kategori</th>
                            <th
                                class="px-4 py-2 text-center text-xs font-medium text-gray-600 dark:text-gray-300 uppercase">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse ($pivots as $index => $pivot)
                            <tr
                                class="{{ $index % 2 === 0 ? 'bg-white dark:bg-gray-900' : 'bg-gray-100 dark:bg-gray-800' }}">
                                <td class="px-4 py-2 text-center text-gray-800 dark:text-gray-100">
                                    {{ \Carbon\Carbon::parse($pivot->tanggal)->translatedFormat('d F Y') }}
                                </td>
                                <td class="px-4 py-2 text-center text-gray-800 dark:text-gray-100">
                                    {{ $pivot->open }}
                                </td>
                                <td class="px-4 py-2 text-center text-gray-800 dark:text-gray-100">
                                    {{ $pivot->high }}
                                </td>
                                <td class="px-4 py-2 text-center text-gray-800 dark:text-gray-100">
                                    {{ $pivot->low }}
                                </td>
                                <td class="px-4 py-2 text-center text-gray-800 dark:text-gray-100">
                                    {{ $pivot->close }}
                                </td>
                                @if($selectedCategory === 'HSI Daily' || $selectedCategory === 'SNI Daily')
                                <td class="px-4 py-2 text-center text-gray-800 dark:text-gray-100">
                                    {{ $pivot->chg }}
                                </td>
                                <td class="px-4 py-2 text-center text-gray-800 dark:text-gray-100">
                                    {{ $pivot->volume }}
                                </td>
                                @if($selectedCategory === 'HSI Daily')
                                <td class="px-4 py-2 text-center text-gray-800 dark:text-gray-100">
                                    {{ $pivot->open_interest }}
                                </td>
                                @endif
                                @endif
                                <td class="px-4 py-2 text-center text-gray-800 dark:text-gray-100">
                                    {{ $pivot->category }}
                                </td>
                                <td class="px-4 py-2 text-center">
                                    <div class="flex gap-2">
                                        <a href="{{ route('pivot.edit', $pivot->id) }}"
                                            class="w-1/2 bg-blue-500 text-white py-1 rounded hover:bg-blue-600 text-sm font-semibold text-center">Edit</a>
                                        <button type="button" onclick="openDeleteModal({{ $pivot->id }})"
                                            class="w-1/2 bg-red-500 text-white py-1 rounded hover:bg-red-600 text-sm font-semibold text-center">Hapus</button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ $selectedCategory === 'HSI Daily' ? '10' : ($selectedCategory === 'SNI Daily' ? '9' : '7') }}" class="px-4 py-4 text-center text-gray-500 dark:text-gray-400">Data
                                    belum
                                    tersedia.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
=======
        <section class="rounded-xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-950">
            <div
                class="flex flex-col gap-3 border-b border-slate-200 p-5 dark:border-slate-800 md:flex-row md:items-center md:justify-between">
                <div>
                    <h2 class="text-lg font-semibold text-slate-900 dark:text-white">Daftar Historical Data</h2>
                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                        {{ $selectedCategory }} menampilkan {{ number_format($totalRows) }} data.
                    </p>
                </div>

                <div class="flex flex-wrap gap-2 text-xs font-medium">
                    <span
                        class="rounded-full bg-slate-100 px-3 py-1.5 text-slate-700 dark:bg-slate-800 dark:text-slate-200">
                        Total {{ $totalRows }}
                    </span>
                    <span
                        class="rounded-full bg-emerald-50 px-3 py-1.5 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-200">
                        Normal {{ $normalCount }}
                    </span>
                    <span
                        class="rounded-full bg-rose-50 px-3 py-1.5 text-rose-700 dark:bg-rose-500/10 dark:text-rose-200">
                        Holiday {{ $holidayCount }}
                    </span>
                </div>
            </div>

            @if ($totalRows === 0)
                <div class="p-6 text-center sm:p-10">
                    <div
                        class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-slate-100 text-slate-500 dark:bg-slate-800 dark:text-slate-300">
                        <i class="fa-solid fa-chart-line"></i>
                    </div>
                    <h3 class="mt-4 text-lg font-semibold text-slate-900 dark:text-white">Belum ada historical data</h3>
                    <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">
                        Tambahkan data pertama untuk kategori {{ $selectedCategory }}.
                    </p>
                </div>
            @else
                <div class="grid gap-4 p-4 lg:hidden">
                    @foreach ($pivots as $pivot)
                        <article class="rounded-2xl border border-slate-200 p-4 dark:border-slate-800">
                            <div class="flex items-start justify-between gap-3">
                                <div>
                                    <p class="text-base font-semibold text-slate-900 dark:text-white">
                                        {{ $formatPivotDate($pivot->tanggal) }}
                                    </p>
                                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">{{ $pivot->category }}
                                    </p>
                                </div>
                            </div>

                            @if ($pivot->isBankHoliday)
                                <div
                                    class="mt-4 rounded-xl bg-slate-50 p-4 text-sm leading-6 text-slate-700 dark:bg-slate-900 dark:text-slate-200">
                                    <p
                                        class="mb-2 text-xs font-semibold uppercase tracking-wide text-rose-600 dark:text-rose-300">
                                        Bank Holiday
                                    </p>
                                    {{ $pivot->description ?: 'Tidak ada pergerakan harga karena bank holiday.' }}
                                </div>
                            @else
                                <div class="mt-4 grid grid-cols-2 gap-3">
                                    <div class="rounded-xl bg-slate-50 p-3 dark:bg-slate-900">
                                        <p class="text-xs uppercase tracking-wide text-slate-400">Open</p>
                                        <p class="mt-2 text-sm font-semibold text-slate-900 dark:text-white">
                                            {{ $pivot->open ?: '-' }}</p>
                                    </div>
                                    <div class="rounded-xl bg-slate-50 p-3 dark:bg-slate-900">
                                        <p class="text-xs uppercase tracking-wide text-slate-400">High</p>
                                        <p class="mt-2 text-sm font-semibold text-slate-900 dark:text-white">
                                            {{ $pivot->high ?: '-' }}</p>
                                    </div>
                                    <div class="rounded-xl bg-slate-50 p-3 dark:bg-slate-900">
                                        <p class="text-xs uppercase tracking-wide text-slate-400">Low</p>
                                        <p class="mt-2 text-sm font-semibold text-slate-900 dark:text-white">
                                            {{ $pivot->low ?: '-' }}</p>
                                    </div>
                                    <div class="rounded-xl bg-slate-50 p-3 dark:bg-slate-900">
                                        <p class="text-xs uppercase tracking-wide text-slate-400">Close</p>
                                        <p class="mt-2 text-sm font-semibold text-slate-900 dark:text-white">
                                            {{ $pivot->close ?: '-' }}</p>
                                    </div>
                                </div>
                            @endif

                            <div class="mt-4 flex gap-3">
                                <a href="{{ route('pivot.edit', $pivot->id) }}"
                                    class="inline-flex flex-1 items-center justify-center rounded-xl border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-50 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-900">
                                    Edit
                                </a>
                                <button type="button"
                                    onclick="openDeleteModal({{ $pivot->id }}, @js($formatPivotDate($pivot->tanggal)))"
                                    class="inline-flex flex-1 items-center justify-center rounded-xl bg-rose-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-rose-700">
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
                                class="text-center text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">
                                <th class="w-px px-4 py-4 text-left whitespace-nowrap">Tanggal</th>
                                <th class="px-5 py-4">Open</th>
                                <th class="px-5 py-4">High</th>
                                <th class="px-5 py-4">Low</th>
                                <th class="px-5 py-4">Close</th>
                                <th class="w-px px-4 py-4 text-right whitespace-nowrap">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                            @foreach ($pivots as $pivot)
                                <tr class="align-middle">
                                    <td
                                        class="w-px px-4 py-4 text-sm font-medium text-slate-900 whitespace-nowrap dark:text-white">
                                        {{ $formatPivotDate($pivot->tanggal) }}
                                    </td>
                                    @if ($pivot->isBankHoliday)
                                        <td colspan="4" class="px-5 py-4">
                                            <div
                                                class="bg-red-100 px-3 py-1 border border-red-200 rounded-full text-center max-w-5xl mx-auto">
                                                <p class="text-sm font-semibold text-slate-800 dark:text-slate-300">
                                                    Bank Holiday
                                                </p>
                                                <p class="text-xs text-slate-500 dark:text-slate-300">
                                                    ~
                                                    {{ $pivot->description ?: 'Tidak ada pergerakan harga karena bank holiday.' }}
                                                    ~
                                                </p>
                                            </div>
                                        </td>
                                    @else
                                        <td class="px-5 py-4 text-sm text-center text-slate-700 dark:text-slate-200">
                                            {{ $pivot->open ?: '-' }}
                                        </td>
                                        <td class="px-5 py-4 text-sm text-center text-slate-700 dark:text-slate-200">
                                            {{ $pivot->high ?: '-' }}
                                        </td>
                                        <td class="px-5 py-4 text-sm text-center text-slate-700 dark:text-slate-200">
                                            {{ $pivot->low ?: '-' }}
                                        </td>
                                        <td class="px-5 py-4 text-sm text-center text-slate-700 dark:text-slate-200">
                                            {{ $pivot->close ?: '-' }}
                                        </td>
                                    @endif
                                    <td class="w-px px-4 py-4 whitespace-nowrap">
                                        <div class="flex justify-end gap-2">
                                            <a href="{{ route('pivot.edit', $pivot->id) }}"
                                                class="inline-flex items-center justify-center rounded-lg border border-slate-200 px-3 py-1.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-900">
                                                Edit
                                            </a>
                                            <button type="button"
                                                onclick="openDeleteModal({{ $pivot->id }}, @js($formatPivotDate($pivot->tanggal)))"
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
>>>>>>> Stashed changes
    </div>

    <div id="deleteModal" data-modal
        class="fixed inset-0 z-50 hidden flex items-center justify-center bg-slate-950/50 px-4">
        <div
            class="w-full max-w-md rounded-2xl border border-slate-200 bg-white p-6 shadow-xl dark:border-slate-800 dark:bg-slate-950">
            <h3 class="text-lg font-semibold text-slate-900 dark:text-white">Hapus data historical?</h3>
            <p id="deleteModalText" class="mt-2 text-sm leading-6 text-slate-500 dark:text-slate-400">
                Data yang dipilih akan dihapus permanen.
            </p>
            <div class="mt-6 flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
                <button type="button" onclick="closeDeleteModal()"
                    class="inline-flex items-center justify-center rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-900">
                    Batal
                </button>
                <form id="deleteForm" action="" method="POST">
                    @csrf
                    @method('DELETE')
<<<<<<< Updated upstream
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">Ya, Hapus</button>
=======
                    <button type="submit"
                        class="inline-flex w-full items-center justify-center rounded-xl bg-rose-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-rose-700">
                        Ya, hapus
                    </button>
>>>>>>> Stashed changes
                </form>
            </div>
        </div>
    </div>

    <script>
        const deleteRouteTemplate = @json($deleteRouteTemplate);

<<<<<<< Updated upstream
        function openDeleteModal(id) {
            document.getElementById('deleteForm').action = `/pivot-fibonacci/${id}/delete`;
            document.getElementById('deleteModal').classList.remove('hidden');
=======
        function openDeleteModal(id, label) {
            const deleteForm = document.getElementById('deleteForm');
            const deleteModal = document.getElementById('deleteModal');
            const deleteModalText = document.getElementById('deleteModalText');

            if (deleteForm) {
                deleteForm.action = deleteRouteTemplate.replace('__ID__', id);
            }

            if (deleteModalText) {
                deleteModalText.textContent = `Data tanggal ${label} akan dihapus permanen.`;
            }

            deleteModal?.classList.remove('hidden');
>>>>>>> Stashed changes
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
