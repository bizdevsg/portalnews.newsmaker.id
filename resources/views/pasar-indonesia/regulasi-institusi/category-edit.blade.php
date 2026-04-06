@section('namePage', 'Edit Kategori Regulasi & Institusi')

<x-app-layout>
    <form action="{{ route('regulasi-institusi.kategori.update', $category->id) }}" method="POST" class="w-full px-4 py-8 sm:px-6 lg:px-8">
        @csrf
        @method('PUT')

        <section class="overflow-hidden rounded-[32px] bg-gradient-to-br from-slate-950 via-slate-900 to-indigo-900 px-6 py-8 shadow-2xl ring-1 ring-white/10 sm:px-8 lg:px-10">
            <div class="flex flex-wrap items-start justify-between gap-6">
                <div class="space-y-4">
                    <span class="inline-flex items-center rounded-full border border-white/10 bg-white/10 px-3 py-1 text-xs font-semibold uppercase tracking-[0.22em] text-indigo-100">
                        Kategori
                    </span>
                    <div class="space-y-2">
                        <p class="text-xs font-semibold uppercase tracking-[0.32em] text-slate-300">Edit</p>
                        <h1 class="text-3xl font-bold tracking-tight text-white sm:text-4xl">Perbarui kategori</h1>
                        <p class="text-sm leading-6 text-indigo-100 sm:text-base">
                            Ubah kategori <span class="font-semibold text-white">{{ $category->name }}</span> agar struktur berita tetap relevan.
                        </p>
                    </div>
                </div>

                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('regulasi-institusi.index') }}" class="inline-flex items-center justify-center rounded-xl border border-white/15 bg-white/5 px-5 py-3 text-sm font-semibold text-white transition hover:bg-white/10">
                        Kembali
                    </a>
                    <button type="submit" class="inline-flex items-center justify-center rounded-xl bg-white px-5 py-3 text-sm font-semibold text-slate-900 transition hover:bg-indigo-50">
                        <i class="fa-solid fa-floppy-disk mr-2"></i>
                        Simpan Perubahan
                    </button>
                </div>
            </div>
        </section>

        @if ($errors->any())
            <div class="mt-6 rounded-2xl border border-rose-200 bg-rose-50 px-5 py-4 text-sm text-rose-700 shadow-sm dark:border-rose-500/20 dark:bg-rose-500/10 dark:text-rose-200">
                <p class="font-semibold">Periksa perubahan kategori sebelum disimpan.</p>
            </div>
        @endif

        <section class="mt-8 rounded-[28px] border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900 sm:p-8">
            <div class="grid grid-cols-1 gap-6 xl:grid-cols-[1.1fr_0.9fr]">
                <div class="space-y-2">
                    <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500 dark:text-slate-400">Detail</p>
                    <h2 class="text-2xl font-bold text-slate-900 dark:text-slate-100">Nama kategori</h2>
                    <p class="text-sm leading-6 text-slate-500 dark:text-slate-400">
                        Nama ini akan tampil pada daftar kategori dan detail Berita Regulasi & Institusi.
                    </p>
                </div>

                <div class="space-y-5">
                    <div>
                        <label for="name" class="mb-2 block text-sm font-semibold text-slate-900 dark:text-slate-100">
                            Nama kategori
                        </label>
                        <input type="text" id="name" name="name" value="{{ old('name', $category->name) }}" placeholder="Contoh: Market Update" class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-slate-900 outline-none transition focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100" required>
                        <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">Maksimal 100 karakter.</p>
                        @error('name')
                            <p class="mt-2 text-sm font-medium text-rose-600 dark:text-rose-300">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </section>
    </form>
</x-app-layout>

