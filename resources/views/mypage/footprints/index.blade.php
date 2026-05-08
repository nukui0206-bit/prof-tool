<x-app-layout>
    <x-slot name="header">
        <h1 class="h4 fw-bold mb-0">足あと</h1>
    </x-slot>

    <p class="text-muted small mb-4">
        あなたのプロフィール <code>/u/{{ $profile->slug }}</code> を訪問してくれた人の一覧です。
        24 時間以内に同じ人が複数回訪れた場合は、最新の訪問時刻に集約されます。
    </p>

    @if ($footprints->isEmpty())
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center text-muted small py-5">
                まだ足あとがありません。<br>
                プロフィールページの URL を SNS で共有してみましょう。
            </div>
        </div>
    @else
        <div class="row g-3">
            @foreach ($footprints as $fp)
                @php($vp = $fp->visitor->profile)
                <div class="col-12 col-md-6">
                    @if ($vp)
                        <a href="{{ $vp->public_url }}" target="_blank" rel="noopener" class="text-decoration-none text-reset">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-body d-flex align-items-center gap-3">
                                    @if ($vp->avatar_path)
                                        <img src="{{ Storage::url($vp->avatar_path) }}" alt="{{ $vp->nickname }} のアイコン"
                                             class="rounded-circle flex-shrink-0"
                                             style="width: 48px; height: 48px; object-fit: cover;">
                                    @else
                                        <div class="d-flex align-items-center justify-content-center rounded-circle text-white fw-bold flex-shrink-0"
                                             style="width: 48px; height: 48px; background: var(--pt-gradient); font-size: 1.25rem;">
                                            {{ mb_substr($vp->nickname, 0, 1) }}
                                        </div>
                                    @endif
                                    <div class="flex-grow-1 min-w-0">
                                        <div class="fw-bold text-truncate">{{ $vp->nickname }}</div>
                                        <div class="small text-muted text-truncate">@{{ $vp->slug }} · {{ $fp->visited_at->diffForHumans() }}</div>
                                    </div>
                                    <i class="bi bi-arrow-right text-muted flex-shrink-0"></i>
                                </div>
                            </div>
                        </a>
                    @else
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body d-flex align-items-center gap-3 text-muted">
                                <div class="d-flex align-items-center justify-content-center rounded-circle flex-shrink-0"
                                     style="width: 48px; height: 48px; background: #e5e7eb; font-size: 1.25rem;">
                                    ?
                                </div>
                                <div class="flex-grow-1 min-w-0">
                                    <div class="fw-bold">退会したユーザー</div>
                                    <div class="small">{{ $fp->visited_at->diffForHumans() }}</div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>

        <div class="mt-4">
            {{ $footprints->links() }}
        </div>
    @endif
</x-app-layout>
