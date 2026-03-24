<x-app-layout>
    @php
        $totalBerita = $beritas->count();
        $latestBerita = $beritas->max('created_at');
    @endphp

    <div class="px-4 sm:px-6 lg:px-8 pt-8 pb-4 w-full max-w-9xl mx-auto">
        <div class="flex flex-col gap-4 sm:flex-row items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="{{ route('kategori.index') }}"
                    class="inline-flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-white text-gray-700 shadow-sm ring-1 ring-gray-200 transition hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-200 dark:ring-gray-700 dark:hover:bg-gray-700">
                    <i class="fa-solid fa-arrow-left"></i>
                </a>
<<<<<<< Updated upstream
                <h1 class="text-2xl md:text-3xl text-gray-800 dark:text-gray-100 font-bold">
                    {{$kategori->name}}
                </h1>
            </div>

            {{-- Button Tambah --}}
            <a href="{{route('berita.create', $kategori->slug)}}"
                class="bg-blue-500 text-white px-4 py-2 rounded-lg text-sm font-bold hover:bg-blue-600 transition duration-200">
                Tambah
=======
                <div>
                    {{-- <p class="text-xs font-semibold uppercase tracking-[0.24em] text-sky-700 dark:text-sky-300">
                        Kategori Berita
                    </p> --}}
                    <h1 class="text-3xl font-semibold tracking-tight text-gray-900 dark:text-white">
                        Kategori {{ $kategori->name }}
                    </h1>
                </div>
            </div>

            <a href="{{ route('berita.create', $kategori->slug) }}"
                class="inline-flex items-center justify-center gap-2 rounded-xl bg-sky-600 px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-sky-600/20 transition hover:-translate-y-0.5 hover:bg-sky-700">
                <i class="fa-solid fa-plus"></i>
                Tambah Berita
>>>>>>> Stashed changes
            </a>
        </div>

        {{-- Alert --}}
        @if (session('success'))
<<<<<<< Updated upstream
        <div id="successAlert" class="mx-auto">
            <div
                class="border-l-4 border-green-600 p-4 mb-6 rounded-lg bg-green-100 dark:bg-green-800 flex items-center justify-between shadow-md transition-opacity duration-300">
                <div class="flex items-center gap-2 text-green-800 dark:text-white text-sm sm:text-base">
                    <i class="fa-solid fa-circle-check text-lg"></i>
                    <span>{{ session('success') }}</span>
=======
            <div id="successAlert" class="mx-auto mt-6">
                <div
                    class="flex items-center justify-between rounded-xl border border-emerald-200 bg-emerald-50/95 p-4 shadow-lg shadow-emerald-100/60 transition-opacity duration-300 dark:border-emerald-700/50 dark:bg-emerald-900/40 dark:shadow-none">
                    <div class="flex items-center gap-2 text-green-800 dark:text-white text-sm sm:text-base">
                        <i class="fa-solid fa-circle-check text-lg"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                    <button onclick="closeAlert()"
                        class="p-1 text-green-800 dark:text-green-300 hover:text-green-900 dark:hover:text-green-400 transition">
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

        {{-- Grid Berita --}}
        @if ($beritas->isEmpty())
