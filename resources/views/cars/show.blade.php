@extends('layout')

@section('title', $car->brand . ' ' . $car->model)

@section('content')
<div class="container">
    <a href="{{ route('cars.index') }}" class="btn btn-outline-secondary mb-4">&larr; Вернуться к списку</a>

    <div class="card shadow-lg border-0 overflow-hidden">
        <div class="row g-0">
            
            <!-- ЛЕВАЯ КОЛОНКА: СЛАЙДЕР -->
            <div class="col-md-6 bg-dark d-flex align-items-center justify-content-center position-relative p-0">
                
                @php
                    $allImages = [];
                    if($car->image) {
                        $allImages[] = $car->image;
                    }
                    foreach($car->images as $galleryImg) {
                        $allImages[] = $galleryImg->image_path;
                    }
                @endphp

                @if(count($allImages) > 0)
                    <div id="carGallery" class="carousel slide w-100" data-bs-ride="carousel">
                        
                        @if(count($allImages) > 1)
                            <div class="carousel-indicators">
                                @foreach($allImages as $index => $img)
                                    <button type="button" data-bs-target="#carGallery" data-bs-slide-to="{{ $index }}" class="{{ $index == 0 ? 'active' : '' }}"></button>
                                @endforeach
                            </div>
                        @endif

                        <div class="carousel-inner">
                            @foreach($allImages as $index => $path)
                                <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                                    <img src="{{ asset('storage/' . $path) }}" class="d-block w-100" style="object-fit: contain; height: 500px; background-color: #000;" alt="Фото">
                                </div>
                            @endforeach
                        </div>

                        @if(count($allImages) > 1)
                            <button class="carousel-control-prev" type="button" data-bs-target="#carGallery" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon p-3 rounded-circle bg-dark bg-opacity-50" aria-hidden="true"></span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#carGallery" data-bs-slide="next">
                                <span class="carousel-control-next-icon p-3 rounded-circle bg-dark bg-opacity-50" aria-hidden="true"></span>
                            </button>
                        @endif
                    </div>
                @else
                    <img src="https://placehold.co/600x400?text=No+Image" class="img-fluid w-100" style="height: 500px; object-fit: cover;" alt="Нет фото">
                @endif
                
                @if($car->is_sold)
                    <div class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center" style="background: rgba(0,0,0,0.5); z-index: 100;">
                        <span class="badge bg-danger fs-1 px-4 py-2 text-uppercase" style="transform: rotate(-15deg); border: 4px solid white;">ПРОДАНО</span>
                    </div>
                @endif
            </div>

            <!-- ПРАВАЯ КОЛОНКА: ИНФОРМАЦИЯ -->
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
                        
                        <div class="col">
                            <div class="border rounded p-3 bg-light h-100">
                                <div class="text-muted small">Цвет</div>
                                <div class="d-flex align-items-center mt-1">
                                    @php
                                        $colorMap = ['Белый'=>'#fff', 'Черный'=>'#000', 'Красный'=>'#dc3545', 'Синий'=>'#0d6efd', 'Серебристый'=>'#c0c0c0', 'Серый'=>'#808080', 'Зеленый'=>'#198754', 'Желтый'=>'#ffc107', 'Бежевый'=>'#F5F5DC'];
                                        $hex = $colorMap[$car->color] ?? null;
                                        $border = ($hex == '#fff' || $hex == '#F5F5DC') ? 'border: 1px solid #ccc;' : '';
                                    @endphp
                                    @if($hex)
                                        <span style="display:inline-block; width: 16px; height: 16px; background-color: {{ $hex }}; border-radius: 50%; {{ $border }}" title="{{ $car->color }}"></span>
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

                    <!-- ТОЛЬКО КНОПКА СВЯЗИ (Управления нет) -->
                    <div class="d-grid">
                        <button class="btn btn-success btn-lg shadow-sm" onclick="alert('Звоните по номеру: +998 90 123 45 67')">
                            📞 Связаться с продавцом
                        </button>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</div>
@endsection