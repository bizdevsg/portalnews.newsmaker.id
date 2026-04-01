@if ($paginator->hasPages())
    <div class="w-full flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <nav class="flex justify-center mb-4 sm:mb-0 sm:order-1" role="navigation" aria-label="{!! __('Pagination Navigation') !!}">
            {{-- Previous Page Link --}}
            <div class="mr-2">
                @if ($paginator->onFirstPage())
                    <span
                        class="inline-flex items-center justify-center rounded-lg leading-5 px-2.5 py-2 bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700/60 text-slate-300 dark:text-slate-600">
                        <span class="sr-only">{!! __('pagination.previous') !!}</span><wbr />
                        <i class="fa-solid fa-chevron-left"></i>
                    </span>
                @else
                    <a href="{{ $paginator->previousPageUrl() }}"
                        class="inline-flex items-center justify-center rounded-lg leading-5 px-2.5 py-2 bg-blue-50 dark:bg-blue-950/30 hover:bg-blue-100 dark:hover:bg-blue-900/40 border border-blue-200 dark:border-blue-800/60 text-blue-600 dark:text-blue-300 shadow-xs">
                        <span class="sr-only">{!! __('pagination.previous') !!}</span><wbr />
                        <i class="fa-solid fa-chevron-left"></i>
                    </a>
                @endif
            </div>

            {{-- Pagination Elements --}}
            <ul class="inline-flex text-sm font-medium -space-x-px rounded-lg shadow-xs">
                @foreach ($elements as $element)
                    {{-- "Three Dots" Separator --}}
                    @if (is_string($element))
                        <li aria-disabled="true">
                            <span
                                class="inline-flex items-center justify-center leading-5 px-3.5 py-2 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700/60 text-slate-400 dark:text-slate-500">{{ $element }}</span>
                        </li>
                    @endif

                    {{-- Array Of Links --}}
                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <li aria-current="page">
                                    <span
                                        class="inline-flex items-center justify-center leading-5 px-3.5 py-2 bg-blue-600 dark:bg-blue-500 border border-blue-600 dark:border-blue-500 text-white @if ($page === 1) {{ 'rounded-l-lg' }}@elseif($page === $paginator->lastPage()){{ 'rounded-r-lg' }} @endif">{{ $page }}</span>
                                </li>
                            @else
                                <li>
                                    <a href="{{ $url }}"
                                        class="inline-flex items-center justify-center leading-5 px-3.5 py-2 bg-white dark:bg-slate-900 hover:bg-blue-50 dark:hover:bg-blue-950/30 border border-blue-200 dark:border-blue-800/40 text-blue-700 dark:text-blue-300 @if ($page === 1) {{ 'rounded-l-lg' }}@elseif($page === $paginator->lastPage()){{ 'rounded-r-lg' }} @endif">{{ $page }}</a>
                                </li>
                            @endif
                        @endforeach
                    @endif
                @endforeach
            </ul>

            {{-- Next Page Link --}}
            <div class="ml-2">
                @if ($paginator->hasMorePages())
                    <a href="{{ $paginator->nextPageUrl() }}"
                        class="inline-flex items-center justify-center rounded-lg leading-5 px-2.5 py-2 bg-blue-50 dark:bg-blue-950/30 hover:bg-blue-100 dark:hover:bg-blue-900/40 border border-blue-200 dark:border-blue-800/60 text-blue-600 dark:text-blue-300 shadow-xs">
                        <span class="sr-only">{!! __('pagination.next') !!}</span><wbr />
                        <i class="fa-solid fa-chevron-right"></i>
                    </a>
                @else
                    <span
                        class="inline-flex items-center justify-center rounded-lg leading-5 px-2.5 py-2 bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700/60 text-slate-300 dark:text-slate-600">
                        <span class="sr-only">{!! __('pagination.next') !!}</span><wbr />
                        <i class="fa-solid fa-chevron-right"></i>
                    </span>
                @endif
            </div>
        </nav>

        <div class="text-sm text-gray-500 text-center sm:text-left">
            {!! __('Menampilkan') !!}
            @if ($paginator->firstItem())
                <span class="font-medium text-gray-600 dark:text-gray-300">{{ $paginator->firstItem() }}</span>
                {!! __('-') !!}
                <span class="font-medium text-gray-600 dark:text-gray-300">{{ $paginator->lastItem() }}</span>
            @else
                {{ $paginator->count() }}
            @endif
            {!! __('dari') !!}
            <span class="font-medium text-gray-600 dark:text-gray-300">{{ $paginator->total() }}</span>
            {!! __('hasil') !!}
        </div>
    </div>
@endif
