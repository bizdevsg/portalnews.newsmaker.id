<x-app-layout>
    @php
        $totalKategori = $categories->count();
        $totalBerita = $categories->sum('berita_count');
        $kategoriTerpadat = $categories->sortByDesc('berita_count')->first();
    @endphp

    <div class="px-4 sm:px-6 lg:px-8 pt-8 pb-4 w-full max-w-9xl mx-auto">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="mt-2 text-3xl font-semibold tracking-tight text-gray-900 dark:text-white">
                    Kategori Berita
                </h1>
                <div class="mt-3 flex flex-wrap items-center gap-2 text-xs">
                    <span
                        class="inline-flex items-center rounded-full bg-sky-50 px-3 py-1.5 font-semibold text-sky-700 dark:bg-sky-900/30 dark:text-sky-200">
                        {{ $totalKategori }} Kategori
                    </span>
                    <span
                        class="inline-flex items-center rounded-full bg-white px-3 py-1.5 font-medium text-gray-600 ring-1 ring-gray-200 dark:bg-gray-800 dark:text-gray-300 dark:ring-gray-700">
                        {{ $totalBerita }} Total Berita
                    </span>
                </div>
            </div>

<<<<<<< Updated upstream
            {{-- Button Tambah --}}
            <a href="{{route('kategori.create')}}"
                class="bg-blue-500 text-center text-white px-4 py-2 rounded-lg text-sm font-bold hover:bg-blue-600 transition">
=======
            <a href="{{ route('kategori.create') }}"
                class="inline-flex items-center justify-center gap-2 rounded-2xl bg-sky-600 px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-sky-600/20 transition hover:-translate-y-0.5 hover:bg-sky-700">
                <i class="fa-solid fa-plus"></i>
>>>>>>> Stashed changes
                Tambah Kategori
            </a>
        </div>
    </div>

    @if (session('success'))
<<<<<<< Updated upstream
    <div id="successAlert" class="w-full max-w-9xl mx-auto px-4 sm:px-6 lg:px-8">
        <div
            class="border-l-4 border-green-600 p-4 mb-6 rounded-lg bg-green-100 dark:bg-green-800 flex items-center justify-between shadow-md transition-opacity duration-300">
            <div class="flex items-center gap-2 text-green-800 dark:text-green-300 text-sm sm:text-base">
                <i class="fa-solid fa-circle-check text-lg"></i>
                <span>{{ session('success') }}</span>
=======
        <div id="successAlert" class="w-full max-w-9xl mx-auto px-4 sm:px-6 lg:px-8">
            <div
                class="flex items-center justify-between rounded-3xl border border-emerald-200 bg-emerald-50/95 p-4 shadow-lg shadow-emerald-100/60 transition-opacity duration-300 dark:border-emerald-700/50 dark:bg-emerald-900/40 dark:shadow-none">
                <div class="flex items-center gap-2 text-sm text-green-800 sm:text-base dark:text-white">
                    <i class="fa-solid fa-circle-check text-lg"></i>
                    <span>{{ session('success') }}</span>
                </div>
                <button type="button" onclick="closeAlert()"
                    class="p-1 text-green-800 transition hover:text-green-900 dark:text-green-300 dark:hover:text-green-400">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
>>>>>>> Stashed changes
            </div>
            <button onclick="closeAlert()"
                class="p-1 text-green-800 dark:text-green-300 hover:text-green-900 dark:hover:text-green-400 transition">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>
    </div>
    @endif

    <div class="px-4 sm:px-6 lg:px-8 py-6 w-full max-w-9xl mx-auto">
        @if ($categories->isEmpty())
