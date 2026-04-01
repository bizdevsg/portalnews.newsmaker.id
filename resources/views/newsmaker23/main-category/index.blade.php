@section('namePage', 'Kategori Newsmaker23')

<x-app-layout>
    <div class="w-full px-4 py-8 sm:px-6 lg:px-8">
        <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <div class="flex flex-wrap items-start justify-between gap-4">
                <div class="space-y-2">
                    <p class="text-sm font-semibold text-slate-500 dark:text-slate-400">Newsmaker23</p>
                    <h1 class="text-3xl font-bold text-slate-900 dark:text-slate-100">Kategori</h1>
                    <p class="text-sm text-slate-500 dark:text-slate-400">
                        Kelola kategori utama untuk mengelompokkan berita bilingual. Tambah berita dilakukan dari halaman detail tiap kategori.
                    </p>
                </div>

                <div class="flex flex-wrap gap-3">
                    <a
                        href="{{ route('newsmaker23.index') }}"
                        class="inline-flex items-center justify-center rounded-xl border border-slate-300 px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-800">
                        Dashboard
                    </a>
                    <a
                        href="{{ route('newsmaker23.berita.index') }}"
                        class="inline-flex items-center justify-center rounded-xl border border-slate-300 px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-800">
                        Semua Berita
                    </a>
                    <a
                        href="{{ route('newsmaker23.main-category.create') }}"
                        class="inline-flex items-center justify-center rounded-xl bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-slate-800 dark:bg-slate-100 dark:text-slate-900 dark:hover:bg-white">
                        Tambah Kategori
                    </a>
                </div>
            </div>
        </section>

        @if (session('success'))
            <div id="successAlert" class="mt-6 flex items-start justify-between gap-4 rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-emerald-700 shadow-sm dark:border-emerald-500/20 dark:bg-emerald-500/10 dark:text-emerald-200">
                <div class="flex items-start gap-3">
                    <i class="fa-solid fa-circle-check mt-0.5"></i>
                    <p class="text-sm font-medium leading-6">{{ session('success') }}</p>
                </div>
                <button type="button" id="closeSuccessAlert" class="inline-flex h-9 w-9 items-center justify-center rounded-full transition hover:bg-emerald-100 dark:hover:bg-emerald-500/10">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
        @endif

        @if (session('info'))
            <div id="infoAlert" class="mt-6 flex items-start justify-between gap-4 rounded-2xl border border-blue-200 bg-blue-50 px-5 py-4 text-blue-700 shadow-sm dark:border-blue-500/20 dark:bg-blue-500/10 dark:text-blue-200">
                <div class="flex items-start gap-3">
                    <i class="fa-solid fa-circle-info mt-0.5"></i>
                    <p class="text-sm font-medium leading-6">{{ session('info') }}</p>
                </div>
                <button type="button" id="closeInfoAlert" class="inline-flex h-9 w-9 items-center justify-center rounded-full transition hover:bg-blue-100 dark:hover:bg-blue-500/10">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
        @endif

        <section class="mt-6">
            @if ($categories->isEmpty())
                <div class="rounded-2xl border border-dashed border-slate-300 bg-white px-6 py-12 text-center shadow-sm dark:border-slate-700 dark:bg-slate-900">
                    <img src="{{ asset('assets/hand-drawn-no-data-concept.png') }}" alt="No Data" class="mx-auto h-40 object-contain">
                    <p class="mt-4 text-sm text-slate-500 dark:text-slate-400">Belum ada kategori.</p>
                </div>
            @else
                <div class="grid grid-cols-1 gap-5 md:grid-cols-2 xl:grid-cols-3">
                    @foreach ($categories as $category)
                        <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                            <p class="text-sm font-semibold text-slate-900 dark:text-slate-100">{{ $category->name }}</p>
                            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">{{ $category->articles_count }} berita</p>

                            <form id="delete-category-form-{{ $category->id }}" action="{{ route('newsmaker23.main-category.destroy', $category->id) }}" method="POST" class="hidden">
                                @csrf
                                @method('DELETE')
                            </form>

                            <div class="mt-5 grid grid-cols-1 gap-3 sm:grid-cols-3">
                                <a
                                    href="{{ route('newsmaker23.main-category.show', $category->slug) }}"
                                    class="inline-flex items-center justify-center rounded-xl border border-slate-300 px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-800 sm:col-span-3">
                                    Lihat Berita
                                </a>
                                <a
                                    href="{{ route('newsmaker23.main-category.edit', $category->id) }}"
                                    class="inline-flex items-center justify-center rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-emerald-700 sm:col-span-2">
                                    Edit
                                </a>
                                <button
                                    type="button"
                                    class="js-delete-category inline-flex items-center justify-center rounded-xl bg-rose-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-rose-700"
                                    data-delete-form="delete-category-form-{{ $category->id }}"
                                    data-delete-name="{{ $category->name }}">
                                    Hapus
                                </button>
                            </div>
                        </article>
                    @endforeach
                </div>
            @endif
        </section>
    </div>

    <div id="deleteCategoryModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-950/50 px-4" aria-hidden="true">
        <div class="w-full rounded-2xl border border-slate-200 bg-white p-6 shadow-xl dark:border-slate-800 dark:bg-slate-900 md:w-[28rem]">
            <h2 class="text-lg font-bold text-slate-900 dark:text-slate-100">Hapus Kategori</h2>
            <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">
                Kategori <span id="deleteCategoryName" class="font-semibold text-slate-900 dark:text-slate-100"></span> akan dihapus beserta artikelnya.
            </p>

            <div class="mt-6 flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
                <button type="button" id="cancelDeleteCategoryButton" class="inline-flex items-center justify-center rounded-xl border border-slate-300 px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-800">
                    Batal
                </button>
                <button type="button" id="confirmDeleteCategoryButton" class="inline-flex items-center justify-center rounded-xl bg-rose-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-rose-700 disabled:opacity-70">
                    Hapus
                </button>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const deleteModal = document.getElementById('deleteCategoryModal');
            const deleteName = document.getElementById('deleteCategoryName');
            const confirmButton = document.getElementById('confirmDeleteCategoryButton');
            const cancelButton = document.getElementById('cancelDeleteCategoryButton');
            const successAlert = document.getElementById('successAlert');
            const closeSuccessAlert = document.getElementById('closeSuccessAlert');
            const infoAlert = document.getElementById('infoAlert');
            const closeInfoAlert = document.getElementById('closeInfoAlert');
            const deleteButtons = document.querySelectorAll('.js-delete-category');

            let activeDeleteForm = null;

            const openModal = (formId, name) => {
                const form = document.getElementById(formId);
                if (!deleteModal || !deleteName || !form) {
                    return;
                }

                activeDeleteForm = form;
                deleteName.textContent = name || '';
                confirmButton.disabled = false;
                confirmButton.textContent = 'Hapus';
                deleteModal.classList.remove('hidden');
                deleteModal.classList.add('flex');
                document.body.classList.add('overflow-hidden');
            };

            const closeModal = () => {
                activeDeleteForm = null;
                deleteModal?.classList.add('hidden');
                deleteModal?.classList.remove('flex');
                document.body.classList.remove('overflow-hidden');
            };

            deleteButtons.forEach((button) => {
                button.addEventListener('click', () => {
                    openModal(button.dataset.deleteForm, button.dataset.deleteName);
                });
            });

            confirmButton?.addEventListener('click', () => {
                if (!activeDeleteForm) {
                    closeModal();
                    return;
                }

                confirmButton.disabled = true;
                confirmButton.textContent = 'Menghapus...';
                activeDeleteForm.submit();
            });

            cancelButton?.addEventListener('click', closeModal);

            deleteModal?.addEventListener('click', (event) => {
                if (event.target === deleteModal) {
                    closeModal();
                }
            });

            document.addEventListener('keydown', (event) => {
                if (event.key === 'Escape') {
                    closeModal();
                }
            });

            closeSuccessAlert?.addEventListener('click', () => {
                if (!successAlert) {
                    return;
                }

                successAlert.style.opacity = '0';
                setTimeout(() => successAlert.remove(), 300);
            });

            closeInfoAlert?.addEventListener('click', () => {
                if (!infoAlert) {
                    return;
                }

                infoAlert.style.opacity = '0';
                setTimeout(() => infoAlert.remove(), 300);
            });
        });
    </script>
</x-app-layout>
