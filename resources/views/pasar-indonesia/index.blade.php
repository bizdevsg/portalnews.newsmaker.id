@section('namePage', 'Pasar Indonesia')

<x-app-layout>
    <div class="w-full px-4 py-8 sm:px-6 lg:px-8">
        <section class="rounded-xl border border-slate-200 bg-white p-5 dark:border-slate-800 dark:bg-slate-900">
            <div class="flex flex-wrap items-center justify-between gap-3">
                <div>
                    <h1 class="text-2xl font-bold text-slate-900 dark:text-slate-100">Pasar Indonesia</h1>
                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Dashboard sederhana untuk Berita dan Analisis.</p>
                </div>

                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('pasar-indonesia.index') }}#berita" class="inline-flex items-center rounded-lg border border-slate-300 px-3 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-800">
                        Berita
                    </a>
                    <a href="{{ route('pasar-indonesia.index') }}#analisis" class="inline-flex items-center rounded-lg border border-slate-300 px-3 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-800">
                        Analisis
                    </a>
                    <a href="{{ route('pasar-indonesia.berita.create') }}" class="inline-flex items-center rounded-lg bg-slate-900 px-3 py-2 text-sm font-semibold text-white hover:bg-slate-800 dark:bg-slate-100 dark:text-slate-900 dark:hover:bg-white">
                        + Berita
                    </a>
                    <a href="{{ route('pasar-indonesia.analisis.create') }}" class="inline-flex items-center rounded-lg bg-slate-900 px-3 py-2 text-sm font-semibold text-white hover:bg-slate-800 dark:bg-slate-100 dark:text-slate-900 dark:hover:bg-white">
                        + Analisis
                    </a>
                </div>
            </div>
        </section>

        @if (session('success'))
            <div class="mt-4 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700 dark:border-emerald-500/30 dark:bg-emerald-500/10 dark:text-emerald-200">
                {{ session('success') }}
            </div>
        @endif

        <section class="mt-4 grid grid-cols-1 gap-3 md:grid-cols-2">
            <div class="rounded-lg border border-slate-200 bg-white px-4 py-3 dark:border-slate-800 dark:bg-slate-900">
                <p class="text-xs uppercase tracking-wide text-slate-500 dark:text-slate-400">Total Berita</p>
                <p class="mt-1 text-2xl font-bold text-slate-900 dark:text-slate-100">{{ number_format($stats['berita']) }}</p>
            </div>
            <div class="rounded-lg border border-slate-200 bg-white px-4 py-3 dark:border-slate-800 dark:bg-slate-900">
                <p class="text-xs uppercase tracking-wide text-slate-500 dark:text-slate-400">Total Analisis</p>
                <p class="mt-1 text-2xl font-bold text-slate-900 dark:text-slate-100">{{ number_format($stats['analisis']) }}</p>
            </div>
        </section>

        <section id="berita" class="mt-6 scroll-mt-24 rounded-xl border border-slate-200 bg-white p-5 dark:border-slate-800 dark:bg-slate-900">
            <div class="mb-4 flex flex-wrap items-center justify-between gap-3">
                <h2 class="text-lg font-bold text-slate-900 dark:text-slate-100">Berita</h2>
                <a href="{{ route('pasar-indonesia.berita.create') }}" class="inline-flex items-center rounded-lg bg-slate-900 px-3 py-2 text-sm font-semibold text-white hover:bg-slate-800 dark:bg-slate-100 dark:text-slate-900 dark:hover:bg-white">
                    Tambah Berita
                </a>
            </div>

            @if ($beritaItems->isEmpty())
                <p class="text-sm text-slate-500 dark:text-slate-400">Belum ada berita.</p>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full text-left text-sm">
                        <thead class="border-b border-slate-200 text-slate-500 dark:border-slate-800 dark:text-slate-400">
                            <tr>
                                <th class="px-3 py-2 font-semibold">Judul</th>
                                <th class="px-3 py-2 font-semibold">Author</th>
                                <th class="px-3 py-2 font-semibold">Source</th>
                                <th class="px-3 py-2 font-semibold">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($beritaItems as $item)
                                <tr class="border-b border-slate-100 dark:border-slate-800">
                                    <td class="px-3 py-3">
                                        <p class="font-semibold text-slate-900 dark:text-slate-100">{{ $item->title_id }}</p>
                                        <p class="text-xs text-slate-500 dark:text-slate-400">{{ $item->title_en }}</p>
                                    </td>
                                    <td class="px-3 py-3 text-slate-700 dark:text-slate-300">{{ $item->author?->name ?? '-' }}</td>
                                    <td class="px-3 py-3 text-slate-700 dark:text-slate-300">{{ $item->source }}</td>
                                    <td class="px-3 py-3">
                                        <div class="flex flex-wrap gap-2">
                                            <a href="{{ route('pasar-indonesia.berita.edit', $item->id) }}" class="inline-flex items-center rounded-md bg-emerald-600 px-3 py-1.5 text-xs font-semibold text-white hover:bg-emerald-700">
                                                Edit
                                            </a>
                                            <form action="{{ route('pasar-indonesia.berita.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus berita ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="inline-flex items-center rounded-md bg-rose-600 px-3 py-1.5 text-xs font-semibold text-white hover:bg-rose-700">
                                                    Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </section>

        <section id="analisis" class="mt-6 scroll-mt-24 rounded-xl border border-slate-200 bg-white p-5 dark:border-slate-800 dark:bg-slate-900">
            <div class="mb-4 flex flex-wrap items-center justify-between gap-3">
                <h2 class="text-lg font-bold text-slate-900 dark:text-slate-100">Analisis</h2>
                <a href="{{ route('pasar-indonesia.analisis.create') }}" class="inline-flex items-center rounded-lg bg-slate-900 px-3 py-2 text-sm font-semibold text-white hover:bg-slate-800 dark:bg-slate-100 dark:text-slate-900 dark:hover:bg-white">
                    Tambah Analisis
                </a>
            </div>

            @if ($analisisItems->isEmpty())
                <p class="text-sm text-slate-500 dark:text-slate-400">Belum ada analisis.</p>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full text-left text-sm">
                        <thead class="border-b border-slate-200 text-slate-500 dark:border-slate-800 dark:text-slate-400">
                            <tr>
                                <th class="px-3 py-2 font-semibold">Judul</th>
                                <th class="px-3 py-2 font-semibold">Author</th>
                                <th class="px-3 py-2 font-semibold">Source</th>
                                <th class="px-3 py-2 font-semibold">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($analisisItems as $item)
                                <tr class="border-b border-slate-100 dark:border-slate-800">
                                    <td class="px-3 py-3">
                                        <p class="font-semibold text-slate-900 dark:text-slate-100">{{ $item->title_id }}</p>
                                        <p class="text-xs text-slate-500 dark:text-slate-400">{{ $item->title_en }}</p>
                                    </td>
                                    <td class="px-3 py-3 text-slate-700 dark:text-slate-300">{{ $item->author?->name ?? '-' }}</td>
                                    <td class="px-3 py-3 text-slate-700 dark:text-slate-300">{{ $item->source }}</td>
                                    <td class="px-3 py-3">
                                        <div class="flex flex-wrap gap-2">
                                            <a href="{{ route('pasar-indonesia.analisis.edit', $item->id) }}" class="inline-flex items-center rounded-md bg-emerald-600 px-3 py-1.5 text-xs font-semibold text-white hover:bg-emerald-700">
                                                Edit
                                            </a>
                                            <form action="{{ route('pasar-indonesia.analisis.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus analisis ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="inline-flex items-center rounded-md bg-rose-600 px-3 py-1.5 text-xs font-semibold text-white hover:bg-rose-700">
                                                    Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </section>
    </div>
</x-app-layout>
