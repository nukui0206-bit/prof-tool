<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        $q = trim((string) $request->input('q', ''));
        $status = $request->input('status', '');

        $users = User::query()
            ->with('profile')
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($qb) use ($q) {
                    $qb->where('name', 'like', "%{$q}%")
                        ->orWhere('email', 'like', "%{$q}%");
                });
            })
            ->when(in_array($status, [User::STATUS_ACTIVE, User::STATUS_SUSPENDED], true),
                fn ($query) => $query->where('status', $status))
            ->orderByDesc('created_at')
            ->paginate(30)
            ->withQueryString();

        return view('admin.users.index', compact('users', 'q', 'status'));
    }

    public function show(User $user): View
    {
        $user->load('profile');
        return view('admin.users.show', compact('user'));
    }

    public function suspend(User $user): RedirectResponse
    {
        if ($user->isAdmin()) {
            return redirect()->route('admin.users.show', $user)
                ->with('status', '管理者は停止できません。');
        }
        $user->update(['status' => User::STATUS_SUSPENDED]);
        return redirect()->route('admin.users.show', $user)
            ->with('status', "{$user->name} を停止しました。");
    }

    public function activate(User $user): RedirectResponse
    {
        $user->update(['status' => User::STATUS_ACTIVE]);
        return redirect()->route('admin.users.show', $user)
            ->with('status', "{$user->name} を有効化しました。");
    }
}
