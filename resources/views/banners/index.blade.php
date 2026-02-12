@extends('layout')

@section('title', 'Управление баннерами')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>📢 Рекламные баннеры</h2>
        <a href="{{ route('banners.create') }}" class="btn btn-primary">+ Добавить баннер</a>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered bg-white align-middle">
            <thead class="table-light">
                <tr>
                    <th>Фото</th>
                    <th>Заголовок</th>
                    <th>Статус</th>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody>
                @forelse($banners as $banner)
                    <tr>
                        <td style="width: 150px;">
                            <img src="{{ asset('storage/' . $banner->image) }}" width="120" class="rounded">
                        </td>
                        <td>
                            <strong>{{ $banner->title ?? 'Без заголовка' }}</strong><br>
                            <small class="text-muted">{{ $banner->text }}</small>
                        </td>
                        <td>
                            <!-- Форма переключения статуса -->
                            <form action="{{ route('banners.update', $banner->id) }}" method="POST">
                                @csrf @method('PUT')
                                <button class="btn btn-sm {{ $banner->is_active ? 'btn-success' : 'btn-secondary' }}">
                                    {{ $banner->is_active ? 'Активен' : 'Скрыт' }}
                                </button>
                            </form>
                        </td>
                        <td>
                            <form action="{{ route('banners.destroy', $banner->id) }}" method="POST" onsubmit="return confirm('Удалить?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-danger btn-sm">Удалить</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="text-center p-4">Баннеров пока нет</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection