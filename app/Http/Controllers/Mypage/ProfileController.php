<?php

namespace App\Http\Controllers\Mypage;

use App\Http\Controllers\Controller;
use App\Services\Slug\SlugGenerator;
use App\Services\Storage\AvatarStorageInterface;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(): View
    {
        $profile = Auth::user()->profile;
        return view('mypage.profile.edit', compact('profile'));
    }

    public function update(
        Request $request,
        AvatarStorageInterface $avatarStorage,
        SlugGenerator $slugGenerator,
    ): RedirectResponse {
        $profile = Auth::user()->profile;

        $validated = $request->validate([
            'nickname' => ['required', 'string', 'max:40'],
            'slug' => [
                'required',
                'string',
                'min:3',
                'max:30',
                'regex:/^[a-zA-Z0-9_-]+$/',
                Rule::unique('profiles', 'slug')->ignore($profile->id),
                function ($attribute, $value, $fail) use ($slugGenerator) {
                    if ($slugGenerator->isReserved($value)) {
                        $fail('この URL は予約語のため使用できません。別の文字列を入力してください。');
                    }
                },
            ],
            'bio' => ['nullable', 'string', 'max:500'],
            'is_published' => ['nullable', 'boolean'],
            'avatar' => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp', 'max:2048'],
            'remove_avatar' => ['nullable', 'boolean'],
        ], [
            'slug.required' => '公開 URL を入力してください。',
            'slug.min' => '公開 URL は 3 文字以上で入力してください。',
            'slug.max' => '公開 URL は 30 文字以内で入力してください。',
            'slug.regex' => '公開 URL は半角英数 / ハイフン / アンダースコアのみ使えます。',
            'slug.unique' => 'この URL は他のユーザーが使用しています。',
            'avatar.image' => '画像ファイル（JPEG / PNG / WebP）を指定してください。',
            'avatar.mimes' => 'JPEG / PNG / WebP のいずれかの画像形式を指定してください。',
            'avatar.max' => '画像サイズは 2MB 以下にしてください。',
            'bio.max' => '自己紹介は 500 文字以内で入力してください。',
        ]);

        $profile->nickname = $validated['nickname'];
        $profile->slug = strtolower($validated['slug']);
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
