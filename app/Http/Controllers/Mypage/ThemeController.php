<?php

namespace App\Http\Controllers\Mypage;

use App\Http\Controllers\Controller;
use App\Models\Theme;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ThemeController extends Controller
{
    public function edit(): View
    {
        $profile = Auth::user()->profile;
        $themes = Theme::active()->get();
        return view('mypage.theme.edit', compact('profile', 'themes'));
    }

    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'theme_id' => ['nullable', 'integer', 'exists:themes,id'],
            'theme_color' => ['nullable', 'regex:/^#[0-9a-fA-F]{6}$/'],
        ], [
            'theme_color.regex' => 'メインカラーは #RRGGBB 形式（HEX 7文字）で指定してください。',
        ]);

        $profile = Auth::user()->profile;
        $profile->theme_id = $request->input('theme_id') ?: null;
        $profile->theme_color = $request->input('theme_color') ?: null;
        $profile->save();

        return redirect()->route('mypage.theme.edit')->with('status', 'theme-updated');
    }
}
