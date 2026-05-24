<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <title>@yield('title', 'Очумелые ручки')</title>
    <link rel="stylesheet" type="text/css" href="{{ asset('styles.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('responsive.css') }}">
</head>
<body class="@yield('body-class')">
    <div class="header">
        <div class="row grid middle between">
            <div class="logo">
                <a href="{{ route('home') }}"><img src="{{ asset('logo.png') }}" alt="Логотип"></a>
            </div>
            <div class="title">Клуб любителей творчества «Очумелые ручки»</div>
            <div class="auth">
                @auth
                    @if(auth()->user()->isInstructor())
                        <a href="{{ route('cabinet.index') }}">Кабинет</a>
                    @else
                        <a href="{{ route('home') }}">Главная</a>
                    @endif
                    <form action="{{ route('logout') }}" method="post" style="display:inline;">
                        @csrf
                        <button type="submit" style="background:none;border:none;padding:0 0 0 20px;color:#00044c;font-weight:bold;cursor:pointer;">Выход</button>
                    </form>
                @else
                    <a href="{{ route('login') }}">Вход</a>
                @endauth
            </div>
        </div>
    </div>
    <div class="row row--nogutter">
        <div class="menu-burger">
            <div class="burger">
                <div></div>
                <div></div>
                <div></div>
            </div>
        </div>
    </div>
    @yield('top-line')
    @if (session('status'))
        <div class="row" style="padding: 10px 30px; color: #20416c; font-weight: bold;">{{ session('status') }}</div>
    @endif
    @if ($errors->any())
        <div class="row" style="padding: 10px 30px; color: #9b0000; font-weight: bold;">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif
    @yield('content')
    <div class="row row--nogutter">
        <div class="line"></div>
    </div>
    <div class="footer">
        <div class="row">
            <div class="row--small grid between">
                <div class="address">Наш адрес: ВДНХ, 120В</div>
                <div class="tel">Тел: 89123456765</div>
                <div class="copy">(с) Copyright, 2017</div>
            </div>
        </div>
    </div>
</body>
</html>
