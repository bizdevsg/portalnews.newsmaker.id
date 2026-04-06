<div class="min-w-fit">
    <!-- Sidebar backdrop (mobile only) -->
    <div class="fixed inset-0 z-40 bg-slate-900/40 transition-opacity duration-200 lg:hidden lg:z-auto"
        :class="sidebarOpen ? 'opacity-100' : 'pointer-events-none opacity-0'" aria-hidden="true" x-cloak></div>

    @php
        $pasarIndonesiaActive = request()->routeIs('pasar-indonesia.*') || request()->routeIs('regulasi-institusi.*');

        $sidebarSections = [
            [
                'label' => 'Dashboard',
                'items' => [
                    [
                        'label' => 'Beranda',
                        'route' => 'dashboard',
                        'icon' => 'fa-solid fa-gauge-high',
                        'active' => request()->routeIs('dashboard'),
                    ],
                ],
            ],
            [
                'label' => '5 PT',
                'items' => [
                    [
                        'label' => 'Berita',
                        'route' => 'kategori.index',
                        'icon' => 'fa-solid fa-newspaper',
                        'active' => request()->routeIs('berita.*') || request()->routeIs('kategori.*'),
                    ],
                ],
            ],
            [
                'label' => 'Newsmaker 23',
                'items' => [
                    [
                        'label' => 'Berita',
                        'route' => 'newsmaker23.index',
                        'icon' => 'fa-solid fa-layer-group',
                        'active' => request()->routeIs('newsmaker23.*'),
                    ],
                    [
                        'label' => 'Kalender Ekonomi',
                        'route' => 'calendar.index',
                        'icon' => 'fa-solid fa-calendar-days',
                        'active' => request()->routeIs('calendar.*'),
                    ],
                    [
                        'label' => 'Historical Data',
                        'route' => 'pivot.index',
                        'icon' => 'fa-solid fa-chart-line',
                        'active' => request()->routeIs('pivot.*'),
                    ],
                    [
                        'label' => 'TikTok',
                        'route' => 'tiktok.index',
                        'icon' => 'fa-brands fa-tiktok',
                        'active' => request()->routeIs('tiktok.*'),
                    ],
                    [
                        'label' => 'Pop Up Banner',
                        'route' => 'popup-banner.index',
                        'icon' => 'fa-solid fa-rectangle-ad',
                        'active' => request()->routeIs('popup-banner.*'),
                    ],
                ],
            ],
            [
                'label' => 'Pasar Indonesia',
                'items' => [
                    [
                        'label' => 'Pasar Indonesia',
                        'route' => 'pasar-indonesia.index',
                        'icon' => 'fa-solid fa-magnifying-glass-chart',
                        'active' => $pasarIndonesiaActive,
                    ],
                    // [
                    //     'label' => 'Regulasi & Institusi',
                    //     'route' => 'regulasi-institusi.index',
                    //     'icon' => 'fa-solid fa-building-columns',
                    //     'active' => request()->routeIs('regulasi-institusi.*'),
                    // ],
                ],
            ],
        ];

        $managementSection = [
            'label' => 'Manajemen',
            'condition' => auth()->user()->role === 'Superadmin',
            'items' => [
                [
                    'label' => 'Manajemen Pengguna',
                    'route' => 'user.index',
                    'icon' => 'fa-solid fa-users',
                    'active' => request()->routeIs('user.*'),
                ],
            ],
        ];

        $navigationSections = $sidebarSections;
        if ($managementSection['condition']) {
            $navigationSections[] = [
                'label' => $managementSection['label'],
                'items' => $managementSection['items'],
            ];
        }

        $navLinkClasses = static function (bool $active): string {
            if ($active) {
                return 'border border-slate-900 bg-slate-900 text-white shadow-sm dark:border-slate-100 dark:bg-slate-100 dark:text-slate-900';
            }

            return 'border border-transparent bg-blue-50 dark:bg-blue-50/20 text-slate-600 hover:bg-slate-200 hover:text-slate-900 dark:text-slate-300 dark:hover:bg-slate-800 dark:hover:text-white';
        };

        $navIconClasses = static function (bool $active): string {
            if ($active) {
                return 'bg-white/15 text-white dark:bg-slate-900/10 dark:text-slate-900';
            }

            return 'bg-slate-100 text-slate-500 dark:bg-slate-800 dark:text-slate-300';
        };
    @endphp

    <!-- Sidebar -->
    <div id="sidebar"
        class="absolute left-0 top-0 z-40 flex h-[100dvh] w-64 pb-4 shrink-0 flex-col overflow-y-auto no-scrollbar border-r border-slate-200 bg-white transition-all duration-200 ease-in-out max-lg:-translate-x-full dark:border-slate-800 dark:bg-slate-900 lg:static lg:left-auto lg:top-auto lg:flex! lg:w-20 lg:translate-x-0 lg:sidebar-expanded:!w-64 2xl:w-64! {{ $variant === 'v2' ? '' : 'shadow-sm' }}"
        :class="sidebarOpen ? 'max-lg:translate-x-0' : 'max-lg:-translate-x-full'" @click.outside="sidebarOpen = false"
        @keydown.escape.window="sidebarOpen = false">
        <div class="flex h-full flex-col">
            <!-- Sidebar header -->
            <div
                class="mb-6 flex items-center justify-between gap-3 border-b border-slate-200 h-16 dark:border-slate-800">
                {{-- <button
                    class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-500 transition hover:bg-slate-100 hover:text-slate-700 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-400 dark:hover:bg-slate-800 dark:hover:text-slate-200 lg:hidden"
                    @click.stop="sidebarOpen = !sidebarOpen" aria-controls="sidebar" :aria-expanded="sidebarOpen">
                    <span class="sr-only">Close sidebar</span>
                    <svg class="h-5 w-5 fill-current" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path d="M10.7 18.7l1.4-1.4L7.8 13H20v-2H7.8l4.3-4.3-1.4-1.4L4 12z" />
                    </svg>
                </button> --}}

                <a class="flex min-w-0 flex-1 px-3 items-center gap-3 rounded-xl lg:mx-auto lg:w-full lg:flex-none lg:justify-center lg:gap-0 lg:px-0 lg:sidebar-expanded:justify-start lg:sidebar-expanded:gap-3 lg:sidebar-expanded:px-3 2xl:justify-start 2xl:gap-3 2xl:px-3"
                    href="{{ route('dashboard') }}" aria-label="Dashboard">
                    <span
                        class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-slate-100 dark:bg-slate-800">
                        <img src="{{ asset('Icon/favicon-96x96.png') }}" alt="Logo NewsMaker"
                            class="h-6 w-6 rounded-lg object-cover">
                    </span>
                    <span class="min-w-0 flex-1 lg:hidden lg:flex-none lg:sidebar-expanded:block 2xl:block">
                        <span class="block truncate text-sm font-semibold text-slate-900 dark:text-white">NewsMaker
                            23</span>
                    </span>
                </a>
            </div>

            <div class="flex-1 space-y-6 px-3">
                @foreach ($navigationSections as $section)
                    <section class="space-y-3">
                        <div
                            class="flex items-center gap-2 px-2 lg:justify-center lg:sidebar-expanded:justify-start 2xl:justify-start">
                            <span
                                class="text-[11px] font-semibold uppercase tracking-[0.18em] text-slate-400 dark:text-slate-500 lg:hidden lg:sidebar-expanded:inline 2xl:inline select-none">
                                {{ $section['label'] }}
                            </span>
                        </div>

                        <nav class="space-y-3 lg:space-y-0 lg:sidebar-expanded:space-y-3 2xl:space-y-3">
                            @foreach ($section['items'] as $item)
                                <div class="group relative">
                                    <a href="{{ route($item['route']) }}" title="{{ $item['label'] }}"
                                        aria-label="{{ $item['label'] }}"
                                        class="flex items-center gap-3 rounded-xl p-1.5 transition-colors duration-200 lg:justify-center lg:gap-0 lg:sidebar-expanded:justify-start lg:sidebar-expanded:gap-3 2xl:justify-start 2xl:gap-3 {{ $navLinkClasses($item['active']) }}">
                                        <span
                                            class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg text-sm transition {{ $navIconClasses($item['active']) }}">
                                            <i class="{{ $item['icon'] }}"></i>
                                        </span>

                                        <span
                                            class="min-w-0 truncate text-sm font-semibold lg:hidden lg:sidebar-expanded:block 2xl:block">
                                            {{ $item['label'] }}
                                        </span>
                                    </a>

                                    {{-- <div
                                            class="pointer-events-none absolute left-full top-1/2 z-50 ml-3 hidden -translate-y-1/2 whitespace-nowrap rounded-lg bg-slate-900 px-3 py-2 text-xs font-medium text-white opacity-0 shadow-lg transition-opacity duration-200 dark:bg-slate-800 lg:flex lg:sidebar-expanded:hidden group-hover:opacity-100">
                                            {{ $item['label'] }}
                                        </div> --}}
                                </div>

                                @unless ($loop->last)
                                    <div class="hidden lg:block lg:sidebar-expanded:hidden 2xl:hidden">
                                        <div class="mx-auto my-2 h-px w-8 bg-slate-200 dark:bg-slate-800"></div>
                                    </div>
                                @endunless
                            @endforeach
                        </nav>
                    </section>
                @endforeach
            </div>
        </div>
    </div>
</div>
