@section('namePage', 'Tambah Berita Newsmaker 23')

<x-app-layout>
    @include('newsmaker23._ui')
    <div class="nm23 px-4 sm:px-6 lg:px-8 py-10 w-full max-w-6xl mx-auto">
        <div class="nm-hero">
            <div class="flex flex-wrap items-start justify-between gap-6">
                <div class="space-y-3">
                    <span class="nm-chip">Form Berita</span>
                    <div>
                        <div class="nm-kicker">Tambah Berita</div>
                        <h1 class="nm-title text-3xl sm:text-4xl">Bangun Cerita yang Rapi</h1>
                        <p class="text-sm sm:text-base text-blue-100 max-w-3xl">
                            Isi data berita dengan lengkap: kategori, judul, isi Indonesia dan Inggris, author, source, serta gambar.
                        </p>
                    </div>
                </div>
                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('newsmaker23.berita.index') }}" class="nm-btn nm-btn-ghost">
                        Kembali
                    </a>
                    <button type="submit" form="newsForm" class="nm-btn nm-btn-primary">
                        <i class="fa-solid fa-plus"></i>
                        Simpan Berita
                    </button>
                </div>
            </div>
        </div>

        <form id="newsForm" action="{{ route('newsmaker23.berita.store') }}" method="POST" enctype="multipart/form-data" class="mt-10">
            @csrf

            <div class="nm-card p-6 sm:p-8 space-y-8">
                <div>
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100">Struktur</h2>
                    <p class="nm-muted">Pilih main dan sub category sesuai struktur berita.</p>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="nm-field space-y-2">
                        <label for="main_category_id">Main Category</label>
                        <select id="main_category_id" name="main_category_id" class="nm-select @error('main_category_id') border-red-500 @enderror" required @if(!empty($selectedMainId)) disabled @endif>
                            <option value="">Pilih Main Category</option>
                            @foreach ($mainCategories as $mainCategory)
                                <option value="{{ $mainCategory->id }}" @selected(old('main_category_id', $selectedMainId) == $mainCategory->id)>
                                    {{ $mainCategory->name }}
                                </option>
                            @endforeach
                        </select>
                        @if (!empty($selectedMainId))
                            <input type="hidden" name="main_category_id" value="{{ $selectedMainId }}">
                        @endif
                        @error('main_category_id')
                            <p class="text-red-500 text-sm">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="nm-field space-y-2">
                        <label for="sub_category_id">Sub Category</label>
                        <select id="sub_category_id" name="sub_category_id" class="nm-select @error('sub_category_id') border-red-500 @enderror" required @if(!empty($selectedSubId)) disabled @endif>
                            <option value="">Pilih Sub Category</option>
                            @foreach ($subCategories as $subCategory)
                                <option value="{{ $subCategory->id }}" data-main="{{ $subCategory->main_category_id }}" @selected(old('sub_category_id', $selectedSubId) == $subCategory->id)>
                                    {{ $subCategory->name }} ({{ $subCategory->mainCategory?->name ?? '-' }})
                                </option>
                            @endforeach
                        </select>
                        @if (!empty($selectedSubId))
                            <input type="hidden" name="sub_category_id" value="{{ $selectedSubId }}">
                        @endif
                        @error('sub_category_id')
                            <p class="text-red-500 text-sm">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="border-t border-gray-200 dark:border-gray-700 pt-6 space-y-6">
                    <div>
                        <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100">Meta & Media</h2>
                        <p class="nm-muted">Lengkapi data author, source, dan gambar.</p>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="nm-field space-y-2 md:col-span-1">
                            <label for="image">Gambar</label>
                            <input type="file" id="image" name="image" accept="image/*" class="nm-input @error('image') border-red-500 @enderror" required>
                            <p class="nm-muted">Ukuran disarankan 1280x720.</p>
                            @error('image')
                                <p class="text-red-500 text-sm">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="nm-field space-y-2">
                            <label for="author">Author</label>
                            <input type="text" id="author" name="author" value="{{ old('author') }}" class="nm-input @error('author') border-red-500 @enderror" placeholder="Masukkan nama author..." required>
                            @error('author')
                                <p class="text-red-500 text-sm">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="nm-field space-y-2">
                            <label for="source">Source</label>
                            <input type="text" id="source" name="source" value="{{ old('source') }}" class="nm-input @error('source') border-red-500 @enderror" placeholder="Masukkan sumber berita..." required>
                            @error('source')
                                <p class="text-red-500 text-sm">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="border-t border-gray-200 dark:border-gray-700 pt-6 space-y-6">
                    <div>
                        <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100">Judul</h2>
                        <p class="nm-muted">Buat judul yang ringkas dan jelas.</p>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="nm-field space-y-2">
                            <label for="title_id">Judul Indonesia</label>
                            <input type="text" id="title_id" name="title_id" value="{{ old('title_id') }}" class="nm-input @error('title_id') border-red-500 @enderror" placeholder="Masukkan judul Indonesia..." required>
                            @error('title_id')
                                <p class="text-red-500 text-sm">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="nm-field space-y-2">
                            <label for="title_en">Judul Inggris</label>
                            <input type="text" id="title_en" name="title_en" value="{{ old('title_en') }}" class="nm-input @error('title_en') border-red-500 @enderror" placeholder="Masukkan judul Inggris..." required>
                            @error('title_en')
                                <p class="text-red-500 text-sm">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="border-t border-gray-200 dark:border-gray-700 pt-6 space-y-6">
                    <div>
                        <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100">Isi Berita</h2>
                        <p class="nm-muted">Gunakan struktur paragraf yang mudah dibaca.</p>
                    </div>
                    <div class="nm-field space-y-2">
                        <label for="content_id">Isi Indonesia</label>
                        <textarea id="content_id" name="content_id" rows="10" class="nm-textarea @error('content_id') border-red-500 @enderror" placeholder="Masukkan isi berita Indonesia..." required>{{ old('content_id') }}</textarea>
                        @error('content_id')
                            <p class="text-red-500 text-sm">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="nm-field space-y-2">
                        <label for="content_en">Isi Inggris</label>
                        <textarea id="content_en" name="content_en" rows="10" class="nm-textarea @error('content_en') border-red-500 @enderror" placeholder="Masukkan isi berita Inggris..." required>{{ old('content_en') }}</textarea>
                        @error('content_en')
                            <p class="text-red-500 text-sm">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="mt-8 flex flex-wrap justify-end gap-3">
                <a href="{{ route('newsmaker23.berita.index') }}" class="nm-btn nm-btn-ghost">Kembali</a>
                <button type="submit" class="nm-btn nm-btn-primary">
                    <i class="fa-solid fa-save"></i>
                    Simpan Berita
                </button>
            </div>
        </form>

        <script src="https://cdn.tiny.cloud/1/uym9uq23mm10dvjizee74f90qbsu25mwhzuz0chkptvo5ref/tinymce/8/tinymce.min.js" referrerpolicy="origin" crossorigin="anonymous"></script>
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const mainSelect = document.getElementById('main_category_id');
                const subSelect = document.getElementById('sub_category_id');
                const allOptions = Array.from(subSelect.querySelectorAll('option')).slice(1);

                const applyFilter = (mainId) => {
                    let hasVisible = false;
                    allOptions.forEach((option) => {
                        const isMatch = option.dataset.main === mainId;
                        option.hidden = !isMatch;
                        if (isMatch) {
                            hasVisible = true;
                        }
                    });

                    if (!mainId) {
                        allOptions.forEach((option) => (option.hidden = true));
                        subSelect.value = '';
                        subSelect.disabled = true;
                        return;
                    }

                    if (subSelect.disabled && mainId) {
                        return;
                    }

                    subSelect.disabled = !hasVisible;
                    if (!hasVisible) {
                        subSelect.value = '';
                    } else if (subSelect.value) {
                        const selected = subSelect.querySelector(`option[value="${subSelect.value}"]`);
                        if (selected && selected.hidden) {
                            subSelect.value = '';
                        }
                    }
                };

                mainSelect.addEventListener('change', (event) => {
                    applyFilter(event.target.value);
                });

                applyFilter(mainSelect.value);

                if (window.tinymce) {
                    tinymce.init({
                        selector: '#content_id,#content_en',
                        height: 360,
                        menubar: false,
                        plugins: 'lists link table code wordcount autoresize',
                        toolbar: 'undo redo | blocks | bold italic underline | alignleft aligncenter alignright | bullist numlist | link table | code',
                        content_style: "body { font-family: 'Source Serif 4', serif; font-size: 16px; }"
                    });
                }
            });
        </script>
    </div>
</x-app-layout>