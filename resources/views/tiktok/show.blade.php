@section('namePage', 'Preview TikTok')

<x-app-layout>
    <div class="mx-auto flex w-full flex-col gap-6 px-4 py-8 sm:px-6 lg:px-8">
        <section
            class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-950">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                <div>
                    <a href="{{ route('tiktok.index') }}"
                        class="inline-flex items-center gap-2 text-sm font-medium text-slate-500 transition hover:text-slate-900 dark:text-slate-400 dark:hover:text-white">
                        <i class="fa-solid fa-arrow-left"></i>
                        Kembali ke daftar TikTok
                    </a>
                    <h1 class="mt-3 text-2xl font-semibold text-slate-900 dark:text-white sm:text-3xl">
                        {{ $tiktok->title }}
                    </h1>
                    <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">
                        Preview embed dan detail cadangan video untuk data TikTok ini.
                    </p>
                </div>

                <a href="{{ route('tiktok.edit', $tiktok->id) }}"
                    class="inline-flex items-center justify-center rounded-xl bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-slate-800 dark:bg-slate-100 dark:text-slate-900 dark:hover:bg-white">
                    Edit Data
                </a>
            </div>
        </section>

        <section class="grid gap-6 lg:grid-cols-[minmax(0,1.1fr)_minmax(320px,0.9fr)]">
            <div
                class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-950">
                <h2 class="text-lg font-semibold text-slate-900 dark:text-white">Preview Embed</h2>
                <div class="mt-4 overflow-hidden rounded-xl border border-slate-200 p-4 dark:border-slate-800">
                    {!! $tiktok->embed_code !!}
                </div>
            </div>

            <div class="space-y-6">
                <section
                    class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-950">
                    <h2 class="text-lg font-semibold text-slate-900 dark:text-white">Backup Video</h2>
                    @if (filled($tiktok->backup_video_url))
                        <a href="{{ $tiktok->backup_video_url }}" target="_blank" rel="noreferrer"
                            class="mt-4 inline-flex break-all text-sm text-slate-700 underline transition hover:text-slate-900 dark:text-slate-200 dark:hover:text-white">
                            {{ $tiktok->backup_video_url }}
                        </a>
                    @else
                        <p class="mt-4 text-sm text-slate-500 dark:text-slate-400">Belum ada backup video.</p>
                    @endif
                </section>

                <section
                    class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-950">
                    <h2 class="text-lg font-semibold text-slate-900 dark:text-white">Embed Code</h2>
                    <pre class="mt-4 whitespace-pre-wrap break-words rounded-xl bg-slate-50 p-4 text-xs leading-6 text-slate-700 dark:bg-slate-900 dark:text-slate-200">{{ $tiktok->embed_code }}</pre>
                </section>
            </div>
        </section>
    </div>
</x-app-layout>
