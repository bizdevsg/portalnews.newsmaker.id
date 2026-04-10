<?php

namespace App\Http\Controllers;

use App\Models\Berita;
use App\Models\Category;
use App\Models\EconomicCalendarCategory;
use App\Models\NewsmakerArticle;
use App\Models\Pivot;
use App\Models\Iklan;
use App\Models\PopupBanner;
use App\Models\Tiktok;
use App\Models\User;
use Illuminate\Support\Facades\Schema;

class DashboardController extends Controller
{
    public function index()
    {
        $superadminCount = User::where('role', 'Superadmin')->count();
        $adminCount = User::where('role', 'Admin')->count();
        $berita = Berita::count();
        $category = Category::count();
        $newsmakerArticle = NewsmakerArticle::count();
        $calendarCategory = EconomicCalendarCategory::count();
        $pivot = Pivot::count();
        $tiktok = Tiktok::count();
        $popupBanner = Schema::hasTable('popup_banners') ? PopupBanner::count() : 0;
        $iklan = Schema::hasTable('iklans') ? Iklan::count() : 0;
        $userTotal = User::count();

        $widget = [
            'superadmin' => $superadminCount,
            'admin' => $adminCount,
            'berita' => $berita,
            'category' => $category,
            'newsmaker_article' => $newsmakerArticle,
            'calendar_category' => $calendarCategory,
            'pivot' => $pivot,
            'tiktok' => $tiktok,
            'popup_banner' => $popupBanner,
            'iklan' => $iklan,
            'user_total' => $userTotal,
        ];

        return view('dashboard', compact('widget'));
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
