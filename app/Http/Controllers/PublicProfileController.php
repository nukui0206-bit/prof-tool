<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use App\Models\User;
use Illuminate\View\View;

class PublicProfileController extends Controller
{
    public function show(string $slug): View
    {
        $profile = Profile::where('slug', $slug)
            ->where('is_published', true)
            ->whereHas('user', fn ($q) => $q->where('status', User::STATUS_ACTIVE))
            ->with('user')
            ->firstOrFail();

        // Phase 9 で footprints テーブル + 24h 集約に置き換える。
        // それまでは閲覧ごとに単純 increment。
        $profile->increment('view_count');

        return view('public.profile', compact('profile'));
    }
}
