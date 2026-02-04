@extends('layout')

@section('title', 'Каталог автомобилей')

@section('content')
<div class="container">
    <div class="row">
        
        <!-- ЛЕВАЯ КОЛОНКА: ФИЛЬТРЫ -->
        <div class="col-lg-3 mb-4">
            <div class="card shadow-sm border-0 sticky-top" style="top: 20px; z-index: 100;">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold">🔍 Фильтры</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('cars.index') }}" method="GET">
                        
                        <!-- Марка -->
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-muted">Марка</label>
                            <select name="brand" class="form-select" onchange="this.form.submit()">
                                <option value="">Все марки</option>
                                @foreach($brands as $brand)
                                    <option value="{{ $brand }}" {{ request('brand') == $brand ? 'selected' : '' }}>
                                        {{ $brand }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Модель (активен, если выбрана марка) -->
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-muted">Модель</label>
                            <select name="model" class="form-select" {{ !request('brand') ? 'disabled' : '' }}>
                                <option value="">Все модели</option>
                                @foreach($models as $model)
                                    <option value="{{ $model }}" {{ request('model') == $model ? 'selected' : '' }}>
                                        {{ $model }}
                                    </option>
                                @endforeach
                            </select>
                            @if(!request('brand'))
                                <small class="text-muted" style="font-size: 10px;">Сначала выберите марку</small>
                            @endif
                        </div>

                        <hr>

                        <!-- Цена -->
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-muted">Цена ($)</label>
                            <div class="input-group mb-2">
                                <span class="input-group-text bg-white text-muted">От</span>
                                <input type="number" name="min_price" class="form-control" value="{{ request('min_price') }}" placeholder="0">
                            </div>
                            <div class="input-group">
                                <span class="input-group-text bg-white text-muted">До</span>
                                <input type="number" name="max_price" class="form-control" value="{{ request('max_price') }}" placeholder="max">
                            </div>
                        </div>

                        <!-- Год -->
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-muted">Год выпуска</label>
                            <div class="d-flex gap-2">
                                <input type="number" name="min_year" class="form-control" placeholder="C 1990" value="{{ request('min_year') }}">
                                <input type="number" name="max_year" class="form-control" placeholder="По {{ date('Y') }}" value="{{ request('max_year') }}">
                            </div>
                        </div>

                        <div class="d-grid gap-2 mt-4">
                            <button type="submit" class="btn btn-primary">Применить</button>
                            <a href="{{ route('cars.index') }}" class="btn btn-outline-secondary btn-sm">Сбросить всё</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- ПРАВАЯ КОЛОНКА: СПИСОК МАШИН -->
        <div class="col-lg-9">
            
            <!-- Верхняя панель сортировки -->
            <div class="d-flex justify-content-between align-items-center mb-4 bg-white p-3 rounded shadow-sm">
                <h4 class="mb-0">Найдено: {{ $cars->count() }} авто</h4>
                
                <form action="{{ route('cars.index') }}" method="GET" class="d-flex align-items-center">
                    <!-- Сохраняем текущие фильтры при сортировке -->
                    @foreach(request()->except('sort') as $key => $value)
                        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                    @endforeach

                    <label class="me-2 text-muted small">Сортировка:</label>
                    <select name="sort" class="form-select form-select-sm" style="width: 180px;" onchange="this.form.submit()">
                        <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Сначала новые</option>
                        <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Сначала старые</option>
                        <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Сначала дешевые</option>
                        <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Сначала дорогие</option>
                    </select>
                </form>
            </div>

            <!-- Сетка карточек -->
            <div class="row row-cols-1 row-cols-md-2 row-cols-xl-3 g-4">
                @forelse ($cars as $car)
                    <div class="col">
                        <div class="card h-100 shadow-sm border-0 transition-hover">
                            <!-- БЕЙДЖИ -->
                            <div class="position-absolute top-0 start-0 p-2 z-1">
                                @if($car->is_sold)
                                    <span class="badge bg-danger">ПРОДАНО</span>
                                @elseif($car->created_at->diffInDays() < 3)
                                    <span class="badge bg-success">NEW</span>
                                @endif
                            </div>

                            <!-- ФОТО -->
                            <div style="height: 200px; overflow: hidden; background: #eee;">
                                @if($car->image)
                                    <img src="{{ asset('storage/' . $car->image) }}" class="w-100 h-100" style="object-fit: cover;" alt="...">
                                @else
                                    <img src="https://placehold.co/600x400?text=Car+Shop" class="w-100 h-100" style="object-fit: cover; opacity: 0.5;">
                                @endif
                            </div>
                            
                            <!-- ТЕЛО КАРТОЧКИ -->
                            <div class="card-body">
                                <h5 class="card-title fw-bold text-truncate">{{ $car->brand }} {{ $car->model }}</h5>
                                <p class="card-text text-muted small mb-2">
                                    {{ $car->year }} г. • {{ $car->mileage ? number_format($car->mileage).' км' : 'Новая' }}
                                </p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="text-success fw-bold fs-5">${{ number_format($car->price, 0, '.', ' ') }}</span>
                                </div>
                            </div>
                            
                            <!-- ФУТЕР КАРТОЧКИ -->
                            <div class="card-footer bg-white border-top-0 pt-0 pb-3">
                                <a href="{{ route('cars.show', $car->id) }}" class="btn btn-outline-primary w-100">Подробнее</a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="alert alert-warning text-center py-5">
                            <h4>😕 Ничего не найдено</h4>
                            <p>Попробуйте изменить параметры фильтрации.</p>
                            <a href="{{ route('cars.index') }}" class="btn btn-dark">Сбросить фильтры</a>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection