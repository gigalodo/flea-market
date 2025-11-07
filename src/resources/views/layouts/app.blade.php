<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>flea market</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
    <link rel="stylesheet" href="{{ asset('css/common.css') }}">
    @yield('css')
</head>

<body>
    <header class="header">
        <div class="header__inner">
            <div class="header__utilities">
                <a class="header__logo" href="/">
                    <img src="{{ asset('images/logo.svg') }}" alt="CoachTechロゴ" class="header__logo-image">
                </a>
                @if (!request()->is('login') && !request()->is('register'))
                <div class="header__search">
                    <form class="header__search-form" action="/" method="post">
                        @csrf
                        <input class="header__search-input" type="text" name="search_word" value="{{ $search_word ?? '' }}" placeholder="何をお探しですか？">
                    </form>
                </div>
                <nav>
                    <ul class="header__nav">

                        @if (Auth::check())
                        <li class="header__nav-item">
                            <form class="header__nav-form" action="/logout" method="post">
                                @csrf
                                <button class="header__nav-button">ログアウト</button>
                            </form>
                        </li>
                        @else
                        <li class="header__nav-item">
                            <a href="/login">
                                <button class="header__nav-button">ログイン</button>
                            </a>
                        </li>
                        @endif

                        <li class="header__nav-item">
                            <a class="header__nav-link" href="/mypage">マイページ</a>
                        </li>

                        <li class="header__nav-item">
                            <a class="header__nav-link--sell" href="/sell">出品</a>
                        </li>

                    </ul>
                </nav>
                @endif
            </div>
        </div>
    </header>

    <main>
        @yield('content')
    </main>
</body>

</html>