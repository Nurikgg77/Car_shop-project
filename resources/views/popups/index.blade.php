@extends('layout')

@section('title', 'Управление Pop-up рекламой')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>⚡ Всплывающая реклама (Popup)</h2>
        <a href="{{ route('popups.create') }}" class="btn btn-primary">+ Добавить рекламу</a>
    </div>

    <div class="alert alert-info">
        На сайте будет показан <strong>самый свежий активный</strong> попап.
    </div>

    <table class="table table-bordered align-middle">
        <thead class="table-light">
            <tr>
                <th>Превью</th>
                <th>Дата создания</th>
                <th>Статус</th>
                <th>Действия</th>
            </tr>
        </thead>
        <tbody>
            @forelse($popups as $popup)
                <tr>
                    <td style="width: 150px;">
                        <img src="{{ asset('storage/' . $popup->image) }}" width="100" class="rounded">
                    </td>
                    <td>{{ $popup->created_at->format('d.m.Y H:i') }}</td>
                    <td>
                        <form action="{{ route('popups.update', $popup->id) }}" method="POST">
                            @csrf @method('PUT')
                            <button class="btn btn-sm {{ $popup->is_active ? 'btn-success' : 'btn-secondary' }}">
                                {{ $popup->is_active ? 'Активен' : 'Отключен' }}
                            </button>
                        </form>
                    </td>
                    <td>
                        <form action="{{ route('popups.destroy', $popup->id) }}" method="POST" onsubmit="return confirm('Удалить?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-danger btn-sm">Удалить</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="4" class="text-center">Нет загруженной рекламы</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection