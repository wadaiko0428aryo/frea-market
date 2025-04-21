<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>coachtechフリマ</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    @yield('css')
</head>
<body>
    <div class="header">
        <div class="header-inner">
            <div class="header-title">
                <a href="{{ route('topPage') }}" class="top-back">
                    <img src="{{ asset('images/logo.svg') }}" alt="coachtech" class="header-logo">
                </a>
            </div>

            @if (Request::is('login') || Request::is('register') || Request::is('mailCheck'))
                {{-- ログイン・新規登録ページではタイトル非表示 --}}

            @else
                {{-- ログイン後のヘッダー（検索欄・ログアウトボタン付き） --}}
                <div class="header-search">
                    <form action="{{ route('search') }}" method="get">
                        @csrf
                        <input type="text" name="name" id="name" placeholder="なにをお探しですか？" class="search-form"value="{{ request('name') }}">
                        <input type="submit" value="検索" class="search-submit" >
                        @if(request('name'))
                            <a href="{{ route('topPage') }}" class="reset-button">リセット</a>
                        @endif
                    </form>
                </div>

            @if(Auth::check())
                <div class="header-logout">
                    <form action="/logout" method="post">
                        @csrf
                        <input type="submit" value="ログアウト" class="logout-btn">
                    </form>
                </div>
            @else
                <div class="header-logout">
                    <form action="/login" method="get">
                        @csrf
                        <input type="submit" value="ログイン" class="logout-btn">
                    </form>
                </div>
            @endif

                <div class="header-mypage">
                    <a href="{{ Auth::check() ? route('myPage') : route('nothing') }}" class="myPage">マイページ</a>
                </div>
                <div class="header-sell">
                    <a href="{{ Auth::check() ? route('sell') : route('nothing') }}" class="sell">出品</a>
                </div>
        @endif
        </div>
    </div>

    <div class="content">
        @yield('content')
    </div>
</body>
</html>