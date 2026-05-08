<section>
    <header class="mb-3">
        <h2 class="h5 fw-bold text-danger mb-1">アカウント削除</h2>
        <p class="small text-muted mb-0">
            アカウントを削除すると、関連するすべてのデータが永久に消去されます。実行前に必要なデータをエクスポートしてください。
        </p>
    </header>

    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteAccountModal">
        アカウントを削除する
    </button>

    <div class="modal fade" id="deleteAccountModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form method="post" action="{{ route('profile.destroy') }}">
                    @csrf
                    @method('delete')

                    <div class="modal-header">
                        <h5 class="modal-title">本当にアカウントを削除しますか？</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="閉じる"></button>
                    </div>
                    <div class="modal-body">
                        <p class="small text-muted">
                            削除を確定するためにパスワードを入力してください。一度削除すると元に戻せません。
                        </p>
                        <div class="mb-3">
                            <x-input-label for="password" value="パスワード" />
                            <x-text-input id="password" name="password" type="password" placeholder="パスワード" />
                            <x-input-error :messages="$errors->userDeletion->get('password')" />
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">キャンセル</button>
                        <button type="submit" class="btn btn-danger">削除する</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
