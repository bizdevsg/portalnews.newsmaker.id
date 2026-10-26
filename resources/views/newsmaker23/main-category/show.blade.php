@section('namePage', 'Kategori ' . $category->name)

<x-app-layout>
    <div class="w-full px-4 py-8 sm:px-6 lg:px-8">
        <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <div class="flex flex-wrap items-start justify-between gap-4">
                <div class="space-y-2">
                    <p class="text-sm font-semibold text-slate-500 dark:text-slate-400">Kategori</p>
                    <h1 class="text-3xl font-bold text-slate-900 dark:text-slate-100">{{ $category->name }}</h1>
                    <p class="text-sm text-slate-500 dark:text-slate-400">
                        {{ $articles->count() }} berita aktif dalam kategori ini.
                    </p>
                </div>

                <div class="flex flex-wrap gap-3">
                    <a
                        href="{{ route('newsmaker23.berita.create', ['main_category_id' => $category->id]) }}"
                        class="inline-flex items-center justify-center rounded-xl bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-slate-800 dark:bg-slate-100 dark:text-slate-900 dark:hover:bg-white">
                        Tambah Berita
                    </a>
                    <a
                        href="{{ route('newsmaker23.main-category.edit', $category->id) }}"
                        class="inline-flex items-center justify-center rounded-xl border border-slate-300 px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-800">
                        Edit Kategori
                    </a>
                    <a
                        href="{{ route('newsmaker23.index') }}"
                        class="inline-flex items-center justify-center rounded-xl border border-slate-300 px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-800">
                        Kembali
                    </a>
                </div>
            </div>
        </section>

        @include('newsmaker23.main-category.partials.articles-section', ['category' => $category, 'articles' => $articles])
    </div>

    @include('newsmaker23.main-category.partials.delete-article-modal')
</x-app-layout>
