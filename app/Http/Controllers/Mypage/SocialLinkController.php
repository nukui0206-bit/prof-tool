<?php

namespace App\Http\Controllers\Mypage;

use App\Http\Controllers\Controller;
use App\Models\SocialLink;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class SocialLinkController extends Controller
{
    public function index(): View
    {
        $profile = Auth::user()->profile;
        $links = $profile->socialLinks;
        return view('mypage.links.index', compact('profile', 'links'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate($this->rules(), $this->messages());

        $profile = Auth::user()->profile;
        $maxSort = (int) $profile->socialLinks()->max('sort_order');

        $profile->socialLinks()->create([
            'platform' => $request->platform,
            'url' => $request->url,
            'label' => $request->label ?: null,
            'sort_order' => $maxSort + 1,
        ]);

        return redirect()->route('mypage.links.index')
            ->with('status', 'link-added');
    }

    public function update(Request $request, SocialLink $link): RedirectResponse
    {
        $this->ensureOwner($link);

        $request->validate($this->rules(), $this->messages());

        $link->update([
            'platform' => $request->platform,
            'url' => $request->url,
            'label' => $request->label ?: null,
        ]);

        return redirect()->route('mypage.links.index')
            ->with('status', 'link-updated');
    }

    public function destroy(SocialLink $link): RedirectResponse
    {
        $this->ensureOwner($link);
        $link->delete();

        return redirect()->route('mypage.links.index')
            ->with('status', 'link-deleted');
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
                $profile->socialLinks()
                    ->where('id', (int) $id)
                    ->update(['sort_order' => $i + 1]);
            }
        });

        return response()->json(['ok' => true]);
    }

    private function rules(): array
    {
        return [
            'platform' => ['required', Rule::in(array_keys(SocialLink::PLATFORMS))],
            'url' => ['required', 'url', 'max:500'],
            'label' => ['nullable', 'string', 'max:60'],
        ];
    }

    private function messages(): array
    {
        return [
            'platform.required' => 'プラットフォームを選択してください。',
            'platform.in' => 'プラットフォームの値が不正です。',
            'url.required' => 'URL を入力してください。',
            'url.url' => '有効な URL を入力してください（https://… 形式）。',
            'url.max' => 'URL は 500 文字以内で入力してください。',
            'label.max' => 'ラベルは 60 文字以内で入力してください。',
        ];
    }

    private function ensureOwner(SocialLink $link): void
    {
        abort_if($link->profile_id !== Auth::user()->profile->id, 403);
    }
}
