<?php

namespace App\Http\Controllers\Mypage;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class FootprintController extends Controller
{
    public function index(): View
    {
        $profile = Auth::user()->profile;

        $footprints = $profile->footprints()
            ->with(['visitor.profile'])
            ->whereHas('visitor', fn ($q) => $q->where('status', User::STATUS_ACTIVE))
            ->orderByDesc('visited_at')
            ->paginate(20);

        return view('mypage.footprints.index', compact('footprints', 'profile'));
    }
}
