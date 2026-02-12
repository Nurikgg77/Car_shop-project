@extends('admin_layout')

@section('title', 'Добавить автомобиль')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow border-0">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">Добавить новый автомобиль</h4>
            </div>
            <div class="card-body p-4">
                
                <!-- Вывод ошибок валидации -->
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- ВАЖНО: enctype для загрузки файлов -->
                <form action="{{ route('cars.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="brand" class="form-label">Марка <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="brand" id="brand" placeholder="Toyota" value="{{ old('brand') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label for="model" class="form-label">Модель <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="model" id="model" placeholder="Camry" value="{{ old('model') }}" required>
                        </div>
                    </div>

                    <!-- Блок ФОТО -->
                    <div class="p-3 mb-4 bg-light rounded border">
                        <h6 class="fw-bold mb-3">📸 Фотографии</h6>
                        
                        <!-- 1. Главное фото -->
                        <div class="mb-3">
                            <label for="image" class="form-label">Главное фото (Обложка)</label>
                            <input type="file" class="form-control" name="image" id="image" accept="image/*">
                            <div class="form-text">Это фото будет видно в каталоге.</div>
                        </div>

                        <!-- 2. Галерея (Мультизагрузка) -->
                        <div class="mb-3">
                            <label for="photos" class="form-label">Дополнительные фото (Галерея)</label>
                            <!-- name="photos[]" и multiple - ключевые моменты -->
                            <input type="file" class="form-control" name="photos[]" id="photos" multiple accept="image/*">
                            <div class="form-text text-primary">💡 Совет: Зажмите <strong>Ctrl</strong> (или Cmd), чтобы выбрать сразу много файлов.</div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                             <label for="year" class="form-label">Год выпуска <span class="text-danger">*</span></label>
                             <input type="number" class="form-control" name="year" id="year" placeholder="2022" value="{{ old('year') }}" required>
                        </div>
                        <div class="col-md-4">
                             <label for="price" class="form-label">Цена ($) <span class="text-danger">*</span></label>
                             <input type="number" class="form-control" name="price" id="price" placeholder="15000" value="{{ old('price') }}" required>
                        </div>
                        <div class="col-md-4">
                             <label for="mileage" class="form-label">Пробег (км)</label>
                             <input type="number" class="form-control" name="mileage" id="mileage" placeholder="0" value="{{ old('mileage') }}">
                        </div>
                    </div>

                    <!-- Выбор Цвета -->
                    <div class="mb-3">
                        <label for="color" class="form-label">Цвет автомобиля</label>
                        @php
                            $colors = ['Белый', 'Черный', 'Серебристый', 'Серый', 'Красный', 'Синий', 'Зеленый', 'Коричневый', 'Бежевый', 'Желтый', 'Оранжевый', 'Фиолетовый'];
                        @endphp
                        <select class="form-select" name="color" id="color">
                            <option value="" selected disabled>-- Выберите цвет --</option>
                            @foreach($colors as $colorOption)
                                <option value="{{ $colorOption }}" {{ old('color') == $colorOption ? 'selected' : '' }}>
                                    {{ $colorOption }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Описание</label>
                        <textarea class="form-control" name="description" id="description" rows="3" placeholder="Состояние отличное, один владелец...">{{ old('description') }}</textarea>
                    </div>

                    <div class="d-grid gap-2 mt-4">
                        <button type="submit" class="btn btn-success btn-lg">Сохранить объявление</button>
                        <a href="{{ route('cars.index') }}" class="btn btn-secondary">Отмена</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection