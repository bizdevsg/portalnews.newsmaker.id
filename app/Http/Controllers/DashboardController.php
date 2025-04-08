<?php

namespace App\Http\Controllers;

use App\Models\Berita;
use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $superadminCount = User::where('role', 'Superadmin')->count();
        $adminCount = User::where('role', 'Admin')->count();
        $Berita = Berita::count();
        $category = Category::count();

        $widget = [
            'superadmin' => $superadminCount,
            'admin' => $adminCount,
            'berita' => $Berita,
            'category' => $category,
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
