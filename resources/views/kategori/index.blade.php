<x-app-layout>
    <div class="px-4 sm:px-6 lg:px-8 pt-8 pb-4 w-full max-w-9xl mx-auto">
        <!-- Dashboard actions -->
        <div class="flex justify-between items-center">
            <!-- Title -->
            <h1 class="text-2xl md:text-3xl text-gray-800 dark:text-gray-100 font-bold">Kategori Berita</h1>

            {{-- Button Tambah --}}
            <a href="{{route('kategori.create')}}"
                class="bg-blue-500 text-center text-white px-4 py-2 rounded-lg text-sm font-bold hover:bg-blue-600 transition">
                Tambah Kategori
            </a>
        </div>
    </div>

    {{-- Alert --}}
    @if (session('success'))
    <div id="successAlert" class="w-full max-w-9xl mx-auto px-4 sm:px-6 lg:px-8">
        <div
            class="border-l-4 border-green-600 p-4 mb-6 rounded-lg bg-green-100 dark:bg-green-800 flex items-center justify-between shadow-md transition-opacity duration-300">
            <div class="flex items-center gap-2 text-green-800 dark:text-green-300 text-sm sm:text-base">
                <i class="fa-solid fa-circle-check text-lg"></i>
                <span>{{ session('success') }}</span>
            </div>
            <button onclick="closeAlert()"
                class="p-1 text-green-800 dark:text-green-300 hover:text-green-900 dark:hover:text-green-400 transition">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>
    </div>
    @endif

    {{-- Grid Berita --}}
    <div class="px-4 sm:px-6 lg:px-8 py-4 w-full max-w-9xl mx-auto">
        @if ($categories->isEmpty())
        <div
            class="flex flex-col justify-center items-center mx-4 sm:mx-6 lg:mx-8 p-10 rounded-lg gap-3 bg-gray-200 dark:bg-gray-800 dark:text-gray-300 transition">
            <img src="{{ asset('assets/hand-drawn-no-data-concept.png') }}" alt="No Data" class="h-50 rounded-lg">
            <p class="text-gray-600 text-xl">Belum ada kategori berita.</p>
        </div>
        @else
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-5 gap-6">
            @foreach($categories as $kategori)
            <div
                class="border-blue-500 border-l-4 flex flex-col justify-between items-center p-5 bg-white dark:bg-gray-800 rounded-lg shadow-md gap-4 transition">

                <!-- Atas: Konten -->
                <div class="w-full flex flex-col gap-1">
                    <h1 class="text-xl font-semibold text-gray-800 dark:text-gray-200">{{ $kategori->name }}</h1>
                    <p class="text-gray-600 dark:text-gray-400 text-base">{{ $kategori->berita_count }} Berita</p>
                </div>

                <!-- Bawah: Tombol Aksi -->
                <div class="w-full flex gap-2">
                    <a href="{{route('berita.index', $kategori->slug)}}"
                        class="w-full bg-yellow-500 text-center text-white px-4 py-2 rounded-lg text-sm font-bold hover:bg-yellow-600 transition">
                        Lihat
                    </a>
                    <a href="{{route('kategori.edit', $kategori->id)}}"
                        class="w-full bg-green-500 text-white px-4 py-2 rounded-lg text-center text-sm font-bold hover:bg-green-600 transition">
                        Edit
                    </a>
                    <button onclick="showDeleteModal({{ $kategori->id }}, '{{ $kategori->name }}')"
                        class="w-full bg-red-500 text-white dark:bg-red-700 dark:text-gray-200 px-4 py-2 rounded-lg text-sm font-bold hover:bg-red-700 dark:hover:bg-red-600 transition cursor-pointer">
                        Hapus
                    </button>
                </div>
            </div>
            @endforeach
        </div>
        @endif

        <!-- Modal Konfirmasi Hapus -->
        <div id="deleteModal"
            class="fixed inset-0 bg-black/50 flex justify-center items-center hidden z-100 dark:bg-gray-900/75">
            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg w-full max-w-md">
                <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">Konfirmasi Hapus</h2>
                <p class="text-gray-600 dark:text-gray-200">Apakah Anda yakin ingin menghapus kategori <b
                        id="categoryName"></b>?</p>
                <form id="deleteForm" method="POST" class="mt-4">
                    @csrf
                    @method('DELETE')
                    <div class="flex justify-end gap-2">
                        <button type="button" onclick="hideDeleteModal()"
                            class="px-4 py-2 bg-gray-300 dark:bg-gray-600 text-gray-800 dark:text-gray-200 rounded-lg hover:bg-gray-400 dark:hover:bg-gray-700 transition">
                            Batal
                        </button>
                        <button type="submit"
                            class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 dark:bg-red-700 dark:hover:bg-red-600 transition">
                            Hapus
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <script>
            function showDeleteModal(id, name) {
                const modal = document.getElementById("deleteModal");
                const form = document.getElementById("deleteForm");
                const categoryName = document.getElementById("categoryName");

                form.action = `{{ route('kategori.destroy', ':id') }}`.replace(':id', id);
                categoryName.textContent = name; // Menampilkan nama kategori di modal

                modal.classList.remove("hidden");
            }

            function hideDeleteModal() {
                document.getElementById("deleteModal").classList.add("hidden");
            }
        </script>
        <script>
            function closeAlert() {
                const alert = document.getElementById("successAlert");
                if (alert) {
                    alert.style.opacity = "0";
                    setTimeout(() => alert.remove(), 300);
                }
            }
        </script>
</x-app-layout>