@extends('layouts.app')

@section('title', 'Подтверждение записи')

@section('top-line')
    <div class="row row--nogutter top-line">
        <div class="line"></div>
    </div>
@endsection

@section('content')
    <div class="main">
        <div class="row">
            <div class="row--small">
                <form method="post" action="{{ route('enrollments.store', $masterClass) }}">
                    @csrf
                    <h2>Подтверждение записи</h2>
                    <div class="form-group">
                        <label>ФИО пользователя</label>
                        <input type="text" value="{{ auth()->user()->name }}" disabled>
                    </div>
                    <div class="form-group">
                        <label>Вид творчества</label>
                        <input type="text" value="{{ $masterClass->creativityType->name }}" disabled>
                    </div>
                    <div class="form-group">
                        <label>ФИО мастера</label>
                        <input type="text" value="{{ $masterClass->instructor->name }}" disabled>
                    </div>
                    <div class="form-group">
                        <label>Дата и время</label>
                        <input type="text" value="{{ $masterClass->schedule_label }}" disabled>
                    </div>
                    <div class="form-group">
                        <button class="btn">Подтвердить</button>
                    </div>
                </form>
                <form method="post" action="{{ route('enrollments.cancel', $masterClass) }}">
                    @csrf
                    <div class="form-group">
                        <button class="btn" type="submit">Отмена</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
