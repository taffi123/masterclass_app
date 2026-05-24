@extends('layouts.app')

@section('title', 'Главная')

@section('content')
    <div class="main">
        <div class="row">
            <div class="hover"></div>
            <div class="title">Виды творчества</div>
            <div class="row--small grid between">
                <div class="content">
                    <img src="{{ asset('elifant.png') }}" alt="Очумелые ручки">
                    <p>Компания «Очумелые ручки» имеет сайт для обмена информацией с потенциальными или актуальными клиентами. На сайте можно познакомиться с творческими направлениями и выбрать подходящий мастер-класс.</p>
                    <p>Для каждого направления доступны описание, расписание занятий, стоимость и количество свободных мест. После авторизации посетитель может записаться на занятие, а ведущий мастер-класса получает доступ к личному кабинету.</p>
                    <p><span>Основные роли пользователей:</span> посетитель и ведущий мастер-класса.</p>
                    @auth
                        @if(auth()->user()->isVisitor() && $myEnrollments->isNotEmpty())
                            <h2 style="margin-top: 30px;">Мои записи</h2>
                            @foreach($myEnrollments as $enrollment)
                                <p style="text-indent: 0;">
                                    <span>{{ $enrollment->masterClass->title }}</span><br>
                                    {{ $enrollment->masterClass->creativityType->name }}<br>
                                    {{ $enrollment->masterClass->schedule_label }}<br>
                                    Ведущий: {{ $enrollment->masterClass->instructor->name }}
                                </p>
                                <form method="post" action="{{ route('enrollments.destroy', $enrollment) }}" style="margin: -10px 0 20px; text-indent: 0;">
                                    @csrf
                                    @method('delete')
                                    <button
                                        type="submit"
                                        class="btn"
                                        onclick="return confirm('Удалить эту запись?')"
                                    >
                                        Удалить запись
                                    </button>
                                </form>
                            @endforeach
                        @endif
                    @endauth
                </div>
                <ul class="menu">
                    @foreach($menuTypes as $menuType)
                        <li><a href="{{ route('types.show', $menuType) }}">{{ $menuType->name }}</a></li>
                    @endforeach
                    @auth
                        @if(auth()->user()->isInstructor())
                            <li><a href="{{ route('cabinet.index') }}">Личный кабинет</a></li>
                        @endif
                    @endauth
                </ul>
            </div>
        </div>
    </div>
@endsection
