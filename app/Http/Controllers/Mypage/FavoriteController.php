<?php

namespace App\Http\Controllers\Mypage;

use App\Http\Controllers\Controller;
use App\Models\Favorite;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class FavoriteController extends Controller
{
    public function index(): View
    {
        $profile = Auth::user()->profile;
        $favorites = $profile->favorites;
        return view('mypage.favorites.index', compact('profile', 'favorites'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'label' => ['required', 'string', 'max:60'],
        ], [
            'label.required' => '好きなものを入力してください。',
            'label.max' => '60 文字以内で入力してください。',
        ]);

        $profile = Auth::user()->profile;
        $maxSort = (int) $profile->favorites()->max('sort_order');

        $profile->favorites()->create([
            'label' => $request->label,
            'sort_order' => $maxSort + 1,
        ]);

        return redirect()->route('mypage.favorites.index')
            ->with('status', 'favorite-added');
    }

    public function update(Request $request, Favorite $favorite): RedirectResponse
    {
        $this->ensureOwner($favorite);

        $request->validate([
            'label' => ['required', 'string', 'max:60'],
        ]);

        $favorite->update(['label' => $request->label]);

        return redirect()->route('mypage.favorites.index')
            ->with('status', 'favorite-updated');
    }

    public function destroy(Favorite $favorite): RedirectResponse
    {
        $this->ensureOwner($favorite);
        $favorite->delete();

        return redirect()->route('mypage.favorites.index')
            ->with('status', 'favorite-deleted');
    }

    public function reorder(Request $request): JsonResponse
    {
        $request->validate([
            'ids' => ['required', 'array'],
            'ids.*' => ['integer'],
        ]);

        $profile = Auth::user()->profile;

        DB::transaction(function () use ($request, $profile) {
            foreach ($request->input('ids', []) as $i => $id) {
                $profile->favorites()
                    ->where('id', (int) $id)
                    ->update(['sort_order' => $i + 1]);
            }
        });

        return response()->json(['ok' => true]);
    }

    private function ensureOwner(Favorite $favorite): void
    {
        abort_if($favorite->profile_id !== Auth::user()->profile->id, 403);
    }
}
