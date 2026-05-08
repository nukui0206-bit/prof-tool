<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>利用規約 — {{ config('app.name', 'ProfTool') }}</title>
        @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    </head>
    <body class="d-flex flex-column min-vh-100">
        <main class="flex-grow-1 py-4">
            <div class="container" style="max-width: 720px;">
                <h1 class="h3 fw-bold mb-4">利用規約</h1>

                <div class="alert alert-warning small">
                    本ページは Phase 1 時点のプレースホルダーです。Phase 11 で正式な利用規約に差し替えます。
                </div>

                <h2 class="h5 fw-bold mt-4">第1条（適用）</h2>
                <p class="small text-muted">
                    本規約は、当サービスの利用に関する一切の関係に適用されます。
                </p>

                <h2 class="h5 fw-bold mt-4">第2条（利用登録）</h2>
                <p class="small text-muted">
                    13歳未満の方はご利用いただけません。利用登録は、本規約に同意の上、当サービスの定める方法で申請するものとします。
                </p>

                <h2 class="h5 fw-bold mt-4">第3条（禁止事項）</h2>
                <p class="small text-muted">
                    法令違反、第三者の権利侵害、運営妨害、誹謗中傷、なりすまし等の行為を禁止します。
                </p>

                <p class="mt-5"><a href="/" class="small">トップへ戻る</a></p>
            </div>
        </main>
    </body>
</html>
