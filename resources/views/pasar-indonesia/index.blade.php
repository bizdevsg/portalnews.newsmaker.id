@section('namePage', 'Pasar Indonesia')

<x-app-layout>
    <div class="w-full px-4 py-8 sm:px-6 lg:px-8">
        <section
            class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <div class="space-y-2">
                <p class="text-sm font-semibold text-slate-500 dark:text-slate-400">Pasar Indonesia</p>
                <h1 class="text-3xl font-bold text-slate-900 dark:text-slate-100">Pilih Modul</h1>
                <p class="text-sm text-slate-500 dark:text-slate-400">
                    Pilih menu yang ingin Anda kelola: Berita, Analisis, atau Regulasi & Institusi.
                </p>
            </div>
        </section>

        <section class="mt-6 grid grid-cols-1 gap-5 md:grid-cols-2 xl:grid-cols-3">
            <a href="{{ route('pasar-indonesia.berita.index') }}"
                class="group rounded-2xl border border-slate-200 bg-white p-6 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md dark:border-slate-800 dark:bg-slate-900">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p class="text-sm font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">
                            Modul</p>
                        <h2 class="mt-2 text-2xl font-bold text-slate-900 dark:text-slate-100">Berita</h2>
                        <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">Kelola kategori dan artikel berita
                            Pasar Indonesia.</p>
                    </div>
                    <span
                        class="inline-flex h-11 w-11 items-center justify-center rounded-xl bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-200">
                        <i class="fa-solid fa-newspaper"></i>
                    </span>
                </div>
                <p class="mt-5 text-sm font-semibold text-slate-900 dark:text-slate-100">Buka Berita <i
                        class="fa-solid fa-arrow-right ml-1 text-xs"></i></p>
            </a>

            <a href="{{ route('pasar-indonesia.analisis.index') }}"
                class="group rounded-2xl border border-slate-200 bg-white p-6 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md dark:border-slate-800 dark:bg-slate-900">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p class="text-sm font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">
                            Modul</p>
                        <h2 class="mt-2 text-2xl font-bold text-slate-900 dark:text-slate-100">Analisis</h2>
                        <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">Kelola daftar analisis Pasar
                            Indonesia.</p>
                    </div>
                    <span
                        class="inline-flex h-11 w-11 items-center justify-center rounded-xl bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-200">
                        <i class="fa-solid fa-chart-line"></i>
                    </span>
                </div>
                <p class="mt-5 text-sm font-semibold text-slate-900 dark:text-slate-100">Buka Analisis <i
                        class="fa-solid fa-arrow-right ml-1 text-xs"></i></p>
            </a>

            <a href="{{ route('regulasi-institusi.index') }}"
                class="group rounded-2xl border border-slate-200 bg-white p-6 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md dark:border-slate-800 dark:bg-slate-900">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p class="text-sm font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">
                            Modul</p>
                        <h2 class="mt-2 text-2xl font-bold text-slate-900 dark:text-slate-100">Regulasi & Institusi
                        </h2>
                        <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">Kelola kategori dan berita khusus
                            modul Regulasi & Institusi.</p>
                    </div>
                    <span
                        class="inline-flex h-11 w-11 items-center justify-center rounded-xl bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-200">
                        <i class="fa-solid fa-building-columns"></i>
                    </span>
                </div>
                <p class="mt-5 text-sm font-semibold text-slate-900 dark:text-slate-100">Buka Regulasi & Institusi <i
                        class="fa-solid fa-arrow-right ml-1 text-xs"></i></p>
            </a>
        </section>
    </div>
</x-app-layout>
