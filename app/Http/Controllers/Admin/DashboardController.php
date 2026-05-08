<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Profile;
use App\Models\Report;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $weekAgo = Carbon::now()->subDays(7);

        $stats = [
            'users_total' => User::count(),
            'users_active' => User::where('status', User::STATUS_ACTIVE)->count(),
            'users_suspended' => User::where('status', User::STATUS_SUSPENDED)->count(),
            'users_new_7d' => User::where('created_at', '>=', $weekAgo)->count(),
            'profiles_published' => Profile::where('is_published', true)->count(),
            'reports_open' => Report::where('status', Report::STATUS_OPEN)->count(),
            'reports_reviewing' => Report::where('status', Report::STATUS_REVIEWING)->count(),
        ];

        $latestReports = Report::with('reporter')
            ->where('status', Report::STATUS_OPEN)
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'latestReports'));
    }
}
