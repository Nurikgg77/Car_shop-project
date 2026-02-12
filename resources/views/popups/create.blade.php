@extends('layout')

@section('title', 'Добавить Popup')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow">
            <div class="card-header">Загрузить картинку для рекламы</div>
            <div class="card-body">
                <form action="{{ route('popups.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Выберите изображение</label>
                        <input type="file" name="image" class="form-control" required accept="image/*">
                        <div class="form-text">Лучше использовать квадратные или вертикальные картинки.</div>
                    </div>
                    <button class="btn btn-success w-100">Сохранить</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection