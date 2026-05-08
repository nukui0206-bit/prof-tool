<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <h1 class="h4 fw-bold mb-0">通報一覧</h1>
            <a href="{{ route('admin.dashboard') }}" class="small">← 管理ダッシュボード</a>
        </div>
    </x-slot>

    <form method="get" class="mb-4">
        <div class="btn-group btn-group-sm" role="group">
            <a href="{{ route('admin.reports.index') }}" class="btn {{ $status === '' ? 'btn-primary' : 'btn-outline-primary' }}">すべて</a>
            @foreach (\App\Models\Report::STATUSES as $key => $label)
                <a href="{{ route('admin.reports.index', ['status' => $key]) }}"
                   class="btn {{ $status === $key ? 'btn-primary' : 'btn-outline-primary' }}">{{ $label }}</a>
            @endforeach
        </div>
    </form>

    @if ($reports->isEmpty())
        <div class="card border-0 shadow-sm">
            <div class="card-body text-muted small">該当する通報はありません。</div>
        </div>
    @else
        <div class="card border-0 shadow-sm">
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead>
                        <tr class="small text-muted">
                            <th>日時</th>
                            <th>理由</th>
                            <th>通報者</th>
                            <th>対象</th>
                            <th>状態</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($reports as $r)
                            <tr>
                                <td class="small">{{ $r->created_at->format('Y/m/d H:i') }}</td>
                                <td><span class="badge text-bg-light">{{ $r->reasonLabel() }}</span></td>
                                <td class="small">{{ $r->reporter?->name ?? '匿名' }}</td>
                                <td class="small"><code>{{ $r->target_type }}#{{ $r->target_id }}</code></td>
                                <td>
                                    @php
                                        $badge = match($r->status) {
                                            'open' => 'text-bg-danger',
                                            'reviewing' => 'text-bg-warning',
                                            'closed' => 'text-bg-secondary',
                                            default => 'text-bg-light',
                                        };
                                    @endphp
                                    <span class="badge {{ $badge }}">{{ $r->statusLabel() }}</span>
                                </td>
                                <td><a href="{{ route('admin.reports.show', $r) }}" class="btn btn-sm btn-outline-primary">詳細</a></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="mt-3">{{ $reports->links() }}</div>
    @endif
</x-app-layout>
