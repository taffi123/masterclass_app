@extends('layouts.app')

@section('title', 'Редактирование мастер-класса')

@section('top-line')
    <div class="row row--nogutter top-line">
        <div class="line"></div>
    </div>
@endsection

@section('content')
    <div class="main">
        <div class="row">
            <div class="row--small">
                <form method="post" action="{{ route('cabinet.update', $masterClass) }}">
                    @csrf
                    @method('put')
                    <h2>Редактирование мастер-класса</h2>
                    <div class="form-group">
                        <label>Название</label>
                        <input type="text" value="{{ $masterClass->title }}" disabled>
                    </div>
                    <div class="form-group">
                        <label>Описание мастер-класса</label>
                        <textarea name="description">{{ old('description', $masterClass->description) }}</textarea>
                    </div>
                    <div class="form-group">
                        <label>Стоимость мастер-класса</label>
                        <input type="number" step="0.01" name="price" value="{{ old('price', $masterClass->price) }}">
                    </div>
                    <div class="form-group" style="display:flex; gap: 12px; align-items:center; flex-wrap:wrap;">
                        <button class="btn" type="submit">Сохранить</button>
                    </div>
                </form>

                <form method="post" action="{{ route('cabinet.destroy', $masterClass) }}" onsubmit="return confirm('Удалить этот мастер-класс?');">
                    @csrf
                    @method('delete')
                    <div class="form-group">
                        <button class="btn" type="submit">Удалить мастер-класс</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
