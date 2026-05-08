<x-guest-layout>
    <h1 class="h5 fw-bold mb-3">パスワード再確認</h1>

    <p class="small text-muted">
        この操作は重要です。続行する前にパスワードを再入力してください。
    </p>

    <form method="POST" action="{{ route('password.confirm') }}">
        @csrf

        <div class="mb-3">
            <x-input-label for="password" value="パスワード" />
            <x-text-input id="password" type="password" name="password" required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" />
        </div>

        <div class="d-flex justify-content-end">
            <x-primary-button>確認</x-primary-button>
        </div>
    </form>
</x-guest-layout>
