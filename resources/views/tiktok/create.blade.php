@section('namePage', 'Tambah TikTok')

@php($errors = $errors ?? new \Illuminate\Support\ViewErrorBag())

<x-app-layout>
    <div class="mx-auto flex w-full flex-col gap-6 px-4 py-8 sm:px-6 lg:px-8">
        @if ($errors->any())
            <section
                class="rounded-xl border border-rose-200 bg-rose-50 px-5 py-4 text-sm text-rose-700 shadow-sm dark:border-rose-500/20 dark:bg-rose-500/10 dark:text-rose-200">
                <h2 class="font-semibold">Periksa kembali form sebelum menyimpan.</h2>
                <ul class="mt-2 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </section>
        @endif

        <section class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-950">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                <div class="min-w-0">
                    <button type="button" onclick="openModal('modalKembali')"
                        class="inline-flex items-center gap-2 text-sm font-medium text-slate-500 transition hover:text-slate-900 dark:text-slate-400 dark:hover:text-white">
                        <i class="fa-solid fa-arrow-left"></i>
                        Kembali ke daftar TikTok
                    </button>

                    <h1 class="mt-3 text-2xl font-semibold text-slate-900 dark:text-white sm:text-3xl">
                        Tambah TikTok
                    </h1>
                    <p class="mt-2 max-w-3xl text-sm text-slate-500 dark:text-slate-400">
                        Simpan judul, embed code, dan backup video ke dalam form yang lebih ringkas dan lebih mudah
                        dipindai.
                    </p>
                </div>

                <button type="button" onclick="openModal('modalSubmit')"
                    class="inline-flex items-center justify-center gap-2 rounded-xl bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-slate-800 dark:bg-slate-100 dark:text-slate-900 dark:hover:bg-white">
                    <i class="fa-solid fa-plus"></i>
                    Simpan Data
                </button>
            </div>
        </section>

        <form id="tiktokForm" action="{{ route('tiktok.store') }}" method="POST" class="flex flex-col gap-6">
            @csrf

            @include('tiktok.partials.form-fields', ['tiktok' => null])

            <section class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-950">
                <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                    <div>
                        <h2 class="text-lg font-semibold text-slate-900 dark:text-white">Siap Disimpan</h2>
                        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                            Pastikan judul dan embed code sudah benar sebelum data ditambahkan.
                        </p>
                    </div>

                    <div class="flex flex-col-reverse gap-3 sm:flex-row">
                        <button type="button" onclick="openModal('modalKembali')"
                            class="inline-flex items-center justify-center rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-900">
                            Batal
                        </button>
                        <button type="button" onclick="openModal('modalSubmit')"
                            class="inline-flex items-center justify-center gap-2 rounded-xl bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-slate-800 dark:bg-slate-100 dark:text-slate-900 dark:hover:bg-white">
                            <i class="fa-solid fa-floppy-disk"></i>
                            Tambahkan
                        </button>
                    </div>
                </div>
            </section>
        </form>
    </div>

    <div id="modalSubmit" data-modal
        class="fixed inset-0 z-50 hidden flex items-center justify-center bg-slate-950/50 px-4">
        <div class="w-full max-w-md rounded-2xl border border-slate-200 bg-white p-6 shadow-xl dark:border-slate-800 dark:bg-slate-950">
            <h2 class="text-lg font-semibold text-slate-900 dark:text-white">Simpan data TikTok?</h2>
            <p class="mt-2 text-sm leading-6 text-slate-500 dark:text-slate-400">
                Data akan langsung masuk ke daftar TikTok setelah disimpan.
            </p>

            <div class="mt-6 flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
                <button type="button" onclick="closeModal('modalSubmit')"
                    class="inline-flex items-center justify-center rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-900">
                    Batal
                </button>
                <button type="submit" form="tiktokForm"
                    class="inline-flex items-center justify-center gap-2 rounded-xl bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-slate-800 dark:bg-slate-100 dark:text-slate-900 dark:hover:bg-white">
                    <i class="fa-solid fa-check"></i>
                    Ya, simpan
                </button>
            </div>
        </div>
    </div>

    <div id="modalKembali" data-modal
        class="fixed inset-0 z-50 hidden flex items-center justify-center bg-slate-950/50 px-4">
        <div class="w-full max-w-md rounded-2xl border border-slate-200 bg-white p-6 shadow-xl dark:border-slate-800 dark:bg-slate-950">
            <h2 class="text-lg font-semibold text-slate-900 dark:text-white">Tinggalkan halaman ini?</h2>
            <p class="mt-2 text-sm leading-6 text-slate-500 dark:text-slate-400">
                Data yang belum disimpan akan hilang jika Anda kembali ke daftar TikTok.
            </p>

            <div class="mt-6 flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
                <button type="button" onclick="closeModal('modalKembali')"
                    class="inline-flex items-center justify-center rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-900">
                    Tetap di sini
                </button>
                <a href="{{ route('tiktok.index') }}"
                    class="inline-flex items-center justify-center rounded-xl bg-rose-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-rose-700">
                    Ya, kembali
                </a>
            </div>
        </div>
    </div>

    @include('tiktok.partials.form-scripts', ['tiktok' => null])
</x-app-layout>
