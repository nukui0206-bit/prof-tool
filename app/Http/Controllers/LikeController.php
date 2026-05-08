<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use App\Models\User;
use App\Services\Like\LikeToggler;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class LikeController extends Controller
{
    public function toggle(string $slug, LikeToggler $toggler): JsonResponse
    {
        $profile = Profile::where('slug', $slug)
            ->where('is_published', true)
            ->whereHas('user', fn ($q) => $q->where('status', User::STATUS_ACTIVE))
            ->firstOrFail();

        if ($profile->user_id === Auth::id()) {
            return response()->json(['error' => '自分のプロフィールにはいいねできません'], 422);
        }

        $result = $toggler->toggle(Auth::user(), $profile);

        return response()->json($result);
    }

    public function index(): View
    {
        $likes = Auth::user()->likes()
            ->with(['profile.user', 'profile.theme'])
            ->whereHas('profile', fn ($q) => $q->where('is_published', true))
            ->whereHas('profile.user', fn ($q) => $q->where('status', User::STATUS_ACTIVE))
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('mypage.likes.index', compact('likes'));
    }
}
