@extends('layouts.app')

@section('title', $masterClass->title)
@section('body-class', 'dp')

@section('content')
    <div class="main">
        <div class="row">
            <div class="hover"></div>
            <div class="title"></div>
            <div class="row--small grid between">
                <div class="content driver-page">
                    <div class="driver-page-photo">
                        <img src="{{ asset($masterClass->instructor->avatar ?? 'driver1.png') }}" alt="{{ $masterClass->instructor->name }}">
                    </div>
                    <div class="driver-page-name">{{ $masterClass->instructor->name }}</div>
                    <div class="driver-page-text">
                        <div class="driver-page-my">{{ $masterClass->title }}</div>
                        <table class="driver-page-table">
                            <tbody>
                                <tr>
                                    <td>{{ $masterClass->schedule_label }}</td>
                                    <td>
                                        <b>{{ $masterClass->creativityType->name }}</b><br>
                                        {{ $masterClass->description }}<br>
                                        Стоимость: {{ number_format((float) $masterClass->price, 0, ',', ' ') }} руб.
                                    </td>
                                </tr>
                                @foreach($masterClass->enrollments as $enrollment)
                                    <tr>
                                        <td>Участник</td>
                                        <td>
                                            <b>{{ $enrollment->user->name }}</b><br>
                                            email: {{ $enrollment->user->email }}<br>
                                            tel: {{ $enrollment->user->phone }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="driver-page-btn-wrapper">
                        <a class="driver-page-btn btn" href="{{ route('cabinet.edit', $masterClass) }}">Редактировать</a>
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
