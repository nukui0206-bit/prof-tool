<nav class="navbar navbar-expand-lg">
    <div class="container">
        <a class="navbar-brand" href="{{ route('dashboard') }}">
            <x-application-logo />
        </a>

        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain" aria-controls="navbarMain" aria-expanded="false" aria-label="メニュー切替">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarMain">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        マイページ
                    </x-nav-link>
                </li>
                @auth
                    <li class="nav-item">
                        <x-nav-link :href="route('mypage.profile.edit')" :active="request()->routeIs('mypage.profile.*')">
                            プロフィール編集
                        </x-nav-link>
                    </li>
                    <li class="nav-item">
                        <x-nav-link :href="route('mypage.answers.edit')" :active="request()->routeIs('mypage.answers.*')">
                            質問への回答
                        </x-nav-link>
                    </li>
                    <li class="nav-item">
                        <x-nav-link :href="route('mypage.favorites.index')" :active="request()->routeIs('mypage.favorites.*')">
                            マイタグ
                        </x-nav-link>
                    </li>
                @endauth
            </ul>

            <ul class="navbar-nav ms-auto align-items-lg-center">
                @auth
                    @php($navProfile = Auth::user()->profile)
                    <li class="nav-item dropdown">
                        <x-dropdown align="right">
                            <x-slot name="trigger">
                                <span class="nav-link dropdown-toggle d-inline-flex align-items-center gap-2">
                                    @if ($navProfile && $navProfile->avatar_path)
                                        <img src="{{ Storage::url($navProfile->avatar_path) }}"
                                             alt="avatar"
                                             class="rounded-circle"
                                             style="width: 28px; height: 28px; object-fit: cover;">
                                    @else
                                        <span class="d-inline-flex align-items-center justify-content-center rounded-circle text-white" style="width: 28px; height: 28px; background: var(--pt-gradient); font-size: 0.75rem; font-weight: 700;">
                                            {{ mb_substr($navProfile->nickname ?? Auth::user()->name, 0, 1) }}
                                        </span>
                                    @endif
                                    <span>{{ $navProfile->nickname ?? Auth::user()->name }}</span>
                                </span>
                            </x-slot>

                            <x-slot name="content">
                                <x-dropdown-link :href="route('mypage.profile.edit')">
                                    プロフィール編集
                                </x-dropdown-link>
                                <x-dropdown-link :href="route('mypage.answers.edit')">
                                    質問への回答
                                </x-dropdown-link>
                                <x-dropdown-link :href="route('mypage.favorites.index')">
                                    マイタグ
                                </x-dropdown-link>
                                <x-dropdown-link :href="route('profile.edit')">
                                    アカウント設定
                                </x-dropdown-link>
                                <hr class="my-1">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <x-dropdown-link :href="route('logout')"
                                            onclick="event.preventDefault(); this.closest('form').submit();">
                                        ログアウト
                                    </x-dropdown-link>
                                </form>
                            </x-slot>
                        </x-dropdown>
                    </li>
                @else
                    <li class="nav-item"><a class="nav-link px-3" href="{{ route('login') }}">ログイン</a></li>
                    <li class="nav-item"><a class="btn btn-primary ms-lg-2" href="{{ route('register') }}">無料ではじめる</a></li>
                @endauth
            </ul>
        </div>
    </div>
</nav>
