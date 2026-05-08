<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <h1 class="h4 fw-bold mb-0">プロフィール編集</h1>
            <a href="{{ $profile->public_url }}" target="_blank" rel="noopener" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-box-arrow-up-right"></i> 公開ページを開く
            </a>
        </div>
    </x-slot>

    @if (session('status') === 'profile-updated')
        <div class="alert alert-success small">プロフィールを更新しました。</div>
    @endif

    <form method="post" action="{{ route('mypage.profile.update') }}" enctype="multipart/form-data">
        @csrf
        @method('patch')

        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <h2 class="h6 fw-bold mb-3">アイコン画像</h2>

                <div class="d-flex align-items-center gap-3 flex-wrap">
                    <div class="flex-shrink-0">
                        @if ($profile->avatar_path)
                            <img src="{{ Storage::url($profile->avatar_path) }}"
                                 alt="現在のアイコン"
                                 class="rounded-circle"
                                 style="width: 96px; height: 96px; object-fit: cover; border: 1px solid var(--pt-border);">
                        @else
                            <div class="d-flex align-items-center justify-content-center rounded-circle text-white fw-bold"
                                 style="width: 96px; height: 96px; background: var(--pt-gradient); font-size: 2.25rem;">
                                {{ mb_substr($profile->nickname, 0, 1) }}
                            </div>
                        @endif
                    </div>

                    <div class="flex-grow-1" style="min-width: 240px;">
                        <input type="file" name="avatar" id="avatar"
                               accept="image/jpeg,image/png,image/webp"
                               class="form-control">
                        <div class="form-text small">JPEG / PNG / WebP、最大 2MB。</div>
                        <x-input-error :messages="$errors->get('avatar')" />

                        @if ($profile->avatar_path)
                            <div class="form-check mt-2">
                                <input class="form-check-input" type="checkbox" name="remove_avatar" value="1" id="remove_avatar">
                                <label class="form-check-label small text-danger" for="remove_avatar">
                                    現在のアイコンを削除
                                </label>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <h2 class="h6 fw-bold mb-3">基本情報</h2>

                <div class="mb-3">
                    <x-input-label for="nickname" value="ニックネーム" />
                    <x-text-input id="nickname" name="nickname" type="text" maxlength="40"
                                  :value="old('nickname', $profile->nickname)" required />
                    <x-input-error :messages="$errors->get('nickname')" />
                </div>

                <div class="mb-3">
                    <x-input-label for="bio" value="自己紹介" />
                    <textarea id="bio" name="bio" rows="6" maxlength="500"
                              class="form-control"
                              placeholder="あなたのことを自由に書いてください（最大 500 文字）">{{ old('bio', $profile->bio) }}</textarea>
                    <div class="form-text small d-flex justify-content-between">
                        <span>改行も保持されます。</span>
                        <span><span id="bio-count">{{ mb_strlen(old('bio', $profile->bio ?? '')) }}</span> / 500</span>
                    </div>
                    <x-input-error :messages="$errors->get('bio')" />
                </div>

                <div class="mb-2">
                    <x-input-label for="slug" value="公開URL" />
                    <div class="input-group">
                        <span class="input-group-text small">/u/</span>
                        <input type="text" id="slug" name="slug"
                               value="{{ old('slug', $profile->slug) }}"
                               minlength="3" maxlength="30"
                               pattern="[a-zA-Z0-9_\-]+"
                               class="form-control" required
                               style="font-family: monospace;">
                    </div>
                    <div class="form-text small">
                        半角英数 / <code>-</code> / <code>_</code> の 3〜30 文字。大文字は自動で小文字に変換されます。<br>
                        <strong class="text-danger">変更すると以前の URL（<code>/u/{{ $profile->slug }}</code>）は使えなくなります。</strong>
                    </div>
                    <x-input-error :messages="$errors->get('slug')" />
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <h2 class="h6 fw-bold mb-3">公開設定</h2>

                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" role="switch"
                           name="is_published" value="1" id="is_published"
                           {{ old('is_published', $profile->is_published) ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_published">
                        プロフィールを公開する
                    </label>
                </div>
                <div class="form-text small">
                    オフにすると <code>/u/{{ $profile->slug }}</code> は 404 になり、第三者から閲覧できなくなります。
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-end gap-2">
            <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">キャンセル</a>
            <x-primary-button>保存する</x-primary-button>
        </div>
    </form>

    @push('scripts')
        <script>
            (function () {
                const bio = document.getElementById('bio');
                const counter = document.getElementById('bio-count');
                if (!bio || !counter) return;
                bio.addEventListener('input', () => {
                    counter.textContent = [...bio.value].length;
                });
            })();
        </script>
    @endpush
</x-app-layout>
