<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>プライバシーポリシー — {{ config('app.name', 'ProfTool') }}</title>
        @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    </head>
    <body class="d-flex flex-column min-vh-100">
        <main class="flex-grow-1 py-4">
            <div class="container" style="max-width: 720px;">
                <h1 class="h3 fw-bold mb-4">プライバシーポリシー</h1>

                <div class="alert alert-warning small">
                    本ページは Phase 1 時点のプレースホルダーです。Phase 11 で正式なプライバシーポリシーに差し替えます。
                </div>

                <h2 class="h5 fw-bold mt-4">取得する情報</h2>
                <p class="small text-muted">
                    メールアドレス、ニックネーム、生年月日、プロフィール内容、SNSリンク、アクセスログなど、サービス運営に必要な情報を取得します。
                </p>

                <h2 class="h5 fw-bold mt-4">利用目的</h2>
                <p class="small text-muted">
                    本人認証、サービス提供、不正利用防止、利用状況の分析、ご連絡のため利用します。
                </p>

                <h2 class="h5 fw-bold mt-4">第三者提供</h2>
                <p class="small text-muted">
                    法令に基づく場合を除き、ご本人の同意なく第三者に提供しません。
                </p>

                <p class="mt-5"><a href="/" class="small">トップへ戻る</a></p>
            </div>
        </main>
    </body>
</html>
