@section('namePage', 'Edit Berita')

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

                <div class="mb-4">
                    <label for="category_id" class="block text-gray-700 dark:text-gray-200 font-medium mb-1">Kategori
                        Berita <span class="text-red-500">*</span></label>
                    <select id="category_id" name="category_id"
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-blue-500 focus:outline-none @error('category_id') is-invalid @enderror"
                        required>
                        @foreach ($categories as $item)
                            <option value="{{ $item->id }}"
                                {{ (string) old('category_id', $berita->category_id) === (string) $item->id ? 'selected' : '' }}>
                                {{ $item->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Input Judul Berita Default --}}
                <div class="mb-4">
                    <label for="title" class="block text-gray-700 dark:text-gray-200 font-medium mb-1">Judul Default
                        <span class="text-red-500">*</span></label>
                    <input type="text" id="title" name="title"
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-blue-500 focus:outline-none @error('title') is-invalid @enderror"
                        placeholder="Masukkan judul default..." value="{{ old('title', $berita->title) }}" required>

                    @error('title')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Input Judul untuk Setiap PT --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <!-- SG -->
                    <div class="mb-4">
                        <label for="title_sg" class="block text-gray-700 dark:text-gray-200 font-medium mb-1">Judul
                            SG</label>
                        <input type="text" id="title_sg" name="title_sg"
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-blue-500 focus:outline-none @error('title_sg') is-invalid @enderror"
                            placeholder="Judul untuk SG..." value="{{ old('title_sg', $berita->title_sg) }}">
                        @error('title_sg')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- RFB -->
                    <div class="mb-4">
                        <label for="title_rfb" class="block text-gray-700 dark:text-gray-200 font-medium mb-1">Judul
                            RFB</label>
                        <input type="text" id="title_rfb" name="title_rfb"
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-blue-500 focus:outline-none @error('title_rfb') is-invalid @enderror"
                            placeholder="Judul untuk RFB..." value="{{ old('title_rfb', $berita->title_rfb) }}">
                        @error('title_rfb')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- KPF -->
                    <div class="mb-4">
                        <label for="title_kpf" class="block text-gray-700 dark:text-gray-200 font-medium mb-1">Judul
                            KPF</label>
                        <input type="text" id="title_kpf" name="title_kpf"
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-blue-500 focus:outline-none @error('title_kpf') is-invalid @enderror"
                            placeholder="Judul untuk KPF..." value="{{ old('title_kpf', $berita->title_kpf) }}">
                        @error('title_kpf')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- EWF -->
                    <div class="mb-4">
                        <label for="title_ewf" class="block text-gray-700 dark:text-gray-200 font-medium mb-1">Judul
                            EWF</label>
                        <input type="text" id="title_ewf" name="title_ewf"
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-blue-500 focus:outline-none @error('title_ewf') is-invalid @enderror"
                            placeholder="Judul untuk EWF..." value="{{ old('title_ewf', $berita->title_ewf) }}">
                        @error('title_ewf')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- BPF -->
                    <div class="mb-4">
                        <label for="title_bpf" class="block text-gray-700 dark:text-gray-200 font-medium mb-1">Judul
                            BPF</label>
                        <input type="text" id="title_bpf" name="title_bpf"
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-blue-500 focus:outline-none @error('title_bpf') is-invalid @enderror"
                            placeholder="Judul untuk BPF..." value="{{ old('title_bpf', $berita->title_bpf) }}">
                        @error('title_bpf')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                </div>

                {{-- Foto untuk 5 website berbeda --}}
                @php
                    $imageLabels = [
                        1 => 'Gambar SG:',
                        2 => 'Gambar RFB:',
                        3 => 'Gambar KPF:',
                        4 => 'Gambar EWF:',
                        5 => 'Gambar BPF:',
                        6 => 'Gambar Backup:',
                    ];

                    $images = collect(range(1, 6))
                        ->mapWithKeys(function ($i) use ($berita, $imageLabels) {
                            return [
                                "image{$i}" => [
                                    'path' => $berita->{"image{$i}"},
                                    'label' => $imageLabels[$i] ?? 'Gambar ' . $i,
                                ],
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
                                data-preview="preview-{{ $key }}"
                                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-blue-500 focus:outline-none @error($key) is-invalid @enderror">

                            {{-- Tampilkan gambar yang sudah ada --}}
                            @if ($imageData['path'])
                                <img id="preview-{{ $key }}" src="{{ asset($imageData['path']) }}"
                                    alt="{{ $imageData['label'] }}"
                                    class="mt-2 h-30 w-full rounded-lg object-cover">
                            @else
                                <img id="preview-{{ $key }}" alt="Preview {{ $imageData['label'] }}"
                                    class="mt-2 h-30 w-full rounded-lg object-cover hidden">
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

        {{-- Summernote untuk editor teks --}}
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-lite.min.css">
        <style>
            .note-editor.note-frame {
                border: 1px solid #d1d5db;
                border-radius: 0.75rem;
                overflow: hidden;
                background: #ffffff;
            }

            .note-editor.note-frame:focus-within {
                border-color: #3b82f6;
                box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.25);
            }

            .note-editor .note-toolbar {
                background: #f9fafb;
                border-bottom: 1px solid #e5e7eb;
                padding: 0.5rem;
            }

            .note-editor .note-toolbar .note-btn-group {
                margin: 0 0.25rem 0.25rem 0;
            }

            .note-editor .note-toolbar .note-btn {
                background: #ffffff;
                border: 1px solid #d1d5db;
                border-radius: 0.5rem;
                padding: 0.35rem 0.5rem;
                line-height: 1;
            }

            .note-editor .note-toolbar .note-btn:hover {
                background: #f3f4f6;
            }

            .note-editor .note-editable {
                background: #ffffff;
                color: #111827;
                padding: 1rem;
                font-size: 0.95rem;
                line-height: 1.65;
            }

            /* Tailwind preflight resets paragraph margins */
            .note-editor .note-editable p {
                margin: 0 0 1em;
            }

            .note-editor .note-editable p:last-child {
                margin-bottom: 0;
            }

            /* Tailwind preflight resets list styles */
            .note-editor .note-editable ul {
                list-style: disc;
                padding-left: 1.5rem;
            }

            .note-editor .note-editable ol {
                list-style: decimal;
                padding-left: 1.5rem;
            }

            .note-editor .note-statusbar {
                background: #f9fafb;
                border-top: 1px solid #e5e7eb;
            }

            .dark .note-editor.note-frame {
                border-color: #334155;
                background: #0f172a;
            }

            .dark .note-editor.note-frame:focus-within {
                border-color: #60a5fa;
                box-shadow: 0 0 0 4px rgba(96, 165, 250, 0.25);
            }

            .dark .note-editor .note-toolbar {
                background: #0b1220;
                border-bottom-color: #334155;
            }

            .dark .note-editor .note-toolbar .note-btn {
                background: #0f172a;
                border-color: #334155;
                color: #e2e8f0;
            }

            .dark .note-editor .note-toolbar .note-btn:hover {
                background: #111c33;
            }

            .dark .note-editor .note-editable {
                background: #0f172a;
                color: #e2e8f0;
            }

            .dark .note-editor .note-statusbar {
                background: #0b1220;
                border-top-color: #334155;
            }
        </style>
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-lite.min.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                if (!window.jQuery || !jQuery.fn || !jQuery.fn.summernote) {
                    return;
                }

                jQuery('#content').summernote({
                    height: 500,
                    toolbar: [
                        ['style', ['style']],
                        ['font', ['bold', 'italic', 'underline', 'clear']],
                        ['para', ['ul', 'ol', 'paragraph']],
                        ['insert', ['link', 'picture', 'video', 'table']],
                        ['view', ['fullscreen', 'codeview', 'help']],
                    ],
                });
            });
        </script>

        {{-- Script Modal --}}
        <script>
            function toggleModal(id) {
                const modal = document.getElementById(id);
                modal.classList.toggle('hidden');
            }
        </script>

        {{-- Script Preview Gambar --}}
        <script>
            document.querySelectorAll('input[type="file"][data-preview]').forEach((input) => {
                input.addEventListener('change', (event) => {
                    const file = event.target.files && event.target.files[0];
                    const previewId = event.target.getAttribute('data-preview');
                    const preview = document.getElementById(previewId);

                    if (!preview) {
                        return;
                    }

                    if (file && file.type.startsWith('image/')) {
                        preview.src = URL.createObjectURL(file);
                        preview.classList.remove('hidden');
                    } else {
                        preview.src = '';
                        preview.classList.add('hidden');
                    }
                });
            });
        </script>
    </div>
</x-app-layout>