<<<<<<< Updated upstream
        <div
            class="flex flex-col justify-center items-center mx-4 sm:mx-6 lg:mx-8 p-10 rounded-lg gap-3 bg-gray-200 dark:bg-gray-800 dark:text-gray-300 transition">
            <img src="{{ asset('assets/hand-drawn-no-data-concept.png') }}" alt="No Data" class="h-50 rounded-lg">
            <p class="text-gray-600 text-xl">Belum ada kategori berita.</p>
        </div>
        @else
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-6">
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
=======
            <div
                class="overflow-hidden rounded-[28px] border border-dashed border-sky-200 bg-white/85 p-8 text-center shadow-[0_24px_60px_-40px_rgba(14,116,144,0.45)] backdrop-blur dark:border-sky-800 dark:bg-gray-900/80">
                <div
                    class="mx-auto flex h-[4.5rem] w-[4.5rem] items-center justify-center rounded-full bg-sky-100 text-sky-700 dark:bg-sky-900/40 dark:text-sky-300">
                    <i class="fa-solid fa-layer-group text-3xl"></i>
                </div>
                <h2 class="mt-5 text-2xl font-semibold text-gray-900 dark:text-white">
                    Belum ada kategori berita
                </h2>
                <p class="mx-auto mt-3 max-w-2xl text-sm leading-7 text-gray-600 dark:text-gray-300">
                    Mulai buat struktur kategori agar alur pengelolaan berita lebih rapi. Setiap kategori akan tampil
                    sebagai kartu dengan ringkasan jumlah berita dan tombol aksi cepat.
                </p>
                <a href="{{ route('kategori.create') }}"
                    class="mt-6 inline-flex items-center gap-2 rounded-2xl bg-sky-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-sky-700">
                    <i class="fa-solid fa-plus"></i>
                    Buat Kategori Pertama
                </a>
            </div>
        @else
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 xl:grid-cols-3 2xl:grid-cols-4">
                @foreach ($categories as $kategori)
                    <article
                        class="group relative flex h-full flex-col overflow-hidden rounded-xl border border-white/70 bg-white/90 shadow-[0_26px_70px_-42px_rgba(15,23,42,0.45)] transition duration-300 hover:-translate-y-1 hover:shadow-[0_30px_80px_-38px_rgba(14,116,144,0.45)] dark:border-gray-800 dark:bg-gray-900/90">
                        <div
                            class="absolute inset-x-0 top-0 h-1 bg-linear-to-r from-sky-500 via-cyan-400 to-emerald-400">
                        </div>

                        <div class="flex flex-1 flex-col p-5">
                            <div class="flex items-start justify-between gap-3">
                                <div
                                    class="inline-flex h-14 w-14 shrink-0 items-center justify-center rounded-3xl bg-sky-50 text-sky-700 dark:bg-sky-900/25 dark:text-sky-200">
                                    <i class="fa-solid fa-folder-open text-xl"></i>
                                </div>

                                <div class="flex flex-wrap justify-end gap-2 text-xs">
                                    <span
                                        class="inline-flex items-center rounded-full bg-gray-100 px-3 py-1.5 font-medium text-gray-600 dark:bg-gray-800 dark:text-gray-300">
                                        #{{ $kategori->id }}
                                    </span>
                                    <span
                                        class="inline-flex items-center rounded-full bg-sky-50 px-3 py-1.5 font-semibold text-sky-700 dark:bg-sky-900/30 dark:text-sky-200">
                                        {{ $kategori->berita_count }} Berita
                                    </span>
                                </div>
                            </div>

                            <div class="mt-5">
                                <p
                                    class="text-xs font-semibold uppercase tracking-[0.24em] text-sky-700 dark:text-sky-300">
                                    Kategori
                                </p>
                                <h2 class="mt-2 text-2xl font-semibold tracking-tight text-gray-900 dark:text-white">
                                    {{ $kategori->name }}
                                </h2>
                            </div>

                            <div class="mt-6 grid grid-cols-3 gap-2">
                                <a href="{{ route('berita.index', $kategori->slug) }}"
                                    class="inline-flex items-center justify-center gap-2 rounded-md bg-amber-400 px-4 py-3 text-sm font-semibold text-slate-900 transition hover:bg-amber-300">
                                    <i class="fa-solid fa-arrow-up-right-from-square"></i>
                                    <span class="hidden sm:inline">Lihat</span>
                                </a>
                                <a href="{{ route('kategori.edit', $kategori->id) }}"
                                    class="inline-flex items-center justify-center gap-2 rounded-md bg-emerald-600 px-4 py-3 text-sm font-semibold text-white transition hover:bg-emerald-700">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                    <span class="hidden sm:inline">Edit</span>
                                </a>
                                <button type="button" data-delete-url="{{ route('kategori.destroy', $kategori->id) }}"
                                    data-delete-name="{{ $kategori->name }}"
                                    class="category-delete-trigger inline-flex items-center justify-center gap-2 rounded-md bg-red-50 px-4 py-3 text-sm font-semibold text-red-600 transition hover:bg-red-600 hover:text-white dark:bg-red-900/20 dark:text-red-300 dark:hover:bg-red-700"
                                    aria-label="Hapus kategori {{ $kategori->name }}">
                                    <i class="fa-solid fa-trash"></i>
                                    <span class="hidden sm:inline">Hapus</span>
                                </button>
                            </div>
                        </div>
                    </article>
                @endforeach
