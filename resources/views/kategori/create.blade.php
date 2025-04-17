<x-app-layout>
    <div class="mx-4 sm:mx-6 lg:mx-8 my-8 bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
        {{-- Form Tambah Kategori --}}
        <form action="{{route('kategori.store')}}" method="POST" enctype="multipart/form-data">
            <div class="inline-flex items-center justify-between w-full mb-7">
                {{-- Tombol Kembali --}}
                <div class="text-left">
                    <button type="button" onclick="toggleModal('modalKembali')"
                        class="bg-gray-200 dark:bg-gray-600 hover:bg-gray-300 dark:hover:bg-gray-500 py-2 px-6 rounded-lg text-gray-600 hover:text-gray-800 dark:text-white">
                        <i class="fa-solid fa-chevron-left"></i>
                        <span>Kembali</span>
                    </button>
                </div>

                <div class="flex justify-between items-center">
                    <!-- Title -->
                    <h1 class="text-2xl md:text-3xl text-gray-800 dark:text-gray-100 font-bold">Kategori Berita</h1>
                </div>

                {{-- Tombol Tambah --}}
                <div class="text-right">
                    <button type="button" onclick="toggleModal('modalSubmit')"
                        class="bg-blue-600 hover:bg-blue-700 text-white py-2 px-6 rounded-lg font-semibold transition">
                        <i class="fa-solid fa-plus"></i> <span>Tambah
                        </span>
                    </button>
                </div>
            </div>

            {{-- Token CSRF --}}
            @csrf

            {{-- Input Nama Kategori --}}
            <div class="">
                <label for="name" class="block text-gray-700 dark:text-white font-medium mb-1 transition">Nama Kategori
                    <small class="text-red-500">(Maks. 20 Karakter)</small>
                </label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" class="w-full dark:bg-transparent dark:text-white px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none 
                    @error('name') is-invalid @enderror" placeholder="Masukkan nama kategori..." required>

                {{-- Tampilkan Pesan Error --}}
                @error('name')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Modal Submit --}}
            <div id="modalSubmit"
                class="hidden fixed inset-0 bg-gray-900/50 flex items-center justify-center px-3 z-100">
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg text-gray-900 dark:text-gray-300">
                    <h2 class="text-lg font-semibold mb-4">Konfirmasi</h2>
                    <p>Apakah Anda yakin ingin menambahkan kategori ini?</p>
                    <div class="flex justify-end mt-4">
                        <button onclick="toggleModal('modalSubmit')"
                            class="mr-2 bg-gray-400 hover:bg-gray-500 dark:bg-gray-600 dark:hover:bg-gray-700 text-white py-2 px-4 rounded-lg">
                            Batal
                        </button>
                        <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 text-white py-2 px-4 rounded-lg">
                            Ya, Tambahkan
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    {{-- Modal Kembali --}}
    <div id="modalKembali" class="hidden fixed inset-0 bg-gray-900/50 flex items-center justify-center px-3 z-100">
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg text-gray-900 dark:text-gray-300">
            <h2 class="text-lg font-semibold mb-4">Konfirmasi</h2>
            <p>Apakah Anda yakin ingin kembali? Perubahan tidak akan disimpan.</p>
            <div class="flex justify-end mt-4">
                <button onclick="toggleModal('modalKembali')"
                    class="mr-2 bg-gray-400 hover:bg-gray-500 dark:bg-gray-600 dark:hover:bg-gray-700 text-white py-2 px-4 rounded-lg">
                    Batal
                </button>
                <a href="{{ route('kategori.index') }}"
                    class="bg-red-600 hover:bg-red-700 dark:bg-red-500 dark:hover:bg-red-600 text-white py-2 px-4 rounded-lg">
                    Ya, Kembali
                </a>
            </div>
        </div>
    </div>


    {{-- Script Modal --}}
    <script>
        function toggleModal(id) {
        const modal = document.getElementById(id);
        if (modal.classList.contains('hidden')) {
            modal.classList.remove('hidden');
        } else {
            modal.classList.add('hidden');
        }
    }
    </script>
</x-app-layout>