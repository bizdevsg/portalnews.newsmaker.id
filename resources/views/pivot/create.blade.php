@section('namePage', 'Tambah Pivot & Fibonnaci')

<x-app-layout>
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">
        <div class="card p-6 bg-white rounded shadow">
            <h2 class="text-xl font-semibold mb-4">Tambah Data Pivot</h2>

            <form id="pivotForm" action="{{ route('pivot.store') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label for="tanggal" class="block font-medium">Tanggal</label>
                    <input type="date" name="tanggal" id="tanggal" value="{{ old('tanggal') }}"
                        class="w-full border rounded p-2 @error('tanggal') border-red-500 @enderror" required>
                    @error('tanggal')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div>
                        <label for="open" class="block font-medium">Open</label>
                        <input type="text" name="open" id="open" value="{{ old('open') }}"
                            class="w-full border rounded p-2 @error('open') border-red-500 @enderror" required>
                        @error('open')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="high" class="block font-medium">High</label>
                        <input type="text" name="high" id="high" value="{{ old('high') }}"
                            class="w-full border rounded p-2 @error('high') border-red-500 @enderror" required>
                        @error('high')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="low" class="block font-medium">Low</label>
                        <input type="text" name="low" id="low" value="{{ old('low') }}"
                            class="w-full border rounded p-2 @error('low') border-red-500 @enderror" required>
                        @error('low')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="close" class="block font-medium">Close</label>
                        <input type="text" name="close" id="close" value="{{ old('close') }}"
                            class="w-full border rounded p-2 @error('close') border-red-500 @enderror" required>
                        @error('close')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label for="category" class="block font-medium">Kategori</label>
                    <select name="category" id="category"
                        class="w-full border rounded p-2 @error('category') border-red-500 @enderror" required>
                        <option value="">-- Pilih Kategori --</option>
                        @foreach (['LGD Daily', 'LSI', 'HSI Daily', 'SNI Daily', 'AUD/USD', 'EUR/USD', 'GBP/USD', 'USD/CHF', 'USD/JPY'] as $kategori)
                            <option value="{{ $kategori }}" {{ old('category') == $kategori ? 'selected' : '' }}>
                                {{ $kategori }}
                            </option>
                        @endforeach
                    </select>
                    @error('category')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex gap-4 mt-6">
                    <button type="button" onclick="openBackModal()"
                        class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
                        Kembali
                    </button>

                    <button type="button" onclick="openSubmitModal()"
                        class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Konfirmasi Simpan -->
    <div id="submitModal" class="fixed inset-0 bg-black/50 backdrop-blur flex items-center justify-center hidden z-50">
        <div class="bg-white rounded p-6 w-full max-w-md">
            <h3 class="text-lg font-semibold mb-4">Konfirmasi Simpan</h3>
            <p class="mb-6">Apakah Anda yakin ingin menyimpan data ini?</p>
            <div class="flex justify-end gap-4">
                <button onclick="closeSubmitModal()"
                    class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Batal</button>
                <button onclick="submitForm()" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Ya,
                    Simpan</button>
            </div>
        </div>
    </div>

    <!-- Modal Konfirmasi Kembali -->
    <div id="backModal" class="fixed inset-0 bg-black/50 backdrop-blur flex items-center justify-center hidden z-50">
        <div class="bg-white rounded p-6 w-full max-w-md">
            <h3 class="text-lg font-semibold mb-4">Konfirmasi Kembali</h3>
            <p class="mb-6">Apakah Anda yakin ingin kembali? Data yang belum disimpan akan hilang.</p>
            <div class="flex justify-end gap-4">
                <button onclick="closeBackModal()"
                    class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Batal</button>
                <a href="{{ route('pivot.index') }}"
                    class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">Ya, Kembali</a>
            </div>
        </div>
    </div>

    <script>
        function openSubmitModal() {
            document.getElementById('submitModal').classList.remove('hidden');
        }

        function closeSubmitModal() {
            document.getElementById('submitModal').classList.add('hidden');
        }

        function submitForm() {
            document.getElementById('pivotForm').submit();
        }

        function openBackModal() {
            document.getElementById('backModal').classList.remove('hidden');
        }

        function closeBackModal() {
            document.getElementById('backModal').classList.add('hidden');
        }
    </script>
</x-app-layout>
