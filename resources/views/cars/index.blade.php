@extends('layout')

@section('title', 'Каталог автомобилей')

@section('content')
<div class="container">
    
    <!-- СЛАЙДЕР БАННЕРОВ -->
    @if(isset($banners) && $banners->count() > 0)
        <div id="promoCarousel" class="carousel slide mb-5 shadow rounded overflow-hidden" data-bs-ride="carousel">
            <div class="carousel-indicators">
                @foreach($banners as $key => $banner)
                    <button type="button" data-bs-target="#promoCarousel" data-bs-slide-to="{{ $key }}" class="{{ $key == 0 ? 'active' : '' }}"></button>
                @endforeach
            </div>
            <div class="carousel-inner">
                @foreach($banners as $key => $banner)
                    <div class="carousel-item {{ $key == 0 ? 'active' : '' }}" data-bs-interval="3000">
                        <div style="height: 400px; background: black;">
                            <img src="{{ asset('storage/' . $banner->image) }}" class="d-block w-100 h-100" style="object-fit: cover; opacity: 0.7;" alt="Banner">
                        </div>
                        @if($banner->title || $banner->text)
                            <div class="carousel-caption d-none d-md-block">
                                <h2 class="fw-bold">{{ $banner->title }}</h2>
                                <p class="fs-5">{{ $banner->text }}</p>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    @endif


    <!-- ПАНЕЛЬ (Фильтры + Сортировка) -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 bg-body-tertiary p-3 rounded shadow-sm border">
        
        <button class="btn btn-primary d-flex align-items-center gap-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#filterCanvas">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-sliders" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M11.5 2a1.5 1.5 0 1 0 0 3 1.5 1.5 0 0 0 0-3zM9.05 3a2.5 2.5 0 0 1 4.9 0H16v1h-2.05a2.5 2.5 0 0 1-4.9 0H0V3h9.05zM4.5 7a1.5 1.5 0 1 0 0 3 1.5 1.5 0 0 0 0-3zM2.05 8a2.5 2.5 0 0 1 4.9 0H16v1H6.95a2.5 2.5 0 0 1-4.9 0H0V8h2.05zm9.45 4a1.5 1.5 0 1 0 0 3 1.5 1.5 0 0 0 0-3zm-2.45 1a2.5 2.5 0 0 1 4.9 0H16v1h-2.05a2.5 2.5 0 0 1-4.9 0H0v-1h9.05z"/>
            </svg>
            Фильтры
            
            @php
                $filterCount = 0;
                if(request('brand')) $filterCount++;
                if(request('min_price') || request('max_price')) $filterCount++;
                if(request('min_year') || request('max_year')) $filterCount++;
            @endphp
            @if($filterCount > 0)
                <span class="badge bg-light text-primary rounded-pill">{{ $filterCount }}</span>
            @endif
        </button>

        <div class="d-flex align-items-center gap-3 mt-2 mt-md-0">
            <span class="text-muted fw-bold small d-none d-sm-inline">Найдено: {{ $cars->count() }}</span>
            
            <form action="{{ route('cars.index') }}" method="GET" class="d-flex align-items-center">
                @foreach(request()->except('sort') as $key => $value)
                    <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                @endforeach

                <select name="sort" class="form-select form-select-sm border-0 bg-transparent" style="width: 170px; cursor: pointer;" onchange="this.form.submit()">
                    <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Сначала новые</option>
                    <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Сначала старые</option>
                    <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Сначала дешевые</option>
                    <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Сначала дорогие</option>
                </select>
            </form>
        </div>
    </div>

    <!-- СПИСОК МАШИН -->
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 row-cols-xl-4 g-4">
        @forelse ($cars as $car)
            <div class="col">
                <div class="card h-100 shadow-sm border-0">
                    <div class="position-absolute top-0 start-0 p-2 z-1">
                        @if($car->is_sold)
                            <span class="badge bg-danger shadow">ПРОДАНО</span>
                        @elseif($car->created_at->diffInDays() < 3)
                            <span class="badge bg-success shadow">NEW</span>
                        @endif
                    </div>

                    <div style="height: 200px; overflow: hidden; position: relative;" class="bg-secondary bg-opacity-10">
                        @if($car->image)
                            <img src="{{ asset('storage/' . $car->image) }}" class="w-100 h-100" style="object-fit: cover; transition: transform 0.3s;" alt="...">
                        @else
                            <img src="https://placehold.co/600x400?text=Car+Shop" class="w-100 h-100" style="object-fit: cover; opacity: 0.5;">
                        @endif
                    </div>
                    
                    <div class="card-body">
                        <h5 class="card-title fw-bold text-truncate">{{ $car->brand }} {{ $car->model }}</h5>
                        <p class="card-text text-muted small mb-2">
                            {{ $car->year }} г. • {{ $car->mileage ? number_format($car->mileage).' км' : 'Новая' }}
                        </p>
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <span class="text-primary fw-bold fs-5">${{ number_format($car->price, 0, '.', ' ') }}</span>
                            @php
                                $colorMap = ['Белый'=>'#fff', 'Черный'=>'#000', 'Красный'=>'#dc3545', 'Синий'=>'#0d6efd', 'Серебристый'=>'#c0c0c0', 'Серый'=>'#808080', 'Зеленый'=>'#198754', 'Желтый'=>'#ffc107', 'Бежевый'=>'#F5F5DC'];
                                $hex = $colorMap[$car->color] ?? null;
                                $border = ($hex == '#fff' || $hex == '#F5F5DC') ? 'border: 1px solid #ccc;' : '';
                            @endphp
                            @if($hex)
                                <div style="width: 15px; height: 15px; background: {{$hex}}; border-radius: 50%; {{ $border }}" title="{{ $car->color }}"></div>
                            @endif
                        </div>
                    </div>
                    
                    <div class="card-footer bg-transparent border-top-0 pt-0 pb-3">
                        <a href="{{ route('cars.show', $car->id) }}" class="btn btn-outline-primary w-100">Подробнее</a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-light text-center py-5 border">
                    <h2 class="text-muted">🚗</h2>
                    <h4>Ничего не найдено</h4>
                    <p>Попробуйте сбросить фильтры.</p>
                    <a href="{{ route('cars.index') }}" class="btn btn-dark">Сбросить фильтры</a>
                </div>
            </div>
        @endforelse
    </div>

