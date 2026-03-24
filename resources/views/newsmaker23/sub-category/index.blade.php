@section('namePage', 'Sub Category Newsmaker 23')

<x-app-layout>
    @include('newsmaker23._ui')
    <div class="nm23 px-4 sm:px-6 lg:px-8 py-8 w-full max-w-7xl mx-auto">
        <div class="nm-hero">
            <div class="flex flex-wrap items-start justify-between gap-6">
                <div class="space-y-4">
                    <span class="nm-chip">Newsmaker 23</span>
                    <div>
                        <div class="nm-kicker">Sub Category</div>
                        <h1 class="nm-title text-3xl sm:text-4xl">Struktur Turunan</h1>
                        <p class="text-sm sm:text-base text-blue-100 max-w-2xl">
                            Kelola sub category yang terhubung dengan main category. Dari sini kamu bisa langsung masuk ke berita.
                        </p>
                    </div>
                </div>
                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('newsmaker23.sub-category.create') }}" class="nm-btn nm-btn-primary">
                        <i class="fa-solid fa-plus"></i>
                        Tambah Sub Category
                    </a>
                    <a href="{{ route('newsmaker23.main-category.index') }}" class="nm-btn nm-btn-ghost">
                        Main Category
                    </a>
                </div>
            </div>
        </div>

        @if (session('success'))
            <div id="successAlert" class="mt-6 nm-card p-4 border-l-4 border-emerald-500">
                <div class="flex items-center justify-between gap-3">
                    <div class="flex items-center gap-2 text-emerald-600 dark:text-emerald-300 text-sm">
                        <i class="fa-solid fa-circle-check"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                    <button onclick="closeAlert()" class="text-emerald-600 dark:text-emerald-300">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
            </div>
        @endif

        <div class="mt-8">
            @if ($subCategories->isEmpty())
                <div class="nm-panel p-10 flex flex-col items-center gap-3 text-center">
                    <img src="{{ asset('assets/hand-drawn-no-data-concept.png') }}" alt="No Data" class="h-48 rounded-lg">
                    <p class="text-lg text-gray-600 dark:text-gray-300">Belum ada sub category.</p>
                </div>
            @else
                <div class="nm-grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3">
                    @foreach ($subCategories as $subCategory)
                        <div class="nm-card p-5 flex flex-col gap-4">
                            <div class="flex items-start justify-between gap-3">
                                <div>
                                    <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100">{{ $subCategory->name }}</h2>
                                    <p class="nm-muted mt-1">Main: {{ $subCategory->mainCategory?->name ?? '-' }}</p>
                                    <p class="nm-muted">{{ $subCategory->articles_count }} Berita</p>
                                </div>
                                <span class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-amber-50 text-amber-600 dark:bg-amber-500/20 dark:text-amber-200">
                                    <i class="fa-solid fa-sitemap"></i>
                                </span>
                            </div>

                            <a href="{{ route('newsmaker23.sub-category.show', $subCategory->id) }}"
                                class="nm-btn nm-btn-ghost w-full">
                                Lihat Berita
                            </a>

                            <div class="grid grid-cols-3 gap-2">
                                <a href="{{ route('newsmaker23.berita.create', ['main_category_id' => $subCategory->main_category_id, 'sub_category_id' => $subCategory->id]) }}"
                                    class="nm-btn nm-btn-primary">
                                    Berita
                                </a>
                                <a href="{{ route('newsmaker23.sub-category.edit', $subCategory->id) }}"
                                    class="nm-btn nm-btn-success">
                                    Edit
                                </a>
                                <button onclick="showDeleteModal({{ $subCategory->id }}, '{{ $subCategory->name }}')"
                                    class="nm-btn nm-btn-danger">
                                    Hapus
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <div id="deleteModal" class="fixed inset-0 bg-black/50 flex justify-center items-center hidden z-100 dark:bg-gray-900/75">
            <div class="nm-card p-6 w-full max-w-md">
                <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-2">Konfirmasi Hapus</h2>
                <p class="text-gray-600 dark:text-gray-200">Apakah Anda yakin ingin menghapus sub category <b id="categoryName"></b>?</p>
                <form id="deleteForm" method="POST" class="mt-4">
                    @csrf
                    @method('DELETE')
                    <div class="flex justify-end gap-2">
                        <button type="button" onclick="hideDeleteModal()" class="nm-btn nm-btn-ghost">Batal</button>
                        <button type="submit" class="nm-btn nm-btn-danger">Hapus</button>
                    </div>
                </form>
            </div>
        </div>

        <script>
            function showDeleteModal(id, name) {
                const modal = document.getElementById("deleteModal");
                const form = document.getElementById("deleteForm");
                const categoryName = document.getElementById("categoryName");

                form.action = `{{ route('newsmaker23.sub-category.destroy', ':id') }}`.replace(':id', id);
                categoryName.textContent = name;

                modal.classList.remove("hidden");
            }

            function hideDeleteModal() {
                document.getElementById("deleteModal").classList.add("hidden");
            }

            function closeAlert() {
                const alert = document.getElementById("successAlert");
                if (alert) {
                    alert.style.opacity = "0";
                    setTimeout(() => alert.remove(), 300);
                }
            }
        </script>
    </div>
</x-app-layout>