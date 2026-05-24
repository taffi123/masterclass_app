@extends('layouts.app')

@section('title', 'Вход')

@section('top-line')
    <div class="row row--nogutter top-line">
        <div class="line"></div>
    </div>
@endsection

@section('content')
    <div class="main">
        <div class="row">
            <div class="row--small">
                <form method="post" action="{{ route('login.submit') }}">
                    @csrf
                    <h2>Форма авторизации</h2>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" value="{{ old('email') }}">
                    </div>
                    <div class="form-group">
                        <label>Пароль</label>
                        <input type="password" name="password">
                    </div>
                    <div class="form-group">
                        <button class="btn">Войти</button>
                    </div>
                    <div class="form-group">
                        <a href="{{ route('register') }}">Перейти к регистрации</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
