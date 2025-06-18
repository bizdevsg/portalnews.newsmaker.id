<x-app-layout>
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">
        <div class="card p-6 bg-white rounded shadow">
            <form id="pivotForm" action="{{ route('ebook.update', $ebook->id) }}" method="POST"
                enctype="multipart/form-data" class="space-y-4">
                @csrf
                @method('PUT')

                <div class="flex justify-between items-center mb-5">
                    <button type="button" onclick="openBackModal()"
                        class="inline-flex items-center gap-2 bg-gray-200 dark:bg-gray-700 hover:bg-gray-400 dark:hover:bg-gray-600 py-2 px-6 rounded-lg text-gray-600 dark:text-gray-200 hover:text-gray-800 dark:hover:text-gray-100 cursor-pointer">
                        <i class="fa-solid fa-chevron-left"></i>
                        <span class="hidden md:block">Kembali</span>
                    </button>

                    <h2 class="text-xl font-semibold mb-4">Edit Buku</h2>

                    <button type="button" onclick="openSubmitModal()"
                        class="inline-flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white py-2 px-6 rounded-lg font-semibold transition cursor-pointer">
                        <i class="fa-solid fa-pen-to-square"></i>
                        <span class="hidden md:block">Update</span>
                    </button>
                </div>

                {{-- Judul --}}
                <div class="w-full">
                    <label for="judul" class="block font-medium">Judul Buku</label>
                    <input type="text" name="judul" id="judul" value="{{ old('judul', $ebook->judul) }}"
                        class="w-full border rounded p-2 @error('judul') border-red-500 @enderror">
                    @error('judul')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Deskripsi --}}
                <div class="w-full">
                    <label for="deskripsi" class="block font-medium">Deskripsi Buku</label>
                    <textarea name="deskripsi" id="deskripsi" rows="4"
                        class="w-full border rounded p-2 @error('deskripsi') border-red-500 @enderror">{{ old('deskripsi', $ebook->deskripsi) }}</textarea>
                    @error('deskripsi')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Cover & File --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="cover_image" class="block font-medium">
                            Gambar cover buku
                            <span class="relative group cursor-pointer inline-block ml-1 text-blue-600">
                                <i class="fas fa-info-circle"></i>
                                <div
                                    class="absolute bottom-full mb-2 hidden group-hover:block bg-gray-800 text-white text-xs rounded py-2 px-3 w-max max-w-xs z-10 shadow-lg">
                                    Format file yang diperbolehkan: JPG, JPEG, PNG.<br>
                                    Ukuran maksimal: 2MB.<br>
                                    Ukuran gambar: A4 (Potrait).
                                </div>
                            </span>
                        </label>
                        <input type="file" name="cover_image" id="cover_image"
                            class="w-full border rounded p-2 @error('cover_image') border-red-500 @enderror">
                        @error('cover_image')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="file_ebook" class="block font-medium">
                            File e-Book
                            <span class="relative group cursor-pointer inline-block ml-1 text-blue-600">
                                <i class="fas fa-info-circle"></i>
                                <div
                                    class="absolute bottom-full mb-2 hidden group-hover:block bg-gray-800 text-white text-xs rounded py-2 px-3 w-max max-w-xs z-10 shadow-lg">
                                    Format file yang diperbolehkan: PDF.<br>
                                    Ukuran maksimal: 10MB.
                                </div>
                            </span>
                        </label>
                        <input type="file" name="file_ebook" id="file_ebook"
                            class="w-full border rounded p-2 @error('file_ebook') border-red-500 @enderror">
                        @error('file_ebook')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Penulis --}}
                <div class="w-full">
                    <label for="penulis" class="block font-medium">Penulis</label>
                    <input type="text" name="penulis" id="penulis" value="{{ old('penulis', $ebook->penulis) }}"
                        class="w-full border rounded p-2 @error('penulis') border-red-500 @enderror">
                    @error('penulis')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Tahun Terbit --}}
                <div class="w-full">
                    <label for="tahun_terbit" class="block font-medium">Tahun Terbit</label>
                    <input type="text" name="tahun_terbit" id="tahun_terbit"
                        value="{{ old('tahun_terbit', $ebook->tahun_terbit) }}"
                        class="w-full border rounded p-2 @error('tahun_terbit') border-red-500 @enderror" min="1000"
                        max="{{ date('Y') }}">
                    @error('tahun_terbit')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Konfirmasi Simpan -->
    <div id="submitModal" class="fixed inset-0 bg-black/50 backdrop-blur flex items-center justify-center hidden z-50">
        <div class="bg-white rounded p-6 w-full max-w-md">
            <h3 class="text-lg font-semibold mb-4">Konfirmasi Simpan</h3>
            <p class="mb-6">Apakah Anda yakin ingin menyimpan perubahan buku ini?</p>
            <div class="flex justify-end gap-4">
                <button onclick="closeSubmitModal()"
                    class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Batal</button>
                <button onclick="submitForm()" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">Ya,
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