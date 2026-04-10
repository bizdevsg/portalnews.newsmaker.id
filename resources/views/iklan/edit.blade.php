@section('namePage', 'Edit Iklan')

<x-app-layout>
    <form action="{{ route('iklan.update', $iklan->id) }}" method="POST" enctype="multipart/form-data"
        class="w-full px-4 py-8 sm:px-6 lg:px-8">
        @csrf
        @method('PUT')

        <section
            class="overflow-hidden rounded-[32px] bg-gradient-to-br from-slate-950 via-slate-900 to-blue-900 px-6 py-8 shadow-2xl ring-1 ring-white/10 sm:px-8 lg:px-10">
            <div class="flex flex-wrap items-start justify-between gap-6">
                <div class="space-y-4">
                    <span
                        class="inline-flex items-center rounded-full border border-white/10 bg-white/10 px-3 py-1 text-xs font-semibold uppercase tracking-[0.22em] text-blue-100">
                        Iklan
                    </span>
                    <div class="space-y-2">
                        <p class="text-xs font-semibold uppercase tracking-[0.32em] text-slate-300">Edit</p>
                        <h1 class="text-3xl font-bold tracking-tight text-white sm:text-4xl">Perbarui iklan</h1>
                        <p class="text-sm leading-6 text-blue-100 sm:text-base">
                            Ubah iklan <span class="font-semibold text-white">{{ $iklan->title }}</span> tanpa mengubah alur data,
                            termasuk kalau design modalnya memakai HTML kustom.
                        </p>
                    </div>
                </div>

                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('iklan.index') }}"
                        class="inline-flex items-center justify-center rounded-xl border border-white/15 bg-white/5 px-5 py-3 text-sm font-semibold text-white transition hover:bg-white/10">
                        Kembali
                    </a>
                    <button type="submit"
                        class="inline-flex items-center justify-center rounded-xl bg-white px-5 py-3 text-sm font-semibold text-slate-900 transition hover:bg-blue-50">
                        <i class="fa-solid fa-floppy-disk mr-2"></i>
                        Simpan Perubahan
                    </button>
                </div>
            </div>
        </section>

        @if ($errors->any())
            <div
                class="mt-6 rounded-2xl border border-rose-200 bg-rose-50 px-5 py-4 text-sm text-rose-700 shadow-sm dark:border-rose-500/20 dark:bg-rose-500/10 dark:text-rose-200">
                <p class="font-semibold">Periksa perubahan iklan sebelum disimpan.</p>
            </div>
        @endif

        @include('popup-banner.partials.form-fields', ['popupBanner' => $iklan, 'supportsCustomHtml' => $supportsCustomHtml ?? false])
    </form>
</x-app-layout>

