<section>
    <header class="mb-3">
        <h2 class="h5 fw-bold mb-1">パスワード変更</h2>
        <p class="small text-muted mb-0">アカウントを安全に保つため、長くてランダムなパスワードを使ってください。</p>
    </header>

    <form method="post" action="{{ route('password.update') }}">
        @csrf
        @method('put')

        <div class="mb-3">
            <x-input-label for="update_password_current_password" value="現在のパスワード" />
            <x-text-input id="update_password_current_password" name="current_password" type="password" autocomplete="current-password" />
            <x-input-error :messages="$errors->updatePassword->get('current_password')" />
        </div>

        <div class="mb-3">
            <x-input-label for="update_password_password" value="新しいパスワード" />
            <x-text-input id="update_password_password" name="password" type="password" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password')" />
        </div>

        <div class="mb-3">
            <x-input-label for="update_password_password_confirmation" value="新しいパスワード（確認）" />
            <x-text-input id="update_password_password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" />
        </div>

        <div class="d-flex align-items-center gap-3">
            <x-primary-button>保存</x-primary-button>

            @if (session('status') === 'password-updated')
                <span class="text-success small">保存しました</span>
            @endif
        </div>
    </form>
</section>
