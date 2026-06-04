@section('namePage', 'Edit Berita Pasar Indonesia')

<x-app-layout>
    @php
        $backUrl = $categories->contains('slug', $item->category)
            ? route('pasar-indonesia.berita.kategori.show', $item->category)
            : route('pasar-indonesia.berita.index');
    @endphp

    <form id="newsForm" action="{{ route('pasar-indonesia.berita.update', $item->id) }}" method="POST"
        enctype="multipart/form-data" class="w-full px-4 py-8 sm:px-6 lg:px-8">
        @csrf
        @method('PUT')

        <section
            class="overflow-hidden rounded-[32px] bg-gradient-to-br from-slate-950 via-slate-900 to-indigo-900 px-6 py-8 shadow-2xl ring-1 ring-white/10 sm:px-8 lg:px-10">
            <div class="flex flex-wrap items-start justify-between gap-6">
                <div class="space-y-4">
                    <span
                        class="inline-flex items-center rounded-full border border-white/10 bg-white/10 px-3 py-1 text-xs font-semibold uppercase tracking-[0.22em] text-indigo-100">
                        Form Berita
                    </span>
                    <div class="space-y-2">
                        <p class="text-xs font-semibold uppercase tracking-[0.32em] text-slate-300">Edit</p>
                        <h1 class="text-3xl font-bold tracking-tight text-white sm:text-4xl">Perbarui berita dua bahasa
                        </h1>
                        <p class="text-sm leading-6 text-indigo-100 sm:text-base">
                            Edit kategori, meta artikel, judul, dan isi bilingual agar berita tetap konsisten.
                        </p>
                    </div>
                </div>

                <div class="flex flex-wrap gap-3">
                    <a href="{{ $backUrl }}"
                        class="inline-flex items-center justify-center rounded-xl border border-white/15 bg-white/5 px-5 py-3 text-sm font-semibold text-white transition hover:bg-white/10">
                        Kembali
                    </a>
                    <button type="submit"
                        class="inline-flex items-center justify-center rounded-xl bg-white px-5 py-3 text-sm font-semibold text-slate-900 transition hover:bg-indigo-50">
                        <i class="fa-solid fa-floppy-disk mr-2"></i>
                        Simpan Perubahan
                    </button>
                </div>
            </div>
        </section>

        @if ($errors->any())
            <div
                class="mt-6 rounded-2xl border border-rose-200 bg-rose-50 px-5 py-4 text-sm text-rose-700 shadow-sm dark:border-rose-500/20 dark:bg-rose-500/10 dark:text-rose-200">
                <p class="font-semibold">Masih ada perubahan yang perlu diperbaiki sebelum disimpan.</p>
            </div>
        @endif

        <div class="mt-8 space-y-6">
            <section
                class="rounded-[28px] border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900 sm:p-8">
                <div class="grid grid-cols-1 gap-6 xl:grid-cols-[1.05fr_0.95fr]">
                    <div class="space-y-2">
                        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500 dark:text-slate-400">
                            Kategori</p>
                        <h2 class="text-2xl font-bold text-slate-900 dark:text-slate-100">Penempatan artikel</h2>
                        <p class="text-sm leading-6 text-slate-500 dark:text-slate-400">
                            Pastikan artikel tetap berada di kategori yang tepat setelah diperbarui.
                        </p>
                    </div>

                    <div>
                        <label for="category_id"
                            class="mb-2 block text-sm font-semibold text-slate-900 dark:text-slate-100">
                            Kategori
                        </label>
                        <select id="category_id" name="category_id"
                            class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-slate-900 outline-none transition focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100 @error('category_id') border-rose-500 @enderror"
                            required>
                            <option value="">Pilih kategori</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" @selected(old('category_id', optional($categories->firstWhere('slug', $item->category))->id) == $category->id)>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <p class="mt-2 text-sm font-medium text-rose-600 dark:text-rose-300">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </section>

            <section
                class="rounded-[28px] border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900 sm:p-8">
                <div class="space-y-6">
                    <div class="space-y-2">
                        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500 dark:text-slate-400">
                            Meta</p>
                        <h2 class="text-2xl font-bold text-slate-900 dark:text-slate-100">Media dan identitas</h2>
                    </div>

                    <div class="grid grid-cols-1 gap-5 xl:grid-cols-3">
                        <div>
                            <label for="image"
                                class="mb-2 block text-sm font-semibold text-slate-900 dark:text-slate-100">
                                Gambar utama
                            </label>
                            <input type="file" id="image" name="image" accept="image/*"
                                class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-slate-900 outline-none transition file:mr-4 file:rounded-xl file:border-0 file:bg-slate-900 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-white hover:file:bg-slate-800 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100 @error('image') border-rose-500 @enderror">
                            @if ($item->image)
                                <img src="{{ asset($item->image) }}" alt="{{ $item->title_id }}"
                                    class="mt-4 h-40 w-full rounded-2xl object-cover">
                            @endif
                            @error('image')
                                <p class="mt-2 text-sm font-medium text-rose-600 dark:text-rose-300">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="author"
                                class="mb-2 block text-sm font-semibold text-slate-900 dark:text-slate-100">
                                Author
                            </label>
                            @php
                                $authorInitials = ['MRV', 'ASD', 'YDS', 'ARL', 'CP', 'ALG', 'SRH', 'SNM'];
                                $currentAuthor = old('author', strtoupper((string) ($item->author_initial ?? '')));
                            @endphp
                            <select id="author" name="author"
                                class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-slate-900 outline-none transition focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100 @error('author') border-rose-500 @enderror"
                                required>
                                <option value="">Pilih inisial author</option>
                                @foreach ($authorInitials as $initial)
                                    <option value="{{ $initial }}" @selected($currentAuthor === $initial)>{{ $initial }}</option>
                                @endforeach
                            </select>
                            <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">
                                Pilih salah satu inisial author.
                            </p>
                            @error('author')
                                <p class="mt-2 text-sm font-medium text-rose-600 dark:text-rose-300">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="source"
                                class="mb-2 block text-sm font-semibold text-slate-900 dark:text-slate-100">
                                Source
                            </label>
                            <input type="text" id="source" name="source"
                                value="{{ old('source', $item->source) }}" placeholder="Masukkan sumber berita"
                                class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-slate-900 outline-none transition focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100 @error('source') border-rose-500 @enderror"
                                required>
                            @error('source')
                                <p class="mt-2 text-sm font-medium text-rose-600 dark:text-rose-300">{{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div class="xl:col-span-3">
                            <input type="hidden" name="notif" value="0">
                            <label for="notif" class="inline-flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" id="notif" name="notif" value="1"
                                    class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500"
                                    {{ old('notif', $item->notif) ? 'checked' : '' }}>
                                <span class="text-sm font-semibold text-slate-900 dark:text-slate-100">Kirim Notifikasi
                                    ke API</span>
                            </label>
                            @error('notif')
                                <p class="mt-2 text-sm font-medium text-rose-600 dark:text-rose-300">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="xl:col-span-3">
                            <input type="hidden" name="beranda_api" value="0">
                            <label for="beranda_api" class="inline-flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" id="beranda_api" name="beranda_api" value="1"
                                    class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500"
                                    {{ old('beranda_api', $item->beranda_api) ? 'checked' : '' }}>
                                <span class="text-sm font-semibold text-slate-900 dark:text-slate-100">Kirim ke API
                                    Beranda</span>
                            </label>
                            <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">
                                Centang jika berita ini harus ikut tampil di beranda website lain.
                            </p>
                            @error('beranda_api')
                                <p class="mt-2 text-sm font-medium text-rose-600 dark:text-rose-300">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </section>

            <section
                class="rounded-[28px] border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900 sm:p-8">
                <div class="space-y-6">
                    <div class="space-y-2">
                        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500 dark:text-slate-400">
                            Judul</p>
                        <h2 class="text-2xl font-bold text-slate-900 dark:text-slate-100">Dua versi headline</h2>
                    </div>

                    <div class="grid grid-cols-1 gap-5 xl:grid-cols-2">
                        <div>
                            <label for="title_id"
                                class="mb-2 block text-sm font-semibold text-slate-900 dark:text-slate-100">
                                Judul Indonesia
                            </label>
                            <input type="text" id="title_id" name="title_id"
                                value="{{ old('title_id', $item->title_id) }}" placeholder="Masukkan judul Indonesia"
                                class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-slate-900 outline-none transition focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100 @error('title_id') border-rose-500 @enderror"
                                required>
                            @error('title_id')
                                <p class="mt-2 text-sm font-medium text-rose-600 dark:text-rose-300">{{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div>
                            <label for="title_en"
                                class="mb-2 block text-sm font-semibold text-slate-900 dark:text-slate-100">
                                Judul Inggris
                            </label>
                            <input type="text" id="title_en" name="title_en"
                                value="{{ old('title_en', $item->title_en) }}" placeholder="Masukkan judul Inggris"
                                class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-slate-900 outline-none transition focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100 @error('title_en') border-rose-500 @enderror"
                                required>
                            @error('title_en')
                                <p class="mt-2 text-sm font-medium text-rose-600 dark:text-rose-300">{{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>
                </div>
            </section>

            <section
                class="rounded-[28px] border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900 sm:p-8">
                <div class="space-y-6">
                    <div class="space-y-2">
                        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500 dark:text-slate-400">
                            Konten</p>
                        <h2 class="text-2xl font-bold text-slate-900 dark:text-slate-100">Editor Indonesia dan Inggris
                        </h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6q   space-y-6">
                        <div
                            class="rounded-[24px] border border-slate-200 bg-slate-50 p-4 dark:border-slate-800 dark:bg-slate-950/40">
                            <label for="content_id"
                                class="mb-3 block text-sm font-semibold text-slate-900 dark:text-slate-100">
                                Isi Indonesia
                            </label>
                            <textarea id="content_id" name="content_id" rows="10"
                                class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-slate-900 outline-none transition focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100 @error('content_id') border-rose-500 @enderror"
                                placeholder="Masukkan isi berita Indonesia...">{{ old('content_id', $item->content_id) }}</textarea>
                            @error('content_id')
                                <p class="mt-2 text-sm font-medium text-rose-600 dark:text-rose-300">{{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div
                            class="rounded-[24px] border border-slate-200 bg-slate-50 p-4 dark:border-slate-800 dark:bg-slate-950/40">
                            <label for="content_en"
                                class="mb-3 block text-sm font-semibold text-slate-900 dark:text-slate-100">
                                Isi Inggris
                            </label>
                            <textarea id="content_en" name="content_en" rows="10"
                                class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-slate-900 outline-none transition focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100 @error('content_en') border-rose-500 @enderror"
                                placeholder="Masukkan isi berita Inggris...">{{ old('content_en', $item->content_en) }}</textarea>
                            @error('content_en')
                                <p class="mt-2 text-sm font-medium text-rose-600 dark:text-rose-300">{{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>
                </div>
            </section>

            <div class="flex flex-wrap justify-end gap-3">
                <a href="{{ $backUrl }}"
                    class="inline-flex items-center justify-center rounded-xl border border-slate-300 bg-white px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200 dark:hover:bg-slate-800">
                    Kembali
                </a>
                <button type="submit"
                    class="inline-flex items-center justify-center rounded-xl bg-slate-900 px-5 py-3 text-sm font-semibold text-white transition hover:bg-slate-800 dark:bg-slate-100 dark:text-slate-900 dark:hover:bg-white">
                    Simpan Perubahan
                </button>
            </div>
        </div>
    </form>

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
            const form = document.getElementById('newsForm');
            const editorSelector = '#content_id,#content_en';

            if (window.jQuery && jQuery.fn && jQuery.fn.summernote) {
                jQuery(editorSelector).summernote({
                    height: 500,
                    toolbar: [
                        ['style', ['style']],
                        ['font', ['bold', 'italic', 'underline', 'clear']],
                        ['para', ['ul', 'ol', 'paragraph']],
                        ['insert', ['link', 'picture', 'video', 'table']],
                        ['view', ['fullscreen', 'codeview', 'help']],
                    ],
                });
            }

            form?.addEventListener('submit', () => {
                if (window.jQuery && jQuery.fn && jQuery.fn.summernote) {
                    jQuery(editorSelector).each(function() {
                        const $el = jQuery(this);
                        $el.val($el.summernote('code'));
                    });
                }
            });
        });
    </script>
</x-app-layout>
