@section('namePage', 'Kalender Ekonomi')

<x-app-layout>
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto flex flex-col gap-4 h-full">
        <!-- Header -->
        <div class="flex justify-between items-center">
            <h1 class="flex items-center gap-3 text-2xl md:text-3xl text-gray-800 dark:text-white font-bold">
                Kalender Ekonomi
                <span class="text-base">
                    <x-tool-tip text="Data akan ditampilkan berdasarkan tanggal dan waktu." />
                </span>
            </h1>

            <!-- Tombol Tambah Kalender -->
            <a href="{{ route('calendar.create') }}"
                class="bg-blue-500 text-center text-white px-4 py-2 rounded-lg text-sm font-bold hover:bg-blue-600 transition">
                <span class="hidden md:block">Tambah Kalender</span>
                <span class="block md:hidden"><i class="fa-solid fa-plus"></i></span>
            </a>
        </div>

        <!-- Tabel Kalender -->
        <div class="overflow-x-auto md:overflow-x-visible">
            <table
                class="min-w-max md:min-w-full divide-y divide-gray-200 dark:divide-gray-700 shadow-md rounded-lg overflow-hidden">
                <thead class="bg-gray-200 dark:bg-gray-800 text-gray-700 dark:text-gray-200">
                    <tr>
                        <th class="px-4 py-3 text-left text-sm font-semibold">Peristiwa</th>
                        <th class="px-4 py-3 text-center text-sm font-semibold">Tanggal</th>
                        <th class="px-4 py-3 text-center text-sm font-semibold">Waktu</th>
                        <th class="px-4 py-3 text-center text-sm font-semibold">Impact</th>
                        <th class="px-4 py-3 text-center text-sm font-semibold">Negara</th>
                        <th class="px-4 py-3 text-center text-sm font-semibold">Previous</th>
                        <th class="px-4 py-3 text-center text-sm font-semibold">Forecast</th>
                        <th class="px-4 py-3 text-center text-sm font-semibold">Actual</th>
                        <th class="px-4 py-3 text-center text-sm font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody
                    class="divide-y divide-gray-500 dark:divide-gray-100 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100">
                    @foreach ($calendars as $index => $calendar)
                        <tr
                            class="transition duration-200 
                        @if (strtolower($calendar->impact) === 'high') bg-blue-300 hover:bg-blue-200 text-gray-800 
                        @else hover:bg-gray-50 dark:hover:bg-gray-800 @endif">
                            <td class="px-4 py-2 text-sm font-semibold">{{ $calendar->figures }}</td>
                            <td class="px-4 py-2 text-center text-sm">
                                {{ \Carbon\Carbon::parse($calendar->date)->format('d M Y') }}
                            </td>
                            <td class="px-4 py-2 text-center text-sm">
                                {{ $calendar->time }}
                            </td>
                            <td
                                class="px-4 py-2 text-center text-sm font-bold
                            @if (strtolower($calendar->impact) === 'high') text-red-800
                            @elseif(strtolower($calendar->impact) === 'medium') text-yellow-600 dark:text-yellow-400
                            @elseif(strtolower($calendar->impact) === 'low') text-green-600 dark:text-green-400 @endif">
                                @if (strtolower($calendar->impact) === 'high')
                                    <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i
                                        class="fa-solid fa-star"></i>
                                @elseif(strtolower($calendar->impact) === 'medium')
                                    <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
                                @elseif(strtolower($calendar->impact) === 'low')
                                    <i class="fa-solid fa-star"></i>
                                @endif
                            </td>
                            <td class="px-4 py-2 text-center text-sm">
                                <div class="flex items-center justify-center gap-2">
                                    @php
                                        $currencyToCountry = [
                                            'US' => 'US',
                                            'EUR' => 'EU',
                                            'JPY' => 'JP',
                                            'GBP' => 'GB',
                                            'AUD' => 'AU',
                                            'CAD' => 'CA',
                                            'CHF' => 'CH',
                                            'CHN' => 'CN',
                                            'HKD' => 'HK',
                                            'IDN' => 'ID',
                                        ];
                                        $currencyCode = strtoupper($calendar->country);
                                        $countryCode = $currencyToCountry[$currencyCode] ?? 'unknown';
                                    @endphp
                                    @if ($countryCode !== 'unknown')
                                        <img src="https://flagsapi.com/{{ $countryCode }}/shiny/24.png">
                                    @endif
                                    {{ $currencyCode }}
                                </div>
                            </td>
                            <td class="px-4 py-2 text-center text-sm">
                                {{ $calendar->previous ?? '-' }}
                            </td>
                            <td class="px-4 py-2 text-center text-sm">
                                {{ $calendar->forecast ?? '-' }}
                            </td>
                            <td
                                class="px-4 py-2 text-center text-sm
                            @php
