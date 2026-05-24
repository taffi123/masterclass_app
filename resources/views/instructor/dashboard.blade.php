@extends('layouts.app')

@section('title', 'Личный кабинет')
@section('body-class', 'dp')

@section('content')
    <div class="main">
        <div class="row">
            <div class="hover"></div>
            <div class="title"></div>
            <div class="row--small grid between">
                <div class="content driver-page">
                    <div class="driver-page-photo">
                        <img src="{{ asset(auth()->user()->avatar ?? 'driver1.png') }}" alt="{{ auth()->user()->name }}">
                    </div>
                    <div class="driver-page-name">{{ auth()->user()->name }}</div>
                    <div class="driver-page-text">
                        <div class="driver-page-my">Мои мастер-классы</div>
                        <table class="driver-page-table">
                            <tbody>
                                @foreach($masterClasses as $masterClass)
                                    <tr>
                                        <td>{{ $masterClass->schedule_label }}</td>
                                        <td>
                                            <b>{{ $masterClass->title }}</b><br>
                                            {{ $masterClass->creativityType->name }}<br>
                                            Участников: {{ $masterClass->enrollments_count }} / {{ $masterClass->max_participants }}<br>
                                            <a href="{{ route('cabinet.show', $masterClass) }}">Посмотреть участников</a><br>
                                            <a href="{{ route('cabinet.edit', $masterClass) }}">Редактировать</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="driver-page-btn-wrapper">
                        <a class="driver-page-btn btn" href="{{ route('cabinet.create') }}">Добавить мастер-класс</a>
                    </div>
                </div>
                <ul class="menu">
                    @foreach($menuTypes as $menuType)
                        <li><a href="{{ route('types.show', $menuType) }}">{{ $menuType->name }}</a></li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
@endsection
