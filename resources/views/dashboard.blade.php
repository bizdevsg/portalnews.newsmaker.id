@section('namePage', 'Dashboard')

<x-app-layout>
    @php
        $user = auth()->user();
        $userCanManageUsers = $user?->role === 'Superadmin';
        $todayLabel = ucfirst(\Carbon\Carbon::now()->locale('id')->translatedFormat('l, d F Y'));

        $categoryCount = (int) ($widget['category'] ?? 0);
        $beritaCount = (int) ($widget['berita'] ?? 0);
        $newsmakerArticleCount = (int) ($widget['newsmaker_article'] ?? 0);
        $calendarCategoryCount = (int) ($widget['calendar_category'] ?? 0);
        $pivotCount = (int) ($widget['pivot'] ?? 0);
        $tiktokCount = (int) ($widget['tiktok'] ?? 0);
        $popupBannerCount = (int) ($widget['popup_banner'] ?? 0);
        $iklanCount = (int) ($widget['iklan'] ?? 0);
        $userTotal = (int) ($widget['user_total'] ?? 0);
        $adminCount = (int) ($widget['admin'] ?? 0);
        $superadminCount = (int) ($widget['superadmin'] ?? 0);

        $menuCards = [
            [
                'section' => '5 PT',
                'title' => 'Berita',
                'value' => number_format($beritaCount),
                'description' => 'Total berita pada modul 5 PT.',
                'icon' => 'fa-solid fa-newspaper',
                'iconClass' => 'bg-blue-50 text-blue-600 dark:bg-blue-500/10 dark:text-blue-300',
                'href' => route('kategori.index'),
            ],
            [
                'section' => 'Newsmaker 23',
                'title' => 'Berita',
                'value' => number_format($newsmakerArticleCount),
                'description' => 'Total artikel pada modul Newsmaker 23.',
                'icon' => 'fa-solid fa-layer-group',
                'iconClass' => 'bg-amber-50 text-amber-600 dark:bg-amber-500/10 dark:text-amber-300',
                'href' => route('newsmaker23.index'),
            ],
            [
                'section' => 'Newsmaker 23',
                'title' => 'Kalender Ekonomi',
                'value' => number_format($calendarCategoryCount),
                'description' => 'Total figures kalender ekonomi.',
                'icon' => 'fa-solid fa-calendar-days',
                'iconClass' => 'bg-emerald-50 text-emerald-600 dark:bg-emerald-500/10 dark:text-emerald-300',
                'href' => route('calendar.index'),
            ],
            [
                'section' => 'Newsmaker 23',
                'title' => 'Historical Data',
                'value' => number_format($pivotCount),
                'description' => 'Total data historical yang tersimpan.',
                'icon' => 'fa-solid fa-chart-line',
                'iconClass' => 'bg-sky-50 text-sky-600 dark:bg-sky-500/10 dark:text-sky-300',
                'href' => route('pivot.index'),
            ],
            [
                'section' => 'Newsmaker 23',
                'title' => 'TikTok',
                'value' => number_format($tiktokCount),
                'description' => 'Total konten TikTok yang tersedia.',
                'icon' => 'fa-brands fa-tiktok',
                'iconClass' => 'bg-rose-50 text-rose-600 dark:bg-rose-500/10 dark:text-rose-300',
                'href' => route('tiktok.index'),
            ],
            [
                'section' => 'Newsmaker 23',
                'title' => 'Pop Up Banner',
                'value' => number_format($popupBannerCount),
                'description' => 'Kelola banner popup, campaign, CTA, dan modal HTML kustom.',
                'icon' => 'fa-solid fa-rectangle-ad',
                'iconClass' => 'bg-fuchsia-50 text-fuchsia-600 dark:bg-fuchsia-500/10 dark:text-fuchsia-300',
                'href' => route('popup-banner.index'),
            ],
            [
                'section' => 'Newsmaker 23',
                'title' => 'Iklan',
                'value' => number_format($iklanCount),
                'description' => 'Kelola iklan dengan input mirip Popup Banner (termasuk HTML kustom).',
                'icon' => 'fa-solid fa-rectangle-ad',
                'iconClass' => 'bg-emerald-50 text-emerald-600 dark:bg-emerald-500/10 dark:text-emerald-300',
                'href' => route('iklan.index'),
            ],
        ];

        if ($userCanManageUsers) {
            $menuCards[] = [
                'section' => 'Manajemen',
                'title' => 'Manajemen Pengguna',
                'value' => number_format($userTotal),
                'description' => 'Total akun yang bisa dikelola.',
                'icon' => 'fa-solid fa-users',
                'iconClass' => 'bg-violet-50 text-violet-600 dark:bg-violet-500/10 dark:text-violet-300',
                'href' => route('user.index'),
            ];
        }

        $summaryItems = [
            [
                'label' => 'Kategori 5 PT',
                'value' => number_format($categoryCount),
                'description' => 'Struktur kategori yang dipakai di modul 5 PT.',
            ],
            [
                'label' => 'Total Pengguna',
                'value' => number_format($userTotal),
                'description' =>
                    number_format($adminCount) . ' admin dan ' . number_format($superadminCount) . ' superadmin.',
            ],
            [
                'label' => 'Modul Sidebar',
                'value' => number_format(count($menuCards) + 1),
                'description' => 'Termasuk Beranda pada menu Dashboard.',
            ],
        ];
    @endphp

    <div class="mx-auto w-full max-w-9xl px-4 py-6 sm:px-6 lg:px-8 lg:py-8">
        <div class="mb-6 flex flex-col gap-2 md:flex-row md:items-end md:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-slate-900 dark:text-white sm:text-3xl">Dashboard</h1>
                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                    Ringkasan menu yang tersedia di sidebar.
                </p>
            </div>
            <div class="text-sm text-slate-500 dark:text-slate-400">
                {{ $todayLabel }}
            </div>
        </div>

        <section class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
            @foreach ($menuCards as $card)
                <a href="{{ $card['href'] }}"
                    class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:border-slate-300 dark:border-slate-800 dark:bg-slate-900 dark:hover:border-slate-700">
                    <div class="flex items-start justify-between gap-4">
                        <div class="min-w-0">
                            <div
                                class="text-[11px] font-semibold uppercase tracking-[0.18em] text-slate-400 dark:text-slate-500">
                                {{ $card['section'] }}
                            </div>
                            <div class="mt-1 text-base font-semibold text-slate-900 dark:text-white">
                                {{ $card['title'] }}
                            </div>
                            <div class="mt-3 text-3xl font-bold text-slate-900 dark:text-white">
                                {{ $card['value'] }}
                            </div>
                        </div>
                        <span class="flex h-11 w-11 items-center justify-center rounded-xl {{ $card['iconClass'] }}">
                            <i class="{{ $card['icon'] }}"></i>
                        </span>
                    </div>
                    <p class="mt-4 text-sm text-slate-500 dark:text-slate-400">
                        {{ $card['description'] }}
                    </p>
                </a>
            @endforeach
        </section>

        <section class="mt-6 grid gap-6 xl:grid-cols-[minmax(0,1.2fr)_340px]">
            <div
                class="h-fit rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <div class="border-b border-slate-200 px-6 py-4 dark:border-slate-800">
                    <h2 class="text-lg font-semibold text-slate-900 dark:text-white">Akses Cepat</h2>
                </div>

                <div class="divide-y divide-slate-200 dark:divide-slate-800">
                    @foreach ($menuCards as $card)
                        <a href="{{ $card['href'] }}"
                            class="flex flex-col gap-3 px-6 py-4 transition hover:bg-slate-50 dark:hover:bg-slate-800/50 sm:flex-row sm:items-center sm:justify-between">
                            <div class="min-w-0">
                                <div
                                    class="text-[11px] font-semibold uppercase tracking-[0.18em] text-slate-400 dark:text-slate-500">
                                    {{ $card['section'] }}
                                </div>
                                <div class="mt-1 text-sm font-semibold text-slate-900 dark:text-white">
                                    {{ $card['title'] }}
                                </div>
                                <div class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                                    {{ $card['description'] }}
                                </div>
                            </div>
                            <span
                                class="inline-flex items-center text-sm font-medium text-slate-600 dark:text-slate-300">
                                Buka
                            </span>
                        </a>
                    @endforeach
                </div>
            </div>

            <div class="space-y-6">
                <div
                    class="rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">
                    <div class="border-b border-slate-200 px-6 py-4 dark:border-slate-800">
                        <h2 class="text-lg font-semibold text-slate-900 dark:text-white">Ringkasan Sistem</h2>
                    </div>

                    <div class="space-y-4 px-6 py-5">
                        @foreach ($summaryItems as $item)
                            <div class="rounded-xl bg-slate-50 px-4 py-4 dark:bg-slate-800/60">
                                <div class="text-sm font-medium text-slate-500 dark:text-slate-400">
                                    {{ $item['label'] }}</div>
                                <div class="mt-2 text-2xl font-bold text-slate-900 dark:text-white">
                                    {{ $item['value'] }}</div>
                                <div class="mt-1 text-sm text-slate-500 dark:text-slate-400">{{ $item['description'] }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div
                    class="rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">
                    <div class="border-b border-slate-200 px-6 py-4 dark:border-slate-800">
                        <h2 class="text-lg font-semibold text-slate-900 dark:text-white">Role Pengguna</h2>
                    </div>

                    <div class="space-y-3 px-6 py-5">
                        <div
                            class="flex items-center justify-between rounded-xl bg-slate-50 px-4 py-3 dark:bg-slate-800/60">
                            <span class="text-sm font-medium text-slate-600 dark:text-slate-300">Admin</span>
                            <span
                                class="text-sm font-semibold text-slate-900 dark:text-white">{{ number_format($adminCount) }}</span>
                        </div>
                        <div
                            class="flex items-center justify-between rounded-xl bg-slate-50 px-4 py-3 dark:bg-slate-800/60">
                            <span class="text-sm font-medium text-slate-600 dark:text-slate-300">Superadmin</span>
                            <span
                                class="text-sm font-semibold text-slate-900 dark:text-white">{{ number_format($superadminCount) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</x-app-layout>
