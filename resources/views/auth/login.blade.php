<x-guest-layout>
    <h1 class="h4 fw-bold text-center mb-4">ログイン</h1>

    <x-auth-session-status class="mb-3" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="mb-3">
            <x-input-label for="email" value="メールアドレス" />
            <x-text-input id="email" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" />
        </div>

        <div class="mb-3">
            <x-input-label for="password" value="パスワード" />
            <x-text-input id="password" type="password" name="password" required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" />
        </div>

        <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" id="remember_me" name="remember">
            <label class="form-check-label small text-muted" for="remember_me">ログイン状態を保持する</label>
        </div>

        <div class="d-flex justify-content-between align-items-center">
            @if (Route::has('password.request'))
                <a class="small text-decoration-none" href="{{ route('password.request') }}">パスワードを忘れた？</a>
            @endif
            <x-primary-button>ログイン</x-primary-button>
        </div>

        <hr class="my-4">
        <div class="text-center small">
            アカウント未作成？ <a href="{{ route('register') }}">新規登録</a>
        </div>
    </form>
</x-guest-layout>
