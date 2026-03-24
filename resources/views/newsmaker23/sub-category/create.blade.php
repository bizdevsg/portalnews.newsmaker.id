@section('namePage', 'Tambah Sub Category')

<x-app-layout>
    @include('newsmaker23._ui')
    <div class="nm23 px-4 sm:px-6 lg:px-8 py-8 w-full max-w-4xl mx-auto">
        <div class="nm-hero">
            <div class="flex flex-wrap items-start justify-between gap-6">
                <div class="space-y-3">
                    <span class="nm-chip">Sub Category</span>
                    <div>
                        <div class="nm-kicker">Tambah</div>
                        <h1 class="nm-title text-3xl sm:text-4xl">Sub Category Baru</h1>
                        <p class="text-sm sm:text-base text-blue-100 max-w-xl">
                            Hubungkan sub category dengan main category untuk membuat struktur berita yang rapi.
                        </p>
                    </div>
                </div>
                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('newsmaker23.sub-category.index') }}" class="nm-btn nm-btn-ghost">Kembali</a>
                    <button type="submit" form="subForm" class="nm-btn nm-btn-primary">
                        <i class="fa-solid fa-plus"></i>
                        Simpan
                    </button>
                </div>
            </div>
        </div>

        <form id="subForm" action="{{ route('newsmaker23.sub-category.store') }}" method="POST" class="mt-8">
            @csrf
            <div class="nm-card p-6 space-y-4">
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
                    <label for="name">Nama Sub Category</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" class="nm-input @error('name') border-red-500 @enderror" placeholder="Masukkan nama sub category..." required>
                    <p class="nm-muted">Maks. 100 karakter.</p>
                    @error('name')
                        <p class="text-red-500 text-sm">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </form>
    </div>
</x-app-layout>