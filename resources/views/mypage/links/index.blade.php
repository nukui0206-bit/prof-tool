<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <h1 class="h4 fw-bold mb-0">SNSリンク</h1>
            <a href="{{ $profile->public_url }}" target="_blank" rel="noopener" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-box-arrow-up-right"></i> 公開ページを開く
            </a>
        </div>
    </x-slot>

    @if (session('status'))
        @php($msg = match(session('status')) {
            'link-added' => 'リンクを追加しました。',
            'link-updated' => 'リンクを更新しました。',
            'link-deleted' => 'リンクを削除しました。',
            default => '保存しました。',
        })
        <div class="alert alert-success small">{{ $msg }}</div>
    @endif

    <p class="text-muted small mb-4">
        X (Twitter) / Instagram / TikTok / YouTube などの SNS リンクを並べて、公開プロフから直接飛べるようにできます。
    </p>

    {{-- 追加フォーム --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <h2 class="h6 fw-bold mb-3">リンクを追加</h2>
            <form method="post" action="{{ route('mypage.links.store') }}">
                @csrf
                <div class="row g-2">
                    <div class="col-12 col-md-3">
                        <select name="platform" class="form-select" required>
                            @foreach (\App\Models\SocialLink::PLATFORMS as $key => $label)
                                <option value="{{ $key }}" {{ old('platform') === $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12 col-md-5">
                        <input type="url" name="url" maxlength="500" required
                               value="{{ old('url') }}"
                               placeholder="https://..."
                               class="form-control">
                    </div>
                    <div class="col-12 col-md-2">
                        <input type="text" name="label" maxlength="60"
                               value="{{ old('label') }}"
                               placeholder="ラベル（任意）"
                               class="form-control">
                    </div>
                    <div class="col-12 col-md-2 d-grid">
                        <x-primary-button class="w-100">
                            <i class="bi bi-plus-lg"></i> 追加
                        </x-primary-button>
                    </div>
                </div>
                <x-input-error :messages="$errors->get('platform')" />
                <x-input-error :messages="$errors->get('url')" />
                <x-input-error :messages="$errors->get('label')" />
                <div class="form-text small mt-2">「その他」を選んだ場合は、ラベル欄にサービス名を入れることをおすすめします。</div>
            </form>
        </div>
    </div>

    {{-- リスト --}}
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <h2 class="h6 fw-bold mb-3">登録済みのリンク（{{ count($links) }}件）</h2>

            @if (count($links) === 0)
                <p class="text-muted small mb-0">まだリンクが登録されていません。上のフォームから追加してください。</p>
            @else
                <ul id="links-list" class="list-unstyled mb-0">
                    @foreach ($links as $link)
                        <li class="d-flex align-items-start align-items-md-center gap-2 mb-2 p-2 border rounded flex-wrap"
                            data-id="{{ $link->id }}">
                            <span class="text-muted handle" style="cursor: grab;" title="ドラッグして並び替え">
                                <i class="bi bi-grip-vertical"></i>
                            </span>
                            <i class="bi {{ $link->iconClass() }} fs-5" style="color: var(--pt-primary);"></i>

                            <form method="post" action="{{ route('mypage.links.update', $link) }}"
                                  class="d-flex flex-grow-1 gap-2 flex-wrap" style="min-width: 240px;">
                                @csrf
                                @method('patch')
                                <select name="platform" class="form-select form-select-sm" style="max-width: 140px;" required>
                                    @foreach (\App\Models\SocialLink::PLATFORMS as $key => $label)
                                        <option value="{{ $key }}" {{ $link->platform === $key ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                                <input type="url" name="url" maxlength="500" required
                                       value="{{ $link->url }}"
                                       class="form-control form-control-sm flex-grow-1" style="min-width: 180px;">
                                <input type="text" name="label" maxlength="60"
                                       value="{{ $link->label }}"
                                       placeholder="ラベル"
                                       class="form-control form-control-sm" style="max-width: 140px;">
                                <button type="submit" class="btn btn-outline-secondary btn-sm">保存</button>
                            </form>

                            <form method="post" action="{{ route('mypage.links.destroy', $link) }}"
                                  onsubmit="return confirm('このリンクを削除しますか？')">
                                @csrf
                                @method('delete')
                                <button type="submit" class="btn btn-outline-danger btn-sm" title="削除">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>

    @push('scripts')
        @if (count($links) > 0)
            <script>
                (function () {
                    const list = document.getElementById('links-list');
                    if (!list || typeof window.Sortable === 'undefined') return;

                    new window.Sortable(list, {
                        handle: '.handle',
                        animation: 150,
                        onEnd: function () {
                            const ids = [...list.querySelectorAll('li[data-id]')].map(li => li.dataset.id);
                            fetch('{{ route('mypage.links.reorder') }}', {
                                method: 'PATCH',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                    'Accept': 'application/json',
                                },
                                body: JSON.stringify({ ids }),
                            }).catch(() => {
                                alert('並び替えの保存に失敗しました。再読み込みしてください。');
                            });
                        },
                    });
                })();
            </script>
        @endif
    @endpush
</x-app-layout>
