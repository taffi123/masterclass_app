@extends('layouts.app')

@section('title', 'Добавление мастер-класса')

@section('top-line')
    <div class="row row--nogutter top-line">
        <div class="line"></div>
    </div>
@endsection

@section('content')
    <div class="main">
        <div class="row">
            <div class="row--small">
                <form method="post" action="{{ route('cabinet.store') }}">
                    @csrf
                    <h2>Форма добавления мастер-класса</h2>
                    <div class="form-group">
                        <label>Вид творчества</label>
                        <select name="creativity_type_id">
                            @foreach($types as $type)
                                <option value="{{ $type->id }}" @selected((int) old('creativity_type_id', request('creativity_type_id')) === $type->id)>{{ $type->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Название мастер-класса</label>
                        <input type="text" name="title" value="{{ old('title', request('title')) }}">
                    </div>
                    <div class="form-group">
                        <label>Описание мастер-класса</label>
                        <textarea name="description">{{ old('description', request('description')) }}</textarea>
                    </div>
                    <div class="form-group">
                        <label>Дата</label>
                        <input type="date" name="class_date" value="{{ old('class_date', $selectedDate) }}" min="{{ now()->format('Y-m-d') }}">
                    </div>
                    <div class="form-group">
                        <button
                            class="btn"
                            type="submit"
                            formaction="{{ route('cabinet.create') }}"
                            formmethod="get"
                        >
                            Показать доступное время
                        </button>
                    </div>
                    <div class="form-group">
                        <label>Время</label>
                        <select name="start_time">
                            @foreach(['09:00', '11:00', '13:00', '15:00'] as $slot)
                                <option value="{{ $slot }}" @selected(old('start_time', request('start_time')) === $slot) @disabled(in_array($slot, $disabledSlots, true))>
                                    {{ $slot }}-{{ \Carbon\Carbon::createFromFormat('H:i', $slot)->addHours(2)->format('H:i') }}
                                    @if(in_array($slot, $disabledSlots, true)) (недоступно) @endif
                                </option>
                            @endforeach
                        </select>
                        <div style="margin-top: 8px; color: #20416c; font-size: 12px;">Для выбранной даты уже занятые и прошедшие слоты отображаются как неактивные.</div>
                    </div>
                    <div class="form-group">
                        <label>Количество человек в группе</label>
                        <input type="number" name="max_participants" value="{{ old('max_participants', request('max_participants', 8)) }}">
                    </div>
                    <div class="form-group">
                        <label>Стоимость мастер-класса</label>
                        <input type="number" step="0.01" name="price" value="{{ old('price', request('price', 2500)) }}">
                    </div>
                    <div class="form-group">
                        <button class="btn" type="submit">Отправить</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
