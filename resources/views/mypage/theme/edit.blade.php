<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <h1 class="h4 fw-bold mb-0">テーマ設定</h1>
            <a href="{{ $profile->public_url }}" target="_blank" rel="noopener" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-box-arrow-up-right"></i> 公開ページを開く
            </a>
        </div>
    </x-slot>

    @if (session('status') === 'theme-updated')
        <div class="alert alert-success small">テーマを更新しました。公開ページで確認してください。</div>
    @endif

    <p class="text-muted small mb-4">
        公開プロフィールページ <code>/u/{{ $profile->slug }}</code> の見た目を切り替えられます。
        プリセットを選んだあと、メインカラーだけ自分好みに上書きすることもできます。
    </p>

    <form method="post" action="{{ route('mypage.theme.update') }}">
        @csrf
        @method('patch')

        {{-- プリセット選択 --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <h2 class="h6 fw-bold mb-3">プリセットを選ぶ</h2>

                <div class="row g-3">
                    {{-- プリセット --}}
                    @foreach ($themes as $theme)
                        @php($selected = (int) old('theme_id', $profile->theme_id) === $theme->id)
                        <div class="col-6 col-md-4">
                            <label class="d-block h-100" style="cursor: pointer;">
                                <input type="radio" name="theme_id" value="{{ $theme->id }}"
                                       class="visually-hidden theme-radio"
                                       {{ $selected ? 'checked' : '' }}>
                                <div class="card h-100 theme-card {{ $selected ? 'border-primary' : '' }}"
                                     style="border-width: 2px;">
                                    <div class="card-body p-3 text-center">
                                        <div class="rounded-circle mx-auto mb-2 d-flex align-items-center justify-content-center text-white fw-bold"
                                             style="width: 56px; height: 56px; background: {{ $theme->default_color }}; font-size: 1.25rem;">
                                            ✿
                                        </div>
                                        <div class="fw-bold small">{{ $theme->name }}</div>
                                        <div class="text-muted" style="font-size: 0.75rem;">{{ $theme->key }}</div>
                                    </div>
                                </div>
                            </label>
                        </div>
                    @endforeach

                    {{-- 「指定なし」 --}}
                    @php($noneSelected = !old('theme_id', $profile->theme_id))
                    <div class="col-6 col-md-4">
                        <label class="d-block h-100" style="cursor: pointer;">
                            <input type="radio" name="theme_id" value=""
                                   class="visually-hidden theme-radio"
                                   {{ $noneSelected ? 'checked' : '' }}>
                            <div class="card h-100 theme-card {{ $noneSelected ? 'border-primary' : '' }}"
                                 style="border-width: 2px;">
                                <div class="card-body p-3 text-center">
                                    <div class="rounded-circle mx-auto mb-2 d-flex align-items-center justify-content-center"
                                         style="width: 56px; height: 56px; background: #f3f4f6; color: #94a3b8; font-size: 1.25rem;">
                                        —
                                    </div>
                                    <div class="fw-bold small">指定なし</div>
                                    <div class="text-muted" style="font-size: 0.75rem;">default</div>
                                </div>
                            </div>
                        </label>
                    </div>
                </div>
                <x-input-error :messages="$errors->get('theme_id')" />
            </div>
        </div>

        {{-- メインカラー上書き --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <h2 class="h6 fw-bold mb-3">メインカラー（任意）</h2>
                <p class="text-muted small">
                    プリセットのアクセントカラーを上書きしたい時に使います。「リセット」を押すとテーマ既定の色に戻ります。
                </p>

                <div class="d-flex align-items-center gap-3 flex-wrap">
                    <input type="color" id="theme_color_picker"
                           value="{{ old('theme_color', $profile->theme_color ?? '#6366f1') }}"
                           class="form-control form-control-color"
                           style="width: 4rem; height: 3rem;">
                    <input type="hidden" name="theme_color" id="theme_color_input"
                           value="{{ old('theme_color', $profile->theme_color) }}">
                    <code id="theme_color_display" class="small">
                        {{ old('theme_color', $profile->theme_color) ?: '（未設定）' }}
                    </code>
                    <button type="button" class="btn btn-outline-secondary btn-sm" id="theme_color_reset">
                        リセット
                    </button>
                </div>

                <x-input-error :messages="$errors->get('theme_color')" />
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
                // プリセット選択時に枠を強調
                const cards = document.querySelectorAll('.theme-card');
                document.querySelectorAll('.theme-radio').forEach(radio => {
                    radio.addEventListener('change', () => {
                        cards.forEach(c => c.classList.remove('border-primary'));
                        const card = radio.closest('label').querySelector('.theme-card');
                        card?.classList.add('border-primary');
                    });
                });

                // カラーピッカー → hidden input 反映
                const picker = document.getElementById('theme_color_picker');
                const input = document.getElementById('theme_color_input');
                const display = document.getElementById('theme_color_display');
                const reset = document.getElementById('theme_color_reset');

                picker?.addEventListener('input', () => {
                    input.value = picker.value;
                    display.textContent = picker.value;
                });

                reset?.addEventListener('click', () => {
                    input.value = '';
                    display.textContent = '（未設定）';
                });
            })();
        </script>
    @endpush
</x-app-layout>
