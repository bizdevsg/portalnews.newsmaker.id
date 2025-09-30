@section('namePage', 'Pivot & Fibonnaci')

<x-app-layout>
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">
        <!-- Header -->
        <div class="flex justify-between items-center">
            <h1 class="text-2xl md:text-3xl text-gray-800 dark:text-gray-100 font-bold">Historical Data</h1>
            <a href="{{ route('pivot.create') }}"
                class="bg-blue-500 text-white px-4 py-2 rounded-lg text-sm font-bold hover:bg-blue-600 transition">
                Tambah History
            </a>
        </div>

        @if (session('success'))
            <div
                class="mt-4 rounded-lg border-l-2 border-green-600 bg-green-100 dark:bg-green-900/50 p-4 text-green-800 dark:text-green-200">
                <div class="flex items-center space-x-2 font-semibold">
                    <i class="fa-solid fa-circle-check"></i>
                    <span>{{ session('success') }}</span>
                </div>
            </div>
        @endif

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

                // Ambil kategori dari query param, default ke 'LGD Daily'
                $selectedCategory = request('category', 'LGD Daily');
            @endphp

            <div class="p-4 border-b border-gray-200 dark:border-gray-700 flex gap-2 overflow-x-auto">
                @foreach ($categories as $cat)
                    <button data-category="{{ $cat }}"
                        class="category-button cursor-pointer font-semibold py-1 px-3 rounded
                {{ $selectedCategory == $cat ? 'bg-blue-500 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-100 hover:bg-gray-300 dark:hover:bg-gray-600' }}">
                        {{ $cat }}
                    </button>
                @endforeach
            </div>

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
    </div>

    <!-- Modal Konfirmasi Hapus -->
    <div id="deleteModal" class="fixed inset-0 bg-black/50 backdrop-blur flex items-center justify-center hidden z-50">
        <div class="bg-white rounded p-6 w-full max-w-md">
            <h3 class="text-lg font-semibold mb-4">Konfirmasi Hapus</h3>
            <p class="mb-6">Apakah Anda yakin ingin menghapus data ini?</p>
            <div class="flex justify-end gap-4">
                <button onclick="closeDeleteModal()"
                    class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Batal</button>
                <form id="deleteForm" action="" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">Ya, Hapus</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.querySelectorAll('.category-button').forEach(button => {
            button.addEventListener('click', () => {
                const category = button.getAttribute('data-category');
                const url = new URL(window.location.href);
                url.searchParams.set('category', category);
                window.location.href = url.toString();
            });
        });

        function openDeleteModal(id) {
            document.getElementById('deleteForm').action = `/pivot-fibonacci/${id}/delete`;
            document.getElementById('deleteModal').classList.remove('hidden');
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.add('hidden');
        }
    </script>
</x-app-layout>
