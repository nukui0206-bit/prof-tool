<x-guest-layout>
    <h1 class="h4 fw-bold text-center mb-4">新規登録</h1>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div class="mb-3">
            <x-input-label for="name" value="ニックネーム" />
            <x-text-input id="name" type="text" name="name" :value="old('name')" maxlength="40" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" />
        </div>

        <div class="mb-3">
            <x-input-label for="email" value="メールアドレス" />
            <x-text-input id="email" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" />
        </div>

        <div class="mb-3">
            <x-input-label for="birthdate" value="生年月日" />
            <x-text-input id="birthdate" type="date" name="birthdate" :value="old('birthdate')" required />
            <div class="form-text small">13歳以上の方のみご利用いただけます。</div>
            <x-input-error :messages="$errors->get('birthdate')" />
        </div>

        <div class="mb-3">
            <x-input-label for="password" value="パスワード" />
            <x-text-input id="password" type="password" name="password" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" />
        </div>

        <div class="mb-3">
            <x-input-label for="password_confirmation" value="パスワード（確認）" />
            <x-text-input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" />
        </div>

        <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" id="terms" name="terms" value="1" {{ old('terms') ? 'checked' : '' }} required>
            <label class="form-check-label small" for="terms">
                <a href="{{ route('terms') }}" target="_blank" rel="noopener">利用規約</a>
                および
                <a href="{{ route('privacy') }}" target="_blank" rel="noopener">プライバシーポリシー</a>
                に同意します
            </label>
            <x-input-error :messages="$errors->get('terms')" />
        </div>

        <div class="d-flex justify-content-between align-items-center">
            <a class="small text-decoration-none" href="{{ route('login') }}">既にアカウントをお持ちの方</a>
            <x-primary-button>登録する</x-primary-button>
        </div>
    </form>
</x-guest-layout>
