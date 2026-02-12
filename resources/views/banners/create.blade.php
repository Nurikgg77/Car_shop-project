@extends('layout')

@section('title', 'Новый баннер')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">Добавить рекламный баннер</div>
            <div class="card-body">
                <form action="{{ route('banners.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="mb-3">
                        <label class="form-label">Изображение (желательно широкое, 1200x400)</label>
                        <input type="file" name="image" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Заголовок (необязательно)</label>
                        <input type="text" name="title" class="form-control" placeholder="Скидки на BMW!">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Текст (необязательно)</label>
                        <input type="text" name="text" class="form-control" placeholder="Успейте купить до конца месяца">
                    </div>

                    <button class="btn btn-success w-100">Загрузить</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection