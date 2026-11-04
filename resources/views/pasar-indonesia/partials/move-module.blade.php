@php
    $moveModuleLabels = [
        'berita' => 'Berita',
        'analisis' => 'Analisis',
        'regulasi-institusi' => 'Regulasi & Institusi',
    ];
    $moveTargets = array_diff_key($moveModuleLabels, [$moveFrom => true]);
    $moveAuthorInitials = ['MRV', 'ASD', 'YDS', 'ARL', 'CP', 'ALG', 'SRH', 'SNM'];
    $moveBeritaCategoriesList = $moveBeritaCategories ?? collect();
    $moveRegulasiCategoriesList = $moveRegulasiCategories ?? collect();
@endphp

<section class="rounded-[28px] border border-amber-200 bg-amber-50/60 p-6 shadow-sm dark:border-amber-500/20 dark:bg-amber-500/5 sm:p-8">
    <div class="space-y-2">
        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-amber-700 dark:text-amber-300">Pindah Modul</p>
        <h2 class="text-2xl font-bold text-slate-900 dark:text-slate-100">Pindahkan artikel ini ke modul lain</h2>
        <p class="text-sm leading-6 text-slate-500 dark:text-slate-400">
            Aksi ini langsung dieksekusi terpisah dari tombol "Simpan Perubahan" di atas. Artikel akan dibuka otomatis di modul tujuan setelah berhasil dipindahkan.
        </p>
    </div>

    <form id="moveModuleForm" action="{{ route('pasar-indonesia.move', ['from' => $moveFrom, 'id' => $moveId]) }}"
        method="POST" class="mt-6 space-y-5"
        onsubmit="return confirm('Yakin pindahkan artikel ini ke modul lain? Aksi ini langsung dieksekusi.');">
        @csrf

        @error('to_module')
            <p class="text-sm font-medium text-rose-600 dark:text-rose-300">{{ $message }}</p>
        @enderror

        <div>
            <label for="to_module" class="mb-2 block text-sm font-semibold text-slate-900 dark:text-slate-100">
                Pindah ke modul
            </label>
            <select id="to_module" name="to_module"
                class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-slate-900 outline-none transition focus:border-amber-500 focus:ring-4 focus:ring-amber-500/10 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100">
                <option value="">Pilih modul tujuan</option>
                @foreach ($moveTargets as $moveModuleKey => $moveModuleLabel)
                    <option value="{{ $moveModuleKey }}">{{ $moveModuleLabel }}</option>
                @endforeach
            </select>
        </div>

        <div data-move-block="berita" class="hidden space-y-5 rounded-2xl border border-slate-200 bg-white p-5 dark:border-slate-800 dark:bg-slate-900">
            <div>
                <label for="move_category_berita" class="mb-2 block text-sm font-semibold text-slate-900 dark:text-slate-100">
                    Kategori Berita
                </label>
                <select id="move_category_berita" name="category_id" disabled
                    class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-slate-900 outline-none transition dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100">
                    <option value="">Pilih kategori</option>
                    @foreach ($moveBeritaCategoriesList as $moveCategory)
                        <option value="{{ $moveCategory->id }}">{{ $moveCategory->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="move_author_initial" class="mb-2 block text-sm font-semibold text-slate-900 dark:text-slate-100">
                    Author
                </label>
                <select id="move_author_initial" name="author_initial" disabled
                    class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-slate-900 outline-none transition dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100">
                    <option value="">Pilih inisial author</option>
                    @foreach ($moveAuthorInitials as $moveInitial)
                        <option value="{{ $moveInitial }}">{{ $moveInitial }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div data-move-block="regulasi-institusi" class="hidden space-y-5 rounded-2xl border border-slate-200 bg-white p-5 dark:border-slate-800 dark:bg-slate-900">
            <div>
                <label for="move_category_regulasi" class="mb-2 block text-sm font-semibold text-slate-900 dark:text-slate-100">
                    Kategori Regulasi & Institusi
                </label>
                <select id="move_category_regulasi" name="category_id" disabled
                    class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-slate-900 outline-none transition dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100">
                    <option value="">Pilih kategori</option>
                    @foreach ($moveRegulasiCategoriesList as $moveCategory)
                        <option value="{{ $moveCategory->id }}">{{ $moveCategory->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="flex justify-end">
            <button type="submit"
                class="inline-flex items-center justify-center rounded-xl bg-amber-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-amber-700">
                <i class="fa-solid fa-right-left mr-2"></i>
                Pindahkan Artikel
            </button>
        </div>
    </form>
</section>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const select = document.getElementById('to_module');
        const form = document.getElementById('moveModuleForm');

        if (!select || !form) {
            return;
        }

        const blocks = form.querySelectorAll('[data-move-block]');

        const syncBlocks = () => {
            const target = select.value;

            blocks.forEach((block) => {
                const isActive = block.dataset.moveBlock === target;
                block.classList.toggle('hidden', !isActive);
                block.querySelectorAll('select, input').forEach((field) => {
                    field.disabled = !isActive;
                    if (isActive) {
                        field.setAttribute('required', 'required');
                    } else {
                        field.removeAttribute('required');
                    }
                });
            });
        };

        select.addEventListener('change', syncBlocks);
        syncBlocks();
    });
</script>