>>>>>>> Stashed changes
            </div>
            @endforeach
        </div>
        @endif

        <div id="deleteModal" class="fixed inset-0 z-50 hidden bg-slate-950/60 px-4 backdrop-blur-sm">
            <div class="flex min-h-full items-center justify-center">
                <div
                    class="w-full max-w-md overflow-hidden rounded-[28px] border border-white/10 bg-white shadow-2xl dark:border-gray-800 dark:bg-gray-900">
                    <div
                        class="border-b border-gray-100 bg-linear-to-r from-red-500 to-rose-500 px-6 py-5 text-white dark:border-gray-800">
                        <p class="text-xs font-semibold uppercase tracking-[0.24em] text-red-100">Danger Zone</p>
                        <h2 class="mt-2 text-2xl font-semibold">Konfirmasi Hapus</h2>
                    </div>

                    <div class="px-6 py-5">
                        <p class="text-sm leading-7 text-gray-600 dark:text-gray-300">
                            Apakah Anda yakin ingin menghapus kategori
                            <strong id="categoryName" class="text-gray-900 dark:text-white"></strong>?
                        </p>
                        <p class="mt-2 text-sm leading-7 text-gray-500 dark:text-gray-400">
                            Seluruh berita yang terhubung dengan kategori ini juga akan ikut terhapus.
                        </p>

                        <form id="deleteForm" method="POST" class="mt-6">
                            @csrf
                            @method('DELETE')
                            <div class="flex justify-end gap-3">
                                <button type="button" onclick="hideDeleteModal()"
                                    class="rounded-2xl bg-gray-100 px-4 py-3 text-sm font-semibold text-gray-700 transition hover:bg-gray-200 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700">
                                    Batal
                                </button>
                                <button type="submit"
                                    class="rounded-2xl bg-red-600 px-4 py-3 text-sm font-semibold text-white transition hover:bg-red-700">
                                    Hapus Kategori
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.querySelectorAll('.category-delete-trigger').forEach((button) => {
            button.addEventListener('click', () => {
                showDeleteModal(button.dataset.deleteUrl, button.dataset.deleteName);
            });
        });

        function showDeleteModal(deleteUrl, name) {
            document.getElementById('deleteModal').classList.remove('hidden');
            document.getElementById('deleteForm').action = deleteUrl;
            document.getElementById('categoryName').textContent = name;
        }

        function hideDeleteModal() {
            document.getElementById('deleteModal').classList.add('hidden');
        }

        function closeAlert() {
            const alert = document.getElementById('successAlert');
            if (alert) {
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 300);
            }
<<<<<<< Updated upstream

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
=======
        }
    </script>
</x-app-layout>
>>>>>>> Stashed changes
