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
    @php
        $themeKey = $profile->theme?->key ?? 'modern';
        $accentStyle = $profile->theme_color ? "--pt-public-accent: {$profile->theme_color};" : '';
    @endphp
    <body class="d-flex flex-column min-vh-100 profile-public-page theme-{{ $themeKey }}"
          @if($accentStyle) style="{{ $accentStyle }}" @endif>
        <main class="flex-grow-1 py-5">
            <div class="container" style="max-width: 36rem;">
                <div class="card border-0 shadow-sm pt-public-card">
                    <div class="card-body p-4 p-md-5 text-center">
                        @if ($profile->avatar_path)
                            <img src="{{ Storage::url($profile->avatar_path) }}"
                                 alt="{{ $profile->nickname }} のアイコン"
                                 class="rounded-circle mx-auto mb-3 d-block"
                                 style="width: 88px; height: 88px; object-fit: cover; border: 1px solid var(--pt-public-card-border);">
                        @else
                            <div class="mx-auto mb-3 d-flex align-items-center justify-content-center rounded-circle text-white fw-bold pt-public-avatar"
                                 style="width: 88px; height: 88px; font-size: 2rem;">
                                {{ mb_substr($profile->nickname, 0, 1) }}
                            </div>
                        @endif

                        <h1 class="h3 fw-bold mb-1">{{ $profile->nickname }}</h1>
                        <p class="small pt-public-muted mb-3">@{{ $profile->slug }}</p>

                        @if ($profile->bio)
                            <p style="white-space: pre-wrap;">{{ $profile->bio }}</p>
                        @else
                            <p class="small pt-public-muted fst-italic">まだ自己紹介が登録されていません。</p>
                        @endif

                        <div class="d-flex justify-content-center gap-3 small pt-public-muted mt-4 pt-3 border-top" style="border-color: var(--pt-public-card-border) !important;">
                            <span><i class="bi bi-eye"></i> {{ number_format($profile->view_count) }}</span>
                            <span><i class="bi bi-heart"></i> {{ number_format($profile->like_count) }}</span>
                        </div>
                    </div>
                </div>

                @if ($profile->socialLinks->isNotEmpty())
                    <div class="card border-0 shadow-sm mt-4 pt-public-card">
                        <div class="card-body p-4 p-md-5">
                            <h2 class="h6 fw-bold pt-public-muted mb-3 text-center" style="letter-spacing: 0.1em;">LINKS</h2>
                            <div class="d-grid gap-2">
                                @foreach ($profile->socialLinks as $link)
                                    <a href="{{ $link->url }}" target="_blank" rel="noopener noreferrer"
                                       class="btn text-start d-inline-flex align-items-center gap-3 py-2 pt-public-link-btn">
                                        <i class="bi {{ $link->iconClass() }} fs-5"></i>
                                        <span class="fw-semibold flex-grow-1">{{ $link->displayLabel() }}</span>
                                        <i class="bi bi-arrow-up-right small pt-public-muted"></i>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                @if ($profile->favorites->isNotEmpty())
                    <div class="card border-0 shadow-sm mt-4 pt-public-card">
                        <div class="card-body p-4 p-md-5">
                            <h2 class="h6 fw-bold pt-public-muted mb-3 text-center" style="letter-spacing: 0.1em;">TAGS</h2>
                            <div class="d-flex flex-wrap gap-2 justify-content-center">
                                @foreach ($profile->favorites as $fav)
                                    <span class="badge rounded-pill px-3 py-2 pt-public-tag" style="font-weight: 600; font-size: 0.9rem;">
                                        <span class="opacity-75">#</span>{{ $fav->label }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                @php($answers = $profile->answers->sortBy(fn ($a) => $a->question->sort_order ?? 999))

                @if ($answers->isNotEmpty())
                    <div class="card border-0 shadow-sm mt-4 pt-public-card">
                        <div class="card-body p-4 p-md-5">
                            <h2 class="h6 fw-bold pt-public-muted mb-4 text-center" style="letter-spacing: 0.1em;">Q &amp; A</h2>
                            @foreach ($answers as $answer)
                                <div class="mb-3 pb-3 {{ $loop->last ? 'mb-0 pb-0' : 'border-bottom' }}" style="border-color: var(--pt-public-card-border) !important;">
                                    <div class="small fw-semibold mb-1 pt-public-q">
                                        Q. {{ $answer->question->body }}
                                    </div>
                                    <div style="white-space: pre-wrap;">{{ $answer->body }}</div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <p class="text-center mt-4 mb-0 small pt-public-muted">
                    <a href="/" class="text-decoration-none pt-public-muted">{{ config('app.name', 'ProfTool') }}</a> で作られたプロフィール
                </p>
            </div>
        </main>
    </body>
</html>