</div>

<!-- OFFCANVAS (Скрытые фильтры) -->
<div class="offcanvas offcanvas-start" tabindex="-1" id="filterCanvas">
    <div class="offcanvas-header bg-body-tertiary">
        <h5 class="offcanvas-title fw-bold">🔍 Фильтрация</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body">
        <form action="{{ route('cars.index') }}" method="GET">
            <input type="hidden" name="sort" value="{{ request('sort') }}">

            <div class="mb-4">
                <label class="form-label fw-bold">Марка авто</label>
                <select name="brand" class="form-select form-select-lg" onchange="this.form.submit()">
                    <option value="">Все марки</option>
                    @foreach($brands as $brand)
                        <option value="{{ $brand }}" {{ request('brand') == $brand ? 'selected' : '' }}>{{ $brand }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label class="form-label fw-bold">Модель</label>
                <select name="model" class="form-select" {{ !request('brand') ? 'disabled' : '' }}>
                    <option value="">Все модели</option>
                    @foreach($models as $model)
                        <option value="{{ $model }}" {{ request('model') == $model ? 'selected' : '' }}>{{ $model }}</option>
                    @endforeach
                </select>
            </div>

            <hr>

            <div class="mb-4">
                <label class="form-label fw-bold">Цена ($)</label>
                <div class="row g-2">
                    <div class="col-6">
                        <div class="form-floating">
                            <input type="number" name="min_price" class="form-control" id="minPrice" placeholder="0" value="{{ request('min_price') }}">
                            <label for="minPrice">От</label>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-floating">
                            <input type="number" name="max_price" class="form-control" id="maxPrice" placeholder="Max" value="{{ request('max_price') }}">
                            <label for="maxPrice">До</label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label fw-bold">Год выпуска</label>
                <div class="input-group">
                    <input type="number" name="min_year" class="form-control" placeholder="1990" value="{{ request('min_year') }}">
                    <span class="input-group-text">-</span>
                    <input type="number" name="max_year" class="form-control" placeholder="{{ date('Y') }}" value="{{ request('max_year') }}">
                </div>
            </div>

            <div class="d-grid gap-2 mt-5">
                <button type="submit" class="btn btn-primary btn-lg">Применить</button>
                <a href="{{ route('cars.index') }}" class="btn btn-outline-secondary">Сбросить</a>
            </div>
        </form>
    </div>
</div>
@endsection