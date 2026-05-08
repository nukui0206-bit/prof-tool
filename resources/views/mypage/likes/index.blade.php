<x-app-layout>
    <x-slot name="header">
        <h1 class="h4 fw-bold mb-0">いいねした人</h1>
    </x-slot>

    @if ($likes->isEmpty())
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center text-muted small py-5">
                まだ誰にもいいねしていません。<br>
                気になるプロフィールページで ♡ を押してみましょう。
            </div>
        </div>
    @else
        <div class="row g-3">
            @foreach ($likes as $like)
                @php($p = $like->profile)
                <div class="col-12 col-md-6">
                    <a href="{{ $p->public_url }}" target="_blank" rel="noopener" class="text-decoration-none text-reset">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body d-flex align-items-center gap-3">
                                @if ($p->avatar_path)
                                    <img src="{{ Storage::url($p->avatar_path) }}" alt="{{ $p->nickname }} のアイコン"
                                         class="rounded-circle flex-shrink-0"
                                         style="width: 48px; height: 48px; object-fit: cover;">
                                @else
                                    <div class="d-flex align-items-center justify-content-center rounded-circle text-white fw-bold flex-shrink-0"
                                         style="width: 48px; height: 48px; background: var(--pt-gradient); font-size: 1.25rem;">
                                        {{ mb_substr($p->nickname, 0, 1) }}
                                    </div>
                                @endif
                                <div class="flex-grow-1 min-w-0">
                                    <div class="fw-bold text-truncate">{{ $p->nickname }}</div>
                                    <div class="small text-muted text-truncate">@{{ $p->slug }} · {{ $like->created_at->diffForHumans() }}</div>
                                </div>
                                <i class="bi bi-heart-fill text-danger flex-shrink-0"></i>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>

        <div class="mt-4">
            {{ $likes->links() }}
        </div>
    @endif
</x-app-layout>
