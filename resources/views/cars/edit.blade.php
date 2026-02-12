@extends('admin_layout')

@section('title', 'Редактировать ' . $car->brand)

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow border-0">
            <div class="card-header bg-warning text-dark">
                <h4 class="mb-0">Редактировать автомобиль</h4>
            </div>
            <div class="card-body p-4">
                
                <form action="{{ route('cars.update', $car->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT') 

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="brand" class="form-label">Марка</label>
                            <input type="text" class="form-control" name="brand" value="{{ old('brand', $car->brand) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label for="model" class="form-label">Модель</label>
                            <input type="text" class="form-control" name="model" value="{{ old('model', $car->model) }}" required>
                        </div>
                    </div>

                    <!-- Блок с фото -->
                    <div class="mb-3 p-3 bg-light rounded">
                        <label class="form-label fw-bold">Фотография</label>
                        <div class="d-flex align-items-center">
                            @if($car->image)
                                <img src="{{ asset('storage/' . $car->image) }}" width="100" height="70" class="img-thumbnail me-3" style="object-fit: cover">
                            @else
                                <span class="me-3 text-muted">Нет фото</span>
                            @endif
                            
                            <div class="flex-grow-1">
                                <label for="image" class="form-label small text-muted">Загрузить новое (заменит старое)</label>
                                <input type="file" class="form-control" name="image" id="image">
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                             <label for="year" class="form-label">Год</label>
                             <input type="number" class="form-control" name="year" value="{{ old('year', $car->year) }}" required>
                        </div>
                        <div class="col-md-4">
                             <label for="price" class="form-label">Цена ($)</label>
                             <input type="number" class="form-control" name="price" value="{{ old('price', $car->price) }}" required>
                        </div>
                        <div class="col-md-4">
                             <label for="mileage" class="form-label">Пробег</label>
                             <input type="number" class="form-control" name="mileage" value="{{ old('mileage', $car->mileage) }}">
                        </div>
                    </div>

                    <!-- Выбор Цвета (с предустановленным значением) -->
                    <div class="mb-3">
                        <label for="color" class="form-label">Цвет автомобиля</label>
                        @php
                            $colors = ['Белый', 'Черный', 'Серебристый', 'Серый', 'Красный', 'Синий', 'Зеленый', 'Коричневый', 'Бежевый', 'Желтый', 'Оранжевый', 'Фиолетовый'];
                        @endphp
                        <select class="form-select" name="color" id="color">
                            <option value="">Не указан</option>
                            @foreach($colors as $colorOption)
                                <option value="{{ $colorOption }}" {{ (old('color', $car->color) == $colorOption) ? 'selected' : '' }}>
                                    {{ $colorOption }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Чекбокс Продано -->
                    <div class="mb-3">
                        <div class="form-check form-switch p-3 border rounded bg-light">
                            <input type="hidden" name="is_sold" value="0">
                            <input class="form-check-input ms-0 me-2" type="checkbox" id="is_sold" name="is_sold" value="1" {{ $car->is_sold ? 'checked' : '' }}>
                            <label class="form-check-label fw-bold" for="is_sold">Отметить как "ПРОДАНО"</label>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Описание</label>
                        <textarea class="form-control" name="description" rows="3">{{ old('description', $car->description) }}</textarea>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-warning btn-lg">Обновить данные</button>
                        <a href="{{ route('cars.show', $car->id) }}" class="btn btn-secondary">Отмена</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection