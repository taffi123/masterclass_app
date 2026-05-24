@extends('layouts.app')

@section('title', $type->name)

@section('content')
    <div class="main">
        <div class="row">
            <div class="hover"></div>
            <div class="title">{{ $type->name }}</div>
            <div class="row--small grid between">
                <div class="content">
                    <img src="{{ asset($type->image_path) }}" alt="{{ $type->name }}" style="max-width: 250px; margin-right: 20px;">
                    @foreach(preg_split("/\r\n\r\n|\n\n/", $type->description) as $paragraph)
                        @if(trim($paragraph) !== '')
                            <p>{{ trim($paragraph) }}</p>
                        @endif
                    @endforeach
                </div>
                <ul class="menu">
                    @foreach($menuTypes as $menuType)
                        <li><a href="{{ route('types.show', $menuType) }}">{{ $menuType->name }}</a></li>
                    @endforeach
                </ul>
            </div>

            <div class="row shedule">
                <div class="row--small">
                    <h2>Расписание</h2>
                    <div class="drivers">
                        @foreach($masterClasses as $masterClass)
                            <div class="driver grid">
                                <div class="driver-left grid">
                                    <div class="driver-photo">
                                        <img src="{{ asset($masterClass->instructor->avatar ?? 'driver1.png') }}" alt="{{ $masterClass->instructor->name }}">
                                    </div>
                                    <div class="driver-text">
                                        <div class="driver-name">{{ $masterClass->instructor->name }}</div>
                                        <div class="driver-desc">
                                            <b>{{ $masterClass->title }}</b><br>
                                            {{ $masterClass->description }}<br><br>
                                            Стоимость: {{ number_format((float) $masterClass->price, 0, ',', ' ') }} руб.<br>
                                            Свободных мест: {{ $masterClass->available_places }}
                                        </div>
                                    </div>
                                </div>
                                <div class="driver-right">
                                    @auth
                                        @if(auth()->user()->isVisitor())
                                            @if($masterClass->hasStarted())
                                                <button class="driver-btn" disabled>Запись недоступна</button>
                                            @elseif(!empty($masterClass->is_booked))
                                                <button class="driver-btn" disabled>Вы записаны</button>
                                            @elseif($masterClass->available_places < 1)
                                                <button class="driver-btn" disabled>Мест нет</button>
                                            @else
                                                <a class="driver-btn" href="{{ route('enrollments.confirm', $masterClass) }}">Запись</a>
                                            @endif
                                        @endif
                                    @endauth
                                    <div class="driver-time">
                                        {{ $masterClass->class_date->format('d.m.Y') }}<br>
                                        {{ substr($masterClass->start_time, 0, 5) }} - {{ substr($masterClass->end_time, 0, 5) }}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
