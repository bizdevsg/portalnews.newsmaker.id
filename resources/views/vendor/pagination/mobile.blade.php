@if ($paginator->hasPages())
    <div class="space-y-3 lg:hidden">
        <div class="flex items-center justify-between text-xs font-medium text-slate-500 dark:text-slate-400">
            <span>Halaman {{ $paginator->currentPage() }} / {{ $paginator->lastPage() }}</span>
            <span>
                @if ($paginator->firstItem())
                    {{ $paginator->firstItem() }}-{{ $paginator->lastItem() }}
                @else
                    0
                @endif
                dari {{ $paginator->total() }}
            </span>
        </div>

        <div class="grid grid-cols-2 gap-3">
            @if ($paginator->onFirstPage())
                <span
                    class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-slate-100 px-4 py-2 text-sm font-semibold text-slate-400 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-600">
                    Sebelumnya
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}"
                    class="inline-flex items-center justify-center rounded-xl border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-50 dark:border-slate-600 dark:bg-slate-900 dark:text-slate-200 dark:hover:bg-slate-800">
                    Sebelumnya
                </a>
            @endif

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}"
                    class="inline-flex items-center justify-center rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white transition hover:bg-slate-800 dark:bg-slate-100 dark:text-slate-900 dark:hover:bg-white">
                    Berikutnya
                </a>
            @else
                <span
                    class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-slate-100 px-4 py-2 text-sm font-semibold text-slate-400 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-600">
                    Berikutnya
                </span>
            @endif
        </div>
    </div>
@endif
