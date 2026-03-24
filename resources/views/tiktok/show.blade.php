@section('namePage', $tiktok->title)

@php
    $formatDate = function ($date) {
        if (!filled($date)) {
            return '-';
        }

        return \Carbon\Carbon::parse($date)->format('d M Y H:i');
    };

    $hasBackup = filled($tiktok->backup_video_url);
    $embedCode = trim((string) $tiktok->embed_code);
    $embedPreviewValue = $embedCode;
    $compactEmbedCode = preg_replace('/\s+/', ' ', $embedCode);
@endphp

<x-app-layout>
    <div class="mx-auto flex w-full flex-col gap-6 px-4 py-8 sm:px-6 lg:px-8">
        @if (session('success'))
            <div
                class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700 dark:border-emerald-500/20 dark:bg-emerald-500/10 dark:text-emerald-200">
                {{ session('success') }}
            </div>
        @endif

        <section class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-950">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                <div class="min-w-0">
                    <a href="{{ route('tiktok.index') }}"
                        class="inline-flex items-center gap-2 text-sm font-medium text-slate-500 transition hover:text-slate-900 dark:text-slate-400 dark:hover:text-white">
                        <i class="fa-solid fa-arrow-left"></i>
                        Kembali ke daftar TikTok
                    </a>

                    <h1 class="mt-3 text-2xl font-semibold text-slate-900 dark:text-white sm:text-3xl">
                        {{ $tiktok->title }}
                    </h1>

                    <div class="mt-4 flex flex-wrap gap-2 text-xs font-medium">
                        <span
                            class="rounded-full px-3 py-1.5 {{ $hasBackup
                                ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-200'
                                : 'bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-200' }}">
                            {{ $hasBackup ? 'Backup Tersedia' : 'Tanpa Backup' }}
                        </span>
                        <span class="rounded-full border border-slate-200 px-3 py-1.5 text-slate-600 dark:border-slate-700 dark:text-slate-300">
                            Dibuat {{ $formatDate($tiktok->created_at) }}
                        </span>
                        <span class="rounded-full border border-slate-200 px-3 py-1.5 text-slate-600 dark:border-slate-700 dark:text-slate-300">
                            Update {{ $formatDate($tiktok->updated_at) }}
                        </span>
                    </div>
                </div>

                <div class="flex flex-col gap-2 sm:flex-row">
                    <a href="{{ route('tiktok.edit', $tiktok->id) }}"
                        class="inline-flex items-center justify-center gap-2 rounded-xl bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-slate-800 dark:bg-slate-100 dark:text-slate-900 dark:hover:bg-white">
                        <i class="fa-solid fa-pen-to-square"></i>
                        Edit
                    </a>
                    @if ($hasBackup)
                        <a href="{{ $tiktok->backup_video_url }}" target="_blank" rel="noreferrer"
                            class="inline-flex items-center justify-center gap-2 rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-900">
                            <i class="fa-solid fa-up-right-from-square"></i>
                            Buka Backup
                        </a>
                    @endif
                    <a href="{{ route('tiktok.index') }}"
                        class="inline-flex items-center justify-center rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-900">
                        Tutup
                    </a>
                </div>
            </div>
        </section>

        <section class="grid gap-3 sm:grid-cols-3">
            <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm dark:border-slate-800 dark:bg-slate-950">
                <p class="text-sm text-slate-500 dark:text-slate-400">Status Backup</p>
                <p class="mt-2 text-base font-semibold text-slate-900 dark:text-white">
                    {{ $hasBackup ? 'Tersedia' : 'Belum ada' }}
                </p>
            </div>
            <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm dark:border-slate-800 dark:bg-slate-950">
                <p class="text-sm text-slate-500 dark:text-slate-400">Dibuat</p>
                <p class="mt-2 text-base font-semibold text-slate-900 dark:text-white">
                    {{ $formatDate($tiktok->created_at) }}
                </p>
            </div>
            <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm dark:border-slate-800 dark:bg-slate-950">
                <p class="text-sm text-slate-500 dark:text-slate-400">Terakhir Diupdate</p>
                <p class="mt-2 text-base font-semibold text-slate-900 dark:text-white">
                    {{ $formatDate($tiktok->updated_at) }}
                </p>
            </div>
        </section>

        <section class="grid gap-6 xl:grid-cols-[minmax(0,1.35fr)_minmax(320px,0.9fr)]">
            <section class="rounded-xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-950">
                <div class="border-b border-slate-200 p-5 dark:border-slate-800">
                    <h2 class="text-lg font-semibold text-slate-900 dark:text-white">Preview TikTok</h2>
                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                        Halaman ini menampilkan preview terpisah dari embed TikTok yang sudah tersimpan.
                    </p>
                </div>

                <div class="p-5">
                    <div id="tiktokPreviewEmpty"
                        class="@if (filled($embedPreviewValue)) hidden @endif rounded-xl border border-dashed border-slate-300 px-4 py-16 text-center text-sm text-slate-500 dark:border-slate-700 dark:text-slate-400">
                        Embed code belum tersedia untuk dipreview.
                    </div>

                    <iframe id="tiktokPreviewFrame"
                        class="@if (!filled($embedPreviewValue)) hidden @endif h-[780px] w-full rounded-xl border border-slate-200 bg-slate-50 dark:border-slate-800 dark:bg-slate-900"
                        title="Preview TikTok"></iframe>
                </div>
            </section>

            <div class="flex flex-col gap-6">
                <section class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-950">
                    <h2 class="text-lg font-semibold text-slate-900 dark:text-white">Informasi Konten</h2>

                    <div class="mt-5 space-y-3">
                        <div class="rounded-xl bg-slate-50 px-4 py-3 dark:bg-slate-900">
                            <p class="text-xs font-medium uppercase tracking-wide text-slate-400">Judul</p>
                            <p class="mt-2 text-sm font-medium text-slate-900 dark:text-white">{{ $tiktok->title }}</p>
                        </div>

                        <div class="rounded-xl bg-slate-50 px-4 py-3 dark:bg-slate-900">
                            <p class="text-xs font-medium uppercase tracking-wide text-slate-400">Backup Video</p>
                            @if ($hasBackup)
                                <a href="{{ $tiktok->backup_video_url }}" target="_blank" rel="noreferrer"
                                    class="mt-2 inline-flex break-all text-sm text-slate-700 underline transition hover:text-slate-900 dark:text-slate-200 dark:hover:text-white">
                                    {{ $tiktok->backup_video_url }}
                                </a>
                            @else
                                <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">Belum ada backup video.</p>
                            @endif
                        </div>

                        <div class="rounded-xl bg-slate-50 px-4 py-3 dark:bg-slate-900">
                            <p class="text-xs font-medium uppercase tracking-wide text-slate-400">Embed Ringkas</p>
                            <p class="mt-2 break-all text-sm leading-6 text-slate-700 dark:text-slate-200">
                                {{ \Illuminate\Support\Str::limit($compactEmbedCode, 260) ?: '-' }}
                            </p>
                        </div>
                    </div>
                </section>

                <section class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-950">
                    <h2 class="text-lg font-semibold text-slate-900 dark:text-white">Backup Preview</h2>

                    @if ($hasBackup)
                        <div class="mt-5 overflow-hidden rounded-xl border border-slate-200 dark:border-slate-800">
                            <video controls class="w-full bg-black" src="{{ $tiktok->backup_video_url }}">
                                Browser tidak mendukung preview video backup.
                            </video>
                        </div>
                    @else
                        <p class="mt-4 text-sm text-slate-500 dark:text-slate-400">
                            Tidak ada video backup untuk item ini.
                        </p>
                    @endif
                </section>
            </div>
        </section>
    </div>

    <script>
        const previewSource = @json($embedPreviewValue);
        const previewFrame = document.getElementById('tiktokPreviewFrame');
        const previewEmpty = document.getElementById('tiktokPreviewEmpty');

        function buildPreviewDocument(embedCode) {
            const safeEmbedCode = embedCode.replace(/<\/script>/gi, '<\\/script>');

            return `<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body {
            margin: 0;
            padding: 24px 16px;
            font-family: Inter, system-ui, sans-serif;
            background: #f8fafc;
            min-height: 100vh;
        }

        .preview-shell {
            display: flex;
            justify-content: center;
            align-items: flex-start;
            min-height: 100%;
        }

        blockquote {
            max-width: 100% !important;
        }
    </style>
</head>
<body>
    <div class="preview-shell">${safeEmbedCode}</div>
    <script async src="https://www.tiktok.com/embed.js"><\/script>
</body>
</html>`;
        }

        function renderStandalonePreview(embedCode) {
            const trimmedEmbedCode = embedCode.trim();

            if (!previewFrame || !previewEmpty) {
                return;
            }

            if (trimmedEmbedCode === '') {
                previewFrame.classList.add('hidden');
                previewFrame.srcdoc = '';
                previewEmpty.classList.remove('hidden');
                return;
            }

            previewEmpty.classList.add('hidden');
            previewFrame.classList.remove('hidden');
            previewFrame.srcdoc = buildPreviewDocument(trimmedEmbedCode);
        }

        renderStandalonePreview(previewSource);
    </script>
</x-app-layout>
