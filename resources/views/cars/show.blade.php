@extends('layout')

@section('title', $car->brand . ' ' . $car->model)

@section('content')
<div class="container">
    <a href="{{ route('cars.index') }}" class="btn btn-outline-secondary mb-4">&larr; Вернуться к списку</a>

    <div class="card shadow-lg border-0 overflow-hidden">
        <div class="row g-0">
            <!-- ФОТО -->
            <div class="col-md-6 bg-light d-flex align-items-center justify-content-center position-relative">
                @if($car->image)
                    <img src="{{ asset('storage/' . $car->image) }}" class="img-fluid w-100" style="object-fit: cover; min-height: 400px; max-height: 600px;" alt="{{ $car->brand }}">
                @else
                    <img src="https://placehold.co/600x400?text=No+Image" class="img-fluid w-100" alt="Нет фото">
                @endif
                
                @if($car->is_sold)
                    <div class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center" style="background: rgba(0,0,0,0.5);">
                        <span class="badge bg-danger fs-1 px-4 py-2 text-uppercase" style="transform: rotate(-15deg); border: 4px solid white;">ПРОДАНО</span>
                    </div>
                @endif
            </div>

            <!-- ИНФОРМАЦИЯ -->
            <div class="col-md-6">
                <div class="card-body p-4 p-md-5">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <h1 class="card-title fw-bold mb-0">{{ $car->brand }} {{ $car->model }}</h1>
                            <span class="text-muted small">ID: #{{ $car->id }}</span>
                        </div>
                        @if(!$car->is_sold)
                            <span class="badge bg-success fs-6">В наличии</span>
                        @endif
                    </div>
                    
                    <h2 class="text-primary fw-bold mb-4">${{ number_format($car->price, 0, '.', ' ') }}</h2>
                    
                    <p class="card-text text-secondary mb-4" style="line-height: 1.6;">
                        {{ $car->description ?? 'Описание отсутствует.' }}
                    </p>

                    <hr class="my-4">

                    <!-- Сетка характеристик -->
                    <div class="row row-cols-2 g-3 mb-4">
                        <div class="col">
                            <div class="border rounded p-3 bg-light">
                                <div class="text-muted small">Год выпуска</div>
                                <div class="fw-bold">{{ $car->year }}</div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="border rounded p-3 bg-light">
                                <div class="text-muted small">Пробег</div>
                                <div class="fw-bold">{{ $car->mileage ? number_format($car->mileage) . ' км' : 'Новая' }}</div>
                            </div>
                        </div>
                        
                        <!-- Блок с Цветом (Кружок) -->
                        <div class="col">
                            <div class="border rounded p-3 bg-light h-100">
                                <div class="text-muted small">Цвет</div>
                                <div class="d-flex align-items-center mt-1">
                                    @php
                                        $colorMap = [
                                            'Белый' => '#ffffff', 'Черный' => '#000000', 'Серебристый' => '#c0c0c0', 
                                            'Серый' => '#808080', 'Красный' => '#dc3545', 'Синий' => '#0d6efd', 
                                            'Зеленый' => '#198754', 'Коричневый' => '#8B4513', 'Бежевый' => '#F5F5DC', 
                                            'Желтый' => '#ffc107', 'Оранжевый' => '#fd7e14', 'Фиолетовый' => '#6f42c1'
                                        ];
                                        $cssColor = $colorMap[$car->color] ?? null;
                                        $border = ($cssColor == '#ffffff' || $cssColor == '#F5F5DC') ? 'border: 1px solid #ccc;' : '';
                                    @endphp

                                    @if($cssColor)
                                        <span style="display:inline-block; width: 16px; height: 16px; background-color: {{ $cssColor }}; border-radius: 50%; margin-right: 8px; {{ $border }}"></span>
                                    @endif
                                    <span class="fw-bold">{{ $car->color ?? '—' }}</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col">
                            <div class="border rounded p-3 bg-light">
                                <div class="text-muted small">Дата добавления</div>
                                <div class="fw-bold">{{ $car->created_at->format('d.m.Y') }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Кнопки: Админ видит удаление, Гость видит "Связаться" -->
                    @auth
                        <div class="p-3 bg-warning bg-opacity-10 rounded border border-warning mb-3">
                            <small class="text-warning fw-bold text-uppercase">Панель администратора</small>
                            <div class="d-grid gap-2 d-md-flex mt-2">
                                <a href="{{ route('cars.edit', $car->id) }}" class="btn btn-warning flex-grow-1">✏ Редактировать</a>
                                
                                <form action="{{ route('cars.destroy', $car->id) }}" method="POST" class="flex-grow-1" onsubmit="return confirm('Удалить эту машину? Действие необратимо.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger w-100">🗑 Удалить</button>
                                </form>
                            </div>
                        </div>
                    @endauth

                    @guest
                        <div class="d-grid">
                            <button class="btn btn-success btn-lg shadow-sm" onclick="alert('Звоните по номеру: +998 90 123 45 67')">
                                📞 Связаться с продавцом
                            </button>
                        </div>
                    @endguest
                    
                </div>
            </div>
        </div>
    </div>
</div>
@endsection