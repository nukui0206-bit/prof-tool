<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <h1 class="h4 fw-bold mb-0">ユーザー詳細</h1>
            <a href="{{ route('admin.users.index') }}" class="small">← 一覧に戻る</a>
        </div>
    </x-slot>

    @if (session('status'))
        <div class="alert alert-info small">{{ session('status') }}</div>
    @endif

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <dl class="row mb-0 small">
                <dt class="col-sm-3 text-muted">ID</dt>
                <dd class="col-sm-9">{{ $user->id }}</dd>

                <dt class="col-sm-3 text-muted">名前</dt>
                <dd class="col-sm-9">{{ $user->name }}</dd>

                <dt class="col-sm-3 text-muted">メール</dt>
                <dd class="col-sm-9">{{ $user->email }}</dd>

                <dt class="col-sm-3 text-muted">生年月日</dt>
                <dd class="col-sm-9">{{ optional($user->birthdate)->format('Y/m/d') ?? '—' }}</dd>

                <dt class="col-sm-3 text-muted">ロール</dt>
                <dd class="col-sm-9">
                    @if ($user->isAdmin())
                        <span class="badge text-bg-dark">admin</span>
                    @else
                        <span class="badge text-bg-light">user</span>
                    @endif
                </dd>

                <dt class="col-sm-3 text-muted">状態</dt>
                <dd class="col-sm-9">
                    @if ($user->isActive())
                        <span class="badge text-bg-success">アクティブ</span>
                    @else
                        <span class="badge text-bg-danger">停止</span>
                    @endif
                </dd>

                <dt class="col-sm-3 text-muted">登録日</dt>
                <dd class="col-sm-9">{{ $user->created_at->format('Y/m/d H:i') }}</dd>

                <dt class="col-sm-3 text-muted">公開プロフ</dt>
                <dd class="col-sm-9">
                    @if ($user->profile)
                        <a href="{{ $user->profile->public_url }}" target="_blank" rel="noopener">
                            /u/{{ $user->profile->slug }}
                        </a>
                        @if (! $user->profile->is_published)
                            <span class="badge text-bg-secondary ms-1">非公開</span>
                        @endif
                    @else
                        —
                    @endif
                </dd>
            </dl>
        </div>
    </div>

    @unless ($user->isAdmin())
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h2 class="h6 fw-bold mb-3">操作</h2>
                @if ($user->isActive())
                    <form method="post" action="{{ route('admin.users.suspend', $user) }}"
                          onsubmit="return confirm('このユーザーを停止しますか？停止中はログインできません。');">
                        @csrf @method('patch')
                        <button type="submit" class="btn btn-danger btn-sm">アカウントを停止</button>
                    </form>
                @else
                    <form method="post" action="{{ route('admin.users.activate', $user) }}">
                        @csrf @method('patch')
                        <button type="submit" class="btn btn-success btn-sm">アクティブに戻す</button>
                    </form>
                @endif
            </div>
        </div>
    @endunless
</x-app-layout>
