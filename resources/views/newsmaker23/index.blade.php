@section('namePage', 'Newsmaker23')

<x-app-layout>
    <div class="w-full px-4 py-8 sm:px-6 lg:px-8">
        <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <div class="flex flex-wrap items-start justify-between gap-4">
                <div class="space-y-2">
                    <p class="text-sm font-semibold text-slate-500 dark:text-slate-400">Newsmaker23</p>
                    <h1 class="text-3xl font-bold text-slate-900 dark:text-slate-100">Dashboard</h1>
                    <p class="text-sm text-slate-500 dark:text-slate-400">
                        Kelola kategori dan berita bilingual Indonesia serta Inggris. Untuk menambah berita, buka kategori yang dituju terlebih dahulu.
                    </p>
                </div>

                <div class="flex flex-wrap gap-3">
                    <a
                        href="{{ route('newsmaker23.main-category.index') }}"
                        class="inline-flex items-center justify-center rounded-xl border border-slate-300 px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-800">
                        Kategori
                    </a>
                    <a
                        href="{{ route('newsmaker23.berita.index') }}"
                        class="inline-flex items-center justify-center rounded-xl border border-slate-300 px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-800">
                        Semua Berita
                    </a>
                </div>
            </div>
        </section>

        <section class="mt-6 grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-4">
            <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <p class="text-sm text-slate-500 dark:text-slate-400">Total Kategori</p>
                <p class="mt-2 text-3xl font-bold text-slate-900 dark:text-slate-100">{{ number_format($stats['categories']) }}</p>
            </article>

            <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <p class="text-sm text-slate-500 dark:text-slate-400">Total Berita</p>
                <p class="mt-2 text-3xl font-bold text-slate-900 dark:text-slate-100">{{ number_format($stats['articles']) }}</p>
            </article>

            <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <p class="text-sm text-slate-500 dark:text-slate-400">Kategori Terbaru</p>
                <p class="mt-2 text-base font-semibold text-slate-900 dark:text-slate-100">
                    {{ $stats['latest_category'] ?: 'Belum ada kategori.' }}
                </p>
            </article>

            <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <p class="text-sm text-slate-500 dark:text-slate-400">Artikel Terbaru</p>
                <p class="mt-2 text-base font-semibold text-slate-900 dark:text-slate-100">
                    {{ $stats['latest_article'] ?: 'Belum ada artikel.' }}
                </p>
            </article>
        </section>

        <section class="mt-6 grid grid-cols-1 gap-6 xl:grid-cols-2">
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <div class="flex items-center justify-between gap-3">
                    <div>
                        <h2 class="text-xl font-bold text-slate-900 dark:text-slate-100">Kategori Terbaru</h2>
                        <p class="text-sm text-slate-500 dark:text-slate-400">Struktur kategori yang aktif saat ini.</p>
                    </div>
                    <a
                        href="{{ route('newsmaker23.main-category.create') }}"
                        class="inline-flex items-center justify-center rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white transition hover:bg-slate-800 dark:bg-slate-100 dark:text-slate-900 dark:hover:bg-white">
                        Tambah
                    </a>
                </div>

                <div class="mt-5 space-y-3">
                    @forelse ($categories as $category)
                        <div class="rounded-xl border border-slate-200 p-4 dark:border-slate-800">
                            <div class="flex items-center justify-between gap-3">
                                <div>
                                    <p class="font-semibold text-slate-900 dark:text-slate-100">{{ $category->name }}</p>
                                    <p class="text-sm text-slate-500 dark:text-slate-400">{{ $category->articles_count }} berita</p>
                                </div>
                                <a
                                    href="{{ route('newsmaker23.main-category.show', $category->slug) }}"
                                    class="inline-flex items-center justify-center rounded-lg border border-slate-300 px-3 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-50 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-800">
                                    Lihat
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="rounded-xl border border-dashed border-slate-300 p-6 text-sm text-slate-500 dark:border-slate-700 dark:text-slate-400">
                            Belum ada kategori.
                        </div>
                    @endforelse
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <div class="flex items-center justify-between gap-3">
                    <div>
                        <h2 class="text-xl font-bold text-slate-900 dark:text-slate-100">Berita Terbaru</h2>
                        <p class="text-sm text-slate-500 dark:text-slate-400">Update artikel yang paling terakhir dibuat.</p>
                    </div>
                    <a
                        href="{{ route('newsmaker23.berita.index') }}"
                        class="inline-flex items-center justify-center rounded-xl border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-50 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-800">
                        Lihat Semua
                    </a>
                </div>

                <div class="mt-5 space-y-3">
                    @forelse ($latestArticles as $article)
                        <div class="rounded-xl border border-slate-200 p-4 dark:border-slate-800">
                            <p class="text-xs font-semibold uppercase text-slate-500 dark:text-slate-400">
                                {{ $article->mainCategory?->name ?? '-' }}
                            </p>
                            <p class="mt-1 font-semibold text-slate-900 dark:text-slate-100">{{ $article->title_id }}</p>
                            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                                {{ \Illuminate\Support\Str::limit($article->title_en, 100) }}
                            </p>
                            <div class="mt-3 flex flex-wrap gap-2">
                                <a
                                    href="{{ route('newsmaker23.berita.edit', $article->id) }}"
                                    class="inline-flex items-center justify-center rounded-lg bg-slate-900 px-3 py-2 text-sm font-semibold text-white transition hover:bg-slate-800 dark:bg-slate-100 dark:text-slate-900 dark:hover:bg-white">
                                    Edit
                                </a>
                                <a
                                    href="{{ route('newsmaker23.main-category.show', $article->mainCategory?->slug) }}"
                                    class="inline-flex items-center justify-center rounded-lg border border-slate-300 px-3 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-50 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-800">
                                    Kategori
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="rounded-xl border border-dashed border-slate-300 p-6 text-sm text-slate-500 dark:border-slate-700 dark:text-slate-400">
                            Belum ada artikel.
                        </div>
                    @endforelse
                </div>
            </div>
        </section>
    </div>
</x-app-layout>
