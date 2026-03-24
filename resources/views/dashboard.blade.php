@section('namePage', 'Dashboard')

<x-app-layout>
    @php
        $isSuperadmin = auth()->user()?->role === 'Superadmin';
        $topCategory = $topCategories->first();
        $totalBeritaCount = (int) ($widget['berita'] ?? 0);

        $quickActions = collect([
            [
                'label' => $firstCategory ? 'Tambah Berita' : 'Buat Kategori',
                'href' => $firstCategory ? route('berita.create', $firstCategory->slug) : route('kategori.create'),
                'icon' => 'fa-plus',
                'class' => 'bg-sky-600 text-white hover:bg-sky-700',
            ],
            [
                'label' => 'Tambah Kalender',
                'href' => route('calendar.create'),
                'icon' => 'fa-calendar-plus',
                'class' =>
                    'bg-white text-gray-700 ring-1 ring-gray-200 hover:bg-gray-50 dark:bg-gray-900 dark:text-gray-200 dark:ring-gray-700 dark:hover:bg-gray-800',
            ],
            [
                'label' => 'Tambah Historical Data',
                'href' => route('pivot.create'),
                'icon' => 'fa-chart-column',
                'class' =>
                    'bg-white text-gray-700 ring-1 ring-gray-200 hover:bg-gray-50 dark:bg-gray-900 dark:text-gray-200 dark:ring-gray-700 dark:hover:bg-gray-800',
            ],
            [
                'label' => 'Kelola User',
                'href' => route('user.index'),
                'icon' => 'fa-users-gear',
                'class' =>
                    'bg-white text-gray-700 ring-1 ring-gray-200 hover:bg-gray-50 dark:bg-gray-900 dark:text-gray-200 dark:ring-gray-700 dark:hover:bg-gray-800',
                'visible' => $isSuperadmin,
            ],
        ])
            ->filter(fn($action) => $action['visible'] ?? true)
            ->values();

        $overviewCards = [
            [
                'title' => 'Berita',
                'value' => $widget['berita'] ?? 0,
                'note' => ($summary['berita_today'] ?? 0) . ' dibuat hari ini',
                'href' => route('kategori.index'),
                'icon' => 'fa-newspaper',
                'class' => 'bg-sky-50 text-sky-700 dark:bg-sky-500/10 dark:text-sky-300',
                'updated_at' => $moduleUpdates['berita'] ?? null,
            ],
            [
                'title' => 'Kategori',
                'value' => $widget['category'] ?? 0,
                'note' => ($summary['empty_categories'] ?? 0) . ' kategori belum terisi',
                'href' => route('kategori.index'),
                'icon' => 'fa-layer-group',
                'class' => 'bg-amber-50 text-amber-700 dark:bg-amber-500/10 dark:text-amber-300',
                'updated_at' => $moduleUpdates['berita'] ?? null,
            ],
            [
                'title' => 'Kalender',
                'value' => $widget['calendar'] ?? 0,
                'note' => ($summary['calendar_next_seven_days'] ?? 0) . ' agenda 7 hari ke depan',
                'href' => route('calendar.index'),
                'icon' => 'fa-calendar-days',
                'class' => 'bg-rose-50 text-rose-700 dark:bg-rose-500/10 dark:text-rose-300',
                'updated_at' => $moduleUpdates['calendar'] ?? null,
            ],
            [
                'title' => 'Historical Data',
                'value' => $widget['pivot'] ?? 0,
                'note' => 'Market data yang tersimpan',
                'href' => route('pivot.index'),
                'icon' => 'fa-chart-line',
                'class' => 'bg-emerald-50 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-300',
                'updated_at' => $moduleUpdates['pivot'] ?? null,
            ],
            [
                'title' => 'TikTok',
                'value' => $widget['tiktok'] ?? 0,
                'note' => 'Konten video siap pakai',
                'href' => route('tiktok.index'),
                'icon' => 'fa-film',
                'class' => 'bg-fuchsia-50 text-fuchsia-700 dark:bg-fuchsia-500/10 dark:text-fuchsia-300',
                'updated_at' => $moduleUpdates['tiktok'] ?? null,
            ],
        ];

        $activityToneClasses = [
            'sky' => 'bg-sky-50 text-sky-700 dark:bg-sky-500/10 dark:text-sky-300',
            'rose' => 'bg-rose-50 text-rose-700 dark:bg-rose-500/10 dark:text-rose-300',
            'emerald' => 'bg-emerald-50 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-300',
            'fuchsia' => 'bg-fuchsia-50 text-fuchsia-700 dark:bg-fuchsia-500/10 dark:text-fuchsia-300',
        ];

        $impactClasses = [
            'High' => 'bg-red-50 text-red-700 dark:bg-red-500/10 dark:text-red-300',
            'Medium' => 'bg-amber-50 text-amber-700 dark:bg-amber-500/10 dark:text-amber-300',
            'Low' => 'bg-emerald-50 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-300',
        ];

        $filledCategories = max(($widget['category'] ?? 0) - ($summary['empty_categories'] ?? 0), 0);
        $categoryFillPercentage = ($widget['category'] ?? 0) > 0 ? ($filledCategories / $widget['category']) * 100 : 0;
        $activeModulesCount = collect($moduleUpdates)->filter()->count();
        $snapshotCards = [
            [
                'label' => 'Berita Hari Ini',
                'value' => $summary['berita_today'] ?? 0,
                'caption' => 'artikel baru',
                'icon' => 'fa-pen-nib',
                'class' => 'bg-sky-50 text-sky-700 dark:bg-sky-500/10 dark:text-sky-300',
            ],
            [
                'label' => 'Minggu Ini',
                'value' => $summary['berita_this_week'] ?? 0,
                'caption' => 'artikel terbit',
                'icon' => 'fa-calendar-week',
                'class' => 'bg-indigo-50 text-indigo-700 dark:bg-indigo-500/10 dark:text-indigo-300',
            ],
            [
                'label' => 'Agenda 7 Hari',
                'value' => $summary['calendar_next_seven_days'] ?? 0,
                'caption' => 'event ekonomi',
                'icon' => 'fa-calendar-days',
                'class' => 'bg-rose-50 text-rose-700 dark:bg-rose-500/10 dark:text-rose-300',
            ],
            [
                'label' => 'Kategori Aktif',
                'value' => $filledCategories,
                'caption' => 'sudah berisi',
                'icon' => 'fa-folder-open',
                'class' => 'bg-emerald-50 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-300',
            ],
            [
                'label' => 'Kategori Kosong',
                'value' => $summary['empty_categories'] ?? 0,
                'caption' => 'perlu diisi',
                'icon' => 'fa-folder',
                'class' => 'bg-amber-50 text-amber-700 dark:bg-amber-500/10 dark:text-amber-300',
            ],
            [
                'label' => 'Modul Update',
                'value' => $activeModulesCount,
                'caption' => 'modul aktif',
                'icon' => 'fa-satellite-dish',
                'class' => 'bg-fuchsia-50 text-fuchsia-700 dark:bg-fuchsia-500/10 dark:text-fuchsia-300',
            ],
        ];
    @endphp

    <div class="w-full max-w-9xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">
        <section class="grid gap-6 xl:grid-cols-[minmax(0,1.35fr)_360px]">
            <div class="rounded-3xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-gray-900 lg:p-8">
                <div
                    class="flex flex-col gap-4 border-b border-gray-100 pb-5 dark:border-gray-800 md:flex-row md:items-start md:justify-between">
                    <div>
                        <p class="text-sm font-semibold uppercase tracking-[0.22em] text-sky-700 dark:text-sky-300">
                            Snapshot Operasional
                        </p>
                        <h1 class="mt-3 text-2xl font-semibold tracking-tight text-gray-900 dark:text-white lg:text-3xl">
                            Ringkasan pergerakan konten dan modul
                        </h1>
                        <p class="mt-2 max-w-2xl text-sm leading-7 text-gray-500 dark:text-gray-400">
                            Fokus ke angka yang paling berguna untuk monitoring harian tanpa hero kosong yang terlalu
                            besar.
                        </p>
                    </div>

                    <div class="rounded-2xl bg-gray-50 px-4 py-3 dark:bg-gray-800">
                        <p class="text-xs font-medium uppercase tracking-[0.18em] text-gray-500 dark:text-gray-400">
                            Update Terakhir
                        </p>
                        <p class="mt-2 text-sm font-semibold text-gray-900 dark:text-white">
                            {{ optional($summary['latest_update'] ?? null)->format('d M Y, H:i') ?? 'Belum ada update' }}
                        </p>
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                            {{ optional($summary['latest_update'] ?? null)->diffForHumans() ?? 'Menunggu aktivitas baru' }}
                        </p>
                    </div>
                </div>

                <div class="mt-5 grid gap-3 md:grid-cols-2 xl:grid-cols-3">
                    @foreach ($snapshotCards as $card)
                        <div class="rounded-2xl border border-gray-200 p-4 dark:border-gray-800">
                            <div class="flex items-start justify-between gap-3">
                                <div>
                                    <p
                                        class="text-xs font-medium uppercase tracking-[0.18em] text-gray-500 dark:text-gray-400">
                                        {{ $card['label'] }}
                                    </p>
                                    <p class="mt-2 text-2xl font-semibold text-gray-900 dark:text-white">
                                        {{ $card['value'] }}
                                    </p>
                                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                        {{ $card['caption'] }}
                                    </p>
                                </div>
                                <div
                                    class="inline-flex h-11 w-11 items-center justify-center rounded-2xl {{ $card['class'] }}">
                                    <i class="fa-solid {{ $card['icon'] }}"></i>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-5 grid gap-4 lg:grid-cols-2">
                    <div class="rounded-2xl bg-gray-50 p-4 dark:bg-gray-800">
                        <div class="flex items-center justify-between gap-3">
                            <div>
                                <p
                                    class="text-xs font-medium uppercase tracking-[0.18em] text-gray-500 dark:text-gray-400">
                                    Cakupan Kategori
                                </p>
                                <p class="mt-2 text-lg font-semibold text-gray-900 dark:text-white">
                                    {{ $filledCategories }} dari {{ $widget['category'] ?? 0 }} kategori aktif
                                </p>
                            </div>
                            <span class="text-sm font-semibold text-sky-700 dark:text-sky-300">
                                {{ number_format($categoryFillPercentage, 0) }}%
                            </span>
                        </div>

                        <div class="mt-4 h-2 rounded-full bg-gray-200 dark:bg-gray-700">
                            <div class="h-2 rounded-full bg-sky-600" style="width: {{ $categoryFillPercentage }}%">
                            </div>
                        </div>

                        <p class="mt-3 text-sm text-gray-500 dark:text-gray-400">
                            Kategori teraktif:
                            <span class="font-medium text-gray-700 dark:text-gray-200">
                                {{ $topCategory?->name ?? 'Belum ada data' }}
                            </span>
                        </p>
                    </div>

                    <div class="rounded-2xl bg-gray-50 p-4 dark:bg-gray-800">
                        <p class="text-xs font-medium uppercase tracking-[0.18em] text-gray-500 dark:text-gray-400">
                            Status Modul
                        </p>
                        <div class="mt-4 space-y-3">
                            @foreach (['Berita' => 'berita', 'Kalender' => 'calendar', 'Historical' => 'pivot', 'TikTok' => 'tiktok'] as $label => $key)
                                <div class="flex items-center justify-between gap-3">
                                    <span class="text-sm font-medium text-gray-700 dark:text-gray-200">
                                        {{ $label }}
                                    </span>
                                    <span class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ optional($moduleUpdates[$key] ?? null)->diffForHumans() ?? 'belum ada update' }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                <section class="rounded-3xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-gray-900">
                    <div class="flex items-center justify-between gap-3">
                        <div>
                            <p
                                class="text-xs font-semibold uppercase tracking-[0.22em] text-gray-500 dark:text-gray-400">
                                Aksi Cepat
                            </p>
                            <h2 class="mt-2 text-lg font-semibold text-gray-900 dark:text-white">
                                Langsung ke pekerjaan utama
                            </h2>
                        </div>
                        <div
                            class="inline-flex h-11 w-11 items-center justify-center rounded-2xl bg-sky-50 text-sky-700 dark:bg-sky-500/10 dark:text-sky-300">
                            <i class="fa-solid fa-bolt"></i>
                        </div>
                    </div>

                    <div class="mt-5 grid gap-3">
                        @foreach ($quickActions as $action)
                            <a href="{{ $action['href'] }}"
                                class="inline-flex items-center justify-center gap-2 rounded-xl px-4 py-3 text-sm font-semibold transition {{ $action['class'] }}">
                                <i class="fa-solid {{ $action['icon'] }}"></i>
                                {{ $action['label'] }}
                            </a>
                        @endforeach
                    </div>
                </section>

                <section class="rounded-3xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-gray-900">
                    <p class="text-xs font-semibold uppercase tracking-[0.22em] text-gray-500 dark:text-gray-400">
                        Fokus Hari Ini
                    </p>
                    <div class="mt-4 space-y-4">
                        <div class="rounded-2xl bg-gray-50 p-4 dark:bg-gray-800">
                            <p class="text-xs font-medium uppercase tracking-[0.18em] text-gray-500 dark:text-gray-400">
                                Kategori Teraktif
                            </p>
                            <p class="mt-2 text-lg font-semibold text-gray-900 dark:text-white">
                                {{ $topCategory?->name ?? 'Belum ada data' }}
                            </p>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-300">
                                {{ $topCategory?->berita_count ?? 0 }} berita
                            </p>
                        </div>

                        <div class="rounded-2xl bg-gray-50 p-4 dark:bg-gray-800">
                            <p class="text-xs font-medium uppercase tracking-[0.18em] text-gray-500 dark:text-gray-400">
                                Update Terakhir
                            </p>
                            <p class="mt-2 text-base font-semibold text-gray-900 dark:text-white">
                                {{ optional($summary['latest_update'] ?? null)->format('d M Y, H:i') ?? 'Belum ada update' }}
                            </p>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-300">
                                {{ optional($summary['latest_update'] ?? null)->diffForHumans() ?? 'Data belum tersedia' }}
                            </p>
                        </div>

                        @if ($isSuperadmin)
                            <div class="grid grid-cols-2 gap-3">
                                <div class="rounded-2xl bg-gray-50 p-4 dark:bg-gray-800">
                                    <p
                                        class="text-xs font-medium uppercase tracking-[0.18em] text-gray-500 dark:text-gray-400">
                                        Admin
                                    </p>
                                    <p class="mt-2 text-xl font-semibold text-gray-900 dark:text-white">
                                        {{ $widget['admin'] ?? 0 }}
                                    </p>
                                </div>
                                <div class="rounded-2xl bg-gray-50 p-4 dark:bg-gray-800">
                                    <p
                                        class="text-xs font-medium uppercase tracking-[0.18em] text-gray-500 dark:text-gray-400">
                                        Superadmin
                                    </p>
                                    <p class="mt-2 text-xl font-semibold text-gray-900 dark:text-white">
                                        {{ $widget['superadmin'] ?? 0 }}
                                    </p>
                                </div>
                            </div>
                        @endif
                    </div>
                </section>
            </div>
        </section>

        <section class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-5">
            @foreach ($overviewCards as $card)
                <a href="{{ $card['href'] }}"
                    class="rounded-2xl border border-gray-200 bg-white p-5 transition hover:-translate-y-0.5 hover:shadow-md dark:border-gray-800 dark:bg-gray-900">
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ $card['title'] }}</p>
                            <p class="mt-2 text-2xl font-semibold text-gray-900 dark:text-white">{{ $card['value'] }}
                            </p>
                        </div>
                        <div
                            class="inline-flex h-12 w-12 items-center justify-center rounded-2xl {{ $card['class'] }}">
                            <i class="fa-solid {{ $card['icon'] }} text-lg"></i>
                        </div>
                    </div>
                    <p class="mt-4 text-sm text-gray-600 dark:text-gray-300">{{ $card['note'] }}</p>
                    <p class="mt-2 text-xs text-gray-400 dark:text-gray-500">
                        Update {{ optional($card['updated_at'])->diffForHumans() ?? 'belum ada' }}
                    </p>
                </a>
            @endforeach
        </section>

        <section class="grid gap-6 xl:grid-cols-[minmax(0,1.25fr)_360px]">
            <div class="space-y-6">
                <section class="rounded-3xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900">
                    <div class="border-b border-gray-100 px-6 py-5 dark:border-gray-800">
                        <p class="text-xs font-semibold uppercase tracking-[0.22em] text-sky-700 dark:text-sky-300">
                            Aktivitas
                        </p>
                        <h2 class="mt-2 text-xl font-semibold text-gray-900 dark:text-white">Pembaruan Terbaru</h2>
                    </div>

                    <div class="divide-y divide-gray-100 dark:divide-gray-800">
                        @forelse ($recentActivities as $activity)
                            <article
                                class="flex flex-col gap-4 px-6 py-5 md:flex-row md:items-center md:justify-between">
                                <div class="min-w-0">
                                    <div class="flex flex-wrap items-center gap-2 text-xs">
                                        <span
                                            class="inline-flex items-center rounded-full px-2.5 py-1 font-semibold {{ $activityToneClasses[$activity['tone']] ?? 'bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-300' }}">
                                            {{ $activity['type'] }}
                                        </span>
                                        <span class="text-gray-400 dark:text-gray-500">
                                            {{ optional($activity['time'])->format('d M Y H:i') ?? '-' }}
                                        </span>
                                    </div>
                                    <h3 class="mt-2 text-base font-semibold text-gray-900 dark:text-white">
                                        {{ \Illuminate\Support\Str::limit($activity['title'], 90) }}
                                    </h3>
                                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                        {{ $activity['subtitle'] }}
                                    </p>
                                </div>

                                <a href="{{ $activity['href'] }}"
                                    class="inline-flex shrink-0 items-center justify-center rounded-xl bg-gray-100 px-4 py-2.5 text-sm font-semibold text-gray-700 transition hover:bg-gray-200 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700">
                                    {{ $activity['href_label'] }}
                                </a>
                            </article>
                        @empty
                            <div class="px-6 py-10 text-center text-sm text-gray-500 dark:text-gray-400">
                                Belum ada aktivitas yang bisa ditampilkan.
                            </div>
                        @endforelse
                    </div>
                </section>

                <section class="rounded-3xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900">
                    <div
                        class="flex items-center justify-between gap-4 border-b border-gray-100 px-6 py-5 dark:border-gray-800">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.22em] text-sky-700 dark:text-sky-300">
                                Konten
                            </p>
                            <h2 class="mt-2 text-xl font-semibold text-gray-900 dark:text-white">Berita Terbaru</h2>
                        </div>
                        <a href="{{ route('kategori.index') }}"
                            class="text-sm font-medium text-sky-700 transition hover:text-sky-800 dark:text-sky-300 dark:hover:text-sky-200">
                            Lihat Semua
                        </a>
                    </div>

                    <div class="divide-y divide-gray-100 dark:divide-gray-800">
                        @forelse ($latestBeritas as $item)
                            <article
                                class="flex flex-col gap-4 px-6 py-5 md:flex-row md:items-center md:justify-between">
                                <div class="min-w-0">
                                    <div class="flex flex-wrap items-center gap-2 text-xs">
                                        <span
                                            class="inline-flex items-center rounded-full bg-sky-50 px-2.5 py-1 font-semibold text-sky-700 dark:bg-sky-500/10 dark:text-sky-300">
                                            {{ $item->category->name ?? 'Tanpa Kategori' }}
                                        </span>
                                        <span class="text-gray-400 dark:text-gray-500">
                                            {{ optional($item->created_at)->format('d M Y H:i') }}
                                        </span>
                                    </div>
                                    <h3
                                        class="mt-2 text-base font-semibold text-gray-900 dark:text-white line-clamp-1">
                                        {{ \Illuminate\Support\Str::limit($item->title, 100) }}
                                    </h3>
                                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400 line-clamp-1">
                                        {{ $item->slug }}
                                    </p>
                                </div>

                                @if ($item->category)
                                    <div class="flex shrink-0 gap-2">
                                        <a href="{{ route('berita.show', [$item->category->slug, $item->id]) }}"
                                            class="inline-flex items-center justify-center rounded-xl bg-amber-400 px-4 py-2.5 text-sm font-semibold text-slate-900 transition hover:bg-amber-300">
                                            Detail
                                        </a>
                                        <a href="{{ route('berita.edit', [$item->category->slug, $item->id]) }}"
                                            class="inline-flex items-center justify-center rounded-xl bg-sky-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-sky-700">
                                            Edit
                                        </a>
                                    </div>
                                @endif
                            </article>
                        @empty
                            <div class="px-6 py-10 text-center text-sm text-gray-500 dark:text-gray-400">
                                Belum ada berita yang bisa ditampilkan.
                            </div>
                        @endforelse
                    </div>
                </section>
            </div>

            <div class="space-y-6">
                <section class="rounded-3xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900">
                    <div class="border-b border-gray-100 px-6 py-5 dark:border-gray-800">
                        <p class="text-xs font-semibold uppercase tracking-[0.22em] text-sky-700 dark:text-sky-300">
                            Struktur
                        </p>
                        <h2 class="mt-2 text-xl font-semibold text-gray-900 dark:text-white">Kategori Terpadat</h2>
                    </div>

                    <div class="space-y-5 px-6 py-5">
                        @forelse ($topCategories as $category)
                            @php
                                $categoryPercentage =
                                    $totalBeritaCount > 0 ? ($category->berita_count / $totalBeritaCount) * 100 : 0;
                            @endphp
                            <div>
                                <div class="flex items-center justify-between gap-4">
                                    <div class="min-w-0">
                                        <h3 class="truncate text-sm font-semibold text-gray-900 dark:text-white">
                                            {{ $category->name }}
                                        </h3>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ $category->slug }}</p>
                                    </div>
                                    <span class="text-sm font-semibold text-gray-700 dark:text-gray-200">
                                        {{ number_format($categoryPercentage, 0) }}%
                                    </span>
                                </div>

                                <div class="mt-3 h-2 rounded-full bg-gray-100 dark:bg-gray-800">
                                    <div class="h-2 rounded-full bg-sky-600"
                                        style="width: {{ $categoryPercentage }}%">
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                Belum ada kategori dengan artikel.
                            </div>
                        @endforelse
                    </div>
                </section>

                <section class="rounded-3xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900">
                    <div
                        class="flex items-center justify-between gap-4 border-b border-gray-100 px-6 py-5 dark:border-gray-800">
                        <div>
                            <p
                                class="text-xs font-semibold uppercase tracking-[0.22em] text-sky-700 dark:text-sky-300">
                                Video
                            </p>
                            <h2 class="mt-2 text-xl font-semibold text-gray-900 dark:text-white">TikTok Terbaru</h2>
                        </div>
                        <a href="{{ route('tiktok.index') }}"
                            class="text-sm font-medium text-sky-700 transition hover:text-sky-800 dark:text-sky-300 dark:hover:text-sky-200">
                            Buka Modul
                        </a>
                    </div>

                    <div class="divide-y divide-gray-100 dark:divide-gray-800">
                        @forelse ($recentTiktoks as $tiktok)
                            <article class="flex items-center justify-between gap-4 px-6 py-5">
                                <div class="min-w-0">
                                    <h3 class="truncate text-base font-semibold text-gray-900 dark:text-white">
                                        {{ \Illuminate\Support\Str::limit($tiktok->title, 70) }}
                                    </h3>
                                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                        Ditambahkan {{ optional($tiktok->created_at)->format('d M Y H:i') }}
                                    </p>
                                </div>
                                <a href="{{ route('tiktok.index') }}"
                                    class="inline-flex shrink-0 items-center justify-center rounded-xl bg-gray-100 px-4 py-2.5 text-sm font-semibold text-gray-700 transition hover:bg-gray-200 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700">
                                    Lihat
                                </a>
                            </article>
                        @empty
                            <div class="px-6 py-10 text-center text-sm text-gray-500 dark:text-gray-400">
                                Belum ada data TikTok.
                            </div>
                        @endforelse
                    </div>
                </section>



                <section class="rounded-3xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900">
                    <div
                        class="flex items-center justify-between gap-4 border-b border-gray-100 px-6 py-5 dark:border-gray-800">
                        <div>
                            <p
                                class="text-xs font-semibold uppercase tracking-[0.22em] text-sky-700 dark:text-sky-300">
                                Market
                            </p>
                            <h2 class="mt-2 text-xl font-semibold text-gray-900 dark:text-white">Historical Data</h2>
                        </div>
                        <a href="{{ route('pivot.index') }}"
                            class="text-sm font-medium text-sky-700 transition hover:text-sky-800 dark:text-sky-300 dark:hover:text-sky-200">
                            Buka Modul
                        </a>
                    </div>

                    <div class="space-y-4 px-6 py-5">
                        @forelse ($latestPivots as $pivot)
                            <div class="rounded-2xl border border-gray-200 p-4 dark:border-gray-800">
                                <div class="flex items-center justify-between gap-3">
                                    <h3 class="text-sm font-semibold text-gray-900 dark:text-white">
                                        {{ $pivot->category }}</h3>
                                    <span class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ $pivot->tanggal ? \Carbon\Carbon::parse($pivot->tanggal)->format('d M Y') : '-' }}
                                    </span>
                                </div>
                                <div class="mt-3 grid grid-cols-2 gap-3 text-sm text-gray-600 dark:text-gray-300">
                                    <div>Open: {{ $pivot->open ?? '-' }}</div>
                                    <div>Close: {{ $pivot->close ?? '-' }}</div>
                                    <div>High: {{ $pivot->high ?? '-' }}</div>
                                    <div>Low: {{ $pivot->low ?? '-' }}</div>
                                </div>
                            </div>
                        @empty
                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                Belum ada historical data.
                            </div>
                        @endforelse
                    </div>
                </section>
            </div>
        </section>

        <section class="rounded-3xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900">
            <div
                class="flex items-center justify-between gap-4 border-b border-gray-100 px-6 py-5 dark:border-gray-800">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.22em] text-sky-700 dark:text-sky-300">
                        Agenda
                    </p>
                    <h2 class="mt-2 text-xl font-semibold text-gray-900 dark:text-white">Kalender Ekonomi</h2>
                </div>
                <a href="{{ route('calendar.index') }}"
                    class="text-sm font-medium text-sky-700 transition hover:text-sky-800 dark:text-sky-300 dark:hover:text-sky-200">
                    Buka Modul
                </a>
            </div>

            <div class="divide-y divide-gray-100 dark:divide-gray-800">
                @forelse ($upcomingCalendars as $calendar)
                    <article class="px-6 py-5">
                        <div class="flex flex-col gap-4 md:flex-row md:items-start md:justify-between">
                            <div class="min-w-0">
                                <div class="flex flex-wrap items-center gap-2 text-xs">
                                    <span
                                        class="inline-flex items-center rounded-full px-2.5 py-1 font-semibold {{ $impactClasses[$calendar->impact] ?? 'bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-300' }}">
                                        {{ $calendar->impact }}
                                    </span>
                                    <span class="text-gray-500 dark:text-gray-400">
                                        {{ optional($calendar->date)->format('d M Y') }}{{ $calendar->time ? ', ' . $calendar->time : '' }}
                                    </span>
                                </div>
                                <h3 class="mt-2 text-base font-semibold text-gray-900 dark:text-white">
                                    {{ $calendar->figures }}
                                </h3>
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                    {{ $calendar->country }} | Forecast: {{ $calendar->forecast ?: '-' }} |
                                    Actual:
                                    {{ $calendar->actual ?: '-' }}
                                </p>
                            </div>

                            <a href="{{ route('calendar.show', $calendar->id) }}"
                                class="inline-flex shrink-0 items-center justify-center rounded-xl bg-gray-100 px-4 py-2.5 text-sm font-semibold text-gray-700 transition hover:bg-gray-200 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700">
                                Detail
                            </a>
                        </div>
                    </article>
                @empty
                    <div class="px-6 py-10 text-center text-sm text-gray-500 dark:text-gray-400">
                        Belum ada data kalender ekonomi.
                    </div>
                @endforelse
            </div>
        </section>
    </div>
</x-app-layout>
