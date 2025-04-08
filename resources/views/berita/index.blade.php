<x-app-layout>
    <div class="px-4 sm:px-6 lg:px-8 pt-8 pb-4 w-full max-w-9xl mx-auto">
        <!-- Dashboard actions -->
        <div class="flex justify-between items-center mb-4">
            <!-- Title -->
            <div class="flex items-center gap-3">
                <a href="{{ route('kategori.index') }}"
                    class="bg-gray-300 hover:bg-gray-400 dark:bg-gray-700 dark:hover:bg-gray-600 px-3 py-2 rounded-lg transition duration-200">
                    <i class="fa-solid fa-xmark text-lg text-gray-600 dark:text-gray-300"></i>
                </a>
                <h1 class="text-2xl md:text-3xl text-gray-800 dark:text-gray-100 font-bold">
                    {{$kategori->name}}
                </h1>
            </div>

            {{-- Button Tambah --}}
            <a href="{{route('berita.create', $kategori->slug)}}"
                class="bg-blue-500 text-white px-4 py-2 rounded-lg text-sm font-bold hover:bg-blue-600 transition duration-200">
                Tambah
            </a>
        </div>

        {{-- Alert --}}
        @if (session('success'))
        <div id="successAlert" class="mx-auto">
            <div
                class="border-l-4 border-green-600 p-4 mb-6 rounded-lg bg-green-100 dark:bg-green-800 flex items-center justify-between shadow-md transition-opacity duration-300">
                <div class="flex items-center gap-2 text-green-800 dark:text-white text-sm sm:text-base">
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
        @if ($beritas->isEmpty())
        <div class="border-l-4 border-amber-500 bg-amber-300/15 shadow-lg p-4 rounded-lg">
            <p class="text-center text-yellow-700">
                <i class="fa-solid fa-triangle-exclamation"></i> Belum ada berita yang dipublikasikan di kategori
                <strong>{{$kategori->name}}</strong>.
            </p>
        </div>
        @else
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach ($beritas as $berita)
            <div
                class="bg-white dark:bg-gray-900 shadow-md rounded-lg overflow-hidden border border-gray-300 dark:border-gray-700 h-full flex flex-col">
                {{-- Gambar berita --}}
                <img src="{{ asset($berita->image1) }}" alt="{{ $berita->image1 }}" class="w-full h-40 object-cover">

                {{-- Konten berita --}}
                <div class="p-4 flex-1 flex flex-col bg-gray-100 dark:bg-gray-800">
                    <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-200">
                        {{ Str::limit($berita['title'] ?? 'Judul tidak tersedia', 55) }}
                    </h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-2 flex-1 line-clamp-2">
                        {!! strip_tags($berita['content']) !!}
                    </p>

                    {{-- Footer berita --}}
                    <div class="flex justify-between items-center mt-4">
                        <span class="text-sm text-gray-400 dark:text-gray-500">
                            {{ \Carbon\Carbon::parse($berita->created_at)->translatedFormat('l, d F Y') }}
                        </span>
                        <div class="flex space-x-2">
                            <a href="{{ route('berita.show', ['slug' => $kategori->slug, 'id' => $berita->id]) }}"
                                class="bg-yellow-500 hover:bg-yellow-700 py-1 px-2 text-white rounded transition">
                                <i class="fa-solid fa-arrow-up-right-from-square"></i>
                            </a>
                            <a href="{{route('berita.edit', ['slug' => $kategori->slug, 'id' => $berita->id])}}"
                                class="bg-green-600 hover:bg-green-800 py-1 px-2 text-white rounded transition">
                                <i class="fa-solid fa-edit"></i>
                            </a>
                            <form
                                action="{{ route('berita.destroy', ['slug' => $kategori->slug, 'id' => $berita->id]) }}"
                                method="post">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="bg-red-600 hover:bg-red-800 py-1 px-2 text-white rounded transition">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @endif

        {{-- Modal Konfirmasi Hapus --}}
        <div id="deleteModal" class="fixed inset-0 bg-black/50 flex justify-center items-center hidden">
            <div class="bg-white dark:bg-gray-900 p-6 rounded-lg shadow-lg w-96">
                <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">Konfirmasi Hapus</h2>
                <p class="text-gray-600 dark:text-gray-400">Apakah Anda yakin ingin menghapus berita
                    <strong id="deleteTitle"></strong>?
                </p>
                <form id="deleteForm" method="POST" class="mt-4">
                    @csrf
                    @method('DELETE')
                    <div class="flex justify-end gap-2">
                        <button type="button" onclick="hideDeleteModal()"
                            class="px-4 py-2 bg-gray-300 dark:bg-gray-700 rounded-lg hover:bg-gray-400 dark:hover:bg-gray-600 transition">
                            Batal
                        </button>
                        <button type="submit"
                            class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                            Hapus
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Script untuk Modal Hapus --}}
    <script>
        function showDeleteModal(id, title) {
            document.getElementById("deleteModal").classList.remove("hidden");
            document.getElementById("deleteTitle").textContent = title;
            document.getElementById("deleteForm").action = "/berita/" + id;
        }

        function hideDeleteModal() {
            document.getElementById("deleteModal").classList.add("hidden");
        }

        function closeAlert() {
            const alertBox = document.getElementById('successAlert');
            alertBox.classList.add('opacity-0');
            setTimeout(() => alertBox.style.display = 'none', 300);
        }
    </script>
</x-app-layout>