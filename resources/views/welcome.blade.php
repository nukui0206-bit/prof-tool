<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'ProfTool') }} — 現代版・前略プロフィール</title>
        @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    </head>
    <body class="d-flex flex-column min-vh-100">
        <nav class="navbar navbar-expand-lg bg-white border-bottom shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="/">
                    <x-application-logo />
                </a>
                <ul class="navbar-nav ms-auto align-items-lg-center">
                    @auth
                        <li class="nav-item"><a class="btn btn-outline-primary" href="{{ url('/dashboard') }}">マイページ</a></li>
                    @else
                        <li class="nav-item me-lg-2"><a class="nav-link" href="{{ route('login') }}">ログイン</a></li>
                        <li class="nav-item"><a class="btn btn-primary" href="{{ route('register') }}">新規登録</a></li>
                    @endauth
                </ul>
            </div>
        </nav>

        <main class="flex-grow-1">
            <section class="py-5 text-center" style="background: linear-gradient(180deg, #fff8fc 0%, #ffe6f5 100%);">
                <div class="container py-4">
                    <h1 class="display-5 fw-bold mb-3" style="font-family: 'Hiragino Mincho ProN', serif; color: #d6377f;">
                        ✿ あなたのプロフ、もっと自由に。
                    </h1>
                    <p class="lead text-muted mb-4">
                        平成の前略プロフィールを、いまのSNS時代へ。<br>
                        好き・推し・自己紹介を、1ページに全部のせ。
                    </p>
                    <a href="{{ route('register') }}" class="btn btn-primary btn-lg px-4 me-2">いますぐ作る</a>
                    <a href="{{ route('login') }}" class="btn btn-outline-secondary btn-lg px-4">ログイン</a>
                </div>
            </section>

            <section class="py-5">
                <div class="container">
                    <div class="row g-4">
                        <div class="col-md-4">
                            <div class="card h-100 border-0 shadow-sm">
                                <div class="card-body">
                                    <h2 class="h5 fw-bold">★ 質問テンプレで遊ぶ</h2>
                                    <p class="small text-muted mb-0">
                                        「最近ハマってるもの」「平成で一番好きな曲」など、運営が用意した質問に答えるだけで、あなたらしいプロフが完成。
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card h-100 border-0 shadow-sm">
                                <div class="card-body">
                                    <h2 class="h5 fw-bold">♡ 好き・推し・SNSをまとめて</h2>
                                    <p class="small text-muted mb-0">
                                        好きなものリスト・SNSリンク・自己紹介を1ページに。Lit.linkのように使えて、もっと自由。
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card h-100 border-0 shadow-sm">
                                <div class="card-body">
                                    <h2 class="h5 fw-bold">♪ 世界観を選ぶ</h2>
                                    <p class="small text-muted mb-0">
                                        平成ピンク／ガラケー緑／推し活ネオン／パステルなど、テーマで世界観を切り替え。
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </main>

        <footer class="border-top bg-white py-3">
            <div class="container small text-muted text-center">
                © {{ date('Y') }} ProfTool
            </div>
        </footer>
    </body>
</html>