<<<<<<< Updated upstream
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
=======
            <div
                class="mt-6 overflow-hidden rounded-xl border border-dashed border-sky-200 bg-white/85 p-8 text-center shadow-[0_24px_60px_-40px_rgba(14,116,144,0.45)] backdrop-blur dark:border-sky-800 dark:bg-gray-900/80">
                <div
                    class="mx-auto flex h-[4.5rem] w-[4.5rem] items-center justify-center rounded-full bg-sky-100 text-sky-700 dark:bg-sky-900/40 dark:text-sky-300">
                    <i class="fa-regular fa-newspaper text-3xl"></i>
                </div>
                <h3 class="mt-5 text-2xl font-semibold text-gray-900 dark:text-white">
                    Belum ada berita di {{ $kategori->name }}
                </h3>
                <p class="mx-auto mt-3 max-w-2xl text-sm leading-7 text-gray-600 dark:text-gray-300">
                    Mulai isi kategori ini dengan artikel pertama. Setelah data masuk, kartu berita akan tampil dengan
                    preview visual, ringkasan isi, dan tombol aksi yang lebih rapi.
                </p>
                <a href="{{ route('berita.create', $kategori->slug) }}"
                    class="mt-6 inline-flex items-center gap-2 rounded-2xl bg-sky-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-sky-700">
                    <i class="fa-solid fa-plus"></i>
                    Buat Berita Pertama
                </a>
            </div>
        @else
            <div class="mt-6 grid grid-cols-1 gap-6 sm:grid-cols-2 xl:grid-cols-3 2xl:grid-cols-4">
                @foreach ($beritas as $berita)
                    <div
                        class="group flex h-full flex-col overflow-hidden rounded-xl border border-white/70 bg-white/90 shadow-[0_26px_70px_-42px_rgba(15,23,42,0.45)] transition duration-300 hover:-translate-y-1 hover:shadow-[0_30px_80px_-38px_rgba(14,116,144,0.45)] dark:border-gray-800 dark:bg-gray-900/90">
                        <div class="relative overflow-hidden">
                            <div
                                class="absolute inset-0 bg-linear-to-t from-slate-950/80 via-slate-950/20 to-transparent z-10">
                            </div>
                            <img src="{{ asset($berita->image1) }}" alt="{{ $berita->title }}"
                                class="h-56 w-full object-cover transition duration-500 group-hover:scale-105">

                            <div class="absolute inset-x-0 top-0 z-20 flex items-start justify-between p-4">
                                <span
                                    class="inline-flex items-center gap-2 rounded-full border border-white/20 bg-blue-500/30 px-3 py-1.5 text-[11px] font-semibold uppercase tracking-[0.24em] text-white backdrop-blur">
                                    <span class="h-2 w-2 rounded-full bg-emerald-300"></span>
                                    {{ $kategori->name }}
                                </span>
                                <span
                                    class="rounded-full border border-white/20 bg-blue-900/50 px-3 py-1 text-xs font-medium text-slate-100 backdrop-blur">
                                    {{ \Carbon\Carbon::parse($berita->created_at)->translatedFormat('d M Y') }}
                                </span>
                            </div>

                            <div class="absolute inset-x-0 bottom-0 z-20 p-5">
                                <p class="text-xs font-medium uppercase tracking-[0.24em] text-cyan-100/80">
                                    {{ \Carbon\Carbon::parse($berita->created_at)->translatedFormat('l') }}
                                </p>
                                <h3 class="mt-2 text-xl font-semibold leading-tight text-white line-clamp-2">
                                    {{ Str::limit($berita['title'] ?? 'Judul tidak tersedia', 72) }}
                                </h3>
                            </div>
                        </div>

                        <div class="flex flex-1 flex-col p-5">
                            <div class="flex flex-wrap items-center gap-2">
                                <span
                                    class="inline-flex items-center rounded-full bg-sky-50 px-3 py-1 text-xs font-semibold text-sky-700 dark:bg-sky-900/30 dark:text-sky-200">
                                    Artikel
                                </span>
                                <span
                                    class="inline-flex items-center rounded-full bg-gray-100 px-3 py-1 text-xs font-medium text-gray-600 dark:bg-gray-800 dark:text-gray-300">
                                    #{{ $berita->id }}
                                </span>
                            </div>

                            <p class="mt-4 flex-1 text-sm leading-7 text-gray-600 dark:text-gray-300 line-clamp-2">
                                {{ Str::limit(strip_tags($berita['content'] ?? ''), 140) }}
                            </p>

                            <div
                                class="mt-6 flex items-center justify-between rounded-2xl bg-gray-50 px-4 py-3 text-sm text-gray-500 dark:bg-gray-800/80 dark:text-gray-300">
                                <div>
                                    <p
                                        class="text-[11px] font-semibold uppercase tracking-[0.22em] text-gray-400 dark:text-gray-500">
                                        Dipublikasikan
                                    </p>
                                    <p class="mt-1 font-medium text-gray-700 dark:text-gray-200">
                                        {{ \Carbon\Carbon::parse($berita->created_at)->translatedFormat('d F Y, H:i') }}
                                    </p>
                                </div>
                                <span
                                    class="inline-flex h-11 w-11 items-center justify-center rounded-2xl bg-white text-sky-700 shadow-sm dark:bg-gray-900 dark:text-sky-300">
                                    <i class="fa-regular fa-clock"></i>
                                </span>
                            </div>

                            <div class="mt-5 flex items-center gap-2">
                                <a href="{{ route('berita.show', ['slug' => $kategori->slug, 'id' => $berita->id]) }}"
                                    class="inline-flex flex-1 items-center justify-center gap-2 rounded-2xl bg-amber-400 px-4 py-3 text-sm font-semibold text-slate-900 transition hover:bg-amber-300">
                                    <i class="fa-solid fa-arrow-up-right-from-square"></i>
                                    Lihat
                                </a>
                                <a href="{{ route('berita.edit', ['slug' => $kategori->slug, 'id' => $berita->id]) }}"
                                    class="inline-flex flex-1 items-center justify-center gap-2 rounded-2xl bg-emerald-600 px-4 py-3 text-sm font-semibold text-white transition hover:bg-emerald-700">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                    Edit
                                </a>
                                <button type="button"
                                    data-delete-url="{{ route('berita.destroy', ['slug' => $kategori->slug, 'id' => $berita->id]) }}"
                                    data-delete-title="{{ Str::limit($berita['title'] ?? 'Judul tidak tersedia', 55) }}"
                                    class="news-delete-trigger inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-red-50 text-red-600 transition hover:bg-red-100 dark:bg-red-900/20 dark:text-red-300 dark:hover:bg-red-400/20 cursor-pointer"
                                    aria-label="Hapus berita {{ Str::limit($berita['title'] ?? 'Judul tidak tersedia', 30) }}">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </div>
>>>>>>> Stashed changes
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @endif

        {{-- Modal Konfirmasi Hapus --}}
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
                        <p class="text-sm leading-7 text-gray-600 dark:text-gray-300">Apakah Anda yakin ingin menghapus
                            berita
                            <strong id="deleteTitle" class="text-gray-900 dark:text-white"></strong>?
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
                                    Hapus Berita
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Script untuk Modal Hapus --}}
    <script>
        document.querySelectorAll('.news-delete-trigger').forEach((button) => {
            button.addEventListener('click', () => {
                showDeleteModal(button.dataset.deleteUrl, button.dataset.deleteTitle);
            });
        });

        function showDeleteModal(deleteUrl, title) {
            document.getElementById("deleteModal").classList.remove("hidden");
            document.getElementById("deleteTitle").textContent = title;
            document.getElementById("deleteForm").action = deleteUrl;
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