if (!function_exists('parseEconomicValue')) {
                                function parseEconomicValue($value) {
                                    if (empty($value)) return null;
                                    if (preg_match('/(-?\d+(\.\d+)?)([KMB%]?)/i', $value, $matches)) {
                                        $number = floatval($matches[1]);
                                        $suffix = strtoupper($matches[3]);
                                        return match ($suffix) {
                                            'K' => $number * 1000,
                                            'M' => $number * 1000000,
                                            'B' => $number * 1000000000,
                                            default => $number,
                                        };
                                    }
                                    return null;
                                }
                            }
                            $actual = parseEconomicValue($calendar->actual ?? null);
                            $previous = parseEconomicValue($calendar->previous ?? null); @endphp
            
                            @if (is_numeric($actual) && is_numeric($previous)) {{ $actual > $previous ? 'text-green-800 font-semibold' : ($actual < $previous ? 'text-red-900' : 'text-gray-700 dark:text-gray-300') }}
                            @else
                                text-gray-700 dark:text-gray-300 @endif">
                                {{ $calendar->actual ?? '-' }}
                            </td>
                            <td class="px-4 py-2 text-center text-sm flex justify-center gap-2">
                                <a href="{{ route('calendar.show', $calendar->id) }}"
                                    class="w-full bg-green-400 hover:bg-green-500 text-black px-3 py-1 rounded text-sm">Detail</a>
                                <a href="{{ route('calendar.edit', $calendar->id) }}"
                                    class="w-full bg-yellow-400 hover:bg-yellow-500 text-black px-3 py-1 rounded text-sm">Edit</a>
                                <button type="button" onclick="openDeleteModal({{ $calendar->id }})"
                                    class="w-full bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm cursor-pointer">
                                    Hapus
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Modal Konfirmasi Hapus -->
            <div id="deleteModal" class="fixed inset-0 z-50 hidden bg-gray-900/50 flex items-center justify-center">
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg w-full max-w-sm">
                    <h2 class="text-lg font-semibold mb-4 text-gray-800 dark:text-white">Konfirmasi Hapus</h2>
                    <p class="text-sm text-gray-700 dark:text-gray-300 mb-4">Apakah kamu yakin ingin menghapus data ini?
                    </p>
                    <form id="deleteForm" method="POST">
                        @csrf
                        @method('DELETE')
                        <div class="flex justify-end gap-2">
                            <button type="button" onclick="closeDeleteModal()"
                                class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded cursor-pointer">Batal</button>
                            <button type="submit"
                                class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded cursor-pointer">Hapus</button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>

    <script>
        let deleteForm = document.getElementById('deleteForm');
        let deleteModal = document.getElementById('deleteModal');

        function openDeleteModal(id) {
            deleteForm.action = `{{ url('/kalender') }}/${id}/delete`;
            deleteModal.classList.remove('hidden');
        }

        function closeDeleteModal() {
            deleteModal.classList.add('hidden');
        }
    </script>
</x-app-layout>
