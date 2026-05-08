<x-guest-layout>
    <h1 class="h5 fw-bold mb-3">メール認証</h1>

    <p class="small text-muted">
        ご登録ありがとうございます。先ほどお送りした認証メールのリンクをクリックして、メールアドレス認証を完了してください。
        メールが届かない場合は、再送ボタンを押してください。
    </p>

    @if (session('status') == 'verification-link-sent')
        <div class="alert alert-success small">
            新しい認証メールを送信しました。
        </div>
    @endif

    <div class="d-flex justify-content-between align-items-center mt-4">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <x-primary-button>認証メールを再送する</x-primary-button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn-link small text-muted">ログアウト</button>
        </form>
    </div>
</x-guest-layout>
