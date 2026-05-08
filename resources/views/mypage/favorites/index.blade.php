<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <h1 class="h4 fw-bold mb-0">マイタグ</h1>
            <a href="{{ $profile->public_url }}" target="_blank" rel="noopener" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-box-arrow-up-right"></i> 公開ページを開く
            </a>
        </div>
    </x-slot>

    @if (session('status'))
        @php($msg = match(session('status')) {
            'favorite-added' => 'タグを追加しました。',
            'favorite-updated' => 'タグを更新しました。',
            'favorite-deleted' => 'タグを削除しました。',
            default => '保存しました。',
        })
        <div class="alert alert-success small">{{ $msg }}</div>
    @endif

    <p class="text-muted small mb-4">
        あなたの推し・興味・ハマってるものを「タグ」として登録できます。<br>
        同じタグを持つ人と共通点を見つけられる機能は、今後のアップデートで追加予定です。
    </p>

    {{-- 追加フォーム --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <h2 class="h6 fw-bold mb-3">タグを追加</h2>
            <form method="post" action="{{ route('mypage.favorites.store') }}" class="d-flex gap-2 flex-wrap">
                @csrf
                <input type="text" name="label" maxlength="60" required
                       value="{{ old('label') }}"
                       placeholder="例：カヌレ、星野源、推しのライブ"
                       class="form-control flex-grow-1" style="min-width: 200px;">
                <x-primary-button>
                    <i class="bi bi-plus-lg"></i> 追加
                </x-primary-button>
            </form>
            <x-input-error :messages="$errors->get('label')" />
            <div class="form-text small mt-2">最大 60 文字。並び替えは下のリストをドラッグして行えます。</div>
        </div>
    </div>

    {{-- リスト --}}
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <h2 class="h6 fw-bold mb-3">登録済みのタグ（{{ count($favorites) }}件）</h2>

            @if (count($favorites) === 0)
                <p class="text-muted small mb-0">まだタグが登録されていません。上のフォームから追加してください。</p>
            @else
                <ul id="favorites-list" class="list-unstyled mb-0">
                    @foreach ($favorites as $fav)
                        <li class="d-flex align-items-center gap-2 mb-2 p-2 border rounded" data-id="{{ $fav->id }}">
                            <span class="text-muted handle" style="cursor: grab;" title="ドラッグして並び替え">
                                <i class="bi bi-grip-vertical"></i>
                            </span>

                            <span class="text-muted small" style="font-family: monospace;">#</span>

                            <form method="post" action="{{ route('mypage.favorites.update', $fav) }}"
                                  class="d-flex flex-grow-1 gap-2">
                                @csrf
                                @method('patch')
                                <input type="text" name="label" maxlength="60" required
                                       value="{{ $fav->label }}"
                                       class="form-control form-control-sm flex-grow-1">
                                <button type="submit" class="btn btn-outline-secondary btn-sm">保存</button>
                            </form>

                            <form method="post" action="{{ route('mypage.favorites.destroy', $fav) }}"
                                  onsubmit="return confirm('このタグを削除しますか？')">
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
        @if (count($favorites) > 0)
            <script>
                (function () {
                    const list = document.getElementById('favorites-list');
                    if (!list || typeof window.Sortable === 'undefined') return;

                    new window.Sortable(list, {
                        handle: '.handle',
                        animation: 150,
                        onEnd: function () {
                            const ids = [...list.querySelectorAll('li[data-id]')].map(li => li.dataset.id);
                            fetch('{{ route('mypage.favorites.reorder') }}', {
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
