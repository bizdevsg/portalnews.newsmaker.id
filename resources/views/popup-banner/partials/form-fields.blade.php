@php
    $supportsCustomHtml = $supportsCustomHtml ?? false;
    $startAtValue = old('start_at', optional($popupBanner?->start_at)->format('Y-m-d\TH:i'));
    $endAtValue = old('end_at', optional($popupBanner?->end_at)->format('Y-m-d\TH:i'));
    $isActiveValue = (string) old('is_active', isset($popupBanner) ? (int) $popupBanner->is_active : 1);
    $designMode = $supportsCustomHtml
        ? old('design_mode', filled($popupBanner?->modal_html) ? 'html' : 'standard')
        : 'standard';
    $modalHtmlValue = old('modal_html', $popupBanner?->modal_html ?? '');
@endphp

<section data-popup-banner-builder
    class="mt-8 rounded-[28px] border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900 sm:p-8">
    <div class="grid grid-cols-1 gap-6 xl:grid-cols-[1.1fr_0.9fr]">
        <div class="space-y-5">
            <div class="space-y-2">
                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500 dark:text-slate-400">Detail</p>
                <h2 class="text-2xl font-bold text-slate-900 dark:text-slate-100">Konten banner</h2>
                <p class="text-sm leading-6 text-slate-500 dark:text-slate-400">
                    Modul ini mendukung banner biasa berbasis field, dan jika migrasi terbaru sudah dijalankan juga
                    mendukung modal kustom dari file HTML/paste markup.
                </p>
            </div>

            <div class="rounded-[24px] border border-slate-200 bg-slate-50 p-5 dark:border-slate-800 dark:bg-slate-950/60">
                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">Alur kerja</p>
                <div class="mt-4 space-y-4 text-sm leading-6 text-slate-600 dark:text-slate-300">
                    <div>
                        <p class="font-semibold text-slate-900 dark:text-slate-100">1. Pilih mode desain</p>
                        <p>Pakai mode standar untuk isi field biasa, atau pilih HTML kustom untuk upload design modal.</p>
                    </div>

                    <div>
                        <p class="font-semibold text-slate-900 dark:text-slate-100">2. Upload atau paste HTML</p>
                        <p>File `.html` akan dibaca lalu disimpan sebagai markup modal. Kalau perlu, bisa edit manual di textarea.</p>
                    </div>

                    <div>
                        <p class="font-semibold text-slate-900 dark:text-slate-100">3. Cek preview</p>
                        <p>Preview live membantu lihat bentuk modal sebelum disimpan, tanpa perlu render manual di frontend.</p>
                    </div>
                </div>
            </div>

            <div class="overflow-hidden rounded-[24px] border border-slate-200 bg-slate-50 shadow-sm dark:border-slate-800 dark:bg-slate-950/60">
                <div class="flex items-center justify-between gap-3 border-b border-slate-200 px-5 py-4 dark:border-slate-800">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">Preview HTML</p>
                        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Upload file atau paste HTML untuk lihat hasil modal.</p>
                    </div>
                    <span data-html-file-name
                        class="max-w-[180px] truncate rounded-full bg-white px-3 py-1 text-xs font-medium text-slate-500 dark:bg-slate-900 dark:text-slate-300">
                        {{ $supportsCustomHtml && filled($popupBanner?->modal_html) ? 'HTML tersimpan' : 'Belum ada file' }}
                    </span>
                </div>
                <div class="p-5">
                    <div class="overflow-hidden rounded-2xl border border-dashed border-slate-300 bg-white dark:border-slate-700 dark:bg-slate-900">
                        <div data-html-preview-empty
                            class="flex min-h-[420px] items-center justify-center px-6 text-center text-sm leading-6 text-slate-500 dark:text-slate-400">
                            @if ($supportsCustomHtml)
                                Upload file `.html` atau paste markup HTML untuk menampilkan preview modal di area ini.
                            @else
                                Jalankan migrasi terbaru dulu supaya mode HTML kustom dan preview bisa dipakai.
                            @endif
                        </div>
                        <iframe data-html-preview class="hidden h-[420px] w-full bg-white"
                            sandbox="allow-scripts"></iframe>
                    </div>
                    <p class="mt-3 text-xs leading-5 text-slate-500 dark:text-slate-400">
                        HTML dirender di `iframe srcdoc` untuk preview. Pastikan markup berasal dari sumber internal
                        yang memang ingin dipakai.
                    </p>
                </div>
            </div>
        </div>

        <div class="space-y-5">
            <div>
                <label for="title" class="mb-2 block text-sm font-semibold text-slate-900 dark:text-slate-100">
                    Judul banner
                </label>
                <input type="text" id="title" name="title" value="{{ old('title', $popupBanner?->title ?? '') }}"
                    placeholder="Contoh: Install Aplikasi Newsmaker 23"
                    class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-slate-900 outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100"
                    required>
                @error('title')
                    <p class="mt-2 text-sm font-medium text-rose-600 dark:text-rose-300">{{ $message }}</p>
                @enderror
            </div>

            <div class="rounded-[24px] border border-slate-200 bg-slate-50 p-5 dark:border-slate-800 dark:bg-slate-950/60">
                <label class="block text-sm font-semibold text-slate-900 dark:text-slate-100">
                    Mode desain
                </label>
                <p class="mt-2 text-sm leading-6 text-slate-500 dark:text-slate-400">
                    Pilih cara membangun modal. Mode HTML cocok kalau design sudah jadi dalam bentuk file `.html`.
                </p>
                <div class="mt-4 grid gap-3 sm:grid-cols-2">
                    <label
                        class="flex cursor-pointer items-start gap-3 rounded-2xl border border-slate-200 bg-white p-4 dark:border-slate-700 dark:bg-slate-900">
                        <input type="radio" name="design_mode" value="standard"
                            class="mt-1 h-4 w-4 border-slate-300 text-blue-600 focus:ring-blue-500"
                            @checked($designMode === 'standard')>
                        <span>
                            <span class="block text-sm font-semibold text-slate-900 dark:text-slate-100">Standar</span>
                            <span class="mt-1 block text-sm leading-6 text-slate-500 dark:text-slate-400">
                                Pakai field deskripsi, gambar, CTA, dan jadwal seperti biasa.
                            </span>
                        </span>
                    </label>

                    <label
                        class="flex cursor-pointer items-start gap-3 rounded-2xl border border-slate-200 bg-white p-4 dark:border-slate-700 dark:bg-slate-900">
                        <input type="radio" name="design_mode" value="html"
                            class="mt-1 h-4 w-4 border-slate-300 text-blue-600 focus:ring-blue-500"
                            @checked($designMode === 'html') @disabled(!$supportsCustomHtml)>
                        <span>
                            <span class="block text-sm font-semibold text-slate-900 dark:text-slate-100">HTML Kustom</span>
                            <span class="mt-1 block text-sm leading-6 text-slate-500 dark:text-slate-400">
                                @if ($supportsCustomHtml)
                                    Upload file `.html` atau paste markup modal sendiri lalu preview langsung.
                                @else
                                    Butuh migrasi terbaru dulu sebelum mode ini bisa dipakai.
                                @endif
                            </span>
                        </span>
                    </label>
                </div>
                @unless ($supportsCustomHtml)
                    <p class="mt-3 text-sm font-medium text-amber-700 dark:text-amber-200">
                        Jalankan `php artisan migrate` untuk mengaktifkan mode HTML kustom.
                    </p>
                @endunless
                @error('design_mode')
                    <p class="mt-2 text-sm font-medium text-rose-600 dark:text-rose-300">{{ $message }}</p>
                @enderror
            </div>

            <div data-standard-fields class="space-y-5 @if ($designMode !== 'standard') hidden @endif">
                <div>
                    <label for="description" class="mb-2 block text-sm font-semibold text-slate-900 dark:text-slate-100">
                        Deskripsi
                    </label>
                    <textarea id="description" name="description" rows="4" placeholder="Contoh: Download aplikasi untuk akses berita dan update market lebih cepat."
                        class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-slate-900 outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100">{{ old('description', $popupBanner?->description ?? '') }}</textarea>
                    @error('description')
                        <p class="mt-2 text-sm font-medium text-rose-600 dark:text-rose-300">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="image" class="mb-2 block text-sm font-semibold text-slate-900 dark:text-slate-100">
                        Gambar banner
                    </label>
                    <input type="file" id="image" name="image" accept=".jpg,.jpeg,.png,.webp"
                        class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-slate-900 outline-none transition file:mr-4 file:rounded-lg file:border-0 file:bg-slate-900 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-white hover:file:bg-slate-800 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100 dark:file:bg-slate-100 dark:file:text-slate-900">
                    <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">Format `jpg`, `jpeg`, `png`, atau `webp` maksimal 2MB.</p>
                    @if (!empty($popupBanner?->image))
                        <img src="{{ asset($popupBanner->image) }}" alt="{{ $popupBanner->title }}"
                            class="mt-4 h-44 w-full rounded-2xl object-cover sm:w-80">
                    @endif
                    @error('image')
                        <p class="mt-2 text-sm font-medium text-rose-600 dark:text-rose-300">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid gap-5 md:grid-cols-2">
                    <div>
                        <label for="cta_label" class="mb-2 block text-sm font-semibold text-slate-900 dark:text-slate-100">
                            Label CTA
                        </label>
                        <input type="text" id="cta_label" name="cta_label"
                            value="{{ old('cta_label', $popupBanner?->cta_label ?? '') }}" placeholder="Contoh: Download Sekarang"
                            class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-slate-900 outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100">
                        @error('cta_label')
                            <p class="mt-2 text-sm font-medium text-rose-600 dark:text-rose-300">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="cta_url" class="mb-2 block text-sm font-semibold text-slate-900 dark:text-slate-100">
                            Link CTA / Deep Link
                        </label>
                        <input type="text" id="cta_url" name="cta_url"
                            value="{{ old('cta_url', $popupBanner?->cta_url ?? '') }}"
                            placeholder="Contoh: https://play.google.com/store/apps/details?id=com.newsmaker.app"
                            class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-slate-900 outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100">
                        <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">
                            Bisa isi link website, Play Store, App Store, atau deep link aplikasi.
                        </p>
                        @error('cta_url')
                            <p class="mt-2 text-sm font-medium text-rose-600 dark:text-rose-300">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <div data-html-fields class="space-y-5 @if ($designMode !== 'html') hidden @endif">
                <div>
                    <label for="html_file" class="mb-2 block text-sm font-semibold text-slate-900 dark:text-slate-100">
                        Upload file HTML
                    </label>
                    <input type="file" id="html_file" name="html_file" accept=".html,.htm"
                        class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-slate-900 outline-none transition file:mr-4 file:rounded-lg file:border-0 file:bg-slate-900 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-white hover:file:bg-slate-800 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100 dark:file:bg-slate-100 dark:file:text-slate-900">
                    <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">
                        Upload file `.html` maksimal 512KB. Kalau file dipilih, isinya akan diprioritaskan saat simpan.
                    </p>
                    @error('html_file')
                        <p class="mt-2 text-sm font-medium text-rose-600 dark:text-rose-300">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="modal_html" class="mb-2 block text-sm font-semibold text-slate-900 dark:text-slate-100">
                        HTML modal
                    </label>
                    <textarea id="modal_html" name="modal_html" rows="16"
                        placeholder="<div class=&quot;relative w-full max-w-xl rounded-3xl bg-white p-8 shadow-2xl&quot;>
  <span class=&quot;inline-flex rounded-full bg-sky-100 px-3 py-1 text-sm font-semibold text-sky-700&quot;>Newsmaker 23 App</span>
  <h2 class=&quot;mt-4 text-3xl font-bold text-slate-900&quot;>Install aplikasi kami</h2>
  <p class=&quot;mt-3 text-base text-slate-600&quot;>Akses update market lebih cepat langsung dari ponsel.</p>
  <a href=&quot;https://example.com&quot; class=&quot;mt-6 inline-flex rounded-full bg-slate-900 px-5 py-3 text-white&quot;>Download Sekarang</a>
