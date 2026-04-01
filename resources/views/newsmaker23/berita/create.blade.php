@section('namePage', 'Tambah Berita Newsmaker23')

<x-app-layout>
    @php
        $backUrl = route('newsmaker23.main-category.show', $selectedMainCategory->slug);
    @endphp

    <form id="newsForm" action="{{ route('newsmaker23.berita.store') }}" method="POST" enctype="multipart/form-data" class="w-full px-4 py-8 sm:px-6 lg:px-8">
        @csrf
        <input type="hidden" name="main_category_id" value="{{ old('main_category_id', $selectedMainCategory->id) }}">

        <section class="overflow-hidden rounded-[32px] bg-gradient-to-br from-slate-950 via-slate-900 to-blue-900 px-6 py-8 shadow-2xl ring-1 ring-white/10 sm:px-8 lg:px-10">
            <div class="flex flex-wrap items-start justify-between gap-6">
                <div class="space-y-4">
                    <span class="inline-flex items-center rounded-full border border-white/10 bg-white/10 px-3 py-1 text-xs font-semibold uppercase tracking-[0.22em] text-blue-100">
                        Form Berita
                    </span>
                    <div class="space-y-2">
                        <p class="text-xs font-semibold uppercase tracking-[0.32em] text-slate-300">Tambah</p>
                        <h1 class="text-3xl font-bold tracking-tight text-white sm:text-4xl">Buat berita dua bahasa</h1>
                        <p class="text-sm leading-6 text-blue-100 sm:text-base">
                            Anda sedang menambahkan berita ke kategori {{ $selectedMainCategory->name }}. Lengkapi meta artikel, judul Indonesia dan Inggris, serta konten editor untuk kedua bahasa.
                        </p>
                    </div>
                </div>

                <div class="flex flex-wrap gap-3">
                    <a href="{{ $backUrl }}" class="inline-flex items-center justify-center rounded-xl border border-white/15 bg-white/5 px-5 py-3 text-sm font-semibold text-white transition hover:bg-white/10">
                        Kembali
                    </a>
                    <button type="submit" class="inline-flex items-center justify-center rounded-xl bg-white px-5 py-3 text-sm font-semibold text-slate-900 transition hover:bg-blue-50">
                        <i class="fa-solid fa-plus mr-2"></i>
                        Simpan Berita
                    </button>
                </div>
            </div>
        </section>

        @if ($errors->any())
            <div class="mt-6 rounded-2xl border border-rose-200 bg-rose-50 px-5 py-4 text-sm text-rose-700 shadow-sm dark:border-rose-500/20 dark:bg-rose-500/10 dark:text-rose-200">
                <p class="font-semibold">Masih ada field berita yang perlu diperbaiki.</p>
            </div>
        @endif

        <div class="mt-8 space-y-6">
            <section class="rounded-[28px] border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900 sm:p-8">
                <div class="grid grid-cols-1 gap-6 xl:grid-cols-[1.05fr_0.95fr]">
                    <div class="space-y-2">
                        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500 dark:text-slate-400">Kategori</p>
                        <h2 class="text-2xl font-bold text-slate-900 dark:text-slate-100">Penempatan artikel</h2>
                        <p class="text-sm leading-6 text-slate-500 dark:text-slate-400">
                            Artikel baru akan langsung masuk ke kategori yang sedang Anda buka.
                        </p>
                    </div>

                    <div>
                        <p class="mb-2 block text-sm font-semibold text-slate-900 dark:text-slate-100">
                            Kategori aktif
                        </p>
                        <div class="rounded-2xl border border-slate-300 bg-slate-50 px-4 py-4 dark:border-slate-700 dark:bg-slate-950">
                            <p class="text-base font-semibold text-slate-900 dark:text-slate-100">
                                {{ $selectedMainCategory->name }}
                            </p>
                            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                                Jika ingin menambah berita ke kategori lain, kembali dulu ke halaman kategori lalu buka kategori tujuan.
                            </p>
                        </div>
                        @error('main_category_id')
                            <p class="mt-2 text-sm font-medium text-rose-600 dark:text-rose-300">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </section>

            <section class="rounded-[28px] border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900 sm:p-8">
                <div class="space-y-6">
                    <div class="space-y-2">
                        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500 dark:text-slate-400">Meta</p>
                        <h2 class="text-2xl font-bold text-slate-900 dark:text-slate-100">Media dan identitas</h2>
                    </div>

                    <div class="grid grid-cols-1 gap-5 xl:grid-cols-3">
                        <div>
                            <label for="image" class="mb-2 block text-sm font-semibold text-slate-900 dark:text-slate-100">
                                Gambar utama
                            </label>
                            <input type="file" id="image" name="image" accept="image/*" class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-slate-900 outline-none transition file:mr-4 file:rounded-xl file:border-0 file:bg-slate-900 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-white hover:file:bg-slate-800 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100 @error('image') border-rose-500 @enderror" required>
                            @error('image')
                                <p class="mt-2 text-sm font-medium text-rose-600 dark:text-rose-300">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="author" class="mb-2 block text-sm font-semibold text-slate-900 dark:text-slate-100">
                                Author
                            </label>
                            <input type="text" id="author" name="author" value="{{ old('author') }}" placeholder="Masukkan nama author" class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-slate-900 outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100 @error('author') border-rose-500 @enderror" required>
                            @error('author')
                                <p class="mt-2 text-sm font-medium text-rose-600 dark:text-rose-300">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="source" class="mb-2 block text-sm font-semibold text-slate-900 dark:text-slate-100">
                                Source
                            </label>
                            <input type="text" id="source" name="source" value="{{ old('source') }}" placeholder="Masukkan sumber berita" class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-slate-900 outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100 @error('source') border-rose-500 @enderror" required>
                            @error('source')
                                <p class="mt-2 text-sm font-medium text-rose-600 dark:text-rose-300">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </section>

            <section class="rounded-[28px] border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900 sm:p-8">
                <div class="space-y-6">
                    <div class="space-y-2">
                        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500 dark:text-slate-400">Judul</p>
                        <h2 class="text-2xl font-bold text-slate-900 dark:text-slate-100">Dua versi headline</h2>
                    </div>

                    <div class="grid grid-cols-1 gap-5 xl:grid-cols-2">
                        <div>
                            <label for="title_id" class="mb-2 block text-sm font-semibold text-slate-900 dark:text-slate-100">
                                Judul Indonesia
                            </label>
                            <input type="text" id="title_id" name="title_id" value="{{ old('title_id') }}" placeholder="Masukkan judul Indonesia" class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-slate-900 outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100 @error('title_id') border-rose-500 @enderror" required>
                            @error('title_id')
                                <p class="mt-2 text-sm font-medium text-rose-600 dark:text-rose-300">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="title_en" class="mb-2 block text-sm font-semibold text-slate-900 dark:text-slate-100">
                                Judul Inggris
                            </label>
                            <input type="text" id="title_en" name="title_en" value="{{ old('title_en') }}" placeholder="Masukkan judul Inggris" class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-slate-900 outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100 @error('title_en') border-rose-500 @enderror" required>
                            @error('title_en')
                                <p class="mt-2 text-sm font-medium text-rose-600 dark:text-rose-300">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </section>

            <section class="rounded-[28px] border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900 sm:p-8">
                <div class="space-y-6">
                    <div class="space-y-2">
                        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500 dark:text-slate-400">Konten</p>
                        <h2 class="text-2xl font-bold text-slate-900 dark:text-slate-100">Editor Indonesia dan Inggris</h2>
                    </div>

                    <div class="space-y-6">
                        <div class="rounded-[24px] border border-slate-200 bg-slate-50 p-4 dark:border-slate-800 dark:bg-slate-950/40">
                            <label for="content_id" class="mb-3 block text-sm font-semibold text-slate-900 dark:text-slate-100">
                                Isi Indonesia
                            </label>
                            <textarea id="content_id" name="content_id" rows="10" class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-slate-900 outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100 @error('content_id') border-rose-500 @enderror" placeholder="Masukkan isi berita Indonesia...">{{ old('content_id') }}</textarea>
                            @error('content_id')
                                <p class="mt-2 text-sm font-medium text-rose-600 dark:text-rose-300">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="rounded-[24px] border border-slate-200 bg-slate-50 p-4 dark:border-slate-800 dark:bg-slate-950/40">
                            <label for="content_en" class="mb-3 block text-sm font-semibold text-slate-900 dark:text-slate-100">
                                Isi Inggris
                            </label>
                            <textarea id="content_en" name="content_en" rows="10" class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-slate-900 outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100 @error('content_en') border-rose-500 @enderror" placeholder="Masukkan isi berita Inggris...">{{ old('content_en') }}</textarea>
                            @error('content_en')
                                <p class="mt-2 text-sm font-medium text-rose-600 dark:text-rose-300">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </section>

            <div class="flex flex-wrap justify-end gap-3">
                <a href="{{ $backUrl }}" class="inline-flex items-center justify-center rounded-xl border border-slate-300 bg-white px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200 dark:hover:bg-slate-800">
                    Kembali
                </a>
                <button type="submit" class="inline-flex items-center justify-center rounded-xl bg-slate-900 px-5 py-3 text-sm font-semibold text-white transition hover:bg-slate-800 dark:bg-slate-100 dark:text-slate-900 dark:hover:bg-white">
                    Simpan Berita
                </button>
            </div>
        </div>
    </form>

    <script src="https://cdn.tiny.cloud/1/rijrac2uxn06a1q296snq7j1fi420fd29r3lc1o12yzq6fwv/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const form = document.getElementById('newsForm');

            if (window.tinymce) {
                tinymce.init({
                    selector: '#content_id,#content_en',
                    plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount',
                    toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table | align lineheight | numlist bullist indent outdent | emoticons charmap | removeformat',
                    height: 500
                });
            }

            form?.addEventListener('submit', () => {
                if (window.tinymce) {
                    tinymce.triggerSave();
                }
            });
        });
    </script>
</x-app-layout>
