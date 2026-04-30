@section('namePage', 'Berita Newsmaker23')

<x-app-layout>
    <div class="w-full px-4 py-8 sm:px-6 lg:px-8">
        <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <div class="flex flex-wrap items-start justify-between gap-4">
                <div class="space-y-2">
                    <p class="text-sm font-semibold text-slate-500 dark:text-slate-400">Newsmaker23</p>
                    <h1 class="text-3xl font-bold text-slate-900 dark:text-slate-100">Semua Berita</h1>
                    <p class="text-sm text-slate-500 dark:text-slate-400">
                        Semua artikel bilingual yang sudah tersimpan. Untuk menambah berita, masuk dulu ke halaman kategori.
                    </p>
                </div>

                <div class="flex flex-wrap gap-3">
                    <a
                        href="{{ route('newsmaker23.index') }}"
                        class="inline-flex items-center justify-center rounded-xl border border-slate-300 px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-800">
                        Kategori
                    </a>
                    <a
                        href="{{ route('newsmaker23.index') }}"
                        class="inline-flex items-center justify-center rounded-xl border border-slate-300 px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-800">
                        Pilih Kategori
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

        <section class="mt-6">
            @if ($articles->isEmpty())
                <div class="rounded-2xl border border-dashed border-slate-300 bg-white px-6 py-12 text-center shadow-sm dark:border-slate-700 dark:bg-slate-900">
                    <img src="{{ asset('assets/hand-drawn-no-data-concept.png') }}" alt="No Data" class="mx-auto h-40 object-contain">
                    <p class="mt-4 text-sm text-slate-500 dark:text-slate-400">Belum ada berita.</p>
                </div>
            @else
                <div class="grid grid-cols-1 gap-5 md:grid-cols-2 xl:grid-cols-3">
                    @foreach ($articles as $article)
                        <article class="rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">
                            <img src="{{ asset($article->image) }}" alt="{{ $article->title_id }}" class="h-48 w-full rounded-t-2xl object-cover">

                            <div class="p-5">
                                <p class="text-xs font-semibold uppercase text-slate-500 dark:text-slate-400">{{ $article->mainCategory?->name ?? '-' }}</p>
                                <h2 class="mt-2 text-lg font-bold text-slate-900 dark:text-slate-100">{{ $article->title_id }}</h2>
                                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">{{ $article->title_en }}</p>

                                <div class="mt-4 space-y-2 text-sm text-slate-600 dark:text-slate-300">
                                    <p><span class="font-semibold text-slate-900 dark:text-slate-100">Author:</span> {{ $article->author_initial ?? $article->author ?? $article->authorUser?->name ?? '-' }}</p>
                                    <p><span class="font-semibold text-slate-900 dark:text-slate-100">Source:</span> {{ $article->source }}</p>
                                </div>

                                <form id="delete-form-{{ $article->id }}" action="{{ route('newsmaker23.berita.destroy', $article->id) }}" method="POST" class="hidden">
                                    @csrf
                                    @method('DELETE')
                                </form>

                                <div class="mt-5 grid grid-cols-3 gap-3">
                                    <a
                                        href="{{ route('newsmaker23.berita.show', $article->id) }}"
                                        class="inline-flex items-center justify-center rounded-xl border border-slate-300 px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-800">
                                        Preview
                                    </a>
                                    <a
                                        href="{{ route('newsmaker23.berita.edit', $article->id) }}"
                                        class="inline-flex items-center justify-center rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-emerald-700">
                                        Edit
                                    </a>
                                    <button
                                        type="button"
                                        class="js-delete-trigger inline-flex items-center justify-center rounded-xl bg-rose-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-rose-700"
                                        data-delete-form="delete-form-{{ $article->id }}"
                                        data-delete-title="{{ $article->title_id }}">
                                        Hapus
                                    </button>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
            @endif
        </section>
    </div>

    <div id="deleteModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-950/50 px-4" aria-hidden="true">
        <div class="w-full rounded-2xl border border-slate-200 bg-white p-6 shadow-xl dark:border-slate-800 dark:bg-slate-900 md:w-[28rem]">
            <h2 class="text-lg font-bold text-slate-900 dark:text-slate-100">Hapus Berita</h2>
            <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">
                Artikel <span id="deleteArticleTitle" class="font-semibold text-slate-900 dark:text-slate-100"></span> akan dihapus.
            </p>

            <div class="mt-6 flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
                <button type="button" id="cancelDeleteButton" class="inline-flex items-center justify-center rounded-xl border border-slate-300 px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-800">
                    Batal
                </button>
                <button type="button" id="confirmDeleteButton" class="inline-flex items-center justify-center rounded-xl bg-rose-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-rose-700 disabled:opacity-70">
                    Hapus
                </button>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const deleteModal = document.getElementById('deleteModal');
            const deleteArticleTitle = document.getElementById('deleteArticleTitle');
            const confirmDeleteButton = document.getElementById('confirmDeleteButton');
            const cancelDeleteButton = document.getElementById('cancelDeleteButton');
            const closeSuccessAlertButton = document.getElementById('closeSuccessAlert');
            const successAlert = document.getElementById('successAlert');
            const deleteTriggers = document.querySelectorAll('.js-delete-trigger');

            let activeDeleteForm = null;

            const openDeleteModal = (formId, title) => {
                const form = document.getElementById(formId);
                if (!deleteModal || !deleteArticleTitle || !form) {
                    return;
                }

                activeDeleteForm = form;
                deleteArticleTitle.textContent = title || '';
                confirmDeleteButton.disabled = false;
                confirmDeleteButton.textContent = 'Hapus';
                deleteModal.classList.remove('hidden');
                deleteModal.classList.add('flex');
                document.body.classList.add('overflow-hidden');
            };

            const closeDeleteModal = () => {
                activeDeleteForm = null;
                deleteModal?.classList.add('hidden');
                deleteModal?.classList.remove('flex');
                document.body.classList.remove('overflow-hidden');
            };

            deleteTriggers.forEach((button) => {
                button.addEventListener('click', () => {
                    openDeleteModal(button.dataset.deleteForm, button.dataset.deleteTitle);
                });
            });

            confirmDeleteButton?.addEventListener('click', () => {
                if (!activeDeleteForm) {
                    closeDeleteModal();
                    return;
                }

                confirmDeleteButton.disabled = true;
                confirmDeleteButton.textContent = 'Menghapus...';
                activeDeleteForm.submit();
            });

            cancelDeleteButton?.addEventListener('click', closeDeleteModal);

            deleteModal?.addEventListener('click', (event) => {
                if (event.target === deleteModal) {
                    closeDeleteModal();
                }
            });

            document.addEventListener('keydown', (event) => {
                if (event.key === 'Escape') {
                    closeDeleteModal();
                }
            });

            closeSuccessAlertButton?.addEventListener('click', () => {
                if (!successAlert) {
                    return;
                }

                successAlert.style.opacity = '0';
                setTimeout(() => successAlert.remove(), 300);
            });
        });
    </script>
</x-app-layout>