</div>"
                        class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 font-mono text-sm leading-6 text-slate-900 outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100">{{ $modalHtmlValue }}</textarea>
                    <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">
                        Bisa isi full document HTML atau hanya fragment modal. Simpan hanya markup yang memang ingin
                        dirender.
                    </p>
                    @error('modal_html')
                        <p class="mt-2 text-sm font-medium text-rose-600 dark:text-rose-300">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid gap-5 md:grid-cols-2">
                <div>
                    <label for="start_at" class="mb-2 block text-sm font-semibold text-slate-900 dark:text-slate-100">
                        Mulai tayang
                    </label>
                    <input type="datetime-local" id="start_at" name="start_at" value="{{ $startAtValue }}"
                        class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-slate-900 outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100">
                    @error('start_at')
                        <p class="mt-2 text-sm font-medium text-rose-600 dark:text-rose-300">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="end_at" class="mb-2 block text-sm font-semibold text-slate-900 dark:text-slate-100">
                        Selesai tayang
                    </label>
                    <input type="datetime-local" id="end_at" name="end_at" value="{{ $endAtValue }}"
                        class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-slate-900 outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100">
                    @error('end_at')
                        <p class="mt-2 text-sm font-medium text-rose-600 dark:text-rose-300">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid gap-5 md:grid-cols-2">
                <div>
                    <label for="is_active" class="mb-2 block text-sm font-semibold text-slate-900 dark:text-slate-100">
                        Status
                    </label>
                    <select id="is_active" name="is_active"
                        class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-slate-900 outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100">
                        <option value="1" @selected($isActiveValue === '1')>Aktif</option>
                        <option value="0" @selected($isActiveValue === '0')>Nonaktif</option>
                    </select>
                    @error('is_active')
                        <p class="mt-2 text-sm font-medium text-rose-600 dark:text-rose-300">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="sort_order" class="mb-2 block text-sm font-semibold text-slate-900 dark:text-slate-100">
                        Urutan tampil
                    </label>
                    <input type="number" min="0" id="sort_order" name="sort_order"
                        value="{{ old('sort_order', $popupBanner?->sort_order ?? 0) }}"
                        class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-3 text-slate-900 outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100">
                    <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">Semakin kecil nilainya, semakin atas urutannya.</p>
                    @error('sort_order')
                        <p class="mt-2 text-sm font-medium text-rose-600 dark:text-rose-300">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const root = document.querySelector('[data-popup-banner-builder]');

        if (!root) {
            return;
        }

        const designModeInputs = root.querySelectorAll('input[name="design_mode"]');
        const standardFields = root.querySelector('[data-standard-fields]');
        const htmlFields = root.querySelector('[data-html-fields]');
        const htmlTextarea = root.querySelector('#modal_html');
        const htmlFileInput = root.querySelector('#html_file');
        const fileNameBadge = root.querySelector('[data-html-file-name]');
        const previewEmpty = root.querySelector('[data-html-preview-empty]');
        const previewFrame = root.querySelector('[data-html-preview]');

        const renderMode = () => {
            const activeMode = root.querySelector('input[name="design_mode"]:checked')?.value ?? 'standard';

            standardFields?.classList.toggle('hidden', activeMode !== 'standard');
            htmlFields?.classList.toggle('hidden', activeMode !== 'html');
        };

        const renderPreview = (html, label = null) => {
            const markup = (html ?? '').trim();

            if (fileNameBadge && label) {
                fileNameBadge.textContent = label;
            }

            if (!previewFrame || !previewEmpty) {
                return;
            }

            if (markup === '') {
                previewFrame.srcdoc = '';
                previewFrame.classList.add('hidden');
                previewEmpty.classList.remove('hidden');

                if (fileNameBadge && !label) {
                    fileNameBadge.textContent = 'Belum ada file';
                }

                return;
            }

            previewFrame.srcdoc = markup;
            previewFrame.classList.remove('hidden');
            previewEmpty.classList.add('hidden');

            if (fileNameBadge && !label) {
                fileNameBadge.textContent = 'Markup aktif';
            }
        };

        const renderPreviewFromTextarea = () => {
            renderPreview(htmlTextarea?.value ?? '');
        };

        designModeInputs.forEach((input) => {
            input.addEventListener('change', renderMode);
        });

        htmlTextarea?.addEventListener('input', () => {
            if (htmlFileInput?.files?.length) {
                return;
            }

            renderPreviewFromTextarea();
        });

        htmlFileInput?.addEventListener('change', (event) => {
            const file = event.target.files?.[0];

            if (!file) {
                renderPreviewFromTextarea();
                return;
            }

            const reader = new FileReader();
            reader.onload = () => renderPreview(String(reader.result ?? ''), file.name);
            reader.readAsText(file);
        });

        renderMode();
        renderPreviewFromTextarea();
    });
</script>
