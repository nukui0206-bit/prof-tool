<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <h1 class="h4 fw-bold mb-0">通報詳細</h1>
            <a href="{{ route('admin.reports.index') }}" class="small">← 一覧に戻る</a>
        </div>
    </x-slot>

    @if (session('status'))
        <div class="alert alert-info small">{{ session('status') }}</div>
    @endif

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <dl class="row mb-0 small">
                <dt class="col-sm-3 text-muted">通報 ID</dt>
                <dd class="col-sm-9">#{{ $report->id }}</dd>

                <dt class="col-sm-3 text-muted">日時</dt>
                <dd class="col-sm-9">{{ $report->created_at->format('Y/m/d H:i') }}</dd>

                <dt class="col-sm-3 text-muted">理由</dt>
                <dd class="col-sm-9"><span class="badge text-bg-light">{{ $report->reasonLabel() }}</span></dd>

                <dt class="col-sm-3 text-muted">通報者</dt>
                <dd class="col-sm-9">
                    @if ($report->reporter)
                        {{ $report->reporter->name }}（{{ $report->reporter->email }}）
                        <a href="{{ route('admin.users.show', $report->reporter) }}" class="small">→ ユーザー詳細</a>
                    @else
                        <span class="text-muted">匿名</span>
                    @endif
                </dd>

                <dt class="col-sm-3 text-muted">対象</dt>
                <dd class="col-sm-9">
                    <code>{{ $report->target_type }}#{{ $report->target_id }}</code>
                    @if ($target)
                        <br>
                        <a href="{{ $target->public_url }}" target="_blank" rel="noopener">
                            {{ $target->nickname }}（/u/{{ $target->slug }}）
                        </a>
                        @if ($target->user)
                            <br>
                            <a href="{{ route('admin.users.show', $target->user) }}" class="small">→ 投稿者ユーザー詳細</a>
                        @endif
                    @else
                        <br><span class="text-muted small">対象は削除されているか見つかりません。</span>
                    @endif
                </dd>

                <dt class="col-sm-3 text-muted">補足</dt>
                <dd class="col-sm-9" style="white-space: pre-wrap;">{{ $report->body ?: '—' }}</dd>

                <dt class="col-sm-3 text-muted">現在の状態</dt>
                <dd class="col-sm-9">
                    @php
                        $badge = match($report->status) {
                            'open' => 'text-bg-danger',
                            'reviewing' => 'text-bg-warning',
                            'closed' => 'text-bg-secondary',
                            default => 'text-bg-light',
                        };
                    @endphp
                    <span class="badge {{ $badge }}">{{ $report->statusLabel() }}</span>
                </dd>
            </dl>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <h2 class="h6 fw-bold mb-3">ステータス変更</h2>
            <form method="post" action="{{ route('admin.reports.status', $report) }}" class="d-flex gap-2 flex-wrap">
                @csrf @method('patch')
                @foreach (\App\Models\Report::STATUSES as $key => $label)
                    <button type="submit" name="status" value="{{ $key }}"
                            class="btn btn-sm {{ $report->status === $key ? 'btn-primary' : 'btn-outline-primary' }}"
                            {{ $report->status === $key ? 'disabled' : '' }}>
                        {{ $label }}
                    </button>
                @endforeach
            </form>
        </div>
    </div>
</x-app-layout>
