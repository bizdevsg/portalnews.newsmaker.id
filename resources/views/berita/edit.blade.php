<x-app-layout>
    <div class="px-4 sm:px-6 lg:px-8 pt-8 pb-4 w-full max-w-9xl mx-auto">
        <div class="mx-auto bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
            <form action="{{ route('berita.update', [$kategori->slug, $berita->id]) }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="inline-flex items-center justify-between w-full mb-7 md:mb-4">
                    {{-- Tombol Kembali --}}
                    <div class="text-left">
                        <button type="button" onclick="toggleModal('modalKembali')"
                            class="inline-flex items-center gap-2 bg-gray-200 dark:bg-gray-700 hover:bg-gray-400 dark:hover:bg-gray-600 py-2 px-6 rounded-lg text-gray-600 dark:text-gray-200 hover:text-gray-800 dark:hover:text-gray-100">
                            <i class="fa-solid fa-chevron-left"></i>
                            <span class="hidden md:block">Kembali</span>
                        </button>
                    </div>

                    <h1 class="text-2xl md:text-3xl text-gray-800 dark:text-gray-100 font-bold">
                        Edit Berita
                    </h1>

                    {{-- Tombol Simpan --}}
                    <div class="text-right">
                        <button type="button" onclick="toggleModal('modalSubmit')"
                            class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white py-2 px-6 rounded-lg font-semibold transition">
                            <i class="fa-solid fa-save"></i>
                            <span class="hidden md:block">Simpan</span>
                        </button>
                    </div>
                </div>

                <input type="hidden" name="category_id" value="{{ $kategori->id }}">

                {{-- Input Judul Berita --}}
                <div class="mb-4">
                    <label for="title" class="block text-gray-700 dark:text-gray-200 font-medium mb-1">Judul
                        Berita</label>
                    <input type="text" id="title" name="title"
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-blue-500 focus:outline-none @error('title') is-invalid @enderror"
                        placeholder="Masukkan judul berita..." value="{{ old('title', $berita->title) }}" required>

                    @error('title')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Foto untuk 5 website berbeda --}}
                @php
                    $imageLabels = [
                        1 => 'Gambar SG:',
                        2 => 'Gambar RFB:',
                        3 => 'Gambar KPF:',
                        4 => 'Gambar EWF:',
                        5 => 'Gambar BPF:',
                        6 => 'Gambar Backup:'
                    ];

                    $images = collect(range(1, 6))
                        ->mapWithKeys(function($i) use ($berita, $imageLabels) {
                            return [
                                "image{$i}" => [
                                    'path' => $berita->{"image{$i}"},
                                    'label' => $imageLabels[$i] ?? 'Gambar ' . $i
                                ]
                            ];
                        })
                        ->filter(fn($item) => !empty($item['path']));
                @endphp

                {{-- Input Gambar --}}
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-3">
                    @foreach ($images as $key => $imageData)
                        <div class="mb-4">
                            <label for="{{ $key }}"
                                class="block text-gray-700 dark:text-gray-200 font-medium mb-1">
                                {{ $imageData['label'] }}
                            </label>
                            <input type="file" id="{{ $key }}" name="{{ $key }}"
                                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-blue-500 focus:outline-none @error($key) is-invalid @enderror">

                            {{-- Tampilkan gambar yang sudah ada --}}
                            @if ($imageData['path'])
                                <img src="{{ asset($imageData['path']) }}" alt="{{ $imageData['label'] }}"
                                    class="mt-2 h-30 w-full rounded-lg object-cover">
                            @endif

                            @error($key)
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    @endforeach
                </div>

                {{-- Input Isi Berita --}}
                <div class="mb-4">
                    <label for="content" class="block text-gray-700 dark:text-gray-200 font-medium mb-1">Isi
                        Berita</label>
                    <textarea id="content" name="content" class="w-full h-48 dark:bg-gray-800">{{ old('content', $berita->content) }}</textarea>

                    @error('content')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Modal Submit --}}
                <div id="modalSubmit"
                    class="hidden fixed inset-0 bg-gray-900/50 dark:bg-gray-900/75 flex items-center justify-center px-3 z-100">
                    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg">
                        <h2 class="text-lg font-semibold mb-4 dark:text-gray-100">Konfirmasi</h2>
                        <p class="dark:text-gray-300">Apakah Anda yakin ingin menyimpan perubahan ini?</p>
                        <div class="flex justify-end mt-4">
                            <button onclick="toggleModal('modalSubmit')"
                                class="mr-2 bg-gray-400 hover:bg-gray-500 dark:bg-gray-600 dark:hover:bg-gray-500 text-white py-2 px-4 rounded-lg">Batal</button>
                            <button type="submit"
                                class="bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-lg">Ya,
                                Simpan</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        {{-- Modal Kembali --}}
        <div id="modalKembali"
            class="hidden fixed inset-0 bg-gray-900/50 dark:bg-gray-900/75 flex items-center justify-center px-3 z-100">
            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg">
                <h2 class="text-lg font-semibold mb-4 dark:text-gray-100">Konfirmasi</h2>
                <p class="dark:text-gray-300">Apakah Anda yakin ingin kembali? Perubahan tidak akan disimpan.</p>
                <div class="flex justify-end mt-4">
                    <button onclick="toggleModal('modalKembali')"
                        class="mr-2 bg-gray-400 hover:bg-gray-500 dark:bg-gray-600 dark:hover:bg-gray-500 text-white py-2 px-4 rounded-lg">Batal</button>
                    <a href="{{ route('berita.index', $kategori->slug) }}"
                        class="bg-red-600 hover:bg-red-700 text-white py-2 px-4 rounded-lg">Ya, Kembali</a>
                </div>
            </div>
        </div>

        {{-- TinyMCE untuk editor teks --}}
        <script src="https://cdn.tiny.cloud/1/rijrac2uxn06a1q296snq7j1fi420fd29r3lc1o12yzq6fwv/tinymce/7/tinymce.min.js"
            referrerpolicy="origin"></script>
        <script>
            tinymce.init({
                selector: '#content',
                plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount',
                toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table | align lineheight | numlist bullist indent outdent | emoticons charmap | removeformat',
                height: 500
            });
        </script>

        {{-- Script Modal --}}
        <script>
            function toggleModal(id) {
                const modal = document.getElementById(id);
                modal.classList.toggle('hidden');
            }
        </script>
    </div>
</x-app-layout>
