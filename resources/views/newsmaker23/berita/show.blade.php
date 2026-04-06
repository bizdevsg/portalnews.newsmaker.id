@section('namePage', 'Preview Berita Newsmaker23')

<x-app-layout>
    @php
        $backUrl = $article->mainCategory
            ? route('newsmaker23.main-category.show', $article->mainCategory->slug)
            : route('newsmaker23.berita.index');
    @endphp

    <div class="w-full px-4 py-8 sm:px-6 lg:px-8">
        <section class="overflow-hidden rounded-[32px] bg-gradient-to-br from-slate-950 via-slate-900 to-blue-900 px-6 py-8 shadow-2xl ring-1 ring-white/10 sm:px-8 lg:px-10">
            <div class="flex flex-wrap items-start justify-between gap-6">
                <div class="space-y-4">
                    <span class="inline-flex items-center rounded-full border border-white/10 bg-white/10 px-3 py-1 text-xs font-semibold uppercase tracking-[0.22em] text-blue-100">
                        Preview Berita
                    </span>
                    <div class="space-y-2">
                        <p class="text-xs font-semibold uppercase tracking-[0.32em] text-slate-300">
                            {{ $article->mainCategory?->name ?? 'Newsmaker23' }}
                        </p>
                        <h1 class="text-3xl font-bold tracking-tight text-white sm:text-4xl">{{ $article->title_id }}</h1>
                        <p class="text-sm leading-6 text-blue-100 sm:text-base">{{ $article->title_en }}</p>
                    </div>
                </div>

                <div class="flex flex-wrap gap-3">
                    <a href="{{ $backUrl }}" class="inline-flex items-center justify-center rounded-xl border border-white/15 bg-white/5 px-5 py-3 text-sm font-semibold text-white transition hover:bg-white/10">
                        Kembali
                    </a>
                    <a href="{{ route('newsmaker23.berita.edit', $article->id) }}" class="inline-flex items-center justify-center rounded-xl bg-white px-5 py-3 text-sm font-semibold text-slate-900 transition hover:bg-blue-50">
                        Edit Berita
                    </a>
                </div>
            </div>
        </section>

        <section class="mt-8 grid grid-cols-1 gap-6 xl:grid-cols-[1.1fr_0.9fr]">
            <article class="overflow-hidden rounded-[28px] border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">
                @if ($article->image)
                    <img src="{{ asset($article->image) }}" alt="{{ $article->title_id }}" class="h-72 w-full object-cover sm:h-96">
                @endif
            </article>

            <article class="rounded-[28px] border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900 sm:p-8">
                <div class="space-y-5">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500 dark:text-slate-400">Metadata</p>
                        <h2 class="mt-2 text-2xl font-bold text-slate-900 dark:text-slate-100">Informasi artikel</h2>
                    </div>

                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div class="rounded-2xl bg-slate-50 p-4 dark:bg-slate-950">
                            <p class="text-xs font-semibold uppercase tracking-[0.16em] text-slate-500 dark:text-slate-400">Kategori</p>
                            <p class="mt-2 text-sm font-semibold text-slate-900 dark:text-slate-100">{{ $article->mainCategory?->name ?? '-' }}</p>
                        </div>
                        <div class="rounded-2xl bg-slate-50 p-4 dark:bg-slate-950">
                            <p class="text-xs font-semibold uppercase tracking-[0.16em] text-slate-500 dark:text-slate-400">Author</p>
                            <p class="mt-2 text-sm font-semibold text-slate-900 dark:text-slate-100">{{ $article->authorUser?->name ?? $article->author ?? '-' }}</p>
                        </div>
                        <div class="rounded-2xl bg-slate-50 p-4 dark:bg-slate-950">
                            <p class="text-xs font-semibold uppercase tracking-[0.16em] text-slate-500 dark:text-slate-400">Source</p>
                            <p class="mt-2 text-sm font-semibold text-slate-900 dark:text-slate-100">{{ $article->source }}</p>
                        </div>
                        <div class="rounded-2xl bg-slate-50 p-4 dark:bg-slate-950">
                            <p class="text-xs font-semibold uppercase tracking-[0.16em] text-slate-500 dark:text-slate-400">Dipublikasikan</p>
                            <p class="mt-2 text-sm font-semibold text-slate-900 dark:text-slate-100">
                                {{ \Illuminate\Support\Carbon::parse($article->created_at)->translatedFormat('d F Y H:i') }}
                            </p>
                        </div>
                    </div>
                </div>
            </article>
        </section>

        <section class="mt-8 grid grid-cols-1 gap-6 xl:grid-cols-2">
            <article class="rounded-[28px] border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900 sm:p-8">
                <div class="space-y-4">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500 dark:text-slate-400">Versi Indonesia</p>
                        <h2 class="mt-2 text-2xl font-bold text-slate-900 dark:text-slate-100">{{ $article->title_id }}</h2>
                    </div>
                    <div class="prose prose-slate max-w-none dark:prose-invert">
                        {!! $article->content_id !!}
                    </div>
                </div>
            </article>

            <article class="rounded-[28px] border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900 sm:p-8">
                <div class="space-y-4">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500 dark:text-slate-400">English Version</p>
                        <h2 class="mt-2 text-2xl font-bold text-slate-900 dark:text-slate-100">{{ $article->title_en }}</h2>
                    </div>
                    <div class="prose prose-slate max-w-none dark:prose-invert">
                        {!! $article->content_en !!}
                    </div>
                </div>
            </article>
        </section>
    </div>
</x-app-layout>
