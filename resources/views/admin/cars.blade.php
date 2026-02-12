@extends('admin_layout')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Управление автомобилями</h2>
        <a href="{{ route('cars.create') }}" class="btn btn-primary">+ Добавить машину</a>
    </div>

    <table class="table table-dark table-hover align-middle">
        <thead>
            <tr>
                <th>Фото</th>
                <th>Название</th>
                <th>Год</th>
                <th>Цена</th>
                <th>Статус</th>
                <th>Действия</th>
            </tr>
        </thead>
        <tbody>
            @foreach($cars as $car)
                <tr>
                    <td>
                        @if($car->image)
                            <img src="{{ asset('storage/'.$car->image) }}" width="50" height="40" style="object-fit: cover;">
                        @else
                            <span class="text-muted">Нет</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('cars.show', $car->id) }}" class="text-white text-decoration-underline" target="_blank">
                            {{ $car->brand }} {{ $car->model }}
                        </a>
                    </td>
                    <td>{{ $car->year }}</td>
                    <td>${{ number_format($car->price) }}</td>
                    <td>
                        @if($car->is_sold)
                            <span class="badge bg-danger">Продано</span>
                        @else
                            <span class="badge bg-success">В наличии</span>
                        @endif
                    </td>
                    <td>
                        <div class="d-flex gap-2">
                            <a href="{{ route('cars.edit', $car->id) }}" class="btn btn-sm btn-warning">✏</a>
                            
                            <form action="{{ route('cars.destroy', $car->id) }}" method="POST" onsubmit="return confirm('Удалить?');">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger">🗑</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    
    <!-- Пагинация -->
    <div class="mt-3">
        {{ $cars->links() }}
    </div>
@endsection