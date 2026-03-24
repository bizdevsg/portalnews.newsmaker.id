@section('namePage', 'Berita Newsmaker 23')

<x-app-layout>
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-6xl mx-auto">
        <div class="rounded-3xl bg-gradient-to-br from-slate-900 via-slate-800 to-blue-900 text-white p-6 sm:p-10 shadow-xl">
            <div class="flex flex-col gap-6">
                <div class="flex flex-wrap items-center gap-3">
                    <span class="inline-flex items-center gap-2 rounded-full bg-white/10 px-3 py-1 text-xs font-semibold uppercase tracking-widest">
                        Edisi 23
                    </span>
                    <span class="text-sm text-blue-200">Manajemen Berita</span>
                </div>

                <div>
                    <h1 class="text-2xl sm:text-4xl font-bold leading-tight">Berita Newsmaker 23</h1>
                    <p class="mt-2 text-sm sm:text-base text-blue-100 max-w-2xl">
                        Kelola struktur berita mulai dari Main Category, Sub Category, hingga konten berita.
                    </p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div class="rounded-2xl bg-white/10 px-4 py-3">
                        <div class="text-xs uppercase tracking-wider text-blue-200">Main Category</div>
                        <div class="text-xl font-semibold">{{ $stats['main'] ?? 0 }}</div>
                    </div>
                    <div class="rounded-2xl bg-white/10 px-4 py-3">
                        <div class="text-xs uppercase tracking-wider text-blue-200">Sub Category</div>
                        <div class="text-xl font-semibold">{{ $stats['sub'] ?? 0 }}</div>
                    </div>
                    <div class="rounded-2xl bg-white/10 px-4 py-3">
                        <div class="text-xs uppercase tracking-wider text-blue-200">Berita</div>
                        <div class="text-xl font-semibold">{{ $stats['article'] ?? 0 }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
            <a href="{{ route('newsmaker23.main-category.index') }}"
                class="rounded-2xl bg-white dark:bg-gray-800 p-6 shadow hover:shadow-lg transition">
                <div class="flex items-center gap-3">
                    <div class="h-12 w-12 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center">
                        <i class="fa-solid fa-layer-group"></i>
                    </div>
                    <div>
                        <div class="text-lg font-semibold text-gray-900 dark:text-gray-100">Main Category</div>
                        <div class="text-sm text-gray-600 dark:text-gray-300">Kelola kategori utama.</div>
                    </div>
                </div>
            </a>

            <a href="{{ route('newsmaker23.sub-category.index') }}"
                class="rounded-2xl bg-white dark:bg-gray-800 p-6 shadow hover:shadow-lg transition">
                <div class="flex items-center gap-3">
                    <div class="h-12 w-12 rounded-xl bg-amber-100 text-amber-600 flex items-center justify-center">
                        <i class="fa-solid fa-sitemap"></i>
                    </div>
                    <div>
                        <div class="text-lg font-semibold text-gray-900 dark:text-gray-100">Sub Category</div>
                        <div class="text-sm text-gray-600 dark:text-gray-300">Kelola sub kategori berita.</div>
                    </div>
                </div>
            </a>

            <a href="{{ route('newsmaker23.berita.index') }}"
                class="rounded-2xl bg-white dark:bg-gray-800 p-6 shadow hover:shadow-lg transition">
                <div class="flex items-center gap-3">
                    <div class="h-12 w-12 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center">
                        <i class="fa-solid fa-newspaper"></i>
                    </div>
                    <div>
                        <div class="text-lg font-semibold text-gray-900 dark:text-gray-100">Berita</div>
                        <div class="text-sm text-gray-600 dark:text-gray-300">Kelola konten berita.</div>
                    </div>
                </div>
            </a>
        </div>
    </div>
</x-app-layout>
