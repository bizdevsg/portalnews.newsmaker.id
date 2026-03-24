@php
    $isEdit = isset($berita) && $berita;
    $imageLabels = [
        1 => 'Gambar SG',
        2 => 'Gambar RFB',
        3 => 'Gambar KPF',
        4 => 'Gambar EWF',
        5 => 'Gambar BPF',
        6 => 'Gambar Backup',
    ];
    $titleFields = [
        'title_sg' => 'Judul SG',
        'title_rfb' => 'Judul RFB',
        'title_kpf' => 'Judul KPF',
        'title_ewf' => 'Judul EWF',
        'title_bpf' => 'Judul BPF',
    ];
@endphp

<div class="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <form id="beritaForm" action="{{ $formAction }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @if ($isEdit)
            @method('PUT')
        @endif

        <input type="hidden" name="category_id" value="{{ $kategori->id }}">

        <div class="flex flex-col gap-4 md:flex-row md:items-start md:justify-between">
            <div class="space-y-2">
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    <span class="font-medium text-gray-700 dark:text-gray-200">{{ $kategori->name }}</span>
                    <span class="mx-2 text-gray-300 dark:text-gray-600">/</span>
                    {{ $isEdit ? 'Edit Berita' : 'Tambah Berita' }}
                </p>
                <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">
                    {{ $pageTitle }}
                </h1>
                <p class="max-w-3xl text-sm text-gray-500 dark:text-gray-400">
                    {{ $pageDescription }}
                </p>
            </div>

            <div class="flex flex-wrap gap-3">
                <button type="button" onclick="toggleModal('modalKembali')"
                    class="inline-flex items-center justify-center gap-2 rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700">
                    <i class="fa-solid fa-arrow-left text-xs"></i>
                    Kembali
                </button>
                <button type="button" onclick="toggleModal('modalSubmit')"
                    class="inline-flex items-center justify-center gap-2 rounded-xl bg-sky-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-sky-700">
                    <i class="fa-solid {{ $submitIcon }} text-xs"></i>
                    {{ $submitLabel }}
                </button>
            </div>
        </div>

        <section class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-gray-900">
            <div class="flex flex-col gap-1 border-b border-gray-100 pb-4 dark:border-gray-800">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Informasi Berita</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Isi judul utama dan variasi judul untuk masing-masing PT.
                </p>
            </div>

            <div class="mt-5 space-y-5">
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-200">
                        Judul Default <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="title" name="title" value="{{ old('title', $berita->title ?? '') }}"
                        placeholder="Masukkan judul utama berita"
                        class="mt-2 w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm text-gray-800 outline-none transition focus:border-sky-500 focus:ring-2 focus:ring-sky-100 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 dark:focus:ring-sky-900/40 @error('title') border-red-400 ring-2 ring-red-100 dark:ring-red-900/30 @enderror"
                        required>
                    @error('title')
                        <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    @foreach ($titleFields as $field => $label)
                        <div>
                            <label for="{{ $field }}" class="block text-sm font-medium text-gray-700 dark:text-gray-200">
                                {{ $label }}
                            </label>
                            <input type="text" id="{{ $field }}" name="{{ $field }}"
                                value="{{ old($field, $berita->{$field} ?? '') }}" placeholder="Opsional"
                                class="mt-2 w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm text-gray-800 outline-none transition focus:border-sky-500 focus:ring-2 focus:ring-sky-100 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 dark:focus:ring-sky-900/40 @error($field) border-red-400 ring-2 ring-red-100 dark:ring-red-900/30 @enderror">
                            @error($field)
                                <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        <section class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-gray-900">
            <div class="flex flex-col gap-1 border-b border-gray-100 pb-4 dark:border-gray-800">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Gambar Berita</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    {{ $isEdit ? 'Upload hanya gambar yang ingin diganti.' : 'Semua slot gambar wajib diisi.' }}
                </p>
            </div>

            <div class="mt-5 grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-3">
                @foreach (range(1, 6) as $i)
                    @php
                        $field = 'image' . $i;
                        $existingImage = $berita->{$field} ?? null;
                        $label = $imageLabels[$i] ?? 'Gambar ' . $i;
                    @endphp

                    <div class="rounded-xl border border-gray-200 p-4 dark:border-gray-700">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <h3 class="text-sm font-semibold text-gray-900 dark:text-white">{{ $label }}</h3>
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                    {{ $isEdit ? 'Biarkan kosong jika tidak diubah.' : 'Wajib diisi.' }}
                                </p>
                            </div>
                            <span
                                class="inline-flex h-7 min-w-7 items-center justify-center rounded-lg bg-gray-100 px-2 text-xs font-semibold text-gray-600 dark:bg-gray-800 dark:text-gray-300">
                                {{ $i }}
                            </span>
                        </div>

                        <div class="mt-4 overflow-hidden rounded-xl border border-dashed border-gray-300 bg-gray-50 dark:border-gray-700 dark:bg-gray-800/60">
                            <div id="empty-{{ $field }}"
                                class="{{ $existingImage ? 'hidden' : 'flex' }} h-40 items-center justify-center px-4 text-center text-sm text-gray-400 dark:text-gray-500">
                                Belum ada gambar
                            </div>
                            <img id="preview-{{ $field }}" src="{{ $existingImage ? asset($existingImage) : '' }}"
                                alt="Preview {{ $label }}"
                                class="{{ $existingImage ? 'block' : 'hidden' }} h-40 w-full object-cover">
                        </div>

                        <div class="mt-4">
                            <label for="{{ $field }}"
                                class="inline-flex cursor-pointer items-center justify-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700">
                                <i class="fa-solid fa-upload text-xs"></i>
                                {{ $isEdit ? 'Pilih Gambar Baru' : 'Pilih Gambar' }}
                            </label>
                            <input type="file" id="{{ $field }}" name="{{ $field }}" accept="image/*"
                                data-preview-target="preview-{{ $field }}"
                                data-empty-target="empty-{{ $field }}" class="hidden"
                                @if (!$isEdit) required @endif>
                            @error($field)
                                <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                @endforeach
            </div>
        </section>

        <section class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-gray-900">
            <div class="flex flex-col gap-1 border-b border-gray-100 pb-4 dark:border-gray-800">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Isi Berita</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Tulis isi artikel dengan editor di bawah ini.
                </p>
            </div>

            <div class="mt-5">
                <textarea id="content" name="content" class="w-full">{{ old('content', $berita->content ?? '') }}</textarea>
                @error('content')
                    <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>
        </section>

        <div
            class="flex flex-col gap-3 rounded-2xl border border-gray-200 bg-white p-4 sm:flex-row sm:items-center sm:justify-between dark:border-gray-800 dark:bg-gray-900">
            <div class="text-sm text-gray-500 dark:text-gray-400">
                {{ $isEdit ? 'Perubahan akan diterapkan ke berita yang dipilih.' : 'Pastikan judul, konten, dan semua gambar sudah sesuai.' }}
            </div>
            <div class="flex flex-wrap gap-3">
                <button type="button" onclick="toggleModal('modalKembali')"
                    class="inline-flex items-center justify-center gap-2 rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700">
                    Batal
                </button>
                <button type="button" onclick="toggleModal('modalSubmit')"
                    class="inline-flex items-center justify-center gap-2 rounded-xl bg-sky-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-sky-700">
                    <i class="fa-solid {{ $submitIcon }} text-xs"></i>
                    {{ $submitLabel }}
                </button>
            </div>
        </div>

        <div id="modalSubmit" class="fixed inset-0 z-50 hidden bg-slate-950/50 px-4">
            <div class="flex min-h-full items-center justify-center">
                <div class="w-full max-w-md rounded-2xl bg-white p-6 shadow-xl dark:bg-gray-900">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Konfirmasi</h2>
                    <p class="mt-3 text-sm leading-6 text-gray-600 dark:text-gray-300">
                        {{ $submitModalMessage }}
                    </p>
                    <div class="mt-6 flex justify-end gap-3">
                        <button type="button" onclick="toggleModal('modalSubmit')"
                            class="rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700">
                            Batal
                        </button>
                        <button type="submit"
                            class="rounded-xl bg-sky-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-sky-700">
                            Ya, Lanjutkan
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div id="modalKembali" class="fixed inset-0 z-50 hidden bg-slate-950/50 px-4">
            <div class="flex min-h-full items-center justify-center">
                <div class="w-full max-w-md rounded-2xl bg-white p-6 shadow-xl dark:bg-gray-900">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Kembali</h2>
                    <p class="mt-3 text-sm leading-6 text-gray-600 dark:text-gray-300">
                        {{ $backModalMessage }}
                    </p>
                    <div class="mt-6 flex justify-end gap-3">
                        <button type="button" onclick="toggleModal('modalKembali')"
                            class="rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700">
                            Batal
                        </button>
                        <a href="{{ $backUrl }}"
                            class="rounded-xl bg-red-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-red-700">
                            Ya, Kembali
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <script src="https://cdn.tiny.cloud/1/rijrac2uxn06a1q296snq7j1fi420fd29r3lc1o12yzq6fwv/tinymce/7/tinymce.min.js"
        referrerpolicy="origin"></script>
    <script>
        function toggleModal(id) {
            document.getElementById(id).classList.toggle('hidden');
        }

        if (window.tinymce) {
            const editor = tinymce.get('content');
            if (editor) {
                editor.remove();
            }

            tinymce.init({
                selector: '#content',
                plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount',
                toolbar: 'undo redo | blocks bold italic underline | alignleft aligncenter alignright | numlist bullist | link image media table | removeformat',
                height: 520,
                content_style: "body { font-family: Arial, sans-serif; font-size: 14px; line-height: 1.7; }"
            });
        }

        document.querySelectorAll('input[type=\"file\"][data-preview-target]').forEach((input) => {
            input.addEventListener('change', (event) => {
                const file = event.target.files && event.target.files[0];
                const preview = document.getElementById(event.target.dataset.previewTarget);
                const emptyState = document.getElementById(event.target.dataset.emptyTarget);

                if (!preview) {
                    return;
                }

                if (file && file.type.startsWith('image/')) {
                    preview.src = URL.createObjectURL(file);
                    preview.classList.remove('hidden');
                    emptyState?.classList.add('hidden');
                } else if (!preview.getAttribute('src')) {
                    preview.classList.add('hidden');
                    emptyState?.classList.remove('hidden');
                }
            });
        });
    </script>
</div>
