<x-guest-layout>
    <h1 class="h5 fw-bold mb-3">パスワード再設定</h1>

    <p class="small text-muted">
        ご登録のメールアドレスを入力してください。パスワード再設定用のリンクをお送りします。
    </p>

    <x-auth-session-status class="mb-3" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <div class="mb-3">
            <x-input-label for="email" value="メールアドレス" />
            <x-text-input id="email" type="email" name="email" :value="old('email')" required autofocus />
            <x-input-error :messages="$errors->get('email')" />
        </div>

        <div class="d-flex justify-content-end">
            <x-primary-button>再設定リンクを送る</x-primary-button>
        </div>
    </form>
</x-guest-layout>
