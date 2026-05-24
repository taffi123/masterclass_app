@extends('layouts.app')

@section('title', 'Регистрация')

@section('top-line')
    <div class="row row--nogutter top-line">
        <div class="line"></div>
    </div>
@endsection

@section('content')
    <div class="main">
        <div class="row">
            <div class="row--small">
                <form method="post" action="{{ route('register.submit') }}">
                    @csrf
                    <h2>Форма регистрации</h2>
                    <div class="form-group">
                        <label>ФИО</label>
                        <input type="text" name="name" value="{{ old('name') }}" placeholder="Иванов Иван">
                        <div style="margin-top: 8px; color: #20416c; font-size: 12px;">Минимум 2 слова, каждое с большой буквы.</div>
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" placeholder="example@mail.ru">
                    </div>
                    <div class="form-group">
                        <label>Пароль</label>
                        <input type="password" name="password" placeholder="Минимум 6 символов">
                        <div style="margin-top: 8px; color: #20416c; font-size: 12px;">Минимум 6 символов, 1 заглавная буква и 1 цифра.</div>
                    </div>
                    <div class="form-group">
                        <label>Подтверждение пароля</label>
                        <input type="password" name="password_confirmation">
                    </div>
                    <div class="form-group">
                        <label>Номер телефона</label>
                        <input type="tel" name="phone" value="{{ old('phone') }}" placeholder="+7 (999) 123-45-67">
                        <div style="margin-top: 8px; color: #20416c; font-size: 12px;">Допустимы форматы: +7, 7 или 8.</div>
                    </div>
                    <div class="form-group">
                        <button class="btn">Отправить</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
