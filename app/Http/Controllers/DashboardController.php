<?php

namespace App\Http\Controllers;

use App\Models\Berita;
use App\Models\Category;
use App\Models\EconomicCalendar;
use App\Models\Pivot;
use App\Models\Tiktok;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;

class DashboardController extends Controller
{
    public function index()
    {
        $superadminCount = User::where('role', 'Superadmin')->count();
        $adminCount = User::where('role', 'Admin')->count();
        $beritaCount = Berita::count();
        $categoryCount = Category::count();
        $tiktokTableExists = Schema::hasTable('tiktoks');
        $calendarTableExists = Schema::hasTable('economic_calendars');
        $pivotTableExists = Schema::hasTable('pivots');

        $tiktokCount = $tiktokTableExists ? Tiktok::count() : 0;
        $calendarCount = $calendarTableExists ? EconomicCalendar::count() : 0;
        $pivotCount = $pivotTableExists ? Pivot::count() : 0;
        $beritaTodayCount = Berita::whereDate('created_at', today())->count();
        $beritaThisWeekCount = Berita::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count();
        $emptyCategoryCount = Category::doesntHave('berita')->count();
        $calendarNextSevenDaysCount = $calendarTableExists
            ? EconomicCalendar::whereBetween('date', [today(), today()->copy()->addDays(6)])->count()
            : 0;

        $latestBeritas = Berita::with('category')
            ->latest()
            ->take(6)
            ->get();

        $topCategories = Category::withCount('berita')
            ->orderByDesc('berita_count')
            ->take(5)
            ->get();

        $upcomingCalendars = collect();
        if ($calendarTableExists) {
            $upcomingCalendars = EconomicCalendar::query()
                ->whereDate('date', '>=', today())
                ->orderBy('date')
                ->orderBy('time')
                ->take(5)
                ->get();

            if ($upcomingCalendars->isEmpty()) {
                $upcomingCalendars = EconomicCalendar::query()
                    ->latest('date')
                    ->take(5)
                    ->get();
            }
        }

        $latestPivots = $pivotTableExists
            ? Pivot::query()
                ->orderByDesc('tanggal')
                ->orderByDesc('id')
                ->take(5)
                ->get()
            : new Collection;

        $recentTiktoks = $tiktokTableExists
            ? Tiktok::query()
                ->latest()
                ->take(4)
                ->get()
            : new Collection;

        $firstCategory = Category::query()
            ->orderBy('name')
            ->first();

        $beritaUpdatedAt = Berita::query()->latest('updated_at')->first()?->updated_at;
        $calendarUpdatedAt = $calendarTableExists
            ? EconomicCalendar::query()->latest('updated_at')->first()?->updated_at
            : null;
        $pivotUpdatedAt = $pivotTableExists
            ? Pivot::query()->latest('updated_at')->first()?->updated_at
            : null;
        $tiktokUpdatedAt = $tiktokTableExists
            ? Tiktok::query()->latest('updated_at')->first()?->updated_at
            : null;

        $latestCalendarUpdates = $calendarTableExists
            ? EconomicCalendar::query()
                ->latest('updated_at')
                ->take(2)
                ->get()
            : new Collection;

        $recentActivities = collect()
            ->merge($latestBeritas->take(4)->map(function ($item) {
                return [
                    'type' => 'Berita',
                    'title' => $item->title,
                    'subtitle' => $item->category->name ?? 'Tanpa kategori',
                    'time' => $item->updated_at,
                    'href' => $item->category
                        ? route('berita.edit', [$item->category->slug, $item->id])
                        : route('kategori.index'),
                    'href_label' => $item->category ? 'Edit' : 'Buka',
                    'tone' => 'sky',
                ];
            }))
            ->merge($latestCalendarUpdates->map(function ($item) {
                return [
                    'type' => 'Kalender',
                    'title' => $item->figures,
                    'subtitle' => trim(($item->country ?: '-').' | '.($item->impact ?: '-')),
                    'time' => $item->updated_at,
                    'href' => route('calendar.show', $item->id),
                    'href_label' => 'Detail',
                    'tone' => 'rose',
                ];
            }))
            ->merge($latestPivots->take(2)->map(function ($item) {
                return [
                    'type' => 'Historical',
                    'title' => $item->category,
                    'subtitle' => 'Open '.($item->open ?? '-').' | Close '.($item->close ?? '-'),
                    'time' => $item->updated_at,
                    'href' => route('pivot.index', ['category' => $item->category]),
                    'href_label' => 'Buka',
                    'tone' => 'emerald',
                ];
            }))
            ->merge($recentTiktoks->take(2)->map(function ($item) {
                return [
                    'type' => 'TikTok',
                    'title' => $item->title,
                    'subtitle' => 'Konten video',
                    'time' => $item->updated_at,
                    'href' => route('tiktok.index'),
                    'href_label' => 'Lihat',
                    'tone' => 'fuchsia',
                ];
            }))
            ->sortByDesc(fn ($item) => optional($item['time'])->getTimestamp() ?? 0)
            ->take(5)
            ->values();

        $latestUpdate = collect([
            $beritaUpdatedAt,
            $calendarUpdatedAt,
            $pivotUpdatedAt,
            $tiktokUpdatedAt,
        ])
            ->filter()
            ->sortByDesc(fn ($item) => optional($item)->getTimestamp() ?? 0)
            ->first();

        $summary = [
            'berita_today' => $beritaTodayCount,
            'berita_this_week' => $beritaThisWeekCount,
            'empty_categories' => $emptyCategoryCount,
            'calendar_next_seven_days' => $calendarNextSevenDaysCount,
            'latest_update' => $latestUpdate,
        ];

        $moduleUpdates = [
            'berita' => $beritaUpdatedAt,
            'calendar' => $calendarUpdatedAt,
            'pivot' => $pivotUpdatedAt,
            'tiktok' => $tiktokUpdatedAt,
        ];

        $widget = [
            'superadmin' => $superadminCount,
            'admin' => $adminCount,
            'berita' => $beritaCount,
            'category' => $categoryCount,
            'tiktok' => $tiktokCount,
            'calendar' => $calendarCount,
            'pivot' => $pivotCount,
        ];

        return view('dashboard', compact(
            'widget',
            'summary',
            'moduleUpdates',
            'recentActivities',
            'latestBeritas',
            'topCategories',
            'upcomingCalendars',
            'latestPivots',
            'recentTiktoks',
            'firstCategory'
        ));
    }

    /**
     * Displays the analytics screen
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function analytics()
    {
        return view('pages/dashboard/analytics');
    }

    /**
     * Displays the fintech screen
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function fintech()
    {
        return view('pages/dashboard/fintech');
    }
}
