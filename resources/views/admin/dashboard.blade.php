<x-app-layout>
    <x-slot name="header">
        <h1 class="h4 fw-bold mb-0">管理ダッシュボード</h1>
    </x-slot>

    <div class="alert alert-info small">
        <strong>管理画面</strong> ／ ロール：admin。
        <a href="{{ route('admin.users.index') }}">ユーザー一覧</a>
        ・<a href="{{ route('admin.reports.index') }}">通報一覧</a>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm h-100"><div class="card-body">
                <div class="small text-muted">登録ユーザー</div>
                <div class="h4 fw-bold mb-0">{{ number_format($stats['users_total']) }}</div>
            </div></div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm h-100"><div class="card-body">
                <div class="small text-muted">アクティブ</div>
                <div class="h4 fw-bold mb-0 text-success">{{ number_format($stats['users_active']) }}</div>
            </div></div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm h-100"><div class="card-body">
                <div class="small text-muted">停止中</div>
                <div class="h4 fw-bold mb-0 text-danger">{{ number_format($stats['users_suspended']) }}</div>
            </div></div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm h-100"><div class="card-body">
                <div class="small text-muted">直近 7 日 新規</div>
                <div class="h4 fw-bold mb-0" style="color: var(--pt-primary);">+{{ number_format($stats['users_new_7d']) }}</div>
            </div></div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-6 col-md-4">
            <div class="card border-0 shadow-sm h-100"><div class="card-body">
                <div class="small text-muted">公開プロフ</div>
                <div class="h4 fw-bold mb-0">{{ number_format($stats['profiles_published']) }}</div>
            </div></div>
        </div>
        <div class="col-6 col-md-4">
            <div class="card border-0 shadow-sm h-100 {{ $stats['reports_open'] > 0 ? 'border border-danger' : '' }}"><div class="card-body">
                <div class="small text-muted">通報 未対応</div>
                <div class="h4 fw-bold mb-0 {{ $stats['reports_open'] > 0 ? 'text-danger' : '' }}">{{ number_format($stats['reports_open']) }}</div>
            </div></div>
        </div>
        <div class="col-6 col-md-4">
            <div class="card border-0 shadow-sm h-100"><div class="card-body">
                <div class="small text-muted">通報 対応中</div>
                <div class="h4 fw-bold mb-0 text-warning">{{ number_format($stats['reports_reviewing']) }}</div>
            </div></div>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2 class="h6 fw-bold mb-0">未対応の通報（最新 5 件）</h2>
                <a href="{{ route('admin.reports.index') }}" class="small">すべて見る</a>
            </div>

            @if ($latestReports->isEmpty())
                <p class="text-muted small mb-0">未対応の通報はありません。</p>
            @else
                <div class="table-responsive">
                    <table class="table table-sm align-middle mb-0">
                        <thead>
                            <tr class="small text-muted">
                                <th>日時</th>
                                <th>理由</th>
                                <th>通報者</th>
                                <th>対象</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($latestReports as $r)
                                <tr>
                                    <td class="small">{{ $r->created_at->format('m/d H:i') }}</td>
                                    <td><span class="badge text-bg-light">{{ $r->reasonLabel() }}</span></td>
                                    <td class="small">{{ $r->reporter?->name ?? '匿名' }}</td>
                                    <td class="small"><code>{{ $r->target_type }}#{{ $r->target_id }}</code></td>
                                    <td><a href="{{ route('admin.reports.show', $r) }}" class="btn btn-sm btn-outline-primary">確認</a></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
