<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Profile;
use App\Models\Report;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function index(Request $request): View
    {
        $status = $request->input('status', '');

        $reports = Report::query()
            ->with('reporter.profile')
            ->when(in_array($status, array_keys(Report::STATUSES), true),
                fn ($query) => $query->where('status', $status))
            ->orderByDesc('created_at')
            ->paginate(30)
            ->withQueryString();

        return view('admin.reports.index', compact('reports', 'status'));
    }

    public function show(Report $report): View
    {
        $report->load('reporter.profile');
        $target = $this->resolveTarget($report);

        return view('admin.reports.show', compact('report', 'target'));
    }

    public function updateStatus(Request $request, Report $report): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', Rule::in(array_keys(Report::STATUSES))],
        ]);

        $report->update(['status' => $validated['status']]);

        return redirect()->route('admin.reports.show', $report)
            ->with('status', "ステータスを「{$report->statusLabel()}」に変更しました。");
    }

    private function resolveTarget(Report $report): ?Profile
    {
        return match ($report->target_type) {
            'profile' => Profile::with('user')->find($report->target_id),
            default => null,
        };
    }
}
