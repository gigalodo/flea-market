<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance Management</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
    <link rel="stylesheet" href="{{ asset('css/common.css') }}">
    @yield('css')
</head>

<body>
    <header class="header">
        <div class="header__inner">
            <div class="header-utilities">
                <a class="header__logo" href="/">
                    <img src="{{ asset('images/logo.svg') }}" alt="CoachTechロゴ" class="header__logo-image">
                </a>
                @if (!request()->is('login') && !request()->is('register'))
                <div class="header__search">
                    <form action="/" method="post">
                        @csrf
                        <input type="text" name="search_word" value="{{ $search_word ?? '' }}" placeholder="何をお探しですか？">
                    </form>
                </div>
                <nav>
                    <ul class="header-nav">

                        @if (Auth::check())
                        <li class="header-nav__item">
                            <form action="/logout" method="post">
                                @csrf
                                <button class="header-nav__button">ログアウト</button>
                            </form>
                        </li>
                        @else
                        <li class="header-nav__item">
                            <a href="/login"><button class="header-nav__button">ログイン</button></a>
                        </li>
                        @endif

                        <li class="header-nav__item">
                            <a class="header-nav__link" href="/mypage">マイページ</a>
                        </li>

                        <li class="header-nav__item">
                            <a class="header-nav__link_sell" href="/sell">出品</a>
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