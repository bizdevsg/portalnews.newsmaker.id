@section('namePage', 'Edit Main Category')

<x-app-layout>
    @include('newsmaker23._ui')
    <div class="nm23 px-4 sm:px-6 lg:px-8 py-8 w-full max-w-4xl mx-auto">
        <div class="nm-hero">
            <div class="flex flex-wrap items-start justify-between gap-6">
                <div class="space-y-3">
                    <span class="nm-chip">Main Category</span>
                    <div>
                        <div class="nm-kicker">Edit</div>
                        <h1 class="nm-title text-3xl sm:text-4xl">Perbarui Main Category</h1>
                        <p class="text-sm sm:text-base text-blue-100 max-w-xl">
                            Ubah nama main category untuk menyesuaikan struktur berita.
                        </p>
                    </div>
                </div>
                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('newsmaker23.main-category.index') }}" class="nm-btn nm-btn-ghost">Kembali</a>
                    <button type="submit" form="mainForm" class="nm-btn nm-btn-primary">
                        <i class="fa-solid fa-pen"></i>
                        Simpan
                    </button>
                </div>
            </div>
        </div>

        <form id="mainForm" action="{{ route('newsmaker23.main-category.update', $category->id) }}" method="POST" class="mt-8">
            @csrf
            @method('PUT')
            <div class="nm-card p-6 space-y-4">
                <div class="nm-field space-y-2">
                    <label for="name">Nama Main Category</label>
                    <input type="text" id="name" name="name" value="{{ old('name', $category->name) }}" class="nm-input @error('name') border-red-500 @enderror" placeholder="Masukkan nama main category..." required>
                    <p class="nm-muted">Maks. 100 karakter.</p>
                    @error('name')
                        <p class="text-red-500 text-sm">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </form>
    </div>
</x-app-layout>