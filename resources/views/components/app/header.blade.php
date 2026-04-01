<header
    class="sticky top-0 before:absolute before:inset-0 before:backdrop-blur-md max-lg:before:bg-white/90 dark:max-lg:before:bg-gray-800/90 before:-z-10 z-30 shadow-lg lg:border-b border-gray-200 dark:border-gray-700/60 {{ $variant === 'v2' || $variant === 'v3' ? 'before:bg-white after:absolute after:h-px after:inset-x-0 after:top-full after:bg-gray-200 dark:after:bg-gray-700/60 after:-z-10' : 'max-lg:shadow-xs lg:before:bg-gray-100/90 dark:lg:before:bg-gray-900/90' }} {{ $variant === 'v2' ? 'dark:before:bg-gray-800' : '' }} {{ $variant === 'v3' ? 'dark:before:bg-gray-900' : '' }}">
    <div class="px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16 {{ $variant === 'v2' || $variant === 'v3' ? '' : '' }}">
            <!-- Header: Left side -->
            <div class="flex items-center gap-3">
                <!-- Hamburger button -->
                <button class="cursor-pointer text-gray-500 hover:text-gray-600 dark:hover:text-gray-400 lg:hidden"
                    @click.stop="sidebarOpen = !sidebarOpen" aria-controls="sidebar" :aria-expanded="sidebarOpen">
                    <span class="sr-only">Open sidebar</span>
                    <svg class="w-6 h-6 fill-current" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <rect x="4" y="5" width="16" height="2" />
                        <rect x="4" y="11" width="16" height="2" />
                        <rect x="4" y="17" width="16" height="2" />
                    </svg>
                </button>

                <button
                    class="hidden items-center rounded-xl border border-slate-200 bg-white px-2.5 py-2 text-slate-500 transition hover:bg-slate-100 hover:text-slate-700 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-400 dark:hover:bg-slate-800 dark:hover:text-slate-200 lg:inline-flex 2xl:hidden"
                    @click="sidebarExpanded = !sidebarExpanded">
                    <span class="sr-only">Expand / collapse sidebar</span>
                    <span
                        class="flex h-8 w-8 items-center justify-center rounded-lg bg-slate-100 text-slate-500 transition dark:bg-slate-800 dark:text-slate-300">
                        <svg class="h-4 w-4 transition-transform" :class="sidebarExpanded ? 'rotate-180' : ''"
                            xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
                            <path fill="currentColor"
                                d="M15 16a1 1 0 0 1-1-1V1a1 1 0 1 1 2 0v14a1 1 0 0 1-1 1ZM8.586 7H1a1 1 0 1 0 0 2h7.586l-2.793 2.793a1 1 0 1 0 1.414 1.414l4.5-4.5A.997.997 0 0 0 12 8.01M11.924 7.617a.997.997 0 0 0-.217-.324l-4.5-4.5a1 1 0 0 0-1.414 1.414L8.586 7M12 7.99a.996.996 0 0 0-.076-.373Z" />
                        </svg>
                    </span>
                </button>
            </div>

            <!-- Header: Middle logo -->
            <div>
                <h1>@yield('titlePage')</h1>
            </div>

            <!-- Header: Right side -->
            <div class="flex items-center space-x-3">

                {{-- Real Time --}}
                <x-time-real />

                {{-- Info --}}
                {{-- <x-tool-tip /> --}}

                <!-- Dark mode toggle -->
                <x-theme-toggle />

                <!-- Divider -->
                <hr class="w-px h-6 bg-gray-200 dark:bg-gray-700/60 border-none" />

                <!-- User button -->
                <x-dropdown-profile align="right" />

            </div>

        </div>
    </div>
</header>
