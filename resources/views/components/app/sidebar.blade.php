<div class="min-w-fit">
    <!-- Sidebar backdrop (mobile only) -->
    <div class="fixed inset-0 bg-gray-900/30 z-40 lg:hidden lg:z-auto transition-opacity duration-200"
        :class="sidebarOpen ? 'opacity-100' : 'opacity-0 pointer-events-none'" aria-hidden="true" x-cloak></div>

    <!-- Sidebar -->
    <div id="sidebar"
        class="flex lg:flex! flex-col absolute z-40 left-0 top-0 lg:static lg:left-auto lg:top-auto lg:translate-x-0 h-[100dvh] overflow-y-scroll lg:overflow-y-auto no-scrollbar w-64 lg:w-20 lg:sidebar-expanded:!w-64 2xl:w-64! shrink-0 bg-white dark:bg-gray-800 px-4 transition-all duration-200 ease-in-out {{ $variant === 'v2' ? 'border-r border-gray-200 dark:border-gray-700/60' : 'shadow-xs' }}"
        :class="sidebarOpen ? 'max-lg:translate-x-0' : 'max-lg:-translate-x-64'" @click.outside="sidebarOpen = false"
        @keydown.escape.window="sidebarOpen = false">

        <!-- Sidebar header -->
        <div class="flex justify-between md:justify-center my-4 pr-3 sm:px-2">
            <!-- Close button -->
            <button class="lg:hidden text-gray-500 hover:text-gray-400 cursor-pointer"
                @click.stop="sidebarOpen = !sidebarOpen" aria-controls="sidebar" :aria-expanded="sidebarOpen">
                <span class="sr-only">Close sidebar</span>
                <svg class="w-6 h-6 fill-current" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path d="M10.7 18.7l1.4-1.4L7.8 13H20v-2H7.8l4.3-4.3-1.4-1.4L4 12z" />
                </svg>
            </button>

            <!-- Logo -->
            <a class="flex justify-center dark:hidden" href="{{ route('dashboard') }}">
                <img src="{{ asset('assets/NewsMaker-23-logo.png') }}" alt="Logo NewsMaker" class="h-20">
            </a>

            <a class="hidden dark:flex justify-center " href="{{ route('dashboard') }}">
                <img src="{{ asset('assets/NewsMaker-23-logo-white.png') }}" alt="Logo NewsMaker" class="h-20">
            </a>
        </div>

        <hr>

        <div class="space-y-8 mt-4">
            <!-- Pages group -->
            <div>
                <h3 class="text-xs uppercase text-gray-400 dark:text-gray-500 font-semibold pl-3">
                    <span class="hidden lg:block lg:sidebar-expanded:hidden 2xl:hidden text-center w-6"
                        aria-hidden="true">•••</span>
                    <span class="lg:hidden lg:sidebar-expanded:block 2xl:block">Dashboard</span>
                </h3>
                <nav class="flex flex-col gap-3 my-4">
                    <!-- Dashboard -->
                    <a href="{{ route('dashboard') }}"
                        class="flex items-center p-3 rounded-lg gap-3 text-black dark:text-white hover:bg-gray-200 dark:hover:bg-gray-900
                        {{ request()->routeIs('dashboard') ? 'bg-gray-200 dark:bg-gray-900 font-bold' : '' }}">
                        <i class="fa-solid fa-gauge-high"></i>
                        <span class="text-sm font-medium">Beranda</span>
                    </a>
                </nav>
            </div>
        </div>

        <hr>

        <!-- Links -->
        <div class="space-y-8 mt-4">
            <!-- Pages group -->
            <div>
                <h3 class="text-xs uppercase text-gray-400 dark:text-gray-500 font-semibold pl-3">
                    <span class="hidden lg:block lg:sidebar-expanded:hidden 2xl:hidden text-center w-6"
                        aria-hidden="true">•••</span>
                    <span class="lg:hidden lg:sidebar-expanded:block 2xl:block">Fitur</span>
                </h3>
                <nav class="flex flex-col gap-3 my-4">
                    <!-- Berita -->
                    <a href="{{ route('kategori.index') }}"
                        class="flex items-center p-3 rounded-lg gap-3 text-black dark:text-white hover:bg-gray-200 dark:hover:bg-gray-900
                        {{ request()->routeIs('berita.*') || request()->routeIs('kategori.*') ? 'bg-gray-200 dark:bg-gray-900 font-bold' : '' }}">
                        <i class="fa-solid fa-newspaper"></i>
                        <span class="text-sm font-medium">Berita</span>
                    </a>

                    <!-- Berita -->
                    <a href="{{ route('calendar.index') }}"
                        class="flex items-center p-3 rounded-lg gap-3 text-black dark:text-white hover:bg-gray-200 dark:hover:bg-gray-900
                        {{ request()->routeIs('calendar.*') ? 'bg-gray-200 dark:bg-gray-900 font-bold' : '' }}">
                        <i class="fa-solid fa-calendar-days"></i>
                        <span class="text-sm font-medium">Kalender Ekonomi</span>
                    </a>

                    <!-- Berita -->
                    <a href="{{ route('pivot.index') }}"
                        class="flex items-center p-3 rounded-lg gap-3 text-black dark:text-white hover:bg-gray-200 dark:hover:bg-gray-900
                        {{ request()->routeIs('pivot.*') ? 'bg-gray-200 dark:bg-gray-900 font-bold' : '' }}">
                        <i class="fa-solid fa-chart-line"></i>
                        <span class="text-sm font-medium">Pivot & Fibonacci </span>
                    </a>
                </nav>
            </div>
        </div>

        <hr>

        @if (auth()->user()->role === 'Superadmin')
            <hr>

            <div class="space-y-8 my-4">
                <!-- Pages group -->
                <div>
                    <h3 class="text-xs uppercase text-gray-400 dark:text-gray-500 font-semibold pl-3">
                        <span class="hidden lg:block lg:sidebar-expanded:hidden 2xl:hidden text-center w-6"
                            aria-hidden="true">•••</span>
                        <span class="lg:hidden lg:sidebar-expanded:block 2xl:block">Manajemen</span>
                    </h3>
                    <nav class="flex flex-col gap-3 my-4">
                        <!-- User Manage -->
                        <a href="{{ route('user.index') }}"
                            class="flex items-center p-3 rounded-lg gap-3 text-black dark:text-white hover:bg-gray-200 dark:hover:bg-gray-900
                        {{ request()->routeIs('user.*') ? 'bg-gray-200 dark:bg-gray-900 font-bold' : '' }}">
                            <i class="fa-solid fa-users"></i>
                            <span class="text-sm font-medium">Manajemen Pengguna</span>
                        </a>
                    </nav>
                </div>
            </div>
        @endif

        <!-- Expand / collapse button -->
        <div class="pt-3 hidden lg:inline-flex 2xl:hidden justify-end mt-auto">
            <div class="w-12 pl-4 pr-3 py-2">
                <button
                    class="text-gray-400 hover:text-gray-500 dark:text-gray-500 dark:hover:text-gray-400 transition-colors"
                    @click="sidebarExpanded = !sidebarExpanded">
                    <span class="sr-only">Expand / collapse sidebar</span>
                    <svg class="shrink-0 fill-current text-gray-400 dark:text-gray-500 sidebar-expanded:rotate-180"
                        xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
                        <path
                            d="M15 16a1 1 0 0 1-1-1V1a1 1 0 1 1 2 0v14a1 1 0 0 1-1 1ZM8.586 7H1a1 1 0 1 0 0 2h7.586l-2.793 2.793a1 1 0 1 0 1.414 1.414l4.5-4.5A.997.997 0 0 0 12 8.01M11.924 7.617a.997.997 0 0 0-.217-.324l-4.5-4.5a1 1 0 0 0-1.414 1.414L8.586 7M12 7.99a.996.996 0 0 0-.076-.373Z" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
</div>
