<x-app-layout>
    <x-slot name="header">
        <h1 class="h4 fw-bold mb-0">マイページ</h1>
    </x-slot>

    @php($profile = Auth::user()->profile)

    @if ($profile)
        {{-- 公開プロフ URL カード --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <div class="d-flex flex-column flex-md-row align-items-md-center gap-3">
                    <div class="flex-shrink-0">
                        @if ($profile->avatar_path)
                            <img src="{{ Storage::url($profile->avatar_path) }}"
                                 alt="アバター"
                                 class="rounded-circle"
                                 style="width: 56px; height: 56px; object-fit: cover;">
                        @else
                            <div class="d-flex align-items-center justify-content-center rounded-circle text-white fw-bold"
                                 style="width: 56px; height: 56px; background: var(--pt-gradient); font-size: 1.5rem;">
                                {{ mb_substr($profile->nickname, 0, 1) }}
                            </div>
                        @endif
                    </div>
                    <div class="flex-grow-1 min-w-0">
                        <div class="small text-muted mb-1">あなたの公開プロフィール URL</div>
                        <div class="d-flex align-items-center gap-2">
                            <code class="small text-truncate" style="max-width: 100%;">{{ $profile->public_url }}</code>
                        </div>
                    </div>
                    <div class="d-flex gap-2 flex-shrink-0 flex-wrap">
                        <a href="{{ $profile->public_url }}" target="_blank" rel="noopener" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-box-arrow-up-right"></i> 開く
                        </a>
                        <button type="button" class="btn btn-outline-secondary btn-sm"
                                onclick="navigator.clipboard.writeText('{{ $profile->public_url }}'); this.innerText='コピー済';">
                            <i class="bi bi-clipboard"></i> URL をコピー
                        </button>
                    </div>
                </div>

                <div class="d-flex flex-wrap gap-2 mt-3 pt-3 border-top">
                    <a href="{{ route('mypage.profile.edit') }}" class="btn btn-primary btn-sm">
                        <i class="bi bi-pencil"></i> プロフィールを編集
                    </a>
                    <a href="{{ route('mypage.answers.edit') }}" class="btn btn-outline-primary btn-sm">
                        <i class="bi bi-chat-dots"></i> 質問に答える
                    </a>
                    <a href="{{ route('mypage.favorites.index') }}" class="btn btn-outline-primary btn-sm">
                        <i class="bi bi-hash"></i> マイタグを編集
                    </a>
                    <a href="{{ route('mypage.links.index') }}" class="btn btn-outline-primary btn-sm">
                        <i class="bi bi-link-45deg"></i> SNSリンクを編集
                    </a>
                    <a href="{{ route('mypage.theme.edit') }}" class="btn btn-outline-primary btn-sm">
                        <i class="bi bi-palette"></i> テーマ設定
                    </a>
                </div>
            </div>
        </div>

        {{-- KPI --}}
        <div class="row g-3 mb-4">
            <div class="col-6 col-md-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="small text-muted">プロフ閲覧数</div>
                        <div class="h3 fw-bold mb-0">{{ number_format($profile->view_count) }}</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="small text-muted">いいね</div>
                        <div class="h3 fw-bold mb-0">{{ number_format($profile->like_count) }}</div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="small text-muted">公開状態</div>
                        <div class="h5 fw-bold mb-0 mt-1">
                            @if ($profile->is_published)
                                <span class="badge text-bg-success">公開中</span>
                            @else
                                <span class="badge text-bg-secondary">非公開</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="alert alert-warning small mb-0">
            <strong>Phase 7 時点：</strong> いいね・足あと・通報・管理画面などは Phase 8 以降で順次追加されます。
        </div>
    @else
        <div class="alert alert-danger">
            プロフィールが見つかりません。お手数ですが管理者にお問い合わせください。
        </div>
    @endif
</x-app-layout>
