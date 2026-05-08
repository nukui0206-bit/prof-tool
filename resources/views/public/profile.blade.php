<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ $profile->nickname }} — {{ config('app.name', 'ProfTool') }}</title>

        <meta property="og:title" content="{{ $profile->nickname }} のプロフィール">
        <meta property="og:description" content="{{ \Illuminate\Support\Str::limit($profile->bio ?? '', 80) }}">
        <meta property="og:type" content="profile">
        <meta property="og:url" content="{{ $profile->public_url }}">
        <meta name="twitter:card" content="summary">

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@500;600;700;800&family=Noto+Sans+JP:wght@400;500;700&display=swap" rel="stylesheet">

        @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    </head>
    <body class="d-flex flex-column min-vh-100 pt-lp-bg">
        <main class="flex-grow-1 py-5">
            <div class="container" style="max-width: 36rem;">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4 p-md-5 text-center">
                        {{-- Avatar placeholder（Phase 3 で画像対応） --}}
                        <div class="mx-auto mb-3 d-flex align-items-center justify-content-center rounded-circle text-white fw-bold"
                             style="width: 88px; height: 88px; background: var(--pt-gradient); font-size: 2rem;">
                            {{ mb_substr($profile->nickname, 0, 1) }}
                        </div>

                        <h1 class="h3 fw-bold mb-1">{{ $profile->nickname }}</h1>
                        <p class="small text-muted mb-3">@{{ $profile->slug }}</p>

                        @if ($profile->bio)
                            <p class="text-body" style="white-space: pre-wrap;">{{ $profile->bio }}</p>
                        @else
                            <p class="text-muted small fst-italic">まだ自己紹介が登録されていません。</p>
                        @endif

                        <div class="d-flex justify-content-center gap-3 small text-muted mt-4 pt-3 border-top">
                            <span><i class="bi bi-eye"></i> {{ number_format($profile->view_count) }}</span>
                            <span><i class="bi bi-heart"></i> {{ number_format($profile->like_count) }}</span>
                        </div>
                    </div>
                </div>

                <p class="text-center mt-4 mb-0 small text-muted">
                    <a href="/" class="text-decoration-none">{{ config('app.name', 'ProfTool') }}</a> で作られたプロフィール
                </p>
            </div>
        </main>
    </body>
</html>
