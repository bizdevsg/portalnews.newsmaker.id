{{-- Shared article list + stats, dipakai oleh halaman kategori (show.blade.php)
     dan halaman khusus seperti Breaking News. Butuh $category dan $articles. --}}
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

<section class="mt-6 grid grid-cols-1 gap-4 md:grid-cols-3">
    <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
        <p class="text-sm text-slate-500 dark:text-slate-400">Nama</p>
        <p class="mt-2 text-xl font-bold text-slate-900 dark:text-slate-100">{{ $category->name }}</p>
    </article>
    <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
        <p class="text-sm text-slate-500 dark:text-slate-400">Total Berita</p>
        <p class="mt-2 text-xl font-bold text-slate-900 dark:text-slate-100">{{ number_format($articles->count()) }}</p>
    </article>
    <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
        <p class="text-sm text-slate-500 dark:text-slate-400">Aksi Cepat</p>
        <div class="mt-3">
            <a
                href="{{ route('newsmaker23.berita.index') }}"
                class="inline-flex items-center justify-center rounded-xl border border-slate-300 px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-800">
                Semua Berita
            </a>
        </div>
    </article>
</section>

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
                            <p class="text-xs text-slate-500 dark:text-slate-400">
                                ID: {{ \Illuminate\Support\Str::limit(strip_tags($article->content_id), 100) }}
                            </p>
                            <p class="text-xs text-slate-500 dark:text-slate-400">
                                EN: {{ \Illuminate\Support\Str::limit(strip_tags($article->content_en), 100) }}
                            </p>
                        </div>

                        <form id="delete-article-form-{{ $article->id }}" action="{{ route('newsmaker23.berita.destroy', $article->id) }}" method="POST" class="hidden">
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
                                class="js-delete-article inline-flex items-center justify-center rounded-xl bg-rose-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-rose-700"
                                data-delete-form="delete-article-form-{{ $article->id }}"
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
