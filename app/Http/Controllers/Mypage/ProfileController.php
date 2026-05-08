<?php

namespace App\Http\Controllers\Mypage;

use App\Http\Controllers\Controller;
use App\Services\Storage\AvatarStorageInterface;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(): View
    {
        $profile = Auth::user()->profile;
        return view('mypage.profile.edit', compact('profile'));
    }

    public function update(Request $request, AvatarStorageInterface $avatarStorage): RedirectResponse
    {
        $validated = $request->validate([
            'nickname' => ['required', 'string', 'max:40'],
            'bio' => ['nullable', 'string', 'max:500'],
            'is_published' => ['nullable', 'boolean'],
            'avatar' => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp', 'max:2048'],
            'remove_avatar' => ['nullable', 'boolean'],
        ], [
            'avatar.image' => '画像ファイル（JPEG / PNG / WebP）を指定してください。',
            'avatar.mimes' => 'JPEG / PNG / WebP のいずれかの画像形式を指定してください。',
            'avatar.max' => '画像サイズは 2MB 以下にしてください。',
            'bio.max' => '自己紹介は 500 文字以内で入力してください。',
        ]);

        $profile = Auth::user()->profile;

        $profile->nickname = $validated['nickname'];
        $profile->bio = $validated['bio'] ?? null;
        $profile->is_published = $request->boolean('is_published');

        if ($request->boolean('remove_avatar') && $profile->avatar_path) {
            $avatarStorage->delete($profile->avatar_path);
            $profile->avatar_path = null;
        }

        if ($request->hasFile('avatar')) {
            if ($profile->avatar_path) {
                $avatarStorage->delete($profile->avatar_path);
            }
            $profile->avatar_path = $avatarStorage->store($request->file('avatar'), $profile->id);
        }

        $profile->save();

        return redirect()->route('mypage.profile.edit')->with('status', 'profile-updated');
    }
}
