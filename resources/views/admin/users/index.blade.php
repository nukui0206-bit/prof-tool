<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <h1 class="h4 fw-bold mb-0">ユーザー一覧</h1>
            <a href="{{ route('admin.dashboard') }}" class="small">← 管理ダッシュボード</a>
        </div>
    </x-slot>

    <form method="get" class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <div class="row g-2">
                <div class="col-12 col-md-6">
                    <input type="text" name="q" value="{{ $q }}" placeholder="名前 / メールアドレス" class="form-control">
                </div>
                <div class="col-8 col-md-4">
                    <select name="status" class="form-select">
                        <option value="">すべて</option>
                        <option value="active" {{ $status === 'active' ? 'selected' : '' }}>アクティブ</option>
                        <option value="suspended" {{ $status === 'suspended' ? 'selected' : '' }}>停止中</option>
                    </select>
                </div>
                <div class="col-4 col-md-2 d-grid">
                    <button type="submit" class="btn btn-primary">検索</button>
                </div>
            </div>
        </div>
    </form>

    @if ($users->isEmpty())
        <div class="card border-0 shadow-sm">
            <div class="card-body text-muted small">該当するユーザーはいません。</div>
        </div>
    @else
        <div class="card border-0 shadow-sm">
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead>
                        <tr class="small text-muted">
                            <th>ID</th>
                            <th>名前</th>
                            <th>メール</th>
                            <th>ロール</th>
                            <th>状態</th>
                            <th>登録日</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $u)
                            <tr>
                                <td class="small">{{ $u->id }}</td>
                                <td>{{ $u->name }}</td>
                                <td class="small">{{ $u->email }}</td>
                                <td>
                                    @if ($u->isAdmin())
                                        <span class="badge text-bg-dark">admin</span>
                                    @else
                                        <span class="badge text-bg-light">user</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($u->isActive())
                                        <span class="badge text-bg-success">アクティブ</span>
                                    @else
                                        <span class="badge text-bg-danger">停止</span>
                                    @endif
                                </td>
                                <td class="small">{{ $u->created_at->format('Y/m/d') }}</td>
                                <td><a href="{{ route('admin.users.show', $u) }}" class="btn btn-sm btn-outline-primary">詳細</a></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="mt-3">{{ $users->links() }}</div>
    @endif
</x-app-layout>
