<x-app-layout>
    <x-slot name="header">
        <h1 class="h4 fw-bold mb-0">マイページ</h1>
    </x-slot>

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
            </div>
        </div>

        {{-- プロフ充実度メーター --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h2 class="h6 fw-bold mb-0">プロフ充実度</h2>
                    <span class="fw-bold {{ $completionRate === 100 ? 'text-success' : '' }}">{{ $completionRate }}%</span>
                </div>
                <div class="progress mb-3" style="height: 8px;">
                    <div class="progress-bar" role="progressbar" aria-valuenow="{{ $completionRate }}"
                         aria-valuemin="0" aria-valuemax="100"
                         style="width: {{ $completionRate }}%; background: var(--pt-gradient);"></div>
                </div>

                @php
                    $checkItems = [
                        ['key' => 'avatar',  'label' => 'アイコン画像', 'route' => route('mypage.profile.edit')],
                        ['key' => 'bio',     'label' => '自己紹介',    'route' => route('mypage.profile.edit')],
                        ['key' => 'tags',    'label' => 'マイタグ',    'route' => route('mypage.favorites.index')],
                        ['key' => 'links',   'label' => 'SNSリンク',   'route' => route('mypage.links.index')],
                        ['key' => 'answers', 'label' => '質問の回答',  'route' => route('mypage.answers.edit')],
                    ];
                @endphp

                <div class="row g-2 small">
                    @foreach ($checkItems as $item)
                        <div class="col-6 col-md-4">
                            @if ($checks[$item['key']])
                                <span class="text-success"><i class="bi bi-check-circle-fill"></i> {{ $item['label'] }}</span>
                            @else
                                <a href="{{ $item['route'] }}" class="text-muted text-decoration-none">
                                    <i class="bi bi-circle"></i> {{ $item['label'] }}
                                </a>
                            @endif
                        </div>
                    @endforeach
                </div>

                @if ($completionRate < 100)
                    <div class="form-text small mt-3 mb-0">
                        未設定の項目をクリックして埋めましょう。プロフが充実するほど、訪問者から興味を持ってもらえます。
                    </div>
                @endif
            </div>
        </div>

        {{-- KPI 4 枚（累計 + 直近7日） --}}
        <div class="row g-3 mb-4">
            <div class="col-6 col-md-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="small text-muted">累計 閲覧数</div>
                        <div class="h4 fw-bold mb-0">{{ number_format($stats['view_count']) }}</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="small text-muted">累計 いいね</div>
                        <div class="h4 fw-bold mb-0">{{ number_format($stats['like_count']) }}</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="small text-muted">直近 7 日 足あと</div>
                        <div class="h4 fw-bold mb-0" style="color: var(--pt-primary);">+{{ number_format($stats['footprints_last_7d']) }}</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="small text-muted">直近 7 日 いいね</div>
                        <div class="h4 fw-bold mb-0" style="color: var(--pt-accent);">+{{ number_format($stats['likes_last_7d']) }}</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- 公開状態 + 質問回答進捗 --}}
        <div class="row g-3 mb-4">
            <div class="col-12 col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <div class="small text-muted mb-1">公開状態</div>
                            <div class="h6 fw-bold mb-0">
                                @if ($profile->is_published)
                                    <span class="badge text-bg-success">公開中</span>
                                @else
                                    <span class="badge text-bg-secondary">非公開</span>
                                @endif
                            </div>
                        </div>
                        <a href="{{ route('mypage.profile.edit') }}" class="btn btn-outline-secondary btn-sm">変更</a>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6">
                <a href="{{ route('mypage.answers.edit') }}" class="text-decoration-none text-reset">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <div>
                                <div class="small text-muted mb-1">質問への回答</div>
                                <div class="h6 fw-bold mb-0">
                                    {{ $stats['answers_count'] }} / {{ $stats['questions_total'] }} 問
                                </div>
                            </div>
                            <i class="bi bi-chat-dots fs-4" style="color: var(--pt-primary);"></i>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        {{-- 最近の足あと + いいね一覧導線 --}}
        <div class="row g-3 mb-4">
            <div class="col-12 col-lg-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h2 class="h6 fw-bold mb-0">最近の足あと</h2>
                            <a href="{{ route('mypage.footprints.index') }}" class="small">すべて見る</a>
                        </div>

                        @if ($recentFootprints->isEmpty())
                            <p class="text-muted small mb-0">まだ足あとがありません。プロフィール URL を SNS で共有してみましょう。</p>
                        @else
                            @foreach ($recentFootprints as $fp)
                                @php($vp = $fp->visitor->profile)
                                @if ($vp)
                                    <a href="{{ $vp->public_url }}" target="_blank" rel="noopener"
                                       class="d-flex align-items-center gap-2 mb-2 text-decoration-none text-reset">
                                        @if ($vp->avatar_path)
                                            <img src="{{ Storage::url($vp->avatar_path) }}" alt=""
                                                 class="rounded-circle flex-shrink-0"
                                                 style="width: 36px; height: 36px; object-fit: cover;">
                                        @else
                                            <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold flex-shrink-0"
                                                 style="width: 36px; height: 36px; background: var(--pt-gradient); font-size: 0.875rem;">
                                                {{ mb_substr($vp->nickname, 0, 1) }}
                                            </div>
                                        @endif
                                        <div class="flex-grow-1 min-w-0">
                                            <div class="small fw-semibold text-truncate">{{ $vp->nickname }}</div>
                                            <div class="small text-muted">{{ $fp->visited_at->diffForHumans() }}</div>
                                        </div>
                                    </a>
                                @endif
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-6">
                <a href="{{ route('mypage.likes.index') }}" class="text-decoration-none text-reset">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body d-flex flex-column justify-content-between">
                            <div>
                                <div class="small text-muted mb-1">いいねした人</div>
                                <div class="fw-semibold mb-2">あなたが ♡ を押したプロフを一覧で確認</div>
                                <p class="small text-muted mb-0">気になるプロフィールを見つけたらタップしてプロフへ。</p>
                            </div>
                            <div class="text-end">
                                <i class="bi bi-heart fs-4" style="color: var(--pt-accent);"></i>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>

    @else
        <div class="alert alert-danger">
            プロフィールが見つかりません。お手数ですが管理者にお問い合わせください。
        </div>
    @endif
</x-app-layout>
