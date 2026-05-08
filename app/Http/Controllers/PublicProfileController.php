<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use App\Models\User;
use App\Services\Footprint\FootprintRecorder;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class PublicProfileController extends Controller
{
    public function show(string $slug, FootprintRecorder $footprintRecorder): View
    {
        $profile = Profile::where('slug', $slug)
            ->where('is_published', true)
            ->whereHas('user', fn ($q) => $q->where('status', User::STATUS_ACTIVE))
            ->with([
                'user',
                'theme',
                'answers' => fn ($q) => $q->with('question')
                    ->whereHas('question', fn ($qq) => $qq->where('is_active', true)),
                'favorites',
                'socialLinks',
            ])
            ->firstOrFail();

        // ページビュー（PV）は閲覧ごとに increment
        $profile->increment('view_count');

        // 足あと（UU 寄り）は 24h 集約。ログイン者かつ自分以外のみ。
        if (Auth::check()) {
            $footprintRecorder->record(Auth::user(), $profile);
        }

        $isLiked = $profile->isLikedBy(Auth::user());

        return view('public.profile', compact('profile', 'isLiked'));
    }
}
