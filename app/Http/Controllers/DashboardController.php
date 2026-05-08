<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();
        $profile = $user->profile;

        $weekAgo = Carbon::now()->subDays(7);

        $stats = [
            'view_count' => (int) $profile->view_count,
            'like_count' => (int) $profile->like_count,
            'likes_last_7d' => $profile->likes()
                ->where('created_at', '>=', $weekAgo)->count(),
            'footprints_last_7d' => $profile->footprints()
                ->where('visited_at', '>=', $weekAgo)->count(),
            'tags_count' => $profile->favorites()->count(),
            'links_count' => $profile->socialLinks()->count(),
            'answers_count' => $profile->answers()->count(),
            'questions_total' => Question::active()->count(),
        ];

        // プロフ充実度（5 項目）
        $checks = [
            'avatar' => (bool) $profile->avatar_path,
            'bio' => trim((string) $profile->bio) !== '',
            'tags' => $stats['tags_count'] > 0,
            'links' => $stats['links_count'] > 0,
            'answers' => $stats['answers_count'] > 0,
        ];
        $completed = count(array_filter($checks));
        $completionRate = (int) round($completed / count($checks) * 100);

        // 最新の足あと 3 件
        $recentFootprints = $profile->footprints()
            ->with(['visitor.profile'])
            ->whereHas('visitor', fn ($q) => $q->where('status', User::STATUS_ACTIVE))
            ->orderByDesc('visited_at')
            ->limit(3)
            ->get();

        return view('dashboard', compact(
            'profile', 'stats', 'checks', 'completionRate', 'recentFootprints',
        ));
    }
}
