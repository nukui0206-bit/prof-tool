<section>
    <header class="mb-3">
        <h2 class="h5 fw-bold mb-1">基本情報</h2>
        <p class="small text-muted mb-0">アカウントの名前・メールアドレスを変更できます。</p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}">
        @csrf
        @method('patch')

        <div class="mb-3">
            <x-input-label for="name" value="ニックネーム" />
            <x-text-input id="name" name="name" type="text" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" />
        </div>

        <div class="mb-3">
            <x-input-label for="email" value="メールアドレス" />
            <x-text-input id="email" name="email" type="email" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="alert alert-warning small mt-2 mb-0">
                    メールアドレスが未認証です。
                    <button form="send-verification" class="btn btn-link btn-sm p-0 align-baseline">認証メールを再送する</button>

                    @if (session('status') === 'verification-link-sent')
                        <div class="text-success small mt-1">認証メールを送信しました。</div>
                    @endif
                </div>
            @endif
        </div>

        <div class="d-flex align-items-center gap-3">
            <x-primary-button>保存</x-primary-button>

            @if (session('status') === 'profile-updated')
                <span class="text-success small">保存しました</span>
            @endif
        </div>
    </form>
</section>
