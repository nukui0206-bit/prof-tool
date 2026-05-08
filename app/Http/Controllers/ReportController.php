<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use App\Models\Report;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function create(Request $request): View
    {
        $request->validate([
            'target_type' => ['required', 'in:profile'],
            'target_id' => ['required', 'integer'],
        ]);

        $target = $this->resolveTarget($request->target_type, (int) $request->target_id);
        $targetLabel = $target instanceof Profile
            ? "{$target->nickname} さんのプロフィール（@{$target->slug}）"
            : '対象が見つかりませんでした';

        return view('public.report', [
            'targetType' => $request->target_type,
            'targetId' => (int) $request->target_id,
            'targetLabel' => $targetLabel,
            'targetExists' => (bool) $target,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'target_type' => ['required', 'in:profile'],
            'target_id' => ['required', 'integer'],
            'reason' => ['required', Rule::in(array_keys(Report::REASONS))],
            'body' => ['nullable', 'string', 'max:1000'],
        ], [
            'reason.required' => '通報理由を選択してください。',
            'body.max' => '内容は 1000 文字以内で入力してください。',
        ]);

        // 同じユーザーが同じ対象を 24h 以内に複数通報するのを抑止（軽いスパム対策）
        if (Auth::check()) {
            $duplicate = Report::where('reporter_user_id', Auth::id())
                ->where('target_type', $validated['target_type'])
                ->where('target_id', $validated['target_id'])
                ->where('created_at', '>=', now()->subDay())
                ->exists();

            if ($duplicate) {
                return redirect('/')->with('status', 'report-duplicate');
            }
        }

        Report::create([
            'reporter_user_id' => Auth::id(),
            'target_type' => $validated['target_type'],
            'target_id' => $validated['target_id'],
            'reason' => $validated['reason'],
            'body' => $validated['body'] ?? null,
            'status' => Report::STATUS_OPEN,
        ]);

        return redirect('/')->with('status', 'report-submitted');
    }

    private function resolveTarget(string $type, int $id): ?Profile
    {
        return match ($type) {
            'profile' => Profile::find($id),
            default => null,
        };
    }
}
